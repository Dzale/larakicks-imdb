<?php

namespace Tests\Feature\Movie;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\User;
use App\Models\Actor;
use App\Models\Director;

class MovieSearchActorsTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/movies/{$movie->id}/actors"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->getJson(
            "/api/movies/1337/actors"
        );

        $response->assertResponseError('No query results for model [App\Models\Movie] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->getJson(
            "/api/movies/{$movie->id}/actors"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/movies/{$movie->id}/actors"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
