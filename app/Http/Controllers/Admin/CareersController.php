<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Careers;
use App\Models\Jobtypes;
use Inertia\Inertia;

class CareersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $careers = Careers::join('jobtypes', 'careers.jobtype_id', 'jobtypes.id')
        ->select('careers.*', 'jobtypes.jobtype')
        ->get();
        return Inertia::render('Admin/Manage/Careers', compact('careers')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobtypes = Jobtypes::all();
        return Inertia::render('Admin/Add/Career', compact('jobtypes')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'jobtype' => 'required',
            'description' => 'required|string',
            'qualification' => 'required|string|max:255',
            'deadline' => 'required|string|max:255'
        ]);

        $career = Careers::create([
            'title' => $request->input('title'),
            'jobtype_id' => $request->input('jobtype'),
            'description' => $request->input('description'),
            'qualification' => $request->input('qualification'),
            'deadline' => $request->input('deadline'),
        ]);

        if($career){
            return redirect('/admin/careers')->with('message', 'Career posted successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Careers could not be posted. Try again!');
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $career = Careers::where('id', $id)->first();
        $jobtypes = Jobtypes::all();
        return Inertia::render('Admin/Edit/Career', compact('career', 'jobtypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'jobtype' => 'required',
            'description' => 'required|string',
            'qualification' => 'required|string|max:255',
            'deadline' => 'required|string|max:255'
        ]);

        $career = Careers::where('id', $id)->update([
            'title' => $request->input('title'),
            'jobtype_id' => $request->input('jobtype'),
            'description' => $request->input('description'),
            'qualification' => $request->input('qualification'),
            'deadline' => $request->input('deadline'),
        ]);

        if($career){
            return redirect('/admin/careers')->with('message', 'Career updated successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Careers could not be updated. Try again!');
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $career = Careers::where('id', $id)->delete();
        if($career){
          return redirect()->back()->with('message', 'Career has been deleted');
        }
        else{
            return redirect()->back()->with('message', 'Cause could not be deleted');
        }
    }
}
