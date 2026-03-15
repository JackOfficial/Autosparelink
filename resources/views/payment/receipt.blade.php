<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $data['tx_ref'] }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">AutoSpareLink</td>
                            <td class="text-right">
                                Date: {{ date('M d, Y') }}<br>
                                Ref: {{ $data['tx_ref'] }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Customer: {{ $data['customer']['name'] }}<br>
                                Email: {{ $data['customer']['email'] }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Item Description</td>
                <td class="text-right">Price</td>
            </tr>
            <tr class="item">
                <td>Order #{{ $data['tx_ref'] }} (Flutterwave Payment)</td>
                <td class="text-right">{{ number_format($data['amount']) }} {{ $data['currency'] }}</td>
            </tr>
            <tr class="total">
                <td></td>
                <td class="text-right">Total: {{ number_format($data['amount']) }} {{ $data['currency'] }}</td>
            </tr>
        </table>
    </div>
</body>
</html>