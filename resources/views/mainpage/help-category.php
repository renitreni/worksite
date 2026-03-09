@extends('main')

@section('title','Help Category')

@section('content')

<section class="py-16">

    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12">

        {{-- SIDEBAR --}}
        <div>

            <h3 class="font-semibold text-gray-800 mb-6">
                Categories
            </h3>

            <ul class="space-y-4 text-sm">

                <li>
                    <a href="#" class="text-[#16A34A] font-semibold">
                        Getting Started
                    </a>
                </li>

                <li>
                    <a href="#" class="text-gray-600 hover:text-[#16A34A]">
                        Application Steps
                    </a>
                </li>

                <li>
                    <a href="#" class="text-gray-600 hover:text-[#16A34A]">
                        Account Settings
                    </a>
                </li>

                <li>
                    <a href="#" class="text-gray-600 hover:text-[#16A34A]">
                        Troubleshooting
                    </a>
                </li>

            </ul>

        </div>


        {{-- ARTICLES --}}
        <div class="md:col-span-3">

            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Getting Started
            </h1>

            <p class="text-gray-500 mb-8">
                Sign-up, resume update and email verification
            </p>


            <div class="space-y-4">

                <a href="#" class="block border-b pb-4 hover:text-[#16A34A]">
                    How do I sign up for a JobAbroad account?
                </a>

                <a href="#" class="block border-b pb-4 hover:text-[#16A34A]">
                    How do I verify my email address?
                </a>

                <a href="#" class="block border-b pb-4 hover:text-[#16A34A]">
                    How do I update my online resume?
                </a>

                <a href="#" class="block border-b pb-4 hover:text-[#16A34A]">
                    How do I upload my resume photo?
                </a>

                <a href="#" class="block border-b pb-4 hover:text-[#16A34A]">
                    How do I attach my resume?
                </a>

            </div>

        </div>

    </div>

</section>

@endsection