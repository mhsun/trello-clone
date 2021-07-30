<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Column::factory(3)->create()->each(function ($column) {
            Card::factory()->withColumn($column)->count(10)->create();
        });
    }
}
