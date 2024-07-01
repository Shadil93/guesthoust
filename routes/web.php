<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ReservationController,
    RoomController,
    RoomTypeController,
    BedTypeController,
    AmenityController,
    ComplementController,
    AddOnServiceController,
    SettingController,
    BookingController,
    CancelBookingController,
    DashboardController,
    ReportController
};
use App\Models\Room;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    Route::resource('/rooms',RoomController::class);
    Route::resource('/room-types',RoomTypeController::class);
    Route::delete('/admin/delete-room/{id}', [RoomTypeController::class, 'deleteRoom'])->name('admin.delete-room');
    Route::post('/admin/update-room/{id}', [RoomTypeController::class, 'updateRoom'])->name('admin.update-room');
    Route::get('/fetch-caution-deposit/{room_type}', [RoomTypeController::class, 'fetchCautionDeposit']);
    Route::resource('/bed-types',BedTypeController::class);
    Route::resource('/amenities',AmenityController::class);
    Route::resource('/complements',ComplementController::class);
    Route::resource('/addonservices',AddOnServiceController::class);
    Route::resource('/settings',SettingController::class);

    Route::get('reservations/index',[ReservationController::class, 'index'])->name('reservations.index');
    Route::get('reservations/create',[ReservationController::class, 'create'])->name('reservations.create');
    Route::get('reservations/search',[ReservationController::class, 'search'])->name('reservations.search');
    Route::post('reservations/book',[ReservationController::class, 'store'])->name('reservations.store');
    Route::get('reservations/details/{id}',[ReservationController::class, 'show'])->name('reservations.show');
    Route::get('reservations/checkin/{id}',[ReservationController::class, 'checkin'])->name('reservations.checkin');
    Route::post('reservations/checkin-update/{id}',[ReservationController::class, 'checkinUpdate'])->name('reservations.checkinUpdate');
    Route::get('reservations/checkout/{id}',[ReservationController::class, 'checkOutPreview'])->name('reservations.checkout');
    Route::post('reservations/checkout/{id}',[ReservationController::class, 'checkOut'])->name('reservations.checkout');
    Route::get('reservations/payment/{id}', [ReservationController::class,'paymentView'])->name('reservations.payment');
    Route::post('reservations/payment/{id}', [ReservationController::class,'payment'])->name('reservations.payment');
    Route::post('reservations/add-charge/{id}', [ReservationController::class,'addExtraCharge'])->name('extra.charge.add');
    Route::post('reservations/subtract-charge/{id}', [ReservationController::class,'subtractExtraCharge'])->name('extra.charge.subtract');
    Route::get('reservations/booked-rooms/{id}', [ReservationController::class,'bookedRooms'])->name('reservations.booked.rooms');
    Route::get('/fetch-guest-details', [ReservationController::class,'fetchGuestDetails']);
    Route::post('reservations/add-discount/{id}', [ReservationController::class,'addDiscount'])->name('discount.add');
    Route::post('reservations/subtract-discount/{id}', [ReservationController::class,'subtractDiscount'])->name('discount.subtract');
    Route::get('/generate-invoice/{booking_id}', [ReservationController::class,'generateInvoice'])->name('generate_invoice');

    Route::post('reservations/initializeR',[ReservationController::class, 'initializeR'])->name('reservations.initialize_r');
    
    Route::get('/available-rooms',[ReservationController::class, 'availableRooms'])->name('available.rooms');
    Route::post('/update-room/{bookingId}', [ReservationController::class, 'updateRoom'])->name('update.room');
    
    Route::get('todays/check-in', [BookingController::class,'todayCheckInBooking'])->name('todays.checkin');
    Route::get('todays/checkout', [BookingController::class,'todayCheckoutBooking'])->name('todays.checkout');
    Route::get('canceled-bookings', [BookingController::class,'canceledBookingList'])->name('reservations.canceled');
    Route::get('checked-out-booking', [BookingController::class,'checkedOutBookingList'])->name('reservations.checkedout');
    Route::get('reservations/active', [BookingController::class,'activeReservations'])->name('reservations.active');
    // Route::get('booking-invoice/{id}', [BookingController::class,'generateInvoice'])->name('invoice');

    Route::get('cancel/{id}', [CancelBookingController::class,'cancelBooking'])->name('cancel');
    Route::post('cancel-full/{id}', [CancelBookingController::class,'cancelFullBooking'])->name('cancel.full');
    Route::post('booked-room/cancel/{id}', [CancelBookingController::class,'cancelSingleBookedRoom'])->name('booked.room.cancel');
    Route::post('cancel-booking/{id}', [CancelBookingController::class,'cancelBookingByDate'])->name('booked.day.cancel');

    Route::get('/get-room-details/{id}', [RoomController::class, 'getRoomDetails']);
    Route::post('/check-room-availability', [RoomTypeController::class, 'checkRoomAvailability']);
    
    Route::get('/reports/monthly', [ReportController::class, 'index'])->name('reports.monthly');
    Route::get('/reports/yearly', [ReportController::class, 'yearly'])->name('reports.yearly');
    Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');
    Route::get('/reports/collection', [ReportController::class, 'collection'])->name('reports.collection');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/daily',[ReportController::class,'daily'])->name('reports.daily');


    Route::get('/daily_pdf',[ReservationController::class,'daily_pdf'])->name('daily_pdf');
    Route::get('/yearly_pdf',[ReportController::class,'yearly_pdf'])->name('yearly_pdf');
    Route::get('/monthly_pdf',[ReportController::class,'monthly_pdf'])->name('monthly_pdf');
  
});

    Route::get('booking-invoice/{id}', [BookingController::class,'generateInvoice'])->name('invoice');


require __DIR__.'/auth.php';
