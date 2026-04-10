<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .invoice-card {
            max-width: 850px;
            margin: 30px auto;
            background: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        .invoice-logo { height: 50px; filter: grayscale(100%); }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; }
        .total-section { background-color: #f8f9fa; padding: 20px; border-radius: 8px; }
        
        @media print {
            body { background: none; padding: 0; }
            .invoice-card { box-shadow: none; margin: 0; width: 100%; max-width: 100%; padding: 20px; }
            .no-print { display: none !important; }
            .print-m-0 { margin: 0 !important; }
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Action Buttons --}}
    <div class="max-width: 850px; margin: 20px auto;" class="no-print text-center mb-4 mt-4">
        <button onclick="window.print()" class="btn btn-primary px-4">
            <i class="fas fa-print me-2"></i> Print Invoice
        </button>
        <a href="{{ route('shop.sales.index') }}" class="btn btn-outline-secondary px-4">
            Back to Sales
        </a>
    </div>

    <div class="invoice-card">
        {{-- Header --}}
        <div class="row mb-5">
            <div class="col-6">
                <h2 class="fw-bold text-uppercase mb-0">{{ Auth::user()->shop->name ?? 'OFFICIAL INVOICE' }}</h2>
                <p class="text-muted small">Professional Automotive Spare Parts</p>
                <div class="mt-3">
                    <p class="mb-0 small fw-bold">Kigali, Rwanda</p>
                    <p class="mb-0 small text-muted">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="col-6 text-end">
                <h1 class="text-muted opacity-25 fw-bold" style="font-size: 3.5rem;">INVOICE</h1>
                <p class="mb-0"><strong>Invoice No:</strong> #{{ $order->id }}</p>
                <p class="mb-0"><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                <p class="mb-0 text-uppercase text-primary fw-bold">{{ $order->status }}</p>
            </div>
        </div>

        <hr class="my-5">

        {{-- Addresses --}}
        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-muted text-uppercase small fw-bold">Billed To:</h6>
                <h5 class="fw-bold mb-1">{{ $order->guest_name ?? $order->user->name ?? 'Valued Customer' }}</h5>
                <p class="text-muted small mb-0">{{ $order->guest_email ?? $order->user->email ?? '' }}</p>
                <p class="text-muted small">{{ $order->guest_phone ?? '' }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-muted text-uppercase small fw-bold">Shipping Address:</h6>
                <p class="small mb-0">
                    {{ $order->address->street ?? 'Pickup' }}<br>
                    {{ $order->address->city ?? 'Kigali' }}, {{ $order->address->country ?? 'Rwanda' }}
                </p>
            </div>
        </div>

        {{-- Items Table --}}
        <table class="table table-borderless mb-4">
            <thead>
                <tr>
                    <th class="py-3">Description</th>
                    <th class="text-center py-3">Qty</th>
                    <th class="text-end py-3">Unit Price</th>
                    <th class="text-end py-3">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr class="border-bottom">
                    <td class="py-3">
                        <div class="fw-bold">{{ $item->part->part_name }}</div>
                        <small class="text-muted">SKU: {{ $item->part->sku }}</small>
                    </td>
                    <td class="text-center py-3">{{ $item->quantity }}</td>
                    <td class="text-end py-3">{{ number_format($item->unit_price) }} RWF</td>
                    <td class="text-end py-3 fw-bold">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="total-section">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">{{ number_format($order->total_amount) }} RWF</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax (0%)</span>
                        <span class="fw-bold">0 RWF</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold mb-0">Total Amount</h5>
                        <h5 class="fw-bold text-primary mb-0">{{ number_format($order->total_amount) }} RWF</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-5 pt-5 text-center">
            <p class="text-muted small">Thank you for choosing <strong>{{ Auth::user()->shop->name }}</strong> for your vehicle parts.</p>
            <div class="mt-4">
                <span class="badge bg-light text-dark p-2 border">Generated via {{ config('app.name') }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</html>