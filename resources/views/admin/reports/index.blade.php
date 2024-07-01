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
                        <a href="{{ route('reports.monthly') }}" class="btn btn-small btn-warning">Monthly Summary</a>
                        <a href="{{ route('reports.yearly') }}" class="btn btn-small btn-primary">Yearly Summary</a>
                        <a href="{{ route('reports.collection') }}" class="btn btn-small btn-primary">Collection Report</a>
                        <a href="{{ route('reports.bookings') }}" class="btn btn-small btn-primary">Booking Report</a>
                        <a href="{{ route('reports.daily') }}" class="btn btn-small btn-primary">Daily Collection</a>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
                </div>
            </div>
          </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>Monthly Booking Summary</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                      <div class="table-responsive p-0 px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <form action="{{ route('reports.monthly') }}" method="GET">
                                        <select class="form-control" name="selected_month" onchange="this.form.submit()">
                                          @foreach ($months as $monthNumber => $monthName)
                                              <option value="{{ $selectedMonth->copy()->setMonth($monthNumber)->format('Y-m') }}" {{ $selectedMonth->format('m') === $monthNumber ? 'selected' : '' }}>
                                                  {{ $monthName }}
                                              </option>
                                          @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-5 mt-1 mr-2">
                                    <a href="{{ route('reports.monthly', ['selected_month' => $selectedMonth->format('Y-m'), 'pdf' => 1]) }}" class="btn btn-danger mx-3">Download PDF</a>
                                    <a href="{{ route('monthly_pdf',) }}" class="btn btn-info font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Print" target="_blank">  Print </a>
                                </div>
                            </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-12">
              <div class="card mb-4">
                  <div class="card-header pb-0 d-flex justify-content-center">
                      <h6>Booking Summary for {{ $selectedMonth->format('F Y') }}</h6>
                  </div>
                  <div class="card-body px-0 pt-0 pb-2 m-2">
                      <div class="table-responsive p-0">
                          <table class="custom-table">
                              <thead>
                                  <tr>
                                      <th>Date</th>
                                      <th>Number of Bookings</th>
                                      <th>Cash</th>
                                      <th>Card</th>
                                      <th>UPI</th>
                                      <th>Total Collection</th>
                                  </tr>
                              </thead>
                              <tbody>
    @php
        $grandTotalBooking = 0;
        $grandcash = 0;
        $grandcard = 0;
        $grandUPI = 0;
        $grandTotalCollection = 0;
    @endphp
    @foreach ($bookings as $bookingData)
        @php
            $grandTotalBooking += $bookingData->bookingCount;
            $grandcash += $bookingData->cash_collection;
            $grandcard += $bookingData->card_collection;
            $grandUPI += $bookingData->upi_collection;
            $grandTotalCollection += $bookingData->totalCollection;
        @endphp
        <tr>
            <td>{{ \Carbon\Carbon::parse($bookingData->date)->format('d-m-Y') }}</td>
            <td>{{ $bookingData->bookingCount }}</td>
            <td>{{ $bookingData->cash_collection }}</td>
            <td>{{ $bookingData->card_collection }}</td>
            <td>{{ $bookingData->upi_collection }}</td>
            <td>{{ $bookingData->totalCollection }}</td>
        </tr>
    @endforeach
    <tr>
        <td><strong>Grand Total</strong></td>
        <td><strong>{{ $grandTotalBooking }}</strong></td>
        <td><strong>{{$grandcash}}</strong></td> 
        <td><strong>{{$grandcard}}</strong></td>
        <td><strong>{{$grandUPI}}</strong></td>
        <td><strong>{{ $grandTotalCollection }}</strong></td>
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