{{-- RESULTS --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i data-lucide="info" class="w-4 h-4"></i>
        <span id="showingAgencyText">Loading agencies...</span>
    </div>

    {{-- RESULTS GRID --}}
    <div id="agenciesGrid" class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {{-- JS will render cards here --}}
    </div>

    {{-- PAGINATION (mobile-safe) --}}
    <div class="mt-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">

        <button id="agencyPrevBtn"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
                   hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Prev
        </button>

        <div id="agencyPaginationNumbers"
            class="w-full sm:w-auto flex items-center justify-center gap-2 overflow-x-auto whitespace-nowrap px-1 sm:px-0">
            {{-- JS page numbers --}}
        </div>

        <button id="agencyNextBtn"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
                   hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Next
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
</main>

<script>
    // ✅ placeholder agencies (add your real ones later)
    // NOTE: this matches your MAIN PAGE card design fields
    const AGENCIES = [
        {
            name: 'Golden Home Staffing',
            image: '/images/1.jpg',
            jobs: 24,
            description: 'Trusted recruitment agency for domestic and caregiving roles abroad.',
            location: 'Riyadh, Saudi Arabia',
            email: 'info@goldenhome.com',
            phone: '+966 512 345 678',
            industries: ['Domestic', 'Caregiver', 'Hospitality'],
            country: 'Saudi Arabia',
            city: 'Riyadh',
            verified: true,
            licensed: true,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'Global Care Agency',
            image: '/images/2.jpg',
            jobs: 12,
            description: 'Healthcare-focused agency connecting caregivers and nurses to Japan employers.',
            location: 'Tokyo, Japan',
            email: 'support@globalcare.com',
            phone: '+81 90 1234 5678',
            industries: ['Healthcare', 'Caregiver'],
            country: 'Japan',
            city: 'Tokyo',
            verified: true,
            licensed: true,
            noFee: false,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'Sunrise HR',
            image: '/images/3.jpg',
            jobs: 18,
            description: 'Hospitality and service roles with fast processing and responsive support.',
            location: 'Dubai, UAE',
            email: 'hello@sunrisehr.ae',
            phone: '+971 50 123 4567',
            industries: ['Hospitality', 'Food'],
            country: 'UAE',
            city: 'Dubai',
            verified: false,
            licensed: true,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'BuildPro Intl',
            image: '/images/4.png',
            jobs: 9,
            description: 'Construction manpower provider for Qatar and nearby GCC countries.',
            location: 'Doha, Qatar',
            email: 'contact@buildpro.com',
            phone: '+974 55 123 456',
            industries: ['Construction', 'Manufacturing'],
            country: 'Qatar',
            city: 'Doha',
            verified: true,
            licensed: false,
            noFee: false,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'LogiWorld Recruitment',
            image: '/images/5.png',
            jobs: 15,
            description: 'Warehouse and logistics jobs in Canada with verified partner employers.',
            location: 'Toronto, Canada',
            email: 'info@logiworld.ca',
            phone: '+1 647 123 4567',
            industries: ['Logistics', 'Transportation'],
            country: 'Canada',
            city: 'Toronto',
            verified: true,
            licensed: true,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'TechLink Overseas',
            image: '/images/6.png',
            jobs: 6,
            description: 'IT and technical roles for skilled workers targeting AU/NZ markets.',
            location: 'Sydney, Australia',
            email: 'support@techlink.au',
            phone: '+61 401 234 567',
            industries: ['Technology'],
            country: 'Australia',
            city: 'Sydney',
            verified: false,
            licensed: true,
            noFee: false,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'CarePlus Partners',
            image: '/images/1.jpg',
            jobs: 8,
            description: 'Healthcare placements with partner hospitals and assisted living facilities.',
            location: 'Osaka, Japan',
            email: 'hello@careplus.jp',
            phone: '+81 80 2222 3333',
            industries: ['Healthcare'],
            country: 'Japan',
            city: 'Osaka',
            verified: true,
            licensed: true,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'Prime Staff Services',
            image: '/images/2.jpg',
            jobs: 10,
            description: 'Domestic helper placements with flexible schedules and quick interviews.',
            location: 'Abu Dhabi, UAE',
            email: 'prime@staff.ae',
            phone: '+971 55 888 7777',
            industries: ['Domestic'],
            country: 'UAE',
            city: 'Abu Dhabi',
            verified: false,
            licensed: false,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'FoodBridge Recruitment',
            image: '/images/3.jpg',
            jobs: 14,
            description: 'Hospitality and kitchen jobs with trusted employers in Singapore.',
            location: 'Singapore',
            email: 'info@foodbridge.sg',
            phone: '+65 8123 4567',
            industries: ['Hospitality', 'Food'],
            country: 'Singapore',
            city: 'Singapore',
            verified: true,
            licensed: true,
            noFee: false,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'ManuHire Global',
            image: '/images/4.png',
            jobs: 16,
            description: 'Manufacturing and factory roles in KSA with reputable partner companies.',
            location: 'Dammam, Saudi Arabia',
            email: 'apply@manuhire.com',
            phone: '+966 55 999 1111',
            industries: ['Manufacturing', 'Factory'],
            country: 'Saudi Arabia',
            city: 'Dammam',
            verified: true,
            licensed: true,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'TranspoWorks Intl',
            image: '/images/5.png',
            jobs: 7,
            description: 'Driver and transport roles with ongoing deployments to Qatar.',
            location: 'Doha, Qatar',
            email: 'hr@transpoworks.qa',
            phone: '+974 66 777 888',
            industries: ['Transportation'],
            country: 'Qatar',
            city: 'Doha',
            verified: false,
            licensed: true,
            noFee: false,
            has_jobs: true,
            viewUrl: '#'
        },
        {
            name: 'ABC Recruitment',
            image: '/images/6.png',
            jobs: 11,
            description: 'Recruitment partner for manufacturing and industrial roles in KSA.',
            location: 'Jeddah, Saudi Arabia',
            email: 'info@abcrecruit.com',
            phone: '+966 50 222 3333',
            industries: ['Manufacturing'],
            country: 'Saudi Arabia',
            city: 'Jeddah',
            verified: true,
            licensed: false,
            noFee: true,
            has_jobs: true,
            viewUrl: '#'
        },
    ];

    // ===== ELEMENTS =====
    const agenciesGrid = document.getElementById('agenciesGrid');
    const showingAgencyText = document.getElementById('showingAgencyText');
    const agencyPaginationNumbers = document.getElementById('agencyPaginationNumbers');
    const agencyPrevBtn = document.getElementById('agencyPrevBtn');
    const agencyNextBtn = document.getElementById('agencyNextBtn');

    const agencyKeywordEl = document.getElementById('agencyKeyword');
    const agencyCountryEl = document.getElementById('agencyCountry');
    const agencyIndustryEl = document.getElementById('agencyIndustry');

    // hidden filter values (inside filter cards)
    const verifiedHidden = document.querySelector('input[name="verified"]');
    const noFeeHidden = document.querySelector('input[name="no_fee"]');
    const licensedHidden = document.querySelector('input[name="licensed"]');
    const hasJobsHidden = document.querySelector('input[name="has_jobs"]');

    // ===== STATE =====
    let FILTERED_AGENCIES = [...AGENCIES];
    let agencyCurrentPage = 1;
    let agencyPerPage = 9;

    function calcAgencyPerPage() {
        agencyPerPage = window.matchMedia('(min-width: 1024px)').matches ? 9 : 6;
    }

    function agencyTotalPages() {
        return Math.max(1, Math.ceil(FILTERED_AGENCIES.length / agencyPerPage));
    }

    function sliceAgencies(page) {
        const start = (page - 1) * agencyPerPage;
        const end = start + agencyPerPage;
        return { start, end, items: FILTERED_AGENCIES.slice(start, end) };
    }

    // ===== RENDER =====
    function renderAgencies() {
        const { start, end, items } = sliceAgencies(agencyCurrentPage);

        agenciesGrid.innerHTML = '';

        items.forEach(a => {
            const industries = Array.isArray(a.industries) ? a.industries : [];

            agenciesGrid.insertAdjacentHTML('beforeend', `
                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-transform transform hover:-translate-y-1 p-4 border border-gray-100">

                    <!-- Top Row: Image + Name + Jobs -->
                    <div class="flex items-center mb-3">
                        <img src="${a.image || '/images/placeholder-agency.png'}" alt="${a.name}"
                            class="w-20 h-20 rounded-lg object-cover mr-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">${a.name}</h3>
                            <p class="text-green-700 font-medium">${a.jobs} jobs available</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">${a.description || ''}</p>

                    <!-- Location, Email, Phone -->
                    <div class="space-y-1 text-gray-500 text-sm mb-3">
                        <p class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span class="truncate">${a.location || '-'}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span class="truncate">${a.email || '-'}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span class="truncate">${a.phone || '-'}</span>
                        </p>
                    </div>

                    <!-- Industries -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        ${industries.slice(0, 6).map(ind => `
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                ${ind}
                            </span>
                        `).join('')}
                        ${industries.length > 6 ? `
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                +${industries.length - 6}
                            </span>
                        ` : ''}
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center">
                        <a href="${a.viewUrl || '#'}"
                           class="text-white bg-[#16A34A] px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition
                                  text-center flex-1 text-sm mr-2">
                            View Profile
                        </a>
                        <button class="text-gray-500 hover:text-gray-700 transition" aria-label="Save agency">
                            <i data-lucide="bookmark" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            `);
        });

        const showingStart = FILTERED_AGENCIES.length ? start + 1 : 0;
        const showingEnd = Math.min(end, FILTERED_AGENCIES.length);
        showingAgencyText.innerHTML =
            `Showing <strong>${showingStart}</strong> to <strong>${showingEnd}</strong> out of <strong>${FILTERED_AGENCIES.length}</strong> agencies`;

        agencyPrevBtn.disabled = agencyCurrentPage === 1;
        agencyNextBtn.disabled = agencyCurrentPage === agencyTotalPages();

        lucide.createIcons();
    }

    function isMobile() {
        return window.matchMedia('(max-width: 639px)').matches; // Tailwind sm breakpoint
    }

    function renderAgencyPagination() {
        const pages = agencyTotalPages();
        agencyPaginationNumbers.innerHTML = '';

        const pageBtn = (page) => {
            const isActive = page === agencyCurrentPage;
            return `
                <button data-page="${page}"
                    class="min-w-[38px] sm:min-w-[42px] h-10 sm:h-11 px-2 rounded-xl text-sm font-semibold transition
                           ${isActive ? 'bg-[#16A34A] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'}">
                    ${page}
                </button>
            `;
        };

        if (pages <= 5) {
            for (let p = 1; p <= pages; p++) agencyPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));
        } else {
            agencyPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(1));

            const delta = isMobile() ? 0 : 1;
            const start = Math.max(2, agencyCurrentPage - delta);
            const end = Math.min(pages - 1, agencyCurrentPage + delta);

            if (start > 2) agencyPaginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);
            for (let p = start; p <= end; p++) agencyPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));
            if (end < pages - 1) agencyPaginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);

            agencyPaginationNumbers.insertAdjacentHTML('beforeend', pageBtn(pages));
        }

        agencyPaginationNumbers.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                agencyCurrentPage = Number(btn.dataset.page);
                renderAgencyPagination();
                renderAgencies();
            });
        });
    }

    // ===== FILTERS =====
    function applyAgencyFilters() {
        const q = (agencyKeywordEl?.value || '').trim().toLowerCase();
        const country = (agencyCountryEl?.value || '').trim();
        const industry = (agencyIndustryEl?.value || '').trim();

        const wantVerified = verifiedHidden?.value === '1';
        const wantNoFee = noFeeHidden?.value === '1';
        const wantLicensed = licensedHidden?.value === '1';
        const wantHasJobs = hasJobsHidden?.value === '1';

        FILTERED_AGENCIES = AGENCIES.filter(a => {
            const nameOk = !q || (a.name || '').toLowerCase().includes(q);

            const countryOk = !country || (a.country === country);
            const industryOk = !industry || (Array.isArray(a.industries) ? a.industries.includes(industry) : false);

            const verifiedOk = !wantVerified || !!a.verified;
            const noFeeOk = !wantNoFee || !!a.noFee;
            const licensedOk = !wantLicensed || !!a.licensed;
            const hasJobsOk = !wantHasJobs || (a.jobs > 0);

            return nameOk && countryOk && industryOk && verifiedOk && noFeeOk && licensedOk && hasJobsOk;
        });

        agencyCurrentPage = 1;
        renderAgencyPagination();
        renderAgencies();
    }

    // ===== EVENTS =====
    document.getElementById("agencySearchForm")?.addEventListener("submit", (e) => {
        e.preventDefault();
        applyAgencyFilters();
    });

    agencyKeywordEl?.addEventListener('input', applyAgencyFilters);
    agencyCountryEl?.addEventListener('change', applyAgencyFilters);
    agencyIndustryEl?.addEventListener('change', applyAgencyFilters);

    // filter cards toggle (keeps your UI)
    document.querySelectorAll('.filter-card').forEach(card => {
        card.addEventListener('click', () => {
            const span = card.querySelector('span');
            const hidden = card.querySelector('input[type="hidden"]');

            card.classList.toggle('bg-green-50');
            card.classList.toggle('border-green-600');

            if (span) span.classList.toggle('text-green-700');
            if (hidden) hidden.value = (hidden.value === "0") ? "1" : "0";

            applyAgencyFilters();
        });
    });

    agencyPrevBtn.addEventListener('click', () => {
        if (agencyCurrentPage > 1) {
            agencyCurrentPage--;
            renderAgencyPagination();
            renderAgencies();
        }
    });

    agencyNextBtn.addEventListener('click', () => {
        if (agencyCurrentPage < agencyTotalPages()) {
            agencyCurrentPage++;
            renderAgencyPagination();
            renderAgencies();
        }
    });

    window.addEventListener('resize', () => {
        const old = agencyPerPage;
        calcAgencyPerPage();
        if (old !== agencyPerPage) {
            agencyCurrentPage = Math.min(agencyCurrentPage, agencyTotalPages());
            renderAgencyPagination();
            renderAgencies();
        }
    });

    // ===== INIT =====
    lucide.createIcons();
    calcAgencyPerPage();
    applyAgencyFilters(); // renders initially too
</script>
