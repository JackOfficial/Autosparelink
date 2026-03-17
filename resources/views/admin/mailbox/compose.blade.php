@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-lg-3">
            <a href="{{ route('admin.mailbox.index') }}" class="btn btn-light btn-block mb-3 py-2 rounded-pill shadow-sm border">
                <i class="fa fa-arrow-left mr-2"></i> Back to Inbox
            </a>
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="p-3">
                        <h6 class="text-muted small text-uppercase font-weight-bold">Mailbox Folders</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <a href="{{ route('admin.mailbox.index', ['status' => 'active']) }}" class="list-group-item list-group-item-action border-0">
                            <i class="fa fa-inbox mr-2"></i> Inbox
                        </a>
                        <a href="{{ route('admin.mailbox.index', ['status' => 'resolved']) }}" class="list-group-item list-group-item-action border-0">
                            <i class="fa fa-check-circle mr-2"></i> Resolved
                        </a>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        {{ $replyTo ? 'Reply to Message' : 'Compose New Message' }}
                    </h5>
                </div>
                
                <div class="card-body border-top">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('admin.mailbox.send') }}" method="POST">
                        @csrf
                        
                        {{-- Hidden field to track if this is a reply to an existing contact --}}
                        @if($replyTo)
                            <input type="hidden" name="contact_id" value="{{ $replyTo->id }}">
                        @endif

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark">To:</label>
                            <input type="email" name="email" class="form-control border-light bg-light rounded-pill px-3 @error('email') is-invalid @enderror" 
                                   placeholder="recipient@example.com" 
                                   value="{{ old('email', $replyTo->email ?? '') }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark">Subject:</label>
                            <input type="text" name="subject" class="form-control border-light bg-light rounded-pill px-3 @error('subject') is-invalid @enderror" 
                                   placeholder="Enter subject" 
                                   value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}" required>
                            @error('subject') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-dark">Message Content:</label>
                            <textarea name="message" rows="10" class="form-control border-light bg-light @error('message') is-invalid @enderror" 
                                      placeholder="Type your message here..." 
                                      style="border-radius: 15px; resize: none;" required>{{ old('message') }}</textarea>
                            @error('message') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-light rounded-pill px-4 mr-2">Discard</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="fa fa-paper-plane mr-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($replyTo)
            <div class="mt-4 p-3 bg-light border rounded shadow-sm" style="border-style: dashed !important; opacity: 0.8;">
                <h6 class="font-weight-bold small text-muted">Original Message History:</h6>
                <div class="small text-muted">
                    <strong>From:</strong> {{ $replyTo->name }} ({{ $replyTo->email }})<br>
                    <strong>Date:</strong> {{ $replyTo->created_at->format('M d, Y') }}<br><br>
                    {{ $replyTo->message }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .form-control:focus { border-color: #007bff; box-shadow: none; background-color: #fff; }
    .list-group-item-action:hover { background-color: #f8f9fa; color: #007bff; }
</style>
@endsection