<div>
    @if(session('contactSuccess'))
    <div class="alert alert-success">{{ session('contactSuccess') }}</div>
    @endif
    @if(session('contactFail'))
    <div class="alert alert-danger">{{ session('contactFail') }}</div>
    @endif
    <form wire:submit.prevent="contact">
        <div class="row gx-4 gy-3">
            <div class="col-xl-6">
                <input type="text" wire:model="name" class="form-control bg-white border-0 py-3 px-4" placeholder="Your Full Name" required />
            </div>
            <div class="col-xl-6">
                <input type="email" wire:model="email" class="form-control bg-white border-0 py-3 px-4" placeholder="Your Email" required />
            </div>
            <div class="col-xl-12">
                <input type="text" wire:model="subject" class="form-control bg-white border-0 py-3 px-4" placeholder="Subject">
            </div>
            <div class="col-12">
                <textarea wire:model="message" class="form-control bg-white border-0 py-3 px-4" rows="7" cols="10" placeholder="Your Message" required></textarea>
            </div>
            <div class="col-12">
                <button class="btn-hover-bg btn btn-primary w-100 py-3 px-5" type="submit">Submit 
                    <div wire:loading wire:target="contact" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                </button>
            </div>
        </div>
    </form>
</div>
