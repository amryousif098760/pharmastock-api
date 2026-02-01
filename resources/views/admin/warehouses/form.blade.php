@extends('admin.layout')

@section('title', $mode === 'create' ? 'Create Warehouse' : 'Edit Warehouse')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <div class="h4 fw-black mb-0">{{ $mode === 'create' ? 'Create Warehouse' : 'Edit Warehouse' }}</div>
            <div class="text-muted">Manage warehouse details and location.</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.warehouses.index') }}"><i class="bi bi-arrow-left"></i></a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ $mode === 'create' ? route('admin.warehouses.store') : route('admin.warehouses.update',$row) }}" class="d-grid gap-3">
                @csrf
                @if($mode === 'edit') @method('PUT') @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input class="form-control" name="name" value="{{ old('name', $row->name ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Address</label>
                        <input class="form-control" name="address_text" value="{{ old('address_text', $row->address_text ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input class="form-control" name="lat" value="{{ old('lat', $row->lat ?? '') }}" inputmode="decimal">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input class="form-control" name="lng" value="{{ old('lng', $row->lng ?? '') }}" inputmode="decimal">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-save2 me-1"></i>Save</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.warehouses.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
