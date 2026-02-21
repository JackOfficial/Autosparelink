<div style="position: relative;"> {{-- ROOT --}}
    
    <style>
        /* This ensures the right column doesn't slide under the left one invisibly */
        .row::after { content: ""; clear: both; display: table; }
        .clickable-area { position: relative; z-index: 10; }
        .form-check-input, .form-check-label { cursor: pointer !important; }
        /* Highlighting the checkbox to see if it's even there */
        .form-check-input:hover { outline: 2px solid #007bff; }
    </style>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="clickable-area">
        <div class="row">
            {{-- LEFT SIDE --}}
            <div class="col-md-7">
                <div class="card card-outline card-info">
                    <div class="card-header"><h3 class="card-title">General Details</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Part Name *</label>
                            <input type="text" class="form-control" wire:model="part_name">
                            @error('part_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Parent Category</label>
                                <select class="form-control" wire:model.live="parentCategoryId">
                                    <option value="">-- Select --</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label>Child Category *</label>
                                <select class="form-control" wire:model.live="category_id">
                                    <option value="">-- Select --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label>Price</label>
                            <input type="number" class="form-control" wire:model="price">
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE (The Problem Area) --}}
            <div class="col-md-5">
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Vehicle Compatibility</h3></div>
                    {{-- Added z-index and relative position here --}}
                    <div class="card-body p-0" style="height: 400px; overflow-y: scroll; position: relative; z-index: 9999; background: white;">
                        <ul class="list-group list-group-flush">
                            @foreach($vehicleModels as $model)
                                <li class="list-group-item bg-dark py-1" style="font-size: 0.7rem;">
                                    {{ strtoupper($model->brand->brand_name ?? 'Brand') }} - {{ $model->model_name }}
                                </li>
                                @foreach($model->specifications as $spec)
                                    <li class="list-group-item py-2" wire:key="spec-{{ $spec->id }}">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   id="spec_check_{{ $spec->id }}" 
                                                   value="{{ $spec->id }}"
                                                   wire:model.live="fitment_specifications">
                                            <label class="form-check-label ml-2" for="spec_check_{{ $spec->id }}">
                                                {{ $spec->variant->name }} ({{ $spec->production_start }})
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success btn-block">SAVE PART</button>
        </div>
    </form>
</div>