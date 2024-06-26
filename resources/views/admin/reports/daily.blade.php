@extends('admin.layouts.app')

@section('main')


<style>
    .custom-table { border-collapse: collapse; width: 100%;}
    .custom-table th, .custom-table td { border: 1px solid #dee2e6; padding: 10px; text-align: center; }
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
                            <a href="{{ route('reports.bookings') }}" class="btn btn-small btn-primary">Booking Report</a>
                            <a href="{{route('reports.daily')}}" class="btn btn-small btn-warning" >Daily Collection</a>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-4">
                            <form action="{{ route('reports.daily') }}" method="GET">
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
                                    <div class="col-md-3 mt-4">
                                        <!-- <a href="{{ route('reports.daily', ['start_date' => $startDate, 'end_date' => $endDate, 'pdf' => 1]) }}" class="btn btn-danger">Download PDF</a> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-center">
                        <!-- <h6> Booking Summary BTW {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} and {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</h6> -->
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 m-2">
                            <table class="custom-table ">
                                <thead>
                                    <tr>
                                        <th>Receipt Number</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>UPI</th>
                                        <th>Collection</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @php
                                        $totalCash = 0;
                                        $totalCards = 0;
                                        $totalUPI = 0;
                                        $totalCollection = 0;
                                    @endphp
                                    @foreach($bookings as $booking)
                                    @php
                                         $totalCash += $booking->cash_collection;
                                         $totalCards += $booking->card_collection;
                                         $totalUPI += $booking->upi_collection;
                                         $totalCollection += $booking->total_collection;
                                    @endphp
                                  
                                        <tr>
                                            <td>{{$booking->receipt_number}}</td>
                                            <td>{{$booking->cash_collection}}</td>
                                            <td>{{$booking->card_collection}}</td>
                                            <td>{{$booking->upi_collection}}</td>
                                            <td>{{$booking->total_collection }}</td>
                                        </tr>
                                    @endforeach
                                 <tr>
                                 <td><B>Total</B></td>
                                 <td><B>{{$totalCash}}</B></td>
                                 <td><B>{{$totalCards}}</B></td>
                                 <td><B>{{$totalUPI}}</B></td>
                                 <td><B>{{$totalCollection}}</B></td>
                                 </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            </div>
</div>
</div>



@endsection