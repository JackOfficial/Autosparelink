@extends('admin.layouts.app')
@section('title', 'Add Specification')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Specification</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Specification</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-11">
    <livewire:admin.specification-form 
    :vehicle_model_id="request()->query('vehicle_model_id')" 
    :variant_id="request()->query('variant_id')" 
/>

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
