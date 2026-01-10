@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Add Vehicle Model')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Model</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Model</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-10">

<livewire:admin.model-form />

</div>
</div>
</section>

@push('scripts')
{{-- <script>
$(function () {
    $('.my-colorpicker2').colorpicker()
})
</script> --}}

<script>
$(function () {
    $('#colorInput').colorpicker().on('colorpickerChange', function(e) {
        @this.set('spec.color', e.color.toString());
    });
});
</script>
@endpush

@endsection
