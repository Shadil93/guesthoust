@extends('admin.layouts.app')
@section('main')
<style>
    .room-button.selected {
        background-color: green !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="card shadow-lg my-2">
            <div class="card-header pb-0 d-flex justify-content-between"> 
                <h5> Booking Number: <br> #{{ $booking->booking_number }}
                </h5>
                <a href="{{ route('reservations.show',$booking->id) }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <input type="hidden" id="bookingId" value="{{ $booking->id }}" />
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="d-flex justify-content-end align-item-end">
                            <div class="d-flex justify-content-between align-item-center me-2">
                                <span class="btn btn-success me-2"></span>
                                Active
                            </div>
                            <div class="d-flex justify-content-between align-item-center me-2">
                                <span class="btn btn-warning me-2"></span>
                                Checked Out
                            </div>
                            <div class="d-flex justify-content-between align-item-center me-2">
                                <span class="btn btn-danger me-2"></span>
                                Cancelled
                            </div>
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
                                        <th>Booked For</th>
                                        <th>Room Numbers</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @forelse($bookedRooms as $key => $bookedRoom)
                                    <tr>
                                        @php
                                            $cancellationFee = $bookedRoom->where('status', Status::ROOM_ACTIVE)->sum('cancellation_fee');
                                            $totalFare = $bookedRoom->where('status', Status::ROOM_ACTIVE)->sum('fare');

                                            if ($totalFare < $cancellationFee) {
                                                $shouldRefund = 0;
                                            } else {
                                                $shouldRefund = $totalFare - $cancellationFee;
                                            }

                                            $activeBooking = $bookedRoom->where('status', Status::ROOM_ACTIVE)->count();
                                            $bookedRoom = $bookedRoom->sortBy('room_id');
                                            $caution_amount_day = $bookedRoom->where('status', Status::ROOM_ACTIVE)->sum('caution_deposit');
                                            $caution_amount_room = $bookedRoom->where('status', Status::ROOM_ACTIVE)->where('booked_for', $key)->where('room_id', '!=', $bookedRoom->first()->room_id)->count() ? 0 : $bookedRoom->where('status', Status::ROOM_ACTIVE)->pluck('room.roomType.caution_deposit')->first() ?? 0;
                                        @endphp

                                        <td>
                                            {{ \Carbon\Carbon::parse($key)->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            <div class="text-center">
                                                @foreach ($bookedRoom as $item)
                                                    @if ($item->status == Status::BOOKED_ROOM_CANCELED)
                                                        <span class="btn btn-danger">{{ $item->room->room_number }} <br> {{ $item->room->roomType->name }}  </span>
                                                    @elseif($item->status == status::BOOKED_ROOM_CHECKOUT)
                                                        <span class="btn btn-warning">{{ $item->room->room_number }} <br> {{ $item->room->roomType->name }}</span>
                                                    @elseif($item->status == Status::BOOKED_ROOM_ACTIVE)
                                                        <span class="btn btn-success">{{ $item->room->room_number }}<br>{{ $item->room->roomType->name }}<br>
                                                        @if (now()->toDateString() <= $item->booked_for)
                                                            <button @if ($booking->key_status == 1  && $activeBooking && $key == now()->format('Y-m-d')) disabled @endif class="cancel-btn btn btn-danger mt-4 cancelBookingBtn" data-fare="{{ $item->fare }}" data-id="{{ $item->id }}" data-room_number="{{ $item->room->room_number }}" data-should_refund="{{ $item->fare - $item->cancellation_fee }}" type="button">Cancel</button>
                                                            <button class="cancel-btn btn btn-primary mt-4 changeRoomBtn" data-room_type_id="{{ $item->room_type_id }}" data-booked_for="{{ $key }}" data-id="{{ $item->id }}" type="button">Change Room</button>
                                                            @endif
                                                        </span>  
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <button @if ($booking->key_status == 1 && $activeBooking && $key == now()->format('Y-m-d')) disabled @endif @if (!$activeBooking || $key < now()->format('Y-m-d')) disabled @endif class="btn btn-danger cancelBookingBtn" data-booked_for="{{ $key }}" data-fare="{{ $totalFare }}" data-should_refund="{{ $shouldRefund }}" type="button">Cancel Booking</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">No Rooms Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>  
    
    <!-- Cancel Model -->
    <div class="modal fade" id="cancelBookingModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input name="booked_for" type="hidden" value="">
                        <div class="row justify-content-center">
                            <div class="col-10 bg-danger p-3 rounded">
                                <div class="d-flex flex-wrap justify-content-between gap-2">
                                    <h6 class="text-white">Fare</h6>
                                    <span class="text-white totalFare"></span>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between gap-2 mt-2">
                                    <h6 class="text-white">Refundable Amount</h6>
                                    <span class="text-white refundableAmount"></span>
                                </div>
                                @if($booking->caution_status == 1)
                                <div class="d-flex flex-wrap justify-content-between gap-2 mt-2">
                                    <h6 class="text-white">Caution Deposit</h6>
                                    <span class="text-white cautionAmount"></span>
                                    <input name="caution_amount" type="hidden" value="">
                                </div>

                                <div class="d-flex flex-wrap justify-content-between gap-2 mt-2">
                                    <h6 class="text-white">Voucher Number</h6>
                                    <input class="form-control" type="text" name="voucher_number" id="voucher_number" placeholder="Enter Voucher Number" style="width: 200px" value=""/>

                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <h6 class="w-100">Are you sure to cancel this booking?</h6>
                        <button aria-label="Close" class="btn btn--dark" data-bs-dismiss="modal" type="button">No</button>
                        <button class="btn btn--primary" type="submit">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Cancel Model -->

    <!-- Change Room Modal -->
    <div class="modal fade" id="changeRoomModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Room</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <input name="currentBookItemId" id="currentBookItemId" type="hidden" value="">
                    <input name="selected_room_id" id="selected_room_id" type="hidden" value="">
                    <div class="modal-body">
                        
                        <div class="row justify-content-center">
                            <div class="col-10 bg-info p-3 rounded">
                                <div class="d-flex flex-wrap justify-content-between gap-2">
                                    <h6 class="text-white">Selected Room</h6>
                                    <span class="text-white selectedRoom"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Change Room Modal -->
</div>

@push('scripts')
<script>
    (function($) {
        "use strict";

        $('.changeRoomBtn').on('click', function() {
            var roomTypeId = $(this).data('room_type_id');
            var currentBookItemId = $(this).data('id');
            var bookingId = $('#bookingId').val();
            var bookedFor = $(this).data('booked_for');

            $.ajax({
                url: '{{ route("available.rooms") }}',
                type: 'GET',
                data: { booked_for: bookedFor },
                success: function(response) {
                    // Filter rooms based on room_type_id
                    var rooms = response.filter(function(room) {
                        return room.room_type_id == roomTypeId;

                    });
                    let modal = $('#changeRoomModal');
                    var modalBody = $('#changeRoomModal .modal-body');
                    $('#currentBookItemId').val(currentBookItemId);
                    modalBody.empty();
                    if (rooms.length > 0) {
                        $.each(rooms, function(index, room) {
                            var roomButton = $('<button>').addClass('btn btn-info room-button m-1').attr('data-room-id', room.id).text(room.room_number).click(function(e) {
                                e.preventDefault();
                                $('.room-button').removeClass('selected');
                                $(this).addClass('selected');
                                $('#currentBookItemId').val(currentBookItemId);
                                $('#selected_room_id').val(room.id);
                                let action = `{{ route('update.room', '') }}/${bookingId}`;
                                modal.find('form').attr('action', action);
                                $('#changeRoomModal form').submit();
                            });
                            modalBody.append(roomButton);
                        });
                    } else {
                        modalBody.append('<div>No rooms available for this room type.</div>');
                    }

                    $('#changeRoomModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Change Room Modal JavaScript
        $('#changeRoomModal form').on('submit', function(event) {
            event.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                url: form.attr('action'), 
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#changeRoomModal').modal('hide');
                    location.reload(); 
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });


    })(jQuery);

        (function($) {
            "use strict";

            $('.cancelBookingBtn').on('click', function() {
                let modal = $('#cancelBookingModal');
                let data = $(this).data();
                let action;
                let cautionAmount = 0; 

                if (data.booked_for) {
                    action = `{{ route('booked.day.cancel', $booking->id) }}`;
                    cautionAmount = {{ $caution_amount_day }}; 
                    modal.find('[name=booked_for]').val(data.booked_for);
                } else {
                    action = `{{ route('booked.room.cancel', '') }}/${data.id}`;
                    cautionAmount = {{ $caution_amount_room }};
                }

                modal.find('.modal-title').text(`Cancel Booking`);
                modal.find('form').attr('action', action);
                modal.find('.totalFare').text(data.fare);
                modal.find('.refundableAmount').text(data.should_refund);

                modal.find('.cautionAmount').text(`₹ ${cautionAmount.toFixed(2)}`);
                modal.find('[name=caution_amount]').val(cautionAmount);

                let voucherNumber = $('#voucher_number').val();
                modal.find('[name=voucher_number]').val(voucherNumber);

                modal.modal('show');
            });

        })(jQuery);

</script>


@endpush
@endsection