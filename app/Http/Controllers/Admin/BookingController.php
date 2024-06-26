<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\BookingActions;
use App\Constants\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\{
    RoomType,
    Booking,
    BookedRoom,
    Room,
    User
};


class BookingController extends Controller
{

    use BookingActions;
    
    public function todayCheckInBooking(){
        $pageTitle = "Pending";
        $bookings = $this->bookingData('todayCheckIn');
        return view('admin.reservations.list',compact('pageTitle','bookings'));  
    }

    public function todayCheckoutBooking() {
        $pageTitle = "Today's Checkout";
        $bookings = $this->bookingData('todayCheckout');
        return view('admin.reservations.list',compact('pageTitle','bookings'));  
    }

    public function canceledBookingList() {
        $pageTitle = "Canceled Bookings";
        $bookings = $this->bookingData('canceled');
        return view('admin.reservations.list',compact('pageTitle','bookings')); 
    }

    public function checkedOutBookingList() {
        $pageTitle = "Checked Out Bookings";
        $bookings = $this->bookingData('checkedOut');
        return view('admin.reservations.list',compact('pageTitle','bookings')); 
    }

    public function delayedCheckout() {
        $pageTitle = "Delayed Checkout Bookings";
        $bookings = $this->bookingData('delayedCheckOut');
        return view('admin.reservations.list',compact('pageTitle','bookings')); 
    }

    public function activeReservations(){
        $pageTitle = "Active Bookings";
        $bookings = $this->bookingData('active');
        return view('admin.reservations.list',compact('pageTitle','bookings')); 
    }

    public function generateInvoice($bookingId) {
        $booking = Booking::with([
            'checkOutRooms' => function ($query) {
                $query->select('id', 'booking_id', 'room_id', 'fare', 'status', 'booked_for');
            },
            'checkOutRooms.room:id,room_type_id,room_number',
            'checkOutRooms.room.roomType:id,name',
            'usedExtraService.room',
            'usedExtraService.extraService'
        ])->findOrFail($bookingId);

        $data = ['booking' => $booking];

        $pdf = PDF::loadView('admin.reservations.partials.invoice', $data);
        
        return $pdf->stream($booking->booking_number . '.pdf');
    } 
    
    protected function bookingData($scope) {
        $query = Booking::query();
        $query = $query->$scope();
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
        return $query->with('bookedRooms.room', 'user', 'activeBookedRooms', 'activeBookedRooms.room:id,room_number')
            ->withSum('usedExtraService', 'total_amount')
            ->latest()
            ->orderBy('check_in', 'asc')
            ->paginate(10);
    }

}
