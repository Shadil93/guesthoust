<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Booking Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #991403;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        main {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="row">     
        <div class="text">
    <!-- <header>
        <img src="https://www.kadampuzhadevaswom.com/themes/user/img/website/sree-kadampuzha-bhagavathy-temple-logo.jpg" class="navbar-brand-img h-100" alt="main_logo">
    </header> -->
    <div class="mb-4" style="text-align: center;">
<!-- <img src="{{ asset('assets/img/kadampuzha-bhagavathy.png') }}" class="navbar-brand-img h-100" alt="main_logo" style="max-width: 60px;"> -->
<h2 class="mb-1 text-muted">Kadampuzha Bhagavathy Devaswom Guest House</h2>
</div>
<div class="text-muted" style="text-align: center;">
<p class="mb-1">Kadampuzha Devaswom, P.O.Kadampuzha, Malappuram Dist. 676553, Kerala, South India</p>
<p class="mb-1">kadampuzhatemple@gmail.com</p>
<p><i class="uil uil-phone me-1"></i>0494-2618000</p>
</div>
    <h4 style="text-align: center">Booking Summary for {{ $selectedYear->format('Y') }}</h4>
    <main>
        <table class="custom-table ">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Number of Bookings</th>
                    <th>Cash</th>
                    <th>Card</th>
                    <th>UPI</th>
                    <th>Total Collection</th>
                </tr>
            </thead>
            <tbody>
             @php
                 $totalBookings = 0;
                 $totalCash = 0;
                 $totalCard = 0;
                 $totalUpi = 0;
                 $totalCollection = 0;
             @endphp
                @foreach ($bookings as $month => $data)
                    @php
                        $totalBookings += $data['bookingCount'];
                        $totalCash += $data['cash_collection'];
                        $totalCard += $data['card_collection'];
                        $totalUpi += $data['upi_collection'];
                        $totalCollection += $data['totalCollection'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $month }}</td>
                        <td class="text-center">{{ $data['bookingCount'] }}</td>
                        <td>{{ $data['cash_collection'] }}</td>
                        <td>{{ $data['card_collection'] }}</td>
                        <td>{{ $data['upi_collection'] }}</td>
                        <td class="text-end">{{ $data['totalCollection'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center"><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ $totalBookings }}</strong></td>
                    <td><strong>{{ $totalCash }}</strong></td>
                    <td><strong>{{ $totalCard }}</strong></td>
                     <td><strong>{{ $totalUpi }}</strong></td>
                    <td class="text-end"><strong>{{ $totalCollection }}</strong></td>
                </tr>
            </tbody>
        </table>
    </main>
    <div class="d-print-none mt-4">
<div class="float-end">
<a href="javascript:window.print()" class="btn btn-success me-1"><i class="fa fa-print">Print</i></a>
</div>
</div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
