{{-- Identity Section Excerpt --}}
<div class="row">
    {{-- ... Brand, Model, Trim, Base Year ... --}}

    <div class="col-md-6 mb-3">
        <label class="small font-weight-bold text-muted">Production Start (Month & Year) *</label>
        <div class="d-flex">
            <select wire:model="start_month" class="form-control mr-2" style="width: 40%;">
                <option value="">Month</option>
                @foreach($months as $num => $name) <option value="{{ $num }}">{{ $name }}</option> @endforeach
            </select>
            <input type="number" wire:model="start_year" class="form-control" placeholder="Year (e.g. 2024)">
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label class="small font-weight-bold text-muted">Production End (Month & Year)</label>
        <div class="d-flex">
            <select wire:model="end_month" class="form-control mr-2" style="width: 40%;">
                <option value="">Month</option>
                @foreach($months as $num => $name) <option value="{{ $num }}">{{ $name }}</option> @endforeach
            </select>
            <input type="number" wire:model="end_year" class="form-control" placeholder="Year (or empty)">
        </div>
    </div>
</div>

{{-- Body & Capacity Section --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="bg-white p-4 rounded shadow-sm">
            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                <i class="fas fa-ruler-combined mr-2"></i> Body & Capacity
            </h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold text-muted">Body Type</label>
                    <select wire:model="body_type_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold text-muted">Doors</label>
                    <input type="number" wire:model="doors" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold text-muted">Seats</label>
                    <input type="number" wire:model="seats" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold text-muted">Fuel Tank (L)</label>
                    <input type="number" wire:model="tank_capacity" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>