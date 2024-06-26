@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Reservations</p>
                    <h5 class="font-weight-bolder">
                    {{ $widget['pending_checkin'] }}
                    </h5>
                  
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                  <i class="ni ni-calendar-grid-58 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's <br>Chekout</p>
                    <h5 class="font-weight-bolder">
                    {{ $widget['todays_checkout'] }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                  <i class="ni ni-watch-time text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Booked Rooms</p>
                    <h5 class="font-weight-bolder">
                    {{ $widget['today_booked'] }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                  <i class="ni ni-hat-3 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Available <br> Rooms</p>

                    <h5 class="font-weight-bolder">
                    {{ $widget['today_available'] }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                  <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
  
      <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Today's Reservations</h6>
          
            </div>
            <div class="card-body p-3">
               <div class="table-responsive">
              <table class="table align-items-center ">
                <tbody>
                  
                @forelse($salesoverview as $key => $sales)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">
                        <div>
                          <img src="../assets/img/icons/flags/profile.gif" alt="Country flag" style="max-width: 50px;">
                        </div>
                        <div class="ms-4">
                          <p class="text-xs font-weight-bold mb-0">Name:</p>
                          <h6 class="text-sm mb-0" style="text-transform: uppercase;">{{$sales->guest_details->name}}</h6>
                          <span class="text-xs">{{$sales->guest_details->mobile}}</span> <br>
                          <span class="text-xs">{{$sales->guest_details->email}}</span> 
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        <p class="text-xs font-weight-bold mb-0">Booking Number:</p>
                        <h6 class="text-sm mb-0">{{$sales->booking_number}}</h6>
                        <span class="text-xs font-weight-bold">{{$sales->bookedRooms->first()?->room->roomType->name ?? 'NA'}}</span>
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        <p class="text-xs font-weight-bold mb-0">Booking Fare:</p>
                        @php
                          $bookfare=$sales->booking_fare + $sales->tax_charge + $sales->service_cost + $sales->extra_charge + $sales->discount_subtract  + $sales->cancellation_fee - $sales->extra_charge_subtracted - $sales->discount;
                        @endphp
                        <h6 class="text-sm mb-0" style="color: orange;"> ₹ {{ number_format($bookfare , 2) }}</h6>
                      </div>
                    </td>
                    <td class="align-middle text-sm">
                      <div class="col text-center">
                        <p class="text-xs font-weight-bold mb-0">Paid:</p>
                        <h6 class="text-sm mb-0" style="color: green;">₹ {{ number_format($sales->paid_amount , 2) }}</h6>
                      </div>
                    </td>
                    <td class="align-middle text-sm">
                      <div class="col text-center">
                        <p class="text-xs font-weight-bold mb-0">Pending Amount:</p>
                        <h6 class="text-sm mb-0" style="color: red;"> ₹ {{ number_format($bookfare - $sales->paid_amount , 2) }}</h6>
                      </div>
                    </td>
                    <td>
                      <!--<button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">-->
                      <!--                    <i class="ni ni-bold-right" aria-hidden="true"></i>-->
                      <!--</button>-->
                        @if (now()->format('Y-m-d') >= $sales->check_in && now()->format('Y-m-d') < $sales->check_out && $sales->key_status == 0)
                            <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('reservations.checkin',$sales->id) }}">
                                Check In
                            </a>
                        @endif
                         @if ($sales->key_status == 1)
                        <a class="btn btn-sm btn-outline-danger me-1" href="{{ route('reservations.show',$sales->id) }}">
                                Checked Out
                            </a>
                        @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                      <td class="text-center" colspan="3">
                          <img src="../assets/img/icons/flags/noreserve.png" alt="No Data Found Image" style="width:50%;">
                      </td>
                  </tr>
                    @endforelse
                    <!--@if(count($salesoverview) >= 10)-->
                    <!--  <tr>-->
                    <!--    <td colspan="6" class="text-center">-->
                    <!--      <button class="btn btn-primary">Show More</button>-->
                    <!--    </td>-->
                    <!--  </tr>-->
                    <!--@endif-->
                </tbody>
              </table>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Room Availability</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @forelse($roomtypes as $key => $roomtype)
                              <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                  <div class="d-flex align-items-center">
                                      <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                          <i class="ni ni-book-bookmark text-white opacity-10"></i>
                                      </div>
                                      <div class="d-flex flex-column">
                                          <h6 class="mb-1 text-dark text-xs text-uppercase">{{ $roomtype->name }} - ₹ {{ number_format($roomtype->fare  , 2) }}  + {{ number_format($roomtype->tax  , 2) }}</h6>
                                          <span class="text-xs font-weight-bold {{ ($roomtype->rooms->whereNotIn('id', $roomtype->bookedRooms->where('booked_for', date('Y-m-d'))->pluck('room_id')->toArray())->count()) > 1 ? 'text-success' : 'text-danger' }}">
                                              {{ $roomtype->rooms->whereNotIn('id', $roomtype->bookedRooms->where('booked_for', date('Y-m-d'))->pluck('room_id')->toArray())->count() }} rooms available
                                          </span>
                                          <span class="text-xs">Total rooms: {{ $roomtype->rooms->count() }}, 
                                              <span class="font-weight-bold">({{ $roomtype->bookedRooms->where('booked_for', date('Y-m-d'))->count() }} reservation for today)</span> 
                                          </span>
                                      </div>
                                  </div>
                                  <div class="d-flex">
                                      <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto" data-bs-toggle="modal" data-bs-target="#myModal" data-roomtype-id="{{ $roomtype->id }}">
                                          <i class="ni ni-bold-right" aria-hidden="true"></i>
                                      </button>
                                  </div>
                              </li>
                          @empty
                              <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                  <div class="d-flex align-items-center">
                                      No Data Found!
                                  </div>
                              </li>
                          @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

  

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Modal Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be dynamically updated here -->
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Description:</strong></p>
                        <p id="description"></p>
                    </div>
                    <div class="col-md-12">
                        <p><strong>Fare:</strong> <span id="fare"></span></p>
                        <p><strong>Total Rooms:</strong> <span id="totalRooms"></span></p>
                        <p><strong>Available Rooms:</strong> <span id="availableRooms"></span></p>
                    </div>
                    <div class="col-md-12">
                        <p><strong>Active Rooms:</strong></p>
                        <ul id="activeRooms" class="list-unstyled"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));

        // Attach a click event to each button
        var modalButtons = document.querySelectorAll('.btn[data-bs-target="#myModal"]');
        modalButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var roomTypeId = this.getAttribute('data-roomtype-id');

                // AJAX request to fetch room details
                $.ajax({
                    url: '/get-room-details/' + roomTypeId, // Adjust the URL to match your Laravel route
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Update modal content with fetched details
                        updateModalContent(data);
                        
                        // Open the modal
                        myModal.show();
                    },
                    error: function (error) {
                        console.error('Error fetching room details:', error);
                    }
                });
            });
        });

        function updateModalContent(data) {
          $('#modalTitle').text(data.name);
          $('#description').text(data.description);
          $('#fare').text(data.fare);
          $('#totalRooms').text(data.totalroomcount);
          $('#availableRooms').text(data.availableroomcount);

          updateListWithBadge('activeRooms', data.activeRooms, 'room_number');
      }

        function updateListWithBadge(listId, items, primaryField, secondaryField = null) {
          var list = $('#' + listId);
          list.empty();

          if (items.length > 0) {
              var rowCount = Math.ceil(items.length / 6); 
              for (var i = 0; i < rowCount; i++) {
                  var row = $('<div class="row"></div>');
                  for (var j = i * 6; j < Math.min((i + 1) * 6, items.length); j++) {
                      var item = items[j];
                      var col = $('<div class="col-md-2"></div>');
                      var listItem = $('<li>');
                      var badge = $('<span class="badge bg-primary"></span>');
                      badge.text(item[primaryField]);

                      listItem.append(badge);

                      if (secondaryField !== null) {
                          listItem.append(' (Booked for: ' + item[secondaryField] + ')');
                      }

                      col.append(listItem);
                      row.append(col);
                  }
                  list.append(row);
              }
          } else {
              list.append('<li>No items</li>');
          }
      }



    });
</script>

@endpush
