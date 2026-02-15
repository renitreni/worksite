<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i data-lucide="info" class="w-4 h-4"></i>
        <span id="showingIndustryText">
            Showing <strong>1</strong> to <strong>9</strong> out of <strong>10</strong> industries
        </span>
    </div>

    <div id="industryGrid" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        {{-- JS render --}}
    </div>
</main>
<script>
const INDUSTRIES = [
    { name: "Domestic", jobs: 1200, image: "/images/domestic.jpg" },
    { name: "Caregiver", jobs: 860, image: "/images/caregiver.avif" },
    { name: "Construction", jobs: 740, image: "/images/construction.jpeg" },
    { name: "Factory", jobs: 920, image: "/images/factory.jpg" },
    { name: "Driver", jobs: 510, image: "/images/driver.webp" },
    { name: "Hospitality", jobs: 680, image: "/images/hospitality.png" },
    { name: "Food", jobs: 590, image: "/images/food.jpg" },
    { name: "Admin", jobs: 430, image: "/images/admin.jpg" },
    { name: "Beauty", jobs: 370, image: "/images/beauty.jpg" },
    { name: "Maritime", jobs: 295, image: "/images/maritime.avif" },
];

const industryGrid = document.getElementById("industryGrid");

function renderIndustries() {
    industryGrid.innerHTML = "";

    INDUSTRIES.forEach(ind => {
        industryGrid.insertAdjacentHTML("beforeend", `
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-md transition">
                
                <div class="h-40 bg-cover bg-center"
                     style="background-image:url('${ind.image}')"></div>

                <div class="p-4 text-center">
                    <h3 class="font-semibold text-gray-900">${ind.name}</h3>
                    <p class="text-sm text-gray-500">${ind.jobs} jobs</p>

                    <a href="#"
                       class="mt-3 inline-flex items-center justify-center text-sm font-semibold
                              text-[#16A34A] hover:underline">
                        View Jobs
                    </a>
                </div>
            </div>
        `);
    });

    lucide.createIcons();
}

renderIndustries();

document.getElementById("industrySearchForm")?.addEventListener("submit", e => {
    e.preventDefault();
    alert("Industry search later connect to backend");
});
</script>
