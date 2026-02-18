@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Address</h2>

    <form method="POST"
          action="{{ route('admin.addresses.update', $address->id) }}">
        @csrf
        @method('PUT')

        @include('admin.addresses.form')

        <button class="btn btn-primary">Update Address</button>
    </form>
</div>
@endsection