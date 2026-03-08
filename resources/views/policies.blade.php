@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <span class="breadcrumb-item active">Policies</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Policies Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">

            <!-- Privacy Policy -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Privacy Policy</h3>
                <p style="line-height: 1.6;">We value your privacy and protect your personal information. Data collected on AutoSpareLink is used to improve your shopping experience and is never shared without consent.</p>

                <h5 class="mt-4">Data Collection & Use</h5>
                <ul>
                    <li>When you register or place an order, we collect your name, email, mailing address, and phone number. You can also browse our site without registering.</li>
                    <li>We use your information to process transactions, send updates, and respond to inquiries.</li>
                    <li>Cookies are used to recognize your browser and save your preferences for future visits.</li>
                </ul>

                <h5 class="mt-3">Data Security & Sharing</h5>
                <ul>
                    <li>We use secure servers and SSL encryption to safeguard your personal data. Your sensitive information is not stored on our servers after the transaction.</li>
                    <li>We share your personal data only with trusted partners who help us run our website or serve you, and only if they agree to keep it confidential. Non-personal data may be shared for marketing purposes.</li>
                </ul>

                <h5 class="mt-3">Your Rights & Policy Updates</h5>
                <p>If you have an account on AutoSpareLink, you may request deletion of your account data by emailing <a href="mailto:privacy@autosparelink.com">privacy@autosparelink.com</a> or upon account closure. Once deleted, your data cannot be recovered.</p>
            </div>

            <!-- Return Policy -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Return Policy</h3>
                <p style="line-height: 1.6;">AutoSpareLink parts may be returned only if they are unused, undamaged, and sent back within 14 days of delivery. Shipping charges (or free shipping cost) are non-refundable, and a restocking fee of 20% (minimum $10) applies. Items that are used, incomplete, sent to the wrong address, or returned without prior approval will not be refunded. Returns after 30 days are not accepted.</p>

                <h5 class="mt-3">Return Procedure</h5>
                <ul>
                    <li>Return items using the same shipping method as originally delivered; using the original carrier is required to reclaim any duty charges.</li>
                    <li>Ensure the original packaging is intact and manufacturer barcode labels are undamaged.</li>
                    <li>Ship your return within 14 days of delivery to the address on our Contact page.</li>
                    <li>You will receive an 80% refund of the item cost, with 20% (or a minimum of $10) deducted as a restocking fee.</li>
                    <li>You are responsible for the return shipping costs.</li>
                    <li>Email your tracking number to <a href="mailto:help@autosparelink.com">help@autosparelink.com</a> as proof of return.</li>
                </ul>
            </div>

            <!-- Refund Policy -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Refund Policy</h3>
                <p style="line-height: 1.6;">Refunds are issued using the original payment method. If an item is out of stock or delivered in a lesser quantity, you will be refunded the full amount (including shipping) for each item. We do not refund any price differences for substitute parts.</p>
            </div>

            <!-- Cancellation Policy -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Cancellation Policy</h3>
                <p style="line-height: 1.6;">You may cancel your order if it has not yet shipped. If your order status is "Paid," you can cancel for a full refund. Once the order has shipped, please follow our Return Policy. Orders for "Remote dealer branch" items cannot be cancelled.</p>
            </div>

            <!-- Shipping Policy -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Shipping Policy</h3>
                <p style="line-height: 1.6;">All shipments are insured. We do our best to ensure your package arrives in good condition, but we are not liable for damage or delays — especially with freight forwarders. Shipping dates are estimates, and any refused shipment may incur extra charges. It is the buyer's responsibility to track their shipment, as it may be held in the destination country for reasons such as documentation required from the consignee or other formalities that the receiver must complete.</p>
                <p>We ship to nearly 180 countries. Small items may arrive without original packaging due to bulk shipping from manufacturers. We work with FedEx, TNT, DHL, EMS, and Emirates Post. Stock items usually ship within 1-3 working days. Glass items require special packaging and extra fees.</p>
            </div>

            <!-- Damages -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Damages</h3>
                <p style="line-height: 1.6;">Report any damages or defects within 14 days of delivery by emailing <a href="mailto:help@autosparelink.com">help@autosparelink.com</a>. Claims made after 14 days will not be accepted.</p>
            </div>

            <!-- Governing Law / Jurisdiction -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Governing Law / Jurisdiction</h3>
                <p style="line-height: 1.6;">These agreements are governed by the laws of Dubai, United Arab Emirates. Any disputes will be resolved under UAE jurisdiction.</p>
            </div>

            <!-- Terms & Contact -->
            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Terms & Contact</h3>
                <p style="line-height: 1.6;">Please review our Terms and Conditions for details on using our website.</p>
                <p>For any questions, please contact us at:</p>
                <address>
                    AutoSpareLink<br>
                    Gisozi<br>
                    Kigali, Rwanda<br>
                    Email: <a href="mailto:help@autosparelink.com">help@autosparelink.com</a><br>
                    Website: <a href="https://autosparelink.com" target="_blank">autosparelink.com</a>
                </address>
            </div>

            <!-- Related Pages Start -->
            <div class="related-pages mb-5">
                <h5 class="mb-3 text-uppercase">Related Pages</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="/about" class="text-decoration-none">
                            <div class="card hover-shadow border-0 text-center p-3">
                                <i class="fa fa-info-circle fa-2x mb-2 text-primary"></i>
                                <h6 class="mb-0">About Us</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/terms-and-conditions" class="text-decoration-none">
                            <div class="card hover-shadow border-0 text-center p-3">
                                <i class="fa fa-file fa-2x mb-2 text-primary"></i>
                                <h6 class="mb-0">Terms & Conditions</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a class="text-decoration-none">
                            <div class="card hover-shadow border-0 text-center p-3">
                                <i class="fa fa-shield-alt fa-2x mb-2 text-primary"></i>
                                <h6 class="mb-0">Policies</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Related Pages End -->

        </div>
    </div>
</div>
<!-- Policies End -->

<style>
    h3 {
        font-weight: 700;
        font-size: 22px;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 6px;
    }

    h5 {
        font-weight: 600;
        font-size: 18px;
    }

    p, ul, address {
        font-size: 14px;
    }

    ul li {
        margin-bottom: 6px;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
</style>

@endsection
