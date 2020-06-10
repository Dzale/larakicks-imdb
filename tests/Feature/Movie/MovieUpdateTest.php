<?php

namespace Tests\Feature\Movie;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\User;
use App\Models\Actor;
use App\Models\Director;
use App\Http\Requests\Movie\UpdateMovieRequest;

class MovieUpdateTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);
        $this->mock(
            UpdateMovieRequest::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn(true);
                $mock->shouldReceive('user')->andReturn($user);
            }
        );

        $response = $this->putJson(
            "/api/movies/{$movie->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidData_forNonStringColumns_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/movies/{$movie->id}",
            [
                'genre' => 'test',
                'release_date' => 'test',
            ]
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['genre' => 'The selected genre is invalid.']);
        $response->assertJsonValidationErrors(['release_date' => 'The release date is not a valid date.']);
    }

    /** @test */
    public function endpoint_withoutData_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/movies/{$movie->id}",
            []
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
        $response->assertJsonValidationErrors(['genre' => 'The genre field is required.']);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->putJson(
            "/api/movies/{$movie->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $movie = factory(Movie::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/movies/{$movie->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
