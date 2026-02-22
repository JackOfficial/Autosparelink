@extends('admin.layouts.app')

@section('title', 'Vehicle Specifications')

@section('content')
<div class="content-header px-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="font-weight-bold text-dark mb-1" style="letter-spacing: -0.5px;">Vehicle Specifications</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                    <li class="breadcrumb-item active">Specifications</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary btn-lg shadow-sm px-4" style="border-radius: 8px;">
            <i class="fa fa-plus mr-2"></i> Add Specification
        </a>
    </div>
</div>

<section class="content px-4" x-data="{ search: '' }">
    
    {{-- UNIFIED SEARCH BAR --}}
    <div class="mb-4">
        <div class="input-group shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-0 pl-4"><i class="fa fa-search text-muted"></i></span>
            </div>
            <input type="text" x-model="search" 
                   class="form-control border-0 py-4" 
                   placeholder="Type to filter by brand, model, chassis or trim..."
                   style="height: 50px; font-size: 15px;">
            <div class="input-group-append" x-show="search" @click="search = ''" style="cursor: pointer;">
                <span class="input-group-text bg-white border-0 pr-4"><i class="fa fa-times-circle text-danger"></i></span>
            </div>
        </div>
    </div>

    @forelse($groupedSpecs as $key => $specGroup)
        @php
            [$brand, $model, $variantGroupName] = explode('|', $key . '||');
            $collapseId = 'group-' . $loop->index;
            $groupSearchTag = strtolower("$brand $model $variantGroupName");
        @endphp

        <div class="card mb-4 border-0 shadow-sm" 
             x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
             style="border-radius: 12px; overflow: hidden;">
            
            {{-- GROUP HEADER --}}
            <div class="card-header bg-white py-3 border-bottom-0" 
                 data-toggle="collapse" data-target="#{{ $collapseId }}"
                 style="cursor: pointer; transition: background 0.2s;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="brand-avatar mr-3 text-uppercase shadow-xs">{{ substr($brand, 0, 1) }}</div>
                            <div>
                                <small class="text-muted font-weight-bold text-uppercase" style="letter-spacing: 1px; font-size: 10px;">{{ $brand }}</small>
                                <h4 class="mb-0 font-weight-bold" style="color: #2c3e50;">
                                    {{ $model }} 
                                    @if($variantGroupName)
                                        <span class="mx-2 text-light" style="font-weight: 300;">|</span>
                                        <span class="text-primary font-weight-normal">{{ $variantGroupName }}</span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <span class="badge badge-light border px-3 py-2 text-primary" style="border-radius: 20px;">
                            {{ $specGroup->count() }} Variations
                        </span>
                        <i class="fa fa-chevron-down ml-3 text-muted opacity-50 collapse-arrow"></i>
                    </div>
                </div>
            </div>

            {{-- EXPANDABLE TABLE --}}
            <div id="{{ $collapseId }}" class="collapse show">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="bg-lightest">
                                <tr class="small text-muted text-uppercase font-weight-bold">
                                    <th class="pl-4 border-0">Identity & Market</th>
                                    <th class="border-0">Technical Data</th>
                                    <th class="border-0 text-center">Lifecycle</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="pr-4 border-0 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specGroup as $spec)
                                    @php
                                        $v = $spec->variant;
                                        $rowSearch = strtolower(($v->name ?? '') . ' ' . $spec->chassis_code . ' ' . $spec->model_code);
                                    @endphp
                                    <tr class="spec-row" x-show="search === '' || '{{ $rowSearch }}'.includes(search.toLowerCase()) || '{{ $groupSearchTag }}'.includes(search.toLowerCase())">
                                        {{-- 1. Identity --}}
                                        <td class="pl-4 py-3">
                                            <div class="font-weight-bold text-dark">{{ $v->name ?? 'N/A' }}</div>
                                            <div class="mt-1">
                                                <span class="badge-code">{{ $spec->chassis_code ?: '---' }}</span>
                                                <span class="badge-code">{{ $spec->model_code ?: '---' }}</span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <i class="fa fa-map-marker-alt mr-1"></i> {{ $spec->destinations->pluck('region_name')->first() ?: 'Global' }}
                                            </div>
                                        </td>

                                        {{-- 2. Performance --}}
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="small font-weight-bold text-dark">{{ $spec->engineType->name ?? 'N/A' }}</span>
                                                <span class="small text-muted">{{ $spec->transmissionType->name ?? 'N/A' }} • {{ $spec->driveType->name ?? 'N/A' }}</span>
                                                <span class="mt-1 font-weight-bold text-warning small" style="letter-spacing: 0.5px;">
                                                    {{ $spec->horsepower ?? 0 }} HP / {{ $spec->torque ?? 0 }} NM
                                                </span>
                                            </div>
                                        </td>

                                        {{-- 3. Lifecycle --}}
                                        <td class="text-center">
                                            <div class="small font-weight-bold text-dark">{{ $spec->production_start }}</div>
                                            <div class="small text-muted">to</div>
                                            <div class="small font-weight-bold {{ $spec->production_end == 'Present' ? 'text-success' : 'text-dark' }}">
                                                {{ $spec->production_end ?? 'Present' }}
                                            </div>
                                        </td>

                                        {{-- 4. Status --}}
                                        <td class="text-center">
                                            @if($v?->is_default)
                                                <span class="badge badge-pill badge-soft-warning mb-1 d-block">Default</span>
                                            @endif
                                            <span class="dot-indicator {{ $spec->status ? 'bg-success' : 'bg-danger' }}"></span>
                                            <span class="small font-weight-bold {{ $spec->status ? 'text-success' : 'text-danger' }}">
                                                {{ $spec->status ? 'Active' : 'Draft' }}
                                            </span>
                                        </td>

                                        {{-- 5. Actions --}}
                                        <td class="pr-4 text-right">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-circle" data-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;">
                                                    <i class="fa fa-ellipsis-v text-muted"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg">
                                                    <a class="dropdown-item" href="{{ route('admin.specifications.edit', $spec->id) }}">
                                                        <i class="fa fa-edit text-primary mr-2"></i> Edit Specification
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item text-danger" onclick="confirm('Delete this spec?')">
                                                        <i class="fa fa-trash mr-2"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 bg-white shadow-sm rounded-lg">
            <i class="fa fa-car-crash fa-3x text-light mb-3"></i>
            <h4 class="text-muted">No specifications found</h4>
        </div>
    @endforelse
</section>

<style>
    /* Modern UI Tweaks */
    body { background-color: #f4f7f6; }
    .bg-lightest { background-color: #fbfbfc; }
    .brand-avatar { width: 40px; height: 40px; background: #3c8dbc; color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 800; font-size: 18px; }
    .badge-code { background: #f0f2f5; color: #475467; font-family: 'Monaco', monospace; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600; border: 1px solid #e2e8f0; }
    .spec-row:hover { background-color: #f8faff !important; }
    .spec-row { transition: all 0.2s ease; }
    .collapse-arrow { transition: transform 0.3s; }
    [aria-expanded="true"] .collapse-arrow { transform: rotate(180deg); }
    
    /* Dot Status Indicator */
    .dot-indicator { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 4px; }
    .badge-soft-warning { background-color: #fff8e1; color: #f57c00; font-size: 9px; }
    
    /* Remove default AdminLTE styling for cards */
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
</style>
@endsection