<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Todo;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_todos()
    {
        Todo::factory()->count(3)->create();

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_create_todo()
    {
        $data = ['title' => 'New Todo'];

        $response = $this->postJson('/api/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    public function test_can_update_todo()
    {
        $todo = Todo::factory()->create();

        $data = ['title' => 'Updated Todo', 'completed' => true];

        $response = $this->putJson("/api/todos/{$todo->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }

    public function test_can_delete_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(204);
    }
}