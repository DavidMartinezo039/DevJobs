<?php

use App\Models\Vacancy;

it('shows a vacancy page successfully', function () {
    $vacancy = Vacancy::factory()->create();

    $response = $this->get(route('vacancies.show', $vacancy));

    $response->assertStatus(200);
    $response->assertViewIs('vacancies.show');
    $response->assertViewHas('vacancy', $vacancy);
});
