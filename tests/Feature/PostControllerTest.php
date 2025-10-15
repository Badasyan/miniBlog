<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_posts_list(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'is_active',
                        'created_at',
                        'updated_at',
                        'user'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_get_single_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_active' => true
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'body',
                    'is_active',
                    'user',
                    'comments'
                ]
            ]);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $postData = [
            'body' => $this->faker->paragraph(),
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'body',
                    'is_active',
                    'user'
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'body' => $postData['body'],
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_post(): void
    {
        $response = $this->postJson('/api/posts', [
            'body' => $this->faker->paragraph(),
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_update_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $updateData = [
            'body' => 'Updated post content',
            'is_active' => false,
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => $updateData['body'],
            'is_active' => false,
        ]);
    }

    public function test_user_cannot_update_others_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($otherUser);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'body' => 'Trying to update',
            'is_active' => false,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_others_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403);
    }

    public function test_can_get_user_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/users/{$user->id}/posts");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'is_active',
                        'user'
                    ]
                ]
            ]);
    }

    public function test_can_get_user_active_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['user_id' => $user->id, 'is_active' => true]);
        Post::factory()->create(['user_id' => $user->id, 'is_active' => false]);

        $response = $this->getJson("/api/users/{$user->id}/posts/active");

        $response->assertStatus(200);

        $posts = $response->json('data');
        $this->assertCount(1, $posts);
        $this->assertTrue($posts[0]['is_active']);
    }
}
