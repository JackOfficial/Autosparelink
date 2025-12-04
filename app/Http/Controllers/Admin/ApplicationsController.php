<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applications;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\CallforinterviewMail;
use App\Mail\ShortlistMail;
use App\Models\Careers;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;
use Illuminate\Support\Facades\Storage;

class ApplicationsController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function exportAll(){
      $header_style = (new Style())->setFontBold()->setBackgroundColor("FFCC00");
      $rows_style = (new Style())
      ->setShouldWrapText();
     
  $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
       ->join('countries', 'applications.country_id', 'countries.id')
       ->select('applications.*', 'careers.title', 'countries.name')
       ->orderBy('applications.first_name', 'ASC')
       ->get();
       
      return (new FastExcel($applications))
      ->headerStyle($header_style)
      ->rowsStyle($rows_style)
      ->download('Applicant list.xlsx');
      dd("done!");
  }

    public function shortlist(Request $request)
    {
       $selected = $request->id;
       $shortlist = Applications::join('careers', 'applications.career_id', 'careers.id')
       ->select('applications.*', 'careers.title')
       ->whereIn('applications.id', $selected)
       ->update([
        'applications.status' => 1
       ]);

       if($shortlist){
         $shortlisted = Applications::join('careers', 'applications.career_id', 'careers.id')
         ->select('applications.*', 'careers.title')
         ->whereIn('applications.id', $selected)
         ->orderBy('applications.first_name', 'ASC')
         ->get();
             
          foreach($shortlisted as $applicant){
              Mail::to($applicant->email)->send(new ShortlistMail($applicant->first_name, $applicant->last_name, $applicant->email, $applicant->title));
          }
        return to_route('admin.applications.index')->with("message", "Applicant shortlisted!"); 
       }
    }

    public function filter(Request $request){
      $postId = $request->id;
      if($postId == "all"){
        return to_route('admin.applications.index'); 
      }
      else{
        $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
        ->join('countries', 'applications.country_id', 'countries.id')
        ->select('applications.*', 'careers.title', 'countries.name')
        ->where('careers.id', $postId)
        ->orderBy('applications.first_name', 'ASC')
        ->get();
  
        $ids = Applications::pluck('id');
  
        $posts = Careers::get();
  
        return Inertia::render('Admin/Manage/Applications', compact('applications', 'ids', 'posts'));
      }
     
    }

    public function exportSelected($id){ 
      //return Storage::download('/storage/applications/resumes/B4noggLrIslbB8Mh254E34KGmV1ocjh8SEbcUNya.pdf', 'filename');
      $header_style = (new Style())->setFontBold()->setBackgroundColor("FFCC00");
      $rows_style = (new Style())
      ->setShouldWrapText();
      $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
      ->join('countries', 'applications.country_id', 'countries.id')
      ->select('applications.*', 'careers.title', 'countries.name')
      ->where('careers.id', $id)
      ->orderBy('applications.first_name', 'ASC')
      ->get();
     // (new FastExcel($users))->export('file.xlsx');
      return (new FastExcel($applications))
      ->headerStyle($header_style)
      ->rowsStyle($rows_style)
      ->download('Applicant list.xlsx');

      //$postId = $request->id;
      //dd($postId);

    //   if($postId == "all"){
    //     $header_style = (new Style())->setFontBold()->setBackgroundColor("FFCC00");
    //     $rows_style = (new Style())
    //     ->setShouldWrapText();
    // $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
    //      ->join('countries', 'applications.country_id', 'countries.id')
    //      ->select('applications.*', 'careers.title', 'countries.name')
    //      ->orderBy('applications.first_name', 'ASC')
    //      ->get();
    //    // (new FastExcel($users))->export('file.xlsx');
    //     return (new FastExcel($applications))
    //     ->headerStyle($header_style)
    //     ->rowsStyle($rows_style)
    //     ->download('Applicant list.xlsx');
    //   }
    //   else{
    //     $header_style = (new Style())->setFontBold()->setBackgroundColor("FFCC00");
    //   $rows_style = (new Style())
    //   ->setShouldWrapText();
    //   $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
    //   ->join('countries', 'applications.country_id', 'countries.id')
    //   ->select('applications.*', 'careers.title', 'countries.name')
    //   ->where('careers.id', $postId)
    //   ->orderBy('applications.first_name', 'ASC')
    //   ->get();
    //  // (new FastExcel($users))->export('file.xlsx');
    //   return (new FastExcel($applications))
    //   ->headerStyle($header_style)
    //   ->rowsStyle($rows_style)
    //   ->download('Applicant list.xlsx');
    //   }
     
    }

    public function search(Request $request){
      $keyword = $request->keyword;
      if($keyword == ""){
        return to_route('admin.applications.index'); 
      }
      else{
        $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
        ->join('countries', 'applications.country_id', 'countries.id')
        ->select('applications.*', 'careers.title', 'countries.name')
        ->where('applications.first_name', $keyword)
        ->orWhere('applications.email', $keyword)
        ->orWhere('applications.phone', $keyword)
        ->orderBy('applications.first_name', 'ASC')
        ->get();
  
        $ids = Applications::pluck('id');
  
        $posts = Careers::get();
  
        return Inertia::render('Admin/Manage/Applications', compact('applications', 'ids', 'posts'));
      }
     
    }

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
       ->join('countries', 'applications.country_id', 'countries.id')
       ->select('applications.*', 'careers.title', 'countries.name')
       ->orderBy('applications.first_name', 'ASC')
       ->get();

       $ids = Applications::pluck('id');

       $posts = Careers::get();

       return Inertia::render('Admin/Manage/Applications', compact('applications', 'ids', 'posts'));
    }

    public function downloadfiles(Request $request){
     // dd("Hellooo");

     $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
      ->join('countries', 'applications.country_id', 'countries.id')
      ->select('applications.*', 'careers.title', 'countries.name')
      ->where('careers.id', 10)
      ->orderBy('applications.first_name', 'ASC')
      ->get();

      $directory = 'public/applications/resumes';
      $getfiles = Storage::files($directory);

      $a=array();
      foreach($getfiles as $key => $value){
        $x = substr($value, 7, 200);
        array_push($a, $x);
      }

      // for($i=0; $i<count($a); $i++){
      //   echo $a[$i]."<br>";
      // }

      foreach($applications as $application){
        //echo "$application->resume === ";
        if (in_array($application->resume, $getfiles)){
          echo "irimo<br>";
        }
        else{
          echo "ntago irimo<br>";
        }
      }

      


      // $directory = 'public/applications/resumes';
      // $getfiles = Storage::files($directory);
      // foreach($getfiles as $key => $value){
      //   $x = substr($value, 7, 200);
      //   if($x ){
      //     $applications = Applications::where('resume', $x)
      // ->get();
      //   }
      //   echo "$x<br>";
      // }




      // foreach($applications as $application){
      //   if()
   
      // }

      // $file_path = public_path('storage/applications/resumes/B4noggLrIslbB8Mh254E34KGmV1ocjh8SEbcUNya.pdf');
      // $file_name = 'custom_file_name.pdf';
      // return response()->download($file_path, $file_name);

    

    // return Storage::download('/storage/applications/resumes/B4noggLrIslbB8Mh254E34KGmV1ocjh8SEbcUNya.pdf', 'filename');
    }

    public function hire(Request $request)
    {
       $selected = $request->id;
       $hired = Applications::join('careers', 'applications.career_id', 'careers.id')
       ->select('applications.*', 'careers.title')
       ->whereIn('applications.id', $selected)
       ->update([
        'applications.status' => 2
       ]);

       if($hired){
         $hired = Applications::join('careers', 'applications.career_id', 'careers.id')
         ->select('applications.*', 'careers.title')
         ->whereIn('applications.id', $selected)
         ->get();
         
          foreach($hired as $applicant){
              Mail::to($applicant->email)->send(new CallforinterviewMail($applicant->first_name, $applicant->last_name, $applicant->email, $applicant->title));
          }
        return to_route('admin.applications.index')->with("message", "Applicant hired!"); 
       }
    }

    public function reject(Request $request)
    {
       $selected = $request->id;
       $reject = Applications::whereIn('id', $selected)
       ->update([
        'applications.status' => 3
       ]);

       if($reject){
        return to_route('admin.applications.index')->with("message", "Application rejected!"); 
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       dd("Hello world");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $application = Applications::join('careers', 'applications.career_id', 'careers.id')
       ->join('countries', 'applications.country_id', 'countries.id')
       ->select('applications.*', 'careers.title', 'countries.name')
       ->where('applications.id', $id)
       ->first();
       return Inertia::render('Admin/Show/Application', compact('application'));
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
      $deleteApplication = Applications::where('id', $id)->delete();
      if($deleteApplication){
        return to_route('admin.applications.index')->with("message", "Application deleted!"); 
      }
      else{
       return to_route('admin.applications.index')->with("message", "Application could not be deleted!"); 
      }
    }
}
