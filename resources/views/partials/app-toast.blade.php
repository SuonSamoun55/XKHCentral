{{--
    Reusable toast notification — include once per page (after @push('styles')
    has pulled in /css/shared/toast.css). Auto-fires from session('success'),
    session('error'), and validation errors on page load; call
    showAppToast(type, title, message) from your own JS for anything else.
--}}
<div id="appToastWrap" class="app-toast-wrap"></div>

<script>
(function () {
    function escapeHtml(value) {
        if (value === null || value === undefined) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    window.showAppToast = function (type, title, message) {
        const wrap = document.getElementById('appToastWrap');
        if (!wrap) return;

        const toast = document.createElement('div');
        toast.className = 'app-toast ' + (type === 'success' ? 'success' : 'error');

        const icon = type === 'success'
            ? '<i class="bi bi-check-circle-fill"></i>'
            : '<i class="bi bi-exclamation-triangle-fill"></i>';

        toast.innerHTML = `
            <div class="app-toast-head">
                <span class="app-toast-head-icon">${icon} ${escapeHtml(title)}</span>
                <button type="button" class="app-toast-close" aria-label="Close">&times;</button>
            </div>
            <div class="app-toast-body">${message}</div>
        `;

        const closeBtn = toast.querySelector('.app-toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => toast.remove());
        }

        wrap.appendChild(toast);
        setTimeout(() => toast.remove(), 4500);
    };

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success') && !($suppressSuccessToast ?? false))
            window.showAppToast('success', 'Success', @json(e(session('success'))));
        @endif

        @if(session('error'))
            window.showAppToast('error', 'Error', @json(e(session('error'))));
        @endif

        @if ($errors->any())
            window.showAppToast('error', 'Please fix the following', @json(
                '<ul>' . collect($errors->all())->map(fn ($m) => '<li>' . e($m) . '</li>')->implode('') . '</ul>'
            ));
        @endif
    });
})();
</script>
