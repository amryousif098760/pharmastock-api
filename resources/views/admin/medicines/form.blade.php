@extends('admin.layout')

@section('title', $mode === 'create' ? 'Create Medicine' : 'Edit Medicine')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">{{ $mode === 'create' ? 'Create Medicine' : 'Edit Medicine' }}</div>
            <div class="text-muted">Manage pricing, stock, and display properties.</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.medicines.index') }}">Back</a>
    </div>

    <div class="card card-soft">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-1">Please fix the errors.</div>
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ $mode === 'create' ? route('admin.medicines.store') : route('admin.medicines.update', $row->id) }}" class="row g-3">
                @csrf
                @if($mode !== 'create')
                    @method('PUT')
                @endif

                <div class="col-md-6">
                    <label class="form-label">Warehouse</label>
                    <select name="warehouse_id" class="form-select" required>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" @selected(old('warehouse_id', $row->warehouse_id ?? '') == $w->id)>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">None</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $row->category_id ?? '') == $c->id)>{{ $c->name_en ?: $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-7">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $row->name ?? '') }}" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Image URL</label>
                    <input name="image_url" class="form-control" value="{{ old('image_url', $row->image_url ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input name="price" type="number" step="0.01" class="form-control" value="{{ old('price', $row->price ?? 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantity</label>
                    <input name="qty" type="number" class="form-control" value="{{ old('qty', $row->qty ?? 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Featured</label>
                    <select name="is_featured" class="form-select">
                        <option value="0" @selected((int)old('is_featured', $row->is_featured ?? 0) === 0)>No</option>
                        <option value="1" @selected((int)old('is_featured', $row->is_featured ?? 0) === 1)>Yes</option>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Save</button>
                    @if($mode !== 'create')
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($mode !== 'create')
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title fw-bold">Delete Medicine</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">This action cannot be undone.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="post" action="{{ route('admin.medicines.destroy', $row->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
