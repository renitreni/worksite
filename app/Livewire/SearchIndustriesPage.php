<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Industry;
use App\Models\JobPost;
use App\Models\Skill;

class SearchIndustriesPage extends Component
{
    use WithPagination;

    #[Url(as: 'keyword')]
    public string $keyword = '';

    public function updating($name): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->keyword = '';
        $this->resetPage();
    }

    public function query()
    {
        $jobsCountSub = JobPost::query()
            ->selectRaw('COUNT(*)')
            ->open()
            ->notHeld()
            ->notDisabled()
            ->whereColumn('job_posts.industry', 'industries.name');

        return Industry::query()
            ->where('is_active', true)
            ->when(trim($this->keyword) !== '', function ($q) {
                $kw = trim($this->keyword);
                $q->where('name', 'like', "%{$kw}%");
            })
            ->addSelect([
                'jobs_count' => $jobsCountSub,
            ])
            ->orderByDesc('jobs_count')   // ⭐ MOST JOBS FIRST
            ->orderBy('sort_order')       // fallback
            ->orderBy('name');
    }

    /**
     * Generate 3 skills per industry.
     * Priority:
     * 1) Skills used in job posts (ranked by frequency)
     * 2) Fallback to industry's own skills table
     */
    private function skillsMap(array $industryNames): array
    {
        $map = [];

        /**
         * ------------------------------------------------
         * 1️⃣ Load fallback skills from industries table
         * ------------------------------------------------
         */
        $industrySkills = Skill::query()
            ->where('is_active', true)
            ->whereHas('industry', function ($q) use ($industryNames) {
                $q->whereIn('name', $industryNames);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy(fn($s) => $s->industry->name)
            ->map(fn($g) => $g->pluck('name')->take(3)->values()->all())
            ->toArray();

        /**
         * ------------------------------------------------
         * 2️⃣ Initialize fallback map
         * ------------------------------------------------
         */
        foreach ($industryNames as $name) {
            $map[$name] = $industrySkills[$name] ?? [];
        }

        /**
         * ------------------------------------------------
         * 3️⃣ Get skills used in job posts
         * ------------------------------------------------
         */
        $rows = JobPost::query()
            ->open()
            ->notHeld()
            ->notDisabled()
            ->whereIn('industry', $industryNames)
            ->get(['industry', 'skills']);

        $counts = [];

        foreach ($rows as $r) {

            $industry = (string) $r->industry;
            $raw = $r->skills;

            if (!$raw)
                continue;

            $skills = [];

            $rawStr = trim((string) $raw);

            // JSON support
            if (str_starts_with($rawStr, '[')) {
                $decoded = json_decode($rawStr, true);
                if (is_array($decoded)) {
                    $skills = $decoded;
                }
            }

            // CSV fallback
            if (empty($skills)) {
                $skills = preg_split('/[,|]/', $rawStr) ?: [];
            }

            $skills = collect($skills)
                ->map(fn($s) => trim((string) $s))
                ->filter()
                ->values()
                ->all();

            foreach ($skills as $s) {
                $counts[$industry] ??= [];
                $counts[$industry][$s] = ($counts[$industry][$s] ?? 0) + 1;
            }
        }

        /**
         * ------------------------------------------------
         * 4️⃣ Select top 3 skills per industry
         * ------------------------------------------------
         */
        foreach ($industryNames as $industry) {

            $list = $counts[$industry] ?? [];

            if (empty($list)) {
                continue; // keep fallback
            }

            $keys = array_keys($list);

            usort($keys, function ($a, $b) use ($list) {

                $ca = $list[$a] ?? 0;
                $cb = $list[$b] ?? 0;

                if ($ca !== $cb) {
                    return $cb <=> $ca; // highest usage first
                }

                return strcmp($a, $b); // alphabetical
            });

            $top3 = array_slice($keys, 0, 3);

            // pad if less than 3
            if (count($top3) < 3) {

                foreach ($map[$industry] as $fallback) {

                    if (count($top3) >= 3)
                        break;

                    if (!in_array($fallback, $top3)) {
                        $top3[] = $fallback;
                    }
                }
            }

            $map[$industry] = $top3;
        }

        return $map;
    }

    public function render()
    {
        $industries = $this->query()->paginate(8);

        $names = $industries->getCollection()->pluck('name')->all();

        $skillsMap = $this->skillsMap($names);

        return view('livewire.search-industries-page', compact('industries', 'skillsMap'));
    }
}