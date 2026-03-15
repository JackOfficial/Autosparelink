<div class="modal fade" id="newTicketModal" tabindex="-1" role="dialog" aria-labelledby="newTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
            
            {{-- Modal Header: Dark/Gradient Aesthetic --}}
            <div class="modal-header border-0 bg-dark text-white p-4 position-relative" style="background: linear-gradient(45deg, #0f172a, #1e293b);">
                <div class="d-flex align-items-center" style="z-index: 2;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3 shadow" style="width: 45px; height: 45px; border: 2px solid rgba(255,255,255,0.1);">
                        <i class="fas fa-comment-dots text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="newTicketModalLabel">New Support Ticket</h5>
                        <p class="small mb-0 text-white-50">Expect a response within <span class="text-warning">2-4 hours</span></p>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-50 outline-none" data-dismiss="modal" aria-label="Close" style="text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{-- Subtle Background Icon --}}
                <i class="fas fa-headset position-absolute" style="right: 20px; bottom: -10px; font-size: 5rem; opacity: 0.05; color: white;"></i>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('tickets.store') }}" method="POST" id="ticketForm">
                @csrf
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    
                    <div class="row">
                        {{-- Category Selector --}}
                        <div class="col-md-6 form-group mb-3">
                            <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Request Type</label>
                            <div class="position-relative">
                                <select name="category" class="form-control custom-pill-input shadow-sm border-0 appearance-none" required>
                                    <option value="order">📦 Order Issue</option>
                                    <option value="payment">💰 Payment/Billing</option>
                                    <option value="part_request">🛠 Custom Request</option>
                                    <option value="technical">💻 Tech Support</option>
                                </select>
                                <i class="fas fa-chevron-down position-absolute text-muted" style="right: 15px; top: 15px; font-size: 0.8rem; pointer-events: none;"></i>
                            </div>
                        </div>

                        {{-- Order Reference (Optional but helpful for SMM/Auto parts) --}}
                        <div class="col-md-6 form-group mb-3">
                            <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Order ID (Optional)</label>
                            <input type="text" name="order_ref" class="form-control custom-pill-input shadow-sm border-0" placeholder="#REF-0000">
                        </div>
                    </div>

                    {{-- Subject Line --}}
                    <div class="form-group mb-3">
                        <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Subject</label>
                        <input type="text" name="subject" 
                               class="form-control custom-pill-input shadow-sm border-0 @error('subject') is-invalid @enderror" 
                               placeholder="Briefly describe the issue" 
                               value="{{ old('subject') }}" required>
                        @error('subject')
                            <span class="invalid-feedback ml-2 font-weight-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Message Area --}}
                    <div class="form-group mb-0">
                        <label class="x-small font-weight-bold text-uppercase text-muted ml-2">Describe your issue</label>
                        <textarea name="message" rows="4" 
                                  class="form-control shadow-sm border-0 @error('message') is-invalid @enderror" 
                                  style="border-radius: 18px; padding: 1.2rem; font-size: 0.9rem; resize: none;" 
                                  placeholder="Provide as much detail as possible to help us resolve this faster..." 
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback ml-2 font-weight-bold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-0 p-4 bg-white justify-content-between">
                    <button type="button" class="btn btn-link text-muted font-weight-bold text-decoration-none" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-lg transition-3s">
                        Submit Ticket <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom Elements to match happyfamilyrwanda.org */
    .custom-pill-input { 
        height: 48px !important; 
        border-radius: 50px !important; 
        padding: 0 1.25rem !important; 
        font-size: 0.9rem !important;
        background-color: white !important;
        transition: all 0.3s ease;
    }
    .custom-pill-input:focus {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15) !important;
        transform: translateY(-1px);
    }
    .x-small { font-size: 0.68rem; letter-spacing: 0.8px; }
    .appearance-none { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    .transition-3s { transition: all 0.3s ease; }
    .transition-3s:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3); }
    .outline-none:focus { outline: none !important; box-shadow: none !important; }

    /* Animation */
    .modal.fade .modal-dialog {
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
</style>