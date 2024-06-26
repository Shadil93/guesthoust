@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>Rooms</h6>
              <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="row m-3">
                    <div class="col-md-12">
                        <h3>{{ $room->roomType->name ?? '' }}</h3>
                        <p>Room Number : <strong>{{ $room->room_number }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Amenities</h4>
                        @if($room->amenities)
                        <ul>
                            @foreach($room->amenities as $key => $amenity)
                                <li>{{ $amenity->name }}</li>
                            @endforeach    
                        </ul>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h4>Complements</h4>
                        @if($room->complements)
                        <ul>
                            @foreach($room->complements as $key => $complement)
                                <li>{{ $complement->name }}</li>
                            @endforeach    
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection