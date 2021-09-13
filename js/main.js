window.onload=function () {

    let darkMode = localStorage.getItem('darkMode');
    const btn = document.querySelector("#btn");

    function darkModeEnabled() {
        document.body.setAttribute("data-theme", "dark");
        localStorage.setItem('darkMode', 'enabled');
    }
    function darkModeDisabled() {
        document.body.setAttribute("data-theme", "");
            localStorage.setItem('darkMode', null);
    }

    if(darkMode === "enabled") {
        darkModeEnabled();
    }

    btn.addEventListener("click", ()=> {
        darkMode = localStorage.getItem('darkMode');
        if(darkMode !== "enabled") {
            darkModeEnabled();
        } else {
            darkModeDisabled();
        }
    });

}
