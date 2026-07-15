@extends('POSViews.POSAdminViews.app')

@section('title', 'Item Detail')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/POSViews/POSAdminViews/Items/show.css') }}">
@endpush

@section('content')
<main class="detail-content">
        <div class="detail-card">
            <a href="{{ url()->previous() }}" class="back-btn text-decoration-none">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="detail-grid">
                <div>
                    <div class="main-image">
                        <img
                            id="mainImage"
                            src="{{ $item['customImageUrl'] ?? url('/item-image/' . $item['id']) }}"
                            alt="{{ $item['displayName'] ?? 'Item Image' }}"
                            onerror="this.src='https://placehold.co/800x600/e5e7eb/94a3b8?text=No+Photo'">
                    </div>

                    <div class="thumb-row" id="thumbRow" style="display:none;">
                        <!-- filled by JavaScript once variants load -->
                    </div>
                </div>

                <div>
                    <div class="item-title">{{ $item['displayName'] ?? 'No Name' }}</div>
                    <div class="item-price">${{ number_format((float)($item['unitPrice'] ?? 0), 2) }}</div>

                    <div class="item-desc">
                        {{ $item['description'] ?? 'No description available for this item.' }}
                    </div>

                    <div class="section-title">Available Size / Unit of Measure</div>

                    <div class="size-row">
                        <div class="size-chip">
                            {{ $item['baseUnitOfMeasureCode'] ?? 'PCS' }}
                        </div>
                    </div>

                    <!-- Variant section, only shown if item has variants -->
                    <div id="variantSection" style="display:none;">
                        <div class="section-title">Variant</div>
                        <div class="size-row" id="variantChips"></div>
                    </div>

                    <div class="stock-box">
                        <i class="bi bi-arrow-right-circle text-info"></i>
                        Stock : <span>{{ (int)($item['inventory'] ?? 0) }} items left</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
    const ITEM_ID = "{{ $item['id'] }}";
    const DEFAULT_IMAGE = "{{ $item['customImageUrl'] ?? url('/item-image/' . $item['id']) }}";

    // Load variants for this item when the page opens
    window.addEventListener('DOMContentLoaded', function () {
        loadVariants();
    });

    function loadVariants() {
        fetch('/items/' + ITEM_ID + '/variants')
            .then(function (response) {
                return response.json();
            })
            .then(function (variants) {
                showVariants(variants);
            })
            .catch(function (error) {
                console.error('Failed to load variants', error);
            });
    }

    function showVariants(variants) {
        const thumbRow = document.getElementById('thumbRow');
        const variantSection = document.getElementById('variantSection');

        // Case 1: No variants at all — hide everything, no thumbnails shown
        if (!variants || variants.length === 0) {
            thumbRow.innerHTML = '';
            thumbRow.style.display = 'none';
            variantSection.style.display = 'none';
            return;
        }

        // Case 2: Has variants (1 or more) — show variant chips + thumbnails
        variantSection.style.display = 'block';
        thumbRow.style.display = 'flex';

        // Build the chips (RED, BLUE, etc.)
        const chipRow = document.getElementById('variantChips');
        chipRow.innerHTML = '';

        for (let i = 0; i < variants.length; i++) {
            const variant = variants[i];

            const chip = document.createElement('div');
            chip.className = 'size-chip variant-chip';
            chip.style.cursor = 'pointer';
            chip.textContent = variant.code;

            chip.addEventListener('click', function () {
                selectVariant(chip, variant);
            });

            chipRow.appendChild(chip);
        }

        // Build the thumbnails — exactly one per variant, no duplicates
        thumbRow.innerHTML = '';

        for (let i = 0; i < variants.length; i++) {
            const variant = variants[i];
            const imageSrc = variant.image_url || DEFAULT_IMAGE;

            const thumbDiv = document.createElement('div');
            thumbDiv.className = 'thumb';
            thumbDiv.style.cursor = 'pointer';

            const img = document.createElement('img');
            img.src = imageSrc;
            img.alt = variant.code;

            thumbDiv.appendChild(img);
            thumbDiv.addEventListener('click', function () {
                document.getElementById('mainImage').src = imageSrc;
            });

            thumbRow.appendChild(thumbDiv);
        }
    }

    function selectVariant(clickedChip, variant) {
        // remove "active" look from all chips
        const allChips = document.querySelectorAll('.variant-chip');
        for (let i = 0; i < allChips.length; i++) {
            allChips[i].classList.remove('variant-chip-active');
        }

        // mark this chip as active
        clickedChip.classList.add('variant-chip-active');

        // swap the main image if this variant has one, otherwise use default
        const imageUrl = variant.image_url || DEFAULT_IMAGE;
        document.getElementById('mainImage').src = imageUrl;
    }
</script>
@endsection
