<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Cause;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Volunteer;

class AdminController extends Controller
{
    public function index(){
        $causes = Cause::where('status', 1)->count();
        $volunteers = Volunteer::count();
        $projects = Project::count();
        $projectsPercentages = Project::all();
        $applications = Application::count();
        $count = 0;
        foreach($projectsPercentages as $projectsPercentage){
            $count = $count + $projectsPercentage->progress;
        }
        $percentage = ($count * 100) / 1; //($projects*100);
        return view('admin.index', compact('causes', 'volunteers', 'projects', 'percentage', 'applications'));
    }

    public function addTask(Request $request){
        Todo::create([
            'task' => $request->input('task')
        ]);
        return redirect()->back();
    }

    public function taskDone($id){
        Todo::where('id', $id)->update([
            'status' => 2
        ]);
    }
}
