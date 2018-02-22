<?php

class CustomersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('customers')->delete();

        Customer::create(array(
          'id' => 0,
          'name' => 'EAW Electronic Systems Inc.',
          'address1' => '16 Victory Ln',
          'city' => 'Poughkeepsie',
          'state' => 'NY',
          'zip' => 12603,
          'email1' => 'ed.winiarski@oasincorp.com',
        ));

        Customer::create(array(
          'name' => 'HVTDC',
          'address1' => '1450 Route 300',
          'address2' => 'Building 1, Suite 1',
          'city' => 'Newburgh',
          'state' => 'NY',
          'zip' => 12550,
          'email1' => 'bob.incerto@hvtdc.org',
        ));
    }

}