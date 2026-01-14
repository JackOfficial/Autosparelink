@extends('admin.layouts.app')
@section('title', 'Add Spare Part')

@section('styles')
<style>
    fieldset { 
        border: 1px solid #ddd; 
        padding: 15px; 
        margin-bottom: 20px; 
        border-radius: 5px;
    }
    legend { 
        width: auto; 
        padding: 0 10px; 
        font-weight: bold; 
        font-size: 1.1rem;
        color: #333;
    }
    .form-label { font-weight: 500; }
    .select2-container--default .select2-selection--multiple {
        min-height: 50px;
        display: flex;
        flex-wrap: wrap;
        padding: 4px 8px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding: 4px 10px 4px 28px !important;
        margin-right: 5px !important;
        position: relative;
        border-radius: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        position: absolute;
        left: 6px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: rgb(240, 83, 83);
        cursor: pointer;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: darkred;
    }
    .action-bar { 
        position: sticky; 
        bottom: 0; 
        background: #fff; 
        padding: 10px 0; 
        z-index: 10; 
        border-top: 1px solid #ddd; 
    }
</style>
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-cogs"></i> Add Spare Part</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.spare-parts.index') }}">Spare Parts</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="card card-primary">
<div class="card-header">
    <h3 class="card-title">Part</h3>
</div>

<div class="card-body">
 <livewire:admin.part.create />
</div>
</div>
</section>

@push('scripts')
    <script>
document.addEventListener('livewire:load', function () {
  alert('Hello world');
    const select = $('#fitmentSelect');

    select.select2({
        placeholder: "Select compatible vehicles",
        width: '100%'
    });

    select.on('change', function () {
        Livewire.emit('fitmentsUpdated', $(this).val());
    });

});
</script>
@endpush

@endsection
