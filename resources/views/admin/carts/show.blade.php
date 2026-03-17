@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.carts.index') }}" class="btn btn-link text-dark p-0">
            <i class="fa fa-arrow-left mr-1"></i> Back to Carts
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <h4 class="font-weight-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-1"><i class="fa fa-envelope mr-2"></i>{{ $user->email }}</p>
                    <p class="text-muted mb-3"><i class="fa fa-phone mr-2"></i>{{ $user->phone ?? 'No phone provided' }}</p>
                    
                    <a href="tel:{{ $user->phone }}" class="btn btn-success btn-block rounded-pill">
                        <i class="fa fa-phone-alt mr-2"></i> Call to Recover
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Items in Cart</h5>
                    <span class="badge badge-warning">{{ $items->count() }} items</span>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Part Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold">{{ $item->name }}</span><br>
                                        <small class="text-muted">SKU/ID: {{ $item->id }}</small>
                                    </td>
                                    <td class="text-center align-middle">{{ $item->qty }}</td>
                                    <td class="text-right align-middle">{{ number_format($item->price, 0) }} RWF</td>
                                    <td class="text-right align-middle">
                                        <strong>{{ number_format($item->price * $item->qty, 0) }} RWF</strong>
                                    </td>
                                </tr>
                                @php $grandTotal += ($item->price * $item->qty); @endphp
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Cart is empty</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total Estimated Value:</td>
                                <td class="text-right text-primary h5 font-weight-bold">
                                    {{ number_format($grandTotal, 0) }} RWF
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-light rounded border-left border-warning">
                <small class="text-muted">
                    <i class="fa fa-info-circle mr-1"></i> 
                    These items are currently saved in the user's browser/account. They have not been converted to an order yet. 
                    Calling the user now to offer a discount or confirm compatibility is highly recommended for conversion.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection