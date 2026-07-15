@extends('POSViews.POSAdminViews.app')

@section('title', 'Update Product Images')

@section('content')
<main class="detail-content">
    <div class="detail-card">
        <a href="{{ url()->previous() }}" class="back-btn text-decoration-none">
            <i class="bi bi-chevron-left"></i>
        </a>

        <h2>{{ $item->display_name }} ({{ $item->number }})</h2>

        <!-- Main item image -->
        <div style="margin-top:16px;">
            <div style="font-weight:700; margin-bottom:8px;">Main Item Photo</div>

            <div style="width:220px; height:160px; border:1px solid #ddd; border-radius:10px; overflow:hidden; background:#f8fafc;">
                <img
                    id="mainItemPreview"
                    src="{{ $item->custom_image_url ?? $item->image_url ?? url('/item-image/' . $item->bc_id) }}"
                    alt="{{ $item->display_name }}"
                    style="width:100%; height:100%; object-fit:cover;"
                    onerror="this.src='https://placehold.co/220x160/e5e7eb/94a3b8?text=No+Photo'">
            </div>

            <p style="color:#94a3b8; font-size:12px; margin-top:6px;">
                Uploading a photo here sets a custom photo that will NOT be lost when you sync from BC.
            </p>

            <input type="file" accept="image/*" id="mainImageFile" style="margin-top:8px; font-size:12px;">

            <button
                type="button"
                onclick="uploadMainImage()"
                style="margin-top:8px; padding:6px 14px; background:#18b8c7; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px; display:block;">
                Upload Main Photo
            </button>
        </div>

        <!-- Variant images -->
        <div style="margin-top:28px;">
            <div style="font-weight:700; margin-bottom:8px;">Variant Photos</div>

            @if($variants->count() > 0)
                <div style="display:flex; gap:16px; flex-wrap:wrap;">
                    @foreach($variants as $variant)
                        <div style="width:160px; text-align:center;">

                            <div style="width:150px; height:100px; border:1px solid #ddd; border-radius:8px; overflow:hidden; background:#f8fafc; margin:0 auto;">
                                <img
                                    id="preview-{{ $variant->id }}"
                                    src="{{ $variant->image_url ?? 'https://placehold.co/150x100/e5e7eb/94a3b8?text=No+Image' }}"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>

                            <div style="font-weight:600; margin-top:6px;">{{ $variant->code }}</div>

                            <input
                                type="file"
                                accept="image/*"
                                id="file-{{ $variant->id }}"
                                style="margin-top:6px; font-size:11px; width:100%;">

                            <button
                                type="button"
                                onclick="uploadVariantImage({{ $variant->id }})"
                                style="margin-top:6px; padding:4px 10px; background:#18b8c7; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:12px; width:100%;">
                                Upload
                            </button>

                        </div>
                    @endforeach
                </div>
            @else
                <p style="color:#6b7280; font-size:13px;">
                    This item has no variants. Only the main photo applies.
                </p>
            @endif
        </div>
    </div>
</main>

<script>
    const ITEM_ID = {{ $item->id }};

    // Upload the main item photo
    function uploadMainImage() {
        const fileInput = document.getElementById('mainImageFile');

        if (!fileInput.files || !fileInput.files[0]) {
            alert('Please choose an image first.');
            return;
        }

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);

        fetch('/store/management/products/' + ITEM_ID + '/image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                document.getElementById('mainItemPreview').src = data.image_url;
                alert('Main photo uploaded!');
            } else {
                alert('Upload failed.');
            }
        })
        .catch(function (error) {
            console.error(error);
            alert('Upload failed.');
        });
    }

    // Upload an image for one variant
    function uploadVariantImage(variantId) {
        const fileInput = document.getElementById('file-' + variantId);

        if (!fileInput.files || !fileInput.files[0]) {
            alert('Please choose an image first.');
            return;
        }

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);

        fetch('/items/variants/' + variantId + '/image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                document.getElementById('preview-' + variantId).src = data.image_url;
                alert('Image uploaded!');
            } else {
                alert('Upload failed.');
            }
        })
        .catch(function (error) {
            console.error(error);
            alert('Upload failed.');
        });
    }
</script>
@endsection
