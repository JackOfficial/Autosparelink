@extends('admin.layouts.app')

@section('title', 'Specifications')

@section('content')
<section class="container-fluid">
    <h1>Vehicle Specifications</h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Specifications</li>
    </ol>
</section>

<section class="container-fluid" x-data="{ search: '' }">
    
    {{-- TOP ACTION BAR --}}
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus"></i> Add New Specification
            </a>
        </div>
        
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-addon" style="background: #fff;"><i class="fa fa-search text-primary"></i></span>
                <input type="text" 
                       x-model="search" 
                       class="form-control" 
                       placeholder="Search Model, Chassis, or Trim..."
                       style="height: 40px; border-left: none;">
                <span class="input-group-addon" style="background: #fff; cursor: pointer;" x-show="search" @click="search = ''">
                    <i class="fa fa-times text-red"></i>
                </span>
            </div>
        </div>
    </div>

    @forelse($groupedSpecs as $key => $specGroup)
        @php
            $parts = explode('|', $key);
            $brand = $parts[0] ?? '';
            $model = $parts[1] ?? '';
            $variantGroupName = $parts[2] ?? '';
            $collapseId = 'group-' . $loop->index;
            // Tag for Alpine search filtering
            $groupSearchTag = strtolower("$brand $model $variantGroupName");
        @endphp

        <div class="box box-solid box-default shadow-sm spec-group-wrapper" 
             x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())"
             style="border-radius: 4px; border-left: 3px solid #3c8dbc; margin-bottom: 20px;">
            
            <div class="box-header with-border" 
                 data-toggle="collapse" 
                 data-target="#{{ $collapseId }}" 
                 style="cursor:pointer; padding: 12px 15px;">
                <div class="row">
                    <div class="col-xs-9">
                        <span class="text-uppercase" style="font-size: 10px; font-weight: 700; color: #999; letter-spacing: 1.2px; display: block;">
                            {{ $brand }}
                        </span>
                        <span style="font-size: 16px; font-weight: 600;">
                            {{ $model }} 
                            @if($variantGroupName)
                                <small style="color: #ccc; margin: 0 8px;">|</small> 
                                <span class="text-primary">{{ $variantGroupName }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="col-xs-3 text-right">
                        <span class="spec-pill">{{ $specGroup->count() }} Variations</span>
                        <i class="fa fa-chevron-down text-muted" style="margin-left:10px;"></i>
                    </div>
                </div>
            </div>

            <div id="{{ $collapseId }}" class="panel-collapse collapse in">
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #fcfcfc; font-size: 11px; text-transform: uppercase; color: #777;">
                                    <th style="padding-left: 15px; width: 250px;">Codes & Market</th>
                                    <th>Technical Specs</th>
                                    <th>Configuration</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right" style="padding-right: 15px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specGroup as $spec)
                                    @php
                                        $v = $spec->variant;
                                        // CORRECTED: Pull codes from $spec, not $v
                                        $rowSearch = strtolower(($v->name ?? '') . ' ' . ($spec->chassis_code ?? '') . ' ' . ($spec->model_code ?? ''));
                                    @endphp
                                    <tr x-show="search === '' || '{{ $rowSearch }}'.includes(search.toLowerCase()) || '{{ $groupSearchTag }}'.includes(search.toLowerCase())">
                                        {{-- 1. IDENTITY & MARKET --}}
                                        <td style="padding-left: 15px;">
                                            {{-- The Variant Name is already auto-generated by your model --}}
                                            <div style="font-weight: 700; color: #333; font-size: 13px;">{{ $v->name ?? 'N/A' }}</div>
                                            <div style="margin-top: 4px;">
                                                {{-- UPDATED: Accessing codes via $spec --}}
                                                <span class="badge-code" title="Chassis Code">{{ $spec->chassis_code ?: '---' }}</span>
                                                <span class="badge-code" title="Model Code">{{ $spec->model_code ?: '---' }}</span>
                                            </div>
                                            <div style="font-size: 11px; margin-top: 5px; color: #666;">
                                                <i class="fa fa-globe text-muted"></i> 
                                                {{ $spec->destinations->pluck('region_name')->join(', ') ?: 'Global' }}
                                            </div>
                                        </td>

                                        {{-- 2. TECHNICAL SPECS --}}
                                        <td>
                                            <div style="display: flex; gap: 15px;">
                                                <div>
                                                    <small class="text-muted block text-uppercase" style="font-size: 9px;">Engine / Trans</small>
                                                    <div style="font-size: 12px;">
                                                        <strong>{{ $spec->engineType->name ?? 'N/A' }}</strong> 
                                                        <span class="text-muted">({{ $spec->transmissionType->name ?? 'N/A' }})</span>
                                                    </div>
                                                    <div style="font-size: 11px; color: #555;">
                                                        <i class="fa fa-bolt text-warning"></i> {{ $spec->horsepower ?? 0 }} HP / {{ $spec->torque ?? 0 }} Nm
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- 3. CONFIGURATION --}}
                                        <td>
                                            <div style="font-size: 11px; line-height: 1.6;">
                                                <div><i class="fa fa-car text-muted" style="width: 14px;"></i> {{ $spec->bodyType->name ?? 'N/A' }} ({{ $spec->driveType->name ?? 'N/A' }})</div>
                                                <div><i class="fa fa-users text-muted" style="width: 14px;"></i> {{ $spec->seats }} Seats | {{ $spec->doors }} Doors</div>
                                                <div class="text-primary" style="font-weight: 600;">
                                                    <i class="fa fa-calendar-o" style="width: 14px;"></i> 
                                                    {{ $spec->production_start }} - {{ $spec->production_end ?? 'Present' }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- 4. STATUS --}}
                                        <td class="text-center">
                                            @if($v?->is_default)
                                                <span class="label label-warning" style="display:block; margin-bottom: 5px; font-size: 9px;">DEFAULT</span>
                                            @endif
                                            <span class="label {{ $spec->status ? 'label-success' : 'label-danger' }}">
                                                {{ $spec->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>

                                        {{-- 5. ACTIONS --}}
                                        <td class="text-right" style="padding-right: 15px;">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-default btn-sm shadow-xs">
                                                    <i class="fa fa-pencil text-blue"></i>
                                                </a>
                                                <button type="button" class="btn btn-default btn-sm shadow-xs" 
                                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                                                    <i class="fa fa-trash text-red"></i>
                                                </button>
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
        <div class="box box-solid text-center" style="padding: 40px;">
            <p class="text-muted">No specifications found in the database.</p>
        </div>
    @endforelse
</section>

<style>
    .shadow-sm { box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .spec-pill { background: #e7f3ff; color: #3c8dbc; padding: 3px 10px; border-radius: 12px; font-weight: 700; font-size: 11px; }
    .badge-code { background: #f8f9fa; border: 1px solid #ddd; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; font-size: 10px; color: #444; margin-right: 3px; font-weight: bold; }
    .table > tbody > tr > td { vertical-align: middle; border-top: 1px solid #f4f4f4; padding: 12px 8px; }
    .block { display: block; }
    [data-toggle="collapse"] .fa-chevron-down { transition: 0.3s; transform: rotate(180deg); }
    [data-toggle="collapse"].collapsed .fa-chevron-down { transform: rotate(0deg); }
    .table-hover tbody tr:hover { background-color: #f9fbff !important; }
</style>
@endsection