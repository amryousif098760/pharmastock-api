@extends('admin.layout')

@section('title','Orders')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Orders</div>
            <div class="text-muted">Track pharmacy requests and stock movements.</div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-2" method="get" action="{{ route('admin.orders.index') }}">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" name="q" value="{{ $q }}" placeholder="Search id, email, warehouse">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All status</option>
                        @foreach(['new','processing','shipped','delivered','canceled'] as $s)
                            <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-muted">
                        <th class="ps-3">#</th>
                        <th>Pharmacy</th>
                        <th>Warehouse</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created</th>
                        <th class="text-end pe-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $o)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $o->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $o->user?->name ?? '—' }}</div>
                                <div class="text-muted small">{{ $o->user?->email ?? '—' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $o->warehouse?->name ?? '—' }}</div>
                                <div class="text-muted small">{{ $o->warehouse?->address_text ?? '' }}</div>
                            </td>
                            <td><span class="badge bg-soft">{{ $o->status }}</span></td>
                            <td class="fw-bold">{{ number_format((float)$o->total, 2) }}</td>
                            <td class="text-muted">{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end pe-3">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $o) }}">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">No orders</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">{{ $rows->links() }}</div>
    </div>
@endsection
