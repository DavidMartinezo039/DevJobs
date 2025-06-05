<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="DrivingLicenseRequest",
 *     title="Driving License Request",
 *     description="Request body for creating/updating a driving license",
 *     required={"category", "description"},
 *     @OA\Property(property="category", type="string", example="B"),
 *     @OA\Property(property="description", type="string", example="Can drive cars"),
 *     @OA\Property(property="only_god", type="boolean", example=true)
 * )
 */
class DrivingLicenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'max_speed' => 'nullable|numeric',
            'max_power' => 'nullable|numeric',
            'power_to_weight' => 'nullable|numeric',
            'max_weight' => 'nullable|numeric',
            'max_passengers' => 'nullable|integer',
            'min_age' => 'required|integer|min:0|max:99',
        ];
    }
}
