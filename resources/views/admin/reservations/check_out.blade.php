@extends('admin.layouts.app')
@section('main')
@php
    $due = $booking->due() 
@endphp
<div class="card shadow-lg mx-4">
        <div class="card-header pb-0 d-flex justify-content-between"> 
            <h6> Reservation Details
                <div class="h-100">
                @php
                    echo $booking->status_badge;
                @endphp
                </div>
            </h6>
            <a href="{{ route('reservations.show',$booking->id) }}" class="btn btn-small btn-primary">Back</a>
        </div>
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto my-auto">
            @if ($due > 0)
            <div class="col-md-12 mt-2">
                <div class="alert alert-danger">
                    The guest didn't pay the due payment for this booking yet. The checkout process can't be completed until the payment is settled. Please receive the due amount.
                </div>
            </div>
            @endif

            @if ($due < 0)
                <div class="col-md-12 mt-2">
                    <div class="alert alert-danger">
                        The guest didn't receive the refundable amount for this booking yet. The checkout process can't be completed until the payment is settled. Please refund the amount.
                    </div>
                </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Booking Information</h5>
              </div>
            </div>
            <div class="card-body">
              <hr class="horizontal dark mt-0">
              <div class="row">
                <div class="col-md-6 mt-4">
                    <span class="font-weight-light">Booking Number </span> 
                    <h5>#{{ $booking->booking_number }}</h5>
                    <span class="font-weight-light">Booked At   </span> 
                    <h5>{{ $booking->created_at }}</h5>
                    <span class="font-weight-light">Checkin  </span> 
                    <h5>{{ $booking->check_in }}</h5>
                    <span class="font-weight-light">Checkout  </span> 
                    <h5>{{ $booking->check_out }}</h5>
                </div>
                <div class="col-md-6 mt-4">
                    <span class="font-weight-light">Total Rooms  </span> 
                    <h5>{{ $booking->bookedRooms->count() }}</h5>
                    <span class="font-weight-light">Total Charge </span> 
                    <h5>{{ $booking->total_amount }}</h5>
                    <span class="font-weight-light">Paid Amount  </span> 
                    <h5>{{ $booking->paid_amount }}</h5>
                    @if ($due < 0)
                    <span class="font-weight-light">Refundable  </span> 
                    <h5>{{ abs($due) }}</h5>
                    @else
                    <span class="font-weight-light">Receivable From Customer  </span> 
                    <h5 class="@if ($due > 0) text-danger @else text-success @endif">{{ abs($due) }}</h5>
                    @endif
                </div>
                <div class="col-md-12 mt-4">
                    <hr class="horizontal dark mt-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="font-weight-light">Checked In At  </span> 
                            <h5>{{ $booking->checked_in_at ? $booking->checked_in_at : 'N/A' }}</h5>
                        </div>
                        <div>
                            <span class="font-weight-light">Checked Out At  </span> 
                            <h5>{{ $booking->checked_out_at  ? $booking->checked_out_at : 'N/A' }}</h5>
                        </div>
                    </div>
                </div>
              </div>        
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="text-uppercase text-sm">Booked Rooms</h5>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Booked For</th>
                                <th>Room Type</th>
                                <th>Room No</th>
                                <th class="text-end">Fare / Night</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->bookedRooms->groupBy('booked_for') as $bookedRoom)
                                @foreach ($bookedRoom as $booked)
                                    <tr>
                                        @if ($loop->first)
                                            <td class="bg-date text-center" rowspan="{{ count($bookedRoom) }}">
                                                {{ $booked->booked_for}}
                                            </td>
                                        @endif
                                        <td class="text-center" data-label="Room Type">
                                            {{ $booked->room->roomType->name }}
                                        </td>
                                        <td data-label="Room No.">
                                            {{ $booked->room->room_number }}
                                            @if ($booked->status == 3)
                                                <span class="text-danger text-sm">(Canceled)</span>
                                            @endif
                                        </td>
                                        <td class="text-end" data-label="Fare">
                                            {{ $booked->fare }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td class="text-end" colspan="3">
                                    <span class="fw-bold">Total Fare</span>
                                </td>
                                <td class="fw-bold text-end">
                                    {{ $totalFare }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title">Payment Info</h5>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                <span>Total Fare</span>
                <span class="text-end">+{{ $totalFare }}</span>
            </li>

            <li class="list-group-item">
                <span> Charge</span>
                <span class="text-end">+{{ $totalTaxCharge }}</span>
            </li>

            <li class="list-group-item">
                <span>Canceled Fare</span>
                <span class="text-end">-{{ $canceledFare }}</span>
            </li>

            <li class="list-group-item">
                <span>Canceled Charge</span>
                <span class="text-end">-{{ $canceledTaxCharge }}</span>
            </li>

            <li class="list-group-item">
                <span>Extra Service Charge</span>
                <span class="text-end">+{{ $booking->service_cost }}</span>
            </li>

            <li class="list-group-item">
                <span>Other Charges</span>
                <span class="text-end">+{{ $booking->extraCharge() }}</span>
            </li>

            <li class="list-group-item">
                <span>Cancellation Fee</span>
                <span class="text-end">+{{ $booking->cancellation_fee }}</span>
            </li>
            
            <li class="list-group-item">
                <span>Discount</span>
                <span class="text-end">-{{ $booking->discount - $booking->discount_subtract }}</span>
            </li>
            
            <li class="list-group-item">
                <span class="fw-bold">Total Amount</span>
                <span class="fw-bold text-end"> = {{ $booking->total_amount }}</span>
            </li>

        </ul>
    </div>
    <div class="card-body">
        <h5 class="card-title">Payment Summary</h5>
        <ul class="list-group">
            <li class="list-group-item">
                <span>Total Payment</span>
                <span>+{{ $booking->total_amount }}</span>
            </li>

            <li class="list-group-item">
                <span>Payment Received</span>
                <span>-{{ $receivedPayments->sum('amount') }}</span>
            </li>

            <li class="list-group-item">
                <span>Refunded</span>
                <span>-{{ $returnedPayments->sum('amount') }}</span>
            </li>

            <li class="list-group-item fw-bold">
                @if ($due < 0)
                    <span class="text-danger">Refundable </span>
                    <span class="text-danger"> = {{ abs($due) }}</span>
                @else
                    <span>Receivable from User</span>
                    <span> = {{ abs($due) }}</span>
                @endif
            </li>
        </ul>
    </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-profile">
            <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Guest Details</h5>
              </div>
            </div>
            <div class="card-body pt-0">
              <hr class="horizontal dark mt-0">
              <div class="mt-4">
                 <span class="font-weight-light">Name </span> 
                 <h5>{{ $booking->guest_details->name }}</h5>
                 <span class="font-weight-light">Email : </span> 
                 <h5>{{ $booking->guest_details->email ?? '' }}</h5>
                 <span class="font-weight-light">Mobile : </span> 
                 <h5>{{ $booking->guest_details->mobile }}</h5>
                 <span class="font-weight-light">Address : </span> 
                 <h5>{{ $booking->guest_details->address }}</h5>
                 <span class="font-weight-light">ID Type : </span> 
                 <h5>{{ $booking->guest_details->id_card_type }}</h5>
                 <span class="font-weight-light">ID Number : </span> 
                 <h5>{{ $booking->guest_details->id_card_number }}</h5>
                <div class="row">
                    <div class="row ms-1 me-1">
                        <a class="btn btn-lg btn-info flex-grow-1" href="{{ route('generate_invoice',$booking->id) }}" target="_blank"><i class="las la-print"></i>Print Invoice</a>
                    </div>
                    <div class="row ms-1 me-1">
                        <a class="btn btn-lg btn-primary flex-grow-1" href="{{ route('reservations.payment', $booking->id) }}"><i class="la la-money-bill"></i>Go To Payment</a>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="check_out">Check Out Time</label>
                            <input class="form-control" name="check_out" id="check_out" required type="datetime-local"  value="{{ date('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                    
                    @if($booking->cautionVoucher)
                        <div class="col-12">
                            <div class="alert alert-success">
                                Caution deposit amount ₹ {{ number_format($booking->cautionVoucher->caution_amt, 2) }} already repaid in voucher :{{$booking->cautionVoucher->voucher_id}}
                            </div>
                        </div>
                    @else
                        @if($booking->caution_status == 1)
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input returnCautionDeposit" name="return_caution_deposit" type="checkbox" value="1" onchange="previewReturnCautionDeposit()">
                                    <label class="form-check-label">Return Caution Deposit</label>
                                </div>
                                <script>
                                    function previewReturnCautionDeposit(){
                                        if($('.returnCautionDeposit').is(":checked")){
                                            $('.returncautionDepositContainer').removeClass('d-none');
                                        }else{
                                            $('.returncautionDepositContainer').addClass('d-none');
                                        }
                                    }
                                    function updateReturnCautionDepositRoute(){
                                        var returnCautionDepositVoucherNumber = $('#rtn_caution_voucher').val();
                                        var returnCautionDepositRoute = $('.confirmationBtn').data('action').replace('__rtn_caution_voucher__', returnCautionDepositVoucherNumber);
                                        $('.confirmationBtn').data('action', returnCautionDepositRoute);
                                    }
                                </script>
                                <div class="form-group returncautionDepositContainer d-none">
                                    <label>Caution Deposit :</label><span>  ₹ {{ number_format($booking->caution_amount, 2) }}</span>
                                    <input type="hidden" name="caution_amount" value="{{ $booking->caution_amount }}">
                                    <input class="form-control" id="rtn_caution_voucher" name="rtn_caution_voucher" placeholder="Voucher Number" type="text" value="" onchange="updateReturnCautionDepositRoute()">
                                </div>
                            </div>
                        @endif
                    @endif
                    
                    <div class="row ms-1 me-1">
                        <button class="btn btn-lg btn-dark flex-grow-1 confirmationBtn" data-action="{{ route('reservations.checkout', [$booking->id, 'caution_amount' => $booking->caution_amount, 'rtn_caution_voucher' => '__rtn_caution_voucher__', 'check_out' => 'check_out_value']) }}" data-question="Are you sure, you want to check out this booking?">
                            <i class="las la-sign-out-alt"></i>Check Out
                        </button>
                    </div>
        
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation Alert!</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST" id="confirmation-form">
                @csrf
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn--primary">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.confirmationBtn', function() {
                var modal = $('#confirmationModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });

            $(document).ready(function() {
                $('.confirmationBtn').click(function() {
                    // Get the value of the check_out input field
                    var checkOutValue = $('#check_out').val();
        
                    // Update the data-action attribute of the checkout button
                    var newAction = $(this).data('action').replace('check_out_value', checkOutValue);
                    $(this).data('action', newAction);
                });
            });
        })(jQuery);
    </script>
@endpush


@endsection