<script>
    window.addEventListener("load", function() {

        /* ===============================
           MOBILE MENU
        =============================== */

        const menuBtn = document.getElementById("mobile-menu-button");
        const mobileMenu = document.getElementById("mobile-menu");
        const iconMenu = document.getElementById("icon-menu");
        const iconClose = document.getElementById("icon-close");

        if (menuBtn && mobileMenu) {

            menuBtn.addEventListener("click", () => {

                mobileMenu.classList.toggle("hidden");

                iconMenu?.classList.toggle("opacity-0");
                iconMenu?.classList.toggle("scale-75");

                iconClose?.classList.toggle("opacity-0");
                iconClose?.classList.toggle("scale-75");

            });

        }


        /* ===============================
           MOBILE SEARCH DROPDOWN
        =============================== */

        const mobileSearchBtn = document.getElementById("mobile-search-btn");
        const mobileSearchDropdown = document.getElementById("mobile-search-dropdown");
        const mobileSearchIcon = document.querySelector(".mobile-search-icon");
        
        if (mobileSearchBtn && mobileSearchDropdown) {

            mobileSearchBtn.addEventListener("click", () => {

                mobileSearchDropdown.classList.toggle("hidden");
                mobileSearchIcon?.classList.toggle("rotate-180");

            });

        }


        /* ===============================
           REGISTER MODAL
        =============================== */

        const registerModal = document.getElementById("registerModal");
        const registerOverlay = document.getElementById("registerOverlay");

        const registerBtnDesktop = document.getElementById("registerBtnDesktop");
        const registerBtnMobile = document.getElementById("registerBtnMobile");

        const registerCloseBtn = document.getElementById("registerCloseBtn");
        const registerCancelBtn = document.getElementById("registerCancelBtn");

        function openRegisterModal() {

            if (!registerModal) return;

            registerModal.classList.remove("hidden");

            if (window.lucide) lucide.createIcons();

        }

        function closeRegisterModal() {

            if (!registerModal) return;

            registerModal.classList.add("hidden");

        }

        registerBtnDesktop?.addEventListener("click", openRegisterModal);

        registerBtnMobile?.addEventListener("click", () => {

            openRegisterModal();
            mobileMenu?.classList.add("hidden");

        });

        registerCloseBtn?.addEventListener("click", closeRegisterModal);
        registerCancelBtn?.addEventListener("click", closeRegisterModal);
        registerOverlay?.addEventListener("click", closeRegisterModal);



        /* ===============================
           LOGIN MODAL
        =============================== */

        const loginModal = document.getElementById("loginModal");
        const loginOverlay = document.getElementById("loginOverlay");

        const loginBtnDesktop = document.getElementById("loginBtnDesktop");
        const loginBtnMobile = document.getElementById("loginBtnMobile");

        const loginCloseBtn = document.getElementById("loginCloseBtn");
        const loginCancelBtn = document.getElementById("loginCancelBtn");

        function openLoginModal() {

            if (!loginModal) return;

            loginModal.classList.remove("hidden");

            if (window.lucide) lucide.createIcons();

        }

        function closeLoginModal() {

            if (!loginModal) return;

            loginModal.classList.add("hidden");

        }

        loginBtnDesktop?.addEventListener("click", openLoginModal);

        loginBtnMobile?.addEventListener("click", () => {

            openLoginModal();
            mobileMenu?.classList.add("hidden");

        });

        loginCloseBtn?.addEventListener("click", closeLoginModal);
        loginCancelBtn?.addEventListener("click", closeLoginModal);
        loginOverlay?.addEventListener("click", closeLoginModal);



        /* ===============================
           ESC KEY CLOSE MODALS
        =============================== */

        document.addEventListener("keydown", (e) => {

            if (e.key !== "Escape") return;

            if (loginModal && !loginModal.classList.contains("hidden")) {
                closeLoginModal();
            }

            if (registerModal && !registerModal.classList.contains("hidden")) {
                closeRegisterModal();
            }

        });

    });
</script>
