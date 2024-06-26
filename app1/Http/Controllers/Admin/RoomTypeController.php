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
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'complements'         => 'nullable|array',
            'complements.*'       => 'integer|exists:complements,id',
            'total_bed'           => 'required|gt:0',
            'bed'                 => 'required|array',
            'bed.*'               => 'integer|exists:bed_types,id',
            'room'                => 'required|array',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric|gte:0|lt:fare'
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
                "description" => $request->description,
                "feature_status" => 1,
                "cancellation_fee" => $request->cancellation_fee ?? 0,
                "cancellation_policy" => $request->cancellation_policy,
                "status" => 1
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
        return view('admin.room_types.edit', [
            'room_type' => RoomType::find($id),
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
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'complements'         => 'nullable|array',
            'complements.*'       => 'integer|exists:complements,id',
            'total_bed'           => 'required|gt:0',
            'bed'                 => 'required|array',
            'bed.*'               => 'exists:bed_types,name',
            'room'                => 'nullable',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric|gte:0|lt:fare'
        ]);

        

        if ($request->room) {
            $roomNumbers = Room::pluck('room_number')->toArray();
            $exists = array_intersect($request->room, $roomNumbers);
            if (!empty($exists)) {
                $notify[] = ['error', implode(', ', $exists) . ' room number already exists'];
                return back()->with('warning', $notify);
            }
        }

        $bedArray         = array_values($request->bed ?? []);

        $roomType = RoomType::find($id)->update([
            "name" => $request->name,
            "total_adult" => $request->total_adult,
            "total_child" => $request->total_child,
            "fare" => $request->fare,
            "description" => $request->description,
            "beds" => $bedArray,
            "feature_status" => 1,
            "cancellation_fee" => $request->cancellation_fee ?? 0,
            "cancellation_policy" => $request->cancellation_policy
        ]);

        $roomType->amenities()->sync($request->amenity);
        $roomType->complements()->sync($request->complement);

        $this->insertRooms($request, $roomType->id);

        return redirect()->route('room-types.index')->with('success','Room Types has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RoomType::find($id)->delete();
        return redirect()->route('room-types.index')->with('success','Room Types has been deleted!');
    }
}
