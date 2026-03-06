document.addEventListener('DOMContentLoaded', () => {
    const dropBtn = document.querySelector('.dropbtn-modern');
    const dropdownContent = document.querySelector('.dropdown-content-horizontal');

    // QUẢN LÝ DROPDOWN BỘ LỌC
    if (dropBtn && dropdownContent) {
        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isShow = dropdownContent.classList.toggle('show');
            const arrow = dropBtn.querySelector('.fa-chevron-down');

            if (arrow) {
                arrow.style.transition = '0.3s';
                arrow.style.transform = isShow ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });

        document.addEventListener('click', (e) => {
            if (!dropBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('show');
                const arrow = dropBtn.querySelector('.fa-chevron-down');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    window.addEventListener('pageshow', (event) => {
        const isBackNavigation = event.persisted ||
            (typeof window.performance !== "undefined" && window.performance.navigation.type === 2);

        if (isBackNavigation) {
            window.location.reload();
        }
    });
});
