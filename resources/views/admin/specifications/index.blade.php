@extends('admin.layouts.app')

@section('title', 'Specifications')

@section('content')

<section class="content-header">
    <h1>Specifications</h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Specifications</li>
    </ol>
</section>

<section class="content">
    {{-- ACTION BAR --}}
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Specification
        </a>
        <span class="text-muted">
            Total: <strong>{{ $groupedSpecs->sum(fn($group) => $group->count()) }}</strong> specifications
        </span>
    </div>

    {{-- GROUPED SPECIFICATIONS --}}
    @forelse($groupedSpecs as $key => $specGroup)
        @php
            [$brand, $model, $variantName] = explode('|', $key);
            $collapseId = 'variant-group-' . $specGroup->first()->id;
        @endphp

        <div class="box box-primary direct-chat"> {{-- Using 'box' instead of 'card' for AdminLTE 2 --}}
            <div class="box-header with-border" 
     data-toggle="collapse" 
     data-target="#{{ $collapseId }}" 
     style="cursor:pointer; padding: 10px 15px;">
    <div class="row">
        <div class="col-xs-8">
            <span class="text-uppercase text-muted" style="font-size: 10px; letter-spacing: 1px; display: block; margin-bottom: 2px;">
                {{ $brand }}
            </span>
            <span style="font-size: 16px; font-weight: 600; color: #333;">
                {{ $model }} 
                <small style="color: #999; margin: 0 5px;">|</small> 
                <span class="text-primary">{{ $variantName }}</span>
            </span>
        </div>
        <div class="col-xs-4 text-right">
            <span class="label label-default" style="font-weight: 500; padding: 5px 10px;">
                {{ $specGroup->count() }} Variations
            </span>
            <i class="fa fa-chevron-down text-muted" style="margin-left: 10px; font-size: 12px;"></i>
        </div>
    </div>
</div>

            <div id="{{ $collapseId }}" class="panel-collapse collapse in"> {{-- Bootstrap 3 collapse --}}
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    <th style="padding-left: 15px;">Body</th>
                                    <th>Trans.</th>
                                    <th>Fuel</th>
                                    <th>Eng.</th>
                                    <th>Power/Torque</th>
                                    <th>Interior</th>
                                    <th>Color</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right" style="padding-right: 15px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specGroup as $spec)
                                    <tr>
                                        <td style="padding-left: 15px;"><strong>{{ $spec->bodyType->name ?? '-' }}</strong></td>
                                        <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                                        <td>{{ $spec->engineType->name ?? '-' }}</td>
                                        <td>{{ $spec->engineDisplacement->name ?? '-' }}</td>
                                        <td>
                                            <span style="display: block; font-size: 0.9em;">{{ $spec->horsepower ?? '0' }} HP</span>
                                            <small class="text-muted">{{ $spec->torque ?? '0' }} Nm</small>
                                        </td>
                                        <td>
                                            <small title="Seats/Doors"><i class="fa fa-user"></i> {{ $spec->seats }} | <i class="fa fa-columns"></i> {{ $spec->doors }}</small>
                                        </td>
                                        <td>
                                            @if($spec->color)
                                                <div style="display: flex; align-items: center;">
                                                    <div class="img-circle" style="width: 15px; height: 15px; border: 1px solid #ddd; margin-right: 5px; background-color: {{ $spec->color }}"></div>
                                                    <small class="text-muted">{{ $spec->color }}</small>
                                                </div>
                                            @else - @endif
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-circle {{ $spec->status ? 'text-success' : 'text-danger' }}" style="font-size: 10px;"></i>
                                        </td>
                                        <td class="text-right" style="padding-right: 15px;">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-edit text-yellow"></i></a>
                                                
                                                <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-default" title="Delete" onclick="return confirm('Delete this specification?')">
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
        <div class="well text-center" style="margin-top: 20px;">
            <i class="fa fa-folder-open fa-3x text-muted" style="margin-bottom: 10px; display: block;"></i>
            <p class="text-muted">No specifications found matching your criteria.</p>
        </div>
    @endforelse
</section>

@endsection