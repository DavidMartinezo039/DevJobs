<?php

use App\Models\DigitalSkill;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{actingAs, deleteJson, getJson, post, get, postJson, put, delete, putJson};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->assignRole('moderator');
    actingAs($this->user);
});

it('returns a paginated list of digital skills', function () {
    DigitalSkill::factory()->count(3)->create();

    getJson(route('digital-skills.index'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
        $json->has(3)
        );
});

it('creates a new digital skill', function () {
    $data = ['name' => 'New Skill'];

    postJson(route('digital-skills.store'), $data)
        ->assertCreated()
        ->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'Digital skill created successfully')
            ->has('data.id')
            ->where('data.name', 'New Skill')
        );
});

it('shows a digital skill', function () {
    $digitalSkill = DigitalSkill::factory()->create();

    $response = getJson(route('digital-skills.show', $digitalSkill));

    $response->assertOk()->assertJson(fn (AssertableJson $json) =>
    $json->where('id', $digitalSkill->id)
        ->where('name', $digitalSkill->name)
        ->has('created_at')
        ->has('updated_at')
        ->etc()
    );
});

it('updates an existing digital skill', function () {
    $digitalSkill = DigitalSkill::factory()->create();
    $data = ['name' => 'Updated Skill'];

    putJson(route('digital-skills.update', $digitalSkill), $data)
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'Digital skill updated successfully')
            ->where('data.name', 'Updated Skill')
        );

    $this->assertDatabaseHas('digital_skills', ['id' => $digitalSkill->id, 'name' => 'Updated Skill']);
});

it('deletes a digital skill', function () {
    $digitalSkill = DigitalSkill::factory()->create();

    deleteJson(route('digital-skills.destroy', $digitalSkill))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'Digital skill deleted successfully')
        );

    $this->assertSoftDeleted('digital_skills', ['id' => $digitalSkill->id]);
});

it('prevents unauthorized users from updating or deleting digital skills', function () {
    $digitalSkill = DigitalSkill::factory()->create();
    $anotherUser = User::factory()->create();

    actingAs($anotherUser);

    putJson(route('digital-skills.update', $digitalSkill), ['name' => 'Unauthorized Update'])
        ->assertForbidden();

    deleteJson(route('digital-skills.destroy', $digitalSkill))
        ->assertForbidden();
});
