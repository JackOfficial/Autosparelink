@extends('layouts.dashboard')

@section('title', 'Open New Support Ticket')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Breadcrumb / Back Navigation --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}" class="text-decoration-none text-muted small">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.tickets.index') }}" class="text-decoration-none text-muted small">Tickets</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">New Ticket</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Column: Form --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h4 fw-bold text-dark mb-1">Open Support Ticket</h2>
                    <p class="text-muted small">Please fill out the form below. Our team usually responds within 24 hours.</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.tickets.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            {{-- Category Selection --}}
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label small fw-bold text-muted text-uppercase">Category</label>
                                <select name="category" id="category" class="form-select border-0 bg-light rounded-3 @error('category') is-invalid @enderror" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <option value="order_issue" {{ old('category') == 'order_issue' ? 'selected' : '' }}>Order Issue</option>
                                    <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment / Billing</option>
                                    <option value="part_request" {{ old('category') == 'part_request' ? 'selected' : '' }}>Part Availability</option>
                                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Linked Order (Dynamic from your Controller) --}}
                            <div class="col-md-6 mb-3">
                                <label for="order_id" class="form-label small fw-bold text-muted text-uppercase">Related Order (Optional)</label>
                                <select name="order_id" id="order_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">None / General Inquiry</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Subject --}}
                        <div class="mb-3">
                            <label for="subject" class="form-label small fw-bold text-muted text-uppercase">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                                   class="form-control border-0 bg-light rounded-3 @error('subject') is-invalid @enderror" 
                                   placeholder="Briefly describe the issue" required>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Message --}}
                        <div class="mb-4">
                            <label for="message" class="form-label small fw-bold text-muted text-uppercase">Detailed Description</label>
                            <textarea name="message" id="message" rows="6" 
                                      class="form-control border-0 bg-light rounded-3 @error('message') is-invalid @enderror" 
                                      placeholder="Provide as much detail as possible (VIN number, part name, order issues, etc.)" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('user.tickets.index') }}" class="btn btn-link text-muted text-decoration-none px-0">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                Submit Ticket <i class="fas fa-paper-plane ms-2 small"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Side Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i> Quick Tips</h5>
                    <ul class="list-unstyled mb-0 small opacity-90">
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle me-2 mt-1"></i>
                            <span>Include your <strong>VIN number</strong> for faster spare parts verification.</span>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="fas fa-check-circle me-2 mt-1"></i>
                            <span>Linking an <strong>Order ID</strong> helps us track your payment status immediately.</span>
                        </li>
                        <li class="d-flex">
                            <i class="fas fa-check-circle me-2 mt-1"></i>
                            <span>Please do not open multiple tickets for the same issue.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Support" width="60" class="mb-3 opacity-75">
                    <h6 class="fw-bold">Other ways to contact us?</h6>
                    <p class="text-muted small">If your request is extremely urgent, please use our WhatsApp live chat.</p>
                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill px-4">
                        <i class="fab fa-whatsapp me-2"></i> WhatsApp Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .form-select, .form-control {
        padding: 0.75rem 1rem;
        transition: all 0.2s ease-in-out;
    }
    .form-select:focus, .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        border: 1px solid #0d6efd !important;
    }
</style>
@endsection