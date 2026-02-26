<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\FeatureDefinition;
use App\Models\PlanFeature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $plans = SubscriptionPlan::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            })
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.plans.index', compact('plans', 'q'));
    }

    public function create()
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('adminpage.contents.subscriptions.plans.create', compact('features'));
    }

    public function store(Request $request)
    {
        $features = FeatureDefinition::query()->where('is_active', true)->get()->keyBy('id');

        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'regex:/^[A-Z0-9_]+$/', 'unique:subscription_plans,code'],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            // dynamic feature values coming from UI
            'feature_values' => ['nullable', 'array'],
        ]);

        $data['code'] = strtoupper($data['code']);

        $plan = SubscriptionPlan::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'price' => $data['price'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        $this->syncPlanFeatures($plan, $features, $request->input('feature_values', []));

        return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan created.');
    }



    public function edit(SubscriptionPlan $plan)
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $plan->load(['featureValues.definition']);

        // map existing values for easy binding in Blade
        $existing = $plan->featureValues
            ->mapWithKeys(fn($pf) => [$pf->feature_definition_id => $pf->value])
            ->toArray();

        return view('adminpage.contents.subscriptions.plans.edit', compact('plan', 'features', 'existing'));
    }
    public function show(SubscriptionPlan $plan)
    {
        $plan->load(['featureValues.definition']);

        return view('adminpage.contents.subscriptions.plans.show', compact('plan'));
    }
    public function update(Request $request, SubscriptionPlan $plan)
    {
        $features = FeatureDefinition::query()->where('is_active', true)->get()->keyBy('id');

        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'regex:/^[A-Z0-9_]+$/', Rule::unique('subscription_plans', 'code')->ignore($plan->id)],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            'feature_values' => ['nullable', 'array'],
        ]);
        $data['code'] = strtoupper($data['code']);

        $plan->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'price' => $data['price'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        $this->syncPlanFeatures($plan, $features, $request->input('feature_values', []));

        return redirect()->route('admin.subscriptions.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }

    /**
     * Save plan feature values based on FeatureDefinition types.
     */
    private function syncPlanFeatures(SubscriptionPlan $plan, $featuresById, array $submitted): void
    {
        foreach ($featuresById as $featureId => $def) {
            $raw = $submitted[$featureId] ?? null;

            // Normalize based on type
            $value = match ($def->type) {
                'boolean' => (bool) $raw,
                'number'  => ($raw === '' || $raw === null) ? null : (int) $raw,
                'select'  => ($raw === '' || $raw === null) ? ($def->default_value ?? null) : (string) $raw,
                'text'    => ($raw === null) ? null : (string) $raw,
                default   => $raw,
            };

            // Optional: validate select options (safety)
            if ($def->type === 'select' && is_array($def->options) && $value !== null) {
                if (!in_array($value, $def->options, true)) {
                    $value = $def->default_value ?? null;
                }
            }

            PlanFeature::updateOrCreate(
                [
                    'plan_id' => $plan->id,
                    'feature_definition_id' => $def->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }
}
