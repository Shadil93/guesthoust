<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BedType;

class BedTypeController extends Controller
{
    public function index()
    {
        return view('admin.bed_types.index', [
            'bed_types' => BedType::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bed_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:bed_types,name'
        ]);
        
        BedType::create([
            'name' => $request->name
        ]);

        return redirect()->route('bed-types.index')->with('success','Bed Types has been saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.bed_types.edit', [
            'bed_type' => BedType::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string|unique:bed_types,name,' . $id
        ]);
        
        BedType::find($id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('bed-types.index')->with('success','Bed Types has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BedType::find($id)->delete();
        return redirect()->route('bed-types.index')->with('success','Bed Types has been deleted!');
    }
}
