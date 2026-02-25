<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Country;
use App\Models\JobPost;

class SearchCountryPage extends Component
{
    use WithPagination;

    #[Url(as: 'keyword')]
    public string $keyword = '';

    #[Url(as: 'region')]
    public string $region = '';

    protected $paginationTheme = 'tailwind';

    public function updating()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->keyword = '';
        $this->region = '';
        $this->resetPage();
    }

    public function query()
    {
        return Country::query()
            ->where('is_active', true)
            ->when(trim($this->keyword) !== '', function ($q) {
                $q->where('name', 'like', '%' . trim($this->keyword) . '%');
            })
            ->when($this->region !== '', function ($q) {
                $q->where('region', $this->region);
            })
            ->withCount([
                'jobPosts as jobs_count' => function ($q) {
                    $q->open()->notHeld()->notDisabled();
                }
            ])
            ->orderByDesc('jobs_count')
            ->orderBy('name');
    }

    public function render()
    {
        $countries = $this->query()->paginate(9);

        return view('livewire.search-country-page', compact('countries'));
    }
}