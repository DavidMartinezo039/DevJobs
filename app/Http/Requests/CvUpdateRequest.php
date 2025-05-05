<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CvUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'new_image' => 'nullable|image|max:2048',
            'about_me' => 'required|string',
            'workPermits' => 'nullable|array',
            'workPermits.*' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'nationalities' => 'nullable|array',
            'nationalities.*' => 'nullable|string|max:255',
            'emails' => 'nullable|array',
            'emails.*' => 'nullable|email',
            'addresses' => 'nullable|array',
            'addresses.*' => 'nullable|string|max:255',
            'gender_id' => 'nullable|exists:genders,id',
            'identity_documents' => 'nullable|array',
            'identity_documents.*.identity_id' => 'nullable|exists:identities,id',
            'identity_documents.*.number' => 'nullable|string|max:100',
            'phones' => 'nullable|array',
            'phones.*.phone_id' => 'nullable|exists:phones,id',
            'phones.*.number' => 'nullable|string|max:20',
            'socialMedia' => 'nullable|array',
            'socialMedia.*.social_media_id' => 'nullable|exists:social_media,id',
            'socialMedia.*.user_name' => 'nullable|string|min:3|max:255',
            'socialMedia.*.url' => 'nullable|url|max:255',
            'workExperiences' => 'nullable|array',
            'workExperiences.*.company' => 'nullable|string|max:255',
            'workExperiences.*.position' => 'nullable|string|max:255',
            'workExperiences.*.start' => 'nullable|date',
            'workExperiences.*.end' => 'nullable|date|after_or_equal:workExperiences.*.start',
            'workExperiences.*.description' => 'nullable|string',
            'educations' => 'nullable|array',
            'educations.*.school' => 'nullable|string|max:255',
            'educations.*.degree' => 'nullable|string|max:255',
            'educations.*.city' => 'nullable|string|max:255',
            'educations.*.country' => 'nullable|string|max:255',
            'educations.*.start' => 'nullable|date',
            'educations.*.end' => 'nullable|date|after_or_equal:educations.*.start',
            'educations.*.description' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*.language_id' => 'nullable|exists:languages,id',
            'languages.*.level' => 'nullable|string|max:50',
            'skills' => 'nullable|array',
            'skills.*.digital_skill_id' => 'nullable|exists:digital_skills,id',
            'skills.*.level' => 'nullable|string|max:50',
            'drivingLicenses' => 'nullable|array',
            'drivingLicenses.*.driving_license_id' => 'nullable|exists:driving_licenses,id',
        ];
    }
}
