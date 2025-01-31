<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{

    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomDigit(),
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'code' => $this->faker->word(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'is_active' => $this->faker->boolean(),
            'user_created' => $this->faker->name(),
            'user_updated' => $this->faker->name(),
        ];
    }
}
