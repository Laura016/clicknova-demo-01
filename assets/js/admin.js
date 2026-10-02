const mobileMenuButton =
    document.getElementById('mobileMenuButton');

const sidebar =
    document.querySelector('.admin-sidebar');

const sidebarClose =
    document.getElementById('sidebarClose');

const mobileMenuOverlay =
    document.getElementById('mobileMenuOverlay');


function abrirMenuMovil() {

    if (!sidebar) return;

    sidebar.classList.add('mobile-open');

    if (mobileMenuOverlay) {
        mobileMenuOverlay.classList.add('active');
    }

    document.body.style.overflow = 'hidden';
}


function cerrarMenuMovil() {

    if (!sidebar) return;

    sidebar.classList.remove('mobile-open');

    if (mobileMenuOverlay) {
        mobileMenuOverlay.classList.remove('active');
    }

    document.body.style.overflow = '';
}


if (mobileMenuButton) {

    mobileMenuButton.addEventListener(
        'click',
        abrirMenuMovil
    );

}


if (sidebarClose) {

    sidebarClose.addEventListener(
        'click',
        cerrarMenuMovil
    );

}


if (mobileMenuOverlay) {

    mobileMenuOverlay.addEventListener(
        'click',
        cerrarMenuMovil
    );

}


if (sidebar) {

    const enlacesMenu =
        sidebar.querySelectorAll('.admin-menu a');

    enlacesMenu.forEach(function (enlace) {

        enlace.addEventListener(
            'click',
            cerrarMenuMovil
        );

    });

}