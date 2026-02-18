@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Address</h2>

    <form method="POST"
          action="{{ route('addresses.update', $address->id) }}">
        @csrf
        @method('PUT')

        @include('addresses.form')

        <button class="btn btn-primary">Update Address</button>
    </form>
</div>
@endsection