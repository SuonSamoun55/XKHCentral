@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/TaxGroup/TaxList.css') }}">
@endpush

@section('title', 'Tax Groups')

@section('content')
<div class="pagelist-page">

    <div class="alert-container">
        @if (session('success'))
            <div class="custom-alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="custom-alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>
    <h1>Tax Groups</h1>
    <div class="page-head">

        <a href="{{ route('tax-groups.create') }}" class="btn btn-primary">
            Add Tax Group
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>

    <div class="table-card">
        <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Code</th>
                    <th>Display Name</th>
                    <th>Percent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($taxGroups as $taxGroup)
                    <tr>
                        <td class="id-cell" data-label="No.">{{ $loop->iteration }}</td>
                        <td class="key-cell" data-label="Code"><code>{{ $taxGroup->code }}</code></td>
                        <td class="label-cell" data-label="Display Name">{{ $taxGroup->display_name ?? '—' }}</td>
                        <td class="url-cell" data-label="Percent">
                            <div class="url-list"><span class="url-chip mono">{{ rtrim(rtrim(number_format($taxGroup->percent, 2), '0'), '.') }}%</span></div>
                        </td>
                        <td class="actions-cell" data-label="Action">
                            <div class="action-group">
                                <a href="{{ route('tax-groups.edit', $taxGroup->id) }}" class="pill-btn edit">Edit</a>
                                <form action="{{ route('tax-groups.destroy', $taxGroup->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this tax group? Items already tagged with this code will keep showing 0% until you add it again.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">No tax groups yet. Add one for each code Business Central sends (e.g. <code>GST15</code>).</td>
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
            const alerts = document.querySelectorAll('.pagelist-page .custom-alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.animation = 'pageListFadeOut 0.5s ease-in forwards';
                    alert.addEventListener('animationend', function () { alert.remove(); });
                }, 4000);
            });
        });
    </script>
@endpush
