<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('developers')->insert([
            'title' => 'Damac',
            'logo' => 'JzlXRQdBu4ijaIKnG42sUPy1WQaADcM0Kw3REUhm.webp',
        ]);
    }
}
