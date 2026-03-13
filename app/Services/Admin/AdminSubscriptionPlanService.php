<?php

namespace App\Services\Admin;

use App\Models\SubscriptionPlan;
use App\Models\FeatureDefinition;
use App\Models\PlanFeature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSubscriptionPlanService
{
    public function getPlans(Request $request): array
    {
        $q = trim((string) $request->query('q',''));

        $plans = SubscriptionPlan::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('code','like',"%{$q}%");
                });
            })
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return compact('plans','q');
    }

    public function getCreateData(): array
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return compact('features');
    }

    public function createPlan(Request $request): void
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $data = $request->validate([
            'code' => ['required','string','max:50','regex:/^[A-Za-z0-9_]+$/','unique:subscription_plans,code'],
            'name' => ['required','string','max:120'],
            'price' => ['required','numeric','min:0'],
            'is_active' => ['nullable','boolean'],
            'feature_values' => ['nullable','array'],
        ]);

        $data['code'] = strtoupper($data['code']);

        $plan = SubscriptionPlan::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'price' => $data['price'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        $this->syncPlanFeatures($plan, $features, $request->input('feature_values', []));
    }

    public function getEditData(SubscriptionPlan $plan): array
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $plan->load(['featureValues.definition']);

        $existing = $plan->featureValues
            ->mapWithKeys(fn($pf) => [$pf->feature_definition_id => $pf->value])
            ->toArray();

        return compact('plan','features','existing');
    }

    public function getShowData(SubscriptionPlan $plan): array
    {
        $plan->load(['featureValues.definition']);

        return compact('plan');
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan): void
    {
        $features = FeatureDefinition::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $data = $request->validate([
            'code' => [
                'required','string','max:50',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('subscription_plans','code')->ignore($plan->id)
            ],
            'name' => ['required','string','max:120'],
            'price' => ['required','numeric','min:0'],
            'is_active' => ['nullable','boolean'],
            'feature_values' => ['nullable','array'],
        ]);

        $data['code'] = strtoupper($data['code']);

        $plan->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'price' => $data['price'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        $this->syncPlanFeatures($plan, $features, $request->input('feature_values', []));
    }

    public function deletePlan(SubscriptionPlan $plan): void
    {
        $plan->delete();
    }

    private function syncPlanFeatures($plan, $featuresById, array $submitted): void
    {
        foreach ($featuresById as $featureId => $def) {

            $raw = $submitted[$featureId] ?? null;

            $value = match ($def->type) {
                'boolean' => (bool) $raw,
                'number'  => ($raw === '' || $raw === null) ? null : (int) $raw,
                'select'  => ($raw === '' || $raw === null)
                                ? ($def->default_value ?? null)
                                : (string) $raw,
                'text'    => ($raw === null) ? null : (string) $raw,
                default   => $raw,
            };

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