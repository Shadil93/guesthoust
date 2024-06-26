<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\BookedRoom;
use App\Models\Booking;
use App\Models\PaymentLog;
use App\Models\RoomType;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $todaysBookedRoomIds                = BookedRoom::active()->where('booked_for', date('Y-m-d'))->pluck('room_id')->toArray();

        $widget['today_booked']             = count($todaysBookedRoomIds);
        $widget['today_available']          = Room::active()->whereNotIn('id', $todaysBookedRoomIds)->count();
        $widget['total']                    = Booking::count();
        $widget['active']                   = Booking::active()->count();        
        $widget['pending_checkin']          = Booking::active()->KeyNotGiven()->whereDate('check_in', '<=', now())->count();
        $widget['todays_checkout']          = Booking::whereDate('checked_out_at', Carbon::today())->count();
        $salesoverview = Booking::with('bookedRooms.room.roomType')->whereDate('check_in', Carbon::today())->take(50)->get();
        $roomtypes = RoomType::with('rooms', 'activeRooms', 'bookedRooms')->get();
      

        return view('admin.dashboard',compact('widget','salesoverview','roomtypes'));
    }

    
   
}
