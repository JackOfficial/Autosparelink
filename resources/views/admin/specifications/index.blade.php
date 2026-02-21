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

<section class="content" x-data="{ 
    search: '',
    {{-- Helper to check if a group has any visible children --}}
    checkGroup(el) {
        let rows = el.querySelectorAll('tbody tr');
        let visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        return visibleRows.length > 0;
    }
}">
    
    {{-- TOP ACTION BAR --}}
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus"></i> Add New Specification
            </a>
        </div>
        
        {{-- ADVANCED SEARCH BOX --}}
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-addon" style="background: #fff;"><i class="fa fa-search text-primary"></i></span>
                <input type="text" 
                       x-model="search" 
                       class="form-control" 
                       placeholder="Search by Model, Variant, Engine (e.g. 'RAV4 Hybrid')..."
                       style="height: 40px; border-left: none;">
                <span class="input-group-addon" style="background: #fff; cursor: pointer;" x-show="search" @click="search = ''">
                    <i class="fa fa-times text-red"></i>
                </span>
            </div>
        </div>
    </div>

    {{-- GROUPED SPECIFICATIONS --}}
    @forelse($groupedSpecs as $key => $specGroup)
        @php
            $parts = explode('|', $key);
            $brand = $parts[0] ?? '';
            $model = $parts[1] ?? '';
            $variantGroupName = $parts[2] ?? '';
            $collapseId = 'group-' . $loop->index;
            
            // Pre-calculate search string for the group header
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
                        <span class="spec-pill">{{ $specGroup->count() }} Items</span>
                        <i class="fa fa-chevron-down text-muted" style="margin-left:10px;"></i>
                    </div>
                </div>
            </div>

            <div id="{{ $collapseId }}" class="panel-collapse collapse in">
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background: #f9f9f9; font-size: 11px; text-transform: uppercase; color: #777;">
                                    <th style="padding-left: 15px;">Body</th>
                                    <th>Variant</th>
                                    <th>Trans.</th>
                                    <th>Engine</th>
                                    <th>Output</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right" style="padding-right: 15px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specGroup as $spec)
                                    @php
                                        // Pre-calculate searchable text for this row
                                        $rowSearch = strtolower($spec->variant->name . ' ' . $spec->bodyType->name . ' ' . $spec->engineType->name);
                                    @endphp
                                    <tr x-show="search === '' || '{{ $rowSearch }}'.includes(search.toLowerCase()) || '{{ $groupSearchTag }}'.includes(search.toLowerCase())">
                                      <td style="padding-left: 15px;"><b>{{ $spec->bodyType->name ?? 'N/A' }}</b></td>
                                        <td><span class="text-primary" style="font-weight:600">{{ $spec->variant->name ?? 'Standard' }}</span></td>
                                          <td><span class="label label-default">{{ $spec->transmissionType->name ?? 'Unknown' }}</span></td>
                                         <td>{{ $spec->engineType->name ?? 'N/A' }}</td>
                                        <td>
                                            <strong>{{ $spec->horsepower }} HP</strong><br>
                                            <small class="text-muted">{{ $spec->torque }} Nm</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="label {{ $spec->status ? 'label-success' : 'label-danger' }}">
                                                {{ $spec->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-right" style="padding-right: 15px;">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                                <button class="btn btn-default btn-sm text-red"><i class="fa fa-trash"></i></button>
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
        {{-- Empty State --}}
    @endforelse

    {{-- "No Results" State for Search --}}
    <div x-show="search !== '' && !document.querySelectorAll('.spec-group-wrapper[style*=\'display: block\']').length" 
         class="text-center text-muted" style="padding: 40px;">
        <i class="fa fa-search fa-3x" style="margin-bottom: 10px;"></i>
        <p>No specifications match your search "<strong><span x-text="search"></span></strong>"</p>
    </div>
</section>

<style>
    .shadow-sm { box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .spec-pill { background: #e7f3ff; color: #3c8dbc; padding: 3px 10px; border-radius: 12px; font-weight: 700; font-size: 11px; }
    .table > tbody > tr > td { vertical-align: middle; border-top: 1px solid #f4f4f4; }
    [data-toggle="collapse"] .fa-chevron-down { transition: 0.3s; transform: rotate(180deg); }
    [data-toggle="collapse"].collapsed .fa-chevron-down { transform: rotate(0deg); }
</style>
@endsection