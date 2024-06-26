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
    RoomTypeImage,
    RoomBedType
};
use DB;

class RoomTypeController extends Controller
{
    public function index()
    {
        return view('admin.room_types.index', [
            'room_types' => RoomType::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.room_types.create',[
            'amenities' => Amenity::all(),
            'complements' => Complement::all(),
            'bed_types' => BedType::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255|unique:room_types,name',
            'total_adult'         => 'required|integer|gte:0',
            'total_child'         => 'required|integer|gte:0',
            'fare'                => 'required|gt:0',
            'tax'                 => 'nullable',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'complements'         => 'nullable|array',
            'complements.*'       => 'integer|exists:complements,id',
            'total_bed'           => 'required|gt:0',
            'bed'                 => 'required|array',
            'bed.*'               => 'integer|exists:bed_types,id',
            'room'                => 'required|array',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric|gte:0|lt:fare',
            'caution_deposit'     => 'required',

            
        ]);

        DB::beginTransaction();

        try {

            if ($request->room) {
                $roomNumbers = Room::pluck('room_number')->toArray();
                $exists = array_intersect($request->room, $roomNumbers);
                if (!empty($exists)) {
                    $notify[] = ['error', implode(', ', $exists) . ' room number already exists'];
                    return back()->with('warning', $notify);
                }
            }

            $roomType = RoomType::create([
                "name" => $request->name,
                "total_adult" => $request->total_adult,
                "total_child" => $request->total_child,
                "fare" => $request->fare,
                "tax" => $request->tax,
                "description" => $request->description,
                "feature_status" => 1,
                "cancellation_fee" => $request->cancellation_fee ?? 0,
                "cancellation_policy" => $request->cancellation_policy,
                "status" => 1,
                "caution_deposit" => $request->caution_deposit,

            ]);
            if ($request->room) {
                foreach ($request->room as $roomNumber) {
                    Room::create([
                        "room_type_id" => $roomType->id,
                        "room_number" => $roomNumber,
                        "status" => 1
                    ]);
                }
            }
            $roomType->amenities()->sync($request->amenities);
            $roomType->complements()->sync($request->complements);
            $roomType->bedTypes()->sync($request->bed);

            DB::commit();

            return redirect()->route('room-types.index')->with('success','Room Types has been saved!');

        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return back()->with('warning','Something went wrong!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roomType = RoomType::findOrFail($id);
        $bedTypes = $roomType->bedTypes()->get();
        $roomTypeAmenities = $roomType->amenities->pluck('id')->toArray();
        $roomTypeComplements = $roomType->complements->pluck('id')->toArray();
    
        return view('admin.room_types.edit', [
            'room_type' => $roomType,
            'amenities' => Amenity::all(),
            'complements' => Complement::all(),
            'bed_types' => BedType::all(),
            'roomTypeBedTypes' => $bedTypes, 
            'roomTypeAmenities' => $roomTypeAmenities,
            'roomTypeComplements' => $roomTypeComplements,
        ]);
    }
    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                => 'required|string|max:255|unique:room_types,name,'. $id,
            'total_adult'         => 'required|integer|gte:0',
            'total_child'         => 'required|integer|gte:0',
            'fare'                => 'required|gt:0',
            'tax'                 => 'nullable',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'complements'         => 'nullable|array',
            'complements.*'       => 'integer|exists:complements,id',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric|gte:0|lt:fare',
            'caution_deposit'     => 'required',

        ]);
    
        try {
            $roomType = RoomType::find($id);
    

    
            DB::beginTransaction();
    
            $roomType->bedTypes()->detach();
    
            if ($request->bed) {
                foreach ($request->bed as $bedType) {
                    $roomType->bedTypes()->attach($bedType);
                }
            }
    
            DB::commit();
    
            $roomType->update([
                "name" => $request->name,
                "total_adult" => $request->total_adult,
                "total_child" => $request->total_child,
                "fare" => $request->fare,
                "tax" => $request->tax,
                "description" => $request->description,
                "feature_status" => 1,
                "cancellation_fee" => $request->cancellation_fee ?? 0,
                "cancellation_policy" => $request->cancellation_policy,
                "caution_deposit" => $request->caution_deposit,
            ]);
    
            $roomType->amenities()->sync($request->amenities);
            $roomType->complements()->sync($request->complements);
    
            $roomType->rooms()->delete();
    
            $this->insertRooms($request, $roomType->id);
            
            return redirect()->route('room-types.index')->with('success','Room Type has been updated!');
        } catch (\Exception $e) {
            DB::rollback();
    
            return back()->with('warning','Something went wrong!');
        }
    }
    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            RoomType::findOrFail($id)->delete();
            return redirect()->route('room-types.index')->with('success', 'Room Type has been deleted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    


    public function checkRoomAvailability(Request $request)
    {
        $roomNumber = $request->input('roomNumber');
    
        $isAvailable = Room::where('room_number', $roomNumber)
            ->exists();
    
        return response()->json(['available' => $isAvailable]);
    }


    public function deleteRoom($id)
    {
        $room = Room::find($id);
        if ($room) {
            $room->delete();
            return response()->json(['message' => 'Room deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Room not found'], 404);
        }
    }



    public function insertRooms(Request $request, $roomTypeId)
    {
        $rooms = $request->input('rooms', []);

        foreach ($rooms as $roomNumber) {
            Room::create([
                'room_number' => $roomNumber,
                'room_type_id' => $roomTypeId,
                "status" => 1,
                
            ]);
        }
    }

    public function fetchCautionDeposit($room_type)
    {
        $roomType = RoomType::findOrFail($room_type);
    
        return response()->json([
            'success' => true,
            'caution_deposit' => $roomType->caution_deposit
        ]);
    }


}
