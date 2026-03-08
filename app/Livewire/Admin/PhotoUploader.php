<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage; 

class PhotoUploader extends Component
{
    use WithFileUploads;

    public $photos = [];
    public $existingPhotos = [];
    public $imageableType;
    public $imageableId;

    protected $rules = [
        'photos.*' => 'image|max:2048', // each image max 2MB
    ];
    
    public function mount($imageableType, $imageableId)
    {
        $this->imageableType = $imageableType;
        $this->imageableId = $imageableId;

        $this->existingPhotos = Photo::where('imageable_type', $imageableType)
            ->where('imageable_id', $imageableId)
            ->get();
    }

    public function updatedPhotos()
    {
        $this->validate();

        foreach ($this->photos as $photo) {
            $path = $photo->store('uploads/photos', 'public');

            Photo::create([
                'file_path' => $path,
                'imageable_type' => $this->imageableType,
                'imageable_id' => $this->imageableId,
            ]);
        }

        $this->reset('photos');
        $this->mount($this->imageableType, $this->imageableId); // reload photos
        session()->flash('success', 'Photos uploaded successfully!');
    }

    public function deletePhoto($photoId)
    {
        $photo = Photo::find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
            $this->mount($this->imageableType, $this->imageableId);
            session()->flash('success', 'Photo deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.photo-uploader');
    }
}
