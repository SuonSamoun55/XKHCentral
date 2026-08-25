@extends('Layout.Management.app')
@section('title', 'Edit Page')

@section('content')
<div class="container mt-4">
    <h2>Edit Page</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Page Key</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
        </div>

        @if($permission->urls)
            <div class="mb-3">
                <div class="field-label">URL(s) this key gates</div>
                <div class="form-control-plaintext text-muted"><code>{{ $permission->urls }}</code></div>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Page Label</label>
            <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $permission->display_name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Side</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="group" id="groupAdmin" value="admin" {{ old('group', $permission->group) === 'admin' ? 'checked' : '' }}>
                <label class="form-check-label" for="groupAdmin">Admin Side</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="group" id="groupCustomer" value="customer" {{ old('group', $permission->group) === 'customer' ? 'checked' : '' }}>
                <label class="form-check-label" for="groupCustomer">User Side</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
