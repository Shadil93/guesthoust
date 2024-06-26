<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<title>Booking Reciept</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    	body{margin-top:20px;
background-color:#eee;
}

.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 1rem;
}
    </style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<div class="invoice-title">
<h4 class="float-end font-size-15"> #{{ $booking->booking_number }} 
    {{-- <span class="badge bg-success font-size-12 ms-2">Paid</span> --}}
</h4>
<div class="mb-4">
<img src="{{ asset('assets/img/kadampuzha-bhagavathy.png') }}" class="navbar-brand-img h-100" alt="main_logo" style="max-width: 60px;">
<h2 class="mb-1 text-muted">Kadampuzha Bhagavathy Devaswom Guest House</h2>
</div>
<div class="text-muted">
<p class="mb-1">Kadampuzha Devaswom, P.O.Kadampuzha, Malappuram Dist. 676553, Kerala, South India</p>
<p class="mb-1">kadampuzhatemple@gmail.com</p>
<p><i class="uil uil-phone me-1"></i>0494-2618000</p>
</div>
</div>
<hr class="my-4">
<div class="row">
<div class="col-sm-6">
<div class="text-muted">
<h5 class="font-size-16 mb-3">Billed To:</h5>

<h5 class="font-size-15 mb-2">{{ $booking->guest_details->name }}</h5>
<p class="mb-1">{{ $booking->guest_details->address  }}</p>
<p class="mb-1">{{ $booking->guest_details->email  }}</p>
<p>{{ $booking->guest_details->mobile }}</p>
<hr>
<div class="mt-1">
    <h6 class="font-size-15 mb-2">No. of Adults: {{ $booking->no_adults }}</h6>
</div>
<div class="mt-1">
    <h6 class="font-size-15 mb-2">No. of Childs: {{ $booking->no_childs }}</h6>
</div>
</div>
</div>

<div class="col-sm-6">
    <div class="text-muted text-sm-end">
        <div class="mt-1">
            <h5 class="font-size-15 mb-1"> Date:</h5>
            <p><?php print_r(date('d/m/Y')); ?></p>
        </div>
       

        <div class="mt-1">
            <h5 class="font-size-15 mb-1">Booking Date:</h5>
            <p><?php print_r(date('d/m/Y')); ?></p>
        </div>
        <div class="mt-1">
            <h5 class="font-size-15 mb-1">Mode:</h5>
            <p>{{ $payment_method->paymentMode->payment_mode ?? '' }}</p>
        </div>
        @isset($booking->checked_in_at)
            <div class="mt-1">
                <h6 class="font-size-15 mb-1">Checkin At:</h6>
                <p>{{ \Carbon\Carbon::parse($booking->checked_in_at)->format('d/m/Y h:i A') }}</p>
            </div>
        @endisset
        @isset($booking->checked_out_at)
            <div class="mt-1">
                <h6 class="font-size-15 mb-1">Checkout At:</h6>
                <p>{{ \Carbon\Carbon::parse($booking->checked_out_at)->format('d/m/Y h:i A') }}</p>
            </div>
        @endisset
    </div>
</div>

</div>

<div class="py-2">
<h5 class="font-size-15">Booking Summary</h5>
<div class="table-responsive">
<table class="table align-middle table-nowrap table-centered mb-0">
<thead>
<tr>
<th style="width: 70px;">No.</th>
<th class="text-center">Room Details</th>
<th class="text-center">Booked For</th>
<th class="text-center">Fare / Night</th>
<th class="text-center" >Tax</th>
</tr>
</thead>
<tbody>
@php

    $totalFare = $booking->bookedRooms->sum('fare');
    $totalTaxCharge = $booking->bookedRooms->sum('tax_charge');
    $roomCount = $booking->bookedRooms->unique('room_id')->count();
    $cautionDeposit = $booking->caution_amount;
    $totalAmount = $totalFare + $totalTaxCharge ;
    $paid = $booking->paid_amount;
    $due = $booking->total_amount - $booking->paid_amount;
    $canceledFare = $booking->bookedRooms->where('status', 3)->sum('fare');
    $canceledTaxCharge = $booking->bookedRooms->where('status', 3)->sum('tax_charge');
    $extracharges = $booking->extra_charge;
    $i=1;
@endphp
@foreach ($booking->bookedRooms->groupBy('booked_for') as  $key =>  $bookedRoom)
                                @foreach ($bookedRoom as $booked)
<tr>
<th scope="row">{{ $i }}</th>
<td class="text-center">
<div>
<h5 class="text-truncate font-size-14 mb-1">{{ $booked->room->roomType->name ?? 'NA'}}</h5>
<p class="text-muted mb-0">
    Room No: {{ $booked->room->room_number ?? 'NA'}}
    @if ($booked->status == 3)
        <span class="text-danger text-sm">(Canceled)</span>
    @endif
</p>
</div>
</td>
<td class="text-center">{{ \Carbon\Carbon::parse($booked->booked_for)->format('d-m-Y') }}</td>
<td class="text-center">{{ $booked->fare }}</td>
<td class="text-center"> {{ $booked->tax_charge }}</td>
</tr>
@php
    $i++;
@endphp
@endforeach
@endforeach

<tr>
<th scope="row" colspan="3" class="text-end">Sub Total :</th>
<td class="text-end" colspan="2" style="padding-right: 75px;">₹ {{ number_format($totalFare, 2) }}</td>
</tr>

<tr>
<th scope="row" colspan="3" class="border-0 text-end">
Tax :</th>
<td class="border-0 text-end" colspan="2" style="padding-right: 75px;">₹ {{ number_format($totalTaxCharge, 2) }}</td>
</tr>
<tr>
<th scope="row" colspan="3" class="border-0 text-end">Total :</th>
<td class="border-0 text-end" colspan="2" style="padding-right: 75px;"><h5 class="m-0 fw-bold">₹ {{ number_format($totalAmount, 2) }}</h5></td>
</tr>
@if ($booked->status == 3)
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">Cancelled Fare:</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;"><h5 class="m-0 fw-semibold">₹ {{ number_format($canceledFare, 2) }}</h5></td>
</tr>
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">Cancelled Tax:</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;"><h5 class="m-0 fw-semibold">₹ {{ number_format($canceledTaxCharge, 2) }}</h5></td>
</tr>
@endif
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">
    Caution Deposit :</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;">₹ {{ number_format($cautionDeposit, 2) }}</td>
</tr>
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">
    Extra Charges :</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;">₹ {{ number_format($extracharges, 2) }}</td>
</tr>
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">Paid :</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;"><h5 class="m-0 fw-semibold">₹ {{ number_format($paid, 2) }}</h5></td>
</tr>
<tr>
    <th scope="row" colspan="3" class="border-0 text-end">Due :</th>
    <td class="border-0 text-end" colspan="2" style="padding-right: 75px;"><h5 class="m-0 fw-semibold">₹ {{ number_format($due, 2) }}</h5></td>
</tr>

</tbody>
</table>
</div>
<div class="d-print-none mt-4">
<div class="float-end">
<a href="javascript:window.print()" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>