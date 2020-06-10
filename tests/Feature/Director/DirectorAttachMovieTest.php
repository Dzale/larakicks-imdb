<?php

namespace Tests\Feature\Director;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Director;
use App\Models\User;
use App\Models\Movie;
use App\Http\Requests\Movie\MovieAttachDirectorRequest;

class DirectorAttachMovieTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $director = factory(Director::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);
        $this->mock(
            MovieAttachDirectorRequest::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn(true);
                $mock->shouldReceive('user')->andReturn($user);
            }
        );

        $response = $this->postJson(
            "/api/directors/{$director->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->postJson(
            "/api/directors/1337/movies/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Director] 1337', 404);

        $director = factory(Director::class)->create();

        $response = $this->postJson(
            "/api/directors/{$director->id}/movies/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Movie] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $director = factory(Director::class)->create();
        $movie = factory(Movie::class)->create();

        $response = $this->postJson(
            "/api/directors/{$director->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $director = factory(Director::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(
            "/api/directors/{$director->id}/movies/{$movie->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
