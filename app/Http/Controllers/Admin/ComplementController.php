<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complement;

class ComplementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.complements.index', [
            'complements' => Complement::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.complements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|unique:complements,name'
        ]);

        Complement::create([
            'name' => $request->name
        ]);

        return redirect()->route('complements.index')->with('success','Complement has been saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.complements.edit', [
            'complement' => Complement::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'required|string|unique:complements,name,'. $id
        ]);

        Complement::find($id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('complements.index')->with('success','Complement has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Complement::find($id)->delete();
        return redirect()->route('complements.index')->with('success','Complement has been deleted!');
    }
}
