@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
    <div class="row">
        <div class="card shadow-lg my-2">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">Booking Number: </h5>
                            #{{ $booking->booking_number }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-item-end">
                            
                    </div>
                </div> 
            </div>
        </div>  
    </div>
    <div class="card shadow-lg my-4">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">Booked Rooms</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>Fare</th>
                                    <th>Cancellation Fee</th>
                                    <th>Refundable</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->activeBookedRooms as $bookedRoom)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $bookedRoom->room->room_number }}</td>
                                        <td> {{ $bookedRoom->room->roomType->name }}</td>
                                        <td>{{ $bookedRoom->fare }}</td>
                                        <td>{{ $bookedRoom->cancellation_fee }}</td>
                                        <td>{{ $bookedRoom->fare - $bookedRoom->cancellation_fee }}</td>
                                    </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th class="text-end" colspan="4">Total</th>
                                        <th>{{ $booking->activeBookedRooms->sum('cancellation_fee') }}</th>
                                        <th>{{ $booking->activeBookedRooms->sum('fare') - $booking->activeBookedRooms->sum('cancellation_fee') }}</th>
                                    </tr>
                                </tfoot>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <div class="card-footer d-flex justify-content-end">
            <form action="{{ route('cancel.full', $booking->id) }}" method="post">
                @csrf
                <button class="btn btn-primary" type="submit">Confirm Cancellation</button>
            </form>
        </div>
    </div>
    
@endsection