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


    public function getRoomDetails($id)
    {
        try {
            $roomType = RoomType::with('rooms', 'activeRooms', 'bookedRooms')->findOrFail($id);
    
            // Include all attributes from the RoomType model along with related data
            $data = [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'rooms' => $roomType->rooms,
                'fare' => $roomType->fare,
                'totalroomcount'=> $roomType->rooms->count(),
                'availableroomcount' =>$roomType->rooms->whereNotIn('id', $roomType->bookedRooms->where('booked_for', date('Y-m-d'))->pluck('room_id')->toArray())->count(),
                'activeRooms' => $roomType->activeRooms,
            ];
    
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Room details not found.'], 404);
        }
    }
    
}
