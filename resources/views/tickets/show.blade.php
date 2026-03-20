@extends('layouts.app')

@section('content')
    {{-- Pass the ID variable to the component --}}
    <livewire:tickets.show :id="$id" />
@endsection