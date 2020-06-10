<?php

namespace Tests\Feature\Director;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Director;
use App\Models\User;
use App\Models\Movie;

class DirectorSearchMoviesTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $director = factory(Director::class)->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/directors/{$director->id}/movies"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->getJson(
            "/api/directors/1337/movies"
        );

        $response->assertResponseError('No query results for model [App\Models\Director] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $director = factory(Director::class)->create();

        $response = $this->getJson(
            "/api/directors/{$director->id}/movies"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $director = factory(Director::class)->create();

        $this->actingAs($user);

        $response = $this->getJson(
            "/api/directors/{$director->id}/movies"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
