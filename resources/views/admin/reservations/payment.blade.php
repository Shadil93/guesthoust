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
            <div class="col-md-12 mt-2 ">
                <button class="btn btn-success extraChargeBtn" data-id="{{ $booking->id }}" data-type="add">Additional Charge</button>
                <button class="btn btn-danger extraChargeBtn" data-id="{{ $booking->id }}" data-type="subtract">Remove Additional Charge</button>
                <!-- Discount -->
                <!--<button class="btn btn-success discountAmountBtn" data-id="{{ $booking->id }}" data-type="add">Add Discount Amount</button>-->
                <!--<button class="btn btn-danger discountAmountBtn" data-id="{{ $booking->id }}" data-type="subtract">Remove Discount Amount</button>-->
            </div>
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
                    <h5>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y H:i:s A') }}</h5>
                    <span class="font-weight-light">Checkin  </span> 
                    <h5>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y') }}</h5>
                    <span class="font-weight-light">Checkout  </span> 
                    <h5>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y') }}</h5>
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
                                            {{ $booked->room->roomType->name ?? 'NA'}}
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
                <span>Total Fare</span> =
                <span class="text-end">{{ $totalFare }}</span>
            </li>

            <li class="list-group-item">
                <span> Tax</span> =
                <span class="text-end">{{ $totalTaxCharge }}</span>
            </li>

            <li class="list-group-item">
                <span>Canceled Fare</span> =
                <span class="text-end">{{ $canceledFare }}</span>
            </li>

            <li class="list-group-item">
                <span>Canceled Charge</span> =
                <span class="text-end">{{ $canceledTaxCharge }}</span>
            </li>

            <li class="list-group-item">
                <span>Extra Service Charge</span> =
                <span class="text-end">{{ $booking->service_cost }}</span>
            </li>

            <li class="list-group-item">
                <span>Other Charges</span> =
                <span class="text-end">{{ $booking->extraCharge() }}</span>
            </li>

            <li class="list-group-item">
                <span>Cancellation Fee</span> =
                <span class="text-end">{{ $booking->cancellation_fee }}</span>
            </li>
            <!--<li class="list-group-item">-->
            <!--    <span>Discount</span>-->
            <!--    <span class="text-end">-{{ $booking->discount - $booking->discount_subtract }}</span>-->
            <!--</li>-->
            <li class="list-group-item">
                <span class="fw-bold">Total Amount</span> =
                <span class="fw-bold text-end"> {{ $booking->total_amount }}</span>
            </li>

        </ul>
    </div>
    <div class="card-body">
        <h5 class="card-title">Payment Summary</h5>
        <ul class="list-group">
            <li class="list-group-item">
                <span>Total Payment</span> =
                <span>{{ $booking->total_amount }}</span>
            </li>

            <li class="list-group-item">
                <span>Payment Received</span> =
                <span>{{ $receivedPayments->sum('amount') }}</span>
            </li>

            <li class="list-group-item">
                <span>Refunded</span> =
                <span>{{ $returnedPayments->sum('amount') }}</span>
            </li>

            <li class="list-group-item fw-bold">
                @if ($due < 0)
                    <span class="text-danger">Refundable </span> =
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
              </div>
            </div>
          </div>
          <div class="card card-profile mt-4">
            <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Collect Payment</h5>
              </div>
            </div>
            <div class="card-body pt-0">
              <hr class="horizontal dark mt-0">
              <div class="mt-4">
                    @if ($due < 0)
                        <h5 class="card-title">Refund Amount</h5>
                        <h5 class="text-danger text-center">Refundable Amount: {{ abs($due) }}</h5>
                    @else
                        <h5 class="card-title"> Receive Payment</h5>
                        <h5 class="text-center text-success"> Receivable Amount: {{ abs($due) }}</h5>
                    @endif
                    <form action="{{ route('reservations.payment', $booking->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Enter Amount</label>
                            <div class="input-group">
                                <input class="form-control" min="0" name="amount" required step="any" type="number">
                            </div>
                            <div class="col-12">
                                            <div class="form-group">
                                                <label>Payment Method</label>
                                                <select class="form-select"  name="payment_method" id="payment_method" aria-label="Default select example">
                                                <option selected>Payment method</option>
                                                @foreach($payment_modes as $payment_mode)
                                                <option value="{{$payment_mode->id}}">{{ $payment_mode->payment_mode }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                        </div>
                        <button @disabled(abs($due) == 0) class="btn btn-primary w-100 h-45" type="submit">Submit</button>
                    </form>
              </div>
                @if(abs($due) == 0 && $booking->status != 9)
                    <a href="{{ route('reservations.checkout', $booking->id) }}" class="btn btn-sm btn-outline-primary me-1 w-100 h-45">Check Out</a>
                @endif
            </div>
          </div>          
        </div>
      </div>
    </div>
 <!-- Extra Charge Model -->
 <div class="modal fade" id="extraChargeModal" role="dialog" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <form action="" method="post">
                            @csrf
                            <input name="type" type="hidden">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-group">
                                        <input class="form-control" min="0" name="amount" required step="any" type="number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea class="form-control" name="reason" required rows="4"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary h-45 w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Extra charge model -->

             <!-- Discount Model -->
 <div class="modal fade" id="discountAmountModal" role="dialog" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <form action="" method="post">
                            @csrf
                            <input name="type" type="hidden">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-group">
                                        <input class="form-control" min="0" name="amount" required step="any" type="number">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary h-45 w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Discount model -->
            @push('scripts')
        <script>
            (function($) {
                "use strict";
                $('.extraChargeBtn').on('click', function() {
                    let data = $(this).data();
                    let modal = $('#extraChargeModal');
                    modal.find('.modal-title').text($(this).text());
                    if (data.type == 'add') {
                        modal.find('form').attr('action', `{{ route('extra.charge.add', '') }}/${data.id}`);
                        modal.find('[name=type]').val('add');
                    } else {
                        modal.find('form').attr('action', `{{ route('extra.charge.subtract', '') }}/${data.id}`);
                        modal.find('[name=type]').val('subtract');
                    }
                    modal.modal('show');
                });
            })(jQuery);
        </script>

        <script>
            (function($) {
                "use strict";
                $('.discountAmountBtn').on('click', function() {
                    let data = $(this).data();
                    let modal = $('#discountAmountModal');
                    modal.find('.modal-title').text($(this).text());
                    if (data.type == 'add') {
                        modal.find('form').attr('action', `{{ route('discount.add', '') }}/${data.id}`);
                        modal.find('[name=type]').val('add');
                    } else {
                        modal.find('form').attr('action', `{{ route('discount.subtract', '') }}/${data.id}`);
                        modal.find('[name=type]').val('subtract');
                    }
                    modal.modal('show');
                });
            })(jQuery);
        </script>
@endpush
@endsection