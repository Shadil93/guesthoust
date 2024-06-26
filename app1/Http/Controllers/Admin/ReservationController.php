<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\BookingActions;
use App\Constants\Status;
use Carbon\Carbon;
use App\Models\{
    RoomType,
    Booking,
    BookedRoom,
    Room,
    User,
    Deposit
};


class ReservationController extends Controller
{

    use BookingActions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Booking::query();
        $request = request();
        if ($request->search) {
            $search = $request->search;
            $query = $query->where(function ($q) use ($search) {
                $q->where('booking_number', $search)
                    ->orWhere(function ($q) use ($search) {
                        $q->whereHas('user', function ($user) use ($search) {
                            $user->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        })
                            ->orWhere('guest_details->name', 'like', "%$search%")
                            ->orWhere('guest_details->email', 'like', "%$search%");
                    });
            });
        }

        if ($request->check_in) {
            $query = $query->whereDate('check_in', $request->check_in);
        }
        if ($request->check_out) {
            $query = $query->whereDate('check_out', $request->check_out);
        }
        $bookings = $query->with('bookedRooms.room', 'user', 'activeBookedRooms', 'activeBookedRooms.room:id,room_number')
            ->latest()
            ->orderBy('check_in', 'asc')
            ->paginate(10);

        return view('admin.reservations.index',compact('bookings'));  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reservations.create',[
            "room_types" => RoomType::all()
        ]);
    }

    public function search(Request $request){
        
        $validator = Validator::make($request->all(), [
            'room_type' => 'required|exists:room_types,id',
            'date' => 'required|string',
            'rooms' => 'required|integer|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $date = explode('-', $request->date);

        $request->merge([
            'checkin_date'  => trim($date[0]),
            'checkout_date' => trim($date[1]),
        ]);

        $validator = Validator::make($request->all(), [
            'checkin_date'  => 'required|date_format:m/d/Y|after:yesterday',
            'checkout_date' => 'required|date_format:m/d/Y|after:checkin_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        
        $view = $this->getRooms($request);
        return response()->json(['html' => $view]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id'    => 'required|integer|gt:0',
            'guest_name'      => 'required',
            'email'           => 'nullable|email',
            'mobile'          => 'required|regex:/^([0-9]*)$/',
            'address'         => 'nullable|string',
            'id_card_type'    => 'required|string',
            'id_card_number'  => 'required|string',
            'room'            => 'required|array',
            'paid_amount'     => 'nullable|numeric|gte:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $guest = [];
        $guest['name'] = $request->guest_name;
        $guest['email'] = $request->email;
        $guest['mobile'] = $request->mobile;
        $guest['address'] = $request->address;
        $guest['id_card_type'] = $request->id_card_type;
        $guest['id_card_number'] = $request->id_card_number;

        $bookedRoomData = [];
        $totalFare      = 0;
        $tax            = 0;

        foreach ($request->room as $room) {
            $data      = [];
            $roomId    = explode('-', $room)[0];
            $bookedFor = explode('-', $room)[1];
            $isBooked  = BookedRoom::where('room_id', $roomId)->where('booked_for', $bookedFor)->exists();

            if ($isBooked) {
                return response()->json(['error' => 'Room has been booked']);
            }

            $room = Room::with('roomType')->find($roomId);

            if ($request->room_type_id != @$room->roomType->id) {
                return response()->json(['error' => 'Invalid room type selected']);
            }

            $data['booking_id']       = 0;
            $data['room_type_id']     = $room->room_type_id;
            $data['room_id']          = $room->id;
            $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d');
            $data['fare']             = $room->roomType->fare;
            $data['tax_charge']       = $room->roomType->fare * $tax / 100;
            $data['cancellation_fee'] = $room->roomType->cancellation_fee;
            $data['status']           = Status::ROOM_ACTIVE;
            $data['created_at']       = now();
            $data['updated_at']       = now();

            $bookedRoomData[] = $data;

            $totalFare += $room->roomType->fare;
        }


        $taxCharge = $totalFare * $tax / 100;

        if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
            return response()->json(['error' => 'Paying amount can\'t be greater than total amount']);
        }

        $booking                 = new Booking();
        $booking->booking_number = date('Ymdhis').rand(2,3);
        $booking->user_id        = @$user->id ?? 0;
        $booking->guest_details  = $guest;
        $booking->tax_charge     = $taxCharge;
        $booking->booking_fare   = $totalFare;
        $booking->paid_amount    = $request->paid_amount ?? 0;
        $booking->status         = 1;
        $booking->save();

        if ($request->paid_amount) {
            $booking->createPaymentLog($booking->paid_amount, 'RECEIVED');
        }

        $booking->createActionHistory('Advance Paid');

        foreach ($bookedRoomData as $key => $bookedRoom) {
            $bookedRoomData[$key]['booking_id'] = $booking->id;
        }

        BookedRoom::insert($bookedRoomData);

        $checkIn  = BookedRoom::where('booking_id', $booking->id)->min('booked_for');
        $checkout = BookedRoom::where('booking_id', $booking->id)->max('booked_for');

        $booking->check_in = $checkIn;
        $booking->check_out = Carbon::parse($checkout)->addDay()->toDateString();
        $booking->save();

        return response()->json(['success' => 'Room booked successfully']);
    }

    public function show($id){
        $booking = Booking::with([
            'bookedRooms',
            'activeBookedRooms:id,booking_id,room_id',
            'activeBookedRooms.room:id,room_number',
            'bookedRooms.room:id,room_type_id,room_number',
            'bookedRooms.room.roomType:id,name',
            'usedExtraService.room',
            'usedExtraService.extraService',
            'payments'
        ])->findOrFail($id);

        return view('admin.reservations.show', compact('booking'));
    }

    public function bookedRooms($id){
        $booking = Booking::findOrFail($id);
        $bookedRooms = BookedRoom::where('booking_id', $id)->with('booking.user', 'room.roomType')->orderBy('booked_for')->get()->groupBy('booked_for');
        return view('admin.reservations.booked_rooms', compact('bookedRooms', 'booking'));
    }

    public function checkin($id) {

        $booking = Booking::active()->findOrFail($id);
        return view('admin.reservations.checkin', compact('booking'));
    }

    public function checkinUpdate(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'id_card'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }


        $booking = Booking::active()->findOrFail($id);

        if ($booking->key_status == Status::ENABLE) {
            return back()->with('success', 'Keys have already been given to the guest');
        }

        if (now()->format('Y-m-d') < $booking->check_in) {
            return back()->with('success', 'You can\'t handover keys before the check-in date');
        }

        if (now()->format('Y-m-d') >= $booking->check_out) {
            return back()->with('success', 'You can\'t handover keys after the check-out date');
        }

        $filePath = '';
        if($request->file()) {
            $fileName = time().'_'.$request->id_card->getClientOriginalName();
            $filePath = $request->file('id_card')->storeAs('uploads', $fileName, 'public');
        }

        $booking->key_status = Status::ENABLE;
        $booking->checked_in_at = now();
        $booking->id_card = $filePath;
        $booking->save();

        $booking->createActionHistory('key_handover');

        return redirect()->route('reservations.index')->with('success', 'Key handover successfully');
    }

    public function checkOutPreview($id) {
        $booking           = Booking::active()->with('bookedRooms', 'payments', 'usedExtraService', 'user')->findOrFail($id);
        $totalFare         = $booking->bookedRooms->sum('fare');
        $totalTaxCharge    = $booking->bookedRooms->sum('tax_charge');
        $canceledFare      = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
        $returnedPayments  = $booking->payments->where('type', 'RETURNED');
        $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
        return view('admin.reservations.check_out', compact( 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments'));
    }

    public function checkOut($id) {
        $booking = Booking::active()->with('payments')->withSum('usedExtraService', 'total_amount')->findOrFail($id);

        if ($booking->check_out > now()->toDateString()) {
            return back()->with("error", "Checkout date for this booking is greater than now");
        }

        $due = $booking->total_amount - $booking->paid_amount;

        if ($due > 0) {
            return back()->with("error", "The guest should pay the payable amount first");
        }

        if ($due < 0) {
            return back()->with("error", "Refund the refundable amount to the guest first");
        }

        $booking->createActionHistory('checked_out');

        $booking->activeBookedRooms()->update(['status' => Status::BOOKING_CHECKOUT]);
        $booking->status = Status::BOOKING_CHECKOUT;
        $booking->checked_out_at = now();

        $booking->save();

        return redirect()->route('reservations.index')->with("success", "Booking checked out successfully");
    }

    public function paymentView($id) {
        $booking           = Booking::with('bookedRooms', 'payments', 'usedExtraService', 'user')->findOrFail($id);
        $totalFare         = $booking->bookedRooms->sum('fare');
        $totalTaxCharge    = $booking->bookedRooms->sum('tax_charge');
        $canceledFare      = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
        $returnedPayments  = $booking->payments->where('type', 'RETURNED');
        $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
        return view('admin.reservations.payment', compact( 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments'));
    }

    public function payment(Request $request, $id) {
        $request->validate([
            'amount' => 'required|numeric|gt:0'
        ]);

        $booking = Booking::findOrFail($id);
        $due     = $booking->total_amount - $booking->paid_amount;

        if ($request->amount > abs($due)) {
            $message = $due <= 0 ? 'Amount can\'t be greater than receivable amount' : 'Amount can\'t be greater than payable amount';
            return back()->withNotify("error",$message);
        }

        if ($due > 0) {
            return $this->receivePayment($booking, $request->amount);
        }

        return $this->returnPayment($booking, $request->amount);
    }

    protected function receivePayment($booking, $receivingAmount) {
        $this->deposit($booking, $receivingAmount);
        $booking->createPaymentLog($receivingAmount, 'RECEIVED');
        $booking->createActionHistory('payment_received');
        $booking->paid_amount += $receivingAmount;
        $booking->save();

        $notify[] = [];
        return back()->with("success", "Payment received successfully");
    }

    protected function returnPayment($booking, $receivingAmount) {
        $booking->createPaymentLog($receivingAmount, 'RETURNED');
        $booking->createActionHistory('payment_returned');

        $booking->paid_amount -= $receivingAmount;
        $booking->save();

        return back()->with("success", "Payment completed successfully");
    }

    protected function deposit($booking, $payableAmount) {
        $data = new Deposit();
        $data->user_id = $booking->user_id;
        $data->booking_id = $booking->id;
        $data->admin_id = auth()->user()->id;
        $data->amount = $payableAmount;
        $data->charge = 0;
        $data->final_amo = $payableAmount;
        $data->btc_amo = 0;
        $data->trx = $this->getTrx();
        $data->btc_wallet = "";
        $data->payment_try = 0;
        $data->status = Status::PAYMENT_SUCCESS;
        $data->save();
    }
    
    function getTrx($length = 12) {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function addExtraCharge(Request $request, $id) {
        $this->extraChargeValidation($request);

        $booking = Booking::findOrFail($id);
        $booking->extra_charge += $request->amount;
        $booking->save();
        $reason = $request->amount . ' added for ' . $request->reason;

        $booking->createActionHistory('extra_charge_added', $reason);

        return back()->with("success", "Extra charge added successfully");
    }

    public function subtractExtraCharge(Request $request, $id) {
        $this->extraChargeValidation($request);

        $booking = Booking::findOrFail($id);

        if ($request->amount + $booking->extra_charge_subtracted > $booking->extra_charge) {
            return back()->with("error", "Subtracted amount should be less than or equal to booking extra charge");
        }

        $booking->extra_charge_subtracted += $request->amount;
        $booking->save();

        $reason = $request->amount . ' subtracted for ' . $request->reason;

        $booking->createActionHistory('extra_charge_subtracted', $reason);

        return back()->with("success", "Extra charge subtracted successfully");
    }

    private function extraChargeValidation($request) {
        $request->validate([
            'amount' => 'required|numeric|gte:0',
            'reason' => 'required|string|max:255',
        ]);
    }

}
