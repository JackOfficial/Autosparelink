@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    {{-- Breadcrumb omitted for brevity, keep your existing one --}}

    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-4">
                {{-- ... Your existing sidebar code ... --}}
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-4 px-4 border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark">Capabilities Management</h5>
                                <p class="text-muted small mb-0">Smart toggle permissions using Alpine.js</p>
                            </div>
                            <span class="badge badge-primary badge-pill px-3 py-2">{{ $permissions->count() }} Total</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 bg-light-gray">
                        <div class="row">
                            @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    {{-- Alpine Component for each Group --}}
                                    <div class="card border-0 shadow-sm h-100 permission-group" 
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
                                        
                                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                                            <h6 class="mb-0 font-weight-bold text-primary text-uppercase small">
                                                <i class="fas fa-folder-open mr-2 opacity-5"></i>{{ $group }}
                                            </h6>
                                            
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="select-all-{{ $group }}"
                                                       @click="toggleAll()"
                                                       :checked="selected.length === total"
                                                       :indeterminate="selected.length > 0 && selected.length < total">
                                                <label class="custom-control-label font-weight-bold text-muted" for="select-all-{{ $group }}">
                                                    <span x-text="selected.length === total ? 'All' : 'Select All'"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            @foreach($groupPermissions as $permission)
                                                <div class="custom-control custom-switch mb-3">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->name }}" 
                                                           class="custom-control-input" 
                                                           id="perm-{{ $permission->id }}"
                                                           x-model="selected"
                                                           :value="{{ $permission->id }}">
                                                    <label class="custom-control-label text-dark text-capitalize w-100" 
                                                           for="perm-{{ $permission->id }}" 
                                                           style="cursor: pointer;">
                                                        {{ str_replace('.', ' ', $permission->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-primary" 
                                                     role="progressbar" 
                                                     :style="`width: ${(selected.length / total) * 100}%`" 
                                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
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
@endsection