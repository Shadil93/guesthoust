@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h6>Edit Bed Type</h6>
                    <a href="{{ route('room-types.index') }}" class="btn btn-small btn-primary">Back</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form method="post" action="{{ route('room-types.update',$room_type->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')
                        <div class="row m-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" 
                                        name="name" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Name"
                                        value="{{ old('name') ?? $room_type->name }}" autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                            </div>    
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="total_adult">Total Adult</label>
                                    <input id="total_adult" 
                                        name="total_adult" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Total Adult"
                                        value="{{ old('total_adult') ?? $room_type->total_adult }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('total_adult')" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="total_child">Total Child</label>
                                    <input id="total_child" 
                                        name="total_child" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Total Child"
                                        value="{{ old('total_child') ?? $room_type->total_child }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('total_child')" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fare">Fare / Night</label>
                                    <input id="fare" 
                                        name="fare" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Fare / Night"
                                        value="{{ old('fare') ?? $room_type->fare }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('fare')" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tax">Tax</label>
                                    <input id="tax" 
                                        name="tax" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Tax"
                                        value="{{ old('tax') ?? $room_type->tax }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tax')" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cancellation_fee">Cancellation Charge / Night</label>
                                    <input id="cancellation_fee" 
                                        name="cancellation_fee" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Cancellation Charge / Night"
                                        value="{{ old('cancellation_fee') ?? $room_type->cancellation_fee }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('cancellation_fee')" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="caution_deposit">Caution Deposit</label>
                                    <input id="caution_deposit" 
                                        name="caution_deposit" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Caution Deposit"
                                        value="{{ old('caution_deposit') ?? $room_type->caution_deposit }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('caution_deposit')" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" 
                                        name="description" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Description">{{ $room_type->description ?? old('description') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cancellation_policy">Cancellation Policy</label>
                                    <textarea id="cancellation_policy" 
                                        name="cancellation_policy" 
                                        type="text" 
                                        class="mt-1 form-control" 
                                        placeholder="Cancellation Policy">{{ $room_type->cancellation_policy ?? old('cancellation_policy') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('cancellation_policy')" />
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Amenities</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4">
                                        @forelse($amenities as $key => $amenity)
                                            <div class="form-check form-check-info">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    name="amenities[]" 
                                                    id="amenity-{{ $amenity->id }}"  
                                                    value="{{ $amenity->id }}" 
                                                    {{ in_array($amenity->id, $roomTypeAmenities) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                                    {{ $amenity->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-center">No Data Found!</p>
                                        @endforelse
                                        <x-input-error class="mt-2" :messages="$errors->get('amenities')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Complements</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4">
                                        @forelse($complements as $key => $complement)
                                            <div class="form-check form-check-info">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    name="complements[]" 
                                                    id="complement-{{ $complement->id }}"  
                                                    value="{{ $complement->id }}" 
                                                    {{ in_array($complement->id, $roomTypeComplements) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="complement-{{ $complement->id }}">
                                                    {{ $complement->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-center">No Data Found!</p>
                                        @endforelse
                                        <x-input-error class="mt-2" :messages="$errors->get('complements')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Room Information</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4" id="roomContainer"> 
                                        @foreach($room_type->rooms as $room)
                                        <div class="col-md-3 number-field-wrapper room-content">
                                            <div class="form-group">
                                                <label for="room" class="required">Room - <span class="serialNumber">{{ $loop->index + 1 }}</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="rooms[]" class="form-control roomNumber" value="{{ $room->room_number }}" required>
                                                    <button type="button" class="input-group-text bg-danger border-0 btnRemoveRoom" data-id="{{ $room->id }}" data-name="room"><i class="las la-times"></i></button>
                                                </div>
                                                <p class="status-message"></p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <button type="button" class="btn btn-primary mx-4" id="btnAddRoom">Add Room</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Bed Information</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4" id="bedContainer">
                                        @foreach ($roomTypeBedTypes as $bedType)
                                            <div class="col-md-4 number-field-wrapper bed-content">
                                                <div class="form-group">
                                                    <label for="bed" class="required">Bed - <span class="serialNumber">{{ $loop->iteration }}</span></label>
                                                    <div class="input-group">
                                                        <select class="form-control bedType" name="bed[]">
                                                            <option value="">Select One</option>
                                                            @foreach ($bed_types as $type)
                                                                <option value="{{ $type->id }}" {{ $type->id == $bedType->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="bed"><i class="las la-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row m-4">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary addBed">Add Bed</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                             <button type="submit" class="btn btn-small btn-primary m-4">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function($) {
        "use strict";

        // Function to send room data to the server using AJAX
        function updateRoomData(roomData) {
            var roomId = roomData.id;
            var roomNumber = roomData.room_number;
            var roomType = roomData.room_type;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/rooms.remove/' + roomId, 
                method: 'POST',
                data: {
                    room_number: roomNumber,
                    room_type: roomType,
                    _token: csrfToken 
                },
                success: function(response) {
                    console.log('Room data updated successfully:', response);
                },
                error: function(error) {
                    console.error('Error updating room data:', error);
                }
            });
        }

        // Add event listener for room input fields
        $(document).on('input', '.roomNumber', function() {
            var roomInput = $(this);
            var roomContainer = roomInput.closest('.room-content');
            var roomId = roomContainer.data('room-id');
            var roomNumber = roomInput.val();

            // Get room type if available
            var roomTypeInput = roomContainer.find('[name^="room_type"]');
            var roomType = roomTypeInput.length > 0 ? roomTypeInput.val() : null;

            // Update room data object
            var roomData = {
                id: roomId,
                room_number: roomNumber,
                room_type: roomType
            };

            // Send room data to the server
            updateRoomData(roomData);
        });

        // Function to generate HTML content for a new room
        function getRoomContent(number) {
            return `
            <div class="col-md-3 number-field-wrapper room-content" data-room-id="">
                <div class="form-group">
                    <label for="room" class="required">Room - <span class="serialNumber">${number}</span></label>
                    <div class="input-group">
                        <input type="text" name="rooms[]" class="form-control roomNumber" required>
                        <button type="button" class="input-group-text bg-danger border-0 btnRemoveRoom" data-name="room"><i class="las la-times"></i></button>
                    </div>
                    <p class="status-message"></p>
                </div>
            </div>`;
        }

        // Add event listener for the "Add Room" button
        $('#btnAddRoom').click(function() {
            var roomContainer = $('#roomContainer');
            var roomNumber = roomContainer.find('.room-content').length + 1;
            var roomContent = getRoomContent(roomNumber);
            roomContainer.append(roomContent);
        });

    })(jQuery);
</script>




    <script>
        $(document).ready(function() {
            // Add event listener for the "Remove Room" button
            $(document).on('click', '.btnRemoveRoom', function() {
                var roomId = $(this).data('id');
                var confirmDelete = confirm('Are you sure you want to delete this room?');
                if (confirmDelete) {
                    $.ajax({
                        url: '/admin/delete-room/' + roomId,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Remove the room element from the UI
                            $(`[data-id="${roomId}"]`).closest('.room-content').remove();
                        },
                        error: function(error) {
                            console.error('Error deleting room:', error);
                            alert('Error deleting room. Please try again.');
                        }
                    });
                }
            });
        });
    </script>


<script>
    $(document).ready(function() {
        // Add event listener for the "Add Bed" button
        $('.addBed').click(function() {
            var bedContainer = $('#bedContainer');
            var bedNumber = bedContainer.find('.bed-content').length + 1;
            var bedContent = getBedContent(bedNumber);
            bedContainer.append(bedContent);
        });

        // Add event listener for the "Remove Bed" button
        $(document).on('click', '.btnRemove', function() {
            $(this).closest('.bed-content').remove();
        });

        // Function to generate HTML content for a new bed
        function getBedContent(number) {
            return `
            <div class="col-md-4 number-field-wrapper bed-content">
                <div class="form-group">
                    <label for="bed" class="required">Bed - <span class="serialNumber">${number}</span></label>
                    <div class="input-group">
                        <select class="form-control bedType" name="bed[]">
                            <option value="">Select One</option>
                            @foreach ($bed_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="bed"><i class="las la-times"></i></button>
                    </div>
                </div>
            </div>`;
        }
    });
</script>


@endpush
@endsection