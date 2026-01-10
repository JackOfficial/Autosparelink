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

                            {{-- ================= GLOBAL ERROR MESSAGES ================= --}}
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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
                                <div x-data="{ photos: [] }" class="form-group mt-3">
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
                                <div class="form-group mt-3">
                                    <label><i class="fas fa-layer-group"></i> Does this model have variants?</label>
                                    <div class="form-check form-check-inline mr-2">
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
