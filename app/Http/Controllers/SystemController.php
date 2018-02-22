<?php

class SystemController extends Controller
{
/*
|--------------------------------------------------------------------------
| System Config Controller
|--------------------------------------------------------------------------
|
*/
    /*--------------------------------------------------------------------------
	| Initialize the global hardware version arrays
	|-------------------------------------------------------------------------*/
    public function initialize_hardware_versions()
    {
        $HV01030000 = [];
        $HV01030300 = [];
        $HV01030303 = [];
        $HV11000000 = [];
        $HV11130000 = [];
        $HV11131400 = [];
        $HV11140000 = [];
        $HV11141400 = [];
        $HV11141414 = [];
        /*
		analog 5
		digital 6
		relay 9
		*/
        $HV01030000 = [
            1 => ["Board","1","Analog","1","5","input"],
            2 => ["Board","1","Analog","2","5","input"],
            3 => ["Board","1","Analog","3","5","input"],
            4 => ["Board","1","Analog","4","5","input"],
            5 => ["Board","1","Digital","1","6","input"],
            6 => ["Board","1","Digital","2","6","input"],
            7 => ["Board","1","Digital","3","6","input"],
            8 => ["Board","1","Digital","4","6","input"],
            10 => ["Board","1","Relay","1","9","output"],
            11 => ["Board","1","Relay","2","9","output"],
            12 => ["Board","1","Relay","3","9","output"],
            13 => ["Board","1","Relay","4","9","output"]
        ];
        $HV01030300 = [
            15 => ["Board","2","Analog","1","5","input"],
            16 => ["Board","2","Analog","2","5","input"],
            17 => ["Board","2","Analog","3","5","input"],
            18 => ["Board","2","Analog","4","5","input"],
            19 => ["Board","2","Digital","1","6","input"],
            20 => ["Board","2","Digital","2","6","input"],
            21 => ["Board","2","Digital","3","6","input"],
            22 => ["Board","2","Digital","4","6","input"],
            24 => ["Board","2","Relay","1","9","output"],
            25 => ["Board","2","Relay","2","9","output"],
            26 => ["Board","2","Relay","3","9","output"],
            27 => ["Board","2","Relay","4","9","output"]
        ];
        $HV01030303 = [
            29 => ["Board","3","Analog","1","5","input"],
            30 => ["Board","3","Analog","2","5","input"],
            31 => ["Board","3","Analog","3","5","input"],
            32 => ["Board","3","Analog","4","5","input"],
            33 => ["Board","3","Digital","1","6","input"],
            34 => ["Board","3","Digital","2","6","input"],
            35 => ["Board","3","Digital","3","6","input"],
            36 => ["Board","3","Digital","4","6","input"],
            38 => ["Board","3","Relay","1","9","output"],
            39 => ["Board","3","Relay","2","9","output"],
            40 => ["Board","3","Relay","3","9","output"],
            41 => ["Board","3","Relay","4","9","output"]
        ];
        $HV11000000 = [
            1 => ["Main Board","0","Analog","1","5","input"],
            2 => ["Main Board","0","Analog","2","5","input"],
            3 => ["Main Board","0","Analog","3","5","input"],
            4 => ["Main Board","0","Analog","4","5","input"],
            5 => ["Main Board","0","Analog","5","5","input"],
            6 => ["Main Board","0","Analog","6","5","input"],
            7 => ["Main Board","0","Digital","1","6","input"],
            8 => ["Main Board","0","Digital","2","6","input"],
            9 => ["Main Board","0","Digital","3","6","input"],
            10 => ["Main Board","0","Digital","4","6","input"],
            11 => ["Main Board","0","Relay","1","9","output"],
            12 => ["Main Board","0","Relay","2","9","output"],
            13 => ["Main Board","0","Relay","3","9","output"],
            14 => ["Main Board","0","Relay","4","9","output"]
        ];
        $HV11130000 = [
            15 => ["Board","1","Analog","1","5","input"],
            16 => ["Board","1","Analog","2","5","input"],
            17 => ["Board","1","Analog","3","5","input"],
            18 => ["Board","1","Analog","4","5","input"],
            19 => ["Board","1","Analog","5","5","input"],
            20 => ["Board","1","Digital","1","6","input"],
            21 => ["Board","1","Digital","2","6","input"],
            22 => ["Board","1","Digital","3","6","input"],
            23 => ["Board","1","Digital","4","6","input"],
            24 => ["Board","1","Relay","1","9","output"],
            25 => ["Board","1","Relay","2","9","output"],
            26 => ["Board","1","Relay","3","9","output"],
            27 => ["Board","1","Relay","4","9","output"],
            28 => ["Board","1","Relay","5","9","output"],
            29 => ["Board","1","Relay","6","9","output"],
            30 => ["Board","1","Relay","7","9","output"],
            31 => ["Board","1","Relay","8","9","output"]
        ];
        $HV11131400 = [
            32 => ["Board","2","Analog","1","5","input"],
            33 => ["Board","2","Analog","2","5","input"],
            34 => ["Board","2","Analog","3","5","input"],
            35 => ["Board","2","Analog","4","5","input"],
            36 => ["Board","2","Analog","5","5","input"],
            37 => ["Board","2","Analog","6","5","input"],
            38 => ["Board","2","Analog","7","5","input"],
            39 => ["Board","2","Analog","8","5","input"],
            40 => ["Board","2","Analog","9","5","input"],
            41 => ["Board","2","Analog","10","5","input"]
        ];
        $HV11140000 = [
            15 => ["Board","1","Analog","1","5","input"],
            16 => ["Board","1","Analog","2","5","input"],
            17 => ["Board","1","Analog","3","5","input"],
            18 => ["Board","1","Analog","4","5","input"],
            19 => ["Board","1","Analog","5","5","input"],
            20 => ["Board","1","Analog","6","5","input"],
            21 => ["Board","1","Analog","7","5","input"],
            22 => ["Board","1","Analog","8","5","input"],
            23 => ["Board","1","Analog","9","5","input"],
            24 => ["Board","1","Analog","10","5","input"]
        ];
        $HV11141400 = [
            25 => ["Board","2","Analog","1","5","input"],
            26 => ["Board","2","Analog","2","5","input"],
            27 => ["Board","2","Analog","3","5","input"],
            28 => ["Board","2","Analog","4","5","input"],
            29 => ["Board","2","Analog","5","5","input"],
            30 => ["Board","2","Analog","6","5","input"],
            31 => ["Board","2","Analog","7","5","input"],
            32 => ["Board","2","Analog","8","5","input"],
            33 => ["Board","2","Analog","9","5","input"],
            34 => ["Board","2","Analog","10","5","input"]
        ];
        $HV11141414 = [
            35 => ["Board","3","Analog","1","5","input"],
            36 => ["Board","3","Analog","2","5","input"],
            37 => ["Board","3","Analog","3","5","input"],
            38 => ["Board","3","Analog","4","5","input"],
            39 => ["Board","3","Analog","5","5","input"],
            40 => ["Board","3","Analog","6","5","input"],
            41 => ["Board","3","Analog","7","5","input"],
            42 => ["Board","3","Analog","8","5","input"],
            43 => ["Board","3","Analog","9","5","input"],
            44 => ["Board","3","Analog","10","5","input"]
        ];

        $hardware_options = [
            "01.03.00.00" => $HV01030000,
            "01.03.03.00" => $HV01030300,
            "01.03.03.03" => $HV01030303,
            "11.00.00.00" => $HV11000000,
            "11.13.00.00" => $HV11130000,
            "11.13.14.00" => $HV11131400,
            "11.14.00.00" => $HV11140000,
            "11.14.14.00" => $HV11141400,
            "11.14.14.14" => $HV11141414
        ];

        return $hardware_options;
    }


    /*--------------------------------------------------------------------------
	| Add New System default page
	|-------------------------------------------------------------------------*/
    public function newsystem($id)
    {
        $thisBldg = Building::find($id); // Lookup info for selected building
        $systems = System::where('building_id', $thisBldg->id)->get(); // Lookup all systems associated with selected building for nav dropdown

        return view('buildings.config.newsystem', ['thisBldg' => $thisBldg])
        ->with('systemsData', $systems);
    }

    /*--------------------------------------------------------------------------
	| Add New System form POST controller
	|-------------------------------------------------------------------------*/
    public function addsystem($id)
    {
        $thisBldg = Building::find($id); // Lookup info for selected building
        $systems = System::where('building_id', $thisBldg->id)->get(); // Lookup all systems associated with selected building for nav dropdown
        $input = Input::except('_token');
        // Create new system from Input data
        $system = new System;

        /*default values*/
        $system->building_id = $thisBldg->id;
        $system->active_relays = 0;
        $system->current_loop_dvrs = 0;
        $system->current_loop_inputs = 0;
        $system->current_loop_outputs = 0;
        $system->active_inputs1 = 0;
        $system->active_inputs2 = 0;
        $system->active_inputs3 = 0;
        $system->active_inputs4 = 0;
        $system->coordinator_mac = 0;
        $system->update_flag = 1;
        foreach ($input as $key => $value) {
            $system->$key = $value;
        }
        $system->save();

        $newSys = System::where('building_id', $thisBldg->id)->orderBy('id', 'desc')->first();

        return Redirect::route('system.editSystem', [$thisBldg->id, $newSys->id]);

        // return view('buildings.config.newsystem', array('thisBldg' => $thisBldg))
        // 	->with('systemsData', $systems)
        // 	->with('newSys', $newSys);
    }

    /*--------------------------------------------------------------------------
	| Edit System default page
	|-------------------------------------------------------------------------*/
    public function editsystem($id, $sid)
    {
        $thisBldg = Building::find($id);                                // Lookup info for selected building
        $systems = System::where('building_id', $thisBldg->id)->get();  // Lookup all systems associated with selected building for nav dropdown

        $thisSys = System::find($sid);                                  // Lookup info for selected system
        $devices = Device::where('system_id', $thisSys->id)->get();     // Lookup all devices associated with this system
        $devicesetpoints = DeviceSetpoints::where('system_id', $thisSys->id)->get(); //Get setpoints, for environment_offset
        $wirelessdevices = Device::where('system_id', $thisSys->id)
            ->whereIn('device_mode', ['wireless','echostream'])
            ->orderby('retired', 'ASC')
            ->orderby('inhibited', 'ASC')
            ->orderby('product_id', 'ASC')
            ->orderby('name', 'ASC')
            ->get();                                                    // Lookup wireless devices associated with this system
        $bacnetdevices = Device::where('system_id', $thisSys->id)
            ->where('device_mode', 'like', 'bacnet%')
            ->orderby('retired', 'ASC')
            ->orderby('inhibited', 'ASC')
            ->orderby('product_id', 'ASC')
            ->orderby('name', 'ASC')
            ->get();                                                    // Lookup bacnet devices associated with this system

        $relay_devices = Device::where('system_id', $sid)
            ->where('device_mode', 'wired')
            ->where('device_types_id', '9')
            ->orderby('retired', 'ASC')
            ->orderby('inhibited', 'ASC')
            ->orderby('product_id', 'ASC')
            ->orderby('name', 'asc')
            ->get();                                                    // Lookup onboard relay devices associated with this system

        $wired_input_devices = Device::where('system_id', $sid)
            ->where('device_mode', 'wired')
            ->whereIn('device_types_id', ['5','6'])
            ->orderby('retired', 'ASC')
            ->orderby('inhibited', 'ASC')
            ->orderby('product_id', 'ASC')
            ->orderby('name', 'ASC')
            ->get();                                                    // Lookup wired input devices associated with this system
        $utility_devices = Device::where('system_id', $sid)
            ->where('device_mode', 'api')
            ->whereIn('device_types_id', ['46'])
            ->orderby('retired', 'ASC')
            ->orderby('inhibited', 'ASC')
            ->orderby('product_id', 'ASC')
            ->orderby('name', 'ASC')
            ->get();                                                    // Lookup wired input devices associated with this system
        $reset_log = SystemLog::where('system_id', $thisSys->id)         // Lookup the last time the system rebooted
            ->where('application_name', 'LINUX')
            ->where('log_type', '5') //HARDWARE_RESET log type
            ->orderBy('recnum', 'DESC')
            ->select('system_log.*')
            ->limit(1)
            ->remember(10)->first();

        $products = ProductType::all();                                 // Lookup all products available to this system
        $product_commands = [];
        foreach ($products as $prods) {
            $product_commands[$prods->name] = $prods->commands;
        }
        $devicetypes = [];
        $dtype = DeviceType::all();                                     // Lookup all device types
        $device_type_names = [];
        foreach ($dtype as $dt) {
            $device_type_names[$dt->command] = $dt->function;
            if (!in_array($dt->function, $devicetypes)) {
                array_push($devicetypes, $dt->function);    // Lookup all unique device types available to this system
            }
        }
        $device_type_units = [];
        foreach ($dtype as $dt) {
            $device_type_units[$dt->command] = $dt->units;
        }

        $mappedOutputs = MappingOutput::where('system_id', $sid)
            ->get();

        $retiredDevices = Device::where('system_id', $sid)
            ->where('retired', 1)
            ->get();

        $latest_sw_version = System::select('systems.software_version')
            ->orderBy('software_version', 'DESC')
            ->get()
            ->first();

        $thisNetwork = NetworkSettings::where('system_id', $sid)
            ->whereNull('deleted_at')
            ->orderby('id', 'DESC')
            ->limit(1)
            ->get()
            ->first();
        if (!isset($thisNetwork)) {
            $thisNetwork =new NetworkSettings();
            $thisNetwork->system_id = $sid;
            $thisNetwork->static_ip = '192.168.0.1';
            $thisNetwork->netmask = '255.255.255.0';
            $thisNetwork->gateway = '192.168.0.0';
            $thisNetwork->dns_nameserver = '8.8.8.8';
        }

        /****************************************************/

        $retired_devices = [];
        $used_retired_devices = [];
        foreach ($retiredDevices as $device) {
            $retired_devices[$device->id] = $device->id;
        }
        $device_env_offset = [];
        foreach ($devicesetpoints as $devset) {
            $device_env_offset[$devset->device_id][$devset->command] = $devset->environmental_offset;
        }

        foreach ($mappedOutputs as $output) {
            if ($output->inputs !== '') {
                $inputs = explode(', ', str_replace('.', '', $output->inputs));
                foreach ($inputs as $index => $input) {
                    $retired_input_id = explode(' ', $input);
                    if (array_key_exists((int)$retired_input_id[0], $retired_devices) !== false) {
                        if (array_key_exists((int)$retired_input_id[0], $used_retired_devices) == false) {
                            $used_retired_devices[$output->device_id] = $output->device_id;
                        }
                    }
                }
            }
            if ($output->reserveinputs !== '') {
                $reserve_inputs = explode(', ', str_replace('.', '', $output->reserveinputs));
                foreach ($reserve_inputs as $reserve_input) {
                    $retired_input_id = explode(' ', $reserve_input);
                    if (array_key_exists((int)$retired_input_id[0], $retired_devices) !== false) {
                        if (array_key_exists((int)$retired_input_id[0], $used_retired_devices) == false) {
                            $used_retired_devices[$output->device_id] = $output->device_id;
                        }
                    }
                }
            }
        }

        /****************************************************/


        // WIRED RELAYS: Get vector info and create multidimensional array reflecting 4-board setup with 4 positions on each board
        $activeRelays = [];
        $activeRelayString = str_pad(decbin($thisSys->active_relays), 16, '0', STR_PAD_LEFT); //Create a string representation of the binary value
        $board = 1;
        $position = 1;
        for ($i = 0; $i < strlen($activeRelayString); $i++) {
            if ($activeRelayString[$i] == 1) {
                $activeRelays[$board][$position] = 1;
            }
            $position++;
            if ($position == 5) {
                $board++;
                $position = 1;
            }
        }

        // ACTIVE INPUTS: Get vector info and create array reflecting 4-board setup with 8 positions on each board
        $activeInputs = [];
        $activeInputString = str_pad(decbin($thisSys->active_inputs1), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs2), 8, '0', STR_PAD_LEFT) .
                            str_pad(decbin($thisSys->active_inputs3), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs4), 8, '0', STR_PAD_LEFT);
        $board = 1;
        $position = 1;
        for ($i = 0; $i < strlen($activeInputString); $i++) {
            if ($activeInputString[$i] == 1) {
                $activeInputs[$board][$position] = 1;
            }
            $position++;
            if ($position == 9) {
                $board++;
                $position = 1;
            }
        }

        $zones = Zone::where('system_id', $thisSys->id)
          ->orderby('zone', 'ASC')
          ->get();

        $zone_labels = [];

        foreach ($zones as $zone) {
            $zone_labels[$zone->zone] = $zone->zonename;
        }

        /********* **** *** *** ** ** * * * * * *  *  *  *  *   *   *   *  *  *  * * * * ** ** ** *** *** *** **** **** *****
    	* 									CREATE 		WIRED 		SENSOR 		CHECKBOX 	DATA 							*
    	********** **** *** *** ** ** * * * * * *  *  *  *  *   *   *   *  *  *  * * * * ** ** ** *** *** *** **** **** *****/
        $ewd = Device::where('system_id', $sid)
            ->where('device_mode', 'wired')
            ->where('retired', '0')
            ->get();
        $existing_wired_devices = [];
        foreach ($ewd as $key => $value) {
            $existing_wired_devices[$value->location] = true;
        }
        $active_components = [];
        $hardware_options = SystemController::initialize_hardware_versions();               //Initialize the hardware options array
        foreach ($hardware_options as $hardware_option => $hwo) {
            switch ($thisSys->hardware_version) {
                /*SELECT ACTIONS BASED ON CURRENT HARDWARE VERSION*/
                case '01.03.03.00':
                    if (($hardware_option == '01.03.03.00')||($hardware_option == '01.03.00.00')) {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            /* Active device location does not exist in system */
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '01.03.00.00':
                    if ($hardware_option == '01.03.00.00') {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            /* Active device location does not exist in system */
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '01.00.00.00':
                    foreach ($hwo as $location => $params) {
                        $active_components[$hardware_option][$location] = 0;
                    }
                    break;
                case '11.14.14.14':
                    if (($hardware_option == '11.14.14.14')||($hardware_option == '11.14.14.00')||($hardware_option == '11.14.00.00')||($hardware_option == '11.00.00.00')) {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '11.14.14.00':
                    if (($hardware_option == '11.14.14.00')||($hardware_option == '11.14.00.00')||($hardware_option == '11.00.00.00')) {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '11.14.00.00':
                    if (($hardware_option == '11.14.00.00')||($hardware_option == '11.00.00.00')) {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '11.13.14.00':
                    if (($hardware_option == '11.13.14.00')||($hardware_option == '11.13.00.00')||($hardware_option == '11.00.00.00')) {
                        foreach ($hwo as $location => $params) {
                                    $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '11.13.00.00':
                    if (($hardware_option == '11.13.00.00')||($hardware_option == '11.00.00.00')) {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                case '11.00.00.00':
                    if ($hardware_option == '11.00.00.00') {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = (isset($existing_wired_devices[$location]))?1:0;
                        }
                    } else {
                        foreach ($hwo as $location => $params) {
                            $active_components[$hardware_option][$location] = 0;
                        }
                    }
                    break;
                default:
                    /* UNRECOGNIZED HARDWARE VERSION RETREIVED FROM DATABASE */
                    break;
            }
        }

        /****************************************************/


        return view('buildings.config.editsystem', ['thisBldg' => $thisBldg])
            ->with('systemsData', $systems)
            ->with('relay_devices', $relay_devices)
            ->with('wired_input_devices', $wired_input_devices)
            ->with('wirelessdevices', $wirelessdevices)
            ->with('bacnetdevices', $bacnetdevices)
            ->with('utilitydevices', $utility_devices)
            ->with('devicetypes', $devicetypes)
            ->with('products', $products)
            ->with('thisSystem', $thisSys)
            ->with('thisNetwork', $thisNetwork)
            ->with('activeRelays', $activeRelays)
            ->with('used_retired_devices', $used_retired_devices)
            ->with('zone_labels', $zone_labels)
            ->with('activeInputs', $activeInputs)
            ->with('devicesoffset', $device_env_offset)
            ->with('product_commands', $product_commands)
            ->with('device_type_names', $device_type_names)
            ->with('device_type_units', $device_type_units)
            ->with('hardware_options', $hardware_options)
            ->with('active_components', $active_components)
            // ->with('reset_log',$reset_log)
            ->with('latest_sw_version', $latest_sw_version->software_version);
    }

    /* -------------------------------------------------------
    | Receive device table entry, selection results array, prescriptions.
    | Verify values of: retired, active, inhibited, device_io, 
    |-------------------------------------------------------------------------*/
    private function dev_gen($Sys, $Bldg, $source_params, $requested_location, $existing_dev, $usr)
    {
        $timezone = "America/New_York";
        date_default_timezone_set($timezone);
        $date=date_create();
        $now = time();
        date_timestamp_set($date, $now);
        $update_time=date_format($date, "Y-m-d H:i:s");
        $sys_id = $Sys->id;
        if (isset($existing_dev)===false) {
            /*	Create device at requested location
			* * * * * * * * * * * * * * * * * * * * */
            $existing_dev = new Device;
            /*----Generate/Assign new device ID----*/
            $dev_id = Device::where('system_id', $sys_id)->orderBy('id', 'desc')->limit(1)->first();
            $dev_id = (isset($dev_id))?$dev_id->id:0;
            $existing_dev->id = ($dev_id + 1);
            $existing_dev->building_id = $Bldg->id;
            $existing_dev->system_id = $sys_id;
            $existing_dev->location = $requested_location;
            $existing_dev->device_function = $source_params[2];
            $existing_dev->functional_description = $source_params[2];
            $existing_dev->device_types_id = $source_params[4];
            $existing_dev->product_id = '-';
            $existing_dev->retired = '0';
            $existing_dev->inhibited = '0';
            $existing_dev->status = '1';
            $existing_dev->comments = 'none';
            $existing_dev->physical_location = ' ';
            $existing_dev->powerlevel = 0;
            $existing_dev->reporttime = 'NA';
            $existing_dev->name = $source_params[0].'-'.$source_params[1].'--'.$source_params[2].'-'.$source_params[3];
            $existing_dev->zone = 0;
            $existing_dev->short_address = 0;
            $existing_dev->mac_address = 0;
            $existing_dev->device_io = $source_params[5];
            $existing_dev->device_mode = 'wired';
            $existing_dev->instance = 0;
            $existing_dev->bacnet_object_type = 0;
            $existing_dev->updated_at = $update_time;
            $existing_dev->created_at = $update_time;
            SystemLog::info($sys_id, "User ".$usr.": Dev Gen: New Device [".$existing_dev->id."] Location [".$requested_location."]", 25);
        } else {
            /* Device exists at $requested_location
			* * * * * * * * * * * * * * * * * * * * */
            // if($existing_dev->device_function != $source_params[2]){
            // 	SystemLog::info($sys_id,"User ".$usr.": Dev Gen: Update Location [".$requested_location."] device_function from [".$existing_dev->device_function."] to [".$source_params[2]."]",25);
            // 	$existing_dev->device_function = $source_params[2];
            // }
            if ($existing_dev->device_types_id != $source_params[4]) {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] device_types_id from [".$existing_dev->device_types_id."] to [".$source_params[4]."]", 25);
                $existing_dev->device_types_id = $source_params[4];
            }
            // if($existing_dev->product_id != '-'){
            // 	SystemLog::info($sys_id,"User ".$usr.": Dev Gen: Update Location [".$requested_location."] product_id from [".$existing_dev->product_id."] to [-]",25);
            // 	$existing_dev->product_id = '-';
            // }
            if ($existing_dev->retired != '0') {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] retired from [".$existing_dev->retired."] to ['0']", 25);
                $existing_dev->retired = '0';
            }
            if ($existing_dev->inhibited != '0') {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] inhibited from [".$existing_dev->inhibited."] to ['0']", 25);
                $existing_dev->inhibited = '0';
            }
            if ($existing_dev->status != '1') {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] active from [".$existing_dev->status."] to ['1']", 25);
                $existing_dev->status = '1';
            }
            if ($existing_dev->device_io != $source_params[5]) {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] device_io from [".$existing_dev->device_io."] to [".$source_params[5]."]", 25);
                $existing_dev->device_io = $source_params[5];
            }
            if ($existing_dev->device_mode != 'wired') {
                SystemLog::info($sys_id, "User ".$usr.": Dev Gen: Update Location [".$requested_location."] device_mode from [".$existing_dev->device_mode."] to ['wired']", 25);
                $existing_dev->device_mode = 'wired';
            }
            $existing_dev->updated_at = $update_time;
            // $existing_dev->comments : stays the same
            // $existing_dev->physical_location : stays the same
            // $existing_dev->powerlevel : stays the same
            // $existing_dev->reporttime : stays the same
            // $existing_dev->name : stays the same
            // $existing_dev->zone : stays the same
            // $existing_dev->short_address : stays the same
            // $existing_dev->mac_address : stays the same
            // $existing_dev->functional_description : stays the same
            // $existing_dev->instance : stays the same
            // $existing_dev->bacnet_object_type : stays the same
            // $existing_dev->id : stays the same
            // $existing_dev->building_id : stays the same
            // $existing_dev->system_id : stays the same
        }
        $existing_dev->save();
    }
    public $checkOn;
    public $checkOff;
    public $session_debug;
    /* -------------------------------------------------------
    |	Call for creation or retirement of hardware expansion board peripherals
    | 	based on predefined hardware option, existing system devices and user input
    |-------------------------------------------------------------------------*/
    private function update_board($board_hardware_option, $wired_devices, $hw_version, $input, $Sys, $Bldg)
    {

        global $checkOff;
        global $checkOn;
        global $session_debug;
        $hw_version = str_replace(".", "-", $hw_version);
        foreach ($board_hardware_option as $location => $params) {
            /*get location options for this board */
            $this_device = null;
            /*Find if the device location exists for the system*/
            foreach ($wired_devices as $key => $dev) {
                if ($dev->location == $location) {
                    $this_device = $dev; /*record the first match*/
                }
            }

            /*
			Determine whether a relevant checkbox setting exists
			*/
            $checkbox_res = isset($input['expansion_io-'.$hw_version.'-'.$location])?'ON':'OFF';
            $checkOff += ($checkbox_res == 'OFF')? 1:0;
            $checkOn += ($checkbox_res == 'ON')? 1:0;
            if (isset($this_device)) {
                /*----
				Wired device location previously mapped
				----*/
                if ($checkbox_res === 'ON') {
                    /*----
					Device marked for activation
					----*/
                    if (isset($input['reactivate'])) {
                        /*----
						Reuse most recent inhibited or retired device from same location 
						----*/
                        SystemController::dev_gen($Sys, $Bldg, $params, $location, $this_device, $input['current_user']);
                    } else {
                        /*----
						Create New Device 
						----*/
                        SystemController::dev_gen($Sys, $Bldg, $params, $location, null, $input['current_user']);
                    }
                } else {
                    /*----
					Deactivate wired devices at this location
					----*/
                    DB::table('devices')
                        ->where('system_id', $Sys->id)
                        ->where('location', $location)
                        ->where('device_mode', 'wired')
                        ->update(['status'=>'0','inhibited'=>'1','retired'=>'1']);
                    SystemLog::info($Sys->id, "Wired Devices Retired at location ".$location." based on $checkbox_res marked OFF", 25);
                }
            } elseif ($checkbox_res === 'ON') {
                /*----
				Device marked for activation: Create New Device
				----*/
                SystemController::dev_gen($Sys, $Bldg, $params, $location, null, $input['current_user']);
            }
        }
    }

    /* -------------------------------------------------------
    | function to use with edit system to add/update expansion board devices
    | Update boards according to hardware version
    |----------------------------------------------------------*/
    private function hardware_verification($Sys, $Bldg, $input, $hw_version)
    {
        global $session_debug;
        /*Predefined device types*/
        $analog_dt = 5;
        $digital_dt = 6;
        $relay_dt = 9;

        $wired_devices = Device::where('system_id', $Sys->id)
            ->where('device_mode', 'wired')
            ->orderBy('inhibited', 'asc')
            ->orderBy('retired', 'asc')
            ->get();
        foreach ($wired_devices as $key => $value) {
        }
        $hardware_options = SystemController::initialize_hardware_versions();   //Initialize the hardware options array

        /**********************************************************************************************************************
		*	Based on the hardware_version, loop through the list of possible expansion I/O's, found in the input array,  
		*	Determine whether latest setting of device matches what's currently found in the devices table.
		*	Make updates and log changes.
		**********************************************************************************************************************/
        switch ($hw_version) {
            case '01.03.03.00':
                SystemController::update_board($hardware_options['01.03.03.00'], $wired_devices, '01.03.03.00', $input, $Sys, $Bldg);
                /*--FALL THRU TO NEXT CASE--*/
            case '01.03.00.00':
                SystemController::update_board($hardware_options['01.03.00.00'], $wired_devices, '01.03.00.00', $input, $Sys, $Bldg);
                break;
            case '01.00.00.00':
                foreach ($wired_devices as $key => $this_device) {
                    /*	Deactivate all wired devices at this location
					----*/
                    DB::table('devices')
                        ->where('system_id', $Sys->id)
                        ->where('device_mode', 'wired')
                        ->update(['status'=>'0','inhibited'=>'1','retired'=>'1']);
                    SystemLog::info($Sys->id, "Wired Devices Retired for Hardware Version 01.00.00.00", 25);
                    /*TODO: REMOVE DEVICE_SETPOINTS & OUTPUT_MAPPING*/
                }
                break;

            case '11.14.14.14':
                SystemController::update_board($hardware_options['11.14.14.14'], $wired_devices, '11.14.14.14', $input, $Sys, $Bldg);
                /*--FALL THRU TO NEXT CASE--*/
            case '11.14.14.00':
                SystemController::update_board($hardware_options['11.14.14.00'], $wired_devices, '11.14.14.00', $input, $Sys, $Bldg);
                /*--FALL THRU TO NEXT CASE--*/
            case '11.14.00.00':
                SystemController::update_board($hardware_options['11.14.00.00'], $wired_devices, '11.14.00.00', $input, $Sys, $Bldg);
                /*--FALL THRU TO NEXT CASE--*/
            case '11.00.00.00':
                SystemController::update_board($hardware_options['11.00.00.00'], $wired_devices, '11.00.00.00', $input, $Sys, $Bldg);
                break;

            case '11.13.14.00':
                SystemController::update_board($hardware_options['11.13.14.00'], $wired_devices, '11.13.14.00', $input, $Sys, $Bldg);
                /*--FALL THRU TO NEXT CASE--*/
            case '11.13.00.00':
                SystemController::update_board($hardware_options['11.13.00.00'], $wired_devices, '11.13.00.00', $input, $Sys, $Bldg);
                SystemController::update_board($hardware_options['11.00.00.00'], $wired_devices, '11.00.00.00', $input, $Sys, $Bldg);
                break;

            default:
                SystemLog::error($Sys->id, "User: ".$input['current_user'].": Unknown Hardware Version: [".$hw_version."]", 26);
                Session::flash('error', 'Bad Hardware Version: '.$hw_version);
                return Redirect::route('system.editSystem', [$Bldg->id, $Sys->id]);
                break;
        }
    }

    /*--------------------------------------------------------------------------
	| Edit System form POST controllers
	|-------------------------------------------------------------------------*/
    public function updatesystem($id, $sid)
    {
        global $checkOff;
        global $checkOn;
        global $session_debug;

        $checkOff = 0;
        $checkOn = 0;
        $session_debug = null;
        $thisBldg = Building::find($id); // Lookup info for selected building
        $systems = System::where('building_id', $thisBldg->id)->get(); // Lookup all systems associated with selected building for nav dropdown

        $thisSys = System::find($sid); // Lookup info for selected system

        $reset_log = SystemLog::where('system_id', $thisSys->id)
            ->where('application_name', 'LINUX')
            ->where('log_type', '5') //HARDWARE_RESET log type
            ->orderBy('recnum', 'DESC')
            ->limit('1')
            ->get();

        $input = Input::except('_token');
        log::info($input);
        /*--------------------------------------------------------------------------
		| System Configuration Tab
		|-------------------------------------------------------------------------*/
        if (isset($input['Config'])) {
            unset($input['Config']);

            /********************************************************************************************************************
			*	Update changed system parameters, where the input index name matches the table column names (covers most inputs)
			*********************************************************************************************************************/
            foreach ($input as $key => $value) {
                if ((strpos($key, "expansion_io-")===false) &
                     (strpos($key, "exp_board_")===false) &
                     (strpos($key, "main_board_version")===false) &
                     (strpos($key, "reactivate")===false) &
                     (strpos($key, "downlink")===false) &
                     (strpos($key, "current_user")===false) &
                     (strpos($key, "static_ip")===false) &
                     (strpos($key, "netmask")===false) &
                     (strpos($key, "gateway")===false) &
                     (strpos($key, "dns_nameserver")===false) ) {
                    /*Associate keys with table entries*/
                    if ($thisSys->$key != $value) {
                        SystemLog::info($thisSys->id, "User: [".$input['current_user']."]: Change [".$key."] from [".$thisSys->$key."] to [".$value."]", 25);
                        $thisSys->$key = $value;
                    }
                }
            }

            /**********************************************************************************************************************
			*	Network Update
			**********************************************************************************************************************/
            $thisNetwork = NetworkSettings::where('system_id', $thisSys->id)
                ->whereNull('deleted_at')
                ->orderby('id', 'DESC')
                ->limit(1)
                ->get()
                ->first();

            if (isset($input['downlink'])) {
                if ($thisSys->downlink != 1) {
                    $thisSys->downlink = 1;
                    /*Remove stored network settings*/
                    try {
                        /*If an active entry was found, "delete" it*/
                        $timezone = "America/New_York";
                        date_default_timezone_set($timezone);
                        $date = date_create();
                        $now = time();
                        date_timestamp_set($date, $now);
                        $current_time = date_format($date, "Y-m-d H:i:s");
                        
                        if (isset($thisNetwork)) {
                            $thisNetwork->deleted_at = $current_time;
                            $thisNetwork->save();
                        }

                        SystemController::DeployNetworkInterfaces($thisSys->id);
                    } catch (Exception $e) {
                        SystemLog::error($thisSys->id, $e, 26);
                    }
                }
            } else {
                if ($thisSys->downlink == 1) {
                    /*If an active entry was found, "delete" it*/
                    $thisSys->downlink = 0;
                    $timezone = "America/New_York";
                    date_default_timezone_set($timezone);
                    $date = date_create();
                    $now = time();
                    date_timestamp_set($date, $now);
                    $current_time = date_format($date, "Y-m-d H:i:s");

                    if (isset($thisNetwork)) {
                        $thisNetwork->deleted_at = $current_time;
                        $thisNetwork->save();
                    }
                    /*Create new static settings*/
                    $thisNetwork = new NetworkSettings();
                    $thisNetwork->created_at = $current_time;
                    $thisNetwork->updated_at = $current_time;
                    $thisNetwork->system_id = $thisSys->id;
                    $thisNetwork->dns_nameserver = $input['dns_nameserver'];
                    $thisNetwork->gateway = $input['gateway'];
                    $thisNetwork->netmask = $input['netmask'];
                    $thisNetwork->static_ip = $input['static_ip'];
                    $thisNetwork->save();

                    try {
                        SystemController::DeployNetworkInterfaces($thisSys->id, $thisNetwork->id, false);
                    } catch (Exception $e) {
                        SystemLog::error($thisSys->id, $e, 26);
                    }
                } else {
                    /*Update static settings*/
                    $thisSys->downlink = 0;
                    if (isset($thisNetwork)) {
                        if ($thisNetwork->dns_nameserver != $input['dns_nameserver']) {
                            SystemLog::info($thisSys->id, "Change [dns_nameserver] from [".$thisNetwork->dns_nameserver."] to [".$input['dns_nameserver']."]", 25);
                            $thisNetwork->dns_nameserver = $input['dns_nameserver'];
                        }
                        if ($thisNetwork->gateway != $input['gateway']) {
                            SystemLog::info($thisSys->id, "Change [gateway] from [".$thisNetwork->gateway."] to [".$input['gateway']."]", 25);
                            $thisNetwork->gateway = $input['gateway'];
                        }
                        if ($thisNetwork->netmask != $input['netmask']) {
                            SystemLog::info($thisSys->id, "Change [netmask] from [".$thisNetwork->netmask."] to [".$input['netmask']."]", 25);
                            $thisNetwork->netmask = $input['netmask'];
                        }
                        if ($thisNetwork->static_ip != $input['static_ip']) {
                            SystemLog::info($thisSys->id, "Change [static_ip] from [".$thisNetwork->static_ip."] to [".$input['static_ip']."]", 25);
                            $thisNetwork->static_ip = $input['static_ip'];
                        }
                        $thisNetwork->save();
                        try {
                            SystemController::DeployNetworkInterfaces($thisSys->id, $thisNetwork->id, false);
                        } catch (Exception $e) {
                            SystemLog::error($thisSys->id, $e, 26);
                        }
                    }
                }
            }



            /**********************************
			*	Expansion Hardware Update
			* * * * * * * * * * * * * * * * * * * ***/
            if (isset($input['main_board_version'])) {
                /*
				*	Calculate and Record hardware_version
				*		Set unset exp_board_ _version values to '00'
				*********************************************************************************/
                $hardware_version = $input['main_board_version'].".".
                    (isset($input['exp_board_1_version'])?$input['exp_board_1_version']:"00").".".
                    (isset($input['exp_board_2_version'])?$input['exp_board_2_version']:"00").".".
                    (isset($input['exp_board_3_version'])?$input['exp_board_3_version']:"00");
                if ($thisSys->hardware_version != $hardware_version) {
                    SystemLog::info($thisSys->id, "Change [hardware_version] from [".$thisSys->hardware_version."] to [".$hardware_version."]", 25);
                    $thisSys->hardware_version = $hardware_version;
                }
                SystemController::hardware_verification($thisSys, $thisBldg, $input, $hardware_version);
                //Session::flash('success',$session_debug);
            } else {
            /*
			*	*		Mainboard version unset
				*	hardware_version is left as-is in table 
				******************************************************************************/
                Session::flash('error', 'Error Setting Main Board Hardware Version; Contact Your System Administrator for Assistance.');
                SystemLog::error($thisSys->id, "SystemController::updatesystem(): Failed to determine Main Board Hardware Version during System Update", 26);
            }

            /*
			*	Update Zone Count, Add Zone Defaults
			*/
            $zones = Zone::where('system_id', $sid)->orderby('zone')->get();
            $old_num_zones = count($zones->toArray());
            $new_num_zones = $input['system_zones'];
            if ($old_num_zones < $new_num_zones) {
                for ($count = $old_num_zones + 1; $count <= $new_num_zones; $count++) {
                    $zone = new Zone();
                    $zone->system_id = $sid;
                    $zone->zone = $count;
                    $zone->zonename = 'Zone '. $count;
                    $zone->save();
                }
            } else if ($old_num_zones > $new_num_zones) {
                foreach ($zones as $zone) {
                    if ($zone->zone > $new_num_zones) {
                        $zone->delete();
                    }
                }
            }

            $thisSys->active_relays = 0;
            $thisSys->current_loop_dvrs = 0;
            $thisSys->current_loop_inputs = 0;
            $thisSys->update_flag = 1;
            $thisSys->wired_relay_outputs = 0;
            $thisSys->current_loop_outputs = 0;
            $thisSys->wired_sensors = 0;
            //$thisSys->hardware_version = $input['hardware_version'];
            $thisSys->save();

            try {
                SystemController::DeployConfig($thisSys->id, true);
                SystemController::DeployLocalParam($thisSys->id);
            } catch (Exception $e) {
                SystemLog::error($thisSys->id, $e, 26);
            };

            Session::flash('success', 'System configuration updated. '.$hardware_version);

            return Redirect::route('system.editSystem', [$thisBldg->id, $thisSys->id]);
        } /*--------------------------------------------------------------------------
		| Update Devices Button
		|-------------------------------------------------------------------------*/
        else if (isset($input['Devices'])) {
            unset($input['Devices']);
            
            // for each device retrieve from db, update fields, and save
            foreach ($input as $key => $value) {
                if (strpos($key, "current_user")===false) {
                    $remapSetpoint = 0;

                    // Retrieve device number
                    $temp = explode(":", $key);
                    $deviceNum = $temp[1];
                    $device = Device::find($deviceNum);

                    //CHECK FOR CHANGES TO DEVICE ATTRIBUTES
                    if (isset($value['device_mode'])) {
                        if ($value['device_mode'] != $device->device_mode) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change device_mode from [".$device->device_mode."] to [".$value['device_mode']."]", 25);
                            $device->device_mode = $value['device_mode'];
                        }
                    }
                    if (isset($value['device_function'])) {
                        if ($value['device_function'] != $device->device_function) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change device_function from [".$device->device_function."] to [".$value['device_function']."]", 25);
                            $device->device_function = $value['device_function'];
                        }
                    }
                    if (isset($value['product_id'])) {
                        if ($value['product_id'] !== $device->product_id) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change product_id from [".$device->product_id."] to [".$value['product_id']."]", 25);
                            $device->product_id = (isset($value['product_id'])?$value['product_id']:$device->product_id);
                            // Remove the old setpoint when the product id changes
                            $oldSetpoints = DeviceSetpoints::where('system_id', $thisSys->id)
                                ->where('device_id', $device->id)
                                ->delete();
                            $remapSetpoint = 1;
                        }
                    }
                    if (isset($value['mac_address'])) {
                        if ($value['mac_address'] != $device->mac_address) {
                            SystemLog::info($thisSys->ids_id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change mac_address from [".$device->mac_address."] to [".$value['mac_address']."]", 25);
                            $device->mac_address = $value['mac_address'];
                        }
                    }
                    if (isset($value['short_address'])) {
                        if ($value['short_address'] != $device->short_address) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change short_address from [".$device->short_address."] to [".$value['short_address']."]", 25);
                            $device->short_address = $value['short_address'];
                        }
                    }
                    if (isset($value['location'])) {
                        if ($value['location'] != $device->location) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change location from [".$device->location."] to [".$value['location']."]", 25);
                            $device->location = $value['location'];
                        }
                    }
                    if (isset($value['name'])) {
                        if ($value['name'] != $device->name) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change name from [".$device->name."] to [".$value['name']."]", 25);
                            $device->name = $value['name'];
                        }
                    }
                    if (isset($value['comments'])) {
                        if ($value['comments'] != $device->comments) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change comments from [".$device->comments."] to [".$value['comments']."]", 25);
                            $device->comments = $value['comments'];
                        }
                    }
                    if (isset($value['zone'])) {
                        if ($value['zone'] != $device->zone) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change zone from [".$device->zone."] to [".$value['zone']."]", 25);
                            $device->zone = $value['zone'];
                        }
                    }
                    if (isset($value['functional_description'])) {
                        if ($value['functional_description'] != $device->functional_description) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change functional_description from [".$device->functional_description."] to [".$value['functional_description']."]", 25);
                            $device->functional_description = $value['functional_description'];
                        }
                    }
                    if (isset($value['reporttime'])) {
                        if ($value['reporttime'] != $device->reporttime) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change reporttime from [".$device->reporttime."] to [".$value['reporttime']."]", 25);
                            $device->reporttime = $value['reporttime'];
                        }
                    }
                    if (isset($value['physical_location'])) {
                        if ($value['physical_location'] != $device->physical_location) {
                            SystemLog::info($thisSys->id, "User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change physical_location from [".$device->physical_location."] to [".$value['physical_location']."]", 25);
                            $device->physical_location = $value['physical_location'];
                        }
                    }

                    // SystemLog::info($sys_id,"User ".$input['current_user'].": edit to System(".$thisSys->id.")-Device(".$device->id."): change  from [".."] to [".."]",25);

                    $offset_update = false;
                    $devtypes = DeviceType::all();
                    foreach ($devtypes as $dt) {
                        if (isset($value['offset-'.$device->id.'-'.$dt->command])) {
                            /*We store the offset in the system's respective format (i.e. Fahrenheit offsets are stored as such,
			        		and the same with Celcius offsets). The Offset will be sent to the individual system in the stored format, 
			        		at which point any necessary conversion will be taken into account (in accordance with the system's temperature format).
			        		This is dues to lack of ability to convert use 'converted offsets' from the db to offset incoming values. */

                            $offset_val = $value['offset-'.$device->id.'-'.$dt->command];
                            $initial_offset_val = $value['original_offset-'.$device->id.'-'.$dt->command];

                            if ($offset_val != $initial_offset_val) {
                                $offset_update = true;
                                $setP = DeviceSetpoints::where('system_id', $thisSys->id)
                                    ->where('command', $dt->command)
                                    ->where('device_id', $device->id)
                                    ->limit('1')
                                    ->update(['environmental_offset' => $offset_val]);
                                $log_report = "ENVIRONMENTAL_OFFSET CHANGED FOR [ID:".$device->id."][TYPE:".$dt->command."] FROM [".$initial_offset_val."] TO [".$offset_val."]";
                                SystemLog::info($thisSys->id, $log_report, 10);
                            }
                        }
                    }
                    if ($offset_update == true) {
                        SetpointMappingController::DeploySetpoints($thisSys->id);
                        SystemController::DeployLocalParam($thisSys->id);
                    }
                    

                    
                    

                    $DevStatus=(isset($value['status'])?$value['status']:(($device->status * 0x04)+($device->inhibited * 0x02)+($device->retired * 0x01)));

                    // ORPHAN DEVICE COMMISSIONING
                    // if commisioning box is checked
                    // look at Status field if new then update device_id to next valid id
                    // if status is replace find location, zone, phyiscal location of specific retired sensor to be replaced
                    // update these fields in this sensor concatenate comments
                    // set update flag to force EMC to send location to zigbee device if applicable
                    // set status to active
                    if (isset($value['comm'])) { //commissioning checkbox
                        if ($value['comm']==1) {
                            //  find next valid id
                            $NextID = Device::where('system_id', $thisSys->id)
                                ->where('id', '>', 55)
                                ->where('id', '<', 32767)
                                ->orderby('id', 'desc')
                                ->first();
                            if (!count($NextID)) {
                                $NextID = new Device();
                                $NextID->id = 55;
                            }
                            $device->id=($NextID->id)+1;
                            if ($value['status'] !="New") {
                                $DevStatus = $value['status'];
                                // replacement device
                                // first find retired device
                                //$ReplaceID=substr($DevStatus,strpos($DevStatus,"-"));
                                $ReplaceID=$DevStatus;
                                $Replace = Device::where('system_id', $thisSys->id)
                                    ->where('id', $ReplaceID)
                                    ->limit('1')
                                    ->get();

                                $Retired = new Device();
                                // now transfer parameters
                                foreach ($Replace as $W) {
                                    $device->name = $W->name;
                                    $device->zone= $W->zone;
                                    $device->physical_location= $W->physical_location;
                                    $device->reporttime= $W->reporttime;
                                    $device->comments="Replaced device #".$ReplaceID."::".$W->comments.".";
                                    $device->device_types_id=$W->device_types_id;
                                    $device->bacnet_object_type=$W->bacnet_object_type;
                                    $device->instance=$W->instance;
                                    $device->functional_description=$W->functional_description;
                                }
                                SystemLog::info($thisSys->id, "User ".$input['current_user'].": commissioned Short Address (".$device->short_address.") to Device(".$device->id.") System(".$thisSys->id.")", 25);
                                //TODO: UPDATE device_setpoints -> check for retired device entry; replace with new device id.
                                // update mapping output table - replace all old ids with new ids
                                $remapSetpoint = 1;
                            }
                            $DevStatus=0x04;  // sets to active
                        } // commissioning checked

                        //Add newly commissioned devices to the device_setpoints table.
                        // $devicetypes = DB::table('device_types')->select(DB::raw('distinct function'))
                        // 		->orderby('function')
                        // 		->get();
                        // $deviceTypes = DeviceType::all();
                        // $deviceProduct = ProductType::where('product_id',$device->product_id)
                        //   ->first();
                        // if($deviceProduct->product_type == 'Sensor'){
                        //   $commandTypes = explode(',',$deviceProduct->commands);
                        //   foreach($commandTypes as $type){
                        //     $currentType = DeviceType::where('command',$type)
                        //       ->first();
                        //     $deviceSetpoint = new DeviceSetpoints();

                        //     $deviceSetpoint->device_id = $device->id;
                        //     $deviceSetpoint->system_id = $thisSys->id;
                        //     $deviceSetpoint->command = $type;
                        //     $deviceSetpoint->setpoint = $currentType->setpoint;
                        //     $deviceSetpoint->hysteresis = $currentType->hysteresis;
                        //     $deviceSetpoint->alarm_high = $currentType->alarm_high;
                        //     $deviceSetpoint->alarm_low = $currentType->alarm_low;
                        //     $deviceSetpoint->environmental_offset = 0;
                        //     $deviceSetpoint->save();
                        //   }
                        // }
                    }  // end commissioning

                    //Decode returned status value into status, inhibited and retired
                    // Valid States 0 - uncommisioned, 5,7 Retired, 6 -  inhibited

                    $device->status = ($DevStatus & 0x04) == 0x04;
                    $device->inhibited = ($DevStatus & 0x02) == 0x02;
                    $device->retired = ($DevStatus & 0x01) == 0x01;

                    // Set the device type id based on selected device function. wired relays and current loops always 9 and 8 so no need to check
                    if ($value['device_type']=="wired_sensor") {
                        if ($value['device_function'] == "Analog") {
                            $device->device_types_id = 5;
                        } else if ($value['device_function'] == "Digital") {
                            $device->device_types_id = 6;
                        } else if ($value['device_function'] == "Current") {
                            $device->device_types_id = 7;
                        }
                    } else if ($value['device_type']=="wireless_sensor") {
                        if ($value['device_function'] == "Temperature") {
                            $device->device_types_id = 1;
                        } else if ($value['device_function'] == "Voltage") {
                            $device->device_types_id = 2;
                        } else if ($value['device_function'] == "Light") {
                            $device->device_types_id = 3;
                        } else if ($value['device_function'] == "Occupancy") {
                            $device->device_types_id = 4;
                        }
                    }

                    $device->save();
                    if ($remapSetpoint == 1) {
                        SetpointMappingController::mapDevice($thisBldg->id, $thisSys->id, $device->id);
                    }
                }
            }

            // re-query $devices
            $devices = DB::table('devices')->where('system_id', $thisSys->id)->get();

            // Set updateflag on changed system
            $thisSys->update_flag = 1;
            $thisSys->save();

            // WIRED RELAYS: Get vector info and create multidimensional array reflecting 4-board setup with 4 positions on each board
            $activeRelays = [];
            $activeRelayString = str_pad(decbin($thisSys->active_relays), 16, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeRelayString); $i++) {
                if ($activeRelayString[$i] == 1) {
                    $activeRelays[$board][$position] = 1;
                }
                $position++;
                if ($position == 5) {
                    $board++;
                    $position = 1;
                }
            }
            // CURRENT LOOPS: Get vector info to check correct checkboxes
            $currentLoops = [];
            $currentLoopString = str_pad(decbin($thisSys->current_loop_dvrs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopString); $i++) {
                if ($currentLoopString[$i] == 1) {
                    $currentLoops[$i+1] = 1;
                }
            }

            // CURRENT LOOP INPUTS: Get vector info to check correct checkboxes
            $currentLoopsInput = [];
            $currentLoopStringInput = str_pad(decbin($thisSys->current_loop_inputs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopStringInput); $i++) {
                if ($currentLoopStringInput[$i] == 1) {
                    $currentLoopsInput[$i+1] = 1;
                }
            }

            // ACTIVE INPUTS: Get vector info and create array reflecting 4-board setup with 8 positions on each board
            $activeInputs = [];
            $activeInputString = str_pad(decbin($thisSys->active_inputs1), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs2), 8, '0', STR_PAD_LEFT) .
                                str_pad(decbin($thisSys->active_inputs3), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs4), 8, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeInputString); $i++) {
                if ($activeInputString[$i] == 1) {
                    $activeInputs[$board][$position] = 1;
                }
                $position++;
                if ($position == 9) {
                    $board++;
                    $position = 1;
                }
            }

            try {
                SystemController::DeployDeviceMapping($thisSys->coordinator_format, $thisSys->id, false);
            } catch (Exception $e) {
            };

            $zones = Zone::where('system_id', $thisSys->id)
                ->orderby('zone', 'ASC')
                ->get();

            $zone_labels = [];

            foreach ($zones as $zone) {
                $zone_labels[$zone->zone] = $zone->zonename;
            }

            $confirm = "updateDevice";

            return Redirect::route('system.editSystem', [$thisBldg->id, $thisSys->id]);
        } /*--------------------------------------------------------------------------
		| Add Device button
		|-------------------------------------------------------------------------*/
        else if (isset($input['AddDevice'])) {
            unset($input['AddDevice']);
            // $wirelessdevices = Device::where('system_id', $thisSys->id)
            // 	->whereIn('device_mode',['wireless','echostream'])
            // 	->orderby('id')->get(); // Lookup all devices associated with this system

            // Find last device for system and get new device number from it
            $last = Device::where('system_id', $thisSys->id)->orderBy('id', 'desc')->first();

            if (!$last) {
                $newDeviceNum = 1;
            } else {
                $newDeviceNum = $last->id + 1;
            }

            $device = new Device;

            foreach ($input as $key => $value) {
                $device->$key = $value;
            }

            $device->id = $newDeviceNum;
            $device->building_id = $thisSys->building_id;
            $device->system_id = $thisSys->id;

            $device->save();

            // re-query $devices
            $devices = DB::table('devices')->where('system_id', $thisSys->id)->get();

            // Set updateflag on changed system
            $thisSys->update_flag = 1;
            $thisSys->save();

            // WIRED RELAYS: Get vector info and create multidimensional array reflecting 4-board setup with 4 positions on each board
            $activeRelays = [];
            $activeRelayString = str_pad(decbin($thisSys->active_relays), 16, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeRelayString); $i++) {
                if ($activeRelayString[$i] == 1) {
                    $activeRelays[$board][$position] = 1;
                }
                $position++;
                if ($position == 5) {
                    $board++;
                    $position = 1;
                }
            }
            // CURRENT LOOPS: Get vector info to check correct checkboxes
            $currentLoops = [];
            $currentLoopString = str_pad(decbin($thisSys->current_loop_dvrs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopString); $i++) {
                if ($currentLoopString[$i] == 1) {
                    $currentLoops[$i+1] = 1;
                }
            }

            // CURRENT LOOP INPUTS: Get vector info to check correct checkboxes
            $currentLoopsInput = [];
            $currentLoopStringInput = str_pad(decbin($thisSys->current_loop_inputs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopStringInput); $i++) {
                if ($currentLoopStringInput[$i] == 1) {
                    $currentLoopsInput[$i+1] = 1;
                }
            }

            // ACTIVE INPUTS: Get vector info and create array reflecting 4-board setup with 8 positions on each board
            $activeInputs = [];
            $activeInputString = str_pad(decbin($thisSys->active_inputs1), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs2), 8, '0', STR_PAD_LEFT) .
                                str_pad(decbin($thisSys->active_inputs3), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs4), 8, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeInputString); $i++) {
                if ($activeInputString[$i] == 1) {
                    $activeInputs[$board][$position] = 1;
                }
                $position++;
                if ($position == 9) {
                    $board++;
                    $position = 1;
                }
            }

            // Grab devices data and create devices.txt in /storage
            $final = DB::table('devices')->where('system_id', $thisSys->id)->get();

            $file = fopen(storage_path().'/devices-'.$thisSys->id.'.txt', 'w');
            foreach ($final as $device) {
                foreach ($device as $key => $value) {
                    if ($key != "status") { // Don't write an extra pipe at the end of the line
                        fwrite($file, $key.":".$value."|");
                    } else {
                        fwrite($file, $key.":".$value);
                    }
                }
                fwrite($file, "\n");
            }
            fclose($file);
            $latest_sw_version = System::select('systems.software_version')
                ->orderBy('software_version', 'DESC')
                ->get()
                ->first();
            
            $confirm = Device::where('system_id', $thisSys->id)->orderBy('id', 'desc')->first();

            return view('buildings.config.editsystem', ['thisBldg' => $thisBldg])
                ->with('systemsData', $systems)
                ->with('devices', $devices)
                ->with('thisSystem', $thisSys)
                ->with('activeRelays', $activeRelays)
                ->with('currentLoops', $currentLoops)
                ->with('currentLoopsInput', $currentLoopsInput)
                ->with('activeInputs', $activeInputs)
                ->with('confirm', $confirm)
                ->with('reset_log', $reset_log)
                ->with('latest_sw_version', $latest_sw_version->software_version);
        } /*--------------------------------------------------------------------------
		| Add Mapping button
		|-------------------------------------------------------------------------*/
        else if (isset($input['AlgMap'])) {
            unset($input['AlgMap']);
            //For each of the entries (Device:DeviceRecnum:AlgoNum):

            // for each device retrieve from db, update fields, and save
            foreach ($input as $key => $value) {
                // Retrieve device number
                $temp = explode(":", $key);
                $deviceNum = $temp[1];
                // Get list of inputs assigned to each algorithm
                $inputlist = implode(",", $value['AlgInputs']);

                //Select statement to see if this device-alg mapping is already in mapping_output table
                $test = DB::table('mapping_output')->where('system_id', $thisSys->id)->where('device_id', $deviceNum)->get();

                //If mapping for that device is already in it (alg->output relation is 1 to 1), update that mapping in particular
                //If not, insert it into table

                if ($test) {
                    DB::table('mapping_output')
                        ->where('system_id', $thisSys->id)
                        ->where('device_id', $deviceNum)
                        ->update(['algorithm_id' => $value['algorithm'], 'input_id' => $inputlist]);
                } else {
                    $mapoutput = new MappingOutput;
                    $mapoutput->system_id = $thisSys->id;
                    $mapoutput->device_id = $deviceNum;
                    $mapoutput->input_id = $inputlist;
                    $mapoutput->algorithm_id = $value['algorithm'];
                    $mapoutput->save();
                }
            }

            // WIRED RELAYS: Get vector info and create multidimensional array reflecting 4-board setup with 4 positions on each board
            $activeRelays = [];
            $activeRelayString = str_pad(decbin($thisSys->active_relays), 16, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeRelayString); $i++) {
                if ($activeRelayString[$i] == 1) {
                    $activeRelays[$board][$position] = 1;
                }
                $position++;
                if ($position == 5) {
                    $board++;
                    $position = 1;
                }
            }
            // CURRENT LOOPS: Get vector info to check correct checkboxes
            $currentLoops = [];
            $currentLoopString = str_pad(decbin($thisSys->current_loop_dvrs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopString); $i++) {
                if ($currentLoopString[$i] == 1) {
                    $currentLoops[$i+1] = 1;
                }
            }

            // CURRENT LOOP INPUTS: Get vector info to check correct checkboxes
            $currentLoopsInput = [];
            $currentLoopStringInput = str_pad(decbin($thisSys->current_loop_inputs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopStringInput); $i++) {
                if ($currentLoopStringInput[$i] == 1) {
                    $currentLoopsInput[$i+1] = 1;
                }
            }

            // ACTIVE INPUTS: Get vector info and create array reflecting 4-board setup with 8 positions on each board
            $activeInputs = [];
            $activeInputString = str_pad(decbin($thisSys->active_inputs1), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs2), 8, '0', STR_PAD_LEFT) .
                                str_pad(decbin($thisSys->active_inputs3), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs4), 8, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeInputString); $i++) {
                if ($activeInputString[$i] == 1) {
                    $activeInputs[$board][$position] = 1;
                }
                $position++;
                if ($position == 9) {
                    $board++;
                    $position = 1;
                }
            }
            $latest_sw_version = System::select('systems.software_version')
                ->orderBy('software_version', 'DESC')
                ->get()
                ->first();

            $confirm = "updateMap";

            return view('buildings.config.editsystem', ['thisBldg' => $thisBldg])
                ->with('systemsData', $systems)
                ->with('devices', $devices)
                ->with('thisSystem', $thisSys)
                ->with('activeRelays', $activeRelays)
                ->with('currentLoops', $currentLoops)
                ->with('currentLoopsInput', $currentLoopsInput)
                ->with('activeInputs', $activeInputs)
                ->with('confirm', $confirm)
                ->with('input', $input)
                ->with('reset_log', $reset_log)
                ->with('latest_sw_version', $latest_sw_version->software_version);
        } /*--------------------------------------------------------------------------
		| Utility devices - Add Devices after accessing data from DEP API
		|-------------------------------------------------------------------------*/
        else if (isset($input['utilityDevice_login'])) {
                $client     = new GuzzleHttp\Client();
                $startDate  = date('Y-m-d', strtotime('-30 days'));
                $endDate    = date('Y-m-d', strtotime('-5 days'));  // Latest date in API is 4 days old
                $username   = $input['utility_username'];
                $token      = $input['utility_token'];
                $device_created = false;
                $dev_name_index = 0;
                $timezone = "America/New_York";
                date_default_timezone_set($timezone);
                $date = date_create();
                $now = time();
                date_timestamp_set($date, $now);
                $created_time = date_format($date, "Y-m-d H:i:s");
                // AMR DATA API CALL - Water api
            try {
                $res = $client->request('GET', "https://a826-web01.nyc.gov/AMRDataAPI/user/consumption.json?startDate=".$startDate.'&endDate='.$endDate, [
                'auth' => [$username, $token]
                ]);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse()->getStatusCode();
                switch ($response) {
                    case 401:
                    case 403:
                        Session::flash('error', 'Invalid username or token. Try again');
                        break;
                    case 500:
                        Session::flash('error', 'Could not process the request due to an internal server error.');
                        break;
                    default:
                        break;
                }
                return Redirect::route('system.editSystem', [$thisBldg->id, $thisSys->id]);
            }
                Session::flash('success', 'Successfully logged in.');
    
                $waterAPI_data = json_decode($res->getBody(), true);
                
            foreach ($waterAPI_data['Bldgs'] as $building) {
                // GEOCLIENT API - coming from developer.cityofnewyork.us
                $address    = $thisBldg->address1;
                $city       = $thisBldg->city;
                $app_id     = "c927624a";
                $app_key    = "aa63b84efeff43a80617d6411c2e6bcd";
                try {
                    $res1 = $client->request('GET', "https://api.cityofnewyork.us/geoclient/v1/place.json?name=".$address."&borough=".$city."&app_id=".$app_id."&app_key=".$app_key);
                } catch (GuzzleHttp\Exception\ClientException $e) {
                    $response = $e->getResponse()->getStatusCode();
                    switch ($response) {
                        case 401:
                        case 403:
                            Session::flash('error', 'Invalid username or token. Try again');
                            break;
                        case 500:
                            Session::flash('error', 'Could not process the request due to an internal server error.');
                            break;
                        default:
                            break;
                    }
                    return Redirect::route('system.editSystem', [$thisBldg->id, $thisSys->id]);
                }
                $geoclientDATA = json_decode($res1->getBody(), true);
                if (array_key_exists('bbl', $geoclientDATA['place'])) {
                    if ($geoclientDATA['place']['bbl'] == $building['BBL']) {
                        foreach ($building['Accts'] as $account) {
                            foreach ($account['Mtrs'] as $meter) {
                                foreach ($meter['MtrRegs'] as $mtrreg) {
                                    //Add new Device
                                    // The reporttime column for all these devices are 2400min = 1 Day 16 hours
                                    // This is useful to add new alarm and update the old alarms
                                    $device_created = true;
                                    $maxID = Device::where('system_id', $sid)
                                                    ->max('id');
                                    $outputDevice = [];
                                    $outputDevice['id']             = $maxID + 1;
                                    $outputDevice['created_at']     = $created_time;
                                    $outputDevice['updated_at']     = $created_time;
                                    $outputDevice['building_id']    = $id;
                                    $outputDevice['system_id']      = $sid;
                                    $outputDevice['device_types_id'] = 46;
                                    $outputDevice['instance']       = 0;
                                    $outputDevice['product_id']     = 'U1';
                                    $outputDevice['device_mode']    = 'api';
                                    $outputDevice['device_io']      = 'input';
                                    $outputDevice['device_function'] = 'Virtual';
                                    $outputDevice['location']       = 0;
                                    $outputDevice['zone']           = 1;
                                    $outputDevice['name']           = '#'.$dev_name_index.' Water Monitor';
                                    $outputDevice['reporttime']     = '5';
                                    $outputDevice['powerlevel']     = 0;
                                    $outputDevice['physical_location'] = 'the cloud';
                                    $outputDevice['comments']       = 'Water Sensor';
                                    $outputDevice['status']         = 1;
                                    $outputDevice['inhibited']      = 0;
                                    $outputDevice['retired']        = 0;
                                    DB::table('devices')->insert($outputDevice);
                                        
                                    // Add new entry in utility table
                                    $data = [];
                                    $data['device_id']  = $outputDevice['id'];
                                    $data['system_id']  = $sid;
                                    $data['user']       = $username;
                                    $data['token']      = $token;
                                    $data['bbl']        = $building['BBL'];
                                    $data['acc_no']     = $account['AcctNo'];
                                    $data['mtr_no']     = $meter['MtrNo'];
                                    $data['reg_id']     = $mtrreg['RegId'];
                                    DB::table('utility')->insert($data);

                                    $dev_name_index++;
                                }
                            }
                        }
                    }
                }
            }
            if ($device_created == false) {
                Session::flash('error', 'Building not found, try using different account.');
            }
                return Redirect::route('system.editSystem', [$thisBldg->id, $thisSys->id]);
        } /*--------------------------------------------------------------------------
		| Alg Inputs button
		|-------------------------------------------------------------------------*/
        else if (isset($input['AlgInput'])) {
            unset($input['AlgInput']);
            // WIRED RELAYS: Get vector info and create multidimensional array reflecting 4-board setup with 4 positions on each board
            $activeRelays = [];
            $activeRelayString = str_pad(decbin($thisSys->active_relays), 16, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeRelayString); $i++) {
                if ($activeRelayString[$i] == 1) {
                    $activeRelays[$board][$position] = 1;
                }
                $position++;
                if ($position == 5) {
                    $board++;
                    $position = 1;
                }
            }
            // CURRENT LOOPS: Get vector info to check correct checkboxes
            $currentLoops = [];
            $currentLoopString = str_pad(decbin($thisSys->current_loop_dvrs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopString); $i++) {
                if ($currentLoopString[$i] == 1) {
                    $currentLoops[$i+1] = 1;
                }
            }

            // CURRENT LOOP INPUTS: Get vector info to check correct checkboxes
            $currentLoopsInput = [];
            $currentLoopStringInput = str_pad(decbin($thisSys->current_loop_inputs), 4, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($currentLoopStringInput); $i++) {
                if ($currentLoopStringInput[$i] == 1) {
                    $currentLoopsInput[$i+1] = 1;
                }
            }

            // ACTIVE INPUTS: Get vector info and create array reflecting 4-board setup with 8 positions on each board
            $activeInputs = [];
            $activeInputString = str_pad(decbin($thisSys->active_inputs1), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs2), 8, '0', STR_PAD_LEFT) .
                                str_pad(decbin($thisSys->active_inputs3), 8, '0', STR_PAD_LEFT) . str_pad(decbin($thisSys->active_inputs4), 8, '0', STR_PAD_LEFT);
            $board = 1;
            $position = 1;
            for ($i = 0; $i < strlen($activeInputString); $i++) {
                if ($activeInputString[$i] == 1) {
                    $activeInputs[$board][$position] = 1;
                }
                $position++;
                if ($position == 9) {
                    $board++;
                    $position = 1;
                }
            }
            $latest_sw_version = System::select('systems.software_version')
                ->orderBy('software_version', 'DESC')
                ->get()
                ->first();

            $confirm = "updateInput";

            return view('buildings.config.editsystem', ['thisBldg' => $thisBldg])
                ->with('systemsData', $systems)
                ->with('devices', $devices)
                ->with('devicetypes', $devicetypes)
                ->with('thisSystem', $thisSys)
                ->with('activeRelays', $activeRelays)
                ->with('currentLoops', $currentLoops)
                ->with('currentLoopsInput', $currentLoopsInput)
                ->with('activeInputs', $activeInputs)
                ->with('confirm', $confirm)
                ->with('input', $input)
                ->with('reset_log', $reset_log)
                ->with('latest_sw_version', $latest_sw_version->software_version);
        } else if (isset($input['ResetSystem'])) {
            SystemLog::info($sid, 'Web Request for reset of system '.$sid, 5);
            try {
                SystemController::ReqSysReset($sid);
            } catch (Exception $e) {
                SystemLog::error($sid, $e->getMessage(), 26);
            }
            return Redirect::route('system.editSystem', [$thisBldg->id, $sid]);
        } else if (isset($input['UndoResetSystem'])) {
            SystemLog::info($sid, 'Web Request for reset of system '.$sid, 5);
            try {
                SystemController::RemoveSysResetReq($sid);
            } catch (Exception $e) {
                SystemLog::error($sid, $e->getMessage(), 26);
            }
            return Redirect::route('system.editSystem', [$thisBldg->id, $sid]);
        } else if (isset($input['SoftwareUpdate'])) {
            SystemLog::info($sid, 'Web Request for software update of system '.$sid, 5);
            try {
                SystemController::ReqSysSoftUpdate($sid);
            } catch (Exception $e) {
                SystemLog::error($sid, $e->getMessage(), 26);
            }
            return Redirect::route('system.editSystem', [$thisBldg->id, $sid]);
        } else if (isset($input['UndoSoftwareUpdate'])) {
            SystemLog::info($sid, 'Web Request for reset of system '.$sid, 5);
            try {
                SystemController::RemoveSysSoftUpdateReq($sid);
            } catch (Exception $e) {
                SystemLog::error($sid, $e->getMessage(), 26);
            }
            return Redirect::route('system.editSystem', [$thisBldg->id, $sid]);
        }
    }

    /**
   * Generate a system configuration file and send it to the remote system
   * @param integer  $id        The remote system's ID
   * @param boolean $forceSend  Allow an override of the downlink check
   */
    public static function DeployConfig($id, $forceSend = false)
    {
        /*Prevent deploy of mapping files for system 499 - "NEW - EMC 20/20"
		  Config file can only be deployed to system via DeployNewConfig()*/
        if ($id != 499) {
            // Grab full config data and create config.txt in /storage
            $final = DB::table('systems')->where('id', $id)->first();

            $content = "";
            foreach ($final as $key => $value) {
                if ($key != "update_flag") {
                    $content .= $key.":".$value."\n";
                }
            }
            /* Add some settings that are the same for all systems */
            $content .= 'web_domain'          .":".config('remote.web_domain')          ."\n";
            $content .= 'ip_request_address'  .":".config('remote.ip_request_address')  ."\n";
            $content .= 'server_receiving_dir'.":".config('remote.server_receiving_dir')."\n";
            $content .= 'server_username'     .":".config('remote.server_username')     ."\n";
            $content .= 'server_domain_name'  .":".config('remote.server_domain_name')  ."\n";

            SystemLog::info($id, 'Generated system configuration file for system #'.$id, 17);
            /*TODO: check for network config, to determine which command files need to be sent*/
            RemoteTask::deployFile($id, '/var/2020_command', 'config.txt', $content, $forceSend);
            RemoteTask::deployFile($id, '/var/2020_command', 'zig_cmd_'.date('U'), 'CONFIG', $forceSend);
            RemoteTask::deployFile($id, '/var/2020_command', 'ino_cmd_'.date('U'), 'CONFIG', $forceSend);
            RemoteTask::deployFile($id, '/var/2020_algorithm/table_updates', 'update.txt', 'config.txt', $forceSend);
        }
    }

    public static function DeployMapping($id)
    {
        // Grab full config data and create config.txt in /storage
        $devices = DB::table('devices')->where('system_id', $id)
            ->where('device_mode', 'wireless')
            ->where('id', '>=', 0)
            ->where('id', '<=', 65534)
            ->where('location', '!=', 0)
            ->where('status', 1)
            ->where('retired', 0)
            ->get();
        $content = "";

        foreach ($devices as $device) {
            $content .= str_pad($device->location, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->status . ' ';
            $content .= str_pad($device->short_address, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->mac_address . ' ';
            $content .= str_pad($device->id, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->product_id;

            $content .= "\n";
        }

        SystemLog::info($id, 'Generated ZigBee mapping file for system #'.$id, 17);

        RemoteTask::deployFile($id, '/var/2020_mapping', 'zig_mapping_'.$id.'.txt', $content);
        RemoteTask::deployFile($id, '/var/2020_command', 'zig_cmd_'.date('U'), 'MAP');
    }

    public static function DeployInovonicsMapping($id)
    {
        $devices = DB::table('devices')->where('system_id', $id)
            ->where('device_mode', 'echostream')
            ->where('id', '>=', 0)
            ->where('id', '<=', 65534)
            ->where('location', '!=', 0)
            ->where('status', 1)
            ->where('retired', 0)
            ->get();
        $content = "";

        foreach ($devices as $device) {
            $content .= str_pad($device->location, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->status . ' ';
            $content .= str_pad($device->short_address, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->mac_address . ' ';
            $content .= str_pad($device->id, 5, '0', STR_PAD_LEFT) . ' ';
            $content .= $device->product_id;

            $content .= "\n";
        }

        SystemLog::info($id, 'Generated Inovonics mapping file for system #'.$id, 17);

        RemoteTask::deployFile($id, '/var/2020_mapping', 'ino_mapping_'.$id.'.txt', $content);
        RemoteTask::deployFile($id, '/var/2020_command', 'ino_cmd_'.date('U'), 'MAP');
    }

    public static function DeployExpansionMapping($id)
    {
        $devices = DB::table('devices')->where('system_id', $id)
            ->join('product_types', 'product_types.product_id', '=', 'devices.product_id')
            ->join('device_types', 'device_types.id', '=', 'product_types.commands')
            ->where('devices.status', 1)
            ->where('devices.retired', 0)
            ->where('device_mode', 'wired')
            ->select('devices.location', 'devices.id AS device_id', 'product_types.commands', 'device_types.gain', 'device_types.intercept')
            ->orderBy('devices.location', 'ASC')
            ->get();
        $filename = 'ex_mapping_'.$id.'.txt';
        $content = "";

        foreach ($devices as $device) {
            $content .= $device->location;
            $content .= ' ';
            $content .= $device->device_id;
            $content .= ' ';
            $content .= $device->commands;
            $content .= ' ';
            $content .= number_format($device->gain, 4);
            $content .= ' ';
            $content .= number_format($device->intercept, 4);

            $content .= "\n";
        }

        SystemLog::info($id, 'Generated expansion mapping file for system #'.$id, 17);

        RemoteTask::deployFile($id, '/var/2020_mapping', "exp_mapping.txt", $content);
        RemoteTask::deployFile($id, '/var/2020_command', 'ex_'.date('U'), 'MAP');
    }


  /**
   * Generate a mapping file for BacNET devices, send it to a remote device, and
   * create a command file to trigger the new mappings.
   * @param integer $id System ID for the target remote device
   */
    public static function DeployBacnetMapping($id)
    {
        $devices = DB::table('devices')->where('system_id', $id)
            ->where('status', 1)
            ->where('retired', 0)
            ->where('device_mode', 'bacnetmstp')
            ->select('devices.id', 'devices.location', 'devices.short_address', 'devices.bacnet_object_type', 'devices.instance', 'devices.reporttime')
            ->orderBy('devices.location', 'ASC')
            ->get();
        $filename = 'bac_mapping_'.$id.'.txt';
        $content = "";

        foreach ($devices as $device) {
            $content .= $device->id;
            $content .= ' ';
            $content .= $device->location;
            $content .= ' ';
            $content .= $device->short_address;
            $content .= ' ';
            $content .= $device->bacnet_object_type;
            $content .= ' ';
            $content .= $device->instance;
            $content .= ' ';
            $content .= ($device->reporttime * 60); /*convert minutes to seconds*/

            $content .= "\n";
        }

        SystemLog::info($id, 'Generated BacNET mapping file for '.count($devices).' devices in system #'.$id, 17);


        RemoteTask::deployFile($id, '/var/2020_mapping', $filename, $content);
        RemoteTask::deployFile($id, '/var/2020_command', 'bac_command_'.date('U'), '127 1');
        /*value of 127 is max-master address, for a given local mstp network; this is the address reserved for the EMC's BACnet polling application*/
        /*The seconds value, '1', is meant to maintain similar command file formatting for the BACnet poller, when it is parsing incoming command files. Currently, this value may be set to '1' or '0'; such a change will not effect the application's ability to recognize a pending mapping file update.*/
    }

    /**
    * Generate a system configuration file for the systems.id corresponding to the systems.net_mac received.
    * This configuration file should be sent to the requesting system, NOT the systems.id corresponding to
    * the systems.net_mac received. This method should allow correlation of phycial systems with their systems.id
    * @param integer  $id        The remote system's ID
    * @param boolean $forceSend  Allow an override of the downlink check
    */
    public static function DeployNewConfig($id, $SysMac, $forceSend = false)
    {
        // Grab full config data and create config.txt in /storage
        $final = DB::table('systems')->where('net_mac', $SysMac)->first();

        $content = "";
        $network_format = 4;
        $final_sysid = $id;
        $num_expansion_boards = 0;
        $hardware_version = 0;
        foreach ($final as $key => $value) {
            /*load $content string with data returned from systems table query*/
            if ($key != "update_flag") {
                $content .= $key.":".$value."\n";
            }
            /*for use with mapping deploys*/
            if ($key == "coordinator_format") {
                $network_format = $value;
            } else if ($key == "id") {
                $final_sysid = $value;
            } else if ($key == "extender_boards") {
                $num_expansion_boards = $value;
            } else if ($key == "hardware_version") {
                $hardware_version = $value;
            }
        }
        /* Add some settings that are the same for all systems */
        $content .= 'web_domain'          .":".config('remote.web_domain')          ."\n";
        $content .= 'ip_request_address'  .":".config('remote.ip_request_address')  ."\n";
        $content .= 'server_receiving_dir'.":".config('remote.server_receiving_dir')."\n";
        $content .= 'server_username'     .":".config('remote.server_username')     ."\n";
        $content .= 'server_domain_name'  .":".config('remote.server_domain_name')  ."\n";

        RemoteTask::deployFile($id, '/var/2020_command', 'config.txt', $content, $forceSend);

        $localPath  = storage_path('network/');
        if (File::exists($localPath)) {
            $sourceNetworkFile = $localPath . 'interfaces-' . $final_sysid;
            echo "looking for " . $sourceNetworkFile . "\n";
            if (File::exists($sourceNetworkFile)) {
                $destinationNetworkFile = storage_path('downlink/storage_' . $final_sysid . '/');
                if (!File::exists($destinationNetworkFile)) {
                    File::makeDirectory($destinationNetworkFile);
                }
                $destinationNetworkFile .= 'etc.network.interfaces';

                File::copy($sourceNetworkFile, $destinationNetworkFile);
            } else {
                echo "interfaces file does not exist\n";
            }
        } else {
            echo "network directory does not exist in storage\n";
        }

        /*Prevent deploy of mapping files for system 499 - "NEW - EMC 20/20"*/
        if ($final_sysid != 499) {
            SystemLog::info($final_sysid, 'Generated system configuration file for system #'.$final_sysid.' and sent file to system #'.$id, 17);
            echo "algorithm update command file and mappings deploy\n";
            RemoteTask::deployFile($final_sysid, '/var/2020_algorithm/table_updates', 'update.txt', 'config.txt', $forceSend);
            AlgorithmController::DeployMapping($final_sysid);
            SetpointMappingController::DeploySetpoints($final_sysid);
            SystemController::DeployLocalParam($final_sysid);

            try {
                SystemController::DeployDeviceMapping($network_format, $final_sysid, false);
            } catch (Exception $e) {
            };
        }
    }

    public static function DeployDeviceMapping($network_format, $SysId, $forceSend = false)
    {
        SystemController::DeployLocalParam($SysId);
        SystemController::DeployExpansionMapping($SysId);
        /*Network Formats (ie systems.coordinator_format)(enumerated):
			1 = BACnet
			2 = EMC Wireless
			3 = BACnet & EMC Wireless
			4 = Inovonics
			5 = BACnet & Inovonics
			6 = EMC Wireless & Inovonics
		*/
        switch ($network_format) {
            case 1: /*BACnet*/
                echo "bacnet mapping deploy\n";
                SystemController::DeployBacnetMapping($SysId);
                break;
            case 2: /*EMC Wireless*/
                echo "emc wireless mapping deploy\n";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'zig_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployMapping($SysId);
                break;
            case 3: /*BACnet & EMC Wireless*/
                echo "emc wireless mapping and bacnet mapping deploy\n";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'zig_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployBacnetMapping($SysId);
                SystemController::DeployMapping($SysId);
                break;
            case 4: /*Inovonics*/
                echo "inovonics mapping deploy\n";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'ino_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployInovonicsMapping($SysId);
                break;
            case 5: /*BACnet & Inovonics*/
                echo "bacnet mapping and inovonics mapping deploy";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'ino_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployBacnetMapping($SysId);
                SystemController::DeployInovonicsMapping($SysId);
                break;
            case 6: /*EMC Wireless & Inovonics*/
                echo "emc wireless mapping and inovonics mapping deploy\n";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'zig_cmd_'.date('U'), 'CONFIG', $forceSend);
                RemoteTask::deployFile($SysId, '/var/2020_command', 'ino_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployMapping($SysId);
                SystemController::DeployInovonicsMapping($SysId);
                break;
            default: /*all networks*/
                echo "All Networks mapping deploy\n";
                RemoteTask::deployFile($SysId, '/var/2020_command', 'zig_cmd_'.date('U'), 'CONFIG', $forceSend);
                RemoteTask::deployFile($SysId, '/var/2020_command', 'ino_cmd_'.date('U'), 'CONFIG', $forceSend);
                SystemController::DeployBacnetMapping($SysId);
                SystemController::DeployMapping($SysId);
                SystemController::DeployInovonicsMapping($SysId);
                break;
        }
    }

    /**
    * Generate the local_param file for use on local systems
    * An up-to-date local param file is integral in allow the system's proper offline fuctioning.
    * @param integer  $id        The remote system's ID
    * @param boolean $forceSend  Allow an override of the downlink check
    */
    public static function DeployLocalParam($id, $forceSend = false)
    {
        $dev_details = DB::table('devices')->where('devices.system_id', $id)
            ->join('product_types', 'product_types.product_id', '=', 'devices.product_id')
            ->leftJoin('zone_labels', function ($join) {
                $join->on('zone_labels.zone', '=', 'devices.zone')
                ->on('zone_labels.system_id', '=', 'devices.system_id');
            })
            ->where('devices.status', 1)
            ->where('devices.retired', 0)
            ->select('devices.name', 'devices.id AS device_id', 'product_types.commands', 'zone_labels.zonename')
            ->get();

        $devicetypes = DeviceType::all();

        $local_devs = [];

        foreach ($dev_details as $dd) {
            $boom = explode(',', $dd->commands);
            foreach ($boom as $boomlet) {
                foreach ($devicetypes as $dt) {
                    if ($dt->id == $boomlet) {
                        (array)$local_devs[] = [
                        'id'            => $dd->device_id,
                        'device_type'   => $boomlet,
                        'function'      => $dt->function,
                        'units'         => $dt->units,
                        'dev_name'      => $dd->name,
                        'zone_name'     => $dd->zonename
                        ];
                    }
                }
            }
        }

        $content = "";

        foreach ($local_devs as $ld) {
            $content .= '<';
            $content .= $ld['id'];
            $content .= '><';
            $content .= $ld['device_type'];
            $content .= '><';
            $content .= $ld['function'];
            $content .= '><';
            $content .= $ld['units'];
            $content .= '><';
            $content .= $ld['dev_name'];
            $content .= '><';
            $content .= $ld['zone_name'];
            $content .= '>';
            $content .= "\n";
        }

        SystemLog::info($id, 'Local Parameter file for system #'.$id, 17);

        RemoteTask::deployFile($id, '/var/2020_command', "local_param.txt", $content);
        RemoteTask::deployFile($id, '/var/2020_command', 'lp_'.date('U'), 'MAP');

        $alarm_descriptions = DB::table('alarm_codes')->get();

        $content = "";

        foreach ($alarm_descriptions as $ad) {
            $content .= '<';
            $content .= $ad->id;
            $content .= '><';
            $content .= $ad->description;
            $content .= '><';
            $content .= $ad->severity;
            $content .= '>';
            $content .= "\n";
        }
        SystemLog::info($id, 'Alarm Description file for system #'.$id, 17);

        RemoteTask::deployFile($id, '/var/2020_command', "alarm_desc.txt", $content);
    }

    public static function DeployNetworkInterfaces($sid, $table_entry_id = 0, $auto = true)
    {
        if ($auto == true) {
            $content = "auto lo\niface lo inet loopback\n\nauto eth0\niface eth0 inet dhcp\n";
        } else {
            $thisNetwork = NetworkSettings::find($table_entry_id);
            $content = "auto lo\niface lo inet loopback\n\n#auto eth0\n#iface eth0 inet dhcp\n\nauto eth0\niface eth0 inet static\n";
            $content .= "   address ".$thisNetwork->static_ip."\n";
            $content .= "   netmask ".$thisNetwork->netmask."\n";
            $content .= "   gateway ".$thisNetwork->gateway."\n";
            $content .= "   dns-nameserver ".$thisNetwork->dns_nameserver."\n";
        }

        SystemLog::info($sid, 'Deploy Network Interfaces File', 17);
        RemoteTask::deployFile($sid, '/etc/network', "interfaces", $content);
    }

    /**************************************************************************************************************
	*	FUNCTIONS FOR editsystem COMMANDS
	************************************** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    public static function ReqSysReset($sid)
    {
        $content = "kill -15 $(pgrep light)"; //Systems watchdog will be triggered if local application lightdm is found not running (such as when it's been killed)

        RemoteTask::deployFile($sid, '/var/2020_command', "xx_forcewatchdog.emc", $content);
    }
    public static function RemoveSysResetReq($sid)
    {
        try {
            unlink(storage_path('downlink/storage_' . $sid . '/var.2020_command.xx_forcewatchdog.emc'));
        } catch (Exception $e) {
            SystemLog::error($thisSys->id, $e, 26);
        }
    }
    public static function ReqSysSoftUpdate($sid)
    {
        $content = "cd /var/2020_system_main/scripts/ && ./git-update.sh"; //Request the system to run the local git update script

        RemoteTask::deployFile($sid, '/var/2020_command', "xx_gitupdate.emc", $content);
    }
    public static function RemoveSysSoftUpdateReq($sid)
    {
        try {
            unlink(storage_path('downlink/storage_' . $sid . '/var.2020_command.xx_gitupdate.emc'));
        } catch (Exception $e) {
            SystemLog::error($thisSys->id, $e, 26);
        }
    }
    /*---------------------------------------------- END editsystem COMMANDS ----------------------------------------------*/
}
