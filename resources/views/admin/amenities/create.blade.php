@extends('admin.layouts.app')
@section('main')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between">
              <h6>Add New Amenity</h6>
              <a href="{{ route('amenities.index') }}" class="btn btn-small btn-primary">Back</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <form method="post" action="{{ route('amenities.store') }}" class="mt-6 space-y-6">
                    @csrf
                    <div class="row m-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       class="mt-1 form-control" 
                                       placeholder="Name"
                                       value="{{ old('name') }}" autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end">
                             <button type="submit" class="btn btn-small btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection