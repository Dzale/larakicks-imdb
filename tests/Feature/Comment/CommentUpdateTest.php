<?php

namespace Tests\Feature\Comment;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Http\Requests\Comment\UpdateCommentRequest;

class CommentUpdateTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();

        $this->actingAs($user);
        $this->mock(
            UpdateCommentRequest::class,
            function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn(true);
                $mock->shouldReceive('user')->andReturn($user);
            }
        );

        $response = $this->putJson(
            "/api/comments/{$comment->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidData_forNonStringColumns_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/comments/{$comment->id}",
            [
                'rate' => 'test',
            ]
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['rate' => 'The rate must be an integer.']);
    }

    /** @test */
    public function endpoint_withoutData_shouldReturn_422()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/comments/{$comment->id}",
            []
        );

        $response->assertResponseError('The given data was invalid.');
        $response->assertJsonValidationErrors(['text' => 'The text field is required.']);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->putJson(
            "/api/comments/{$comment->id}"
        );

        $response->assertResponseError('Unauthenticated.', 401);
    }

    /** @test */
    public function endpoint_withoutVerifiedEmail_shouldReturn_403()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);
        $comment = factory(Comment::class)->create();

        $this->actingAs($user);

        $response = $this->putJson(
            "/api/comments/{$comment->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
