@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="fa fa-file-alt mr-2"></i> Content Management
                    </h5>
                    <small class="text-muted">Manage your legal and informational pages</small>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4">Page Title</th>
                                    <th class="border-0">URL Slug</th>
                                    <th class="border-0 text-center">Last Updated</th>
                                    <th class="border-0 text-right px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                <tr>
                                    <td class="px-4 align-middle">
                                        <div class="font-weight-bold text-dark">{{ $page->title }}</div>
                                        <small class="text-muted">Type: {{ $page->slug === 'faqs' ? 'Structured Data' : 'Rich Text/HTML' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <code>/{{ $page->slug }}</code>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="text-muted small">{{ $page->updated_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $page->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-right px-4 align-middle">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary px-3">
                                                <i class="fa fa-edit mr-1"></i> Edit
                                            </a>
                                            
                                            <a href="{{ url($page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($pages->isEmpty())
                <div class="card-body text-center py-5">
                    <i class="fa fa-folder-open fa-3x text-light mb-3"></i>
                    <p class="text-muted">No pages found in the database. Run your seeders or add entries manually.</p>
                </div>
                @endif
                
                <div class="card-footer bg-white py-3">
                    <p class="mb-0 small text-muted">
                        <i class="fa fa-info-circle mr-1 text-info"></i> 
                        <strong>Note:</strong> Policies and Terms use HTML/CKEditor, while FAQs use the Dynamic Builder.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to match your premium marketplace theme */
    .table thead th {
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        color: #8898aa;
    }
    
    .table tbody td {
        border-top: 1px solid #f6f9fc;
        font-size: 14px;
    }
    
    .card {
        border-radius: 0.5rem;
    }

    code {
        background: #f1f3f5;
        padding: 2px 6px;
        border-radius: 4px;
        color: #e83e8c;
        font-weight: 500;
    }

    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
</style>
@endsection