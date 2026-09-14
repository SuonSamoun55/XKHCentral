@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/Role/role_list.css') }}">
@endpush

@section('title', 'Role List')

@section('content')
<div class="role-list-page">

    <div class="alert-container">
        @if (session('success'))
            <div class="custom-alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

    <div class="page-head">
        <h1>Role List</h1>
    </div>

    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Create Role
    </a>

    <div class="table-card">
        <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Pages Accessible</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td class="id-cell" data-label="No.">{{ $loop->iteration }}</td>
                        <td class="key-cell" data-label="Name"><code>{{ $role->name }}</code></td>
                        <td class="label-cell" data-label="Display Name">
                            {{ $role->display_name }}
                            @if ($role->is_cross_company)
                                <span class="page-chip" style="background:#eef2ff;color:#4338ca;">Cross-Company</span>
                            @endif
                        </td>
                        <td class="pages-cell" data-label="Pages Accessible">
                            @if ($role->permissions->isNotEmpty())
                                <div class="chip-list">
                                    @foreach ($role->permissions as $permission)
                                        <span class="page-chip">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="no-pages">No permissions</span>
                            @endif
                        </td>
                        <td class="actions-cell" data-label="Action">
                            <div class="action-group">
                                <a href="{{ route('roles.edit', $role->id) }}" class="pill-btn edit">Edit</a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.role-list-page .custom-alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.animation = 'roleListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function () { alert.remove(); });
                }, 4000);
            });
        });
    </script>
@endpush
