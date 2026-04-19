@extends('admin.layouts.app')

@section('title', 'Commission Settings | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-percentage mr-2 text-primary"></i>Platform Commissions
            </h1>
            <p class="text-muted small">Manage the global commission rate applied to all vendor sales.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.commissions.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus mr-1"></i> Set New Rate
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <small class="text-uppercase opacity-75 d-block mb-1">Current Active Rate</small>
                    <h2 class="font-weight-bold mb-0">{{ \App\Models\Commission::getRate() }}%</h2>
                    <i class="fas fa-check-circle position-absolute" style="right: 20px; top: 25px; font-size: 2rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light small text-uppercase font-weight-bold">
                    <tr>
                        <th class="pl-4">Date Created</th>
                        <th>Rate (%)</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $commission)
                    <tr>
                        <td class="pl-4">
                            <span class="text-dark d-block">{{ $commission->created_at->format('d M, Y') }}</span>
                            <small class="text-muted">{{ $commission->created_at->diffForHumans() }}</small>
                        </td>
                        <td><span class="h6 font-weight-bold text-primary">{{ $commission->rate }}%</span></td>
                        <td><span class="text-muted small">{{ $commission->description ?? 'No description' }}</span></td>
                        <td>
                            @if($commission->is_active)
                                <span class="badge badge-success px-3 py-2">ACTIVE</span>
                            @else
                                <span class="badge badge-light border text-muted px-3 py-2">INACTIVE</span>
                            @endif
                        </td>
                        <td class="text-right pr-4">
                            <a href="{{ route('admin.commissions.edit', $commission) }}" class="btn btn-sm btn-white border shadow-none">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$commission->is_active)
                            <form action="{{ route('admin.commissions.destroy', $commission) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-white border text-danger shadow-none" onclick="return confirm('Delete this record?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No commission records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($commissions->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection