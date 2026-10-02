@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/LoginSettings/login_settings.css') }}?v={{ filemtime(public_path('css/views/Management/LoginSettings/login_settings.css')) }}">
@endpush

@section('title', 'Login Page Setup')

@php
    $slides = [1 => 'Slide 1', 2 => 'Slide 2', 3 => 'Slide 3'];
@endphp

@section('content')
    <div class="login-setup-page">

        <div class="alert-container">
            @if (session('success'))
                <div class="custom-alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="custom-alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="custom-alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
        </div>

        <div class="page-head">
            <h1>Login Page Setup</h1>
        </div>

        <div class="ls-tabs" role="tablist">
            <button type="button" class="ls-tab is-active" data-tab="desktop" role="tab">
                <i class="bi bi-display"></i> Desktop
            </button>
            <button type="button" class="ls-tab" data-tab="mobile" role="tab">
                <i class="bi bi-phone"></i> Mobile
            </button>
        </div>

        <form method="POST" action="{{ route('login-settings.update') }}" enctype="multipart/form-data" id="loginSetupForm">
            @csrf
            @method('PUT')

            {{-- ================= DESKTOP ================= --}}
            <section class="ls-panel is-active" data-panel="desktop">
                <div class="ls-card ls-desktop-card">
                    <div class="ls-desktop-preview">
                        <div class="ls-browser">
                            <div class="ls-browser-bar"><span></span><span></span><span></span></div>
                            <div class="ls-browser-screen">
                                <div class="ls-browser-media">
                                    <video data-preview-target="desktop_video" src="{{ $settings->mediaUrl('desktop_video') }}"
                                        autoplay muted loop playsinline></video>
                                </div>
                                <div class="ls-fake-form">
                                    <b></b><i></i><i></i><em></em>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ls-desktop-controls">
                        @include('ManagementSystemViews.AdminViews.Layouts.LoginSettings._upload', [
                            'column' => 'desktop_video', 'label' => 'Video', 'kind' => 'video',
                        ])
                    </div>
                </div>
            </section>

            {{-- ================= MOBILE ================= --}}
            <section class="ls-panel" data-panel="mobile">
                <div class="ls-screens">
                    {{-- Welcome --}}
                    <div class="ls-card ls-screen">
                        <div class="ls-step-head">
                            <span class="ls-step">1</span>
                            <div class="ls-step-title">Welcome</div>
                        </div>
                        <div class="ls-phone">
                            <div class="ls-phone-screen ls-welcome">
                                <img class="ls-welcome-logo" data-preview-target="welcome_logo" src="{{ $settings->mediaUrl('welcome_logo') }}" alt="">
                                <img class="ls-welcome-img" data-preview-target="welcome_image" src="{{ $settings->mediaUrl('welcome_image') }}" alt="">
                            </div>
                        </div>
                        <div class="ls-controls">
                            @include('ManagementSystemViews.AdminViews.Layouts.LoginSettings._upload', [
                                'column' => 'welcome_logo', 'label' => 'Logo', 'kind' => 'image',
                            ])
                            @include('ManagementSystemViews.AdminViews.Layouts.LoginSettings._upload', [
                                'column' => 'welcome_image', 'label' => 'Picture', 'kind' => 'image',
                            ])
                        </div>
                    </div>

                    {{-- Intro slides --}}
                    @foreach ($slides as $n => $slideLabel)
                        <div class="ls-card ls-screen">
                            <div class="ls-step-head">
                                <span class="ls-step">{{ $n + 1 }}</span>
                                <div class="ls-step-title">{{ $slideLabel }}</div>
                            </div>
                            <div class="ls-phone">
                                <div class="ls-phone-screen ls-slide">
                                    <img data-preview-target="slide_{{ $n }}_image" src="{{ $settings->mediaUrl("slide_{$n}_image") }}" alt="">
                                    <div class="ls-slide-text">
                                        <strong data-text-target="slide_{{ $n }}_title">{{ $settings->text("slide_{$n}_title") }}</strong>
                                        <span data-text-target="slide_{{ $n }}_text">{{ $settings->text("slide_{$n}_text") }}</span>
                                        <em>Next</em>
                                    </div>
                                </div>
                            </div>
                            <div class="ls-controls">
                                @include('ManagementSystemViews.AdminViews.Layouts.LoginSettings._upload', [
                                    'column' => "slide_{$n}_image", 'label' => 'Picture', 'kind' => 'image',
                                ])
                                <label class="ls-field">
                                    <span>Title</span>
                                    <input type="text" name="slide_{{ $n }}_title" maxlength="120"
                                        value="{{ old("slide_{$n}_title", $settings->text("slide_{$n}_title")) }}"
                                        data-text-source="slide_{{ $n }}_title">
                                </label>
                                <label class="ls-field">
                                    <span>Text</span>
                                    <textarea name="slide_{{ $n }}_text" rows="2" maxlength="500"
                                        data-text-source="slide_{{ $n }}_text">{{ old("slide_{$n}_text", $settings->text("slide_{$n}_text")) }}</textarea>
                                </label>
                            </div>
                        </div>
                    @endforeach

                    {{-- Login form --}}
                    <div class="ls-card ls-screen">
                        <div class="ls-step-head">
                            <span class="ls-step">5</span>
                            <div class="ls-step-title">Login</div>
                        </div>
                        <div class="ls-phone">
                            <div class="ls-phone-screen ls-login">
                                <img data-preview-target="mobile_image" src="{{ $settings->mediaUrl('mobile_image') }}" alt="">
                                <div class="ls-fake-form">
                                    <b></b><i></i><i></i><em></em>
                                </div>
                            </div>
                        </div>
                        <div class="ls-controls">
                            @include('ManagementSystemViews.AdminViews.Layouts.LoginSettings._upload', [
                                'column' => 'mobile_image', 'label' => 'Picture', 'kind' => 'image',
                            ])
                        </div>
                    </div>
                </div>
            </section>

            <div class="ls-form-actions">
                <button type="submit" class="ls-btn ls-btn-primary ls-save" id="loginSetupSave">
                    <i class="bi bi-check2"></i> Save changes
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const page = document.querySelector('.login-setup-page');
            const form = document.getElementById('loginSetupForm');
            const saveBtn = document.getElementById('loginSetupSave');

            page.querySelectorAll('.custom-alert').forEach(function(alert) {
                setTimeout(function() { alert.remove(); }, 5000);
            });

            // ---- Tabs (remembered per browser) ----
            const tabs = page.querySelectorAll('.ls-tab');
            const panels = page.querySelectorAll('.ls-panel');

            function showTab(name) {
                tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === name));
                panels.forEach(p => p.classList.toggle('is-active', p.dataset.panel === name));
                try { localStorage.setItem('loginSetupTab', name); } catch (e) {}
            }

            tabs.forEach(t => t.addEventListener('click', () => showTab(t.dataset.tab)));
            try {
                const saved = localStorage.getItem('loginSetupTab');
                if (saved === 'mobile') showTab('mobile');
            } catch (e) {}

            // ---- Unsaved-changes tracking ----
            const dirty = new Set();
            // A file over the size limit would be rejected on save anyway.
            function refreshSaveButton() {
                saveBtn.disabled = !!page.querySelector('.ls-file.too-big');
            }

            function setPreview(column, src) {
                page.querySelectorAll('[data-preview-target="' + column + '"]').forEach(el => { el.src = src; });
            }

            // File picked → preview it right away.
            page.querySelectorAll('input[type="file"][data-preview]').forEach(function(input) {
                const column = input.dataset.preview;
                const originalSrc = page.querySelector('[data-preview-target="' + column + '"]')?.getAttribute('src');

                input.addEventListener('change', function() {
                    const file = input.files[0];
                    const nameEl = page.querySelector('[data-file-for="' + column + '"]');
                    const reset = page.querySelector('[data-reset="' + column + '"]');
                    if (!file) return;

                    const maxMb = Number(input.dataset.maxMb);
                    const sizeMb = file.size / 1024 / 1024;
                    const isTooBig = sizeMb > maxMb;

                    nameEl.innerHTML = '';
                    const name = document.createElement('span');
                    name.textContent = file.name + ' · ' + sizeMb.toFixed(1) + ' MB' + (isTooBig ? ' — max ' + maxMb + ' MB' : '');
                    const undo = document.createElement('button');
                    undo.type = 'button';
                    undo.className = 'ls-undo';
                    undo.textContent = 'Undo';
                    undo.addEventListener('click', function() {
                        input.value = '';
                        nameEl.innerHTML = '';
                        nameEl.classList.remove('too-big');
                        setPreview(column, originalSrc);
                        dirty.delete(column);
                        refreshSaveButton();
                    });
                    nameEl.append(name, undo);
                    nameEl.classList.toggle('too-big', isTooBig);

                    if (reset) reset.checked = false;
                    setPreview(column, URL.createObjectURL(file));
                    dirty.add(column);
                    refreshSaveButton();
                });
            });

            // "Go back to default" → preview the default.
            page.querySelectorAll('input[data-reset]').forEach(function(box) {
                const column = box.dataset.reset;
                const originalSrc = page.querySelector('[data-preview-target="' + column + '"]')?.getAttribute('src');

                box.addEventListener('change', function() {
                    const input = document.getElementById('file_' + column);
                    if (box.checked && input.value) {
                        input.value = '';
                        const nameEl = page.querySelector('[data-file-for="' + column + '"]');
                        nameEl.innerHTML = '';
                        nameEl.classList.remove('too-big');
                    }
                    setPreview(column, box.checked ? box.dataset.defaultSrc : originalSrc);
                    box.checked ? dirty.add(column) : dirty.delete(column);
                    refreshSaveButton();
                });
            });

            // Slide title/text → live preview.
            page.querySelectorAll('[data-text-source]').forEach(function(field) {
                const key = field.dataset.textSource;
                const original = field.value;
                field.addEventListener('input', function() {
                    page.querySelectorAll('[data-text-target="' + key + '"]').forEach(el => {
                        el.textContent = field.value;
                    });
                    field.value !== original ? dirty.add(key) : dirty.delete(key);
                    refreshSaveButton();
                });
            });

            form.addEventListener('submit', function() {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Uploading…';
                window.removeEventListener('beforeunload', warnUnsaved);
            });

            function warnUnsaved(e) {
                if (dirty.size > 0) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            }
            window.addEventListener('beforeunload', warnUnsaved);
        });
    </script>
@endpush
