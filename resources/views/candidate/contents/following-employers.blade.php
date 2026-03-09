@extends('candidate.layout')

@section('content')
    <div class="space-y-6" x-data="followingEmployersPage(@js($agencies))" x-init="init()">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                    Following Employers
                </h1>

                <p class="text-sm text-gray-500">
                    Employers you follow will appear here.
                </p>
            </div>


            {{-- SEARCH --}}
            <div class="relative w-full sm:max-w-md lg:w-96">

                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>

                <input type="text" placeholder="Search employers..."
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                    x-model="query" />

            </div>

        </div>


        {{-- EMPTY STATE --}}
        <template x-if="filteredEmployers().length === 0">

            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-10 text-center">

                <div
                    class="mx-auto h-12 w-12 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                    <i data-lucide="building-2" class="h-6 w-6 text-gray-400"></i>
                </div>

                <p class="mt-3 text-sm font-semibold text-gray-900">
                    No followed employers
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Follow companies to receive job updates.
                </p>

            </div>

        </template>


        {{-- EMPLOYER CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

            <template x-for="emp in filteredEmployers()" :key="emp.id">

                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">

                    <div class="flex flex-col items-center text-center">


                        {{-- LOGO --}}
                        <template x-if="emp.logo">

                            <img :src="emp.logo" class="h-16 w-16 rounded-2xl object-cover" />

                        </template>

                        <template x-if="!emp.logo">

                            <div class="h-16 w-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl font-semibold"
                                x-text="emp.name.charAt(0)">
                            </div>

                        </template>



                        {{-- COMPANY NAME --}}
                        <p class="mt-4 text-sm font-semibold text-gray-900" x-text="emp.name"></p>


                        {{-- LOCATION --}}
                        <p class="text-xs text-gray-500 mt-1" x-text="emp.location ?? 'Location not available'">
                        </p>



                        {{-- JOB COUNT --}}
                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">

                            <span class="inline-flex items-center gap-1">

                                <i data-lucide="briefcase" class="h-4 w-4"></i>

                                <span x-text="emp.open_jobs + ' Open Jobs'"></span>

                            </span>

                        </div>



                        {{-- ACTION BUTTONS --}}
                        <div class="mt-5 flex w-full gap-3">

                            <a :href="`/agencies/${emp.id}`"
                                class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 text-center">
                                View Profile
                            </a>


                            <form method="POST" :action="'/agency/' + emp.id + '/follow'" class="flex-1">

                                @csrf

                                <button type="submit"
                                    class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-semibold">

                                    Unfollow

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </template>

        </div>

    </div>



    {{-- ALPINE --}}
    <script>
        function followingEmployersPage(agencies) {

            return {

                query: '',

                employers: agencies,


                init() {

                    this.$nextTick(() => {
                        window.lucide?.createIcons();
                    });

                },


                filteredEmployers() {

                    if (!this.query) return this.employers

                    const q = this.query.toLowerCase()

                    return this.employers.filter(emp =>

                        (emp.name ?? '').toLowerCase().includes(q) ||
                        (emp.location ?? '').toLowerCase().includes(q)

                    )

                }

            }

        }
    </script>
@endsection
