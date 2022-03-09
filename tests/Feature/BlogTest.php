<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
     use RefreshDatabase;

    public function test_store()
    {
        $response = $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $this->assertJson($response->getContent());

        $response->assertStatus(201); // Or assertCreated

        $this->assertDatabaseHas('posts', [
            'title' => 'First Post Test'
        ]);

        $this->assertDatabaseCount('posts', 1);
    }

    public function test_index()
    {
        $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $response = $this->get('/api/blog');

        $this->assertJson($response->getContent());

        $this->assertIsArray(json_decode($response->getContent()));

        $response->assertSee([
            'title' => 'First Post Test'
        ]);

        $this->assertCount(2, json_decode($response->getContent()));

        $response->assertStatus(200); // Or assertOk
    }

    public function test_get()
    {
        $new_post = $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $new_post_id = json_decode($new_post->getContent())->id;

        $response = $this->get('/api/blog/' . $new_post_id);

        $this->assertJson($response->getContent());

        $response->assertSee([
            'title' => 'First Post Test'
        ]);

        $response->assertOk();
    }

    public function test_update()
    {
        $new_post = $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $new_post_id = json_decode($new_post->getContent())->id;

        $response = $this->putJson('/api/blog/' . $new_post_id, [
            'title' => 'This is now a second post',
            'body' => 'This actually used to be the first post'
        ]);

        $this->assertJson($response->getContent());

        $response->assertSee([
            'title' => 'This is now a second post'
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'This is now a second post'
        ]);

        $response->assertOk();
    }

    public function test_delete()
    {
        $post_1 = $this->postJson('/api/blog', [
            'title' => 'First Post Test',
            'body' => 'I love to do this'
        ]);

        $post_2 = $this->postJson('/api/blog', [
            'title' => 'Second Post Test',
            'body' => 'I love to do this again'
        ]);

        $post_1_id = json_decode($post_1->getContent())->id;

        $response = $this->delete('/api/blog/' . $post_1_id);

        $this->assertJson($response->getContent());

        $this->assertDeleted('posts', [
            'title' => 'First Post Test',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Second Post Test'
        ]);

        $response->assertOk();
    }
}
