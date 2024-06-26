<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\CautionVoucher;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class CancelBookingController extends Controller {

    public function cancelBooking($id) {
        $booking   = Booking::active()->with('activeBookedRooms.room.roomType')->findOrFail($id);
        return view('admin.reservations.cancel', compact('booking'));
    }

    public function cancelFullBooking($id) {
        $booking     = Booking::active()->findOrFail($id);
        $bookedRooms = BookedRoom::active()->where('booking_id', $booking->id);
        
        $booking->cancellation_fee += (clone $bookedRooms)->sum('cancellation_fee');
        $booking->booking_fare  -= (clone $bookedRooms)->sum('fare');
        $booking->tax_charge -= (clone $bookedRooms)->sum('tax_charge');

        $booking->status = Status::BOOKING_CANCELED;
        $booking->save();

        $roomIds =  $booking->bookedRooms()->pluck('room_id')->toArray();
        $rooms   = Room::whereIn('id', $roomIds)->get()->pluck('room_number')->toArray();
        $bookedRooms->update(['status' => Status::ROOM_CANCELED]);

        $booking->createActionHistory('cancel_booking');

        return redirect()->route('reservations.index')->with('success', 'Booking canceled successfully');
    }

    public function cancelBookingByDate(Request $request, $id) {
        // dd($request->all());

        if ($request->booked_for < now()->toDateString()) {
            return back()->with("error", "Past date's bookings can't be canceled");
        }

        $booking  = Booking::active()->find($id);

        if (!$booking) {
            return back()->with("error", "This booking can't be canceled");
        }

        $bookedRooms         = BookedRoom::active()->where('booking_id', $booking->id);
        $bookedForOtherDates = (clone $bookedRooms)->where('booked_for', '!=', $request->booked_for)->count();
        $bookedRooms         = (clone $bookedRooms)->whereDate('booked_for', $request->booked_for);

        $booking->cancellation_fee += (clone $bookedRooms)->sum('cancellation_fee');
        $booking->booking_fare  -= (clone $bookedRooms)->sum('fare');
        $booking->tax_charge -= (clone $bookedRooms)->sum('tax_charge');

        if (!$bookedForOtherDates) {
            $booking->status = Status::BOOKING_CANCELED;
        }

        $booking->save();

        $dateWiseBooked = (clone $bookedRooms)->get()->pluck('room_id')->toArray();
        $bookedRooms->update(['status' => Status::ROOM_CANCELED]);

        $this->updateCheckInCheckoutDate($booking);

        $rooms = Room::whereIn('id', $dateWiseBooked)->get()->pluck('room_number')->toArray();

        $booking->createActionHistory('cancel_booking');

        return back()->with("success", "Booking canceled successfully");
    }

    public function cancelSingleBookedRoom(Request $request, $id) {
        // dd($request->all());
        $bookedRoom = BookedRoom::with('booking', 'room')->findOrFail($id);

        if ($bookedRoom->status != Status::ROOM_ACTIVE) {
            return back()->with("error", "This room can't be canceled");
        }

        if ($bookedRoom->booked_for < now()->toDateString()) {
            return back()->with("error", "Previous days booking can't be canceled");
        }

        $booking              = $bookedRoom->booking;
        $anotherBookedRooms   = BookedRoom::active()->where('id', '!=', $request->id)->exists();

        $booking->cancellation_fee += $bookedRoom->cancellation_fee;
        $booking->booking_fare -= $bookedRoom->fare;
        $booking->tax_charge -= $bookedRoom->tax_charge;

        if (!$anotherBookedRooms) {
            $booking->status = Status::BOOKING_CANCELED;
        }

        $booking->save();

        $bookedRoom->status = Status::ROOM_CANCELED;
        $bookedRoom->save();

        $this->updateCheckInCheckoutDate($booking);

        $booking_id=$bookedRoom->booking_id;
        $book = Booking::active()->findOrFail($booking_id);
        $book->caution_amount -= $request->caution_amount;
        $book->save();

        $book->createActionHistory('caution_cancel_room');
        
        if ($book->caution_status == 1 && $request->caution_amount != 0) {
            $existingCautionVoucher = CautionVoucher::where('booking_id', $booking_id)->first();
        
            if ($existingCautionVoucher) {
                $existingCautionVoucher->update([
                    'caution_amt' => $request->caution_amount,
                    'voucher_id'  => $request->voucher_number,
                ]);
            } else {
                CautionVoucher::create([
                    'booking_id'  => $booking_id,
                    'caution_amt' => $request->caution_amount,
                    'voucher_id'  => $request->voucher_number,
                ]);
            }
        }


        return back()->with("success", "Room canceled successfully");
    }

    private function bookingGuest($booking) {
        if ($booking->user) {
            return $booking->user;
        }

        $guest = new User();
        $guest->username = $booking->guest_details->name;
        $guest->fullname = $booking->guest_details->name;
        $guest->email    = $booking->guest_details->email;
        $guest->mobile   = $booking->guest_details->mobile;

        return $guest;
    }

    protected function updateCheckInCheckoutDate($booking) {
        $lastDateBookedRoom  = $booking->activeBookedRooms()->orderBy('booked_for', 'desc')->first();
        $firstDateBookedRoom = $booking->activeBookedRooms()->orderBy('booked_for', 'asc')->first();

        if ($lastDateBookedRoom) {
            $booking->check_out = Carbon::parse($lastDateBookedRoom->booked_for)->addDay()->format('Y-m-d');
        }

        if ($firstDateBookedRoom) {
            $booking->check_in = $firstDateBookedRoom->booked_for;
        }

        $booking->save();
    }
}
