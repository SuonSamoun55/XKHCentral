@extends('POSViews.POSAdminViews.app')

@section('title', 'Manage Variant Images')

@section('content')
<main class="detail-content">
    <div class="detail-card">
        <h2>Manage Variant Images</h2>
        <p style="color:#6b7280; font-size:13px; margin-bottom:20px;">
            Choose a photo for each variant below, then click Upload.
        </p>

        @foreach($items as $item)
            @if($item->variantList->count() > 0)
                <div style="border:1px solid #e5e7eb; border-radius:10px; padding:16px; margin-bottom:16px;">
                    <h4 style="margin-bottom:4px;">{{ $item->display_name }} ({{ $item->number }})</h4>

                    <div style="display:flex; gap:16px; flex-wrap:wrap; margin-top:12px;">
                        @foreach($item->variantList as $variant)
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
                </div>
            @endif
        @endforeach

        @if($items->every(fn($item) => $item->variantList->count() === 0))
            <p>No items with variants found. Sync items first (click "Sync BC Product" on the main Items page).</p>
        @endif
    </div>
</main>

<script>
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