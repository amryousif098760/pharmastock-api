@extends('admin.layout')

@section('title', $mode === 'create' ? 'Create Banner' : 'Edit Banner')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">{{ $mode === 'create' ? 'Create Banner' : 'Edit Banner' }}</div>
            <div class="text-muted">Configure banner appearance and action.</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.banners.index') }}"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 cardx">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card cardx">
        <div class="card-body p-4">
            <form method="POST" action="{{ $mode === 'create' ? route('admin.banners.store') : route('admin.banners.update', $banner->id) }}" class="row g-3">
                @csrf
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold">Title</label>
                    <input class="form-control" name="title" value="{{ old('title', $banner->title ?? '') }}" required>
                </div>
                <div class="col-12 col-lg-6">
                    <label class="form-label fw-semibold">Subtitle</label>
                    <input class="form-control" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Image URL</label>
                    <input class="form-control" name="image_url" value="{{ old('image_url', $banner->image_url ?? '') }}" required>
                    @if(!empty($banner?->image_url))
                        <div class="mt-2"><img src="{{ $banner->image_url }}" alt="" style="max-height:110px;border-radius:14px" onerror="this.style.display='none'"></div>
                    @endif
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">Action Type</label>
                    <select class="form-select" name="action_type">
                        @php $at = old('action_type', $banner->action_type ?? 'none'); @endphp
                        <option value="none" @selected($at==='none')>None</option>
                        <option value="category" @selected($at==='category')>Category</option>
                        <option value="medicine" @selected($at==='medicine')>Medicine</option>
                        <option value="url" @selected($at==='url')>URL</option>
                    </select>
                </div>
                <div class="col-12 col-lg-8">
                    <label class="form-label fw-semibold">Action Value</label>
                    <input class="form-control" name="action_value" value="{{ old('action_value', $banner->action_value ?? '') }}" placeholder="id or url">
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input class="form-control" name="sort_order" type="number" value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">Active</label>
                    @php $ia = (int)old('is_active', $banner->is_active ?? 1); @endphp
                    <select class="form-select" name="is_active">
                        <option value="1" @selected($ia===1)>Yes</option>
                        <option value="0" @selected($ia===0)>No</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Save</button>
                    @if($mode === 'edit')
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete banner?')">
                            @csrf
                            <button class="btn btn-outline-danger" type="submit"><i class="bi bi-trash3 me-1"></i>Delete</button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
