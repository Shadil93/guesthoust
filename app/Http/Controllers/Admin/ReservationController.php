<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\BookingActions;
use App\Constants\Status;

use App\Mail\ReservationSuccess;
use Carbon\Carbon;
use App\Models\{
    RoomType,
    Booking,
    BookedRoom,
    CautionVoucher,
    Room,
    User,
    Deposit,
    PaymentLog,
    PaymentMode
};
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class ReservationController extends Controller
{

    use BookingActions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \DB::enableQueryLog();
        
        $query = Booking::query();
        $request = request();
        
        $search = $request->search;
        $dateRange = $request->date;
        
        if ($search || $dateRange) {
            if ($search) {
                // Search logic remains the same
            }
            
            if ($dateRange) {
                $dateParts = explode(' - ', $dateRange);
                $checkInDate = trim($dateParts[0]);
                $checkOutDate = isset($dateParts[1]) ? trim($dateParts[1]) : null;
                
                // Convert dates to proper format
                $checkInDate = date('Y-m-d', strtotime($checkInDate));
                $checkOutDate = $checkOutDate ? date('Y-m-d', strtotime($checkOutDate)) : null;
                
                // Apply filtering based on provided dates
                if ($checkOutDate) {
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereBetween('check_in', [$checkInDate, $checkOutDate])
                          ->orWhereBetween('check_out', [$checkInDate, $checkOutDate]);
                    });
                } else {
                    // Only check-in date is provided
                    $query->whereDate('check_in', '=', $checkInDate);
                }
            }
        }
        
        $bookings = $query->with('bookedRooms.room', 'user', 'activeBookedRooms', 'activeBookedRooms.room:id,room_number')
                          ->latest()
                          ->orderBy('check_in', 'asc')
                          ->paginate(10);
        $queries = \DB::getQueryLog();
        
        return view('admin.reservations.index', compact('bookings'));
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reservations.create',[
            "room_types" => RoomType::all(),
            "payment_methods" => PaymentMode::all()
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

        $checkin_date = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
        $checkout_date = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');

        $request->merge([
            'checkin_date'  => $checkin_date,
            'checkout_date' => $checkout_date,
        ]);

        $validator = Validator::make($request->all(), [
            'checkin_date'  => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
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
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'room_type_id'    => 'required|integer|gt:0',
    //         'guest_name'      => 'required',
    //         'email'           => 'nullable|email',
    //         'mobile'          => 'required|regex:/^([0-9]*)$/',
    //         'address'         => 'nullable|string',
    //         'id_card_type'    => 'required|string',
    //         'id_card_number'  => 'required|string',
    //         'room'            => 'required|array',
    //         'paid_amount'     => 'nullable|numeric|gte:0'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->all()]);
    //     }
    //     $guest = [];
    //     $guest['name'] = $request->guest_name;
    //     $guest['email'] = $request->email;
    //     $guest['mobile'] = $request->mobile;
    //     $guest['address'] = $request->address;
    //     $guest['id_card_type'] = $request->id_card_type;
    //     $guest['id_card_number'] = $request->id_card_number;

    //     $bookedRoomData = [];
    //     $totalFare      = 0;
    //     $tax            = 0;

    //     foreach ($request->room as $room) {
    //         $data      = [];
    //         $roomId    = explode('-', $room)[0];
    //         $bookedFor = explode('-', $room)[1];
    //         $isBooked  = BookedRoom::where('room_id', $roomId)->where('booked_for', $bookedFor)->exists();

    //         if ($isBooked) {
    //             return response()->json(['error' => 'Room has been booked']);
    //         }

    //         $room = Room::with('roomType')->find($roomId);

    //         if ($request->room_type_id != @$room->roomType->id) {
    //             return response()->json(['error' => 'Invalid room type selected']);
    //         }

    //         $data['booking_id']       = 0;
    //         $data['room_type_id']     = $room->room_type_id;
    //         $data['room_id']          = $room->id;
    //         $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d');
    //         $data['fare']             = $room->roomType->fare;
    //         $data['tax_charge']       = $room->roomType->fare * $tax / 100;
    //         $data['cancellation_fee'] = $room->roomType->cancellation_fee;
    //         $data['status']           = Status::ROOM_ACTIVE;
    //         $data['created_at']       = now();
    //         $data['updated_at']       = now();

    //         $bookedRoomData[] = $data;

    //         $totalFare += $room->roomType->fare;
    //     }


    //     $taxCharge = $totalFare * $tax / 100;

    //     if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
    //         return response()->json(['error' => 'Paying amount can\'t be greater than total amount']);
    //     }

    //     $booking                 = new Booking();
    //     $booking->booking_number = date('Ymdhis').rand(2,3);
    //     $booking->user_id        = @$user->id ?? 0;
    //     $booking->guest_details  = $guest;
    //     $booking->tax_charge     = $taxCharge;
    //     $booking->booking_fare   = $totalFare;
    //     $booking->paid_amount    = $request->paid_amount ?? 0;
    //     $booking->status         = 1;
    //     $booking->save();

    //     if ($request->paid_amount) {
    //         $booking->createPaymentLog($booking->paid_amount, 'RECEIVED');
    //     }

    //     $booking->createActionHistory('Advance Paid');

    //     foreach ($bookedRoomData as $key => $bookedRoom) {
    //         $bookedRoomData[$key]['booking_id'] = $booking->id;
    //     }

    //     BookedRoom::insert($bookedRoomData);

    //     $checkIn  = BookedRoom::where('booking_id', $booking->id)->min('booked_for');
    //     $checkout = BookedRoom::where('booking_id', $booking->id)->max('booked_for');

    //     $booking->check_in = $checkIn;
    //     $booking->check_out = Carbon::parse($checkout)->addDay()->toDateString();
    //     $booking->save();

    //     try {
    //         Mail::to($request->email)->send(new ReservationSuccess($booking));
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to send email. Please try again later.']);
    //     }
    //     return response()->json(['success' => 'Room booked successfully']);
    // }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'room_type_id'    => 'required|integer|gt:0',
            'guest_name'      => 'required',
            'email'           => 'nullable|email',
            'mobile'          => 'required|regex:/^([0-9]*)$/',
            'address'         => 'nullable|string',
            'id_card_type'    => 'required|string',
            'id_card_number'  => 'required|string',
            'room'            => 'required|array',
            'paid_amount'     => 'nullable|numeric|gte:0',
            'collect_caution_deposit'      => 'nullable',
            'caution_deposit_amount'      => 'nullable|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $currentYear = date('Y');
        $lastBooking = Booking::orderBy('id', 'desc')->first();
        $lastBookingNumber = null;
        
        if ($lastBooking && $lastBooking->created_at->format('Y') == $currentYear) {
            $lastBookingNumber = explode('/', $lastBooking->booking_number)[0];
        }
        
        if ($lastBookingNumber === null || !is_numeric($lastBookingNumber)) {
            $bookingNumber = '1/' . $currentYear;
        } else {
            $bookingNumber = ($lastBookingNumber + 1) . '/' . $currentYear;
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
        $taxCharge      = 0;

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
            $data['tax_charge']       = $room->roomType->tax;
            $data['caution_deposit']       = $room->roomType->caution_deposit;
            $data['cancellation_fee'] = $room->roomType->cancellation_fee;
            $data['status']           = Status::ROOM_ACTIVE;
            $data['created_at']       = now();
            $data['updated_at']       = now();

            $bookedRoomData[] = $data;

            $totalFare += $room->roomType->fare;
            $taxCharge += $room->roomType->tax;
        }


        if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
            return response()->json(['error' => 'Paying amount can\'t be greater than total amount']);
        }
        $collect_caution_deposit = $request->input('collect_caution_deposit', 0);

        $booking                 = new Booking();
        $booking->booking_number = $bookingNumber;
        $booking->user_id        = @$user->id ?? 0;
        $booking->guest_details  = $guest;
        $booking->tax_charge     = $taxCharge;
        $booking->booking_fare   = $totalFare;
        $booking->advance_fare   = $request->paid_amount ?? 0;;
        $booking->paid_amount    = $request->paid_amount ?? 0;
        $booking->caution_status    = $collect_caution_deposit;
        $booking->caution_amount    = $request->caution_deposit_amount ?? 0;
        $booking->status         = 1;
        $booking->save();

        
    

        if ($request->paid_amount) {
            $booking->createPaymentLog($booking->paid_amount, 'RECEIVED', $request->payment_method);
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

        // try {
        //     Mail::to($request->email)->send(new ReservationSuccess($booking));
        // } catch (\Exception $e) {
        //     // Log the error or handle it as needed
        //     return response()->json(['error' => 'Failed to send email. Please try again later.']);
        // }
        $invoiceUrl = URL::signedRoute('generate_invoice', ['booking_id' => $booking->id]);

        return response()->json(['success' => 'Room booked successfully', 'message' => 'success', 'invoice_url' => $invoiceUrl]);
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_card'      => 'nullable|file',
            'no_adults'    => 'required|integer|min:0',
            'no_childs'    => 'required|integer|min:0',
            'canvas_image' => 'nullable',
            'check_in'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }


        $booking = Booking::active()->findOrFail($id);

        if ($booking->key_status == Status::ENABLE) {
            return back()->with('success', 'Keys have already been given to the guest');
        }

        // if (now()->format('Y-m-d') < $booking->check_in) {
        //     return back()->with('success', 'You can\'t handover keys before the check-in date');
        // }

        // if (now()->format('Y-m-d') >= $booking->check_out) {
        //     return back()->with('success', 'You can\'t handover keys after the check-out date');
        // }

        $filePath = '';
        if($request->file()) {
            $fileName = time().'_'.$request->id_card->getClientOriginalName();
            $filePath = $request->file('id_card')->storeAs('uploads', $fileName, 'public');
        }

        $canvasPath = '';

        if ($request->hasFile('canvas_image')) {
            $imageData = $request->input('canvas_image');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);
            
            $canvasFileName = time() . '_canvas_image.png';
            
            file_put_contents(public_path('uploads/' . $canvasFileName), $imageData);
            
            $canvasPath = 'uploads/' . $canvasFileName;
        }
               

        $booking->key_status = Status::ENABLE;
        $booking->checked_in_at = $request->check_in;
        $booking->checked_in_at_exact = now();
        $booking->id_card = $filePath;
        $booking->no_adults = $request->no_adults;
        $booking->no_childs = $request->no_childs;
        $booking->canvas_image = $canvasPath;
        $booking->save();

        $booking->createActionHistory('key_handover');

        return redirect()->route('reservations.index')->with('success', 'Key handover successfully');
    }

    public function checkOutPreview($id) {
        $booking           = Booking::active()->with('bookedRooms', 'payments', 'usedExtraService', 'user', 'cautionVoucher')->findOrFail($id);
        $totalFare         = $booking->bookedRooms->sum('fare');
        $totalTaxCharge    = $booking->bookedRooms->sum('tax_charge');
        $canceledFare      = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
        $returnedPayments  = $booking->payments->where('type', 'RETURNED');
        $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
        return view('admin.reservations.check_out', compact( 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments'));
    }

    public function checkOut(Request $request,$id) {

        $validator = Validator::make($request->all(), [
            'caution_amount'      => 'nullable|numeric|gte:0',
            'rtn_caution_voucher' => 'nullable|string',
            'check_out'     => 'required',
        ]);

        $booking = Booking::active()->with('payments')->withSum('usedExtraService', 'total_amount')->findOrFail($id);

        if ($booking->caution_status == 1) {
            $existingCautionVoucher = CautionVoucher::where('booking_id', $id)->first();
    
            if ($existingCautionVoucher) {
                $existingCautionVoucher->update([
                    'caution_amt' => $request->input('caution_amount'),
                    'voucher_id'  => $request->input('rtn_caution_voucher'),
                ]);
            } else {
                CautionVoucher::create([
                    'booking_id'  => $id,
                    'caution_amt' => $request->input('caution_amount'),
                    'voucher_id'  => $request->input('rtn_caution_voucher'),
                ]);
            }
        }

        // if ($booking->check_out > now()->toDateString()) {
        //     return back()->with("error", "Checkout date for this booking is greater than now");
        // }

        $due = $booking->total_amount - $booking->paid_amount;

        if ($due > 0) {
            return back()->with("error", "The guest should pay the payable amount first");
        }

        if ($due < 0) {
            return back()->with("error", "Refund the refundable amount to the guest first");
        }

        $booking->createActionHistory('checked_out');

        $checkOutValue = $request->input('check_out');
        $checkOutDateTime = date('Y-m-d H:i:s', strtotime($checkOutValue));

        $booking->activeBookedRooms()->update(['status' => Status::BOOKING_CHECKOUT]);
        $booking->status = Status::BOOKING_CHECKOUT;
        $booking->checked_out_at = $checkOutDateTime;
        $booking->checked_out_at_exact = now();
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
        $payment_modes = PaymentMode::all();
        return view('admin.reservations.payment', compact( 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments','payment_modes'));
    }

    public function payment(Request $request, $id) {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'payment_method'=>'required',
        ]);

        $booking = Booking::findOrFail($id);
        $due     = $booking->total_amount - $booking->paid_amount;

        if ($request->amount > abs($due)) {
            $message = $due <= 0 ? 'Amount can\'t be greater than receivable amount' : 'Amount can\'t be greater than payable amount';
            return back()->withNotify("error",$message);
        }

        if ($due > 0) {
            return $this->receivePayment($booking, $request->amount,$request->payment_method);
        }

        return $this->returnPayment($booking, $request->amount);
    }

    protected function receivePayment($booking, $receivingAmount ,$payment_mod) {
        $this->deposit($booking, $receivingAmount);
        $booking->createPaymentLog($receivingAmount, 'RECEIVED' ,$payment_mod);
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
    
     public function fetchGuestDetails(Request $request)
    {
        $mobile = $request->input('mobile');
       
        $guest = Booking::where('guest_details->mobile', $mobile)->first();
        
        if ($guest) {
            
            return response()->json([
                'success' => true,
                'guest_name' => $guest['guest_details']->name ?? null,
                'email' => $guest['guest_details']->email ?? null,
                'address' =>$guest['guest_details']->address ?? null
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }
    

    public function addDiscount(Request $request, $id) {
        $this->discountValidation($request);
        $booking = Booking::findOrFail($id);
        $amount = $request->input('amount');
        $booking->discount += $amount; 
        $booking->save();
        
        $reason = $amount . ' added as discount';
    
        $booking->createActionHistory('discount_added', $reason);
    
        return back()->with("success", "Discount added successfully");
    }
    
    public function subtractDiscount(Request $request, $id) {
        $this->discountValidation($request);
    
        $booking = Booking::findOrFail($id);
    
        if ($request->input('amount') + $booking->discount_subtract > $booking->discount) {
            return back()->with("error", "Subtracted amount should be less than or equal to discount amount");
        }
    
        $amount = $request->input('amount');
        $booking->discount_subtract += $amount; 
        $booking->save();
    
        $reason = $amount . ' subtracted from discount';
    
        $booking->createActionHistory('discount_subtracted', $reason);
        return back()->with("success", "Discount subtracted successfully");
    }
    

    private function discountValidation($request) {
        $request->validate([
            'amount' => 'required|numeric|gte:0',
        ]);
    }
    
    
    // Initialize Razorpay Transaction
    public function initializeR(Request $request) {
        $customer      = $request->name;
    	$mobile_number = $request->mobile_number;
    	$email         = $request->email;
    	$amount        = $request->amount;
    
    	$refno="KP".rand(1000,9999);
        $apiUrl = 'https://www.ezetap.com/api/3.0/p2padapter/pay'; 
	
        $postData = array(
            "appKey"=>"c578b63d-9ca7-42c2-8679-6e6bb36b0f66",
    		"username"=> "1000798388",
    		"amount"=> $amount,
    		"customerMobileNumber"=>$mobile_number,
    		"externalRefNumber"=> $refno,
    		"externalRefNumber2"=>"", //470000099275152
    		"externalRefNumber3"=>"", //00798388
    		"accountLabel"=> "",
    		"customerEmail"=>$email,
            "orgCode"=> "SREE_KADAMPUZHA",
    		"pushTo"=> ["deviceId"=>"1492109487|ezetap_android"],
    		"mode"=>"ALL"
        );
    	
    	$jsonData = json_encode($postData);

       	$response = Http::post($apiUrl, $jsonData, [
            'Content-Type' => 'application/json',
        ]);
        
        if ($response->failed()) {
            echo 'HTTP error: ' . $response->status();
        }
        	
        if ($response) {
        	$res = $response->json();
			dd($res);
        	if($res->success == false) {
        		return response()->json([
                    'success' => false,
                    'response'=> $response
                ]);
        	} else {
        		$p2pRequestId = $res->p2pRequestId;

        		return response()->json([
                    'success' => false,
                    'id'      => $p2pRequestId
                ]);
        	}
        } else {
            echo 'Error';
        }
    }
    
    
    public function availableRooms(Request $request)
    {
        $bookedFor = $request->input('booked_for');
    
        $availableRooms = Room::whereNotIn('id', function($query) use ($bookedFor) {
            $query->select('room_id')
                  ->from('booked_rooms')
                  ->whereDate('booked_for', $bookedFor);
        })->get();
    
        return response()->json($availableRooms);
    }
    


    public function updateRoom(Request $request, $bookingId)
    {

        $data = BookedRoom::where(['booking_id'=> $bookingId, 'id' => $request->currentBookItemId])->update(['room_id' => $request->selected_room_id]);

        return back()->with("success", "Room updated successfully");
    }




    public function generateInvoice($booking_id)
    {
        
        $booking = Booking::with([
           
            'bookedRooms',
            'activeBookedRooms:id,booking_id,room_id',
            'activeBookedRooms.room:id,room_number',
            'bookedRooms.room:id,room_type_id,room_number',
            'bookedRooms.room.roomType:id,name,caution_deposit',
            'usedExtraService.room',
            'usedExtraService.extraService',
            'payments',
            

        ])->findOrFail($booking_id);
      
    
        $payment_method = PaymentLog::with('paymentMode')->where('booking_id',$booking_id)->latest()->first();
       

        return view('admin.invoices.invoice', compact('booking','payment_method'));
    }
}
