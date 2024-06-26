<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Booking Report</title>
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
    <header>
        <img src="https://www.kadampuzhadevaswom.com/themes/user/img/website/sree-kadampuzha-bhagavathy-temple-logo.jpg" class="navbar-brand-img h-100" alt="main_logo">
    </header>
    <h4 style="text-align: center">Booking Summary for {{ $selectedYear->format('Y') }}</h4>
    <main>
        <table class="custom-table ">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Number of Bookings</th>
                    <th>Total Collection</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBookings = 0; $totalCollection = 0; @endphp
                @foreach ($bookings as $month => $data)
                    @php
                        $totalBookings += $data['bookingCount'];
                        $totalCollection += $data['totalCollection'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $month }}</td>
                        <td class="text-center">{{ $data['bookingCount'] }}</td>
                        <td class="text-end">{{ $data['totalCollection'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center"><strong>Total</strong></td>
                    <td class="text-center"><strong>{{ $totalBookings }}</strong></td>
                    <td class="text-end"><strong>{{ $totalCollection }}</strong></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
