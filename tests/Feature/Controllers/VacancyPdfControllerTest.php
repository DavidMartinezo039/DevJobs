<?php

use App\Models\Vacancy;

it('downloads the vacancy pdf successfully', function () {
    $vacancy = Vacancy::factory()->create();

    $response = $this->get(route('vacancy.download', $vacancy));

    $response->assertStatus(200);

    $response->assertHeader('content-type', 'application/pdf');

    expect($response->getContent())->not()->toBeEmpty();
});
