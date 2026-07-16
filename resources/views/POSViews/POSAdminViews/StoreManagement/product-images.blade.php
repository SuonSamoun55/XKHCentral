@extends('POSViews.POSAdminViews.app')

@section('title', 'Update Product Images')

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
                        src="{{ $item->custom_image_url ?? $item->image_url ?? url('/item-image/' . $item->bc_id) }}"
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
                <p class="pim-hint">
                    This photo is custom-pinned — it won't be overwritten the next time this item syncs from BC.
                </p>

                <input type="file" accept="image/*" id="mainImageFile" class="pim-file-input" data-target="main" hidden>

                <div class="pim-actions">
                    <button type="button" class="pim-btn" id="mainUploadBtn" onclick="uploadImage('main')">
                        Upload main photo
                    </button>
                    <span class="pim-filename" id="mainFileName">No file selected</span>
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

                            <button
                                type="button"
                                class="pim-btn pim-btn--sm pim-btn--block"
                                id="btn-{{ $variant->id }}"
                                onclick="uploadImage('{{ $variant->id }}')">
                                Upload
                            </button>
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

<style>
    /* ===================================================================
       TOKENS  —  .pim-page
       Design variables. Change a color/spacing scale here, it updates
       everywhere below.
       =================================================================== */
       .content-wrapper{
        padding: 10px 15px;
        background-color:white;
        border-radius: 18px;
       }
    .pim-page{
        --ink: #101828;
        --slate: #667085;
        --slate-soft: #98A2B3;
        --cloud: #F5F6FA;
        --paper: #FFFFFF;
        --border: #E4E7EC;
        --teal: #17B8C4;
        --teal-deep: #0E8C96;
        --teal-tint: #E3FAFB;
        --success: #22C55E;

        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        color: var(--ink);
    }

    .pim-scroll{
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 2px; /* keeps scrollbar off the card edge content */
    }
    .pim-scroll::-webkit-scrollbar{ width: 8px; height: 8px; }
    .pim-scroll::-webkit-scrollbar-thumb{ background: #cbd5e1; border-radius: 8px; }

    .pim-crumb{
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        flex-shrink: 0; /* never gets squeezed by the scroll region below it */
    }

    .pim-back{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: var(--paper);
        border: 1px solid var(--border);
        color: var(--ink);
        text-decoration: none;
        flex-shrink: 0;
    }
    .pim-back:hover{ border-color: var(--teal); color: var(--teal-deep); }

    .pim-title{
        font-size: clamp(1.15rem, 2.5vw, 1.375rem);
        font-weight: 700;
        margin: 0;
        color: var(--ink);
        word-break: break-word;
    }

    .pim-sku{
        font-size: 12px;
        font-weight: 500;
        color: var(--slate-soft);
        margin-top: 2px;
    }

    .pim-panel{
        background: var(--paper);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 18px;
        width: 100%;
    }

    .pim-panel-head{
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .pim-panel-eyebrow{
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--slate-soft);
        line-height: 1;
        margin-bottom: 3px;
    }

    .pim-panel-title{
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
    }

    .pim-tag{
        margin-left: auto;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--teal-deep);
        background: var(--teal-tint);
        padding: 4px 10px;
        border-radius: 6px;
        flex-shrink: 0;
    }
    .pim-tag--muted{
        color: var(--slate);
        background: var(--cloud);
    }

    .pim-panel-body{
        padding: 10px;
    }

    .pim-tile{
        position: relative;
        isolation: isolate;
        contain: layout paint;
        overflow: hidden;
        background: var(--cloud);
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: border-color .15s ease;
        width: 100%;
        height: auto;
    }
    .pim-tile:hover{ border-color: var(--teal); }
    .pim-tile--drag{ border-color: var(--teal); border-style: dashed; }

    .pim-tile--hero{
        max-width: 280px;
        aspect-ratio: 4 / 3;
    }
    .pim-tile--variant{
        aspect-ratio: 4 / 3;
    }

    .pim-tile-frame{
        position: absolute;
        inset: 0;
        z-index: 1;
        background: var(--cloud);
    }

    .pim-img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: var(--cloud);
    }

    .pim-tile-veil{
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: rgba(16,24,40,0.5);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        opacity: 0;
        transition: opacity .15s ease;
        text-align: center;
        padding: 8px;
    }
    .pim-tile-veil i{ font-size: 18px; }
    .pim-tile:hover .pim-tile-veil,
    .pim-tile:focus-within .pim-tile-veil{ opacity: 1; }

    .pim-tile-loading{
        position: absolute;
        inset: 0;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.85);
        color: var(--ink);
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        padding: 8px;
    }

    .pim-tile-check{
        position: absolute;
        top: 8px; right: 8px;
        z-index: 4;
        width: 22px; height: 22px;
        border-radius: 50%;
        background: var(--success);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    /* ===================================================================
       MAIN PHOTO LAYOUT (Part 1)  —  .pim-main-layout, .pim-main-meta,
                                       .pim-hint, .pim-actions, .pim-filename
       =================================================================== */
    .pim-main-layout{
        display: flex;
        gap: 22px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .pim-main-meta{
        flex: 1 1 220px;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .pim-hint{
        font-size: 13px;
        line-height: 1.55;
        color: var(--slate);
        margin: 0 0 14px;
        max-width: 40ch;
    }

    .pim-actions{
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pim-filename{
        font-size: 12px;
        color: var(--slate-soft);
        word-break: break-all;
    }

    /* ===================================================================
       BUTTONS  —  .pim-btn, .pim-btn--sm, .pim-btn--block
       =================================================================== */
    .pim-btn{
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 16px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 600;
        transition: background .15s ease;
    }
    .pim-btn:hover{ background: var(--teal-deep); }
    .pim-btn:disabled{ background: var(--slate-soft); cursor: not-allowed; }

    .pim-btn--sm{ padding: 7px 10px; font-size: 12.5px; }
    .pim-btn--block{ width: 100%; margin-top: 8px; }

    /* ===================================================================
       VARIANT GRID (Part 2)  —  .pim-variant-grid, .pim-variant-footer,
                                  .pim-code-chip, .pim-empty
       =================================================================== */
    .pim-variant-grid{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 16px;
    }

    .pim-variant-footer{
        display: flex;
        justify-content: center;
        margin-top: 8px;
    }

    .pim-code-chip{
        font-size: 12px;
        font-weight: 600;
        color: var(--ink);
        text-align: center;
        overflow-wrap: anywhere;
    }

    .pim-empty{
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--slate);
        font-size: 13px;
        padding: 6px 2px;
    }
    .pim-empty i{ font-size: 16px; color: var(--slate-soft); }

    /* ===================================================================
       RESPONSIVE BREAKPOINTS
       =================================================================== */
    @media (max-width: 640px){
        .pim-tile--hero{ max-width: 100%; }
        .pim-main-layout{ flex-direction: column; align-items: stretch; }
        .pim-variant-grid{ grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; }
    }

    @media (max-width: 380px){
        .pim-variant-grid{ grid-template-columns: repeat(2, 1fr); }
    }
</style>

<script>
    const ITEM_ID = {{ $item->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Show or hide an element by id.
    function toggle(id, on) {
        document.getElementById(id).hidden = !on;
    }

    // Briefly show the green check mark, then hide it again.
    function showCheck(id) {
        const el = document.getElementById(id);
        el.hidden = false;
        setTimeout(function () {
            el.hidden = true;
        }, 1600);
    }

    // Show the picked file locally before it's uploaded.
    function showLocalPreview(fileInput, imgEl) {
        const file = fileInput.files && fileInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            imgEl.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Wire up preview + filename updates for every file input on the page.
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
        });
        input.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // Drag-and-drop support for every photo tile.
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

    // One shared upload function for both the main photo and every variant photo.
    // target is either 'main' or a variant id.
    function uploadImage(target) {
        const isMain = target === 'main';

        const fileInput = document.getElementById(isMain ? 'mainImageFile' : 'file-' + target);
        const btn = document.getElementById(isMain ? 'mainUploadBtn' : 'btn-' + target);
        const barId = isMain ? 'mainBar' : 'bar-' + target;
        const checkId = isMain ? 'mainCheck' : 'check-' + target;
        const previewEl = document.getElementById(isMain ? 'mainItemPreview' : 'preview-' + target);
        const url = isMain
            ? '/store/management/products/' + ITEM_ID + '/image'
            : '/items/variants/' + target + '/image';

        if (!fileInput.files || !fileInput.files[0]) {
            alert('Please choose an image first.');
            return;
        }

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);

        toggle(barId, true);
        btn.disabled = true;

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
            btn.disabled = false;

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
            btn.disabled = false;
            alert('Upload failed.');
        });
    }
</script>
@endsection