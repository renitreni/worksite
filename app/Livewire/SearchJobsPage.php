<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\JobPost;
use App\Models\Country;

class SearchJobsPage extends Component
{
    use WithPagination;

    #[Url(as: 'keyword')] public string $keyword = '';
    #[Url(as: 'country')] public string $country = ''; // name string

    #[Url(as: 'no_fee')] public ?int $no_fee = null;
    #[Url(as: 'hs_grad')] public ?int $hs_grad = null;
    #[Url(as: 'no_exp')] public ?int $no_exp = null;
    #[Url(as: 'college_grad')] public ?int $college_grad = null;
    #[Url(as: 'masteral')] public ?int $masteral = null;
    #[Url(as: 'phd')] public ?int $phd = null;

    public function updating($name): void
    {
        $this->resetPage();
    }

    public function getCountriesProperty()
    {
        $countryNames = JobPost::query()
            ->open()->notHeld()->notDisabled()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->pluck('country');

        return Country::query()
            ->whereIn('name', $countryNames)
            ->orderBy('name')
            ->get(['name']);
    }

    private function educationMatch($level): bool
    {
        // You can adjust to your exact values in DB.
        // Example values you might store: "high_school", "college", "masteral", "phd"
        return true;
    }

    public function query()
    {
        $q = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->open()
            ->notHeld()
            ->notDisabled();

        if (trim($this->keyword) !== '') {
            $kw = trim($this->keyword);
            $q->where(function ($qq) use ($kw) {
                $qq->where('title', 'like', "%{$kw}%")
                    ->orWhere('job_description', 'like', "%{$kw}%")
                    ->orWhere('skills', 'like', "%{$kw}%");
            });
        }

        if ($this->country !== '') {
            $q->where('country', $this->country);
        }

        // ✅ No placement fee
        if ($this->no_fee) {
            $q->where(function ($qq) {
                $qq->whereNull('placement_fee')
                    ->orWhere('placement_fee', 0);
            });
        }

        // ✅ No experience
        if ($this->no_exp) {
            $q->where(function ($qq) {
                $qq->whereNull('min_experience_years')
                    ->orWhere('min_experience_years', 0);
            });
        }

        // ✅ Education filters (adjust matching based on your stored strings)
        if ($this->hs_grad) {
            $q->where('education_level', 'like', '%high%'); // change if exact value
        }
        if ($this->college_grad) {
            $q->where('education_level', 'like', '%college%'); // change if exact value
        }
        if ($this->masteral) {
            $q->where('education_level', 'like', '%master%'); // change if exact value
        }
        if ($this->phd) {
            $q->where('education_level', 'like', '%phd%'); // change if exact value
        }

        return $q->orderByDesc('posted_at')->orderByDesc('created_at');
    }

    public function render()
    {
        $jobs = $this->query()->paginate(9);

        return view('livewire.search-jobs-page', compact('jobs'));
    }

    public function toggle(string $key): void
    {
        if (!property_exists($this, $key)) return;

        $this->{$key} = $this->{$key} ? null : 1;

        $this->resetPage();
    }

    public function clearFilters(): void
    {
        // Clear filters + inputs
        $this->keyword = '';
        $this->country = '';

        $this->no_fee = null;
        $this->hs_grad = null;
        $this->no_exp = null;
        $this->college_grad = null;
        $this->masteral = null;
        $this->phd = null;

        $this->resetPage();
    }
}
