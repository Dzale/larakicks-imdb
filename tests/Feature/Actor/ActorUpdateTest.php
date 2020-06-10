<?php

namespace Tests\Feature\Actor;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Actor;
use App\Models\User;
use App\Models\Movie;
use App\Http\Requests\Actor\UpdateActorRequest;

class ActorUpdateTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();

        $this->actingAs($user);
        $this->mock(
            UpdateActorRequest::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn(true);
                $mock->shouldReceive('user')->andReturn($user);
            }
        );

        $response = $this->putJson(
            "/api/actors/{$actor->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidData_forNonStringColumns_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/actors/{$actor->id}",
            [
                'dob' => 'test',
            ]
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['dob' => 'The dob is not a valid date.']);
    }

    /** @test */
    public function endpoint_withoutData_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/actors/{$actor->id}",
            []
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['firstname' => 'The firstname field is required.']);
        $response->assertJsonValidationErrors(['lastname' => 'The lastname field is required.']);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->putJson(
            "/api/actors/{$actor->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $actor = factory(Actor::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/actors/{$actor->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
