<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Services\Admin\AdminSubscriptionPlanService;

class SubscriptionPlanController extends Controller
{
    public function __construct(
        private AdminSubscriptionPlanService $planService
    ) {}

    public function index(Request $request)
    {
        $data = $this->planService->getPlans($request);

        return view(
            'adminpage.contents.subscriptions.plans.index',
            $data
        );
    }

    public function create()
    {
        $data = $this->planService->getCreateData();

        return view(
            'adminpage.contents.subscriptions.plans.create',
            $data
        );
    }

    public function store(Request $request)
    {
        $this->planService->createPlan($request);

        return redirect()
            ->route('admin.subscriptions.plans.index')
            ->with('success', 'Plan created.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        $data = $this->planService->getEditData($plan);

        return view(
            'adminpage.contents.subscriptions.plans.edit',
            $data
        );
    }

    public function show(SubscriptionPlan $plan)
    {
        $data = $this->planService->getShowData($plan);

        return view(
            'adminpage.contents.subscriptions.plans.show',
            $data
        );
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $this->planService->updatePlan($request, $plan);

        return redirect()
            ->route('admin.subscriptions.plans.index')
            ->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $this->planService->deletePlan($plan);

        return back()->with('success', 'Plan deleted.');
    }
}