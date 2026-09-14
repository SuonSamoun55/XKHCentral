document.addEventListener("DOMContentLoaded", function () {
    // Scoped to "posAdminShell" specifically (not the generic "appShell" id) —
    // several page-level templates (e.g. the notification list) reuse
    // id="appShell" for their own unrelated content wrapper, which made
    // getElementById("appShell") grab the wrong element on those pages.
    const appShell = document.getElementById("posAdminShell");
    const collapseHandle = document.getElementById("collapseHandle");
    const settingsBtn = document.getElementById("settingsBtn");
    const settingsBox = document.getElementById("settingsBox");

    if (!appShell || appShell.dataset.sidebarReady === "true") {
        return;
    }

    appShell.dataset.sidebarReady = "true";

    // Restoring the collapsed class itself happens synchronously in an
    // inline <script> right after #posAdminShell opens (see app.blade.php),
    // so it's applied before this file even loads — no flash of the
    // expanded sidebar on page load.
    const COLLAPSE_STORAGE_KEY = "posAdminSidebarCollapsed";

    if (collapseHandle && appShell) {
        collapseHandle.addEventListener("click", function () {
            appShell.classList.toggle("collapsed");

            try {
                localStorage.setItem(COLLAPSE_STORAGE_KEY, appShell.classList.contains("collapsed"));
            } catch (_) {
                // localStorage unavailable — nothing to do.
            }

            if (appShell.classList.contains("collapsed") && settingsBox) {
                settingsBox.classList.remove("open");
                appShell.classList.remove("settings-active");
            }
        });
    }

    if (settingsBtn && settingsBox && appShell) {
        settingsBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (appShell.classList.contains("collapsed")) return;

            const willOpen = !settingsBox.classList.contains("open");
            settingsBox.classList.toggle("open", willOpen);
            appShell.classList.toggle("settings-active", willOpen);
        });
    }

    document.addEventListener("click", function (e) {
        if (!settingsBox || !settingsBtn) return;

        if (!settingsBox.contains(e.target) && !settingsBtn.contains(e.target)) {
            settingsBox.classList.remove("open");
            appShell?.classList.remove("settings-active");
        }
    });
});
