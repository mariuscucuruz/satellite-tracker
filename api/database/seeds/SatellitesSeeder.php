<?php

use Illuminate\Database\Seeder;

class SatellitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('satellites')->insert([
                'name' => 'ISS',
                'noradId' => 25544,
            ],
            [
                'name' => 'Hubble',
                'noradId' => 20580,
            ],
            [
                'name' => 'Aeolus',
                'noradId' => 43600,
            ]);
    }
}
