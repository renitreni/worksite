<?php

namespace App\Services\Employer;

class EmployerPermissionService
{
    public function accessMatrix(string $candidateInfoLevel, string $cvAccess): array
    {
        $access = match ($candidateInfoLevel) {

            'basic_preview' => [
                'level'=>'basic_preview',
                'can_view_profile_picture'=>true,
                'can_view_full_name'=>true,
                'can_view_birthdate'=>true,
                'can_view_years_experience'=>true,
                'can_view_highest_education'=>true,
                'can_view_address_city_province_only'=>true,
                'can_view_short_bio'=>true,
                'can_view_work_history'=>false,
                'can_view_education_history'=>false,
                'can_view_social_links'=>false,
                'can_download_documents'=>false,
                'can_view_full_contact_info'=>false,
            ],

            'expanded' => [
                'level'=>'expanded',
                'can_view_profile_picture'=>true,
                'can_view_full_name'=>true,
                'can_view_birthdate'=>true,
                'can_view_years_experience'=>true,
                'can_view_highest_education'=>true,
                'can_view_address_city_province_only'=>true,
                'can_view_short_bio'=>true,
                'can_view_work_history'=>true,
                'can_view_education_history'=>true,
                'can_view_social_links'=>true,
                'can_download_documents'=>false,
                'can_view_full_contact_info'=>false,
            ],

            'full' => [
                'level'=>'full',
                'can_view_profile_picture'=>true,
                'can_view_full_name'=>true,
                'can_view_birthdate'=>true,
                'can_view_years_experience'=>true,
                'can_view_highest_education'=>true,
                'can_view_address_city_province_only'=>true,
                'can_view_short_bio'=>true,
                'can_view_work_history'=>true,
                'can_view_education_history'=>true,
                'can_view_social_links'=>true,
                'can_download_documents'=>true,
                'can_view_full_contact_info'=>true,
            ],

            default => [
                'level'=>'default',
                'can_view_profile_picture'=>false,
                'can_view_full_name'=>true,
                'can_view_birthdate'=>false,
                'can_view_years_experience'=>true,
                'can_view_highest_education'=>false,
                'can_view_address_city_province_only'=>true,
                'can_view_short_bio'=>false,
                'can_view_work_history'=>false,
                'can_view_education_history'=>false,
                'can_view_social_links'=>false,
                'can_download_documents'=>false,
                'can_view_full_contact_info'=>false,
            ]
        };

        $access['can_preview_cv'] = in_array($cvAccess,['preview','download'],true);
        $access['can_download_cv'] = ($cvAccess === 'download');

        return $access;
    }
}