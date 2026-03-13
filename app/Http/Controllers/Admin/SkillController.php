<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Services\Admin\AdminSkillService;

class SkillController extends Controller
{
    public function __construct(
        private AdminSkillService $skillService
    ) {}

    public function index(Request $request)
    {
        $data = $this->skillService->getSkills($request);

        return view(
            'adminpage.contents.skills.index',
            $data
        );
    }

    public function edit(Skill $skill)
    {
        $data = $this->skillService->getEditData($skill);

        return view(
            'adminpage.contents.skills.edit',
            $data
        );
    }

    public function store(Request $request)
    {
        $this->skillService->createSkill($request);

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Skill created.');
    }

    public function update(Request $request, Skill $skill)
    {
        $this->skillService->updateSkill($request, $skill);

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Skill updated.');
    }

    public function destroy(Skill $skill)
    {
        $this->skillService->deleteSkill($skill);

        return back()->with('success', 'Skill deleted.');
    }

    public function updateMeta(Request $request, Skill $skill)
    {
        $this->skillService->updateMeta($request, $skill);

        return back()->with('success', 'Skill updated.');
    }
}