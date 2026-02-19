<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $job->title }} | {{ config('app.name', 'Worksite') }}</title>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    [x-cloak] {
      display: none !important
    }
  </style>
</head>

<body class="font-['Inter',sans-serif] bg-white text-slate-900">
  @php
    $ep = $job->employerProfile;

    $company = $ep->company_name ?? 'Agency / Company';
    $logo = $ep->logo_path ?? null;

    $cur = $job->salary_currency ?? 'PHP';

    $salaryText = 'Not specified';

    if (!is_null($job->salary_min) || !is_null($job->salary_max)) {
      if (!is_null($job->salary_min) && !is_null($job->salary_max)) {
        $salaryText = $cur . ' ' . number_format((float) $job->salary_min) . ' - ' . number_format((float) $job->salary_max);
      } elseif (!is_null($job->salary_min)) {
        $salaryText = $cur . ' ' . number_format((float) $job->salary_min) . ' (min)';
      } else {
        $salaryText = $cur . ' ' . number_format((float) $job->salary_max) . ' (max)';
      }
    }



    $postedDate = ($job->posted_at ?? $job->created_at);
    $applyUntil = $job->apply_until ? \Carbon\Carbon::parse($job->apply_until)->format('M d, Y') : null;

    $ageText = 'Not specified';
    if ($job->age_min !== null && $job->age_max !== null)
      $ageText = "{$job->age_min} - {$job->age_max}";
    elseif ($job->age_min !== null)
      $ageText = "{$job->age_min} and above";
    elseif ($job->age_max !== null)
      $ageText = "Up to {$job->age_max}";

    $locationText = collect([$job->city, $job->area, $job->country])
      ->filter(fn($v) => !empty($v))
      ->implode(', ');

    $placementFeeText = ($job->placement_fee !== null && $job->placement_fee !== '')
      ? trim(($job->placement_fee_currency ?? 'PHP') . ' ' . number_format((float) $job->placement_fee, 2))
      : 'This job has no placement fee.';

    // If you already track "saved", pass $isSaved from controller (optional)
    $isSaved = (bool) ($isSaved ?? false);

    // helper: turn textarea to bullets if lines start with "-" or "•"
    function lines_to_list($text)
    {
      $text = (string) $text;
      $lines = collect(preg_split("/\r\n|\n|\r/", $text))
        ->map(fn($l) => trim($l))
        ->filter();
      return $lines;
    }
  @endphp
  @include('mainpage.components.navbar')

  <div class="font-['Inter',sans-serif] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @include('mainpage.job-details-page.partials.card-details')
    @include('mainpage.job-details-page.partials.other-jobs', ['agencyJobs' => $agencyJobs])


  </div>
  @include('mainpage.components.footer')
  <script>
    lucide.createIcons();
    document.addEventListener('alpine:init', () => {
      // re-render icons after DOM changes (optional)
      document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    });
  </script>

</body>

</html>