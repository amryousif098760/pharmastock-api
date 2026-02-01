@extends('admin.layout')

@section('title','Warehouses')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Warehouses</div>
            <div class="text-muted">Inventory sources.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.warehouses.create') }}"><i class="bi bi-plus-lg me-1"></i>Create</a>
    </div>

    <div class="card cardx">
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET">
                <div class="col-md-5">
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th class="text-nowrap">Lat/Lng</th>
                        <th>Address</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        <tr>
                            <td class="fw-semibold">{{ $r->id }}</td>
                            <td>{{ $r->name }}</td>
                            <td class="text-nowrap"><span class="badge text-bg-secondary">{{ number_format((float)$r->lat,5) }}, {{ number_format((float)$r->lng,5) }}</span></td>
                            <td class="text-truncate" style="max-width: 360px">{{ $r->address_text }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.warehouses.edit',$r) }}"><i class="bi bi-pencil"></i></a>
                                <form class="d-inline" method="POST" action="{{ route('admin.warehouses.destroy',$r) }}" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{ $rows->links() }}
        </div>
    </div>
@endsection
