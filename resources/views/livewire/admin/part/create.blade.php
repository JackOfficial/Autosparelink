<div>
<form wire:submit.prevent="save" enctype="multipart/form-data">

<div class="row">

{{-- LEFT --}}
<div class="col-lg-6">
<fieldset>
<legend><i class="fas fa-info-circle"></i> General Info</legend>

<div class="row">

<div class="col-md-6 mb-3">
<label>Part Number</label>
<input type="text" class="form-control" wire:model.defer="part_number">
</div>

<div class="col-md-6 mb-3">
<label>Part Name *</label>
<input type="text" class="form-control" wire:model.defer="part_name">
</div>

<div class="col-md-6 mb-3">
<label>Parent Category</label>
<select class="form-control" wire:model="parentCategoryId">
<option value="">-- Select Parent --</option>
@foreach($parentCategories as $parent)
<option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
@endforeach
</select>
</div>

<div class="col-md-6 mb-3">
<label>Child Category *</label>
<select class="form-control" wire:model.defer="category_id">
<option value="">-- Select Child --</option>
@foreach($childCategories as $child)
<option value="{{ $child->id }}">{{ $child->category_name }}</option>
@endforeach
</select>
</div>

<div class="col-md-6 mb-3">
<label>Part Brand *</label>
<select class="form-control" wire:model.defer="part_brand_id">
<option value="">-- Select Brand --</option>
@foreach($partBrands as $brand)
<option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
@endforeach
</select>
</div>

<div class="col-md-6 mb-3">
<label>OEM Number</label>
<input type="text" class="form-control" wire:model.defer="oem_number">
</div>

<div class="col-md-6 mb-3">
<label>Price (RWF)</label>
<input type="number" class="form-control" wire:model.defer="price">
</div>

<div class="col-md-6 mb-3">
<label>Stock Quantity</label>
<input type="number" class="form-control" wire:model.defer="stock_quantity">
</div>

</div>
</fieldset>
</div>

{{-- RIGHT --}}
<div class="col-lg-6">
<fieldset>
<legend><i class="fas fa-car-side"></i> Fitment & Media</legend>

<div class="mb-3">
<label>Compatible Vehicles</label>
<select wire:model.defer="fitment_specifications" class="form-control" multiple>
@foreach($vehicleModels as $model)
@if($model->variants->isEmpty())
@foreach($model->specifications as $spec)
<option value="{{ $spec->id }}">
{{ optional($model->brand)->brand_name }} /
{{ $model->model_name }}
({{ $spec->production_start }}–{{ $spec->production_end }})
</option>
@endforeach
@else
@foreach($model->variants as $variant)
@foreach($variant->specifications as $spec)
<option value="{{ $spec->id }}">
{{ optional($model->brand)->brand_name }} /
{{ $model->model_name }} — {{ $variant->name }}
({{ $spec->production_start }}–{{ $spec->production_end }})
</option>
@endforeach
@endforeach
@endif
@endforeach
</select>
</div>

<div class="mb-3">
<label>Description</label>
<textarea class="form-control" wire:model.defer="description"></textarea>
</div>

<div class="mb-3">
<label>Photos</label>
<input type="file" wire:model="photos" multiple class="form-control">
</div>

</fieldset>
</div>

</div>

<div class="text-end mt-3">
<button class="btn btn-success">
<i class="fas fa-save"></i> Save Part
</button>
</div>

</form>
</div>
