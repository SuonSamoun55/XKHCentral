@extends('POSViews.POSAdminViews.app')

@section('title', 'Edit Discount')

@push('styles')
<style>
    :root{
        --primary:#1bb8c9;
        --primary-dark:#12a5b5;
        --bg:#f5f6f8;
        --white:#ffffff;
        --text:#202938;
        --muted:#8b95a7;
        --border:#eceef2;
        --danger:#ef4444;
        --success:#10b981;
        --shadow:0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .containter{
        flex:1;
        min-width:0;
        width:100%;
        height:100vh;
        display:flex;
    }

    .main-wrap,
    .main-wrap *{
        box-sizing:border-box;
        background: white;
        border-radius:15px  ; 
    }

    .main-wrap{
        flex:1;
        width:100%;
        max-width:100%;
        min-width:0;
        min-height:100vh;
        height:100vh;
        overflow-y:auto;
        overflow-x:hidden;
        padding:28px 26px 30px;
    }

    .page-title{
        font-size:24px;
        font-weight:700;
        color:#34a6b5;
        margin-bottom:6px;
    }

    .page-subtitle{
        font-size:13px;
        color:#8b95a7;
        margin-bottom:22px;
    }

    .form-card{
        width:100%;
        background:#fff;
        border:1px solid var(--border);
        border-radius:18px;
        box-shadow:var(--shadow);
        overflow:hidden;
    }

    .form-card-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:18px 20px;
        border-bottom:1px solid #eef1f4;
        background:#fcfdfd;
    }

    .form-card-head h2{
        margin:0;
        font-size:18px;
        font-weight:700;
        color:#1f2937;
    }

    .form-card-body{
        padding:22px 20px;
    }

    .item-preview{
        margin-bottom:18px;
        padding:12px;
        border:1px solid #eef1f4;
        border-radius:12px;
        background:#fbfdff;
        font-size:13px;
        color:#475569;
    }

    .form-grid{
        display:grid;
        grid-template-columns:repeat(2, minmax(0,1fr));
        gap:18px;
    }

    .form-group{
        display:flex;
        flex-direction:column;
        gap:8px;
    }

    .form-label{
        font-size:13px;
        font-weight:700;
        color:#344054;
        margin:0;
    }

    .form-control-custom{
        width:100%;
        height:44px;
        border:1px solid #dfe3e8;
        border-radius:12px;
        background:#fff;
        padding:0 14px;
        outline:none;
        font-size:13px;
        color:#202938;
        transition:.2s ease;
    }

    .form-control-custom:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 3px rgba(27,184,201,.10);
    }

    .error-text{
        font-size:12px;
        color:var(--danger);
        margin-top:2px;
    }

    .action-row{
        display:flex;
        justify-content:flex-end;
        gap:10px;
        flex-wrap:wrap;
        margin-top:24px;
    }

    .btn-main,
    .btn-light-main{
        border:none;
        height:42px;
        padding:0 18px;
        border-radius:10px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        font-size:13px;
        font-weight:600;
        text-decoration:none;
        transition:.2s ease;
        cursor:pointer;
    }

    .btn-main{
        background:var(--primary);
        color:#fff;
        box-shadow:0 6px 16px rgba(27,184,201,.25);
    }

    .btn-main:hover{
        background:var(--primary-dark);
        color:#fff;
    }

    .btn-light-main{
        background:#fff;
        color:#5f6b7a;
        border:1px solid #dfe3e8;
    }

    .btn-light-main:hover{
        border-color:var(--primary);
        color:var(--primary);
    }

    @media (max-width: 992px){
        .form-grid{
            grid-template-columns:1fr;
        }
    }
</style>
@endpush

@section('content')
<main class="main-wrap">
    <h1 class="page-title">Edit Discount</h1>
    {{-- <div class="page-subtitle">Update discount value and date range</div> --}}

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-3">
            <strong>Please check the form.</strong>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-head">
            <h2>Discount Form</h2>
        </div>
        <div class="form-card-body">
            <div class="item-preview">
                <strong>{{ $item->display_name ?? 'No Name' }}</strong>
                <div>Code: {{ $item->number ?? '-' }} | Category: {{ $item->item_category_code ?? '-' }}</div>
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
                    <a href="{{ route('discounts.index') }}" class="btn-light-main">Back</a>
                    <button type="submit" class="btn-main">Update Discount</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
