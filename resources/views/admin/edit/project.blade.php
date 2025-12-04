@extends('admin.layouts.app')

@section('title')
    <title>HFRO - Edit Project</title>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1 class="mb-0 text-primary fw-bold">Edit Project</h1>
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Edit Project</li>
        </ol>
    </div>
</section>

<section class="content mb-4">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data"
                    x-data="{ 
                        photoPreview: null, 
                        currentPhoto: '{{ $project->photo ? asset('storage/' . $project->photo->file_path) : '' }}',
                        documents: [] 
                    }">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <h5 class="text-secondary border-bottom pb-2 mb-3">General Information</h5>

                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control rounded-2 @error('title') is-invalid @enderror"
                                    value="{{ old('title', $project->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="cause_id" class="form-label fw-semibold">Cause</label>
                                <select name="cause_id" id="cause_id"
                                    class="form-control rounded-2 @error('cause_id') is-invalid @enderror">
                                    <option value="">Select a cause</option>
                                    @foreach($causes as $cause)
                                        <option value="{{ $cause->id }}" 
                                            {{ old('cause_id', $project->cause_id) == $cause->id ? 'selected' : '' }}>
                                            {{ $cause->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cause_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label fw-semibold">Project Photo</label>
                                <input type="file" name="photo" id="photo"
                                    class="form-control rounded-2 @error('photo') is-invalid @enderror"
                                    accept="image/*"
                                    @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                                @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                <div class="mt-3 text-center">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="img-fluid rounded-3 shadow-sm" style="max-height:180px;">
                                    </template>
                                    <template x-if="!photoPreview && currentPhoto">
                                        <img :src="currentPhoto" class="img-fluid rounded-3 shadow-sm" style="max-height:180px;">
                                    </template>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="summary" class="form-label fw-semibold">Short Summary</label>
                                <textarea name="summary" rows="3"
                                    class="form-control rounded-2 @error('summary') is-invalid @enderror">{{ old('summary', $project->summary) }}</textarea>
                                @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <h5 class="text-secondary border-bottom pb-2 mb-3">Budget, Schedule & Documents</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="budget" class="form-label fw-semibold">Budget (RWF)</label>
                                    <input type="number" name="budget" id="budget"
                                        class="form-control rounded-2 @error('budget') is-invalid @enderror"
                                        value="{{ old('budget', $project->budget) }}">
                                    @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="beneficiaries" class="form-label fw-semibold">Beneficiaries</label>
                                    <input type="number" name="beneficiaries" id="beneficiaries"
                                        class="form-control rounded-2 @error('beneficiaries') is-invalid @enderror"
                                        value="{{ old('beneficiaries', $project->beneficiaries) }}">
                                    @error('beneficiaries')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-semibold">Start Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control rounded-2 @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date', $project->start_date) }}">
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-semibold">End Date</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="form-control rounded-2 @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date', $project->end_date) }}">
                                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="progress" class="form-label fw-semibold">Progress (%)</label>
                                <input type="range" name="progress" id="progress"
                                    class="form-range @error('progress') is-invalid @enderror"
                                    min="0" max="100" value="{{ old('progress', $project->progress) }}"
                                    oninput="document.getElementById('progressValue').innerText = this.value + '%'">
                                <span id="progressValue" class="text-muted">{{ old('progress', $project->progress) }}%</span>
                                @error('progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-semibold">Project Status</label>
                                <select name="status" id="status"
                                    class="form-control rounded-2 @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="paused" {{ old('status', $project->status) == 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Document Upload -->
                            <div class="mb-3" x-data>
                                <label for="documents" class="form-label fw-semibold">Project Documents</label>
                                <input type="file" name="documents[]" id="documents" 
                                    class="form-control rounded-2 @error('documents') is-invalid @enderror" 
                                    multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png"
                                    @change="documents = Array.from($event.target.files)">
                                @error('documents')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                <!-- New documents preview -->
                                <template x-if="documents.length">
                                    <ul class="list-group mt-3">
                                        <template x-for="(file, index) in documents" :key="index">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span x-text="file.name"></span>
                                                <span class="badge bg-secondary rounded-pill" 
                                                    x-text="Math.round(file.size / 1024) + ' KB'"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>

                                <!-- Existing documents -->
                                @if($project->documents && $project->documents->count())
                                    <div class="mt-3">
                                        <h6 class="fw-semibold">Existing Documents:</h6>
                                        <ul class="list-group">
                                            @foreach($project->documents as $doc)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                                                        {{ basename($doc->file_path) }}
                                                    </a>
                                                    <span class="text-muted small">{{ strtoupper($doc->file_extension) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 my-3">
                            <label for="myeditorinstance" class="form-label fw-semibold">Project Description</label>
                            <textarea name="description" rows="4"
                                id="myeditorinstance"
                                class="form-control rounded-2 @error('description') is-invalid @enderror"
                                placeholder="Describe the project">{{ old('description', $project->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="text-end mt-4 border-top pt-3">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">💾 Update Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
