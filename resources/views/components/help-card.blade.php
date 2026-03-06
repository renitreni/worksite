@props(['image', 'title', 'description', 'route'])

<div class="bg-white rounded-2xl shadow hover:shadow-md transition overflow-hidden flex flex-col">

    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-44 object-cover">

    <div class="p-5 flex flex-col flex-grow">

        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            {{ $title }}
        </h3>

        <p class="text-gray-600 text-sm mb-4">
            {{ $description }}
        </p>

        <a href="{{ $route }}"
            class="text-[#16A34A] font-medium text-sm hover:underline mt-auto flex items-center gap-1">

            View Articles

            <i data-lucide="arrow-right" class="w-4 h-4"></i>

        </a>

    </div>

</div>
