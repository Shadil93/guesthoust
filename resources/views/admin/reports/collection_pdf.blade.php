<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Report</title>
    <style>
        /* Define your PDF styles here */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right; 
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border: none; 
        }

        .footer-table td, .footer-table th {
            text-align: right; 
            padding: 8px;
            border: 1px solid #fff;
        }
        .footer-table td:nth-child(1) {
            width: 80%; 
            text-align: right; 
        }
        .footer-table td:nth-child(2) {
            width: 30%; 
            text-align: right; 
        }
        .manager-text {
            position: absolute; 
            bottom: 0; 
            right: 0; 
        }
    </style>
</head>
<body>
    <h2 class="center">SREE KADAMPUZHA BHAGAWATHI DEVASWOM</h2>
    <h3 class="center"> Devaswom Rest House</h3>
    <h5 class="center">Collection Report</h5>
    <p class="text-right">Start Date: {{ \Carbon\Carbon::parse($startDate)->format('j-M-Y') }} - End Date: {{ \Carbon\Carbon::parse($endDate)->format('j-M-Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Serial No</th>
                <th>Receipt No</th>
                <th>Room Rent</th>
                <th>Tax</th>
                <th>Additional Rent</th>
                <th>Advance</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $key => $booking)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $booking->booking_number }}</td>
                    <td>{{ number_format($booking->booking_fare, 2) }}</td>
                    <td>{{ number_format($booking->tax_charge, 2) }}</td>
                    <td>{{ number_format($booking->extra_charge, 2) }}</td>
                    <td>{{ number_format($booking->paid_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Totals</td>
                <td>{{ number_format($totalBookingFare, 2) }}</td>
                <td>{{ number_format($totalTaxCharge, 2) }}</td>
                <td>{{ number_format($totalExtraCharge, 2) }}</td>
                <td>{{ number_format($totalPaidAmount, 2) }}</td>
                <td class="text-right">{{ number_format($totalRoundedValues, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <table class="footer-table">
        <tr>
            <td>Total Rent:</td>
            <td>{{ number_format($totalBookingFare, 2) }}</td>
        </tr>
        <tr>
            <td>Tax Collected:</td>
            <td>{{ number_format($totalTaxCharge, 2) }}</td>
        </tr>
        <tr>
            <td>Grand Total:</td>
            <td>{{ number_format($totalRoundedValues, 2) }}</td>
        </tr>
    </table>
    <h4 class="manager-text">Manager</h4>
</body>
</html>
