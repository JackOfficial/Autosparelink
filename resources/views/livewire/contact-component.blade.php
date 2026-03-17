<div class="contact-form bg-light p-30 shadow-sm" style="border-radius: 15px;">
    @if($successMessage)
        <div class="alert alert-success animate__animated animate__fadeIn">
            <i class="fa fa-check-circle mr-2"></i> {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="row">
            <div class="col-md-6">
                <div class="control-group mb-3">
                    <label class="small font-weight-bold text-dark">Full Name</label>
                    <input type="text" class="form-control border-0 shadow-none @error('name') is-invalid @enderror" 
                           placeholder="Enter your name" wire:model="name" style="border-radius: 10px; padding: 1.2rem;">
                    @error('name') <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="control-group mb-3">
                    <label class="small font-weight-bold text-dark">Email Address</label>
                    <input type="email" class="form-control border-0 shadow-none @error('email') is-invalid @enderror" 
                           placeholder="example@mail.com" wire:model="email" style="border-radius: 10px; padding: 1.2rem;">
                    @error('email') <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="control-group mb-3">
            <label class="small font-weight-bold text-dark">Subject</label>
            <input type="text" class="form-control border-0 shadow-none @error('subject') is-invalid @enderror" 
                   placeholder="How can we help?" wire:model="subject" style="border-radius: 10px; padding: 1.2rem;">
            @error('subject') <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
        </div>

        <div class="control-group mb-4">
            <label class="small font-weight-bold text-dark">Message Details</label>
            <textarea class="form-control border-0 shadow-none @error('message') is-invalid @enderror" 
                      rows="6" placeholder="Write your message here..." wire:model="message" 
                      style="border-radius: 15px; padding: 1.2rem; resize: none;"></textarea>
            @error('message') <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
        </div>

        <div class="text-right">
            <button class="btn btn-primary py-3 px-5 font-weight-bold shadow-sm" 
                    type="submit" 
                    wire:loading.attr="disabled"
                    style="border-radius: 50px; transition: all 0.3s ease;">
                
                {{-- Show spinner when sending --}}
                <span wire:loading.remove wire:target="submit">
                    <i class="fa fa-paper-plane mr-2"></i> Send Message
                </span>
                
                <span wire:loading wire:target="submit">
                    <i class="fa fa-spinner fa-spin mr-2"></i> Sending...
                </span>
            </button>
        </div>
    </form>
</div>