<?php

class SystemsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('systems')->delete();

        System::create([
          'building_id' => 1,
          'extender_boards' => 4,
          'wired_relay_outputs' => 3,
          'current_loop_outputs' => 2,
          'wired_sensors' => 2,
          'wireless_sensors' => 2,
          'bacnet_devices' => 2,
          'system_mode' => 2,
          'season_mode' => 0,
          'active_relays' => 24581,
          'current_loop_dvrs' => 5,
          'current_loop_inputs' => 5,
          'active_inputs1' => 200000,
          'active_inputs2' => 300000,
          'active_inputs3' => 400000,
          'active_inputs4' => 500000,
          'ethernet_ip' => '192.168.1.20',
          'wireless_ip' => '192.168.1.21',
          'ethernet_port' => 22,
          'wireless_port' => 22,
          'coordinator_mac' => 12345,
          'coordinator_format' => 1,
        ]);

        System::create([
          'building_id' => 1,
          'extender_boards' => 2,
          'wired_relay_outputs' => 3,
          'current_loop_outputs' => 4,
          'wired_sensors' => 5,
          'wireless_sensors' => 6,
          'bacnet_devices' => 7,
          'system_mode' => 8,
          'season_mode' => 0,
          'active_relays' => 33792,
          'current_loop_dvrs' => 8,
          'current_loop_inputs' => 5,
          'active_inputs1' => 1,
          'active_inputs2' => 2,
          'active_inputs3' => 3,
          'active_inputs4' => 4,
          'ethernet_ip' => '192.168.1.22',
          'wireless_ip' => '192.168.1.23',
          'ethernet_port' => 22,
          'wireless_port' => 22,
          'coordinator_mac' => 0,
          'coordinator_format' => 1,
        ]);

        System::create([
          'building_id' => 1,
          'extender_boards' => 2,
          'wired_relay_outputs' => 3,
          'current_loop_outputs' => 4,
          'wired_sensors' => 5,
          'wireless_sensors' => 6,
          'bacnet_devices' => 7,
          'system_mode' => 8,
          'season_mode' => 0,
          'active_relays' => 33792,
          'current_loop_dvrs' => 8,
          'current_loop_inputs' => 5,
          'active_inputs1' => 1,
          'active_inputs2' => 2,
          'active_inputs3' => 3,
          'active_inputs4' => 4,
          'ethernet_ip' => '192.168.1.24',
          'wireless_ip' => '192.168.1.25',
          'ethernet_port' => 22,
          'wireless_port' => 22,
          'coordinator_mac' => 0,
          'coordinator_format' => 1,
        ]);
    }
}
