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

                            {{-- Error Messages --}}
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <fieldset class="border p-3 mb-4 rounded bg-light">
                                <legend class="w-auto fw-bold text-primary"><i class="fas fa-car-side"></i> Vehicle Model Details</legend>

                                <div class="row g-3">
                                    {{-- Brand Selection --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-industry"></i> Brand *</label>
                                            <select wire:model.live="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    {{-- Model Name Input --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-key"></i> Model Name *</label>
                                            <input type="text" wire:model.live="model_name" class="form-control @error('model_name') is-invalid @enderror" placeholder="e.g. Civic, Corolla, Golf">
                                            @error('model_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="form-group mt-3">
                                    <label><i class="fas fa-align-left"></i> Description (Optional)</label>
                                    <textarea wire:model.live="description" class="form-control" rows="3" placeholder="General info about this model series..."></textarea>
                                </div>

                                {{-- Photos (Keeping your Alpine.js logic) --}}
                                <div x-data="{ photos: [] }" class="form-group mt-3">
                                    <label><i class="fas fa-upload"></i> Model Photos</label>
                                    <input type="file" multiple
                                           x-on:change="photos = Array.from($event.target.files).map(file => URL.createObjectURL(file)); @this.uploadMultiple('photos', $event.target.files);"
                                           class="form-control">

                                    <div class="row mt-3" x-show="photos.length > 0">
                                        <template x-for="(photo, index) in photos" :key="index">
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <div class="card shadow-sm position-relative">
                                                    <img :src="photo" class="card-img-top rounded" style="height:120px; object-fit:cover;">
                                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle"
                                                            x-on:click="photos.splice(index, 1); @this.removeUpload('photos', index);">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success btn-lg px-5">
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