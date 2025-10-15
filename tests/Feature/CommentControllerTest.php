<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_comments_list(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory()->count(3)->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        $response = $this->getJson('/api/comments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'created_at',
                        'updated_at',
                        'user'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_get_single_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        $response = $this->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'body',
                    'user',
                    'commentable'
                ]
            ]);
    }

    public function test_authenticated_user_can_create_comment_for_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $commentData = [
            'body' => $this->faker->paragraph(),
        ];

        $response = $this->postJson("/api/posts/{$post->id}/comments", $commentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'body',
                    'user',
                    'commentable'
                ]
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => $commentData['body'],
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
    }

    public function test_authenticated_user_can_create_reply_to_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        Sanctum::actingAs($user);

        $replyData = [
            'body' => 'This is a reply to the comment',
        ];

        $response = $this->postJson("/api/comments/{$comment->id}/replies", $replyData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'body' => $replyData['body'],
            'user_id' => $user->id,
            'commentable_id' => $comment->id,
            'commentable_type' => Comment::class,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'body' => $this->faker->paragraph(),
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_update_own_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        Sanctum::actingAs($user);

        $updateData = [
            'body' => 'Updated comment content',
        ];

        $response = $this->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => $updateData['body'],
        ]);
    }

    public function test_user_cannot_update_others_comment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);
        $comment = Comment::factory()->create([
            'user_id' => $owner->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        Sanctum::actingAs($otherUser);

        $response = $this->putJson("/api/comments/{$comment->id}", [
            'body' => 'Trying to update',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_deleting_comment_cascades_to_replies(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        $reply = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $comment->id,
            'commentable_type' => Comment::class,
        ]);
        
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
    }

    public function test_can_get_post_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory()->count(2)->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        $response = $this->getJson("/api/posts/{$post->id}/comments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'user'
                    ]
                ]
            ]);
    }

    public function test_can_get_comment_replies(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
        
        Comment::factory()->count(2)->create([
            'user_id' => $user->id,
            'commentable_id' => $comment->id,
            'commentable_type' => Comment::class,
        ]);

        $response = $this->getJson("/api/comments/{$comment->id}/replies");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'user'
                    ]
                ]
            ]);
    }
}