<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'a@a.a',
            'password' => '$2y$10$zE4xzTuPqQ2SBSBriRwAn.oPbqbUObJk87N/RKoJmNm7kQC/cbiga',
            'is_admin' => True,
            // Add more columns as needed
        ]);
        DB::table('users')->insert([
            'name' => 'agent 1',
            'email' => 'ag1@a.a',
            'password' => '$2y$10$zE4xzTuPqQ2SBSBriRwAn.oPbqbUObJk87N/RKoJmNm7kQC/cbiga',
            // Add more columns as needed
        ]);
    }
}
