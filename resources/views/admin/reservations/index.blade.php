@extends('admin.layouts.app')
@section('main')
<link href="{{ asset('assets/css/datepicker.min.css') }}" rel="stylesheet" />

<div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-body px-2 pt-2 pb-2">
                    <a href="{{ route('reservations.index') }}" class="btn btn-small btn-warning">All Bookings</a>
                    <a href="{{ route('todays.checkin') }}" class="btn btn-small btn-primary"> Expected CheckIn</a>
                    <!--<a href="{{ route('todays.checkout') }}" class="btn btn-small btn-primary">Today's Checkout</a>-->
                    <a href="{{ route('reservations.active') }}" class="btn btn-small btn-primary">Active Bookings</a>
                    <a href="{{ route('reservations.checkedout') }}" class="btn btn-small btn-primary">Checked Out List</a>
                    <a href="{{ route('reservations.canceled') }}" class="btn btn-small btn-primary">Cancelled Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>List Of Reservations</h6>
              <div class="search-form col-10">
                <form action="{{ route('reservations.index') }}" method="GET" onsubmit="return validateForm()">
                  <div class="row">
                      <div class="col-md-4 mb-3">
                          <label>Check In - Check Out Date</label>
                          <div class="form-group flex-fill">
                              <input autocomplete="off" class="datepicker-here form-control bg--white" data-language="en" data-multiple-dates-separator=" - " data-position="bottom left" data-range="true" name="date" id="date" placeholder="Select Date"  type="text">
                          </div>
                      </div>
                      <div class="col-md-8 mb-3">
                          <label>Search here</label>
                          <div class="input-group">
                              <input type="text" name="search" class="form-control" placeholder="Search by booking number, guest name, mobile, or email" value="{{ request()->input('search') }}" style="height: fit-content;" >
                              <div class="input-group-append">
                                  <button type="submit" class="btn btn-primary">Search</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </form>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Booking Number</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Guest</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Checkin - Checkout</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Amount</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Paid</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Due</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-secondary opacity-7 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $key => $booking)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-small mb-0">{{ $bookings->firstItem() + $key }}</p>
                                </td>
                                <td>
                                    @if ($booking->key_status)
                                    <span class="text-warning ">
                                        <i class="ni ni-key-25 text-warning text-sm opacity-10"></i>
                                    </span>
                                    @endif
                                    <span class="fw-bold">#{{ $booking->booking_number }}</span><br>
                                    <em class="text-muted text--small">
                                        {{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y H:i:s A') }}
                                    </em><br>
                                    <em class="text-muted text--small">{{$booking->bookedRooms->first()->room->roomType->name ?? 'NA'}}</em>
                                </td>
                                <td>
                                    <span class="small">{{ $booking->guest_details->name }}</span>
                                    <br>
                                    <span class="fw-bold">{{ $booking->guest_details->mobile }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y') }}
                                    <br>
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y') }}
                                </td>
                                <td> ₹ {{ number_format($booking->total_amount , 2) }}</td>
                                <td> ₹ {{ number_format($booking->paid_amount , 2) }}</td>
                                @php
                                $due = $booking->total_amount - $booking->paid_amount;
                                @endphp
                                <td class="@if ($due < 0) text--danger @elseif($due > 0) text--warning @endif">
                                     ₹ {{ number_format($due , 2) }}
                                </td>
                                <td>
                                    @php echo $booking->statusBadge; @endphp
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('reservations.show',$booking->id) }}" class="btn btn-secondary font-weight-bold text-xs me-2" data-toggle="tooltip" data-original-title="Edit user">
                                        Details
                                    </a>
                                    <!-- @if($booking->statusBadge == '<small class=\'badge badge-sm bg-gradient-primary\'>Reserved</small>')
                                        <a href="{{ route('generate_invoice',$booking->id) }}" class="btn btn-info font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Print" target="_blank">
                                            Print 
                                        </a>
                                    @endif -->
                                    <a href="{{ route('generate_invoice',$booking->id) }}" class="btn btn-info font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Print" target="_blank">
                                        Print 
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="3">
                                    No Data Found!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="col-12 d-flex justify-content-center">
                        {{ $bookings->links('admin.layouts.pagination') }}
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/js/plugins/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datepicker.en.js') }}"></script>
<!-- <script> 
    if (!$('.datepicker-here').val()) {
        $('.datepicker-here').datepicker({
            dateFormat: 'dd/mm/yyyy'
        });
    }
</script> -->
<!-- <script>
    function validateForm() {
        var dateInput = document.getElementById('date').value;
        var dateParts = dateInput.split(' - ');
        if (dateParts.length !== 2) {
            alert('Please select both check-in and check-out dates.');
            return false;
        }
        return true;
    }
</script> -->
@endsection