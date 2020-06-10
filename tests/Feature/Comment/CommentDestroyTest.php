<?php

namespace Tests\Feature\Comment;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;

class CommentDestroyTest extends TestCase
{
    /** @test */
    public function endpoint_withoutPermission_shouldReturn_401()
    {
        $user = factory(User::class)->create();
        $comment = factory(Comment::class)->create();

        $this->actingAs($user);

        $response = $this->deleteJson(
            "/api/comments/{$comment->id}"
        );

        $response->assertResponseError('This action is unauthorized.', 401);
    }

    /** @test */
    public function endpoint_withInvalidUrlParameters_shouldReturn_404()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->deleteJson(
            "/api/comments/1337"
        );

        $response->assertResponseError('No query results for model [App\Models\Comment] 1337', 404);
    }

    /** @test */
    public function endpoint_withoutAuth_shouldReturn_401()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->deleteJson(
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

        $response = $this->deleteJson(
            "/api/comments/{$comment->id}"
        );

        $response->assertResponseError('Your email address is not verified.', 403);
    }
}
