<?php

class RoleLabelTableSeeder extends Seeder {

    public function run()
    {
        DB::table('role_labels')->delete();

        RoleLabel::create(array(
          'id'    => 1,
          'label' => 'User',
        ));

        RoleLabel::create(array(
          'id'    => 2,
          'label' => 'Maintainer',
        ));

        RoleLabel::create(array(
          'id'    => 3,
          'label' => 'Superintendant',
        ));

        RoleLabel::create(array(
          'id'    => 4,
          'label' => 'Manager',
        ));

        RoleLabel::create(array(
          'id'    => 5,
          'label' => 'Owner',
        ));

        RoleLabel::create(array(
          'id'    => 6,
          'label' => 'Support',
        ));

        RoleLabel::create(array(
          'id'    => 7,
          'label' => 'Admin',
        ));

        RoleLabel::create(array(
          'id'    => 8,
          'label' => 'Sysadmin',
        ));

    }

}