@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 shadow-sm rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Help Center / FAQ</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mb-5" 
     x-data="{ 
        search: '', 
        category: 'All',
        faqs: [
            { cat: 'Shipping', q: 'How long does shipping to Kigali take?', a: 'Stock items usually ship within 1-3 working days. International delivery varies by courier.' },
            { cat: 'Payments', q: 'Do you accept Mobile Money?', a: 'Yes, we accept MoMo, bank transfers, and major credit cards for all spare parts orders.' },
            { cat: 'Returns', q: 'What is the restocking fee?', a: 'A 20% restocking fee (minimum $10) applies to all approved returns.' },
            { cat: 'Account', q: 'How do I track my order?', a: 'Once your order is dispatched, a tracking number from DHL, FedEx, or TNT will be emailed to you.' },
            { cat: 'Products', q: 'Are the parts original (OEM)?', a: 'We supply both genuine OEM parts and high-quality aftermarket substitutions.' }
        ],
        get filteredFaqs() {
            return this.faqs.filter(i => {
                const matchesSearch = i.q.toLowerCase().includes(this.search.toLowerCase()) || i.a.toLowerCase().includes(this.search.toLowerCase());
                const matchesCat = this.category === 'All' || i.cat === this.category;
                return matchesSearch && matchesCat;
            });
        }
     }">
    <div class="row px-xl-5">

        <div class="col-lg-3 col-md-4">
            <div class="bg-white p-4 mb-30 shadow-sm rounded border-top border-primary" style="border-top-width: 3px !important;">
                <h5 class="font-weight-bold mb-3 text-uppercase small">Find an Answer</h5>
                <div class="input-group">
                    <input type="text" x-model="search" class="form-control border-right-0" placeholder="Type your question...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white text-primary">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 mb-30 shadow-sm rounded border-top border-primary" style="border-top-width: 3px !important;">
                <h5 class="font-weight-bold mb-3 text-uppercase small">Categories</h5>
                <div class="list-group list-group-flush">
                    <button @click="category = 'All'" :class="category === 'All' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">All Categories</button>
                    <button @click="category = 'Shipping'" :class="category === 'Shipping' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Orders & Shipping</button>
                    <button @click="category = 'Payments'" :class="category === 'Payments' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Payments</button>
                    <button @click="category = 'Products'" :class="category === 'Products' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Products & Returns</button>
                    <button @click="category = 'Account'" :class="category === 'Account' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Account & Login</button>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8">
            <div class="bg-white p-4 p-md-5 mb-4 shadow-sm rounded">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h3 class="font-weight-bold mb-0">Frequently Asked Questions</h3>
                    <span class="badge badge-primary badge-pill px-3 py-2" x-text="filteredFaqs.length + ' Results'"></span>
                </div>

                <template x-if="filteredFaqs.length === 0">
                    <div class="text-center py-5">
                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                        <p class="lead text-muted">No questions found matching "<span x-text="search"></span>"</p>
                        <button @click="search = ''; category = 'All'" class="btn btn-primary btn-pill px-4">Clear All Filters</button>
                    </div>
                </template>

                <div id="faqAccordion">
                    <template x-for="(faq, index) in filteredFaqs" :key="index">
                        <div class="card mb-3 border rounded-lg overflow-hidden faq-card">
                            <div class="card-header bg-white p-0 border-0">
                                <button class="btn btn-link text-dark d-flex justify-content-between align-items-center w-100 text-left p-4 no-gutters" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        :data-target="'#collapse' + index" 
                                        aria-expanded="false">
                                    <span class="pr-3">
                                        <span class="badge badge-light text-primary mr-2" x-text="faq.cat"></span>
                                        <span class="font-weight-bold" x-text="faq.q"></span>
                                    </span>
                                    <i class="fa fa-chevron-down transition-icon"></i>
                                </button>
                            </div>

                            <div :id="'collapse' + index" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body bg-light border-top text-muted" style="font-size: 15px; line-height: 1.7;" x-text="faq.a">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-5 p-4 bg-primary text-white rounded-lg d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h5 class="mb-1 font-weight-bold">Didn't find what you need?</h5>
                        <p class="mb-0 opacity-75">Our Kigali-based team is ready to help you find the right part.</p>
                    </div>
                    <a href="mailto:help@autosparelink.com" class="btn btn-light text-primary font-weight-bold btn-pill px-4 mt-3 mt-md-0">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
        </div>
</div>

<style>
    /* Professional UI Enhancements */
    .faq-card {
        transition: all 0.3s ease;
        border: 1px solid #eef0f2 !important;
    }
    .faq-card:hover {
        border-color: #007bff !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .btn-link {
        text-decoration: none !important;
        color: #1a202c !important;
    }
    .transition-icon {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }
    .btn-link:not(.collapsed) .transition-icon {
        transform: rotate(180deg);
        color: #007bff;
    }
    .btn-pill {
        border-radius: 50px;
    }
    .opacity-75 { opacity: 0.75; }
    
    /* Animation for filtered items */
    [x-cloak] { display: none !important; }
</style>

@endsection