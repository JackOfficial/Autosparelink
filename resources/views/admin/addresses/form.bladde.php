<div class="mb-3">
    <label>Full Name</label>
    <input type="text" name="full_name"
        value="{{ old('full_name', $address->full_name ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone"
        value="{{ old('phone', $address->phone ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>Street Address</label>
    <input type="text" name="street_address"
        value="{{ old('street_address', $address->street_address ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>City</label>
    <input type="text" name="city"
        value="{{ old('city', $address->city ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>State</label>
    <input type="text" name="state"
        value="{{ old('state', $address->state ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>Postal Code</label>
    <input type="text" name="postal_code"
        value="{{ old('postal_code', $address->postal_code ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>Country</label>
    <input type="text" name="country"
        value="{{ old('country', $address->country ?? '') }}"
        class="form-control">
</div>