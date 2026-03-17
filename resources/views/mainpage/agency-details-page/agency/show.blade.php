@extends('main')

@section('title',
    $agency->company_name .
    ' Recruitment Agency | Overseas Jobs for
    Filipinos')
@section('meta_description',
    'Explore job opportunities with ' .
    $agency->company_name .
    '. Verified recruitment agency
    offering overseas jobs for Filipinos. Apply now and work abroad.')

@section('canonical', url()->current())

@section('schema')
    @verbatim
        <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EmploymentAgency",
  "name": "{{ $agency->name }}",
  "url": "{{ url()->current() }}",
  "description": "Verified recruitment agency offering overseas jobs for Filipinos.",
  "areaServed": "Philippines"
}
</script>
    @endverbatim
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">

        @php
            $companyName = $agency->company_name ?? 'Agency';
        @endphp

        <nav class="flex items-center gap-3 text-sm text-gray-500 mb-2">
            <a href="/" class="hover:text-gray-700">Home</a> /
            <span>Agencies</span> /
            <span class="text-gray-700 font-medium">{{ $companyName }}</span>
        </nav>

        {{-- HEADER --}}
        @include('mainpage.agency-details-page.agency.partials._header', [
            'agency' => $agency,
            'openJobsCount' => $openJobsCount,
        ])

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT --}}
            <div class="lg:col-span-1 space-y-6">
                @include('mainpage.agency-details-page.agency.partials._about', [
                    'agency' => $agency,
                    'openJobsCount' => $openJobsCount,
                ])
            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-2">
                @include('mainpage.agency-details-page.agency.partials._jobs', [
                    'jobs' => $jobs,
                    'agency' => $agency,
                ])
            </div>
        </div>


    </div>
@endsection
