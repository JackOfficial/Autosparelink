<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class GalleryManager extends Component
{
    use WithFileUploads;

    public $file;
    public $caption;
    public $editingPhotoId = null;
    public $selectedPhotos = [];
    public $modelFilter = 'all';
    public $photoGroups = [];

    protected $rules = [
        'file' => 'nullable|image|max:2048',
        'caption' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->loadPhotos();
    }

    public function updatedModelFilter()
    {
        $this->loadPhotos();
    }

   public function loadPhotos()
{
    $query = Photo::query();

    if ($this->modelFilter !== 'all') {
        $query->where('imageable_type', $this->modelFilter);
    }

    $photos = $query->latest()->get();

    $groups = [];

    foreach ($photos as $photo) {
        // Map friendly name immediately
        $type = match ($photo->imageable_type) {
            'App\Models\Project' => 'Projects',
            'App\Models\Team'    => 'Teams',
            'App\Models\Event'   => 'Events',
            default               => class_basename($photo->imageable_type),
        };

        $groups[$type][] = $photo;
    }

    // Convert each group to collection
    $this->photoGroups = collect($groups)->map(fn($group) => collect($group));
}

    public function save()
    {
        $this->validate();

        if ($this->editingPhotoId) {
            $photo = Photo::find($this->editingPhotoId);
            if (!$photo) return;

            if ($this->file) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->file_path = $this->file->store('photos', 'public');
            }
            $photo->caption = $this->caption;
            $photo->save();

            $this->editingPhotoId = null;
            session()->flash('message', 'Photo updated.');
        } else {
            if (!$this->file) {
                session()->flash('message', 'Please choose a photo.');
                return;
            }

            Photo::create([
                'file_path' => $this->file->store('photos', 'public'),
                'caption' => $this->caption,
                'imageable_type' => 'App\\Models\\Project', // adjust dynamically if needed
                'imageable_id' => 1,
            ]);

            session()->flash('message', 'Photo uploaded.');
        }

        $this->reset(['file', 'caption']);
        $this->loadPhotos();
    }

    public function edit($id)
    {
        $photo = Photo::find($id);
        if (!$photo) return;

        $this->editingPhotoId = $id;
        $this->caption = $photo->caption;
        $this->file = null;
         $this->dispatch('show-photo-modal'); 
    }

    public function delete($id)
    {
        $photo = Photo::find($id);
        if (!$photo) return;

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        $this->loadPhotos();
        session()->flash('message', 'Photo deleted.');
    }

    public function deleteSelected()
    {
        $photos = Photo::whereIn('id', $this->selectedPhotos)->get();
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $this->selectedPhotos = [];
        $this->loadPhotos();
        session()->flash('message', 'Selected photos deleted.');
    }

    public function render()
    {
        // Map friendly names
    $friendlyGroups = collect($this->photoGroups)->mapWithKeys(function ($group, $type) {
        $friendlyName = match ($type) {
            'App\Models\Project' => 'Projects',
            'App\Models\Team'    => 'Teams',
            'App\Models\Event'   => 'Events',
            default               => class_basename($type),
        };
        return [$friendlyName => $group];
    });

    return view('livewire.admin.gallery-manager', [
        'photoGroups' => $friendlyGroups,
        'totalPhotos' => collect($this->photoGroups)->sum(fn($group) => count($group)), // total count
    ]);
    }
}
