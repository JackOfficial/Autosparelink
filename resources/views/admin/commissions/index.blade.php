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
            {{-- Only Super Admin can see the create button --}}
            @role('super-admin')
            <a href="{{ route('admin.commissions.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus mr-1"></i> Set New Rate
            </a>
            @endrole
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
                        {{-- Only Super Admin sees the Action header --}}
                        @role('super-admin')
                        <th class="text-right pr-4">Actions</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $commission)
                    <tr>
                        <td class="pl-4">
                            <span class="text-dark d-block font-weight-bold">{{ $commission->created_at->format('d M, Y') }}</span>
                            <small class="text-muted">{{ $commission->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <span class="h6 font-weight-bold text-primary">{{ $commission->rate }}%</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $commission->description ?? 'Standard platform fee' }}</span>
                        </td>
                        <td>
                            @if($commission->is_active)
                                <span class="badge badge-success px-3 py-2 shadow-sm" style="border-radius: 50px;">
                                    <i class="fas fa-check mr-1"></i> ACTIVE
                                </span>
                            @else
                                <span class="badge bg-soft-secondary text-muted border px-3 py-2" style="border-radius: 50px;">
                                    INACTIVE
                                </span>
                            @endif
                        </td>

                        {{-- Only Super Admin sees the Action buttons --}}
                        @role('super-admin')
                        <td class="text-right pr-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.commissions.edit', $commission) }}" 
                                   class="btn btn-sm btn-white border shadow-none" 
                                   title="Edit Rate">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                
                                @if(!$commission->is_active)
                                <form action="{{ route('admin.commissions.destroy', $commission) }}" method="POST" class="d-inline ml-1">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-white border text-danger shadow-none" 
                                            onclick="return confirm('Are you sure you want to delete this commission record?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endrole
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-3 opacity-25"></i>
                            <p>No commission history found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($commissions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing history of global rate changes.</small>
                {{ $commissions->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .bg-soft-secondary { background-color: #f8f9fa; }
    .table td { vertical-align: middle; }
    .badge { letter-spacing: 0.3px; font-weight: 600; }
</style>
@endpush
@endsection