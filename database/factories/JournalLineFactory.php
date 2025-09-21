<?php

namespace Database\Factories;

use App\Models\ChartOfAccount;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JournalLine>
 */
class JournalLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'journal_id' => Journal::factory(),
            'account_id' => ChartOfAccount::factory(),
            'dept_id' => null,
            'debit' => $this->faker->randomFloat(2, 0, 1000),
            'credit' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
