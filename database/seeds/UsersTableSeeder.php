<?php

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();

        User::create([
          'customer_id' => 1,
          'first_name'  => 'Demo',
          'last_name'   => 'User',
          'email'       => 'demo.user@example.com',
          'password'    => Hash::make('pass'),
        ]);

        User::create([
          'customer_id' => 0,
          'first_name'  => 'Ed',
          'last_name'   => 'Winarski',
          'email'       => 'ed.winiarski@oasincorp.com',
          'password'    => Hash::make('demo')
        ]);
    }
}
