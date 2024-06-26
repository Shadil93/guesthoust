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
                            <a href="{{ route('reports.yearly') }}" class="btn btn-small btn-warning">Yearly Summary</a>
                            <a href="{{ route('reports.collection') }}" class="btn btn-small btn-primary">Collection Report</a>
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
                        <h6>Yearly Booking Summary</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <form action="{{ route('reports.yearly') }}" method="GET">
                                        <select class="form-control" name="selected_year" onchange="this.form.submit()">
                                            @for ($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}" {{ $selectedYear->format('Y') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('reports.yearly', ['selected_year' => $selectedYear->year, 'pdf' => 1]) }}" class="btn btn-danger mx-3">Download PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-center">
                        <h6>Yearly Summary for {{ $selectedYear->format('Y') }}</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 m-2">
                        <div class="table-responsive p-0">
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
                                            <td>{{ $month }}</td>
                                            <td>{{ $data['bookingCount'] }}</td>
                                            <td>{{ $data['totalCollection'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>{{ $totalBookings }}</strong></td>
                                        <td><strong>{{ $totalCollection }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
