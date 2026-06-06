@extends('POSViews.POSAdminViews.app')

@section('title', 'Create Discount')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Discounts/create.css') }}">
@endpush

@section('content')
<main class="main-wrap">
        <h1 class="page-title">Create Discount</h1>
        {{-- <div class="page-subtitle">Add discount by item or category</div> --}}

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-3">
                <strong>Please check the form.</strong>
            </div>
        @endif

        <div class="form-card">
            {{-- <div class="form-card-head">
                <h2>Discount Form</h2>
            </div> --}}

            <div class="form-card-body">
                <form action="{{ route('discounts.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="discount_type" class="form-label">Discount Target</label>
                            <select name="discount_type" id="discount_type" class="form-select-custom" required>
                                <option value="item" {{ old('discount_type', 'item') === 'item' ? 'selected' : '' }}>Item</option>
                                <option value="category" {{ old('discount_type') === 'category' ? 'selected' : '' }}>Category</option>
                            </select>
                        </div>

                        <div class="form-group full" id="itemSearchGroup">
                            <label class="form-label">Search Item by ID or Name</label>

                            <div class="search-select-wrap">
                                <input
                                    type="text"
                                    id="itemSearchInput"
                                    class="search-select-input"
                                    placeholder="Click to show all items, then search by code or name..."
                                    autocomplete="off"
                                >
                                <div class="search-dropdown" id="itemSearchDropdown"></div>
                            </div>

                            <input type="hidden" name="item_id" id="item_id" value="{{ old('item_id') }}">

                            @error('item_id')
                                <div class="error-text">{{ $message }}</div>
                            @enderror

                            <div class="product-preview" id="productPreview" style="display:none;">
                                <div class="product-preview-thumb" id="previewThumb">
                                    <div class="product-preview-fallback">
                                        <i class="bi bi-image"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="product-preview-name" id="previewName">-</div>
                                    <div class="product-preview-sub" id="previewSub">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group full hidden" id="categoryGroup">
                            <label for="category_code" class="form-label">Category</label>
                            <select name="category_code" id="category_code" class="form-select-custom">
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category_code') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_code')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

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
                                value="{{ old('discount_amount') }}"
                                placeholder="Enter discount percent (0-100)"
                                required
                            >
                            <div class="input-note">Example: 5 means 5% discount.</div>
                            @error('discount_amount')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="schedule_type" class="form-label">Duration</label>
                            <select name="schedule_type" id="schedule_type" class="form-select-custom" required>
                                <option value="forever" {{ old('schedule_type', 'forever') === 'forever' ? 'selected' : '' }}>Forever</option>
                                <option value="scheduled" {{ old('schedule_type') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
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
                                value="{{ old('discount_start_date') }}"
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
                                value="{{ old('discount_end_date') }}"
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
                            <i class="bi bi-check2-circle"></i>
                            Save Discount
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
        const discountType = document.getElementById('discount_type');
        const itemSearchGroup = document.getElementById('itemSearchGroup');
        const categoryGroup = document.getElementById('categoryGroup');

        const itemSearchInput = document.getElementById('itemSearchInput');
        const itemSearchDropdown = document.getElementById('itemSearchDropdown');
        const itemIdInput = document.getElementById('item_id');

        const productPreview = document.getElementById('productPreview');
        const previewThumb = document.getElementById('previewThumb');
        const previewName = document.getElementById('previewName');
        const previewSub = document.getElementById('previewSub');
        const scheduleType = document.getElementById('schedule_type');
        const startDateInput = document.getElementById('discount_start_date');
        const endDateInput = document.getElementById('discount_end_date');

        const items = {!! $discountItemsJson !!};

        function toggleTarget() {
            const type = discountType.value;

            if (type === 'category') {
                itemSearchGroup.classList.add('hidden');
                categoryGroup.classList.remove('hidden');
            } else {
                itemSearchGroup.classList.remove('hidden');
                categoryGroup.classList.add('hidden');
            }
        }

        function escapeHtml(text) {
            return String(text ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function renderDropdown(list) {
            if (!list.length) {
                itemSearchDropdown.innerHTML = `
                    <div class="search-option">
                        <div class="search-option-title">No item found</div>
                    </div>
                `;
                itemSearchDropdown.style.display = 'block';
                return;
            }

            itemSearchDropdown.innerHTML = list.map(item => `
                <div class="search-option"
                     data-id="${item.id}"
                     data-name="${escapeHtml(item.name)}"
                     data-number="${escapeHtml(item.number)}"
                     data-category="${escapeHtml(item.category)}"
                     data-image="${escapeHtml(item.image)}">
                    <div class="search-option-title">${escapeHtml(item.name)}</div>
                    <div class="search-option-sub">Code: ${escapeHtml(item.number)} | Category: ${escapeHtml(item.category)}</div>
                </div>
            `).join('');

            itemSearchDropdown.style.display = 'block';
        }

        function updatePreview(item) {
            if (!item) {
                productPreview.style.display = 'none';
                previewName.textContent = '-';
                previewSub.textContent = '-';
                previewThumb.innerHTML = `
                    <div class="product-preview-fallback">
                        <i class="bi bi-image"></i>
                    </div>
                `;
                return;
            }

            previewName.textContent = item.name || 'No Name';
            previewSub.textContent = `Code: ${item.number || '-'} | Category: ${item.category || '-'}`;
            productPreview.style.display = 'flex';

            if (item.image) {
                previewThumb.innerHTML = `
                    <img src="${item.image}" alt="Product"
                         onerror="this.remove(); document.getElementById('previewThumb').innerHTML = '<div class=&quot;product-preview-fallback&quot;><i class=&quot;bi bi-image&quot;></i></div>';">
                `;
            } else {
                previewThumb.innerHTML = `
                    <div class="product-preview-fallback">
                        <i class="bi bi-image"></i>
                    </div>
                `;
            }
        }

        function searchItems(keyword) {
            const q = keyword.trim().toLowerCase();

            if (!q) {
                renderDropdown(items.slice(0, 50));
                return;
            }

            const filtered = items.filter(item => {
                return (
                    String(item.name).toLowerCase().includes(q) ||
                    String(item.number).toLowerCase().includes(q) ||
                    String(item.id).toLowerCase().includes(q)
                );
            }).slice(0, 50);

            renderDropdown(filtered);
        }

        itemSearchInput.addEventListener('focus', function () {
            renderDropdown(items.slice(0, 50));
        });

        itemSearchInput.addEventListener('click', function () {
            renderDropdown(items.slice(0, 50));
        });

        itemSearchInput.addEventListener('input', function () {
            itemIdInput.value = '';
            updatePreview(null);
            searchItems(this.value);
        });

        document.addEventListener('click', function (e) {
            if (!itemSearchInput.contains(e.target) && !itemSearchDropdown.contains(e.target)) {
                itemSearchDropdown.style.display = 'none';
            }
        });

        itemSearchDropdown.addEventListener('click', function (e) {
            const option = e.target.closest('.search-option');
            if (!option || !option.dataset.id) return;

            const selectedItem = {
                id: option.dataset.id,
                name: option.dataset.name,
                number: option.dataset.number,
                category: option.dataset.category,
                image: option.dataset.image,
            };

            itemIdInput.value = selectedItem.id;
            itemSearchInput.value = `${selectedItem.name} (${selectedItem.number})`;
            itemSearchDropdown.style.display = 'none';
            updatePreview(selectedItem);
        });

        function setOldItem() {
            const oldId = itemIdInput.value;
            if (!oldId) return;

            const selected = items.find(item => String(item.id) === String(oldId));
            if (!selected) return;

            itemSearchInput.value = `${selected.name} (${selected.number})`;
            updatePreview(selected);
        }

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

        discountType.addEventListener('change', toggleTarget);
        scheduleType.addEventListener('change', toggleScheduleDates);

        toggleTarget();
        toggleScheduleDates();
        setOldItem();
    });
</script>
@endpush
