<?php

class DatabaseSeeder extends Seeder
{

  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        Eloquent::unguard();

        $this->call('AlgorithmTableSeeder');
        $this->call('CustomersTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('BuildingsTableSeeder');
        $this->call('SystemsTableSeeder');
        $this->call('DevicesTableSeeder');
        $this->call('DeviceTypesTableSeeder');
        $this->call('PermissionTableSeeder');
        $this->call('RoleLabelTableSeeder');
    }
}
