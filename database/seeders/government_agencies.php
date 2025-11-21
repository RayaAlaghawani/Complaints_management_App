<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class government_agencies extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('government_agencies')->insert([
            [
                'name' => 'Ministry of Electricity',
                'type' => 'Ministry',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ministry of Education',
                'type' => 'Ministry',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
