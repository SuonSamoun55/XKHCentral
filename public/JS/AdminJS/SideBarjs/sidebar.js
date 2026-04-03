document.addEventListener("DOMContentLoaded", function () {
    const appShell = document.getElementById("appShell");
    const collapseHandle = document.getElementById("collapseHandle");
    const settingsBtn = document.getElementById("settingsBtn");
    const settingsBox = document.getElementById("settingsBox");

    if (collapseHandle && appShell) {
        collapseHandle.addEventListener("click", function () {
            appShell.classList.toggle("collapsed");

            if (appShell.classList.contains("collapsed") && settingsBox) {
                settingsBox.classList.remove("open");
            }
        });
    }

    if (settingsBtn && settingsBox && appShell) {
        settingsBtn.addEventListener("click", function (e) {
            e.stopPropagation();

            if (appShell.classList.contains("collapsed")) return;

            settingsBox.classList.toggle("open");
        });
    }

    document.addEventListener("click", function (e) {
        if (!settingsBox || !settingsBtn) return;

        if (!settingsBox.contains(e.target) && !settingsBtn.contains(e.target)) {
            settingsBox.classList.remove("open");
        }
    });
});
