@extends('Layout.POSAdmin.app')
@section('title', 'Update Product Images')
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/views/POSViews/POSAdminViews/StoreManagement/StoreEdite.css') }}">
@endpush
@section('content')
<main class="pim-page">
    <div class="pim-crumb">
        <a href="{{ url()->previous() }}" class="pim-back">
            <i class="bi bi-chevron-left"></i>
        </a>
        <div>
            <h1 class="pim-title">{{ $item->display_name }}</h1>
            <div class="pim-sku">Item #{{ $item->number }}</div>
        </div>

        <button type="button" class="pim-btn pim-mark-btn {{ $isUpdated ? 'is-done' : '' }}" id="markUpdatedBtn" onclick="markAsUpdated()">
            <i class="bi bi-check2-circle"></i>
            <span id="markUpdatedText">{{ $isUpdated ? 'Marked as Updated' : 'Mark as Updated' }}</span>
        </button>
    </div>

    <div class="pim-scroll">

    <!-- ================= PART 1 — MAIN PHOTO ================= -->
    <section class="pim-panel">
        <div class="pim-panel-head">
            <div>
                <div class="pim-panel-eyebrow">Part 1</div>
                <h2 class="pim-panel-title">Main Item Photo</h2>
            </div>
            <span class="pim-tag">Primary</span>
        </div>

        <div class="pim-panel-body pim-main-layout">

            <div class="pim-tile pim-tile--hero" data-target="main" onclick="document.getElementById('mainImageFile').click()">
                <div class="pim-tile-frame">
                    <img
                        id="mainItemPreview"
                        class="pim-img"
                        src="{{ $item->custom_image_url ?? ($item->image_url ? asset('storage/' . $item->image_url) : asset('images/no-image.png')) }}"
                        alt="{{ $item->display_name }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/280x200/f1f5f9/94a3b8?text=No+Photo'">
                </div>

                <div class="pim-tile-veil">
                    <i class="bi bi-camera"></i>
                    <span>Click or drop a photo</span>
                </div>

                <div class="pim-tile-loading" id="mainBar" hidden>Uploading&hellip;</div>
                <div class="pim-tile-check" id="mainCheck" hidden><i class="bi bi-check-lg"></i></div>
            </div>

            <div class="pim-main-meta">
                <input type="file" accept="image/*" id="mainImageFile" class="pim-file-input" data-target="main" hidden>

                <div class="pim-desc-block">
                    <label class="pim-desc-label" for="descriptionInput">Product Description</label>
                    <div id="descriptionAlertBox"></div>
                    <textarea id="descriptionInput" class="pim-desc-textarea" rows="4" placeholder="Describe this product for customers...">{{ $item->description }}</textarea>
                    <div class="pim-actions" style="margin-top:8px;">
                        <button type="button" class="pim-btn" id="saveDescriptionBtn">Save Description</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PART 2 — VARIANT PHOTOS ================= -->
    <section class="pim-panel">
        <div class="pim-panel-head">
            <div>
                <div class="pim-panel-eyebrow">Part 2</div>
                <h2 class="pim-panel-title">Variant Photos</h2>
            </div>
            @if($variants->count() > 0)
                <span class="pim-tag pim-tag--muted">{{ $variants->count() }} variant{{ $variants->count() === 1 ? '' : 's' }}</span>
            @endif
        </div>

        <div class="pim-panel-body">
            @if($variants->count() > 0)
                <div class="pim-variant-grid">
                    @foreach($variants as $variant)
                        <div class="pim-variant-card">

                            <div class="pim-tile pim-tile--variant" data-target="{{ $variant->id }}" onclick="document.getElementById('file-{{ $variant->id }}').click()">
                                <div class="pim-tile-frame">
                                    <img
                                        id="preview-{{ $variant->id }}"
                                        class="pim-img"
                                        src="{{ $variant->image_url ?? 'https://placehold.co/220x160/f1f5f9/94a3b8?text=No+Image' }}"
                                        alt="{{ $variant->code }}"
                                        onerror="this.onerror=null;this.src='https://placehold.co/220x160/f1f5f9/94a3b8?text=No+Image'">
                                </div>

                                <div class="pim-tile-veil pim-tile-veil--sm">
                                    <i class="bi bi-camera"></i>
                                </div>

                                <div class="pim-tile-loading pim-tile-loading--sm" id="bar-{{ $variant->id }}" hidden>Uploading&hellip;</div>
                                <div class="pim-tile-check" id="check-{{ $variant->id }}" hidden><i class="bi bi-check-lg"></i></div>
                            </div>

                            <div class="pim-variant-footer">
                                <span class="pim-code-chip">{{ $variant->code }}</span>
                            </div>

                            <input
                                type="file"
                                accept="image/*"
                                id="file-{{ $variant->id }}"
                                class="pim-file-input"
                                data-target="{{ $variant->id }}"
                                hidden>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="pim-empty">
                    <i class="bi bi-inboxes"></i>
                    <span>This item has no variants — only the main photo applies.</span>
                </div>
            @endif
        </div>
    </section>

    </div>
</main>



<script>
    const ITEM_ID = {{ $item->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Show or hide an element by id.
    function toggle(id, on) {
        document.getElementById(id).hidden = !on;
    }
    function showCheck(id) {
        const el = document.getElementById(id);
        el.hidden = false;
        setTimeout(function () {
            el.hidden = true;
        }, 1600);
    }
    function showLocalPreview(fileInput, imgEl) {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            imgEl.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    document.querySelectorAll('.pim-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const target = input.dataset.target;
            const imgEl = target === 'main'
                ? document.getElementById('mainItemPreview')
                : document.getElementById('preview-' + target);
            showLocalPreview(input, imgEl);

            if (target === 'main') {
                const nameEl = document.getElementById('mainFileName');
                if (nameEl && input.files[0]) {
                    nameEl.textContent = input.files[0].name;
                }
            }

            // No separate "Upload" button anymore — selecting (or dropping)
            // a file uploads it immediately.
            uploadImage(target);
        });
        input.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
    document.querySelectorAll('.pim-tile').forEach(function (tile) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            tile.addEventListener(evt, function (e) {
                e.preventDefault();
                tile.classList.add('pim-tile--drag');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            tile.addEventListener(evt, function (e) {
                e.preventDefault();
                tile.classList.remove('pim-tile--drag');
            });
        });
        tile.addEventListener('drop', function (e) {
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if (!file) return;

            const target = tile.dataset.target;
            const input = target === 'main'
                ? document.getElementById('mainImageFile')
                : document.getElementById('file-' + target);

            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        });
    });

    function uploadImage(target) {
        const isMain = target === 'main';

        const fileInput = document.getElementById(isMain ? 'mainImageFile' : 'file-' + target);
        const barId = isMain ? 'mainBar' : 'bar-' + target;
        const checkId = isMain ? 'mainCheck' : 'check-' + target;
        const previewEl = document.getElementById(isMain ? 'mainItemPreview' : 'preview-' + target);
        const url = isMain
            ? '/store/management/products/' + ITEM_ID + '/image'
            : '/items/variants/' + target + '/image';

        if (!fileInput.files || !fileInput.files[0]) {
            return;
        }

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);

        toggle(barId, true);
        fileInput.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: formData
        })
        .then(function (r) {
            return r.json();
        })
        .then(function (data) {
            toggle(barId, false);
            fileInput.disabled = false;

            if (data.success) {
                previewEl.src = data.image_url;
                showCheck(checkId);
            } else {
                alert('Upload failed.');
            }
        })
        .catch(function (error) {
            console.error(error);
            toggle(barId, false);
            fileInput.disabled = false;
            alert('Upload failed.');
        });
    }

    (function () {
        const saveBtn = document.getElementById('saveDescriptionBtn');
        const textarea = document.getElementById('descriptionInput');
        const alertBox = document.getElementById('descriptionAlertBox');
        if (!saveBtn || !textarea) return;

        function showDescriptionAlert(type, message) {
            if (!alertBox) return;
            alertBox.innerHTML = '<div class="pim-desc-alert" style="background:' + (type === 'success' ? '#e6f9f0' : '#fdecec') + ';color:' + (type === 'success' ? '#0f8a4d' : '#c0392b') + ';">' + message + '</div>';
            setTimeout(function () { alertBox.innerHTML = ''; }, 3500);
        }

        saveBtn.addEventListener('click', function () {
            saveBtn.disabled = true;
            const oldText = saveBtn.textContent;
            saveBtn.textContent = 'Saving...';

            fetch('/store/management/products/' + ITEM_ID + '/description', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ description: textarea.value })
            })
            .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
            .then(function (result) {
                if (!result.ok || !result.data.success) {
                    throw new Error(result.data?.message || 'Failed to save description.');
                }
                showDescriptionAlert('success', 'Description saved.');
            })
            .catch(function (error) {
                showDescriptionAlert('error', error?.message || 'Failed to save description.');
            })
            .finally(function () {
                saveBtn.disabled = false;
                saveBtn.textContent = oldText;
            });
        });
    })();

    function markAsUpdated() {
        const btn = document.getElementById('markUpdatedBtn');
        const label = document.getElementById('markUpdatedText');
        const wasDone = btn.classList.contains('is-done');

        btn.disabled = true;
        label.textContent = 'Saving...';

        fetch('/store/management/products/' + ITEM_ID + '/mark-updated', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            if (data.success) {
                if (data.is_updated) {
                    btn.classList.add('is-done');
                    label.textContent = 'Marked as Updated';
                } else {
                    btn.classList.remove('is-done');
                    label.textContent = 'Mark as Updated';
                }
            } else {
                label.textContent = wasDone ? 'Marked as Updated' : 'Mark as Updated';
                alert('Failed to save.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            label.textContent = wasDone ? 'Marked as Updated' : 'Mark as Updated';
            alert('Failed to save.');
        });
    }
</script>
@endsection
