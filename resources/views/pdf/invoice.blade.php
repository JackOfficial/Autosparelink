<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: right; border-bottom: 2px solid #ffcc00; padding-bottom: 10px; }
        .logo { float: left; font-size: 24px; font-weight: bold; color: #000; }
        .invoice-box { padding: 30px; }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table th { background: #f8f9fa; padding: 10px; }
        table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-weight: bold; color: #007bff; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="logo">AutoSpareLink</div>
            <div>Invoice #: {{ $order->id }}</div>
            <div>Date: {{ now()->format('d/m/Y') }}</div>
        </div>

        <h3 style="margin-top: 40px;">Customer Details</h3>
        <p>
            {{ $order->user->name }}<br>
            {{ $order->user->email }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Part Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price) }} RWF</td>
                    <td>{{ number_format($item->price * $item->quantity) }} RWF</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" align="right"><strong>Total Amount:</strong></td>
                    <td class="total">{{ number_format($order->total_amount) }} RWF</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>