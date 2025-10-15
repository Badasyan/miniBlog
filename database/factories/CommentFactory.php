<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => $this->faker->paragraph(),
            'commentable_id' => Post::factory(),
            'commentable_type' => Post::class,
        ];
    }

    /**
     * Indicate that the comment is for a specific post.
     */
    public function forPost(Post $post): static
    {
        return $this->state(fn (array $attributes) => [
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
    }

    /**
     * Indicate that the comment is a reply to another comment.
     */
    public function replyTo(Comment $comment): static
    {
        return $this->state(fn (array $attributes) => [
            'commentable_id' => $comment->id,
            'commentable_type' => Comment::class,
        ]);
    }
}