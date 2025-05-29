<?php

use App\Http\Requests\CvUpdateRequest;
use Illuminate\Support\Facades\Validator;

it('returns correct validation rules', function () {
    $request = new CvUpdateRequest();

    $rules = $request->rules();

    expect($rules)->toBeArray()
        ->and($rules)->toHaveKey('title')
        ->and($rules['title'])->toBe('required|string|max:255')
        ->and($rules)->toHaveKey('new_image')
        ->and($rules['new_image'])->toBe('nullable|image|max:2048')
        ->and($rules)->toHaveKey('workExperiences.*.end')
        ->and($rules['workExperiences.*.end'])->toContain('date')
        ->and($rules['workExperiences.*.end'])->toContain('after_or_equal:workExperiences.*.start');
});

it('passes validation with valid data', function () {
    $data = [
        'title' => 'Updated CV Title',
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'about_me' => 'Updated about me text',
        'workExperiences' => [
            [
                'company' => 'Company Updated',
                'position' => 'Manager',
                'start' => '2019-01-01',
                'end' => '2020-01-01',
                'description' => 'Managed stuff'
            ]
        ],
        'educations' => [
            [
                'school' => 'Updated University',
                'degree' => 'Master',
                'start' => '2010-01-01',
                'end' => '2014-01-01'
            ]
        ],
        'emails' => ['jane@example.com'],
        'gender_id' => null,
    ];

    $request = new CvUpdateRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation with invalid data', function () {
    $data = [
        'title' => '',  // required
        'first_name' => 555, // must be string
        'last_name' => '', // required
        'about_me' => '', // required
        'emails' => ['bad-email'],
        'workExperiences' => [
            [
                'start' => '2021-01-01',
                'end' => '2020-01-01', // invalid date order
            ]
        ],
        'educations' => [
            [
                'start' => '2021-01-01',
                'end' => '2020-01-01',
            ]
        ],
    ];

    $request = new CvUpdateRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('title'))->toBeTrue()
        ->and($validator->errors()->has('first_name'))->toBeTrue()
        ->and($validator->errors()->has('last_name'))->toBeTrue()
        ->and($validator->errors()->has('about_me'))->toBeTrue()
        ->and($validator->errors()->has('emails.0'))->toBeTrue()
        ->and($validator->errors()->has('workExperiences.0.end'))->toBeTrue()
        ->and($validator->errors()->has('educations.0.end'))->toBeTrue();

});
