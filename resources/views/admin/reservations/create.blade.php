@extends('admin.layouts.app')
@section('main')
<script>
     success: function(response) {
        alert('ok');
    if (response.message == "success") {
        window.open(response.invoice_url, '_blank');
    } else {
        console.error('Error:', response);
    }
}
</script>
<link href="{{ asset('assets/css/datepicker.min.css') }}" rel="stylesheet" />
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6> New Reservation</h6>
              <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <form action="{{ route('reservations.search') }}" class="formRoomSearch" method="get">
                                <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                                    <div class="form-group flex-fill">
                                        <label>Room Type</label>
                                        <select class="form-control" name="room_type" required>
                                            <option value="">Select One</option>
                                            @foreach($room_types as $key => $room_type)
                                            <option value="{{ $room_type->id }}" data-caution-deposit="{{ $room_type->caution_deposit }}">{{ $room_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group flex-fill">
                                        <label>Check In - Check Out Date</label>
                                        <input autocomplete="off" class="datepicker-here form-control bg--white" data-language="en" data-multiple-dates-separator=" - " data-position='bottom left' data-range="true" name="date" placeholder="Select Date" required type="text">
                                    </div>
                                    <div class="form-group flex-fill">
                                        <label>Room</label>
                                        <input class="form-control" name="rooms" placeholder="How many room?" required type="text">
                                    </div>
                                    <div class="form-group flex-fill d-flex align-item-center justify-content-center">
                                        <button class="btn btn-primary search" type="submit">
                                            <i class="la la-search"></i>Search</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Add a Alert modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
                                        </div>
                                        <div class="modal-body">
                                            <p>Please select a maximum of 3 days.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                $(document).ready(function () {
                                    var today = new Date();

                                    $('.datepicker-here').datepicker({
                                        language: 'en',
                                        multipleDatesSeparator: ' - ',
                                        position: 'bottom left',
                                        range: true,
                                        onSelect: function (formattedDate, date, inst) {
                                            var diffInDays = Math.ceil((date[1] - date[0]) / (1000 * 60 * 60 * 24));

                                            if (diffInDays > 3) {
                                                inst.clear();
                                                $('#myModal').modal('show'); 
                                            }
                                        },
                                        minDate: null // Allow selecting older dates
                                    });
                                });
                            </script>
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row booking-wrapper d-none">
                    <div class="col-lg-8 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                                    <h5>Booking Information</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="pb-3">
                                    <span class="fas fa-circle text-danger" disabled></span>
                                    <span class="mr-5">Booked</span>
                                    <span class="fas fa-circle text-success"></span>
                                    <span class="mr-5">Selected</span>
                                    <span class="fas fa-circle text-primary"></span>
                                    <span>Available</span>
                                </div>
                                <div class="alert alert-info room-assign-alert p-3" role="alert">
                                </div>
                                <div class="bookingInfo">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title mb-0">
                                    <h5>Guest Details</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('reservations.store') }}" class="booking-form" id="booking-form" method="POST">
                                    @csrf
                                    <div class="row">
                                        <input name="room_type_id" type="hidden">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Guest Name</label>
                                                <input class="form-control" name="guest_name" required type="text">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" name="email" type="email">
                                            </div>
                                        </div>

                                        <div class="col-12 guestInputDiv">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input class="form-control" id="mobile" name="mobile" required type="text" onkeypress="return onlyNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="col-12 guestInputDiv">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="col-12 guestInputDiv">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input class="form-control" name="address" required type="text">
                                            </div>
                                        </div> -->
                                        <div class="col-12 guestInputDiv">
                                            <div class="form-group">
                                                <label for="id_card_type">ID Card Type</label>
                                                <select class="form-control" name="id_card_type" id="id_card_type" required>
                                                    <option value="">Select ID Card Type</option>
                                                    <option value="Aadhar Card">Aadhar Card</option>
                                                    <option value="Driving License">Driving License</option>
                                                    <option value="Passport">Passport</option>
                                                    <option value="Voter ID">Voter ID</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 guestInputDiv">
                                            <div class="form-group">
                                                <label for="id_card_number">ID Card Number</label>
                                                <input class="form-control" name="id_card_number" id="id_card_number" required type="text">
                                                <div id="id_card_number_error" class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <script>
                                            const idCardValidationRules = {
                                                'Aadhar Card': /^\d{12}$/,
                                                'Driving License': /^\d{16}$/,
                                                'Passport': /^\d{12}$/,
                                                'Voter ID': /^[A-Za-z0-9]{10}$/
                                            };

                                            function validateIdCardNumber() {
                                                const idCardType = document.getElementById('id_card_type').value;
                                                const idCardNumber = document.getElementById('id_card_number').value;

                                                if (idCardValidationRules[idCardType].test(idCardNumber)) {
                                                    document.getElementById('id_card_number').classList.remove('is-invalid');
                                                    document.getElementById('id_card_number_error').innerText = '';
                                                } else {
                                                    document.getElementById('id_card_number').classList.add('is-invalid');
                                                    document.getElementById('id_card_number_error').innerText = 'Invalid ID Card Number';
                                                }
                                            }

                                            document.getElementById('id_card_type').addEventListener('change', validateIdCardNumber);
                                            document.getElementById('id_card_number').addEventListener('input', validateIdCardNumber);

                                            validateIdCardNumber();
                                        </script>


                                        <div class="orderList d-none">
                                            <ul class="list-group list-group-flush orderItem">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <h6>Room</h6>
                                                    <h6>Days</h6>
                                                    <span>
                                                        <h6>Fare</h6>
                                                    </span>
                                                    <span>
                                                        <h6>Tax</h6>
                                                    </span>
                                                    <span>
                                                        <h6>Subtotal</h6>
                                                    </span>
                                                </li>
                                            </ul>
                                            <div class="d-flex justify-content-between align-items-center border-top p-2">
                                                <span>Sub Total</span>
                                                <span class="totalFare" data-amount="0"></span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center border-top p-2">
                                                <span>Total Tax</span>
                                                <span><span class="taxCharge" data-percent_charge="0.00"></span></span> 
                                                <input name="tax_charge" type="hidden">
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center border-top p-2">
                                                <span>Total Fare</span>
                                                <span class="grandTotalFare"></span>
                                                <input hidden name="total_amount" type="text">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Paying Amount</label>
                                                <input class="form-control" min="0" name="paid_amount" placeholder="Paying Amount" step="any" type="number">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Payment Method</label>
                                                <select class="form-select"  name="payment_method" id="payment_method" aria-label="Default select example">
                                                <option selected>Payment method</option>
                                                @foreach($payment_methods as $payment_method)
                                                <option value="{{$payment_method->id}}">{{ $payment_method->payment_mode }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input collectCautionDeposit" name="collect_caution_deposit" type="checkbox" value="1">
                                                <label class="form-check-label">Collect Caution Deposit</label>
                                            </div>
                                            <div class="form-group cautionDepositContainer d-none">
                                                <label>Caution Deposit</label>
                                                <span class="cautionDepositAmount"></span>
                                                <input type="hidden" name="caution_deposit_amount">
                                            </div>
                                        </div>
                                        <script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.querySelector('.collectCautionDeposit');
        const roomTypeSelect = document.querySelector('select[name="room_type"]');
        const roomInput = document.querySelector('input[name="rooms"]');
        const cautionDepositAmount = document.querySelector('.cautionDepositAmount');
        const cautionDepositContainer = document.querySelector('.cautionDepositContainer');

        checkbox.addEventListener('change', function() {
            document.querySelector('input[name="collect_caution_deposit"]').value = this.checked ? 1 : 0;
            updateCautionDeposit();
        });

        roomInput.addEventListener('input', function() {
            updateCautionDeposit();
        });

        roomTypeSelect.addEventListener('change', function() {
            if (checkbox.checked) {
                fetchCautionDeposit(this.value);
            }
        });

        function updateCautionDeposit() {
            if (checkbox.checked && roomInput.value !== '') {
                const selectedRoomType = roomTypeSelect.value;
                const numberOfRooms = parseInt(roomInput.value);
                if (selectedRoomType) {
                    fetchCautionDeposit(selectedRoomType)
                        .then(cautionDeposit => {
                            if (cautionDeposit !== null) {
                                const totalCautionDeposit = numberOfRooms * cautionDeposit;
                                cautionDepositAmount.textContent = totalCautionDeposit;
                                document.querySelector('input[name="caution_deposit_amount"]').value = totalCautionDeposit;
                                cautionDepositContainer.classList.remove('d-none');
                            }
                        });
                }
            } else {
                cautionDepositContainer.classList.add('d-none');
            }
        }

        async function fetchCautionDeposit(roomType) {
            try {
                const response = await fetch(`/fetch-caution-deposit/${roomType}`);
                const data = await response.json();
                if (data.success) {
                    return data.caution_deposit;
                } else {
                    console.error('Failed to fetch caution deposit');
                    return null;
                }
            } catch (error) {
                console.error('Error fetching caution deposit:', error);
                return null;
            }
        }
    });
</script>


                                        <div class="form-group mb-0">
                                            <button class="btn btn-primary h-45 w-100 btn-book confirmBookingBtn" type="button">Book Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmBookingModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to book this rooms?')</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    <button class="btn btn--primary btn-confirm" type="button">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="{{ asset('assets/js/plugins/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datepicker.en.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (!$('.datepicker-here').val()) {
            $('.datepicker-here').datepicker({
                minDate: new Date(),
                dateFormat: 'dd/mm/yyyy'
            });
        }

        $('.formRoomSearch').on('submit', function(e) {
            e.preventDefault();

            let searchDate = $('[name=date]').val();
            if (searchDate.split(" - ").length < 2) {
                Swal.fire({
                            text: `Check-In date and checkout date should be given for booking.`,
                            toast: true
                        });
                return false;
            }

            resetDOM();
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                type: "get",
                url: url,
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('.bookingInfo').html('');
                    $('.booking-wrapper').addClass('d-none');
                    if (response.error) {
                        Swal.fire({
                            text: response.error,
                            toast: true
                        });
                    } else {
                        $('.bookingInfo').html(response.html);
                        let roomTypeId = $('[name=room_type]').val();
                        $('[name=room_type_id]').val(roomTypeId);
                        $('.booking-wrapper').removeClass('d-none');
                    }
                  
                },
                processData: false,
                contentType: false,
            });
        });

        function resetDOM() {
            $(document).find('.orderListItem').remove();
            $('.totalFare').data('amount', 0);
            $('.totalFare').text(`0 INR`);
            $('.taxCharge').text('0');
            $('[name=tax_charge]').val('0');
            $('.grandTotalFare').text(`0 INR`);
            $('[name=total_amount]').val('0');
            $('[name=paid_amount]').val('');
            $('[name=room_type_id]').val('');
        }

        $(document).on('click', '.confirmBookingBtn', function() {
            var modal = $('#confirmBookingModal');
            modal.modal('show');
        });

        $('.btn-confirm').on('click', function() {
            $('#confirmBookingModal').modal('hide');
            $('.booking-form').submit();
        });

        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // alert(response.invoice_url);
                        window.open(response.invoice_url, '_blank');

                        Swal.fire({
                            text: response.success,
                            toast: true
                        });
                        $('.bookingInfo').html('');
                        $('.booking-wrapper').addClass('d-none');
                        $(document).find('.orderListItem').remove();
                        $('.orderList').addClass('d-none');
                        $('.formRoomSearch').trigger('reset');
                    } else {
                        Swal.fire({
                            text: response.error,
                            toast: true
                        });
                    }
                },
            });
        });
       

        document.getElementById('mobile').addEventListener('blur', function() {
            var mobile = this.value;
            fetchGuestDetails(mobile);
        });

        function fetchGuestDetails(mobile) {
            fetch('/fetch-guest-details?mobile=' + mobile)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('input[name="guest_name"]').value = data.guest_name;
                        document.querySelector('input[name="email"]').value = data.email;
                        document.querySelector('textarea[name="address"]').value = data.address; 
                    } else {
                        console.log('Guest details not found');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

    </script>
    <script>
        function onlyNumberKey(evt) {
            
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }

    </script>


@endpush    
@endsection