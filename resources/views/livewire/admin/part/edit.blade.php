<div>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update" enctype="multipart/form-data">

        <div class="row">

            <div class="col-lg-6">
                <fieldset>
                    <legend>General Info</legend>

                    <div class="mb-3">
                        <label>Part Name *</label>
                        <input type="text" class="form-control" wire:model.defer="part_name">
                    </div>

                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" class="form-control" wire:model.defer="price">
                    </div>

                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" class="form-control" wire:model.defer="stock_quantity">
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" wire:model.defer="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                </fieldset>
            </div>

            <div class="col-lg-6">
                <fieldset>
                    <legend>Photos</legend>

                    {{-- Existing Photos --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($existingPhotos as $photo)
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$photo->file_path) }}"
                                     style="width:100px;height:100px;object-fit:cover;">
                                <button type="button"
                                        wire:click="deletePhoto({{ $photo->id }})"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>

                    {{-- Upload new --}}
                    <input type="file" multiple wire:model="photos" class="form-control">

                </fieldset>
            </div>

        </div>

        <div class="text-end mt-3">
            <button class="btn btn-primary" type="submit">
                Update Part
            </button>
        </div>

    </form>
</div>