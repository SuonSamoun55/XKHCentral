@extends('Layout.POSAdmin.app')
@section('title', 'Edit Discount')
@section('backUrl', route('discounts.index'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Discounts/edit.css') }}">
@endpush

@section('content')
<main class="main-wrap">
    <h1 class="page-title">Edit Discount</h1>
    <div class="alert-container" id="alertContainer"></div>

    <div class="form-card">
        <div class="form-card-head">
            <h2>Discount Form</h2>
        </div>
        <div class="form-card-body">
            <div class="item-preview">
                <div class="item-preview-thumb">
                    @if($item->custom_image_url || $item->image_url)
                        <img src="{{ $item->custom_image_url ?? $item->image_url }}" alt="{{ $item->display_name }}"
                             onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
                    @endif
                    <div class="item-preview-fallback" style="{{ ($item->custom_image_url || $item->image_url) ? 'display:none;' : '' }}">
                        <i class="bi bi-image"></i>
                    </div>
                </div>
                <div>
                    <strong>{{ $item->display_name ?: 'No Name' }}</strong>
                    <div>Code: {{ $item->number ?: '-' }} | Category: {{ $item->item_category_code ?: '-' }}</div>
                </div>
            </div>

            <form action="{{ route('discounts.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="discount_amount" class="form-label">Discount Percentage (%)</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="discount_amount"
                            id="discount_amount"
                            class="form-control-custom"
                            value="{{ old('discount_amount', $item->discount_amount) }}"
                            required
                        >
                        @error('discount_amount')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="schedule_type" class="form-label">Duration</label>
                        <select name="schedule_type" id="schedule_type" class="form-control-custom" required>
                            <option value="forever" {{ old('schedule_type', $scheduleType) === 'forever' ? 'selected' : '' }}>Forever</option>
                            <option value="scheduled" {{ old('schedule_type', $scheduleType) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                        @error('schedule_type')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="discount_start_date" class="form-label">Start Date</label>
                        <input
                            type="date"
                            name="discount_start_date"
                            id="discount_start_date"
                            class="form-control-custom"
                            value="{{ old('discount_start_date', optional($item->discount_start_date)->format('Y-m-d')) }}"
                        >
                        @error('discount_start_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="discount_end_date" class="form-label">End Date</label>
                        <input
                            type="date"
                            name="discount_end_date"
                            id="discount_end_date"
                            class="form-control-custom"
                            value="{{ old('discount_end_date', optional($item->discount_end_date)->format('Y-m-d')) }}"
                        >
                        @error('discount_end_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="action-row">
                    <a href="{{ route('discounts.index') }}" class="btn-light-main">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>
                    <button type="submit" class="btn-main">
                        <i class=""></i>
                        Update Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const alertContainer = document.getElementById('alertContainer');

    function showAlert(message, type = 'success') {
        if (!alertContainer) return;
        const el = document.createElement('div');
        el.className = `custom-alert alert-${type}`;
        el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i><span>${message}</span>`;
        alertContainer.appendChild(el);
        setTimeout(() => {
            el.classList.add('fade-out');
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }

    @if ($errors->any())
        showAlert('Please check the form.', 'danger');
    @endif

    const scheduleType = document.getElementById('schedule_type');
    const startDateInput = document.getElementById('discount_start_date');
    const endDateInput = document.getElementById('discount_end_date');

    function toggleScheduleDates() {
        const isScheduled = scheduleType.value === 'scheduled';
        startDateInput.required = isScheduled;
        endDateInput.required = isScheduled;
        startDateInput.disabled = !isScheduled;
        endDateInput.disabled = !isScheduled;

        if (!isScheduled) {
            startDateInput.value = '';
            endDateInput.value = '';
        }
    }

    scheduleType.addEventListener('change', toggleScheduleDates);
    toggleScheduleDates();
});
</script>
@endpush
