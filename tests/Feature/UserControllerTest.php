<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_retorna_todos_os_usuarios()
    {
        User::factory()->count(2)->create();
        $response = $this->getJson('/api/users');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_show_retorna_usuario()
    {
        $user = User::factory()->create();
        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(200)->assertJsonFragment(['id' => $user->id]);
    }

    public function test_show_retorna_404_se_nao_encontrado()
    {
        $response = $this->getJson('/api/users/999');
        $response->assertStatus(404);
    }

    public function test_store_cria_usuario()
    {
        $data = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
        ];
        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(201)->assertJsonFragment(['email' => 'user@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'user@example.com']);
    }

    public function test_update_atualiza_usuario()
    {
        $user = User::factory()->create(['name' => 'Old']);
        $data = ['name' => 'New'];
        $response = $this->putJson("/api/users/{$user->id}", $data);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'New']);
        $this->assertDatabaseHas('users', ['name' => 'New']);
    }

    public function test_update_retorna_404_se_nao_encontrado()
    {
        $data = ['name' => 'New', 'email' => 'notfound@example.com', 'password' => 'password'];
        $response = $this->putJson('/api/users/999', $data);
        $response->assertStatus(404);
    }

    public function test_destroy_deleta_usuario()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_destroy_retorna_404_se_nao_encontrado()
    {
        $response = $this->deleteJson('/api/users/999');
        $response->assertStatus(404);
    }
}
