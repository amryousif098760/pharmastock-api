@extends('admin.layout')

@section('title','Categories')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Categories</div>
            <div class="text-muted">Sections shown in Home.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus-circle me-1"></i>Create</a>
    </div>

    <div class="card cardx mb-3">
        <div class="card-body p-3">
            <form class="d-flex gap-2" method="GET" action="{{ route('admin.categories.index') }}">
                <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <div class="card cardx">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-3">Name</th>
                        <th>EN</th>
                        <th>AR</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-3 bg-body-tertiary d-flex align-items-center justify-content-center" style="width:34px;height:34px">
                                        @if($r->icon_url)
                                            <img src="{{ $r->icon_url }}" alt="" style="width:22px;height:22px;object-fit:contain">
                                        @else
                                            <i class="bi bi-grid text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="fw-semibold">{{ $r->name }}</div>
                                </div>
                            </td>
                            <td>{{ $r->name_en }}</td>
                            <td dir="rtl">{{ $r->name_ar }}</td>
                            <td>{{ (int)$r->sort_order }}</td>
                            <td>
                                @if($r->is_active)
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Disabled</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.categories.edit',$r) }}"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.categories.destroy',$r) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted py-5" colspan="6">No results</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $rows->links() }}</div>
@endsection
