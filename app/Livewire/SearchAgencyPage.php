<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\EmployerProfile;
use App\Models\Industry;

class SearchAgencyPage extends Component
{
    use WithPagination;

    #[Url(as: 'keyword')]
    public string $keyword = '';

    #[Url(as: 'industry')]
    public string $industry = '';

    protected $paginationTheme = 'tailwind';

    public function updating($name)
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->keyword = '';
        $this->industry = '';
        $this->resetPage();
    }

    public function query()
    {
        return EmployerProfile::query()
            ->with(['industries']) // if you have relationship
            ->whereNotNull('company_name')
            ->when(trim($this->keyword) !== '', function ($q) {
                $kw = trim($this->keyword);
                $q->where('company_name', 'like', "%{$kw}%");
            })
            ->when($this->industry !== '', function ($q) {
                $q->whereHas('industries', function ($qq) {
                    $qq->where('name', $this->industry);
                });
            })
            ->latest();
    }

    public function render()
    {
        $agencies = $this->query()->paginate(9);

        $industries = Industry::where('is_active', true)
            ->orderBy('name')
            ->get(['id','name']);

        return view('livewire.search-agency-page', compact('agencies','industries'));
    }
}