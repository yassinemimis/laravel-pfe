<?php

namespace Database\Seeders;
use App\Models\Option;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { $options = [
        ['nom_option' => 'IA'],  
        ['nom_option' => 'GL'],  
        ['nom_option' => 'SIC'], 
        ['nom_option' => 'RSD'], 
    ];

    foreach ($options as $option) {
        Option::create($option);
    }
        // User::factory(10)->create();
       
     
    }
}
