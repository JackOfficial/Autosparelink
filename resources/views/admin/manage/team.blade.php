@extends('admin.layouts.app')

@section('content')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Team Members ({{ $teams->count() }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Stories</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<section class="content">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif


    <div class="card">
        <div class="card-header">
      <h3 class="card-title">
       <a href="{{ route('admin.team.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus-circle"></i> Add Team Member
</a>
      </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Social Links</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($team->profilePhoto)
                            <img src="{{ asset('storage/' . $team->profilePhoto->file_path) }}" 
                                 alt="{{ $team->name }}" 
                                 class="rounded-circle" 
                                 width="50" height="50">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 class="rounded-circle d-none" 
                                 width="50" height="50" 
                                 alt="No Photo">
                                 <div>No Photo</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $team->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $team->organization->name ?? 'Happy Family Rwanda' }}</small>
                    </td>
                    <td>{{ $team->position ?? '—' }}</td>
                    <td>{{ $team->email ?? '—' }}</td>
                    <td>{{ $team->phone ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            @if($team->facebook)
                                <a href="{{ $team->facebook }}" target="_blank" class="text-primary">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            @endif
                            @if($team->linkedin)
                                <a href="{{ $team->linkedin }}" target="_blank" class="text-primary">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            @endif
                            @if($team->twitter)
                                <a href="{{ $team->twitter }}" target="_blank" class="text-info">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $team->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($team->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.team.edit', $team) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.team.destroy', $team) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure you want to delete this team member?')" 
                                    class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No team members found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    </section>



{{-- Font Awesome for social icons --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endpush
@endsection
