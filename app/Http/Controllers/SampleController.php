<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;
use App\Models\User;

class SampleController extends Controller
{
    public function exportAll(){
    
//    dd("Hello world");
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
}
