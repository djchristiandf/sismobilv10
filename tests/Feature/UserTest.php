<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    //use RefreshDatabase; // mudar para nao apagar os dados do banco
    //criar os teste de crud users

    use WithFaker, DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the user CRUD operations.
     */
    public function test_user_crud(): void
    {
        // Definindo a conexão que deseja usar
        //$this->app['config']->set('database.default', 'usuarios'); // ou 'gestaorh'
        // Test Create
        $userData = [
            'email' => $this->faker->unique()->safeEmail(),
            //'password' => 'password',
            'name' => $this->faker->name(),
            //'mobile' => $this->faker->phoneNumber(),
            //'roleId' => 1,
            'isDeleted' => 0,
            //'createdBy' => 1,
            'createdDtm' => '2024-08-09',
            'login' => $this->faker->userName(),
            'matricula' => '9999999',
            'setor' => 'DEINF',
            'cargo' => 'EMPREGADO',
        ];

        //dd($userData);

        $response = $this->post('/users', $userData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('tbl_users', ['email' => $userData['email']]);

        // Test Read
        $user = User::where('email', $userData['email'])->first();
        $response = $this->get("/users/{$user->userId}");
        $response->assertStatus(200);
        $response->assertSee($user->name);

        // Test Update
        $updatedData = [
            'email' => $this->faker->unique()->safeEmail(),
            //'password' => 'password',
            'name' => $this->faker->name(),
            //'mobile' => $this->faker->phoneNumber(),
            //'roleId' => 1,
            'isDeleted' => 0,
            //'createdBy' => 1,
            'createdDtm' => '2024-08-09',
            'login' => $this->faker->userName(),
            'matricula' => '9999999',
            'setor' => 'DEINF',
            'cargo' => 'EMPREGADO',
        ];

        $response = $this->put("/users/{$user->userId}", $updatedData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('tbl_users', ['email' => $updatedData['email']]);

        // Test Delete
        $response = $this->delete("/users/{$user->userId}");
        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('tbl_users', ['email' => $updatedData['email']]);
    }

}
