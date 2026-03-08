<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Cause;
use App\Models\Organization;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index()
    {
        $projects = Project::with(['project_photo', 'documents', 'cause'])->latest()->get();
        return view('admin.manage.projects', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $causes = Cause::all();
        return view('admin.create.project', compact('causes'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cause_id' => 'nullable|exists:causes,id',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'goal' => 'nullable|string',
            'beneficiaries' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'nullable|integer|between:0,100',
            'status' => 'in:active,completed,paused,cancelled',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:5120',
        ]);

        $slug = Str::slug($request->title);
        if (Project::where('slug', $slug)->exists()) $slug .= '-' . time();

        $organization = Organization::first();

        $project = Project::create([
            'title' => $request->title,
            'slug' => $slug,
            'organization_id' => $organization->id,
            'cause_id' => $request->cause_id,
            'summary' => $request->summary,
            'description' => $request->description,
            'goal' => $request->goal,
            'beneficiaries' => $request->beneficiaries,
            'budget' => $request->budget,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'progress' => $request->progress ?? 0,
            'status' => $request->status ?? 'active',
        ]);

        // Upload photo
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('projects', 'public');
            $project->project_photo()->create([
                'file_path' => $filePath,
                'caption' => $request->title,
            ]);
        }

        // Upload multiple documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filePath = $file->store('projects/documents', 'public');
                $project->documents()->create([
                    'title' => $request->title,
                    'file_path' => $filePath,
                    'file_extension' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display a specific project with documents
     */
    public function show(Project $project)
    {
        $project->load(['project_photo', 'documents', 'cause']);
        return view('admin.show.project', compact('project'));
    }

    /**
     * Show the form for editing a project
     */
    public function edit(Project $project)
    {
        $causes = Cause::all();
        $project->load(['project_photo', 'documents', 'cause']);
        return view('admin.edit.project', compact('project', 'causes'));
    }

    /**
     * Update a project
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cause_id' => 'nullable|exists:causes,id',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'goal' => 'nullable|string',
            'beneficiaries' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'nullable|integer|between:0,100',
            'status' => 'in:active,completed,paused,cancelled',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:5120',
        ]);

        $slug = Str::slug($request->title);
        if (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) $slug .= '-' . time();

        $project->update([
            'title' => $request->title,
            'slug' => $slug,
            'cause_id' => $request->cause_id,
            'summary' => $request->summary,
            'description' => $request->description,
            'goal' => $request->goal,
            'beneficiaries' => $request->beneficiaries,
            'budget' => $request->budget,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'progress' => $request->progress ?? $project->progress,
            'status' => $request->status ?? $project->status,
        ]);

        // Update photo
        if ($request->hasFile('photo')) {
            if ($project->project_photo) {
                Storage::disk('public')->delete($project->project_photo->file_path);
                $project->project_photo->delete();
            }
            $filePath = $request->file('photo')->store('projects', 'public');
            $project->project_photo()->create([
                'title' => $request->title,
                'file_path' => $filePath,
                'caption' => $request->title,
            ]);
        }

        // Add new documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filePath = $file->store('projects/documents', 'public');
                $project->documents()->create([
                    'title' => $request->title,
                    'file_path' => $filePath,
                    'file_extension' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Delete a project along with photo and documents
     */
    public function destroy(Project $project)
    {
        // Delete photo
        if ($project->project_photo) {
            Storage::disk('public')->delete($project->project_photo->file_path);
            $project->project_photo->delete();
        }

        // Delete documents
        foreach ($project->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * Optional: Delete a single document (if you add a button in edit page)
     */
    public function destroyDocument($id)
    {
        $document = Document::findOrFail($id);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
