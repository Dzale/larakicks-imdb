<?php

namespace Tests\Feature\User;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;
use App\Models\Comment;
use App\Models\Profile;

class UserDetachFavoriteMovieTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->deleteJson(
            "/api/users/favorite-movies/{$movie->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->deleteJson(
            "/api/users/favorite-movies/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Movie] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->deleteJson(
            "/api/users/favorite-movies/{$movie->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->deleteJson(
            "/api/users/favorite-movies/{$movie->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
