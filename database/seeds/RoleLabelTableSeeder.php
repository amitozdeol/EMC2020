<?php

class RoleLabelTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('role_labels')->delete();

        RoleLabel::create([
          'id'    => 1,
          'label' => 'User',
        ]);

        RoleLabel::create([
          'id'    => 2,
          'label' => 'Maintainer',
        ]);

        RoleLabel::create([
          'id'    => 3,
          'label' => 'Superintendant',
        ]);

        RoleLabel::create([
          'id'    => 4,
          'label' => 'Manager',
        ]);

        RoleLabel::create([
          'id'    => 5,
          'label' => 'Owner',
        ]);

        RoleLabel::create([
          'id'    => 6,
          'label' => 'Support',
        ]);

        RoleLabel::create([
          'id'    => 7,
          'label' => 'Admin',
        ]);

        RoleLabel::create([
          'id'    => 8,
          'label' => 'Sysadmin',
        ]);
    }
}
