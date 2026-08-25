@extends('Layout.Management.app')
@section('title', 'Edit Role')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Edit Role</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Display Name</label>
            <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $role->display_name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Pages this role can access</label>

            @if($permissions->isEmpty())
                <p>No pages in database yet.</p>
            @else
                @foreach(['admin' => 'Admin Side', 'customer' => 'User Side'] as $groupKey => $groupLabel)
                    @if(($permissions[$groupKey] ?? collect())->isNotEmpty())
                        <div class="fw-semibold mt-2">{{ $groupLabel }}</div>
                        @foreach($permissions[$groupKey] as $permission)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    id="permission{{ $permission->id }}"
                                    {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                    {{ $permission->display_name ?? $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
