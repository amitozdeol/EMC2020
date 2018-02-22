<?php

class DeviceTypesTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('device_types')->delete();

        DeviceType::create([
          'id' => 1,
          'name' => 'System Wireless Sensor',
          'command' => 1,
          'function' => 'Temperature',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wireless',
        ]);

        DeviceType::create([
          'id' => 2,
          'name' => 'System Wireless Sensor',
          'command' => 2,
          'function' => 'Voltage',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wireless',
        ]);

        DeviceType::create([
          'id' => 3,
          'name' => 'System Wireless Sensor',
          'command' => 3,
          'function' => 'Light',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wireless',
        ]);

        DeviceType::create([
          'id' => 4,
          'name' => 'System Wireless Sensor',
          'command' => 4,
          'function' => 'Occupancy',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wireless',
        ]);

        DeviceType::create([
          'id' => 5,
          'name' => 'System Wired Sensor',
          'command' => 5,
          'function' => 'Analog',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wired',
        ]);

        DeviceType::create([
          'id' => 6,
          'name' => 'System Wired Sensor',
          'command' => 6,
          'function' => 'Digital',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wired',
        ]);

        DeviceType::create([
          'id' => 7,
          'name' => 'System Wired Sensor',
          'command' => 7,
          'function' => 'Current',
          'mode' => 'input',
          'hysteresis' => 0,
          'mode' => 'wired',
        ]);

        DeviceType::create([
          'id' => 8,
          'name' => 'System Wired Controller',
          'command' => 8,
          'function' => 'Current',
          'mode' => 'output',
          'hysteresis' => 0,
          'mode' => 'wired',
        ]);

        DeviceType::create([
          'id' => 9,
          'name' => 'System Wired Controller',
          'command' => 9,
          'function' => 'Relay',
          'mode' => 'output',
          'hysteresis' => 0,
          'mode' => 'wired',
        ]);
    }
}
