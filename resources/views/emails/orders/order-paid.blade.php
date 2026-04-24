<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your order</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .content { padding: 20px; background: #ffffff; }
        .footer { text-align: center; font-size: 12px; color: #777; padding: 20px; }
        .order-summary { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-summary th, .order-summary td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .btn { display: inline-block; padding: 12px 24px; color: #fff !important; background-color: #28a745; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .status-badge { background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Confirmed!</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $order->user ? $order->user->name : 'Customer' }}</strong>,</p>
            
            <p>Thank you for shopping with <strong>Autosparelink</strong>. We've received your payment for order <strong>#{{ $order->order_number }}</strong>.</p>
            
            <p>Your order is currently <span class="status-badge">Processing</span>. As per our policy, funds will be held securely until you confirm receipt and acceptance of your spare parts.</p>

            <h3>Order Summary:</h3>
            <table class="order-summary">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->part->name ?? 'Spare Part' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0) }} RWF</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Total Paid:</strong></td>
                        <td><strong>{{ number_format($order->total_amount, 0) }} RWF</strong></td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}" class="btn">View Order Details</a>
            </div>

            <p style="margin-top: 30px;">
                <strong>Note:</strong> We have attached a formal PDF invoice to this email for your records.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Autosparelink Rwanda. All rights reserved.</p>
            <p>Kigali, Rwanda | Support: support@autosparelink.com</p>
        </div>
    </div>
</body>
</html>