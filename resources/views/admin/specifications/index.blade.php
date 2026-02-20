@extends('admin.layouts.app')

@section('title', 'Specifications')

@section('content')

<section class="content-header">
    <h1>Vehicle Specifications</h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Specifications</li>
    </ol>
</section>

<section class="content">
    {{-- ACTION BAR --}}
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa fa-plus"></i> Add New Specification
        </a>
        <div class="text-muted">
            <i class="fa fa-info-circle"></i> Total: <strong>{{ $groupedSpecs->sum(fn($group) => $group->count()) }}</strong> items
        </div>
    </div>

    {{-- GROUPED SPECIFICATIONS --}}
    @forelse($groupedSpecs as $key => $specGroup)
        @php
            // Robust explode handling
            $parts = explode('|', $key);
            $brand = $parts[0] ?? 'N/A';
            $model = $parts[1] ?? 'N/A';
            $variantName = $parts[2] ?? 'Standard';
            
            // Generate unique ID for collapse based on the first item in group
            $firstSpec = $specGroup->first();
            $collapseId = 'variant-group-' . ($firstSpec->id ?? loop->index);
        @endphp

        <div class="box box-solid box-default shadow-sm" style="border-radius: 4px; border-left: 3px solid #3c8dbc; margin-bottom: 15px;">
            <div class="box-header with-border" 
                 data-toggle="collapse" 
                 data-target="#{{ $collapseId }}" 
                 style="cursor:pointer; padding: 12px 15px;">
                
                <div class="row">
                    <div class="col-xs-9">
                        {{-- Small Eyebrow for Brand --}}
                        <span class="text-uppercase" style="font-size: 10px; font-weight: 700; color: #999; letter-spacing: 1.2px; display: block; margin-bottom: 2px;">
                            {{ $brand }}
                        </span>
                        {{-- Main Header --}}
                        <span style="font-size: 16px; font-weight: 600; color: #333;">
                            {{ $model }} 
                            @if($variantName)
                                <small style="color: #ccc; margin: 0 8px;">|</small> 
                                <span style="color: #3c8dbc;">{{ $variantName }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="col-xs-3 text-right">
    <div style="display: inline-flex; align-items: center; gap: 10px;">
        <span style="
            background-color: #ebf5ff; 
            color: #3c8dbc; 
            padding: 2px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 600;
            border: 1px solid #d1e9ff;">
            {{ $specGroup->count() }} {{ Str::plural('Spec', $specGroup->count()) }}
        </span>
        <i class="fa fa-chevron-down text-muted" style="font-size: 12px; transition: transform 0.3s;"></i>
    </div>
</div>
                </div>
            </div>

            <div id="{{ $collapseId }}" class="panel-collapse collapse in">
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #fcfcfc; color: #777; font-size: 12px;">
                                    <th style="padding-left: 15px; width: 15%;">Body Type</th>
                                    <th>Trans.</th>
                                    <th>Fuel</th>
                                    <th>Displacement</th>
                                    <th>Power / Torque</th>
                                    <th>Interior</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right" style="padding-right: 15px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 13px;">
                                @foreach($specGroup as $spec)
                                    <tr>
                                        <td style="padding-left: 15px;">
                                            <span class="text-bold">{{ $spec->bodyType->name ?? '-' }}</span>
                                        </td>
                                        <td><span class="label label-default" style="font-weight: normal;">{{ $spec->transmissionType->name ?? '-' }}</span></td>
                                        <td>{{ $spec->engineType->name ?? '-' }}</td>
                                        <td><code style="background: #f4f4f4; color: #555;">{{ $spec->engineDisplacement->name ?? '-' }}</code></td>
                                        <td>
                                            <span style="font-weight: 600;">{{ $spec->horsepower ?? '0' }} <small>HP</small></span>
                                            <div class="text-muted" style="font-size: 11px;">{{ $spec->torque ?? '0' }} Nm</div>
                                        </td>
                                        <td>
                                            <i class="fa fa-user-circle-o text-muted"></i> {{ $spec->seats }} 
                                            <span style="margin: 0 4px; color: #eee;">|</span> 
                                            <i class="fa fa-columns text-muted"></i> {{ $spec->doors }}
                                        </td>
                                        <td class="text-center">
                                            @if($spec->color)
                                                <div class="img-circle border shadow-sm" style="width: 18px; height: 18px; display: inline-block; background-color: {{ $spec->color }}; vertical-align: middle;" title="{{ $spec->color }}"></div>
                                            @else - @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="label {{ $spec->status ? 'label-success' : 'label-danger' }}" style="font-size: 9px; padding: 2px 5px;">
                                                {{ $spec->status ? 'ACTIVE' : 'INACTIVE' }}
                                            </span>
                                        </td>
                                        <td class="text-right" style="padding-right: 15px;">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-default btn-sm" title="Edit">
                                                    <i class="fa fa-edit text-blue"></i>
                                                </a>
                                                <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-default btn-sm" title="Delete" onclick="return confirm('Delete this record?')">
                                                        <i class="fa fa-trash text-red"></i>
                                                    </button>
                                                </form>
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
        <div class="text-center" style="padding: 100px 0; background: #fff; border: 1px dashed #ddd; border-radius: 8px;">
            <i class="fa fa-file-text-o fa-4x text-muted" style="margin-bottom: 15px;"></i>
            <h4 class="text-muted">No specifications recorded yet</h4>
            <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary mt-3">Add First Specification</a>
        </div>
    @endforelse
</section>

@endsection