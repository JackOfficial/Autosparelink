<div>
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Main Form --}}
            <div class="col-md-8">
                <div class="box box-primary box-solid shadow-sm">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-edit" style="margin-right: 5px;"></i> Edit Technical Specification
                        </h3>
                    </div>
                    <div class="box-body" style="padding: 20px;">

                        {{-- ================= Vehicle Selection ================= --}}
                        <fieldset style="border: 1px solid #d2d6de; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                            <legend style="width: auto; padding: 0 10px; border-bottom: none; font-size: 13px; font-weight: bold; color: #3c8dbc; text-transform: uppercase; margin-bottom: 5px;">
                                Vehicle Selection
                            </legend>
                            <div class="row">
                                <div class="col-md-4 form-group @error('brand_id') has-error @enderror">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select wire:model.live="brand_id" class="form-control">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <span class="help-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-4 form-group @error('vehicle_model_id') has-error @enderror">
                                    <label>Vehicle Model <span class="text-danger">*</span></label>
                                    <select wire:model.live="vehicle_model_id" class="form-control">
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_model_id') <span class="help-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-4 form-group">
                                    <label>Trim Level</label> {{-- Changed from Variant --}}
                                    <select wire:model.live="variant_id" class="form-control">
                                        <option value="">Select Trim Level (optional)</option>
                                        @foreach($filteredVariants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Core Specifications ================= --}}
                        <fieldset style="border: 1px solid #d2d6de; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                            <legend style="width: auto; padding: 0 10px; border-bottom: none; font-size: 13px; font-weight: bold; color: #3c8dbc; text-transform: uppercase; margin-bottom: 5px;">
                                Core Specifications
                            </legend>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Body Type</label>
                                    <select wire:model="body_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($bodyTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Fuel Type</label>
                                    <select wire:model="engine_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($engineTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Transmission</label>
                                    <select wire:model="transmission_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($transmissionTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Drive Type</label>
                                    <select wire:model="drive_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($driveTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Engine Displacement</label>
                                    <select wire:model="engine_displacement_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($engineDisplacements as $ed)
                                            <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Performance & Capacity ================= --}}
                        <fieldset style="border: 1px solid #d2d6de; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                            <legend style="width: auto; padding: 0 10px; border-bottom: none; font-size: 13px; font-weight: bold; color: #3c8dbc; text-transform: uppercase; margin-bottom: 5px;">
                                Performance & Capacity
                            </legend>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Horsepower (HP)</label>
                                    <input type="number" wire:model="horsepower" class="form-control">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Torque (Nm)</label>
                                    <input type="number" wire:model="torque" class="form-control">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Fuel Capacity (L)</label>
                                    <input type="number" wire:model="fuel_capacity" step="0.1" class="form-control">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Efficiency (km/L)</label>
                                    <input type="number" wire:model="fuel_efficiency" step="0.1" class="form-control">
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Interior & Production ================= --}}
                        <fieldset style="border: 1px solid #d2d6de; padding: 15px; border-radius: 4px;">
                            <legend style="width: auto; padding: 0 10px; border-bottom: none; font-size: 13px; font-weight: bold; color: #3c8dbc; text-transform: uppercase; margin-bottom: 5px;">
                                Interior & Production
                            </legend>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Seats</label>
                                    <input type="number" wire:model="seats" class="form-control">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Doors</label>
                                    <input type="number" wire:model="doors" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Steering</label>
                                    <select wire:model="steering_position" class="form-control">
                                        <option value="LEFT">Left-Hand Drive</option>
                                        <option value="RIGHT">Right-Hand Drive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Color</label>
                                    <div class="input-group">
                                        <span class="input-group-addon" style="padding: 0; border: none;">
                                            <input type="color" wire:model.live="color" style="width: 40px; height: 34px; border: 1px solid #d2d6de; padding: 2px;">
                                        </span>
                                        <input type="text" wire:model="color" class="form-control" placeholder="#000000">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Prod. Year</label>
                                    <input type="number" wire:model="production_year" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>End Year</label>
                                    <input type="number" wire:model="production_end" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save"></i> Update Specification
                        </button>
                        <a href="{{ route('admin.specifications.index') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                </div>
            </div>

            {{-- Right Column: Preview --}}
            <div class="col-md-4">
                <div class="box box-solid box-dark shadow-sm" style="background: #222d32; color: white;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="color: white; font-size: 12px; text-transform: uppercase;">Live Preview</h3>
                    </div>
                    <div class="box-body text-center" style="padding: 30px 15px;">
                        <div class="img-circle" 
                             style="width: 70px; height: 70px; margin: 0 auto 15px; border: 2px solid #555; background-color: {{ $color ?: '#555' }}; transition: background 0.3s;">
                        </div>
                        
                        <h4 style="margin: 0; font-weight: bold; color: #fff;">
                            {{ $brand_id ? $brands->firstWhere('id', $brand_id)->brand_name : 'Brand' }}
                        </h4>
                        <p style="color: #3c8dbc; font-size: 16px; margin-bottom: 5px;">
                            {{ $vehicle_model_id ? (\App\Models\VehicleModel::find($vehicle_model_id)?->model_name) : 'Model' }}
                        </p>
                        <span class="label label-default" style="font-size: 12px; padding: 5px 10px;">
                            {{ $variant_id ? (\App\Models\Variant::find($variant_id)?->name) : 'Base Trim' }}
                        </span>
                        
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-xs-6">
                                <div style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 4px; border: 1px solid #444;">
                                    <small style="display: block; color: #888; text-transform: uppercase; font-size: 10px;">Power</small>
                                    <strong style="font-size: 14px;">{{ $horsepower ?: '--' }} HP</strong>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 4px; border: 1px solid #444;">
                                    <small style="display: block; color: #888; text-transform: uppercase; font-size: 10px;">Seats</small>
                                    <strong style="font-size: 14px;">{{ $seats ?: '--' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; border-top: 1px solid #444; padding-top: 15px;">
                            <label style="cursor: pointer;">
                                <input type="checkbox" wire:model="status" style="margin-right: 8px; vertical-align: middle;">
                                <span style="vertical-align: middle;">Set as Active Specification</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>