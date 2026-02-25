<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JobPost;
use App\Models\Country;

class HeroJobSearch extends Component
{
    public string $keyword = '';
    public string $country = ''; // will store Country.name (string)

    public array $quick = [
        'no_fee' => false,
        'hs_grad' => false,
        'no_exp' => false,
        'college_grad' => false,
        'masteral' => false,
        'phd' => false,
    ];

    public function toggleQuick(string $key): void
    {
        if (!array_key_exists($key, $this->quick)) return;
        $this->quick[$key] = !$this->quick[$key];
    }

    public function getCountriesProperty()
    {
        // ✅ Only countries that are used by OPEN + NOT HELD + NOT DISABLED jobs
        $countryNames = JobPost::query()
            ->open()
            ->notHeld()
            ->notDisabled()
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->pluck('country');

        // ✅ Keep only those that exist in countries table (prevents random strings)
        return Country::query()
            ->whereIn('name', $countryNames)
            ->orderBy('name')
            ->get(['name']);
    }

    public function search()
    {
        return redirect()->route('search-jobs', [
            'keyword' => $this->keyword ?: null,
            'country' => $this->country ?: null,

            'no_fee' => $this->quick['no_fee'] ? 1 : null,
            'hs_grad' => $this->quick['hs_grad'] ? 1 : null,
            'no_exp' => $this->quick['no_exp'] ? 1 : null,
            'college_grad' => $this->quick['college_grad'] ? 1 : null,
            'masteral' => $this->quick['masteral'] ? 1 : null,
            'phd' => $this->quick['phd'] ? 1 : null,
        ]);
    }

    public function render()
    {
        return view('livewire.hero-job-search');
    }
}
