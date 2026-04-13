@extends('admin.layouts.app')
@section('title', 'Shops')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 font-weight-bold">Shop Management</h2>
        <div class="badge badge-info px-3 py-2">{{ $shops->total() }} Total Shops</div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted uppercase small font-weight-bold">
                    <tr>
                        <th class="px-4 py-3">Shop Details</th>
                        <th>Owner</th>
                        <th>TIN / RDB</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th class="text-right px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shops as $shop)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-soft text-primary rounded p-2 mr-3">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $shop->shop_name }}</div>
                                    <div class="small text-muted">{{ $shop->shop_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small font-weight-bold">{{ $shop->user->name }}</div>
                            <div class="small text-muted">{{ $shop->phone_number }}</div>
                        </td>
                        <td>
                            <div class="badge badge-light border text-dark mb-1">TIN: {{ $shop->tin_number }}</div>
                            <br>
                            <a href="{{ route('admin.shops.show', $shop) }}" class="small text-primary">
                                <i class="fas fa-file-pdf mr-1"></i> View Docs
                            </a>
                        </td>
                        <td>
                            @if($shop->is_active)
                                <span class="badge badge-success px-3 py-1">Active</span>
                            @else
                                <span class="badge badge-warning px-3 py-1 text-white">Pending Review</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $shop->created_at->format('d M, Y') }}
                        </td>
                        <td class="text-right px-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" data-toggle="dropdown">
                                    Manage
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item" href="{{ route('admin.shops.show', $shop) }}">
                                        <i class="fas fa-eye mr-2 text-muted"></i> View Details
                                    </a>
                                    
                                    @if(!$shop->is_active)
                                        <form action="{{ route('admin.shops.approve', $shop) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="fas fa-check mr-2"></i> Approve Shop
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-power-off mr-2 text-muted"></i> 
                                            {{ $shop->is_active ? 'Suspend Shop' : 'Activate Shop' }}
                                        </button>
                                    </form>

                                    <div class="dropdown-divider"></div>
                                    
                                    <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" onsubmit="return confirm('Delete this shop permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash mr-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $shops->links() }}
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(0, 123, 255, 0.1); }
    .table thead th { border-top: 0; border-bottom: 1px solid #edf2f9; }
    .dropdown-item { font-size: 0.85rem; padding: 0.5rem 1rem; }
</style>
@endsection