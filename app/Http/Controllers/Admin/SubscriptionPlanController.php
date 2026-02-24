<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $plans = SubscriptionPlan::query()
            ->when($q !== '', fn ($qr) => $qr->where('name', 'like', "%{$q}%")
                                           ->orWhere('code', 'like', "%{$q}%"))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.plans.index', compact('plans', 'q'));
    }

    public function create()
    {
        return view('adminpage.contents.subscriptions.plans.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'code' => ['required','string','max:50','uppercase', 'unique:subscription_plans,code'],
        'name' => ['required','string','max:120'],
        'price' => ['required','integer','min:0'],
        'is_active' => ['nullable','boolean'],

        // Features fields (structured UI)
        'features' => ['nullable','array'],

        'features.max_active_jobs' => ['nullable','integer','min:0'],      // null = unlimited
        'features.candidate_views_per_day' => ['nullable','integer','min:0'], // null = unlimited

        'features.featured_badge' => ['nullable','boolean'],
        'features.priority_placement' => ['nullable','boolean'],
        'features.homepage_priority' => ['nullable','boolean'],

        'features.can_message_candidates' => ['nullable','boolean'],

        // CV access mode: none | preview | download
        'features.cv_access' => ['nullable','in:none,preview,download'],

        // Analytics level: basic | advanced | dashboard
        'features.analytics_level' => ['nullable','in:basic,advanced,dashboard'],

        'features.advanced_filters' => ['nullable','boolean'],
        'features.priority_support' => ['nullable','boolean'],
        'features.branding_upgrades' => ['nullable','boolean'],
        'features.verification_badge' => ['nullable','boolean'],
        'features.hiring_pipeline' => ['nullable','boolean'],
        'features.conversion_tracking' => ['nullable','boolean'],
    ]);

    // checkbox normalization
    $data['is_active'] = (bool) ($data['is_active'] ?? true);

    $features = $data['features'] ?? [];

    foreach ([
        'featured_badge',
        'priority_placement',
        'homepage_priority',
        'can_message_candidates',
        'advanced_filters',
        'priority_support',
        'branding_upgrades',
        'verification_badge',
        'hiring_pipeline',
        'conversion_tracking',
    ] as $key) {
        $features[$key] = (bool) ($features[$key] ?? false);
    }

    // Normalize “unlimited” coming from UI as null
    if (($features['max_active_jobs'] ?? '') === '') $features['max_active_jobs'] = null;
    if (($features['candidate_views_per_day'] ?? '') === '') $features['candidate_views_per_day'] = null;

    // Defaults if not set
    $features['cv_access'] = $features['cv_access'] ?? 'none';
    $features['analytics_level'] = $features['analytics_level'] ?? 'basic';

    $data['features'] = $features;

    SubscriptionPlan::create($data);

    return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan created.');
}
    public function show(SubscriptionPlan $plan)
    {
        return view('adminpage.contents.subscriptions.plans.show', compact('plan'));
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('adminpage.contents.subscriptions.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
{
    $data = $request->validate([
        'code' => ['required','string','max:50','uppercase', Rule::unique('subscription_plans','code')->ignore($plan->id)],
        'name' => ['required','string','max:120'],
        'price' => ['required','integer','min:0'],
        'is_active' => ['nullable','boolean'],

        'features' => ['nullable','array'],

        'features.max_active_jobs' => ['nullable','integer','min:0'],
        'features.candidate_views_per_day' => ['nullable','integer','min:0'],

        'features.featured_badge' => ['nullable','boolean'],
        'features.priority_placement' => ['nullable','boolean'],
        'features.homepage_priority' => ['nullable','boolean'],

        'features.can_message_candidates' => ['nullable','boolean'],

        'features.cv_access' => ['nullable','in:none,preview,download'],
        'features.analytics_level' => ['nullable','in:basic,advanced,dashboard'],

        'features.advanced_filters' => ['nullable','boolean'],
        'features.priority_support' => ['nullable','boolean'],
        'features.branding_upgrades' => ['nullable','boolean'],
        'features.verification_badge' => ['nullable','boolean'],
        'features.hiring_pipeline' => ['nullable','boolean'],
        'features.conversion_tracking' => ['nullable','boolean'],
    ]);

    $data['is_active'] = (bool) ($data['is_active'] ?? false);

    $features = $data['features'] ?? [];

    foreach ([
        'featured_badge',
        'priority_placement',
        'homepage_priority',
        'can_message_candidates',
        'advanced_filters',
        'priority_support',
        'branding_upgrades',
        'verification_badge',
        'hiring_pipeline',
        'conversion_tracking',
    ] as $key) {
        $features[$key] = (bool) ($features[$key] ?? false);
    }

    if (($features['max_active_jobs'] ?? '') === '') $features['max_active_jobs'] = null;
    if (($features['candidate_views_per_day'] ?? '') === '') $features['candidate_views_per_day'] = null;

    $features['cv_access'] = $features['cv_access'] ?? 'none';
    $features['analytics_level'] = $features['analytics_level'] ?? 'basic';

    $data['features'] = $features;

    $plan->update($data);

    return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan updated.');
}

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }
}