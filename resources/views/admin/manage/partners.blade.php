@extends('admin.layouts.app')
@section('title', 'Partners')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Partners ({{ $partners->count() }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Partners</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Success message -->
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-primary">
          <i class="fa fa-plus"></i> Add Partner
        </a>
      </h3>
    </div>
    <!-- /.card-header -->

    <div class="card-body table-responsive">
      <table id="partnersTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Logo</th>
            <th>Website</th>
            <th>Description</th>
            <th>Organization</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($partners as $partner)
          <tr>
            <td>{{ $partner->id }}</td>
            <td>{{ $partner->name }}</td>
            <td>
              @if($partner->logo)
                <a href="{{ asset('storage/' . $partner->logo) }}" target="_blank">
                  <img src="{{ asset('storage/' . $partner->logo) }}" 
                       alt="{{ $partner->name }}" 
                       class="img img-thumbnail" 
                       style="width: 100px; height: 70px; object-fit: cover;">
                </a>
              @else
                <div>No Logo</div>
              @endif
            </td>
            <td>
              @if($partner->website)
                <a href="{{ $partner->website }}" target="_blank">{{ $partner->website }}</a>
              @else
                -
              @endif
            </td>
            <td>{{ Str::limit(strip_tags($partner->description), 60) ?? '-' }}</td>
            <td>{{ $partner->organization->name ?? '-' }}</td>
            <td>{{ $partner->created_at->format('Y-m-d') }}</td>
            <td class="d-flex">
              <a class="btn btn-info btn-sm mr-2" href="{{ route('admin.partners.edit', $partner->id) }}">
                <i class="fas fa-pencil-alt"></i> Edit
              </a>

              <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="d-inline" 
                    onsubmit="return confirm('Are you sure you want to delete this partner?');">
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
            <td colspan="8" class="text-center">No partners available at the moment.</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Logo</th>
            <th>Website</th>
            <th>Description</th>
            <th>Organization</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>

      <!-- Pagination -->
     
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>
<!-- /.content -->
@endsection
