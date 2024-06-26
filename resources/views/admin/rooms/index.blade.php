@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
      <div class="row">
      <div class="col-12">
            <div class="card mb-2">
                <div class="card-body px-2 pt-2 pb-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-small btn-warning">Rooms</a>
                    <a href="{{ route('room-types.index') }}" class="btn btn-small btn-primary">Room Types</a>
                    <a href="{{ route('bed-types.index') }}" class="btn btn-small btn-primary">Bed Types</a>
                    <a href="{{ route('amenities.index') }}" class="btn btn-small btn-primary">Amenities</a>
                    <a href="{{ route('complements.index') }}" class="btn btn-small btn-primary">Complements</a>
                    <a href="{{ route('addonservices.index') }}" class="btn btn-small btn-primary">Add On Services</a>
                </div>
            </div>
        </div>
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>List Of Rooms</h6>
              <a href="{{ route('dashboard') }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Room No</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Room Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-secondary opacity-7 text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($rooms as $key => $room)
                    <tr>
                      <td>
                        <p class="text-xs font-weight-small mb-0">{{ $rooms->firstItem() + $key }}</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $room->room_number }}</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $room->roomType->name ?? '' }}</p>
                      </td>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $room->status == 1 ? "Active" : "Disabled" }}</p>
                      </td>
                      <td class="text-end">
                        <a href="{{ route('rooms.show',$room->id) }}" class="btn btn-info font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                          View
                        </a>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td class="text-center" colspan="7">
                            No Data Found!
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="col-12 d-flex justify-content-center">
                    {{ $rooms->links('admin.layouts.pagination') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection