<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { background: #1a1a1a; color: #ffffff; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .details-table td { padding: 10px; border-bottom: 1px solid #f4f4f4; }
        .label { font-weight: bold; color: #555; width: 30%; }
        .badge { background: #ffcc00; color: #000; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; }
        .footer { font-size: 12px; text-align: center; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">New Callback Request</h2>
            <p style="margin:5px 0 0 0;">Order #{{ $order->id }}</p>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p>A customer has requested a callback for their order. They chose <strong>"Order via Phone"</strong> instead of paying online.</p>

            <table class="details-table">
                <tr>
                    <td class="label">Customer</td>
                    <td>{{ $order->user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Phone Number</td>
                    <td><a href="tel:{{ $order->address->phone }}" style="color: #007bff; font-weight: bold;">{{ $order->address->phone }}</a></td>
                </tr>
                <tr>
                    <td class="label">City/Area</td>
                    <td>{{ $order->address->city }}</td>
                </tr>
                <tr>
                    <td class="label">Total Amount</td>
                    <td><strong>{{ number_format($order->total_amount, 0) }} RWF</strong></td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td><span class="badge">CALLBACK REQUESTED</span></td>
                </tr>
            </table>

            <h3 style="margin-top: 25px; border-bottom: 2px solid #ffcc00; display: inline-block;">Requested Parts</h3>
            <ul style="list-style: none; padding: 0;">
                @foreach($order->items as $item)
                    <li style="padding: 10px 0; border-bottom: 1px dashed #eee;">
                        <strong>{{ $item->quantity }}x</strong> - {{ $item->part->name ?? 'Spare Part' }} 
                        <span style="color: #888; float: right;">{{ number_format($item->unit_price, 0) }} RWF</span>
                    </li>
                @endforeach
            </ul>

            <p style="margin-top: 20px; background: #fdf6e3; padding: 15px; border-radius: 5px; border-left: 4px solid #ffcc00;">
                <strong>Action Required:</strong> Please call this customer within the next 10 minutes to confirm the part compatibility and delivery.
            </p>
        </div>

        <div class="footer">
            Sent from AutoSpareLink SMM System &bull; Kigali, Rwanda
        </div>
    </div>
</body>
</html>