@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Address</h2>

    <form method="POST" action="{{ route('admin.addresses.store') }}">
        @csrf

        @include('admin.addresses.form')

        <button class="btn btn-success">Save Address</button>
    </form>
</div>
@endsection