@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-body px-2 pt-2 pb-2">
                    <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">All Bookings</a>
                    <a href="{{ route('todays.checkin') }}" class="btn btn-small {{ Request::is('todays/check-in') ? 'btn-warning' : 'btn-primary' }}"> Expected CheckIn</a>
                    <!-- <a href="{{ route('todays.checkout') }}" class="btn btn-small {{ Request::is('todays/checkout') ? 'btn-warning' : 'btn-primary' }}">Today's Checkout</a> -->
                    <a href="{{ route('reservations.active') }}" class="btn btn-small {{ Request::is('reservations/active') ? 'btn-warning' : 'btn-primary' }}">Active Bookings</a>
                    <a href="{{ route('reservations.checkedout') }}" class="btn btn-small {{ Request::is('checked-out-booking') ? 'btn-warning' : 'btn-primary' }}">Checked Out Bookings</a>
                    <a href="{{ route('reservations.canceled') }}" class="btn btn-small {{ Request::is('canceled-bookings') ? 'btn-warning' : 'btn-primary' }}">Cancelled Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>{{ $pageTitle ?? '' }}</h6>
              <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
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
                      <td>
                        <span class="fw-bold">#{{ $booking->booking_number }}</span><br>
                        <em class="text-muted text--small">
                          {{ $booking->created_at->setTimezone('Asia/Kolkata')->format('d M Y h:i:s A') }}
                        </em>
                      </td>
                      <td>
                        <span class="small">{{ $booking->guest_details->name }}</span>
                        <br>
                        <span class="fw-bold">{{ $booking->guest_details->email }}</span>
                      </td>
                      <td>
                        {{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y') }}
                        <br>
                        {{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y') }}
                      </td>
                      <td>{{ 'INR '.$booking->total_amount }}</td>
                      <td>{{ 'INR '.$booking->paid_amount }}</td>

                      @php
                          $due = $booking->total_amount - $booking->paid_amount;
                      @endphp

                      <td class="@if ($due < 0) text--danger @elseif($due > 0) text--warning @endif">
                          {{ 'INR' }}{{ $due }}
                      </td>
                      <td>
                        @php echo $booking->statusBadge; @endphp
                      </td>
                      <td class="text-end">
                        <a href="{{ route('reservations.show',$booking->id) }}" class="btn btn-secondary font-weight-bold text-xs me-2" data-toggle="tooltip" data-original-title="Edit user">
                          Details
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
@endsection