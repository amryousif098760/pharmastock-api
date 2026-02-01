@extends('admin.layout')

@section('title', $mode === 'create' ? 'Create Category' : 'Edit Category')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">{{ $mode === 'create' ? 'Create Category' : 'Edit Category' }}</div>
            <div class="text-muted">Manage home categories.</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}"><i class="bi bi-arrow-left"></i>Back</a>
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
            <form method="POST" action="{{ $mode === 'create' ? route('admin.categories.store') : route('admin.categories.update', $row->id) }}" class="row g-3">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Name (Default)</label>
                    <input class="form-control" name="name" value="{{ old('name', $row->name ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Name (AR)</label>
                    <input class="form-control" name="name_ar" value="{{ old('name_ar', $row->name_ar ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Name (EN)</label>
                    <input class="form-control" name="name_en" value="{{ old('name_en', $row->name_en ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Icon URL</label>
                    <input class="form-control" name="icon_url" value="{{ old('icon_url', $row->icon_url ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sort</label>
                    <input class="form-control" name="sort_order" type="number" value="{{ old('sort_order', $row->sort_order ?? 0) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Active</label>
                    <select class="form-select" name="is_active">
                        <option value="1" @selected((int)old('is_active', $row->is_active ?? 1) === 1)>Yes</option>
                        <option value="0" @selected((int)old('is_active', $row->is_active ?? 1) === 0)>No</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle"></i>Save</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
