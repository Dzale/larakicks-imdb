<?php

namespace Tests\Feature\Actor;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Actor;
use App\Models\User;
use App\Models\Movie;

class ActorShowTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/actors/{$actor->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->getJson(
            "/api/actors/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Actor] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->getJson(
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

        $response = $this->getJson(
            "/api/actors/{$actor->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
