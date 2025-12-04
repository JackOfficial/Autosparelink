@extends('admin.layouts.app')
@section('title', 'Projects')
@section('content')

<section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h1>{{ $projects->count() }} {{ $projects->count() > 1 ? 'Projects' : 'Project' }}</h1>
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Projects</li>
        </ol>
    </div>
</section>

<section class="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Project
                </a>
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-striped projects mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cause</th>
                        <th>Project</th>
                        <th>Photo</th>
                        <th>Budget</th>
                        <th>Period</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->cause?->name ?? '-' }}</td>
                            <td>
                                <strong>{{ $project->title }}</strong>
                                <br>
                                <small>{{ $project->created_at->format('d M, Y') }}</small>
                            </td>
                            <td>
                                @if($project->project_photo)
                                    <a href="{{ asset('storage/' . $project->project_photo->file_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $project->project_photo->file_path) }}" 
                                            alt="{{ $project->title }}" class="img-thumbnail" style="width:50px; height:auto;">
                                    </a>
                                @else
                                    <span class="text-muted">No Photo</span>
                                @endif
                            </td>
                            <td>{{ $project->budget ? number_format($project->budget, 2) : '-' }}</td>
                            <td>
                                {{ $project->start_date?->format('d M, Y') ?? '-' }} 
                                - 
                                {{ $project->end_date?->format('d M, Y') ?? '-' }}
                            </td>
                            <td>
                                <div class="progress progress-sm">
                                    <div class="progress-bar {{ $project->progress == 100 ? 'bg-success' : 'bg-info' }}" 
                                        role="progressbar" style="width: {{ $project->progress }}%;" 
                                        aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small>{{ $project->progress }}% {{ $project->progress == 100 ? 'Complete' : '' }}</small>
                            </td>
                            <td>
                                @if($project->progress == 100)
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($project->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($project->documents->count())
                                    <span class="badge badge-info">{{ $project->documents->count() }} docs</span>
                                @else
                                    <span class="text-muted">No Docs</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-3">
                                No projects available. <a href="{{ route('admin.projects.create') }}">Add Project</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Cause</th>
                        <th>Project</th>
                        <th>Photo</th>
                        <th>Budget</th>
                        <th>Period</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

@endsection
