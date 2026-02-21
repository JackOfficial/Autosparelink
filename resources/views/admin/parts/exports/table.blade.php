<table>
    <thead>
        <tr>
            <th style="background-color: #f0f0f0; font-weight: bold;">Part Name</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">SKU</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Category</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Brand</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Price (RWF)</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Stock</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Compatibility</th>
        </tr>
    </thead>
    <tbody>
        @foreach($parts as $part)
        <tr>
            <td>{{ $part->part_name }}</td>
            <td>{{ $part->sku }}</td>
            <td>{{ $part->category->category_name ?? 'N/A' }}</td>
            <td>{{ $part->partBrand->name ?? 'N/A' }}</td>
            <td>{{ number_format($part->price) }}</td>
            <td>{{ $part->stock_quantity }}</td>
            <td>
                @foreach($part->fitments as $f)
                    {{ $f->specification->vehicleModel->name ?? '' }} ({{ $f->start_year }}){{ !$loop->last ? ', ' : '' }}
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>