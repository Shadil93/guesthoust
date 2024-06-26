@extends('admin.layouts.app')

@section('main')

<style>
    .custom-table { border-collapse: collapse; width: 100%;}
    .custom-table th, .custom-table td { border: 1px solid #dee2e6; padding: 8px; text-align: center; }
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
                            <a href="{{ route('reports.collection') }}" class="btn btn-small btn-primary">Collection Report</a>
                            <a href="{{ route('reports.bookings') }}" class="btn btn-small btn-warning">Booking Report</a>
                            <a href="{{route('reports.daily')}}" class="btn btn-small btn-primary" >Daily Collection</a>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-4">
                            <form action="{{ route('reports.bookings') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? now()->toDateString() }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? now()->toDateString() }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    <!--<div class="col-md-3 mt-4">-->
                                    <!--    <a href="{{ route('reports.bookings', ['start_date' => $startDate, 'end_date' => $endDate, 'pdf' => 1]) }}" class="btn btn-primary">Download PDF</a>-->
                                    <!--</div>-->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-center">
                        <h6> Booking Summary BTW {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} and {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 m-2">
                            <table class="custom-table ">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Number of Bookings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalBookings = 0; @endphp
                                    @foreach ($bookings as $data)
                                        @php
                                            $totalBookings += $data['bookingCount'];
                                        @endphp
                                        <tr>
                                            <td>{{ $data->room_number }}</td>
                                            <td>{{ $data->bookingCount }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Grand Total</strong></td>
                                        <td><strong>{{ $totalBookings }}</strong></td>
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
