<div>
    <style>
        .search-dropdown {
            position: absolute;
            z-index: 2000;
            width: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-height: 250px;
            overflow-y: auto;
        }
        .cursor-pointer { cursor: pointer; }
    </style>

    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Form Fields Side --}}
            <div class="col-md-7">
                <div class="card card-outline card-info shadow-none border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label>Part Name</label>
                                <input type="text" class="form-control" wire:model="part_name">
                                @error('part_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-6 form-group">
                                <label>Part Number</label>
                                <input type="text" class="form-control" wire:model="part_number">
                            </div>
                            <div class="col-6 form-group">
                                <label>Category</label>
                                <select class="form-control" wire:model.live="parentCategoryId">
                                    <option value="">Select Parent</option>
                                    @foreach($parentCategories as $c) <option value="{{$c->id}}">{{$c->category_name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label>Sub-Category</label>
                                <select class="form-control" wire:model.live="category_id">
                                    <option value="">Select Child</option>
                                    @foreach($childCategories as $c) <option value="{{$c->id}}">{{$c->category_name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label>Brand</label>
                                <select class="form-control" wire:model="part_brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($partBrands as $b) <option value="{{$b->id}}">{{$b->name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label>Price</label>
                                <input type="number" class="form-control" wire:model="price">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehicle Compatibility Side --}}
            <div class="col-md-5">
                <div class="card card-primary card-outline shadow-none border">
                    <div class="card-header"><h3 class="card-title text-sm">Vehicle Compatibility</h3></div>
                    <div class="card-body">
                        
                        <div class="position-relative">
                            <input type="text" class="form-control form-control-sm" 
                                   placeholder="Type vehicle brand or model..."
                                   wire:model.live.debounce.250ms="searchVehicle">
                            
                            @if(count($searchResults) > 0)
                                <div class="search-dropdown list-group">
                                    @foreach($searchResults as $spec)
                                        <button type="button" class="list-group-item list-group-item-action py-2 text-xs"
                                                wire:click="toggleFitment({{ $spec->id }})">
                                            <i class="fas {{ in_array($spec->id, $fitment_specifications) ? 'fa-check-circle text-success' : 'fa-plus text-muted' }} mr-2"></i>
                                            {{ $spec->vehicleModel->brand->brand_name }} {{ $spec->vehicleModel->model_name }}
                                            <span class="text-muted">({{ $spec->variant->name ?? 'Standard' }})</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 p-2 bg-light border rounded" style="min-height: 100px;">
                            <label class="text-xs font-weight-bold">Selected ({{ count($fitment_specifications) }})</label>
                            <div class="d-flex flex-wrap">
                                @forelse($selectedSpecs as $s)
                                    <span class="badge badge-info m-1 p-2">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ml-1 cursor-pointer" wire:click="toggleFitment({{ $s->id }})"></i>
                                    </span>
                                @empty
                                    <div class="text-muted text-xs p-2">Search to add vehicles...</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary shadow">Save Spare Part</button>
        </div>
    </form>
</div>