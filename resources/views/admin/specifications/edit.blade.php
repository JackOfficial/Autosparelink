@extends('admin.layouts.app')
@section('title', 'Edit Specification')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Specification</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Edit Specification</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-11">
  
</div>
</div>
</section>

@push('scripts')
<script>
$(function () {
    $('.my-colorpicker2').colorpicker()
})
</script>
@endpush

@endsection
