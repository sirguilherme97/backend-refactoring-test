<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_obter_todos_os_usuarios_retorna_usuarios()
    {
        User::factory()->count(3)->create();
        $service = app(\App\Services\UserService::class);
        $users = $service->getAllUsers();
        $this->assertCount(3, $users);
    }

    public function test_obter_usuario_por_id_retorna_usuario()
    {
        $user = User::factory()->create();
        $service = app(\App\Services\UserService::class);
        $found = $service->getUserById($user->id);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_criar_usuario_cria_usuario()
    {
        $service = app(\App\Services\UserService::class);
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ];
        $user = $service->createUser($data);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_atualizar_usuario_atualiza_usuario()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $service = app(\App\Services\UserService::class);
        $service->updateUser($user->id, ['name' => 'New Name']);
        $this->assertDatabaseHas('users', ['name' => 'New Name']);
    }

    public function test_deletar_usuario_deleta_usuario()
    {
        $user = User::factory()->create();
        $service = app(\App\Services\UserService::class);
        $deleted = $service->deleteUser($user->id);
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
