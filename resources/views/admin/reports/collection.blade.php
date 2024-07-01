@extends('admin.layouts.app')

@section('main')

<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center; 
    }
</style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('reports.custom') }}" class="btn btn-small btn-primary">Summary</a>
                            <a href="{{ route('reports.monthly') }}" class="btn btn-small btn-primary">Monthly Summary</a>
                            <a href="{{ route('reports.yearly') }}" class="btn btn-small btn-primary">Yearly Summary</a>
                            <a href="{{ route('reports.collection') }}" class="btn btn-small btn-warning">Collection Report</a>
                            <a href="{{ route('reports.bookings') }}" class="btn btn-small btn-primary">Booking Report</a>
                            <a href="{{route('reports.daily')}}" class="btn btn-small btn-primary">Daily Collection</a>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>Collection Report</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-4">
                            <form action="{{ route('reports.collection') }}" method="GET" id="collection_form">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" onchange="submitForm()" value="{{ $startDate ?? now()->toDateString() }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" onchange="submitForm()" value="{{ $endDate ?? now()->toDateString() }}">
                                        </div>
                                    </div>
                                    <!--pdf download-->
                                    <div class="col-md-3">
                                        <a href="{{ route('reports.collection', ['start_date' => $startDate, 'end_date' => $endDate, 'pdf' => 1]) }}" class="btn btn-danger mx-3 mt-4">Download PDF</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2 m-2">
                        <div class="table-responsive p-0">
                            <table class="custom-table ">
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
                                    @php
                                        $totalBookingFare = 0;
                                        $totalTaxCharge = 0;
                                        $totalExtraCharge = 0;
                                        $totalPaidAmount = 0;
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($bookings as $key => $booking)
                                        @php
                                            $totalBookingFare += $booking->booking_fare;
                                            $totalTaxCharge += $booking->tax_charge;
                                            $totalExtraCharge += $booking->extra_charge;
                                            $totalPaidAmount += $booking->advance_fare;
                                            $grandTotal += $booking->total_amount;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $booking->booking_number }}</td>
                                            <td>{{ number_format($booking->booking_fare, 2) }}</td>
                                            <td>{{ number_format($booking->tax_charge, 2) }}</td>
                                            <td>{{ number_format($booking->extra_charge, 2) }}</td>
                                            <td>{{ number_format($booking->advance_fare, 2) }}</td>
                                            <td>{{ number_format($booking->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Totals</td>
                                        <td>{{ number_format($totalBookingFare, 2) }}</td>
                                        <td>{{ number_format($totalTaxCharge, 2) }}</td>
                                        <td>{{ number_format($totalExtraCharge, 2) }}</td>
                                        <td>{{ number_format($totalPaidAmount, 2) }}</td>
                                        <td>{{ number_format($grandTotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function submitForm() {
            document.getElementById("collection_form").submit();
        }
    </script>
@endsection
