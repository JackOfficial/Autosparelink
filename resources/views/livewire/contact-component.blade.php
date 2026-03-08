<div class="contact-form bg-light p-30">
    @if($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="control-group mb-3">
            <input type="text" class="form-control" placeholder="Your Name" wire:model="name">
            @error('name') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="control-group mb-3">
            <input type="email" class="form-control" placeholder="Your Email" wire:model="email">
            @error('email') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="control-group mb-3">
            <input type="text" class="form-control" placeholder="Subject" wire:model="subject">
            @error('subject') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="control-group mb-3">
            <textarea class="form-control" rows="8" placeholder="Message" wire:model="message"></textarea>
            @error('message') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <button class="btn btn-primary py-2 px-4" type="submit">Send Message</button>
        </div>
    </form>
</div>
