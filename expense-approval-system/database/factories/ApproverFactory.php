<?php

namespace Database\Factories;

use App\Models\Approver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApproverFactory extends Factory
{
    protected $model = Approver::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }
}
