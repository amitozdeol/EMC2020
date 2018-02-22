<?php

class PermissionTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('building_managers')->delete();

        BuildingManager::create(array(
          'user_id' => 1,
          'building_id' => 1,
          'role' => 3
        ));
    }
}
