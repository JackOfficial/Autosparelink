<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applications;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;

class ExportsController extends Controller
{
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

            public function exportSelected($id){
                $postId = $id;
                  $header_style = (new Style())->setFontBold()->setBackgroundColor("FFCC00");
                $rows_style = (new Style())
                ->setShouldWrapText();
                $applications = Applications::join('careers', 'applications.career_id', 'careers.id')
                ->join('countries', 'applications.country_id', 'countries.id')
                ->select('applications.*', 'careers.title', 'countries.name')
                ->where('careers.id', $postId)
                ->orderBy('applications.first_name', 'ASC')
                ->get();
               // (new FastExcel($users))->export('file.xlsx');
                return (new FastExcel($applications))
                ->headerStyle($header_style)
                ->rowsStyle($rows_style)
                ->download('Applicant list.xlsx');
            }
}
