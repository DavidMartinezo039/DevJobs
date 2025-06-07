<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="DrivingLicenseRequest",
 * title="Driving License Request",
 * description="Request body for creating/updating a driving license",
 * required={"category", "vehicle_type", "min_age"},
 * @OA\Property(property="category", type="string", example="H", description="Category of the driving license (e.g., A, B, C)"),
 * @OA\Property(property="vehicle_type", type="string", example="Automobile", description="Type of vehicle allowed by the license"),
 * @OA\Property(property="max_speed", type="number", format="float", nullable=true, example=200.5, description="Maximum speed allowed for vehicles (optional)"),
 * @OA\Property(property="max_power", type="number", format="float", nullable=true, example=150.0, description="Maximum power allowed for vehicles (optional)"),
 * @OA\Property(property="power_to_weight", type="number", format="float", nullable=true, example=0.2, description="Maximum power to weight ratio (optional)"),
 * @OA\Property(property="max_weight", type="number", format="float", nullable=true, example=3500.0, description="Maximum weight allowed for vehicles (optional)"),
 * @OA\Property(property="max_passengers", type="integer", nullable=true, example=8, description="Maximum number of passengers allowed (optional)"),
 * @OA\Property(property="min_age", type="integer", example=18, description="Minimum age required for this license"),
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
        $drivingLicenseId = $this->route('driving_license');

        return [
            'category' => [
                'required',
                'string',
                'max:255',
                Rule::unique('driving_licenses', 'category')->ignore($drivingLicenseId),
            ],
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
