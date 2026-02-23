<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Support\HandlesPublicImage;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    use HandlesPublicImage;

    private const IMG_DIR = 'industries';

    public function index(Request $request)
{
    $q = (string) $request->query('q', '');
    $active = $request->query('active', '');

    $query = Industry::query();

    if ($q !== '') {
        $query->where('name', 'like', '%' . $q . '%');
    }

    // IMPORTANT: must use !== '' so "0" is not treated as empty
    if ($active !== '') {
        $query->where('is_active', (int) $active);
    }

    $industries = $query
        ->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('adminpage.contents.industries.index', compact('industries', 'q', 'active'));
}

    public function edit(Industry $industry)
    {
        return view('adminpage.contents.industries.edit', compact('industry'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:industries,name'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $industry = new Industry();
            $industry->name = $data['name'];
            $industry->is_active = (int) ($data['is_active'] ?? 0);
            $industry->sort_order = (int) ($data['sort_order'] ?? 0);

            if ($request->hasFile('image')) {
                $industry->image = $this->storePublicImage($request->file('image'), self::IMG_DIR);
            }

            $industry->save();

            return redirect()->route('admin.industries.index')->with('success', 'Industry created.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to create industry.');
        }
    }

    public function update(Request $request, Industry $industry)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:industries,name,' . $industry->id],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $industry->name = $data['name'];
            $industry->is_active = (int) ($data['is_active'] ?? 0);
            $industry->sort_order = (int) ($data['sort_order'] ?? 0);

            if ($request->hasFile('image')) {
                $old = $industry->image;
                $industry->image = $this->storePublicImage($request->file('image'), self::IMG_DIR);
                $this->deletePublicImage($old);
            }

            $industry->save();

            return redirect()->route('admin.industries.index')->with('success', 'Industry updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update industry.');
        }
    }

    public function destroy(Industry $industry)
    {
        try {
            $this->deletePublicImage($industry->image ?? null);
            $industry->delete();
            return back()->with('success', 'Industry deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete industry.');
        }
    }
    public function updateMeta(Request $request, Industry $industry)
{
    $validated = $request->validate([
        'sort_order' => ['required', 'integer', 'min:0'],
        'is_active'  => ['required', 'in:0,1'],
    ]);

    try {
        $industry->update([
            'sort_order' => (int) $validated['sort_order'],
            'is_active'  => (int) $validated['is_active'],
        ]);

        return back()->with('success', 'Industry updated.');
    } catch (\Throwable $e) {
        return back()->with('error', 'Failed to update industry.');
    }
}
}