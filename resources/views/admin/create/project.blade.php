@extends('admin.layouts.app')

@section('title')
    <title>HFRO - Create Project</title>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1 class="mb-0 text-primary fw-bold">Create New Project</h1>
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Create Project</li>
        </ol>
    </div>
</section>

<section class="content mb-4">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" 
                    x-data="{ photoPreview: null, documents: [] }">
                    @csrf

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <h5 class="text-secondary border-bottom pb-2 mb-3">General Information</h5>

                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control rounded-2 @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required placeholder="Enter project title">
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="cause_id" class="form-label fw-semibold">Cause</label>
                                <select name="cause_id" id="cause_id"
                                    class="form-control rounded-2 @error('cause_id') is-invalid @enderror">
                                    <option value="">Select a cause</option>
                                    @foreach($causes as $cause)
                                        <option value="{{ $cause->id }}" {{ old('cause_id') == $cause->id ? 'selected' : '' }}>
                                            {{ $cause->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cause_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3" x-data>
                                <label for="photo" class="form-label fw-semibold">Project Photo</label>
                                <input type="file" name="photo" id="photo"
                                    class="form-control rounded-2 @error('photo') is-invalid @enderror"
                                    accept="image/*"
                                    @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                                @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                <template x-if="photoPreview">
                                    <div class="mt-3 text-center">
                                        <img :src="photoPreview" class="img-fluid rounded-3 shadow-sm" style="max-height:180px;">
                                    </div>
                                </template>
                            </div>

                            <div class="mb-3">
                                <label for="summary" class="form-label fw-semibold">Short Summary</label>
                                <textarea name="summary" rows="3"
                                    class="form-control rounded-2 @error('summary') is-invalid @enderror"
                                    placeholder="Brief summary of the project">{{ old('summary') }}</textarea>
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
                                        value="{{ old('budget') }}" placeholder="e.g. 5000000">
                                    @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="beneficiaries" class="form-label fw-semibold">Beneficiaries</label>
                                    <input type="number" name="beneficiaries" id="beneficiaries"
                                        class="form-control rounded-2 @error('beneficiaries') is-invalid @enderror"
                                        value="{{ old('beneficiaries') }}" placeholder="e.g. 120">
                                    @error('beneficiaries')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-semibold">Start Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control rounded-2 @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date') }}">
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-semibold">End Date</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="form-control rounded-2 @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date') }}">
                                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="progress" class="form-label fw-semibold">Progress (%)</label>
                                <input type="range" name="progress" id="progress"
                                    class="form-control @error('progress') is-invalid @enderror"
                                    min="0" max="100" value="{{ old('progress', 0) }}"
                                    oninput="document.getElementById('progressValue').innerText = this.value + '%'">
                                <span id="progressValue" class="text-muted">{{ old('progress', 0) }}%</span>
                                @error('progress')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-semibold">Project Status</label>
                                <select name="status" id="status"
                                    class="form-control rounded-2 @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Multiple Document Upload -->
                            <div class="mb-3" x-data>
                                <label for="documents" class="form-label fw-semibold">Project Documents</label>
                                <input type="file" name="documents[]" id="documents" 
                                    class="form-control rounded-2 @error('documents') is-invalid @enderror" 
                                    multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png"
                                    @change="documents = Array.from($event.target.files)">
                                @error('documents')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

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
                            </div>
                        </div>
                        <div class=" col-md-12 my-3">
                                <label for="myeditorinstance" class="form-label fw-semibold">Project Description</label>
                                <textarea name="description" rows="4"
                                id="myeditorinstance"
                                    class="form-control rounded-2 @error('description') is-invalid @enderror"
                                    placeholder="Describe the project">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                    </div>

                    <div class="text-end mt-4 border-top pt-3">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">💾 Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
