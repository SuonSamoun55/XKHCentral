@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Permission List')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Permission List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">Create Permission</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Display Name</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->display_name }}</td>
                    <td>
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this permission?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No permissions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection