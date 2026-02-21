<div class="container-fluid py-4">
    <style>
        .z-top { z-index: 1060 !important; }
        .search-results { top: 100%; left: 0; right: 0; background: white; border: 1px solid #dee2e6; }
        .cursor-pointer { cursor: pointer; }
    </style>

    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Part Info --}}
            <div class="col-md-8">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header"><h3 class="card-title">Part Details</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Part Name *</label>
                                <input type="text" wire:model="part_name" class="form-control @error('part_name') is-invalid @enderror">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Brand *</label>
                                <select wire:model="part_brand_id" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $b) <option value="{{$b->id}}">{{$b->name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Category *</label>
                                <select wire:model="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $c) <option value="{{$c->id}}">{{$c->category_name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Price (RWF) *</label>
                                <input type="number" wire:model="price" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Stock *</label>
                                <input type="number" wire:model="stock_quantity" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Compatibility --}}
            <div class="col-md-4">
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header"><h3 class="card-title text-sm">Vehicle Compatibility</h3></div>
                    <div class="card-body">
                        <div class="position-relative">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchVehicle" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search Brand or Model...">
                            
                            @if(count($searchResults) > 0)
                                <div class="list-group position-absolute shadow-lg z-top search-results">
                                    @foreach($searchResults as $spec)
                                        <button type="button" 
                                                wire:click="toggleVehicle({{$spec->id}})" 
                                                class="list-group-item list-group-item-action py-2 text-xs">
                                            <i class="fas {{ in_array($spec->id, $selectedSpecs) ? 'fa-check-circle text-success' : 'fa-plus-circle' }} mr-2"></i>
                                            {{ $spec->vehicleModel->brand->brand_name }} {{ $spec->vehicleModel->model_name }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 bg-light p-2 rounded" style="min-height: 100px;">
                            <label class="text-xs font-weight-bold">Selected ({{ count($selectedSpecs) }})</label>
                            <div class="d-flex flex-wrap">
                                @forelse($displaySpecs as $s)
                                    <span class="badge badge-info m-1">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ml-1 cursor-pointer" wire:click="toggleVehicle({{$s->id}})"></i>
                                    </span>
                                @empty
                                    <p class="text-muted text-xs italic">No vehicles added.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary px-5 shadow">Save Part</button>
        </div>
    </form>
</div>