<?php

class BuildingsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('buildings')->delete();

        Building::create([
          'customer_id' => 1,
          'name' => 'HVTDC Lab',
          'address1' => '1450 Route 300'
        ]);

        Building::create([
          'customer_id' => 0,
          'name' => 'EAW Facilities Building A',
          'address1' => '16 Victory Ln',
          'city' => 'Poughkeepsie',
          'state' => 'NY',
          'zip' => 12602
        ]);

        Building::create([
          'customer_id' => 0,
          'name' => 'EAW Facilities Building B',
          'address1' => '16 Victory Ln',
          'city' => 'Poughkeepsie',
          'state' => 'NY',
          'zip' => 12602
        ]);

        Building::create([
          'customer_id' => 0,
          'name' => 'EAW Facilities Building C',
          'address1' => '16 Victory Ln',
          'city' => 'Poughkeepsie',
          'state' => 'NY',
          'zip' => 12602
        ]);
    }
}
