<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Invoice</title>
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

        .address {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .invoice-to {
            width: 45%;
            float: left;
        }

        .billing-info {
            width: 45%;
            float: right;
            text-align: right;
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

        .total-row {
            font-weight: bold;
        }

        .title {
            margin-top: 0;
        }

        td.text-end {
            text-align: right;
        }

        
    </style>
</head>

<body>
    @php
        $extraService = count($booking->usedExtraService);
        $due = $booking->total_amount - $booking->paid_amount;
    @endphp
    <header>
        <img src="https://www.kadampuzhadevaswom.com/themes/user/img/website/sree-kadampuzha-bhagavathy-temple-logo.jpg" class="navbar-brand-img h-100" alt="main_logo">
    </header>
    <main>
        <div class="section address">
            <h5>Invoice Date:  {{ date('d/m/Y') }}</h5>
            <div class="invoice-to">
                <h3>Invoice To</h3>
                <ul class="list" style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <div class="list list--row gap-5rem">
                            <span class="strong">Name :</span>
                            <span>{{ $booking->guest_details->name }}</span>
                        </div>
                    </li>
                    <li>
                        <div class="list list--row gap-5rem">
                            <span class="strong">Email :</span>
                            <span>{{ $booking->guest_details->email }}</span>
                        </div>
                    </li>
                    <li>
                        <div class="list list--row gap-5rem">
                            <span class="strong">Mobile :</span>
                            <span>{{ $booking->guest_details->mobile }}</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="billing-info">
                <h3>Billing Information</h3>
                <ul class="text-end" style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <span class="d-inline-block strong">Booking No:</span>
                        <span class="d-inline-block">{{ $booking->booking_number }}</span>
                    </li>
                    <li>
                        <span class="d-inline-block strong">Total Amount :</span>
                        <span class="d-inline-block">{{ number_format($booking->total_amount ,2) }}</span>
                    </li>
                    <li>
                        <span class="d-inline-block strong">Paid Amount :</span>
                        <span class="d-inline-block">{{ number_format($booking->paid_amount ,2) }}</span>
                    </li>
                </ul>


            </div>
            <div style="clear:both;"></div>
        </div>
        <div class="section">
            <h3 class="title">Room Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>No of Rooms</th>
                        <th>Fare</th>
                    </tr>
                </thead>
                @php
                    $uniqueRooms = $booking->activeBookedRooms->unique('room_type_id');
                    $activeBookedRooms = $booking->activeBookedRooms;
                    $totalFare = $booking->activeBookedRooms->sum('fare');
                    $roomCounts = $activeBookedRooms->groupBy('room.roomType.name')->map->count();
                @endphp


                <tbody>
                @foreach ($uniqueRooms as $booked)
                    <tr>
                        <td>{{ $booked->room->roomType->name }}</td>
                        <td>{{ $roomCounts[$booked->room->roomType->name] }} </td>
                        <td class="text-end">{{ number_format($booked->fare, 2) }}</td>
                    </tr>
                @endforeach
                    <tr >
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                    </tr>

                    <tr class="custom-table__subhead">
                        <td class="text-end" colspan="2">Total Fare</td>
                        <td class="text-end">{{ number_format($totalFare, 2) }}</td>
                    </tr>

                    @if (!$extraService)

                        @if ($booking->cancellation_fee > 0)
                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="2">Cancellation Fee</td>
                                <td class="text-end">{{ number_format($booking->cancellation_fee, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="custom-table__subhead">
                            <td class="text-end" colspan="2">Tax</td>
                            <td class="text-end">{{ number_format($booking->tax_charge, 2) }}</td>
                        </tr>
                        @if ($booking->extraCharge() > 0)
                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="2">Other Charges</td>
                                <td class="text-end">{{ number_format($booking->extraCharge() , 2) }}</td>
                            </tr>
                        @endif

                        <tr class="custom-table__subhead">
                            <td class="text-end" colspan="2">Total</td>
                            <td class="text-end">{{ number_format($booking->total_amount , 2) }}</td>
                        </tr>

                        @if ($due > 0)
                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="2">Due</td>
                                <td class="text-end">{{ number_format($due , 2) }}</td>
                            </tr>
                        @elseif($due < 0)
                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="2">Refundable</td>
                                <td class="text-end">{{ abs($due) }}</td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
            @if ($extraService)
                        @php
                            $extraServices = $booking->usedExtraService->groupBy('service_date');
                        @endphp
                        <div class="extra-service">
                            <div class="mt-10">
                                <h5 class="title">Service Details</h5>
                            </div>
                            <table class="table-bordered custom-table table">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Service</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($extraServices as $key => $serviceItems)
                                        <tr class="custom-table__subhead">
                                            <td colspan="5" style="text-align: center;">{{ $key }}</td>
                                        </tr>
                                        @foreach ($serviceItems as $service)
                                            <tr>
                                                <td>{{ $service->room->room_number }}</td>
                                                <td>{{ $service->extraService->name }}</td>
                                                <td>{{ $service->qty }}</td>
                                                <td>{{ $service->unit_price }}</td>
                                                <td>{{ $service->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="4">@lang('Total Charge')</td>
                                        <td>{{ $booking->service_cost }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="summary avoid_page_break">
                            <div class="mt-10">
                                <h5 class="title">Billing Details</h5>
                            </div>
                            <table class="table-bordered custom-table table">
                                <tbody>
                                    <tr>
                                        <td class="text-end">Total Fare</td>
                                        <td class="text-end">{{ $totalFare }}</td>
                                    </tr>
                                    @if ($booking->cancellation_fee > 0)
                                        <tr>
                                            <td class="text-end">Cancellation Fee</td>
                                            <td class="text-end">{{ $booking->cancellation_fee }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-end"> Charge</td>
                                        <td class="text-end">{{ $booking->tax_charge }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Service Charge</td>
                                        <td class="text-end">{{ $booking->service_cost }}</td>
                                    </tr>
                                    @if ($booking->extraCharge() > 0)
                                        <tr>
                                            <td class="text-end">Other Charges</td>
                                            <td class="text-end">{{ $booking->extraCharge() }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-end">Total</td>
                                        <td class="text-end">{{ $booking->total_amount }}</td>
                                    </tr>

                                    @if ($due > 0)
                                        <tr class="text-end">
                                            <td class="text-end">Due</td>
                                            <td class="text-end">{{ $due }} </td>
                                        </tr>
                                    @elseif($due < 0)
                                        <tr class="text-end">
                                            <td class="text-end">Refundable</td>
                                            <td class="text-end">{{ abs($due) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>

</html>
