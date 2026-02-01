@extends('admin.layout')

@section('title','Medicines')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Medicines</div>
            <div class="text-muted">Manage stock per warehouse and highlight featured items.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.medicines.create') }}">
            <i class="bi bi-plus-lg me-2"></i>New Medicine
        </a>
    </div>

    <form class="row g-2 mb-3" method="get" action="{{ route('admin.medicines.index') }}">
        <div class="col-12 col-md-4">
            <input class="form-control" name="q" value="{{ $q }}" placeholder="Search name">
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select" name="warehouse">
                <option value="">All warehouses</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" @selected((string)$w->id === (string)$warehouseId)>{{ $w->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select" name="category">
                <option value="">All categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected((string)$c->id === (string)$categoryId)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2 d-grid">
            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search me-2"></i>Filter</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Warehouse</th>
                        <th>Category</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Qty</th>
                        <th class="text-center">Featured</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $m)
                        <tr>
                            <td class="text-muted">{{ $m->id }}</td>
                            <td class="fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="thumb" style="background-image:url('{{ $m->image_url }}')"></div>
                                    <div>
                                        <div>{{ $m->name }}</div>
                                        <div class="text-muted small">{{ $m->image_url }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $m->warehouse?->name }}</td>
                            <td>{{ $m->category?->name }}</td>
                            <td class="text-end">{{ number_format((float)$m->price,2) }}</td>
                            <td class="text-end">{{ (int)$m->qty }}</td>
                            <td class="text-center">
                                @if((int)$m->is_featured === 1)
                                    <span class="badge text-bg-success">Yes</span>
                                @else
                                    <span class="badge text-bg-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.medicines.edit',$m) }}"><i class="bi bi-pencil"></i></a>
                                <form class="d-inline" method="post" action="{{ route('admin.medicines.destroy',$m) }}" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No medicines</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $rows->links() }}
    </div>
@endsection
