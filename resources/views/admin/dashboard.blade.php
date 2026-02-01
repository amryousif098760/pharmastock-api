@extends('admin.layout')

@section('title','Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Dashboard</div>
            <div class="text-muted">Overview of your platform.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.pharmacies.index') }}"><i class="bi bi-person-check me-1"></i>Approve Pharmacies</a>
    </div>

    <div class="row g-3 mb-3">
        @php $cards = [
            ['title'=>'Total Users','value'=>$stats['users'],'icon'=>'bi-people'],
            ['title'=>'Pending Approvals','value'=>$stats['pendingApprovals'],'icon'=>'bi-hourglass-split'],
            ['title'=>'Warehouses','value'=>$stats['warehouses'],'icon'=>'bi-building'],
            ['title'=>'Medicines','value'=>$stats['medicines'],'icon'=>'bi-capsule'],
            ['title'=>'Categories','value'=>$stats['categories'],'icon'=>'bi-grid'],
            ['title'=>'Banners','value'=>$stats['banners'],'icon'=>'bi-image'],
            ['title'=>'Orders','value'=>$stats['orders'],'icon'=>'bi-receipt'],
        ]; @endphp
        @foreach($cards as $c)
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card cardx">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ $c['title'] }}</div>
                            <div class="h3 fw-black mb-0">{{ $c['value'] }}</div>
                        </div>
                        <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(13,110,253,.12)">
                            <i class="bi {{ $c['icon'] }} fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card cardx">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-black">Recent Orders</div>
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.index') }}">View all</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pharmacy</th>
                                <th>Warehouse</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentOrders as $o)
                                <tr>
                                    <td><a class="link-dark fw-bold" href="{{ route('admin.orders.show',$o->id) }}">#{{ $o->id }}</a></td>
                                    <td>{{ $o->user?->name ?? '-' }}</td>
                                    <td>{{ $o->warehouse?->name ?? '-' }}</td>
                                    <td><span class="badge text-bg-primary-subtle text-primary-emphasis">{{ $o->status }}</span></td>
                                    <td class="text-end fw-bold">{{ number_format((float)$o->total,2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card cardx">
                <div class="card-body">
                    <div class="fw-black mb-2">Quick actions</div>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('admin.banners.index') }}"><i class="bi bi-image me-1"></i>Manage Banners</a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.categories.index') }}"><i class="bi bi-grid me-1"></i>Manage Categories</a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.warehouses.index') }}"><i class="bi bi-building me-1"></i>Manage Warehouses</a>
                        <a class="btn btn-outline-primary" href="{{ route('admin.medicines.index') }}"><i class="bi bi-capsule me-1"></i>Manage Medicines</a>
                    </div>
                </div>
            </div>
            <div class="card cardx mt-3">
                <div class="card-body">
                    <div class="fw-black mb-1">Data hygiene</div>
                    <div class="text-muted small">Keep categories and banners ordered using sort order. Use active flags to hide content without deleting.</div>
                </div>
            </div>
        </div>
    </div>
@if (!empty($errors))
    <div style="background:#300; color:#fff; padding:12px; margin-bottom:12px; border-radius:8px;">
        <b>Dashboard Debug</b>
        <ul style="margin:8px 0 0 18px;">
            @foreach ($errors as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection
