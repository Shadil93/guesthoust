@extends('admin.layouts.app')
@section('main')
@php
    $totalFare = $booking->bookedRooms->sum('fare');
    $totalTaxCharge = $booking->bookedRooms->sum('tax_charge');
    $canceledFare = $booking->bookedRooms->where('status', 3)->sum('fare');
    $canceledTaxCharge = $booking->bookedRooms->where('status', 3)->sum('tax_charge');
    $due = $booking->total_amount - $booking->paid_amount;
@endphp
<div class="card shadow-lg mx-4">
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                Reservation Details
              </h5>
              @php
                echo $booking->status_badge;
              @endphp
            </div>
          </div>
          <div class="col-auto my-auto">
    
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Booking Information</h5>
              </div>
            </div>
            <div class="card-body">
              <hr class="horizontal dark mt-0">
              <div class="row">
                <div class="col-md-6 mt-4">
                    <span class="font-weight-light">Booking Number </span> 
                    <h5>#{{ $booking->booking_number }}</h5>
                    <span class="font-weight-light">Booked At   </span> 
                    <h5>{{ $booking->created_at }}</h5>
                    <span class="font-weight-light">Checkin  </span> 
                    <h5>{{ $booking->check_in }}</h5>
                    <span class="font-weight-light">Checkout  </span> 
                    <h5>{{ $booking->check_out }}</h5>
                </div>
                <div class="col-md-6 mt-4">
                    <span class="font-weight-light">Total Rooms  </span> 
                    <h5>{{ $booking->bookedRooms->count() }}</h5>
                    <span class="font-weight-light">Total Charge </span> 
                    <h5>{{ $booking->total_amount }}</h5>
                    <span class="font-weight-light">Paid Amount  </span> 
                    <h5>{{ $booking->paid_amount }}</h5>
                    @if ($due < 0)
                    <span class="font-weight-light">Refundable  </span> 
                    <h5>{{ abs($due) }}</h5>
                    @else
                    <span class="font-weight-light">Receivable From Customer  </span> 
                    <h5 class="@if ($due > 0) text-danger @else text-success @endif">{{ abs($due) }}</h5>
                    @endif
                </div>
                <div class="col-md-12 mt-4">
                    <hr class="horizontal dark mt-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="font-weight-light">Checked In At  </span> 
                            <h5>{{ $booking->checked_in_at ? $booking->checked_in_at : 'N/A' }}</h5>
                        </div>
                        <div>
                            <span class="font-weight-light">Checked Out At  </span> 
                            <h5>{{ $booking->checked_out_at  ? $booking->checked_out_at : 'N/A' }}</h5>
                        </div>
                    </div>
                </div>
              </div>        
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="text-uppercase text-sm">Booked Rooms</h5>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Booked For</th>
                                <th>Room Type</th>
                                <th>Room No</th>
                                <th class="text-end">Fare / Night</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->bookedRooms->groupBy('booked_for') as $bookedRoom)
                                @foreach ($bookedRoom as $booked)
                                    <tr>
                                        @if ($loop->first)
                                            <td class="bg-date text-center" rowspan="{{ count($bookedRoom) }}">
                                                {{ $booked->booked_for}}
                                            </td>
                                        @endif
                                        <td class="text-center" data-label="Room Type">
                                            {{ $booked->room->roomType->name }}
                                        </td>
                                        <td data-label="Room No.">
                                            {{ $booked->room->room_number }}
                                            @if ($booked->status == 3)
                                                <span class="text-danger text-sm">(Canceled)</span>
                                            @endif
                                        </td>
                                        <td class="text-end" data-label="Fare">
                                            {{ $booked->fare }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td class="text-end" colspan="3">
                                    <span class="fw-bold">Total Fare</span>
                                </td>
                                <td class="fw-bold text-end">
                                    {{ $totalFare }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <img id="photo" name="canvas_image" src="#" alt="Your photo" style="display: none;" />
                    </div>
                </div>
            </div>

          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-profile">
            <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
              <div class="d-flex justify-content-between">
                <h5 class="text-uppercase text-sm">Guest Details</h5>
              </div>
            </div>
            <div class="card-body pt-0">
              <hr class="horizontal dark mt-0">
              <div class="mt-4">
                 <span class="font-weight-light">Name </span> 
                 <h5>{{ $booking->guest_details->name }}</h5>
                 <span class="font-weight-light">Email : </span> 
                 <h5>{{ $booking->guest_details->email ?? '' }}</h5>
                 <span class="font-weight-light">Mobile : </span> 
                 <h5>{{ $booking->guest_details->mobile }}</h5>
                 <span class="font-weight-light">Address : </span> 
                 <h5>{{ $booking->guest_details->address }}</h5>
                 <span class="font-weight-light">ID Type : </span> 
                 <h5>{{ $booking->guest_details->id_card_type }}</h5>
                 <span class="font-weight-light">ID Number : </span> 
                 <h5>{{ $booking->guest_details->id_card_number }}</h5>
                 <form action="{{ route('reservations.checkinUpdate',$booking->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 guestInputDiv">
                            <div class="form-group">
                                <label for="check_in">Check In Time</label>
                                <input class="form-control" name="check_in" id="check_in" required type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>">
                                <div id="check_in_error" class="invalid-feedback"></div>
                            </div>
                        </div>                                              
                        <div class="col-6 guestInputDiv">
                            <div class="form-group">
                                <label for="no_adults">No of Adults</label>
                                <input class="form-control" name="no_adults" id="no_adults" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="1" required type="text">
                                <div id="no_adults_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-6 guestInputDiv">
                            <div class="form-group">
                                <label for="no_childs">No of Childrens</label>
                                <input class="form-control" name="no_childs" id="no_childs" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="0" required type="text">
                                <div id="id_card_number_error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>     
                    <div class="row">
                        <div class="form-group">
                            <label>Upload ID Card</label>
                            <input type="file" name="id_card" class="form-control" id="id_card_file" accept="image/*" />
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="take-picture-btn">Take Picture</button>
                        </div>
                        <input type="hidden" name="canvas_image" id="canvas-image-input">
                        
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <video id="video" width="100%" height="auto" autoplay></video>
                            <canvas id="canvas" style="display: none;"></canvas>
                            <img id="photo" src="#" alt="Your photo" style="display: none;" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="row ms-1 me-1">
                            <button type="submit" class="btn btn-primary">Check-In</button>
                        </div>
                    </div>
                    <script>
                      const video = document.getElementById('video');
                      const canvas = document.getElementById('canvas');
                      const photo = document.getElementById('photo');
                      const takePictureButton = document.getElementById('take-picture-btn');
                      const canvasImageInput = document.getElementById('canvas-image-input');

                      navigator.mediaDevices.getUserMedia({ video: true })
                          .then((stream) => {
                              video.srcObject = stream;
                          })
                          .catch((err) => {
                              console.error('Error accessing webcam:', err);
                          });

                      takePictureButton.addEventListener('click', () => {
                          const context = canvas.getContext('2d');
                          canvas.width = video.videoWidth;
                          canvas.height = video.videoHeight;
                          context.drawImage(video, 0, 0, canvas.width, canvas.height);

                          // Convert canvas image to data URL
                          const dataUrl = canvas.toDataURL('image/png');

                          // Set data URL value to the hidden input field
                          canvasImageInput.value = dataUrl;

                          // Optionally, you can set the data URL as the src attribute of an image element for preview
                          photo.setAttribute('src', dataUrl);
                          photo.style.display = 'block';
                      });

                    </script>
                 </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>

       


@endsection