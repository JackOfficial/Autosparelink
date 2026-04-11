@extends('layouts.dashboard')

@section('content')
    {{-- Pass the ID variable to the component --}}
    <livewire:tickets.show :id="$ticket->id" />
@endsection