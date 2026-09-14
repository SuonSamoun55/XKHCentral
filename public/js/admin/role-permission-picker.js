// Shared by RoleCreateView.blade.php and Roleedit.blade.php — the
// permission-card picker, live preview, select-all, and search filter.
document.addEventListener('DOMContentLoaded', function () {
    const allCards = Array.from(document.querySelectorAll('.role-form-page .perm-card'));
    const roleNameInput = document.getElementById('roleName');
    const displayNameInput = document.getElementById('displayName');

    function cardChecked(card) {
        return card.classList.contains('is-checked');
    }

    function setCardChecked(card, checked) {
        card.classList.toggle('is-checked', checked);
        card.querySelector('.perm-card-input').checked = checked;
    }

    function refreshAll() {
        let total = 0;
        ['admin', 'customer'].forEach(function (group) {
            const groupCards = allCards.filter(function (c) { return c.dataset.group === group; });
            const checked = groupCards.filter(cardChecked);
            total += checked.length;

            const countEl = document.querySelector('[data-count="' + group + '"]');
            if (countEl) countEl.textContent = checked.length + ' of ' + groupCards.length + ' selected';

            const progressEl = document.querySelector('[data-progress="' + group + '"]');
            if (progressEl) progressEl.style.width = (groupCards.length ? (checked.length / groupCards.length * 100) : 0) + '%';

            const btn = document.querySelector('[data-select-all="' + group + '"]');
            if (btn) {
                const visibleCards = groupCards.filter(function (c) { return !c.classList.contains('is-hidden'); });
                const allVisibleChecked = visibleCards.length > 0 && visibleCards.every(cardChecked);
                btn.textContent = allVisibleChecked ? 'Clear all' : 'Select all';
            }
        });
        document.getElementById('totalCount').textContent = total;
        updatePreview();
    }

    function updatePreview() {
        const displayName = displayNameInput.value.trim();
        const roleName = roleNameInput.value.trim();

        document.getElementById('previewName').textContent = displayName || 'Untitled role';
        document.getElementById('previewKey').textContent = roleName || '—';

        const badge = document.getElementById('previewBadge');
        badge.textContent = displayName ? displayName.charAt(0).toUpperCase() : '?';

        const checkedAdmin = allCards.filter(function (c) { return c.dataset.group === 'admin' && cardChecked(c); });
        const checkedUser = allCards.filter(function (c) { return c.dataset.group === 'customer' && cardChecked(c); });
        document.getElementById('previewAdminCount').textContent = checkedAdmin.length;
        document.getElementById('previewUserCount').textContent = checkedUser.length;

        const allChecked = checkedAdmin.concat(checkedUser);
        const chipsEl = document.getElementById('previewChips');
        if (allChecked.length === 0) {
            chipsEl.innerHTML = '<span class="preview-empty">No pages selected yet.</span>';
            return;
        }
        const maxShow = 8;
        const shown = allChecked.slice(0, maxShow);
        let html = shown.map(function (c) {
            return '<span class="preview-chip ' + c.dataset.group + '">' + c.dataset.label + '</span>';
        }).join('');
        if (allChecked.length > maxShow) {
            html += '<span class="preview-chip more">+' + (allChecked.length - maxShow) + ' more</span>';
        }
        chipsEl.innerHTML = html;
    }

    allCards.forEach(function (card) {
        card.addEventListener('click', function (e) {
            e.preventDefault();
            setCardChecked(card, !cardChecked(card));
            refreshAll();
        });
    });

    document.querySelectorAll('[data-select-all]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const group = btn.getAttribute('data-select-all');
            const visibleCards = allCards.filter(function (c) { return c.dataset.group === group && !c.classList.contains('is-hidden'); });
            const allChecked = visibleCards.length > 0 && visibleCards.every(cardChecked);
            visibleCards.forEach(function (c) { setCardChecked(c, !allChecked); });
            refreshAll();
        });
    });

    document.getElementById('permSearch').addEventListener('input', function (e) {
        const q = e.target.value.trim().toLowerCase();
        allCards.forEach(function (c) {
            const match = c.dataset.label.toLowerCase().includes(q);
            c.classList.toggle('is-hidden', q.length > 0 && !match);
        });
        refreshAll();
    });

    roleNameInput.addEventListener('input', updatePreview);
    displayNameInput.addEventListener('input', updatePreview);

    refreshAll();
});
