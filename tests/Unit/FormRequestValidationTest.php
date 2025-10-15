<?php

namespace Tests\Unit;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FormRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_request_validation_rules(): void
    {
        $request = new RegisterRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_login_request_validation_rules(): void
    {
        $request = new LoginRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $validator = Validator::make([
            'email' => 'john@example.com',
            'password' => 'password123',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'email' => 'invalid-email',
            'password' => '',
        ], $rules);

        $this->assertTrue($validator->fails());
    }

    public function test_create_post_request_validation_rules(): void
    {
        $request = new CreatePostRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('body', $rules);
        $this->assertArrayNotHasKey('is_active', $rules);

        $validator = Validator::make([
            'body' => 'This is a valid post content.',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'body' => '',
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('body', $validator->errors()->toArray());

        $validator = Validator::make([
            'body' => str_repeat('a', 5001),
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('body', $validator->errors()->toArray());
    }

    public function test_update_post_request_validation_rules(): void
    {
        $request = new UpdatePostRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('body', $rules);
        $this->assertArrayHasKey('is_active', $rules);

        $validator = Validator::make([
            'body' => 'Updated post content.',
            'is_active' => true,
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'body' => '',
            'is_active' => 'not-boolean',
        ], $rules);

        $this->assertTrue($validator->fails());
    }

    public function test_create_comment_request_validation_rules(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $request = new CreateCommentRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('body', $rules);

        $validator = Validator::make([
            'body' => 'This is a valid comment.',
            'commentable_id' => $post->id,
            'commentable_type' => 'post',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'body' => '',
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('body', $validator->errors()->toArray());
    }

    public function test_create_comment_request_validates_existing_commentable(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $request = new CreateCommentRequest();

        $request->merge([
            'body' => 'Valid comment body',
            'commentable_id' => 99999,
            'commentable_type' => 'post',
        ]);

        $rules = $request->rules();

        $validator = Validator::make($request->all(), $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('commentable_id', $validator->errors()->toArray());
    }

    public function test_update_comment_request_validation_rules(): void
    {
        $request = new UpdateCommentRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('body', $rules);

        $validator = Validator::make([
            'body' => 'Updated comment content.',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'body' => str_repeat('a', 5001), // Too long
        ], $rules);

        $this->assertTrue($validator->fails());
    }

    public function test_update_user_request_validation_rules(): void
    {
        $request = new UpdateUserRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);

        $validator = Validator::make([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'name' => str_repeat('a', 256),
            'email' => 'invalid-email',
        ], $rules);

        $this->assertTrue($validator->fails());
    }

    public function test_form_request_authorization(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $request = new UpdateCommentRequest();
        $request->setRouteResolver(function () use ($comment) {
            $route = \Mockery::mock();
            $route->shouldReceive('parameter')->with('comment', null)->andReturn($comment);
            return $route;
        });

        $this->assertFalse($request->authorize());

        Sanctum::actingAs($user);
        $this->assertTrue($request->authorize());

        $otherUser = User::factory()->create();
        Sanctum::actingAs($otherUser);
        $this->assertFalse($request->authorize());
    }
}
