@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Page: {{ $page->title }}</h5>
            <span class="badge badge-light text-primary">{{ strtoupper($page->slug) }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if($page->slug !== 'faqs')
                    <div class="form-group">
                        <label class="font-weight-bold">Page Content (HTML)</label>
                        <textarea name="content" id="editor" class="form-control" rows="15">{{ $page->content }}</textarea>
                    </div>
                @else
                    <div x-data="{ items: {{ $page->content && json_decode($page->content) ? $page->content : '[]' }} }">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="font-weight-bold mb-0">FAQ Items</label>
                            <button type="button" @click="items.push({cat: '', q: '', a: ''})" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-plus mr-1"></i> Add Question
                            </button>
                        </div>
                        
                        <input type="hidden" name="faq_data" value="">

                        <template x-for="(item, index) in items" :key="index">
                            <div class="card mb-3 border-left-primary shadow-sm">
                                <div class="card-body p-3 bg-light">
                                    <div class="row align-items-start">
                                        <div class="col-md-3">
                                            <label class="small font-weight-bold text-uppercase">Category</label>
                                            <input type="text" x-model="item.cat" :name="'faq_data['+index+'][cat]'" class="form-control form-control-sm mb-2" placeholder="e.g. Shipping">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="small font-weight-bold text-uppercase">Question & Answer</label>
                                            <input type="text" x-model="item.q" :name="'faq_data['+index+'][q]'" class="form-control form-control-sm mb-2" placeholder="The question...">
                                            <textarea x-model="item.a" :name="'faq_data['+index+'][a]'" class="form-control form-control-sm" rows="3" placeholder="The detailed answer..."></textarea>
                                        </div>
                                        <div class="col-md-1 text-right mt-4">
                                            <button type="button" @click="items.splice(index, 1)" class="btn btn-outline-danger btn-sm border-0">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="items.length === 0" class="text-center py-4 border rounded bg-white">
                            <p class="text-muted mb-0">No questions added yet. Click "Add Question" to start.</p>
                        </div>
                    </div>
                @endif

                <div class="mt-4 border-top pt-4">
                    <button type="submit" class="btn btn-success px-5 shadow-sm">
                        <i class="fa fa-save mr-2"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-link text-muted">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #007bff !important; }
    .ck-editor__editable { min-height: 400px; }
</style>

@if($page->slug !== 'faqs')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#editor'), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
    }).catch(error => { console.error(error); });
</script>
@endif
@endsection