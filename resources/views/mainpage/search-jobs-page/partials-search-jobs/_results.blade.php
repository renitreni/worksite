<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i data-lucide="info" class="w-4 h-4"></i>
        <span id="showingText">Showing <strong>1</strong> to <strong>9</strong> out of <strong>12</strong> jobs</span>
    </div>

    {{-- RESULTS GRID --}}
    <div id="jobsGrid" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        {{-- JS will render cards here --}}
    </div>

    {{-- PAGINATION --}}
    <div class="mt-10 flex items-center justify-between gap-4">
        <button id="prevBtn"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
                   hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Prev
        </button>

        <div id="paginationNumbers" class="flex items-center gap-2">
            {{-- JS page numbers --}}
        </div>

        <button id="nextBtn"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700
                   hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Next
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
</main>

<script>
    // ✅ 12 placeholder jobs
    const JOBS = [
        { title: 'Domestic Helper', company: 'Golden Home Staffing', tag: 'Domestic', salary: '$450 - $650/month', location: 'Riyadh, Saudi Arabia', vacancies: '30 vacancies', posted: 'Posted 2 days ago', applyUrl: '#' },
        { title: 'Caregiver', company: 'Global Care Agency', tag: 'Healthcare', salary: '$700 - $900/month', location: 'Tokyo, Japan', vacancies: '12 vacancies', posted: 'Posted 1 day ago', applyUrl: '#' },
        { title: 'Hotel Staff', company: 'Sunrise HR', tag: 'Hospitality', salary: '$500 - $750/month', location: 'Dubai, UAE', vacancies: '20 vacancies', posted: 'Posted 5 days ago', applyUrl: '#' },
        { title: 'Factory Worker', company: 'ABC Recruitment', tag: 'Manufacturing', salary: '$480 - $700/month', location: 'Jeddah, Saudi Arabia', vacancies: '18 vacancies', posted: 'Posted 3 days ago', applyUrl: '#' },
        { title: 'Welder', company: 'BuildPro Intl', tag: 'Construction', salary: '$600 - $850/month', location: 'Doha, Qatar', vacancies: '10 vacancies', posted: 'Posted 4 days ago', applyUrl: '#' },
        { title: 'Warehouse Picker', company: 'LogiWorld', tag: 'Logistics', salary: '$650 - $900/month', location: 'Toronto, Canada', vacancies: '25 vacancies', posted: 'Posted 6 days ago', applyUrl: '#' },
        { title: 'Kitchen Helper', company: 'FoodBridge', tag: 'Hospitality', salary: '$450 - $650/month', location: 'Singapore', vacancies: '15 vacancies', posted: 'Posted 1 week ago', applyUrl: '#' },
        { title: 'Nurse Assistant', company: 'CarePlus', tag: 'Healthcare', salary: '$800 - $1,100/month', location: 'Osaka, Japan', vacancies: '8 vacancies', posted: 'Posted 2 days ago', applyUrl: '#' },
        { title: 'Cleaner', company: 'Prime Staff', tag: 'Domestic', salary: '$400 - $600/month', location: 'Abu Dhabi, UAE', vacancies: '22 vacancies', posted: 'Posted 3 days ago', applyUrl: '#' },
        { title: 'Driver', company: 'TranspoWorks', tag: 'Transportation', salary: '$550 - $750/month', location: 'Doha, Qatar', vacancies: '14 vacancies', posted: 'Posted 5 days ago', applyUrl: '#' },
        { title: 'IT Support', company: 'TechLink', tag: 'Technology', salary: '$900 - $1,300/month', location: 'Sydney, Australia', vacancies: '6 vacancies', posted: 'Posted 2 weeks ago', applyUrl: '#' },
        { title: 'Machine Operator', company: 'ManuHire', tag: 'Manufacturing', salary: '$520 - $780/month', location: 'Dammam, Saudi Arabia', vacancies: '16 vacancies', posted: 'Posted 4 days ago', applyUrl: '#' },
    ];

    // Elements
    const grid = document.getElementById('jobsGrid');
    const showingText = document.getElementById('showingText');
    const paginationNumbers = document.getElementById('paginationNumbers');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Pagination state
    let currentPage = 1;
    let perPage = 9; // desktop default

    function calcPerPage() {
        // ✅ mobile: 6, desktop (lg): 9
        perPage = window.matchMedia('(min-width: 1024px)').matches ? 9 : 6;
    }

    function totalPages() {
        return Math.ceil(JOBS.length / perPage);
    }

    function sliceJobs(page) {
        const start = (page - 1) * perPage;
        const end = start + perPage;
        return { start, end, items: JOBS.slice(start, end) };
    }

    function renderJobs() {
        const { start, end, items } = sliceJobs(currentPage);

        // Clear grid
        grid.innerHTML = '';

        // Render cards (same markup style as your component)
        items.forEach(job => {
            grid.insertAdjacentHTML('beforeend', `
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">${job.title}</h3>
                            <p class="text-sm text-gray-500 mt-1">${job.company}</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition" aria-label="Save job">
                            <i data-lucide="bookmark" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mt-4">
                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
                            ${job.tag}
                        </span>
                    </div>

                    <div class="mt-5 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
                            <span class="font-semibold text-gray-900">${job.salary}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            <span>${job.location}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            <span>${job.vacancies}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            <span>${job.posted}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="${job.applyUrl}"
                           class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A] px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
                            Apply Now
                        </a>
                    </div>
                </div>
            `);
        });

        // Update showing text
        const showingStart = start + 1;
        const showingEnd = Math.min(end, JOBS.length);
        showingText.innerHTML = `Showing <strong>${showingStart}</strong> to <strong>${showingEnd}</strong> out of <strong>${JOBS.length}</strong> jobs`;

        // Update buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages();

        // Refresh icons after DOM update
        lucide.createIcons();
    }

    function renderPagination() {
        const pages = totalPages();
        paginationNumbers.innerHTML = '';

        // helper: create page button
        const pageBtn = (page) => {
            const isActive = page === currentPage;
            return `
                <button data-page="${page}"
                    class="min-w-[42px] h-11 rounded-xl text-sm font-semibold transition
                           ${isActive
                               ? 'bg-[#16A34A] text-white shadow-sm'
                               : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'}">
                    ${page}
                </button>
            `;
        };

        // Show 1 2 3 4 ... style (modern)
        // We'll show: first, up to 4 pages, and last if many
        if (pages <= 5) {
            for (let p = 1; p <= pages; p++) paginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));
        } else {
            // always show 1
            paginationNumbers.insertAdjacentHTML('beforeend', pageBtn(1));

            // show middle window
            const start = Math.max(2, currentPage - 1);
            const end = Math.min(pages - 1, currentPage + 1);

            // dots before
            if (start > 2) paginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);

            for (let p = start; p <= end; p++) paginationNumbers.insertAdjacentHTML('beforeend', pageBtn(p));

            // dots after
            if (end < pages - 1) paginationNumbers.insertAdjacentHTML('beforeend', `<span class="px-2 text-gray-400">…</span>`);

            // last
            paginationNumbers.insertAdjacentHTML('beforeend', pageBtn(pages));
        }

        // attach click listeners
        paginationNumbers.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                currentPage = Number(btn.dataset.page);
                renderPagination();
                renderJobs();
            });
        });
    }

    // Prev/Next
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPagination();
            renderJobs();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages()) {
            currentPage++;
            renderPagination();
            renderJobs();
        }
    });

    // On load + responsive change
    function init() {
        calcPerPage();
        // ensure current page valid after resize
        currentPage = Math.min(currentPage, totalPages());
        renderPagination();
        renderJobs();
    }

    window.addEventListener('resize', () => {
        const oldPerPage = perPage;
        calcPerPage();
        if (oldPerPage !== perPage) init();
    });

    // Keep your existing filter + form script (optional)
    const filterCards = document.querySelectorAll('.filter-card');
    filterCards.forEach(card => {
        card.addEventListener('click', () => {
            const span = card.querySelector('span');
            const hidden = card.querySelector('input[type="hidden"]');

            card.classList.toggle('bg-green-50');
            card.classList.toggle('border-green-600');

            if (span) span.classList.toggle('text-green-700');
            if (hidden) hidden.value = (hidden.value === "0") ? "1" : "0";
        });
    });

    document.getElementById("jobSearchForm")?.addEventListener("submit", (e) => {
        e.preventDefault();
        alert("Search submitted! (Connect this to backend later)");
    });

    // Initial icons + render
    lucide.createIcons();
    init();
</script>
