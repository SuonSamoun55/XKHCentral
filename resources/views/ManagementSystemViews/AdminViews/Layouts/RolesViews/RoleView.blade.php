@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Role List')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Role List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create Role</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Display Name</th>
                <th>Permissions</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->display_name }}</td>
                    <td>
                        @forelse($role->permissions as $permission)
                            <span class="badge bg-success me-1">{{ $permission->name }}</span>
                        @empty
                            <span class="text-muted">No permissions</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this role?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
