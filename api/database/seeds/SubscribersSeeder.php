<?php

use Illuminate\Database\Seeder;

class SubscribersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscribers')->insert([
            'name' => 'Marius',
            'email' => 'marius.cucuruz@gmail.com',
            'satId' => 1,
            'noradId' => 25544,
            'location' => 'Home',
            'latitude' => '51.639785',
            'longitude' => '-0.129894',
            'altitude' => '0',
        ],
        [
            'name' => 'Marius',
            'email' => 'marius.cucuruz@gmail.com',
            'satId' => 1,
            'noradId' => 25544,
            'location' => 'London, UK',
            'latitude' => '51.507648',
            'longitude' => '-0.127828',
            'altitude' => '0',
        ],
        [
            'name' => 'Marius',
            'email' => 'marius.cucuruz@gmail.com',
            'satId' => 1,
            'noradId' => 25544,
            'location' => 'Piatra Neamt, RO',
            'latitude' => '46.922383',
            'longitude' => '26.373280',
            'altitude' => '0',
        ]);
    }
}
