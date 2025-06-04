<?php

namespace Database\Factories;

use App\Models\EditRequest;
use App\Models\DrivingLicense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EditRequestFactory extends Factory
{
    protected $model = EditRequest::class;

    public function definition()
    {
        return [
            'driving_license_id' => DrivingLicense::factory(),
            'requested_by'      => User::factory(),
            'approved'          => $this->faker->boolean(20),
        ];
    }

    public function approved()
    {
        return $this->state(fn (array $attributes) => [
            'approved' => true,
        ]);
    }

    public function notApproved()
    {
        return $this->state(fn (array $attributes) => [
            'approved' => false,
        ]);
    }
}
