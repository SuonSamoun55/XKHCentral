@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container mt-4">
    <h2>Create Role</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        {{-- ROLE NAME --}}
        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        {{-- DISPLAY NAME --}}
        <div class="mb-3">
            <label class="form-label">Display Name</label>
            <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}">
        </div>

        {{-- PERMISSIONS --}}
        <div class="mb-3">
            <label class="form-label">Permissions</label>

            @forelse($permissions as $permission)
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="permissions[]"
                        value="{{ $permission->id }}"
                        id="permission{{ $permission->id }}"
                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="permission{{ $permission->id }}">
                        {{ $permission->name }}
                        @if($permission->display_name)
                            ({{ $permission->display_name }})
                        @endif
                    </label>
                </div>
            @empty
                <p>No permissions in database yet.</p>
            @endforelse
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>

    </form>
</div>
@endsection
