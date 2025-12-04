@extends('admin.layouts.app')
@section('title', 'Events')

@section('content')
<!-- Content Header -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Events ({{ $events->count() }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Events</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">

  <!-- Success message -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-primary">
          <i class="fa fa-plus"></i> Add Event
        </a>
      </h3>
    </div>

    <div class="card-body">
      <table id="eventsTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Status</th>
            <th>Photos</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($events as $event)
            <tr>
              <td>{{ $event->id }}</td>
              <td>{{ Str::limit($event->title, 50) }}</td>
              <td>
                {{ $event->date ? $event->date->format('Y-m-d') : 'N/A' }}
                {{ $event->time ?? '' }}
              </td>
              <td>{{ $event->location ?? 'N/A' }}</td>
              <td>
                <span class="badge {{ $event->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                  {{ ucfirst($event->status) }}
                </span>
              </td>
              <td>
                  <span class="badge bg-info">{{ $event->event_photos->count() }} {{ $event->event_photos->count() > 1 ? 'Photo' : 'Photos' }} </span>
              </td>
              <td class="d-flex">
                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-info btn-sm me-2">
                  <i class="fas fa-pencil-alt"></i> Edit
                </a>

                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">No events available.</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Status</th>
            <th>Photos</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  $(function () {
    $('#eventsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });
</script>
@endpush
