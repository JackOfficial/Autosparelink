@extends('layouts.app')

@section('style')
<style>
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .filter-card .form-control, .filter-card .btn { height: calc(2.2rem + 2px); font-size: 0.9rem; }
    .brand-logo-header { width: 50px; height: auto; margin-right: 15px; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .tech-label { font-size: 0.75rem; text-transform: uppercase; color: #86868b; font-weight: 600; }
    .tech-value { font-size: 0.9rem; color: #1d1d1f; }
</style>
@endsection

@section('content')

{{-- Pass the variant from the controller to the Livewire component --}}
<livewire:specifications.index :variant="$variant" />

@endsection