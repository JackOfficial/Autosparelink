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
            { cat: 'Shipping', q: 'How long does delivery within Rwanda take?', a: 'For Kigali, we offer same-day or next-day delivery. For other provinces, it typically takes 2-3 working days after the vendor hands the item to our logistics team.' },
            { cat: 'Payments', q: 'Is my payment secure?', a: 'Yes. We use an escrow system. Your payment is held securely by AutoSpareLink and only released to the vendor after you receive and inspect your part.' },
            { cat: 'Vendors', q: 'How can I sell my spare parts on AutoSpareLink?', a: 'Simply click on \'Launch a Shop\' or register as a vendor. Once our team verifies your business documents and shop location in Rwanda, you can start listing parts.' },
            { cat: 'Returns', q: 'What happens if a vendor sends the wrong part?', a: 'If the part does not match the description or your VIN, you are protected by our Safe Delivery Guarantee. We will collect the part and issue a full refund.' },
            { cat: 'Products', q: 'How do I know if a part fits my car?', a: 'We recommend using our VIN Decoder tool or messaging the vendor directly via the product page to confirm compatibility before purchasing.' },
            { cat: 'Payments', q: 'What payment methods do you support?', a: 'We support MTN MoMo, Airtel Money, Bank Transfers, and all major Credit/Debit cards via our secure gateway.' },
            { cat: 'Vendors', q: 'What are the fees for selling?', a: 'Registration is free. We only charge a small commission fee on successful sales to cover logistics, payment processing, and marketing.' }
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
                    <button @click="category = 'All'" :class="category === 'All' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">All Questions</button>
                    <button @click="category = 'Vendors'" :class="category === 'Vendors' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Selling & Shops</button>
                    <button @click="category = 'Shipping'" :class="category === 'Shipping' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Delivery & Logistics</button>
                    <button @click="category = 'Payments'" :class="category === 'Payments' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Secure Payments</button>
                    <button @click="category = 'Returns'" :class="category === 'Returns' ? 'text-primary font-weight-bold' : ''" class="list-group-item list-group-item-action bg-transparent border-0 px-0">Returns & Refunds</button>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="bg-white p-4 p-md-5 mb-4 shadow-sm rounded">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h3 class="font-weight-bold mb-0">Help Center</h3>
                    <span class="badge badge-primary badge-pill px-3 py-2" x-text="filteredFaqs.length + ' FAQs Found'"></span>
                </div>

                <template x-if="filteredFaqs.length === 0">
                    <div class="text-center py-5">
                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                        <p class="lead text-muted">No questions found matching "<span x-text="search"></span>"</p>
                        <button @click="search = ''; category = 'All'" class="btn btn-primary btn-pill px-4">View All FAQs</button>
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

                <div class="mt-5 p-4 bg-dark text-white rounded-lg d-flex align-items-center justify-content-between flex-wrap shadow">
                    <div>
                        <h5 class="mb-1 font-weight-bold text-primary">Own a Spare Parts Shop?</h5>
                        <p class="mb-0 opacity-75">Join Rwanda's largest automotive network and start selling today.</p>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary font-weight-bold btn-pill px-4 mt-3 mt-md-0">
                        Become a Vendor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    [x-cloak] { display: none !important; }
</style>

@endsection