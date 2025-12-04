<?php

namespace App\Http\Controllers;

use App\Models\Careers;
use App\Models\Applications;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSent;
use App\Models\Countries;

class CareersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $careers = Careers::join('jobtypes', 'careers.jobtype_id', 'jobtypes.id')
        ->select('careers.*', 'jobtypes.jobtype')
        ->where('careers.status', '=', 1)
        ->where('careers.deadline', '<', date('Y-m-d'))
        ->get();

        $careers_counter = Careers::join('jobtypes', 'careers.jobtype_id', 'jobtypes.id')
        ->where('careers.status', '=', 1)
        ->where('careers.deadline', '<', date('Y-m-d'))
        ->count();
      return view('careers', compact('careers', 'careers_counter')); 
    }

    public function jobDetails($id){
        $jobDetails = Careers::join('jobtypes', 'careers.jobtype_id', 'jobtypes.id')
        ->where('careers.id', $id)
        ->select('careers.*', 'jobtypes.jobtype')
        ->first();
      return view('job-details', compact('jobDetails')); 
    }

    public function apply($id){
        $career = Careers::findOrFail($id); 
        $countries = Countries::all();
      return Inertia::render('Apply', compact('career', 'countries')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'career_id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|string|max:255|email',
            'phone' => 'required|numeric|min:10',
            'address' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'level_of_education' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'notice_period' => 'required|string',
            'salary' => 'required|string|max:255',
            'resume' => 'required|mimes:pdf',
            'cover_letter' => 'nullable|mimes:pdf',
        ]);

        $resume = null; 
        $cover_letter = null; 
        
$checkIfApplied = Applications::where('career_id', $request->career_id)
                  ->where('email', $request->email)
                  ->count();

        if($checkIfApplied > 0){ 
            return to_route('apply', $request->career_id)->with("message", "You have already applied!"); 
        }
        else{
           if ($request->hasFile('resume')) {
            $resume = $request->file('resume')->store('applications/resumes', 'public');
        }
 
        if ($request->hasFile('coverletter')) {
            $cover_letter = $request->file('coverletter')->store('applications/cover letters', 'public');
        }

        $application = Applications::create([
            'career_id' => $request->input('career_id'),
            'first_name' => $request->input('firstname'),
            'last_name' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'country_id' => $request->input('nationality'),
            'level_of_education' => $request->input('level_of_education'),
            'field_of_study' => $request->input('field_of_study'),
            'notice_period' => $request->input('notice_period'),
            'desired_salary' => $request->input('salary'),
            'resume' => $resume,
            'cover_letter' => $cover_letter,
        ]);
 
        if($application){
            $email = $request->email;
            $position = Careers::where('id', $request->career_id)->value('title');
            $application_sent = Mail::to($email)->send(new ApplicationSent($request->firstname, $request->lastname, $request->email, $position));
            if($application_sent){
                return redirect('application-sent')->with('application_sent', 'Your Application was sent successfully!');
            }
            else{
                dd("Email was not sent!");
            }
        }
        else{
            return redirect()->back()->with('message', 'Something went wrong. Try again!');
          } 
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}