<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraService;

class AddOnServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.services.index', [
            'services' => ExtraService::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required|string|unique:extra_services,name',
            'cost'     => 'required'
        ]);
        
        ExtraService::create([
            'name' => $request->name,
            'cost' => $request->cost
        ]);

        return redirect()->route('addonservices.index')->with('success','Add On Service has been saved!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.services.edit', [
            'service' => ExtraService::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name'     => 'required|string|unique:extra_services,name,'. $id,
            'cost'     => 'required'
        ]);

        ExtraService::find($id)->update([
            'name' => $request->name,
            'cost' => $request->cost
        ]);

        return redirect()->route('addonservices.index')->with('success','Add On Service has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        ExtraService::find($id)->delete();
        return redirect()->route('addonservices.index')->with('success','Add On Service has been deleted!');
    }
}
