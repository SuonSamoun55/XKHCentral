@extends('Layout.POSAdmin.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/TaxGroup/TaxList.css') }}">
@endpush

@section('title', 'VAT Posting Setup')

@section('content')
    <div class="pagelist-page">

        <div class="alert-container">
            <div id="syncAlertBox"></div>
        </div>
        <h1>VAT Posting Setup</h1>
        <div class="page-head">
            <button id="syncBtn" type="button" class="btn btn-primary" onclick="syncVatPostingSetup()">
                Sync from BC
                <i class="bi bi-arrow-repeat"></i>
            </button>
        </div>

        <div class="table-card">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>VAT Bus. Posting Group</th>
                            <th>VAT Prod. Posting Group</th>
                            <th>Description</th>
                            <th>VAT %</th>
                            <th>VAT Calculation Type</th>
                            <th>VAT Identifier</th>
                            <th>Blocked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($setups as $s)
                            <tr>
                                <td class="vat-bus-cell" data-label="VAT Bus. Posting Group">
                                    {{ $s->vat_bus_posting_group ?: '—' }}</td>
                                <td class="vat-prod-cell" data-label="VAT Prod. Posting Group">
                                    <span class="vat-prod-pill">{{ $s->vat_prod_posting_group ?: '—' }}</span></td>
                                <td class="description-cell" data-label="Description">{{ $s->description ?: '—' }}</td>
                                <td class="vat-pct-cell" data-label="VAT %">{{ number_format((float) $s->vat_pct, 2) }}</td>
                                <td class="calc-type-cell" data-label="VAT Calculation Type">{{ $s->vat_calculation_type ?: '—' }}</td>
                                <td class="identifier-cell" data-label="VAT Identifier">{{ $s->vat_identifier ?: '—' }}</td>
                                <td class="blocked-cell" data-label="Blocked">
                                    <span class="blocked-badge {{ $s->blocked ? 'is-blocked' : 'is-active' }}">
                                        {{ $s->blocked ? 'Blocked' : 'Active' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7">No VAT Posting Setup yet — click "Sync from BC" to pull it in.</td>
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
        function showSyncAlert(type, message) {
            const box = document.getElementById('syncAlertBox');
            if (!box) return;

            box.innerHTML = `
                <div class="custom-alert ${type === 'success' ? 'alert-success' : 'alert-danger'}">
                    <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}"></i>
                    <span>${message}</span>
                </div>
            `;

            const alertEl = box.querySelector('.custom-alert');
            setTimeout(() => {
                alertEl.style.animation = 'pageListFadeOut 0.5s ease-in forwards';
                alertEl.addEventListener('animationend', () => alertEl.remove());
            }, 4000);
        }

        async function syncVatPostingSetup() {
            const btn = document.getElementById('syncBtn');
            const oldHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = 'Syncing... <i class="bi bi-arrow-repeat"></i>';

            try {
                const res = await fetch('{{ route('vat-posting-setup.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                let data = null;
                try {
                    data = await res.json();
                } catch (_) {
                    data = null;
                }

                if (!res.ok) {
                    throw new Error(data?.message || 'Sync failed.');
                }

                showSyncAlert('success', `${data?.count ?? 0} VAT Posting Setup row(s) synced.`);
                setTimeout(() => window.location.reload(), 1200);
            } catch (error) {
                showSyncAlert('error', error?.message || 'Could not sync VAT Posting Setup.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            }
        }
    </script>
@endpush
