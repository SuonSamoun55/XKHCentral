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

    if (collapseHandle && appShell) {
        collapseHandle.addEventListener("click", function () {
            appShell.classList.toggle("collapsed");

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
