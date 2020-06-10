<?php

namespace Tests\Feature\Actor;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Actor;
use App\Models\User;
use App\Models\Movie;
use App\Http\Requests\Movie\MovieAttachActorRequest;

class ActorAttachMovieTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);
        $this->mock(
            MovieAttachActorRequest::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn(true);
                $mock->shouldReceive('user')->andReturn($user);
            }
        );

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(
            "/api/actors/1337/movies/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Actor] 1337', 404);

        $actor = factory(Actor::class)->create();

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Movie] 1337', 404);
    }

    /** @test */
    public function endpoint_withInvalidData_forNonStringColumns_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/{$movie->id}",
            [
                'role_type' => 'test',
            ]
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['role_type' => 'The selected role type is invalid.']);
    }

    /** @test */
    public function endpoint_withoutData_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $actor = factory(Actor::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/{$movie->id}",
            []
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['role' => 'The role field is required.']);
        $response->assertJsonValidationErrors(['role_type' => 'The role type field is required.']);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $actor = factory(Actor::class)->create();
        $movie = factory(Movie::class)->create();

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $actor = factory(Actor::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/actors/{$actor->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
