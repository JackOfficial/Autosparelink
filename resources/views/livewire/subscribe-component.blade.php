<div class="position-relative mx-auto">
    <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="email" wire:model="email" placeholder="Enter your email" required />
    <button type="button" wire:click.prevent="subscribe" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp 
        <div wire:loading wire:target="subscribe" class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
    </button>
    <div class="text-danger">@error('email') {{ $message }} @enderror</div>
    @if(session('subscribeSuccess'))
    <div class="alert alert-sm alert-success mt-1">{{ session('subscribeSuccess') }}</div>
    @elseif(session('subscribeFail'))
    <div class="alert alert-sm alert-danger mt-1">{{ session('subscribeFail') }}</div>
    @endif
</div>
