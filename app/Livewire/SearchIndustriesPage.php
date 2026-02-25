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
            ->whereColumn('job_posts.industry', 'industries.name'); // ✅ string match

        return Industry::query()
            ->where('is_active', true)
            ->when(trim($this->keyword) !== '', function ($q) {
                $kw = trim($this->keyword);
                $q->where('name', 'like', "%{$kw}%");
            })
            ->addSelect([
                'jobs_count' => $jobsCountSub,
            ])
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    /**
     * Always return 3 skills per industry.
     * Priority:
     * 1) Top skills used in that industry's jobs (open/notHeld/notDisabled)
     *    - sorted by usage count desc
     *    - tie-break by skills.sort_order
     * 2) If none found, fallback to global top 3 skills (skills table sort_order)
     */
    private function skillsMap(array $industryNames): array
    {
        // ✅ Global skill order map (for sorting)
        $allSkills = Skill::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('sort_order', 'name')
            ->toArray();

        // ✅ Fallback top 3 skills (always show kahit walang jobs)
        $fallbackTop3 = array_slice(array_keys($allSkills), 0, 3);

        // Initialize map with fallback (so even 0 job industries have skills)
        $map = [];
        foreach ($industryNames as $name) {
            $map[$name] = $fallbackTop3;
        }

        // Pull skills string from jobs
        $rows = JobPost::query()
            ->open()
            ->notHeld()
            ->notDisabled()
            ->whereIn('industry', $industryNames)
            ->get(['industry', 'skills']);

        // Count skill usage per industry
        $counts = []; // $counts[industry][skill] = frequency

        foreach ($rows as $r) {
            $industry = (string) $r->industry;
            $raw = $r->skills;

            if (!$raw) continue;

            $skills = [];

            // ✅ supports JSON array or CSV
            $rawStr = trim((string) $raw);
            if (str_starts_with($rawStr, '[')) {
                $decoded = json_decode($rawStr, true);
                if (is_array($decoded)) {
                    $skills = $decoded;
                }
            }

            if (empty($skills)) {
                $skills = preg_split('/[,|]/', $rawStr) ?: [];
            }

            $skills = collect($skills)
                ->map(fn ($s) => trim((string) $s))
                ->filter()
                ->values()
                ->all();

            foreach ($skills as $s) {
                $counts[$industry] ??= [];
                $counts[$industry][$s] = ($counts[$industry][$s] ?? 0) + 1;
            }
        }

        // Build top 3 per industry from counts (if any)
        foreach ($industryNames as $industry) {
            $list = $counts[$industry] ?? [];

            if (empty($list)) {
                // keep fallbackTop3
                continue;
            }

            // sort by:
            // 1) usage desc
            // 2) skills.sort_order asc (if exists, else 999999)
            // 3) name asc
            $sorted = collect($list)
                ->sort(function ($aCount, $bCount, $aKey = null, $bKey = null) {
                    // not used: we’ll do custom below
                    return 0;
                });

            $keys = array_keys($list);

            usort($keys, function ($a, $b) use ($list, $allSkills) {
                $ca = $list[$a] ?? 0;
                $cb = $list[$b] ?? 0;

                if ($ca !== $cb) return $cb <=> $ca; // usage desc

                $sa = $allSkills[$a] ?? 999999;
                $sb = $allSkills[$b] ?? 999999;

                if ($sa !== $sb) return $sa <=> $sb; // sort_order asc

                return strcmp($a, $b); // name asc
            });

            $top3 = array_slice($keys, 0, 3);

            // If less than 3, pad using fallback (unique)
            if (count($top3) < 3) {
                foreach ($fallbackTop3 as $fb) {
                    if (count($top3) >= 3) break;
                    if (!in_array($fb, $top3, true)) $top3[] = $fb;
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