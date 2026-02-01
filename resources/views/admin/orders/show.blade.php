@extends('admin.layout')

@section('title','Order #'.$order->id)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Order #{{ $order->id }}</div>
            <div class="text-muted">Details and line items.</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Back</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card card-soft">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted">Status</div>
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $order->status }}</span>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted">Total</div>
                        <div class="fw-black">{{ number_format((float)$order->total,2) }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <div class="text-muted">Created</div>
                        <div class="fw-semibold">{{ $order->created_at }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-body">
                    <div class="h6 fw-black mb-3">Items</div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Medicine</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Line</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $it)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $it->medicine?->name ?? ('#'.$it->medicine_id) }}</div>
                                        <div class="text-muted small">Medicine ID: {{ $it->medicine_id }}</div>
                                    </td>
                                    <td class="text-end fw-semibold">{{ (int)$it->qty }}</td>
                                    <td class="text-end">{{ number_format((float)$it->price,2) }}</td>
                                    <td class="text-end fw-black">{{ number_format((float)$it->line_total,2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
