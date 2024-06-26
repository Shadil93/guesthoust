@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>Add New Room Types</h6>
              <a href="{{ route('room-types.index') }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2 m-4">
                <form method="post" action="{{ route('room-types.store') }}" class="mt-6 space-y-6">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4 ">
                                <div class="card-header pb-0">
                                    <h6>General Information</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input id="name" 
                                                    name="name" 
                                                    type="text" 
                                                    class="mt-1 form-control" 
                                                    placeholder="Name"
                                                    value="{{ old('name') }}" autofocus />
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
                                                    value="{{ old('total_adult') }}" />
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
                                                value="{{ old('total_child') }}" />
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
                                                value="{{ old('fare') }}" />
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
                                                value="{{ old('tax') }}" />
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
                                                value="{{ old('cancellation_fee') }}" />
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
                                                value="{{ old('caution_deposit') }}" />
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
                                                placeholder="Description">{{ old('description') }}</textarea>
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
                                                placeholder="Cancellation Policy">{{ old('cancellation_policy') }}</textarea>
                                            <x-input-error class="mt-2" :messages="$errors->get('cancellation_policy')" />
                                        </div>
                                    </div>
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
                                                <input class="form-check-input" type="checkbox" name="amenities[]" id="amenity-{{ $amenity->id }}"  checked="" value="{{ $amenity->id  }}" />
                                                <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                                 {{ $amenity->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-center">No Data Found!</a>
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
                                                <input class="form-check-input" type="checkbox" name="complements[]" id="complement-{{ $complement->id }}"  checked="" value="{{ $complement->id  }}" />
                                                <label class="form-check-label" for="complement-{{ $complement->id }}">
                                                 {{ $complement->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-center">No Data Found!</a>
                                        @endforelse
                                        <x-input-error class="mt-2" :messages="$errors->get('complemets')" />
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
                                    <div class="row m-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="total_rooms">Total Rooms</label>
                                                <input id="total_rooms" 
                                                    name="total_rooms" 
                                                    type="text" 
                                                    class="mt-1 form-control" 
                                                    placeholder="Total Rooms"
                                                    value="{{ old('total_rooms') }}" autofocus />
                                                <x-input-error class="mt-2" :messages="$errors->get('total_rooms')" />
                                            </div>
                                        </div>   
                                        <div class="col-md-12 room">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Bed Per Room</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="row m-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="total_bed">Total Bed</label>
                                                <input id="total_bed" 
                                                    name="total_bed" 
                                                    type="text" 
                                                    class="mt-1 form-control" 
                                                    placeholder="Total Bed"
                                                    value="{{ old('total_bed') }}" autofocus />
                                                <x-input-error class="mt-2" :messages="$errors->get('total_bed')" />
                                            </div>
                                        </div>   
                                        <div class="col-md-12 bed">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-small btn-primary me-5">Save</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>

    (function($) {
        "use strict";
        let bedTypes = {!! json_encode($bed_types) !!};


        let preloaded = [];

        // room js
            $('[name=total_rooms]').on('input', function() {
                var totalRoom = $(this).val();
                if (totalRoom) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalRoom; i++) {
                        content += getRoomContent(i);
                    }
                    content += '</div>';
                    $('.room').html(content);
                }
            });

            function getRoomContent(number) {
            return `
            <div class="col-md-3 number-field-wrapper room-content">
                <div class="form-group">
                    <label for="room" class="required">Room - <span class="serialNumber">${number}</span></label>
                    <div class="input-group">
                        <input type="text" name="room[]" class="form-control roomNumber" required>
                        <button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="room"><i class="las la-times"></i></button>
                    </div>
                    <p class="status-message"></p> 
                </div>
            </div>`;
        }



            function setTotalRoom() {
                var totalRoom = $('.roomNumber').length;
                console.log(totalRoom);
                $('[name=total_rooms]').val(totalRoom);
            }

     
            $(document).on('input', '.roomNumber', function() {
                var roomInput = $(this);
                var roomNumber = roomInput.val();
                var roomType = roomInput.closest('.room-content').find('[name^="room_type"]').val(); 
                var csrfToken = $('meta[name="csrf-token"]').attr('content'); 
                var duplicate = false;
                $('.roomNumber').not(this).each(function() {
                    if ($(this).val() == roomNumber) {
                        duplicate = true;
                        return false; 
                    }
                });

                if (duplicate) {
                    var statusMessage = roomInput.closest('.room-content').find('.status-message');
                    statusMessage.html(`<span style="color: red; font-size: smaller;">Room ${roomNumber} already exists!</span>`);
                    return;
                }
                $.ajax({
                    url: '/check-room-availability', 
                    method: 'POST',
                    data: {
                        roomNumber: roomNumber,
                        roomType: roomType,
                        _token: csrfToken 
                    },
                    success: function(response) {
                        var statusMessage = roomInput.closest('.room-content').find('.status-message');
                        
                        if (response.available) {
                            statusMessage.html(`<span style="color: red; font-size: smaller;">Room ${roomNumber} is already allocated !</span>`);
                        } else {
                            statusMessage.html(`<span style="color: green; font-size: smaller;">Room ${roomNumber} is available !</span>`);

                        }
                    },
                    error: function(error) {
                        console.error('Error checking room availability:', error);
                    }
                });
            });


            //bed js
            $('[name=total_bed]').on('input', function() {
                var totalBed = $(this).val();
                if (totalBed) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalBed; i++) {
                        content += getBedContent(i);
                    }
                    content += '</div>';
                    $('.bed').html(content);
                }
            });

            function getBedContent(number) {
                return `
                    <div class="col-md-3 number-field-wrapper bed-content">
                        <div class="form-group">
                            <label for="bed" class="required">Bed - <span class="serialNumber">${number}</span></label>
                            <div class="input-group"><select class="form-control bedType" name="bed[${number}]">
                                        <option value="">Select One</option>
                                        ${allBedType()}
                                    </select><button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="bed"><i class="las la-times"></i></button>
                            </div>
                        </div>
                    </div>`;
            }

            function setTotalBed() {
                var totalBed = $('.bedType').length;
                $('[name=total_bed]').val(totalBed);
            }

            function allBedType() {
                var options;
                $.each(bedTypes, function(i, e) {
                    options += `<option value="${e.id}">${e.name}</option>`;
                });
                return options;
            }


            //common js
            $('[name=total_bed]').on('input', function() {
                var totalBed = $(this).val();
                if (totalBed) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalBed; i++) {
                        content += getBedContent(i);
                    }
                    content += '</div>';
                    $('.bed').html(content);
                }
            });

            $(document).on('click', '.btnRemove', function() {
                $(this).closest('.number-field-wrapper').remove();
                let divName = null;
                if ($(this).data('name') == 'bed') {
                    setTotalBed();
                    divName = $('.bed-content').find('.serialNumber');
                } else {
                    divName = $('.room-content').find('.serialNumber');
                    setTotalRoom();
                }
                resetSerialNumber(divName);
            });

            function resetSerialNumber(divName) {
                $.each(divName, function(i, e) {
                    $(e).text(i + 1)
                });
            }

            $('.addMore').on('click', function() {
                if ($(this).parents().hasClass('room')) {
                    var total = $('.roomNumber').length;
                    total += 1;

                    $('.room .row').append(getRoomContent(total));
                    setTotalRoom();
                    return;
                }

                var total = $('.bedType').length;
                total += 1;

                $('.bed .row').append(getBedContent(total));
                setTotalBed();
            });

        })(jQuery);
    </script>
    @if (Session::has('warning'))

    <script>
        $(document).ready(function() {
            Swal.fire({
                            text: "{{ Session::get('warning') }}",
                            toast: true
                        });
        });
    </script>

    @endif
@endpush
@endsection