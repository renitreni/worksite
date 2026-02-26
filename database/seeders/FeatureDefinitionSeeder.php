<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureDefinition;

class FeatureDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        $features = [

            // Job limits
            [
                'key' => 'job_limit_active',
                'label' => 'Active Job Post Limit',
                'type' => 'number',
                'default_value' => null,
                'is_core' => true,
            ],

            // Candidate daily views
            [
                'key' => 'candidate_profile_views_per_day',
                'label' => 'Daily Candidate Profile Views',
                'type' => 'number',
                'default_value' => null,
                'is_core' => true,
            ],

            // Candidate info level
            [
                'key' => 'candidate_info_level',
                'label' => 'Candidate Information Level',
                'type' => 'select',
                'options' => ['basic_preview', 'expanded', 'full'],
                'default_value' => 'basic_preview',
                'is_core' => true,
            ],

            // CV Access
            [
                'key' => 'cv_access',
                'label' => 'CV Access Level',
                'type' => 'select',
                'options' => ['none', 'preview', 'download'],
                'default_value' => 'none',
                'is_core' => true,
            ],

            // Messaging
            [
                'key' => 'direct_messaging',
                'label' => 'Direct Messaging',
                'type' => 'boolean',
                'default_value' => false,
                'is_core' => true,
            ],

            // Filters
            [
                'key' => 'advanced_candidate_filters',
                'label' => 'Advanced Candidate Filters',
                'type' => 'boolean',
                'default_value' => false,
                'is_core' => true,
            ],

            // Analytics
            [
                'key' => 'analytics_level',
                'label' => 'Analytics Level',
                'type' => 'select',
                'options' => ['basic', 'advanced', 'enterprise'],
                'default_value' => 'basic',
                'is_core' => true,
            ],

            // Search visibility
            [
                'key' => 'search_visibility',
                'label' => 'Search Visibility Level',
                'type' => 'select',
                'options' => ['normal', 'featured', 'priority'],
                'default_value' => 'normal',
                'is_core' => true,
            ],
        ];

        foreach ($features as $feature) {
            FeatureDefinition::updateOrCreate(
                ['key' => $feature['key']],
                [
                    'label' => $feature['label'],
                    'type' => $feature['type'],
                    'options' => $feature['options'] ?? null,
                    'default_value' => $feature['default_value'] ?? null,
                    'is_core' => $feature['is_core'] ?? false,
                    'is_active' => true,
                ]
            );
        }
    }
}