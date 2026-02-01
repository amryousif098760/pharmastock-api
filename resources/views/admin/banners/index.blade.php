@extends('admin.layout')

@section('title','Banners')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">Banners</div>
            <div class="text-muted">Home sliders and announcements.</div>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.banners.create') }}"><i class="bi bi-plus-lg me-1"></i>New Banner</a>
    </div>

    <form class="d-flex gap-2 mb-3" method="GET" action="{{ route('admin.banners.index') }}">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search">
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <div class="card cardx">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:88px">Image</th>
                    <th>Title</th>
                    <th>Action</th>
                    <th style="width:120px">Active</th>
                    <th style="width:160px"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $b)
                    <tr>
                        <td>
                            <div class="rounded-3 overflow-hidden" style="width:72px;height:44px;background:rgba(0,0,0,.06)">
                                @if($b->image_url)
                                    <img src="{{ $b->image_url }}" style="width:100%;height:100%;object-fit:cover">
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $b->title }}</div>
                            <div class="small text-muted">{{ $b->subtitle }}</div>
                        </td>
                        <td class="small">
                            <span class="badge text-bg-light">{{ $b->action_type ?: 'none' }}</span>
                            <span class="text-muted">{{ $b->action_value }}</span>
                        </td>
                        <td>
                            @if((int)($b->is_active ?? 1) === 1)
                                <span class="badge text-bg-success">Yes</span>
                            @else
                                <span class="badge text-bg-secondary">No</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.banners.edit',$b) }}"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.banners.destroy',$b) }}" onsubmit="return confirm('Delete?')">
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
    </div>

    <div class="mt-3">{{ $rows->links() }}</div>
@endsection
