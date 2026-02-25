<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Industry;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $active = $request->query('active', '');          // '', '1', '0'
        $industryId = $request->query('industry_id', ''); // '' or id

        $skills = Skill::query()
            ->with('industry:id,name')
            ->when($q !== '', fn($qr) => $qr->where('name', 'like', "%{$q}%"))
            ->when(
                $active !== '' && in_array($active, ['0', '1'], true),
                fn($qr) => $qr->where('is_active', (int) $active)
            )
            ->when(
                $industryId !== '' && ctype_digit((string) $industryId),
                fn($qr) => $qr->where('industry_id', (int) $industryId)
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $industries = Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('adminpage.contents.skills.index', compact('skills', 'q', 'active', 'industryId', 'industries'));
    }

    public function edit(Skill $skill)
    {
        $industries = \App\Models\Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('adminpage.contents.skills.edit', compact('skill', 'industries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'industry_id' => ['required', 'exists:industries,id'],
            'name' => ['required', 'string', 'max:255', 'unique:skills,name'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            Skill::create([
                'industry_id' => (int) $data['industry_id'],
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.skills.index')->with('success', 'Skill created.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to create skill.');
        }
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'industry_id' => ['required', 'exists:industries,id'],
            'name' => ['required', 'string', 'max:255', 'unique:skills,name,' . $skill->id],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            $skill->update([
                'industry_id' => (int) $data['industry_id'],
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.skills.index')->with('success', 'Skill updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update skill.');
        }
    }

    public function destroy(Skill $skill)
    {
        try {
            $skill->delete();
            return back()->with('success', 'Skill deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete skill.');
        }
    }

    public function updateMeta(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $skill->update([
                'is_active' => (int) $data['is_active'],
                'sort_order' => (int) $data['sort_order'],
            ]);

            return back()->with('success', 'Skill updated.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to update skill.');
        }
    }
}
