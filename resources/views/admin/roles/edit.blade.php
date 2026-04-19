@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    {{-- Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}" class="text-muted small">Roles</a></li>
                    <li class="breadcrumb-item active font-weight-bold small">Access Matrix</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <span class="p-3 rounded-circle bg-soft-primary d-inline-block">
                                <i class="fas fa-fingerprint text-primary fa-2x"></i>
                            </span>
                        </div>
                        <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">System Role</label>
                        <h3 class="font-weight-bold text-dark mb-4">{{ ucfirst($role->name ?? 'New Role') }}</h3>
                        
                        <input type="hidden" name="name" value="{{ $role->name ?? '' }}">
                        
                        <div class="text-left small bg-light p-3 rounded mb-4">
                            <p class="mb-0 text-muted">
                                <i class="fas fa-info-circle text-primary mr-1"></i> 
                                Permissions selected here define what users in this group can see and do within the dashboard.
                            </p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold py-3">
                            <i class="fas fa-save mr-2"></i> Commit Changes
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-dark">Module Permissions</h5>
                        <div class="badge badge-primary px-3 py-2 rounded-pill">
                            <span x-text="document.querySelectorAll('.perm-check:checked').length"></span> / {{ $permissions->count() }} Set
                        </div>
                    </div>
                    
                    <div class="card-body p-4 bg-light-gray">
                        <div class="row">
                            @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    {{-- Alpine Component for each Group --}}
                                    <div class="card border-0 shadow-sm h-100" 
                                         x-data="{ 
                                            total: {{ $groupPermissions->count() }},
                                            selected: [{{ implode(',', $groupPermissions->filter(fn($p) => in_array($p->name, $rolePermissions ?? []))->pluck('id')->toArray()) }}],
                                            toggleAll() {
                                                if (this.selected.length < this.total) {
                                                    this.selected = [{{ implode(',', $groupPermissions->pluck('id')->toArray()) }}];
                                                } else {
                                                    this.selected = [];
                                                }
                                            }
                                         }">
                                        
                                        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                                            <h6 class="mb-0 font-weight-bold text-dark text-uppercase small">
                                                {{ $group }}
                                            </h6>
                                            
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="select-all-{{ $group }}"
                                                       @click="toggleAll()"
                                                       :checked="selected.length === total"
                                                       :indeterminate="selected.length > 0 && selected.length < total">
                                                <label class="custom-control-label small font-weight-bold" for="select-all-{{ $group }}">All</label>
                                            </div>
                                        </div>

                                        <div class="card-body pt-3">
                                            @foreach($groupPermissions as $permission)
                                                <div class="custom-control custom-checkbox mb-2 py-1 border-bottom-light">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->name }}" 
                                                           class="custom-control-input perm-check" 
                                                           id="perm-{{ $permission->id }}"
                                                           x-model="selected"
                                                           :value="{{ $permission->id }}">
                                                    <label class="custom-control-label text-muted small w-100" 
                                                           for="perm-{{ $permission->id }}" 
                                                           style="cursor: pointer;">
                                                        {{ str_replace('.', ' ', $permission->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="progress rounded-0" style="height: 3px;">
                                            <div class="progress-bar bg-primary" 
                                                 :style="`width: ${(selected.length / total) * 100}%`" ></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .bg-light-gray { background-color: #f4f7f6; }
    .bg-soft-primary { background-color: #eef2ff; }
    .border-bottom-light { border-bottom: 1px solid #f1f1f1; }
    .border-bottom-light:last-child { border-bottom: none; }
    .custom-control-label { transition: color 0.2s; }
    .custom-control-input:checked ~ .custom-control-label { color: #212529 !important; font-weight: 500; }
    .permission-group { border-radius: 10px; }
</style>
@endsection