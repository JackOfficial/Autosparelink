<x-shop-dashboard>
    <x-slot:title>Create Support Ticket</x-slot:title>

    <div class="container-fluid py-4" x-data="ticketForm()">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('shop.support.index') }}" class="btn btn-sm btn-light me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h5 class="mb-0 fw-bold text-dark">New Support Request</h5>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ route('shop.support.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3 mb-4">
                                {{-- Category Selection --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Issue Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <option value="Payments">Payments & Payouts</option>
                                        <option value="Orders">Order Dispute</option>
                                        <option value="Technical">Technical Issue</option>
                                        <option value="Inventory">Inventory/Part Listing</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                {{-- Priority --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Urgency</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="low">Low (General Inquiry)</option>
                                        <option value="medium" selected>Medium (Standard)</option>
                                        <option value="high">High (Urgent Issue)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Linked Order (Optional) --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Relates to Order (Optional)</label>
                                <select name="order_id" class="form-select">
                                    <option value="">None / Not Related to an Order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">
                                            Order #{{ $order->order_number }} - {{ number_format($order->total_amount) }} RWF ({{ $order->created_at->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted mt-1">Linking an order helps us resolve issues faster.</div>
                            </div>

                            {{-- Subject --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Brief summary of the problem" required>
                            </div>

                            {{-- Message --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Detailed Message</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Please provide as much detail as possible..." required></textarea>
                            </div>

                            {{-- Photo Attachments with Alpine.js Preview --}}
                            <div class="mb-4 p-4 bg-light rounded-3 border-dashed">
                                <label class="form-label small fw-bold text-muted text-uppercase d-block mb-3">
                                    <i class="fas fa-paperclip me-2 text-primary"></i> Attachments (Images)
                                </label>
                                
                                <input type="file" name="photos[]" class="form-control" 
                                       @change="handleFiles" multiple accept="image/*">
                                
                                <div class="form-text mt-2 mb-3">You can select multiple images (Max 2MB each).</div>

                                {{-- Alpine.js Preview Grid --}}
                                <template x-if="previews.length > 0">
                                    <div class="row g-2 mt-3">
                                        <template x-for="(src, index) in previews" :key="index">
                                            <div class="col-3 col-md-2 position-relative">
                                                <img :src="src" class="img-thumbnail w-100 shadow-sm" style="height: 80px; object-fit: cover;">
                                                <button type="button" @click="removeFile(index)" 
                                                        class="btn btn-danger btn-sm position-absolute top-0 start-100 translate-middle rounded-circle p-0" 
                                                        style="width: 20px; height: 20px; line-height: 1;">
                                                    &times;
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Ticket
                                </button>
                                <a href="{{ route('shop.support.index') }}" class="btn btn-link text-decoration-none text-muted">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function ticketForm() {
            return {
                previews: [],
                handleFiles(event) {
                    this.previews = [];
                    const files = event.target.files;
                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(files[i]);
                    }
                },
                removeFile(index) {
                    // Logic to clear preview (Note: cleaning input[type=file] is tricky, usually handled by re-selecting)
                    this.previews.splice(index, 1);
                }
            }
        }
    </script>
    <style>
        .border-dashed { border: 2px dashed #dee2e6; }
        .form-control:focus, .form-select:focus {
            border-color: #4338ca;
            box-shadow: 0 0 0 0.25rem rgba(67, 56, 202, 0.1);
        }
    </style>
    @endpush
</x-shop-dashboard>