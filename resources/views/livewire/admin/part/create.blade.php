<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data">

@if (session()->has('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row">

{{-- LEFT --}}
<div class="col-lg-6">
<fieldset>
<legend>General Info</legend>

<div class="row">

<div class="col-md-6 mb-3">
<label>Part Name *</label>
<input type="text" class="form-control @error('part_name') is-invalid @enderror"
       wire:model.defer="part_name">
@error('part_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6 mb-3">
<label>Parent Category</label>
<select class="form-control"
        wire:model="parentCategoryId">
<option value="">-- Select Parent --</option>
@foreach($parentCategories as $parent)
<option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
@endforeach
</select>
</div>

<div class="col-md-6 mb-3">
<label>Child Category *</label>
<select class="form-control @error('category_id') is-invalid @enderror"
        wire:model.defer="category_id">
<option value="">-- Select Child --</option>
@foreach($childCategories as $child)
<option value="{{ $child->id }}">{{ $child->category_name }}</option>
@endforeach
</select>
@error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-md-6 mb-3">
<label>Price *</label>
<input type="number" class="form-control @error('price') is-invalid @enderror"
       wire:model.defer="price">
@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

</div>
</fieldset>
</div>

{{-- RIGHT --}}
<div class="col-lg-6">
<fieldset>
<legend>Fitment & Media</legend>

<div class="mb-3" wire:ignore>
<label>Compatible Vehicles</label>
<select id="fitmentSelect" class="form-control select2" multiple>
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
                </option>
            @endforeach
        @endforeach
    @endif
@endforeach
</select>
@error('fitment_specifications') 
<small class="text-danger">{{ $message }}</small> 
@enderror
</div>

<div class="mb-3" x-data="{ previews: [] }" wire:ignore>
<label>Photos</label>
<input type="file" class="form-control" multiple
       wire:model="photos"
       @change="
           previews=[];
           [...$event.target.files].forEach(f=>{
               let r=new FileReader();
               r.onload=e=>previews.push(e.target.result);
               r.readAsDataURL(f);
           })
       ">
<div class="d-flex mt-2 gap-2">
    <template x-for="img in previews">
        <img :src="img" width="80" class="rounded">
    </template>
</div>
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