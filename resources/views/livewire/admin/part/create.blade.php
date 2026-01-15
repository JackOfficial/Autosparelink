<div>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" enctype="multipart/form-data">

        <div class="row">

            {{-- LEFT COLUMN --}}
            <div class="col-lg-6">
                <fieldset>
                    <legend><i class="fas fa-info-circle"></i> General Info</legend>

                    <div class="row">
                        {{-- Part Number --}}
                        <div class="col-md-6 mb-3">
                            <label>Part Number</label>
                            <input type="text" class="form-control" wire:model.defer="part_number">
                            @error('part_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Part Name --}}
                        <div class="col-md-6 mb-3">
                            <label>Part Name *</label>
                            <input type="text" class="form-control" wire:model.defer="part_name">
                            @error('part_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Parent Category --}}
                        <div class="col-md-6 mb-3">
                            <label>Parent Category</label>
                            <select class="form-control" wire:model.live="parentCategoryId">
                                <option value="">-- Select Parent --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                @endforeach
                            </select>
                            @error('parentCategoryId') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Child Category --}}
                        <div class="col-md-6 mb-3">
                            <label>Child Category *</label>
                            <select class="form-control" wire:model.live="category_id">
                                <option value="">-- Select Child --</option>
                                @foreach($childCategories as $child)
                                    <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Part Brand --}}
                        <div class="col-md-6 mb-3">
                            <label>Part Brand *</label>
                            <select class="form-control" wire:model.live="part_brand_id">
                                <option value="">-- Select Brand --</option>
                                @foreach($partBrands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
                                @endforeach
                            </select>
                            @error('part_brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- OEM Number --}}
                        <div class="col-md-6 mb-3">
                            <label>OEM Number</label>
                            <input type="text" class="form-control" wire:model.defer="oem_number">
                            @error('oem_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6 mb-3">
                            <label>Price (RWF)</label>
                            <input type="number" class="form-control" wire:model.defer="price">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="col-md-3 mb-3">
                            <label>Stock Quantity</label>
                            <input type="number" class="form-control" wire:model.defer="stock_quantity">
                            @error('stock_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select class="form-control" wire:model.defer="status">
                                <option value="Inactive">Inactive</option>
                                <option value="Active">Active</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </fieldset>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-6">
                <fieldset>
                    <legend><i class="fas fa-car-side"></i> Fitment & Media</legend>

                    {{-- Fitments --}}
<div class="mb-3">
    <label>Compatible Vehicles</label>

    <select wire:model.defer='fitment_specifications' class="form-control" multiple>
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
    @error('fitment_specifications')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


                    {{-- Description --}}
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" wire:model.defer="description"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Photos --}}
                    <div class="mb-3" wire:ignore x-data="{ previews: [] }">
                        <label>Photos</label>
                        <input type="file" multiple wire:model="photos" class="form-control"
                               @change="
                                    previews = [];
                                    [...$event.target.files].forEach(file => {
                                        let reader = new FileReader();
                                        reader.onload = e => previews.push(e.target.result);
                                        reader.readAsDataURL(file);
                                    });
                               "
                        >
                        <div class="d-flex mt-2 gap-2">
                            <template x-for="img in previews" :key="img">
                                <img :src="img" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                            </template>
                        </div>
                        @error('photos.*') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </fieldset>
            </div>

        </div>

        {{-- Submit --}}
        <div class="text-end mt-3">
            <button class="btn btn-success"><i class="fas fa-save"></i> Save Part</button>
        </div>

    </form>

</div>
