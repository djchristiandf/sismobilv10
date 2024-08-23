<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Senha padrão
            'mobile' => $this->faker->phoneNumber(),
            'roleId' => $this->faker->numberBetween(1, 5), // Exemplo de IDs de papel
            'isDeleted' => 0, // 0 para não deletado
            'createdBy' => 1, // ID do criador, ajuste conforme necessário
            'createdDtm' => now(),
            'updatedBy' => null,
            'updatedDtm' => null,
            'login' => $this->faker->userName(),
            'matricula' => $this->faker->unique()->lexify('?????????'), // 9 caracteres
            'setor' => $this->faker->word(), // Ajuste conforme necessário
            'empregado' => $this->faker->boolean(),
            'cargo' => $this->faker->jobTitle(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
