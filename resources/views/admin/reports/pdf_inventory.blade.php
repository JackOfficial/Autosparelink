<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary-table { width: 100%; margin-bottom: 20px; border: 1px solid #eee; }
        .summary-table td { padding: 10px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 7px; text-align: left; }
        th { background-color: #343a40; color: white; }
        .critical { color: #d9534f; font-weight: bold; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>AutoSparePart Inventory Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td><strong>Total Items:</strong> {{ $stats['total_items'] }}</td>
            <td><strong>Active Parts:</strong> {{ $stats['active_parts'] }}</td>
            <td><strong>Total Stock:</strong> {{ $stats['total_stock'] }}</td>
            <td><strong>Est. Value:</strong> ${{ number_format($stats['inventory_val'], 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Low Stock Alerts (Items < 5)</div>
    <table>
        <thead>
            <tr>
                <th>Part Name</th>
                <th>Part # / SKU</th>
                <th>Brand</th>
                <th>Stock Left</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $item)
            <tr>
                <td>{{ $item->part_name }}</td>
                <td>{{ $item->part_number }} / {{ $item->sku }}</td>
                <td>{{ $item->partBrand->name ?? 'N/A' }}</td>
                <td class="critical">{{ $item->stock_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($outOfStockItems->count() > 0)
    <div class="section-title" style="color: #d9534f;">Out of Stock Items</div>
    <table>
        <thead>
            <tr>
                <th>Part Name</th>
                <th>Part #</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outOfStockItems as $item)
            <tr>
                <td>{{ $item->part_name }}</td>
                <td>{{ $item->part_number }}</td>
                <td>OUT OF STOCK</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="text-align: center; font-size: 9px; margin-top: 20px; color: #999;">
        End of Inventory Report - autosparepart.com
    </div>
</body>
</html>