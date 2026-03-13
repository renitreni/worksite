<?php

namespace App\Services\Admin;

use App\Models\Skill;
use App\Models\Industry;
use Illuminate\Http\Request;

class AdminSkillService
{
    public function getSkills(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $active = $request->query('active', '');
        $industryId = $request->query('industry_id', '');

        $skills = Skill::query()
            ->with('industry:id,name')
            ->when($q !== '', fn($qr) => $qr->where('name', 'like', "%{$q}%"))
            ->when(
                $active !== '' && in_array($active, ['0','1'], true),
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
            ->get(['id','name']);

        return compact('skills','q','active','industryId','industries');
    }

    public function getEditData(Skill $skill): array
    {
        $industries = Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id','name']);

        return compact('skill','industries');
    }

    public function createSkill(Request $request): void
    {
        $data = $request->validate([
            'industry_id' => ['required','exists:industries,id'],
            'name' => ['required','string','max:255','unique:skills,name'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        Skill::create([
            'industry_id' => (int) $data['industry_id'],
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function updateSkill(Request $request, Skill $skill): void
    {
        $data = $request->validate([
            'industry_id' => ['required','exists:industries,id'],
            'name' => ['required','string','max:255','unique:skills,name,' . $skill->id],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $skill->update([
            'industry_id' => (int) $data['industry_id'],
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function deleteSkill(Skill $skill): void
    {
        $skill->delete();
    }

    public function updateMeta(Request $request, Skill $skill): void
    {
        $data = $request->validate([
            'is_active' => ['required','boolean'],
            'sort_order' => ['required','integer','min:0'],
        ]);

        $skill->update([
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);
    }
}