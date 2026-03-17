@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-shopping-cart text-warning mr-2"></i>Abandoned Carts</h2>
        <span class="badge badge-pill badge-dark px-3 py-2">{{ $users->count() }} Users with Items</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>User Name</th>
                        <th>Contact Info</th>
                        <th>Last Updated</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="align-middle">
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted">ID: #{{ $user->id }}</small>
                            </td>
                            <td class="align-middle">
                                <div><i class="fa fa-envelope small mr-1"></i> {{ $user->email }}</div>
                                <div><i class="fa fa-phone small mr-1"></i> {{ $user->phone ?? 'No Phone' }}</div>
                            </td>
                            <td class="align-middle text-muted">
                                {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.carts.show', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa fa-eye mr-1"></i> View Items
                                </a>
                                
                                <form action="{{ route('admin.carts.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Clear this user\'s cart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa fa-shopping-basket fa-3x mb-3"></i><br>
                                    No active or abandoned carts found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection