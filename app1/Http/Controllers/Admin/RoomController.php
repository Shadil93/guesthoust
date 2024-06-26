<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    RoomType,
    BedType,
    Room,
    Amenity,
    Complement,
    RoomTypeAmenity,
    RoomTypeComplement,
    RoomTypeImage
};

class RoomController extends Controller
{
    public function index()
    {
        return view('admin.rooms.index', [
            'rooms' => Room::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        
    }

    public function show(Request $request, string $id)
    {
        return view('admin.rooms.show', [
            'room' => Room::find($id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
