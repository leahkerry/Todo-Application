<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Todo;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    /******* GET tests *******/

    public function test_can_get_all_todos()
    {
        $user = User::factory()->create();

        Todo::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
                        ->getJson('/api/todos');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    
    public function test_can_get_one_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(
            ['user_id' => $user->id,]
        );

        $response = $this->actingAs($user)->getJson("/api/todos/{$todo->id}");

        $response->assertStatus(200);

    }

    public function test_invalid_get_one_todo()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson("/api/todos/-1");

        $response->assertStatus(404);
    }

    public function test_get_unauthenticated_todo()
    {
        $response = $this->getJson("/api/todos/");

        $response->assertStatus(401);
    }
    

    /******* POST tests *******/
    
    public function test_can_create_todo()
    {
        $user = User::factory()->create();

        $data = ['title' => 'New Todo'];

        $response = $this->actingAs($user)->postJson('/api/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }
    
    public function test_invalid_create_todo()
    {
        $user = User::factory()->create();

        $data = ['title' => ''];

        $response = $this->actingAs($user)->postJson('/api/todos', $data);

        $response->assertStatus(422);
    }
    
    public function test_nodata_create_todo()
    {
        $user = User::factory()->create();

        $data = [];

        $response = $this->actingAs($user)->postJson('/api/todos', $data);

        $response->assertStatus(422);
    }
    
    public function test_can_create_date_todo()
    {
        $user = User::factory()->create();
        $data = ['title' => 'New Todo', 'due_date' => '2025-01-01'];

        $response = $this->actingAs($user)->postJson('/api/todos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);
    }

    public function test_create_unauthenticated_todo()
    {
        $user = User::factory()->create();
        $data = ['title' => 'New Todo', 'due_date' => '2025-01-01'];

        $response = $this->postJson('/api/todos', $data);

        $response->assertStatus(401);
    }
    
    /******* PUT tests *******/

    
    public function test_can_update_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = ['title' => 'Updated Todo', 'completed' => true];

        $response = $this->actingAs($user)->putJson("/api/todos/{$todo->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }
    
    public function test_can_update_date_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = ['title' => 'Updated Todo', 'due_date' => '2025-01-01', 'completed' => true];

        $response = $this->actingAs($user)->putJson("/api/todos/{$todo->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);
    }

    public function test_invalid_update_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $data = ['title' => '', 'completed' => true];

        $response = $this->actingAs($user)->putJson("/api/todos/{$todo->id}", $data);

        $response->assertStatus(422);
    }

    public function test_remove_date_update_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = ['title' => 'Original Title', 'due_date' => '1222-12-12', 'completed' => true];
        $response = $this->actingAs($user)->putJson("/api/todos/{$todo->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment($data);

        $newdata = ['title' => 'New Title2', 'due_date' => null, 'completed' => true];

        $newresponse = $this->putJson("/api/todos/{$todo->id}", $newdata);

        $newresponse->assertStatus(200)
                 ->assertJsonFragment($newdata);
    }

    public function test_invalid_date_update_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = ['title' => 'New Title', 'due_date' => 'a98sd', 'completed' => true];

        $response = $this->actingAs($user)->putJson("/api/todos/{$todo->id}", $data);
        
        $response->assertStatus(422); 
    }
    
    /******* DELETE tests *******/
    
    public function test_can_delete_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(204);
    }

    public function test_delete_not_found_todo()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->deleteJson("/api/todos/-1");

        $response->assertStatus(404);
    }
        
}