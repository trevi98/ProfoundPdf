<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('amenities')->insert([
            'title' => 'gym',
            'cover' => 'TfTKhyViec8ClsugvpyrJi55erf4rQrhSC5FJCdC.png',
        ]);
        DB::table('amenities')->insert([
            'title' => 'wifi',
            'cover' => '3uNcJQPQlUQasLU7YY4v0NGudigVIBJdtZR6TuC7.png',
        ]);

    }
}
