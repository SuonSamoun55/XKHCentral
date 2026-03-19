<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
            <label class="form-label d-block">Permissions</label>

            @foreach($permissions as $permission)
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
                        {{ $permission->name }}
                        @if($permission->display_name)
                            - {{ $permission->display_name }}
                        @endif
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
