@extends('ManagementSystemViews.AdminViews.Layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="container mt-4">
    <h2>Edit Permission</h2>

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
            <label class="form-label">Permission Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Display Name</label>
            <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $permission->display_name) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
