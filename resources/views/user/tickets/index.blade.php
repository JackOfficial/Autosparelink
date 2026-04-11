@extends('layouts.dashboard')

@section('content')

<livewire:tickets.index />

<style>
    .rounded-xl { border-radius: 1rem !important; }
    .table-hover tbody tr:hover { background-color: #fcfcfc; }
    .badge-success { background-color: #28a745; }
    .badge-warning { background-color: #ffc107; color: #333; }
    .badge-secondary { background-color: #6c757d; }
    .btn-primary { background-color: #007bff; border: none; }
</style>
@endsection