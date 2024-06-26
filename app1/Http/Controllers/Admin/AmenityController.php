<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.amenities.index', [
            'amenities' => Amenity::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required|string|unique:amenities,name'
        ]);
        
        Amenity::create([
            'name' => $request->name
        ]);

        return redirect()->route('amenities.index')->with('success','Amenity has been saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.amenities.edit', [
            'amenity' => Amenity::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name'     => 'required|string|unique:amenities,name,'. $id
        ]);

        Amenity::find($id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('amenities.index')->with('success','Amenity has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Amenity::find($id)->delete();
        return redirect()->route('amenities.index')->with('success','Amenity has been deleted!');
    }
}
