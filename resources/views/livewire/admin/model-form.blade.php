<div>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-car"></i> Model Information</h3>
                    </div>

                    <div class="card-body">
                        <form wire:submit.prevent="save">

                            {{-- ================= VEHICLE MODEL ================= --}}
                            <fieldset class="border p-3 mb-4 rounded bg-light">
                                <legend class="w-auto fw-bold text-primary"><i class="fas fa-car-side"></i> Vehicle Model</legend>

                                {{-- Brand + Model on same row --}}
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-industry"></i> Brand *</label>
                                            <select wire:model.live="brand_id" class="form-control">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-key"></i> Model Name *</label>
                                            <input type="text" wire:model.live="model_name" class="form-control" placeholder="Enter model name">
                                            @error('model_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Photos --}}
                                <div x-data="{ photos: [] }" class="form-group">
                                        <label><i class="fas fa-upload"></i> Upload Photos</label>
                                        <input type="file" multiple
                                               x-on:change="
                                                   photos = Array.from($event.target.files).map(file => URL.createObjectURL(file));
                                                   @this.uploadMultiple('photos', $event.target.files);
                                               "
                                               class="form-control"
                                        >

                                        <div class="row mt-3" x-show="photos.length > 0">
                                            <template x-for="(photo, index) in photos" :key="index">
                                                <div class="col-sm-6 col-md-3 mb-3">
                                                    <div class="card shadow-sm position-relative">
                                                        <img :src="photo" class="card-img-top rounded" style="height:150px; object-fit:cover;">
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle"
                                                                x-on:click="
                                                                    photos.splice(index, 1);
                                                                    @this.removeUpload('photos', index);
                                                                "
                                                                title="Remove">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">You can upload multiple images. Preview appears immediately.</small>
                               
                                    {{-- Has Variants --}}
                                <div class="form-group mt-2">
                                    <label><i class="fas fa-layer-group"></i> Does this model have variants?</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" wire:model.live="has_variants" value="1" class="form-check-input" id="has_variants_yes">
                                        <label class="form-check-label" for="has_variants_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" wire:model.live="has_variants" value="0" class="form-check-input" id="has_variants_no">
                                        <label class="form-check-label" for="has_variants_no">No</label>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="form-group mt-3">
                                    <label><i class="fas fa-align-left"></i> Description</label>
                                    <textarea wire:model.live="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
                                </div>

                                {{-- Production Years --}}
                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-calendar-alt"></i> Production Start Year</label>
                                            <input type="number" wire:model.live="production_start_year" class="form-control" min="1900" max="{{ date('Y') }}" placeholder="e.g. 2018">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-calendar-check"></i> Production End Year</label>
                                            <input type="number" wire:model.live="production_end_year" class="form-control" min="1900" max="{{ date('Y')+2 }}" placeholder="e.g. 2023">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{-- ================= SPECIFICATIONS ================= --}}
                            @if($has_variants == 0)
                                <fieldset class="border p-3 mb-4 rounded bg-light">
                                    <legend class="w-auto text-primary fw-bold"><i class="fas fa-cogs"></i> Specifications</legend>

                                    {{-- Core Specs --}}
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-car-side"></i> Body Type *</label>
                                            <select wire:model.live="spec.body_type_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($bodyTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-cogs"></i> Engine Type *</label>
                                            <select wire:model.live="spec.engine_type_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($engineTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-exchange-alt"></i> Transmission *</label>
                                            <select wire:model.live="spec.transmission_type_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($transmissionTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-road"></i> Drive Type</label>
                                            <select wire:model.live="spec.drive_type_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($driveTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Performance --}}
                                    <div class="row g-3 mt-3">
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-tachometer-alt"></i> Horsepower (HP)</label>
                                            <input type="number" wire:model.live="spec.horsepower" class="form-control" min="0" placeholder="e.g. 150">
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-torque"></i> Torque (Nm)</label>
                                            <input type="number" wire:model.live="spec.torque" class="form-control" min="0" placeholder="e.g. 320">
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-gas-pump"></i> Fuel Capacity (L)</label>
                                            <input type="number" step="0.1" wire:model.live="spec.fuel_capacity" class="form-control" placeholder="e.g. 55">
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <label><i class="fas fa-road"></i> Fuel Efficiency (km/L)</label>
                                            <input type="number" step="0.1" wire:model.live="spec.fuel_efficiency" class="form-control" placeholder="e.g. 14.5">
                                        </div>
                                    </div>

                                    {{-- Interior --}}
                                    <div class="row g-3 mt-3">
                                        <div class="col-sm-6 col-md-2">
                                            <label><i class="fas fa-chair"></i> Seats</label>
                                            <input type="number" wire:model.live="spec.seats" class="form-control" min="1" max="20">
                                        </div>
                                        <div class="col-sm-6 col-md-2">
                                            <label><i class="fas fa-door-closed"></i> Doors</label>
                                            <input type="number" wire:model.live="spec.doors" class="form-control" min="1" max="6">
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <label><i class="fas fa-arrows-alt-h"></i> Steering Position</label>
                                            <select wire:model.live="spec.steering_position" class="form-control">
                                                <option value="">Select</option>
                                                <option value="LEFT">Left</option>
                                                <option value="RIGHT">Right</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <label>Color</label>
                                            <div x-data="{ color: @entangle('spec.color').live }" class="d-flex align-items-center gap-2">
                                                <input type="color" x-model="color" class="form-control form-control-color" style="width:50px; height:50px;">
                                                <input type="text" x-model="color" class="form-control" placeholder="Pick color (HEX)">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif

                            {{-- ================= SUBMIT ================= --}}
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Save Model
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
