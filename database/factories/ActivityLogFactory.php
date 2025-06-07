<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id'     => $user?->id,
            'role'        => $user?->getRoleNames()->first() ?? 'guest',
            'action'      => $this->faker->randomElement([
                'created_cv', 'deleted_cv', 'downloaded_cv',
                'applied_job', 'withdrawn_application',
                'created_job', 'deleted_job', 'edited_job',
                'accepted_candidate', 'rejected_candidate',
            ]),
            'target_type' => $this->faker->randomElement([
                'App\Models\CV',
                'App\Models\Job',
                'App\Models\User',
            ]),
            'target_id'   => $this->faker->numberBetween(1, 50),
            'description' => $this->faker->sentence(),
        ];
    }
}

