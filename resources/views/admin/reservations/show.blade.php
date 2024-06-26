@extends('admin.layouts.app')
@section('main')
@php
    $totalFare = $booking->bookedRooms->sum('fare');
    $totalTaxCharge = $booking->bookedRooms->sum('tax_charge');
    $canceledFare = $booking->bookedRooms->where('status', 3)->sum('fare');
    $canceledTaxCharge = $booking->bookedRooms->where('status', 3)->sum('tax_charge');
    $due = $booking->total_amount - $booking->paid_amount;
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
            <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">Back</a>
        </div>
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto my-auto">
            <div class="mt-4">
                  <a href="{{ route('reservations.booked.rooms',$booking->id) }}" class="btn btn-sm btn-outline-primary me-1">Booked Rooms</a>
                  <!--<a class="btn btn-sm btn-outline-primary me-1">Add On Service</a>-->
                  <a href="{{ route('reservations.payment',$booking->id) }}" class="btn btn-sm btn-outline-primary me-1">Payment</a>
                  @if ($booking->status == 1)
                    @if ($booking->key_status == 0)
                            <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('reservations.checkin',$booking->id) }}">
                                Checkin
                            </a>
                    @endif
                    @if ($booking->key_status == 1)
                        <a href="{{ route('reservations.checkout', $booking->id) }}" class="btn btn-sm btn-outline-primary me-1">Check Out</a>
                    @endif

                  @endif
                  @if ($booking->status == 9)
                    <a  href="{{ route('generate_invoice',$booking->id) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">Invoice</a>
                  @endif 
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
                    <h5>{{ $booking->created_at->setTimezone('Asia/Kolkata')->format('d M Y h:i:s A') }}</h5>
                    <span class="font-weight-light">Checkin  </span> 
                    <h5>{{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y') }}</h5>
                    <span class="font-weight-light">Checkout  </span> 
                    <h5>{{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y') }}</h5>
                </div>
                <div class="col-md-6 mt-4">
                    <span class="font-weight-light">Total Rooms  </span> 
                    <h5>{{ $booking->bookedRooms->count() }}</h5>
                    <span class="font-weight-light">Total Charge </span> 
                    <h5> ₹ {{ number_format($booking->total_amount, 2) }}</h5>
                    <span class="font-weight-light">Paid Amount  </span> 
                    <h5>₹ {{ number_format($booking->paid_amount, 2) }}</h5>
                    @if ($due < 0)
                    <span class="font-weight-light">Refundable  </span> 
                    <h5>₹ {{ number_format(abs($due), 2) }}</h5>
                    @else
                    <span class="font-weight-light">Receivable From Customer  </span> 
                    <h5 class="@if ($due > 0) text-danger @else text-success @endif"> ₹ {{ number_format(abs($due), 2) }}</h5>
                    @endif
                </div>
                <div class="col-md-12 mt-4">
                    <hr class="horizontal dark mt-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="font-weight-light">Checked In At  </span> 
                            <h5>
                                {{ $booking->checked_in_at ? \Carbon\Carbon::parse($booking->checked_in_at)->format('d-m-Y h:i:s A') : 'N/A' }}
                            </h5>
                        </div>
                        <div>
                            <span class="font-weight-light">Checked Out At  </span> 
                            <h5>
                                {{ $booking->checked_out_at ? \Carbon\Carbon::parse($booking->checked_out_at)->format('d-m-Y h:i:s A') : 'N/A' }}
                            </h5>
                        </div>
                    </div>
                </div>
              </div>        
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
                 <span class="font-weight-light">No Of Adults : </span> 
                 <h5>{{ $booking->no_adults ?? 0 }}</h5>
                 <span class="font-weight-light">No of Childrens : </span> 
                 <h5>{{ $booking->no_childs ?? 0 }}</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row m-2">
        <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Booked Rooms</h5>
              </div>
            </div>
            <div class="card-body">
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
                                                {{ \Carbon\Carbon::parse($booked->booked_for)->format('d-m-Y') }}
                                            </td>
                                        @endif
                                        <td class="text-center" data-label="Room Type">
                                            {{ $booked->room->roomType->name ?? 'NA'}}
                                        </td>
                                        <td data-label="Room No.">
                                            {{ $booked->room->room_number ?? 'NA' }}
                                            @if ($booked->status == 3)
                                                <span class="text-danger text-sm">(Canceled)</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-end" data-label="Fare">
                                           ₹ {{ number_format($booked->fare , 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            
                        
                            <tr>
                                <td class="text-end" colspan="3">
                                    <span class="fw-bold">Total Fare</span>
                                </td>
                                <td class="fw-bold text-end">
                                     ₹ {{ number_format($totalFare , 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
      </div>
      <!--<div class="row m-2">-->
      <!--  <div class="card">-->
      <!--      <div class="card-header pb-0">-->
      <!--        <div class="d-flex justify-content-between">-->
      <!--          <h5 class="text-uppercase text-sm">Add On Services</h5>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="card-body">-->
      <!--        <hr class="horizontal dark mt-0">-->
      <!--          @if ($booking->usedExtraService->count())-->
      <!--              <div class="table-responsive--sm">-->
      <!--                  <table class="table table-striped">-->
      <!--                      <thead>-->
      <!--                          <tr>-->
      <!--                              <th>Date</th>-->
      <!--                              <th>Room No.</th>-->
      <!--                              <th>Service</th>-->
      <!--                              <th>Total</th>-->
      <!--                          </tr>-->
      <!--                      </thead>-->
      <!--                      <tbody>-->
      <!--                          @foreach ($booking->usedExtraService->groupBy('service_date') as $services)-->
      <!--                              @foreach ($services as $service)-->
      <!--                                  <tr>-->
      <!--                                      @if ($loop->first)-->
      <!--                                          <td class="text-center" data-label="Date" rowspan="{{ count($services) }}">-->
      <!--                                              {{ $service->service_date}}-->
      <!--                                          </td>-->
      <!--                                      @endif-->
      <!--                                      <td data-label="Room No.">-->
      <!--                                          {{ $service->room->room_number }}-->
      <!--                                      </td>-->
      <!--                                      <td data-label="Service">-->
      <!--                                          {{ $service->extraService->name }}<br>-->
      <!--                                          {{ $service->unit_price }} x {{ $service->qty }}-->
      <!--                                      </td>-->
      <!--                                      <td data-label="Total">-->
      <!--                                          {{ $service->total_amount }}-->
      <!--                                      </td>-->
      <!--                                  </tr>-->
      <!--                              @endforeach-->
      <!--                          @endforeach-->
      <!--                           <tr>-->
      <!--                              <td class="text-end" colspan="3">-->
      <!--                                  <span class="fw-bold">Total</span>-->
      <!--                              </td>-->
      <!--                              <td class="fw-bold text-end">-->
      <!--                                  {{ $booking->service_cost }}-->
      <!--                              </td>-->
      <!--                          </tr>-->
      <!--                      </tbody>-->
      <!--                  </table>-->
      <!--              </div>-->
      <!--          @else-->
      <!--              <div class="text-center">-->
      <!--                  <h6 class="p-3">No add on service used</h6>-->
      <!--              </div>-->
      <!--          @endif-->
      <!--      </div>-->
      <!--  </div> -->
      <!--</div>-->
      @php
        $receivedPyaments = $booking->payments->where('type', 'RECEIVED');
        $returnedPyaments = $booking->payments->where('type', 'RETURNED');
    @endphp
      <div class="row m-2">
        <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Payment Received</h5>
              </div>
            </div>
            <div class="card-body">
              <hr class="horizontal dark mt-0">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Time</th>
                                <th>Payment Type</th>
                                <th>payment method</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($receivedPyaments as $payment)
                                <tr>
                                    <td class="text-start">{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y h:i:s A') }}</td>
                                    <td>Cash Payment</td>
                                    <td>{{$payment->paymentMode?->payment_mode}}</td>
                                    <td class="text-end"> ₹ {{ number_format( $payment->amount , 2) }}</td>
                                    
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-end fw-bold" colspan="2">Total</td>
                                <td class="text-end fw-bold">
                                  ₹ {{ number_format($receivedPyaments->sum('amount') , 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
      </div>
      @if ($returnedPyaments->count())
      <div class="row m-2">
        <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Payment Returned</h5>
              </div>
            </div>
            <div class="card-body">
              <hr class="horizontal dark mt-0">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Time</th>
                                <th>Payment Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returnedPyaments as $payment)
                                <tr>
                                    <td class="text-start">{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y h:i:s A') }}</td>
                                    <td>Cash Payment</td>
                                    <td>₹ {{ number_format($payment->amount , 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-end" colspan="2">
                                    <span class="fw-bold">Total</span>
                                </td>
                                <td class="text-end fw-bold">
                                    ₹ {{ number_format($returnedPyaments->sum('amount') , 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
      </div>
      @endif
      <div class="row m-2">
        <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Payment Info</h5>
              </div>
            </div>
            <div class="card-body">
              <hr class="horizontal dark mt-0">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Total Fare</td>
                                <td>₹ {{ number_format($totalFare , 2) }}</td>
                            </tr>
                            <tr>
                                <!--<td>Charge<small>({{ $booking->taxPercentage() }}%)</small></td>-->
                                <td>Tax</td>
                                <td>  ₹ {{ number_format($totalTaxCharge , 2) }}</td>
                            </tr>
                            <tr>
                                <td>Canceled Tax</td>
                                <td>  ₹ {{ number_format($canceledTaxCharge , 2) }}</td>
                            </tr>

                            <tr>
                                <td>Canceled</td>
                                <td>  ₹ {{ number_format($canceledFare , 2) }}</td>
                            </tr>

                            <tr>
                                <td>Extra Service Charge</td>
                                <td>  ₹ {{ number_format($booking->service_cost , 2) }}</td>
                            </tr>

                            @if ($booking->extraCharge() > 0)
                                <tr>
                                    <td>Other Charges</td>
                                    <td>  ₹ {{ number_format($booking->extraCharge() , 2) }}</td>
                                </tr>
                            @endif

                            @if ($booking->cancellation_fee > 0)
                                <tr>
                                    <td>Cancellation Fee</td>
                                    <td>  ₹ {{ number_format($booking->cancellation_fee , 2) }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="fw-bold">Total Amount</td>
                                <td class="fw-bold">   ₹ {{ number_format($booking->total_amount , 2) }}</td>
                            </tr>

                            <tr>
                                <td>Payment Received</td>
                                <td> ₹ {{ number_format($receivedPyaments->sum('amount') , 2) }}</td>
                            </tr>

                            <tr>
                                <td>Refunded</td>
                                <td> ₹ {{ number_format($returnedPyaments->sum('amount') , 2) }}</td>
                            </tr>

                            @php $due = $booking->due(); @endphp

                            <tr>
                                @if ($due < 0)
                                    <td class="text-warning fw-bold">Refundable</td>
                                    <td class="text-warning fw-bold">₹ {{ number_format(abs($due) , 2) }}</td>
                                @else
                                    <td class="@if ($due > 0) text-danger @else text-success @endif fw-bold">Receivable from User</td>
                                    <td class="@if ($due > 0) text-danger @else text-success @endif fw-bold"> ₹ {{ number_format(abs($due) , 2) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
      </div>
    </div>

       


@endsection