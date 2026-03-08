<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Photo;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Display all events
    public function index()
    {
        $events = Event::with('event_photos')->latest()->get();
        return view('admin.manage.events', compact('events'));
    }

    // Show form to create new event
    public function create()
    {
        return view('admin.create.event');
    }

    // Store new event
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'link' => 'nullable|url',
            'status' => 'required|string|in:active,inactive',
            'photos.*' => 'nullable|image|max:2048',
        ]);
        
        $organization = Organization::first();

        $event = Event::create([
            'organization_id' => $organization->id, // Since you have only one organization
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        // Handle photo uploads if any
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('events', 'public');
                $event->event_photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }
    
    public function edit(Event $event)
{
    // Load the event along with its photos
    $event->load('event_photos');

    // Return the edit view
    return view('admin.edit.event', compact('event'));
}

    // Show form to edit existing event
    public function update(Request $request, Event $event)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'nullable|date',
        'time' => 'nullable',
        'location' => 'nullable|string|max:255',
        'link' => 'nullable|url|max:255',
        'status' => 'required|string|in:active,inactive',
        'photos.*' => 'nullable|image|max:2048',
        'removed_photos' => 'nullable|string',
    ]);

    $event->update([
        'title' => $request->title,
        'description' => $request->description,
        'date' => $request->date,
        'time' => $request->time,
        'location' => $request->location,
        'link' => $request->link,
        'status' => $request->status,
    ]);

    // Delete removed photos
    if ($request->removed_photos) {
        $ids = explode(',', $request->removed_photos);
        $photos = $event->event_photos()->whereIn('id', $ids)->get();
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }
    }

    // Upload new photos
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $file) {
            $path = $file->store('events', 'public');
            $event->event_photos()->create([
                'file_path' => $path,
            ]);
        }
    }

    return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
}

    // Delete event and its photos
    public function destroy(Event $event)
    {
        foreach ($event->event_photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
