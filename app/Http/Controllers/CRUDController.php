<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\crud\CustomRequest;

class CRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->view('crud.index', [
            'User' => User::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response() -> view('crud.dataform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomRequest $request)
    {
        $validated = $request->validated();
        $create = User::create($validated);
        if($create){
            session()->flash('notif.success', 'New data created');
            return redirect()->route('crud.index');
        } 
        return abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->view('crud.details',[
            'User' => User::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->view('crud.dataform',  [
            'User' => User::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomRequest $request, string $id)
    {
        $User = User::findOrFail($id);
        $validated = $request->validated();
        $update = $User->update($validated);
        if($update){
            session()->flash('notif.success', 'Data successfully updated');
            return redirect()->route('crud.index');
        }
        return abort(500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $User = User::findOrFail($id);
        $delete = $User->delete($id);
        if($delete){
            session()->flash('notif.success', 'Data successfully deleted');
            return redirect()->route('crud.index'); 
        }
        return abort(500);
    }
}
