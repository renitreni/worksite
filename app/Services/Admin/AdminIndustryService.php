<?php

namespace App\Services\Admin;

use App\Models\Industry;
use Illuminate\Http\Request;
use App\Support\HandlesPublicImage;

class AdminIndustryService
{
    use HandlesPublicImage;

    private const IMG_DIR = 'industries';

    public function getIndustries(Request $request): array
    {
        $q = (string) $request->query('q', '');
        $active = $request->query('active', '');

        $query = Industry::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($active !== '') {
            $query->where('is_active', (int) $active);
        }

        $industries = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return compact('industries','q','active');
    }

    public function createIndustry(Request $request): void
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:industries,name'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $industry = new Industry();

        $industry->name = $data['name'];
        $industry->is_active = (int) ($data['is_active'] ?? 0);
        $industry->sort_order = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $industry->image = $this->storePublicImage(
                $request->file('image'),
                self::IMG_DIR
            );
        }

        $industry->save();
    }

    public function updateIndustry(Request $request, Industry $industry): void
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:industries,name,' . $industry->id],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $industry->name = $data['name'];
        $industry->is_active = (int) ($data['is_active'] ?? 0);
        $industry->sort_order = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {

            $old = $industry->image;

            $industry->image = $this->storePublicImage(
                $request->file('image'),
                self::IMG_DIR
            );

            $this->deletePublicImage($old);
        }

        $industry->save();
    }

    public function deleteIndustry(Industry $industry): void
    {
        $this->deletePublicImage($industry->image ?? null);

        $industry->delete();
    }

    public function updateMeta(Request $request, Industry $industry): void
    {
        $data = $request->validate([
            'sort_order' => ['required','integer','min:0'],
            'is_active' => ['required','in:0,1'],
        ]);

        $industry->update([
            'sort_order' => (int) $data['sort_order'],
            'is_active' => (int) $data['is_active'],
        ]);
    }
}