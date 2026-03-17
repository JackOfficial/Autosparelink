<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #444; }
        .wrapper { background-color: #f9f9f9; padding: 20px; }
        .main-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .brand-header { background: #ffcc00; padding: 30px; text-align: center; }
        .body-content { padding: 30px; }
        .order-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; border: 1px solid #e9ecef; }
        .item-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .footer-note { text-align: center; font-size: 13px; color: #888; padding: 20px; }
        .status-pill { display: inline-block; background: #e3f2fd; color: #0d47a1; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <div class="brand-header">
                <h1 style="margin:0; color: #000; font-size: 24px;">AutoSpareLink</h1>
                <p style="margin:5px 0 0; color: #333;">Quality Parts, Delivered.</p>
            </div>
            
            <div class="body-content">
                <h2 style="color: #333;">Murakoze, {{ $order->user->name }}!</h2>
                <p>We’ve received your request to <strong>Order via Phone Call</strong> for your selected spare parts.</p>
                
                <div class="order-box">
                    <div style="margin-bottom: 10px;">
                        <span class="status-pill">Pending Confirmation</span>
                        <span style="float: right; font-weight: bold;">Order #{{ $order->id }}</span>
                    </div>
                    <p style="margin: 15px 0 5px;"><strong>Summary:</strong></p>
                    @foreach($order->items as $item)
                        <div class="item-row">
                            <span>{{ $item->quantity }}x {{ $item->part->name ?? 'Spare Part' }}</span>
                            <span>{{ number_format($item->unit_price * $item->quantity, 0) }} RWF</span>
                        </div>
                    @endforeach
                    <div style="margin-top: 15px; text-align: right; font-size: 18px;">
                        <strong>Total: {{ number_format($order->total_amount, 0) }} RWF</strong>
                    </div>
                </div>

                <p><strong>What happens next?</strong></p>
                <ul style="padding-left: 20px;">
                    <li>Our team is checking the availability of your parts.</li>
                    <li>An agent will call you on <strong>{{ $order->address->phone }}</strong> shortly.</li>
                    <li>You can finalize payment (MoMo or Cash) during the call/delivery.</li>
                </ul>

                <p style="margin-top: 30px;">If you have any urgent questions, feel free to contact us directly at <a href="tel:+250788000000" style="color: #ffcc00; font-weight: bold;">+250 788 000 000</a>.</p>
            </div>

            <div class="footer-note">
                &copy; {{ date('Y') }} AutoSpareLink Rwanda. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>