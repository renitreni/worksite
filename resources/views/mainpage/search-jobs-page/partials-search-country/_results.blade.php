{{-- SEARCH COUNTRY RESULTS --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i data-lucide="info" class="w-4 h-4"></i>
        <span id="showingCountryText">
            Loading countries...
        </span>
    </div>

    {{-- GRID --}}
    <div id="countryGrid" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- JS renders cards --}}
    </div>

    {{-- PAGINATION --}}
    <div class="mt-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">

        <button id="countryPrevBtn" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl
               border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
               hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Prev
        </button>

        <div id="countryPaginationNumbers" class="w-full sm:w-auto flex items-center justify-center gap-2 overflow-x-auto whitespace-nowrap
               px-1 sm:px-0">
            {{-- JS page numbers --}}
        </div>

        <button id="countryNextBtn" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl
               border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
               hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Next
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>

    </div>

</main>

<script>
    // ====== ELEMENTS ======
    const countryGrid = document.getElementById('countryGrid');
    const showingCountryText = document.getElementById('showingCountryText');
    const countryPaginationNumbers = document.getElementById('countryPaginationNumbers');
    const countryPrevBtn = document.getElementById('countryPrevBtn');
    const countryNextBtn = document.getElementById('countryNextBtn');

    const keywordEl = document.getElementById('countryKeyword');
    const regionEl = document.getElementById('countryRegion');
    const formEl = document.getElementById('countrySearchForm');

    // ====== STATE ======
    let ALL_COUNTRIES = [];
    let FILTERED = [];

    let currentPage = 1;
    let perPage = 9;

    function calcPerPage() {
        // mobile: 6 cards, desktop: 9 cards
        perPage = window.matchMedia('(min-width: 1024px)').matches ? 9 : 6;
    }

    // stable pseudo job count (so it doesn't change every refresh)
    function stableJobsCount(code) {
        let hash = 0;
        for (let i = 0; i < code.length; i++) hash = (hash * 31 + code.charCodeAt(i)) % 100000;
        return 50 + (hash % 1950); // 50..1999
    }

    function totalPages() {
        return Math.max(1, Math.ceil(FILTERED.length / perPage));
    }

    function sliceItems(page) {
        const start = (page - 1) * perPage;
        const end = start + perPage;
        return { start, end, items: FILTERED.slice(start, end) };
    }

    // Image like your sample (landmark feel). No API key.
    function countryImageFromFlag(c) {
        return `https://flagcdn.com/w640/${(c.cca2 || '').toLowerCase()}.png`;
    }


    function countryDescription(country) {
        const cap = country.capital?.[0];
        const region = country.region || "the world";
        if (cap) return `Explore opportunities in ${cap} and across ${country.name.common} in ${region}.`;
        return `Explore opportunities across ${country.name.common} in ${region}.`;
    }

    // ====== RENDER ======
    function renderCountries() {
        const { start, end, items } = sliceItems(currentPage);

        countryGrid.innerHTML = '';

        items.forEach(c => {
            const jobs = c.jobs;
            const desc = countryDescription(c);

            countryGrid.insertAdjacentHTML('beforeend', `
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-md transition">
                    <div class="h-44 sm:h-48 bg-cover bg-center"
                        style="background-image:url('${countryImageFromFlag(c)}')"></div>

                    <div class="p-5">
                        <h3 class="text-xl font-extrabold text-gray-900">${c.name.common}</h3>

                        <p class="mt-1 text-sm text-gray-500">
                            <span class="font-semibold">${jobs}</span> Jobs
                        </p>

                        <p class="mt-3 text-sm leading-relaxed text-gray-600">
                            ${desc}
                        </p>

                        <a href="#"
                           class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#16A34A] hover:underline">
                            View Jobs
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            `);
        });

        // showing text
        const showingStart = FILTERED.length ? start + 1 : 0;
        const showingEnd = Math.min(end, FILTERED.length);
        showingCountryText.innerHTML = `Showing <strong>${showingStart}</strong> to <strong>${showingEnd}</strong> out of <strong>${FILTERED.length}</strong> countries`;

        countryPrevBtn.disabled = currentPage === 1;
        countryNextBtn.disabled = currentPage === totalPages();

        lucide.createIcons();
    }

    function renderPagination() {
        const pages = totalPages();
        countryPaginationNumbers.innerHTML = '';

        const pageBtn = (p) => {
            const isActive = p === currentPage;
            return `
                <button data-page="${p}"
                    class="min-w-[42px] h-11 rounded-xl text-sm font-semibold transition
                           ${isActive ? 'bg-[#16A34A] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'}">
                    ${p}
                </button>
            `;
        };

        if (pages <= 5) {
            for (let p = 1; p <= pages; p++) countryPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));
        } else {
            countryPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(1));

            const s = Math.max(2, currentPage - 1);
            const e = Math.min(pages - 1, currentPage + 1);

            if (s > 2) countryPaginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);
            for (let p = s; p <= e; p++) countryPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));
            if (e < pages - 1) countryPaginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);

            countryPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(pages));
        }

        countryPaginationNumbers.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                currentPage = Number(btn.dataset.page);
                renderPagination();
                renderCountries();
            });
        });
    }

    // ====== FILTER ======
    function applyFilters() {
        const q = (keywordEl?.value || '').trim().toLowerCase();
        const region = (regionEl?.value || '').trim();

        FILTERED = ALL_COUNTRIES.filter(c => {
            const name = (c.name?.common || '').toLowerCase();
            const okName = !q || name.includes(q);
            const okRegion = !region || c.region === region;
            return okName && okRegion;
        });

        currentPage = 1;
        renderPagination();
        renderCountries();
    }

    // ====== LOAD COUNTRIES (REST Countries API) ======
    async function loadCountries() {
        try {
            // fields are limited so it loads faster
            const res = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2,cca3,capital,region');
            const data = await res.json();

            // sort A-Z
            ALL_COUNTRIES = (Array.isArray(data) ? data : [])
                .map(c => ({
                    ...c,
                    jobs: stableJobsCount(c.cca3 || c.cca2 || c.name.common)
                }))
                .sort((a, b) => b.jobs - a.jobs); // 🔥 MOST JOBS FIRST


            FILTERED = [...ALL_COUNTRIES];

            calcPerPage();
            renderPagination();
            renderCountries();
        } catch (err) {
            console.error(err);
            showingCountryText.textContent = "Failed to load countries. Check your internet connection.";
        }
    }

    // ====== EVENTS ======
    formEl?.addEventListener('submit', (e) => {
        e.preventDefault();
        applyFilters();
    });

    keywordEl?.addEventListener('input', () => applyFilters());
    regionEl?.addEventListener('change', () => applyFilters());

    countryPrevBtn?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPagination();
            renderCountries();
        }
    });

    countryNextBtn?.addEventListener('click', () => {
        if (currentPage < totalPages()) {
            currentPage++;
            renderPagination();
            renderCountries();
        }
    });

    window.addEventListener('resize', () => {
        const old = perPage;
        calcPerPage();
        if (old !== perPage) {
            currentPage = Math.min(currentPage, totalPages());
            renderPagination();
            renderCountries();
        }
    });

    // init
    lucide.createIcons();
    loadCountries();
</script>