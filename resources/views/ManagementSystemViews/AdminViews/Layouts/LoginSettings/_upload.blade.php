{{-- One upload control. Expects: $settings, $column, $label, $kind ('image' | 'video') --}}
@php
    $isCustom = $settings->isCustom($column);
    $inputId = 'file_' . $column;
@endphp
<div class="ls-upload" data-column="{{ $column }}">
    <div class="ls-upload-row">
        <div class="ls-upload-info">
            <span class="ls-upload-label">{{ $label }}</span>
            <span class="ls-upload-hint">{{ $kind === 'video' ? 'MP4 · 30 MB' : 'JPG, PNG · 5 MB' }}</span>
            <span class="ls-tag {{ $isCustom ? 'custom' : '' }}">{{ $isCustom ? 'Custom' : 'Default' }}</span>
        </div>
        <label for="{{ $inputId }}" class="ls-btn ls-btn-soft">
            <i class="bi bi-upload"></i> {{ $isCustom ? 'Change' : 'Upload' }}
        </label>
        <input type="file" name="{{ $column }}" id="{{ $inputId }}" hidden
            accept="{{ $kind === 'video' ? 'video/mp4,video/webm' : 'image/png,image/jpeg,image/webp' }}"
            data-preview="{{ $column }}" data-max-mb="{{ $kind === 'video' ? 30 : 5 }}">
    </div>
    <div class="ls-file" data-file-for="{{ $column }}"></div>
    @if ($isCustom)
        <label class="ls-reset">
            <input type="checkbox" name="reset_{{ $column }}" value="1" data-reset="{{ $column }}"
                data-default-src="{{ asset(\App\Models\ManagementSystem\LoginSetting::MEDIA_DEFAULTS[$column]) }}">
            Use default
        </label>
    @endif
</div>
