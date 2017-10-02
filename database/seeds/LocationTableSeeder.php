<?php

use Illuminate\Database\Seeder;
use App\Location;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create(['kota' => 'Malang', 'lat' => '12345', 'lang' => '12345']);
        Location::create(['kota' => 'Kediri', 'lat' => '12345', 'lang' => '12345']);
        Location::create(['kota' => 'Surabaya', 'lat' => '12345', 'lang' => '12345']);
    }
}
