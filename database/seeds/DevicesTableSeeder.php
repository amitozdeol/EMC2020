<?php

class DevicesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('devices')->delete();

        Device::create(array(
          'id' => 1,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 1,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 123,
          'short_address' => 456,
          'location' => 1,
          'zone' => 1,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 2,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 1,
          'device_mode' => 'wireless',
          'device_function' => 'output',
          'mac_address' => 789,
          'short_address' => 1011,
          'location' => 2,
          'zone' => 3,
          'physical_location' => '',
          'comments' => '',
          'status' => 0,
        ));

        Device::create(array(
          'id' => 3,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 2,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 234,
          'short_address' => 567,
          'location' => 3,
          'zone' => 4,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 4,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 2,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 123352,
          'short_address' => 34646,
          'location' => 4,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 0,
        ));

        Device::create(array(
          'id' => 5,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 3,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 23425,
          'short_address' => 13412,
          'location' => 6,
          'zone' => 7,
          'physical_location' => '',
          'comments' => '',
          'status' => 0,
        ));

        Device::create(array(
          'id' => 6,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 3,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 45235,
          'short_address' => 234234,
          'location' => 6,
          'zone' => 8,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 7,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 4,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 436463,
          'short_address' => 423423,
          'location' => 1,
          'zone' => 1,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 8,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 4,
          'device_mode' => 'wired',
          'device_function' => 'input',
          'mac_address' => 42352,
          'short_address' => 5235,
          'location' => 205,
          'zone' => 3,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 9,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 5,
          'device_mode' => 'wireless',
          'device_function' => 'output',
          'mac_address' => 234235,
          'short_address' => 23235,
          'location' => 810,
          'zone' => 2,
          'physical_location' => '',
          'comments' => '',
          'status' => 0,
        ));

        Device::create(array(
          'id' => 10,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 5,
          'device_mode' => 'wired',
          'device_function' => 'output',
          'mac_address' => 5235,
          'short_address' => 23523,
          'location' => 340,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 11,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 6,
          'device_mode' => 'wired',
          'device_function' => 'output',
          'mac_address' => 5235,
          'short_address' => 23523,
          'location' => 340,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 12,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 7,
          'device_mode' => 'wired',
          'device_function' => 'output',
          'mac_address' => 5235,
          'short_address' => 23523,
          'location' => 340,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 13,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 8,
          'device_mode' => 'wired',
          'device_function' => 'output',
          'mac_address' => 5235,
          'short_address' => 23523,
          'location' => 340,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 14,
          'building_id' => 2,
          'system_id' => 500,
          'device_types_id' => 9,
          'device_mode' => 'wired',
          'device_function' => 'output',
          'mac_address' => 5235,
          'short_address' => 23523,
          'location' => 340,
          'zone' => 5,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

        Device::create(array(
          'id' => 1,
          'building_id' => 2,
          'system_id' => 501,
          'device_types_id' => 1,
          'mac_address' => 0,
          'short_address' => 0,
          'location' => 0,
          'zone' => 0,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));

          Device::create(array(
          'id' => 2,
          'building_id' => 2,
          'system_id' => 501,
          'device_types_id' => 2,
          'mac_address' => 0,
          'short_address' => 0,
          'location' => 0,
          'zone' => 0,
          'physical_location' => '',
          'comments' => '',
          'status' => 1,
        ));
    }

}
