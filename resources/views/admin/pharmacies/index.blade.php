@extends('admin.layout')

@section('title','Pharmacies')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Pharmacies</div>
            <div class="text-muted">Approve accounts before they can order.</div>
        </div>
    </div>

    <div class="card card-surface border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-2 align-items-end mb-3" method="get" action="{{ route('admin.pharmacies.index') }}">
                <div class="col-lg-6">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ $q }}" placeholder="Name / email / phone" />
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Approval</label>
                    <select class="form-select" name="approval">
                        <option value="">All</option>
                        <option value="pending" @selected($approval==='pending')>Pending</option>
                        <option value="approved" @selected($approval==='approved')>Approved</option>
                        <option value="rejected" @selected($approval==='rejected')>Rejected</option>
                    </select>
                </div>
                <div class="col-lg-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search me-1"></i>Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.pharmacies.index') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Account</th>
                        <th>Pharmacy</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $u)
                        <tr>
                            <td class="text-muted">{{ $u->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $u->name }}</div>
                                <div class="text-muted small">{{ $u->email }}</div>
                                <div class="text-muted small">{{ $u->phone }}</div>
                            </td>
                            <td>
                                @if($u->pharmacy)
                                    <div class="fw-semibold">{{ $u->pharmacy->name }}</div>
                                    <div class="text-muted small">{{ $u->pharmacy->address_text }}</div>
                                    <div class="text-muted small">{{ $u->pharmacy->lat }}, {{ $u->pharmacy->lng }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php($st = $u->approval_status ?? 'pending')
                                @if($st === 'approved')
                                    <span class="badge text-bg-success">Approved</span>
                                @elseif($st === 'rejected')
                                    <span class="badge text-bg-danger">Rejected</span>
                                @else
                                    <span class="badge text-bg-warning">Pending</span>
                                @endif
                                @if($u->email_verified_at)
                                    <span class="badge text-bg-info ms-1">Verified</span>
                                @else
                                    <span class="badge text-bg-secondary ms-1">Unverified</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <form method="post" action="{{ route('admin.pharmacies.approve',$u->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-check2"></i></button>
                                    </form>
                                    <form method="post" action="{{ route('admin.pharmacies.reject',$u->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $rows->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
