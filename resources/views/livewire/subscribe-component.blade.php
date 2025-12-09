<div>
    <form action="">
        <div class="input-group">
        <input type="email" wire:model="email" class="form-control" placeholder="Your Email Address" required>
        <div class="input-group-append">
        <button type="button" wire:click.prevent="subscribe" class="btn btn-primary">Sign Up
        <span wire:loading wire:target="subscribe" class="spinner-border spinner-border-sm ml-1" role="status"></span>
          </button>
        </div>
         </div>
         <div class="text-danger">@error('email') {{ $message }} @enderror</div>
         </form>
          @if(session('subscribeSuccess'))
    <div class="alert alert-sm alert-success mt-1">{{ session('subscribeSuccess') }}</div>
    @elseif(session('subscribeFail'))
    <div class="alert alert-sm alert-danger mt-1">{{ session('subscribeFail') }}</div>
    @endif
</div>