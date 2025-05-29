<?php

use App\Http\Requests\CvCreateRequest;
use Illuminate\Support\Facades\Validator;

it('returns correct validation rules', function () {
    $request = new CvCreateRequest();

    $rules = $request->rules();

    expect($rules)->toBeArray()
        ->and($rules)->toHaveKey('title')
        ->and($rules['title'])->toBe('required|string|max:255')
        ->and($rules)->toHaveKey('first_name')
        ->and($rules['first_name'])->toBe('required|string|max:255')
        ->and($rules)->toHaveKey('workExperiences.*.end')
        ->and($rules['workExperiences.*.end'])->toContain('date')
        ->and($rules['workExperiences.*.end'])->toContain('after_or_equal:workExperiences.*.start');
});

it('passes validation with valid data', function () {
    $data = [
        'title' => 'My CV Title',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'about_me' => 'Some info about me',
        'workExperiences' => [
            [
                'company' => 'Company 1',
                'position' => 'Developer',
                'start' => '2020-01-01',
                'end' => '2021-01-01',
                'description' => 'Worked on projects'
            ]
        ],
        'educations' => [
            [
                'school' => 'University',
                'degree' => 'Bachelor',
                'start' => '2015-01-01',
                'end' => '2019-01-01'
            ]
        ],
        'emails' => ['john@example.com', 'doe@example.com'],
        'gender_id' => null, // assuming nullable
    ];

    $request = new CvCreateRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation with invalid data', function () {
    $data = [
        'title' => '',  // required
        'first_name' => 123, // must be string
        'last_name' => '', // required
        'about_me' => '', // required
        'emails' => ['not-an-email'],
        'workExperiences' => [
            [
                'start' => '2021-01-01',
                'end' => '2020-01-01', // end before start, should fail after_or_equal
            ]
        ],
        'educations' => [
            [
                'start' => '2021-01-01',
                'end' => '2020-01-01', // end before start
            ]
        ],
    ];

    $request = new CvCreateRequest();
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

