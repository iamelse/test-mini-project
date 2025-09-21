<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;

        return [
            'ref_no' => 'JV-' . now()->format('Y') . '-' . str_pad($counter++, 4, '0', STR_PAD_LEFT),
            'posting_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'memo' => $this->faker->sentence(3),
            'status' => 'posted',
            'created_by' => 1, // bisa diganti user login
        ];
    }
}
