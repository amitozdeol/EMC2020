<?php

class SetpointMappingController extends \BaseController
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index($id, $sid)
    {
        /*timing, for testing*/
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;


        $thisBldg = Building::find($id);
        $thisSys = System::find($sid);
        //Use for getting all the items in sidebar
        $items = DashboardItem::where('system_id', $sid)
                          ->where('parent_id', 0)
                          ->whereRaw("label not LIKE '%EMC Hardware System%'")
                          ->orderBy('order')
                          ->get();
        /**
    * If there are no top level menu items then use the default
    * (i.e. system_id == 0)
    */
        if (!count($items)) {
            $items = DashboardItem::where('system_id', 0)
                            ->where('parent_id', 0)
                            ->whereRaw("label not LIKE '%EMC Hardware System%'")
                            ->orderBy('order')
                            ->get();
        }
        $tempFormat = $thisSys->temperature_format;

        $devices = Device::where('system_id', $sid)
        ->where('device_io', 'input')
        ->where('retired', 0)
        ->orderby('name')
        ->orderBy('zone')
        ->get();

        $setbacks = DeviceSetback::where('device_setback.system_id', $sid)
        ->join('devices', function ($join) {
            $join->on('devices.id', '=', 'device_setback.device_id');
            $join->on('devices.system_id', '=', 'device_setback.system_id');
        })
        ->select('device_setback.*', 'devices.zone')
        ->orderBy('device_id')
        ->orderBy('index', 'ASC')
        ->orderBy('starttime')
        ->get();

        $devTypes = DeviceType::where('IO', 'Input')
        ->orderby('function')
        ->get();

        $zoneNames = Zone::where('system_id', $sid)->get();
        $zoneNameArray = array();
        foreach ($zoneNames as $zone) {
            $zoneNameArray[$zone->zone] = $zone->zonename;
        }

        $typesArray = array();
        foreach ($devTypes as $type) {
            $typesArray[$type->command] = $type->function;
        }
    
        /********************************************************************************/
        $remapRequired = "NO";
        $missingSetpoint = false;
        /*Get input devices, with relevant product info.*/
        $products = Device::where('devices.system_id', $sid)
        ->where('devices.retired', 0)
        ->where('devices.device_io', 'input')
        ->where('zone', '>', 0)
        ->where('id', '>', 0)
        ->where('status', 1)
        ->join('product_types', 'product_types.product_id', '=', 'devices.product_id')
        ->select('product_types.commands AS product_commands', 'devices.name AS device_name', 'devices.created_at AS device_created_at', 'devices.product_id AS product_id', 'devices.id AS device_id', 'devices.zone AS zone', 'devices.device_mode AS device_mode')
        ->get();

        $devIdList = array();
        foreach ($products as $p) {
            $devIdList[] = $p->device_id;
        }
        $devIdList = array_unique($devIdList, SORT_NUMERIC);

        /*Get the setpoints related to this system's input devices.*/
        $setpointsX = DeviceSetpoints::where('system_id', $sid)
        ->wherein('device_id', $devIdList)
        ->get();

        /*Create a list of command -functions for this system's inputs, based on the system's input devices' product types*/
        $systemCommands = array();
        foreach ($products as $p) {
            $commands = explode(',', $p->product_commands);
            foreach ($commands as $c) {
                $systemCommands[] = $c;
            }
        }
        $systemCommands = array_unique($systemCommands, SORT_NUMERIC);

        /*Get entries for this system's command-functions*/
        $functionTypes = DeviceType::wherein('command', $systemCommands)->get();

        $sigfigs = 2;
        $megarray = array();
        $functionsArray = array();
        $zonesArray = array();
        $functionZone = array();
        $remapDevices = array();
        $device_setbacks = array();
        $bluntArray = array();
    
        /*BUILD MEGARRAY AND DEVICE_SETBACKS ARRAYS*/
        foreach ($products as $p) {                                 //All of the devices associated with this system
            foreach ($functionTypes as $ft) {                       //All of the command types/functions associated with this system
                switch ($ft->function) {
                    case 'Temperature':
                        $sigfigs = 1;
                        break;
                    case 'Pressure Differential':
                        $sigfigs = 4;
                        break;
                }
                foreach (explode(',', $p->product_commands) as $c) {    //the commands for this device
                    if ($ft->command == $c) {
                        $missingSetpoint = true;
                        /*Check for missing setpoints, and load active setpoints into megarray*/
                        foreach ($setpointsX as $sp) {                                //All of the setpoints for this system's input devices
                            if (($sp->device_id == $p->device_id) && ($sp->command == $c)) {  //match the setpoint with the device & function
                                $missingSetpoint = false;
                                $bluntArray[] = $sp->device_id."==".$p->device_id." && ".$sp->command."==".$c." && ".$ft->command."==".$c;
                                $megarray[$ft->function][$p->zone][$p->device_id][$c] = [
                                'device_name' => ($p->device_name == '')?"Device-".$p->device_id:$p->device_name,
                                'device_created' => $p->created_at,
                                'product_id' => $p->product_id,
                                'device_mode' => $p->device_mode,
                                'zone_number' => $p->zone,
                                'setpoint' => round(convert_to_system($thisSys->temperature_format, $ft->function, $sp->setpoint), $sigfigs),
                                'alarm_high' => round(convert_to_system($thisSys->temperature_format, $ft->function, $sp->alarm_high), $sigfigs),
                                'alarm_low' => round(convert_to_system($thisSys->temperature_format, $ft->function, $sp->alarm_low), $sigfigs),
                                'hysteresis' => $sp->hysteresis,
                                'priority_alarm' => $sp->priority_alarms,
                                'setpoint_id' => $sp->id,
                                'command' => $sp->command
                                ];
                                $functionsArray[] = $ft->function;
                                $zonesArray[$p->zone] = $p->zone;
                                $functionZone[$ft->function][$p->zone] = true;
                            }
                        }
                        if ($missingSetpoint === true) {
                            $remapDevices[] = $p->device_name;
                        }
                        /*Add to device setbacks array*/
                        foreach ($setbacks as $sb) {
                            if (($sb->command == $c) && ($sb->device_id == $p->device_id)) {
                                $device_setbacks[] = [
                                  'device_name' => $p->device_name,
                                  'device_id' => $p->device_id,
                                  'command' => $sb->command,
                                  'zone' => $sb->zone,
                                  'function'=> $ft->function,
                                  'units' => $ft->units,
                                  'setback_index' => $sb->index
                                ];
                            }
                        }
                    }
                }
                if ($missingSetpoint === true) {
                    $remapRequired = "YES";
                }
            }
        }
        $functionsArray = array_unique($functionsArray, SORT_STRING);
        $zonesArray = array_unique($zonesArray, SORT_STRING);
        $commandFunctions = array();
        $commandUnits = array();
        foreach ($functionTypes as $ft) {
            $commandFunctions[$ft->function] = $ft->command;
      
            $commandUnits[$ft->function] = ($ft->units == "-")?" ":$ft->units;
            if (($ft->function == 'Temperature')) {
                if ($thisSys->temperature_format == "F") {
                    $commandUnits[$ft->function] = "&deg;F";
                } else {
                    $commandUnits[$ft->function] = "&deg;C";
                }
            }
        }
        if (isset($device_setbacks) === false) {
            $device_setbacks[] = ['device_name' => 0,'device_id' => 0];
        }

        /********************************************************************************/

        return view::make('setpoints.list')
        ->with('thisBldg', $thisBldg)
        ->with('thisSystem', $thisSys)
        ->with('tempFormat', $tempFormat)
        ->with('zoneNameArray', $zoneNameArray)
        ->with('setbacks', $setbacks)
        ->with('remapRequired', $remapRequired)
        ->with('remapDevices', $remapDevices)
        ->with('sid', $sid)
        ->with('back_set_devs', $device_setbacks)
        ->with('start', $start)
        ->with('megarray', $megarray)
        ->with('functionsArray', $functionsArray)
        ->with('zonesArray', $zonesArray)
        ->with('devIdList', $devIdList)
        ->with('bluntArray', $bluntArray)
        ->with('systemCommands', $systemCommands)
        ->with('commandFunctions', $commandFunctions)
        ->with('commandUnits', $commandUnits)
        ->with('functionZone', $functionZone)
        ->with('items', $items);
    }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
    public function create()
    {
       //
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store()
    {
       //
    }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function show($id)
    {
      //
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($id)
    {
        //
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function update($bid, $sid, $sensor_id)
    {
        $GLOBALS['session_error'] = "";
        $error_start = '<big><big>&bull; ERROR: ';
        $error_end = ' Not Set. <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Try Again.</big></big><br>';
        $newGlobalRecnums = array();
        $newZoneRecnums = array();
        $newSensorRecnums = array();
        $updateData = Input::all();
        Log::info($updateData);
        $thisSys = System::find($sid);
        $tempFormat = $thisSys->temperature_format;
        $command    = -1*$sensor_id;
        // $thisBldg = Building::Find($bid);
        $sensors = DeviceSetpoints::where('system_id', $sid)->get();
        $setbacks = DeviceSetback::where('system_id', $sid)->get();
        $devTypes = DeviceType::where('IO', 'Input')->get();
      
        $typesArray = array();
        foreach ($devTypes as $type) {
            $typesArray[$type->command] = $type->function;
        }
        if ($sensor_id < 0) {
          /*CHECK GLOBAL AND ZONAL INPUTS*/
            $curr_function = str_replace(' ', '', $typesArray[$command]);
            $globalButton = 'saveGlobal' . $curr_function;
            $zonalButton='';
            if (array_key_exists('zone_id', $updateData)) {
                $zoneButton = 'saveZonal' . $curr_function.$updateData['zone_id'];
            }
        
          // GLOBAL
            if (array_key_exists($globalButton, $updateData)) {
                /*UPDATE SETPOINTS BY FUNCTION*/
                $globalSetpoint = Input::get('global_'.$curr_function);
                if ($globalSetpoint != null) {
                    foreach ($sensors as $dev) {
                        if (str_replace(' ', '', $typesArray[$dev->command]) == $curr_function) {
                            $dev->setpoint = value_convert($tempFormat, $curr_function, $globalSetpoint);
                            if (isset($dev->setpoint) && $dev->setpoint != '') {
                                $dev->save();//save setpoint to database.
                            }
                        }
                    }
                }
          
                $indexString = "globalStartTimeForm-".$command."-1-0";
                if (array_key_exists($indexString, $updateData)) {
                    foreach ($sensors as $sensor) {
                        $setbackIndex = 1;
                        $setbacksFound = 0;
                        /*UPDATE AND CLEAR EXISTING SETBACKS*/
                        foreach ($setbacks as $setback) {
                            if ($setback->device_id == $sensor->device_id && $setback->command == $sensor->command && $typesArray[$setback->command] == $curr_function) {
                                $startTime = "globalStartTimeForm-".$command."-".$setbackIndex."-0";
                                $setbacksFound++;
                                if (array_key_exists($startTime, $updateData) && $typesArray[$sensor->command] == $curr_function) {
                                    // $startPeriod = "globalStartPeriodForm-".$command."-".$setbackIndex."-0";
                                    $stopTime = "globalStopTimeForm-".$command."-".$setbackIndex."-0";
                                    // $stopPeriod = "globalStopPeriodForm-".$command."-".$setbackIndex."-0";
                                    $value = "globalValueForm-".$command."-".$setbackIndex."-0";
                                    $weekday = "globalWeekdayForm-".$command."-".$setbackIndex."-0";
                                    if ($updateData[$startTime]==null) {
                                        Session::flash('error', $error_start.'Global Setback Start Time'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    } else if ($updateData[$stopTime]==null) {
                                        Session::flash('error', $error_start.'Global Setback Stop Time'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    } else if ($updateData[$value]==null) {
                                        Session::flash('error', $error_start.'Global Setback Setpoint'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    }
                                    $int_start = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                                    $setback->starttime = $updateData[$weekday].":".$int_start;
                                    $int_stop = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                                    $setback->stoptime = $updateData[$weekday].":".$int_stop;
                                    $setback->setback = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                                    $setback->index = $setbacksFound;
                                    $setback->save();
                                    $newGlobalRecnums[] = $setback->recnum;
                                } else {
                                    /*REMOVE ENTRIES BEYOND THOSE BEING ADDED*/
                                    $setback->delete();
                                }
                                $setbackIndex++;
                            }
                        }
                        /*ADD SETBACKS BEYOND EXISTING ENTRIES*/
                        for ($remainingSetbacks = ($setbacksFound + 1); $remainingSetbacks < 17; $remainingSetbacks++) {
                            $startTime = "globalStartTimeForm-".$command."-".$remainingSetbacks."-0";
                            if (array_key_exists($startTime, $updateData) && $typesArray[$sensor->command] == $curr_function) {
                                $currentSetback = new DeviceSetback();
                                $currentSetback->created_at = date("Y-m-d H:i:s");
                                $currentSetback->system_id = (int)$sid;
                                $currentSetback->device_id = $sensor->device_id;
                                $currentSetback->command = $sensor->command;
                                $currentSetback->index = $remainingSetbacks;
                                $stopTime = "globalStopTimeForm-".$command."-".$remainingSetbacks."-0";
                                $value = "globalValueForm-".$command."-".$remainingSetbacks."-0";
                                $weekday = "globalWeekdayForm-".$command."-".$remainingSetbacks."-0";
                                if ($updateData[$startTime]==null) {
                                    Session::flash('error', $error_start.'Global Setback Start Time'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                } else if ($updateData[$stopTime]==null) {
                                    Session::flash('error', $error_start.'Global Setback Stop Time'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                } else if ($updateData[$value]==null) {
                                    Session::flash('error', $error_start.'Global Setback Setpoint'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                }
                                $int_start = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                                $currentSetback->starttime = $updateData[$weekday].":".$int_start;
                                $int_stop = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                                $currentSetback->stoptime = $updateData[$weekday].":".$int_stop;
                                $currentSetback->setback = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                                $currentSetback->index = $remainingSetbacks;
                                $currentSetback->save();
                                $newGlobalRecnums[] = $currentSetback->recnum;
                            } else {
                                break;
                            }
                        }
                    }
                }
                if (isset($newGlobalRecnums)) {
                    setback_conflict_resolution($newGlobalRecnums, $single = false);
                }
            } // ZONAL
            else if (array_key_exists($zoneButton, $updateData)) {
                /*UPDATE SETPOINTS BY ZONE*/
                $devices = Device::where('system_id', $sid)->get();
          
                $deviceZones = array();
                foreach ($devices as $device) {
                    $deviceZones[$device->id] = $device->zone;
                }
                $zonalSetpoint = (int)Input::get('zonal_'.$curr_function.$updateData['zone_id']);
                if ($zonalSetpoint != null) {
                    foreach ($sensors as $dev) {
                        if (isset($deviceZones[$dev->device_id])) {
                            if ((str_replace(' ', '', $typesArray[$dev->command]) == $curr_function) && ($deviceZones[$dev->device_id] == $updateData['zone_id'])) {
                                $dev->setpoint = value_convert($tempFormat, $curr_function, $zonalSetpoint);
                                if (isset($dev->setpoint)) {
                                    $dev->save();//save setpoint to database.
                                }
                            }
                        }
                    }
                }
                $zonalString = "zonal";
                if ($curr_function == "Temperature") {
                    $zonalString = "zonal".$updateData['zone_id'];
                }
                $indexString = "zonalStartTimeForm-".$command."-1-".$updateData['zone_id'];
                if (array_key_exists($indexString, $updateData)) {
                    foreach ($sensors as $sensor) {
                        if (isset($deviceZones[$sensor->device_id])) {
                            $setbackIndex = 1;
                            $setbacksFound = 0;
                            /*UPDATE AND CLEAR EXISTING SETBACKS*/
                            foreach ($setbacks as $setback) {
                                if (($setback->device_id == $sensor->device_id) && ($setback->command == $sensor->command) && ($typesArray[$setback->command] == $typesArray[$command]) && ($deviceZones[$sensor->device_id] == $updateData['zone_id'])) {
                                    $startTime = "zonalStartTimeForm-".$command."-".$setbackIndex."-".$updateData['zone_id'];
                                    $setbacksFound++;
                                    if (array_key_exists($startTime, $updateData) && $typesArray[$sensor->command] == $typesArray[$command]) {
                                        $stopTime     = "zonalStopTimeForm-".$command."-".$setbackIndex."-".$updateData['zone_id'];
                                        $value        = "zonalValueForm-".$command."-".$setbackIndex."-".$updateData['zone_id'];
                                        $weekday      = "zonalWeekdayForm-".$command."-".$setbackIndex."-".$updateData['zone_id'];
                                        if ($updateData[$startTime]==null) {
                                            Session::flash('error', $error_start.'Zonal Setback Start Time'.$error_end);
                                            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                        } else if ($updateData[$stopTime]==null) {
                                            Session::flash('error', $error_start.'Zonal Setback Stop Time'.$error_end);
                                            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                        } else if ($updateData[$value]==null) {
                                            Session::flash('error', $error_start.'Zonal Setback Setpoint'.$error_end);
                                            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                        }
                                        $int_start  = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                                        $int_stop   = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                                        $setback->starttime = $updateData[$weekday].":".$int_start;
                                        $setback->stoptime  = $updateData[$weekday].":".$int_stop;
                                        $setback->setback   = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                                        $setback->index     = $setbacksFound;
                                        $setback->save();
                                        $newZoneRecnums[] = $setback->recnum;
                                    } else {
                                        /*REMOVE ENTRIES BEYOND THOSE BEING ADDED*/
                                        $setback->delete();
                                    }
                                    $setbackIndex++;
                                }
                            }
                            /*ADD SETBACKS BEYOND EXISTING ENTRIES*/
                            for ($remainingSetbacks = ($setbacksFound + 1); $remainingSetbacks < 17; $remainingSetbacks++) {
                                $startTime = "zonalStartTimeForm-".$command."-".$remainingSetbacks."-".$updateData['zone_id'];
                                if (array_key_exists($startTime, $updateData) && $typesArray[$sensor->command] == $typesArray[$command] && $deviceZones[$sensor->device_id] == $updateData['zone_id']) {
                                    $currentSetback = new DeviceSetback();
                                    $currentSetback->created_at = date("Y-m-d H:i:s");
                                    $currentSetback->system_id  = (int)$sid;
                                    $currentSetback->device_id  = $sensor->device_id;
                                    $currentSetback->command    = $sensor->command;
                                    $stopTime     = "zonalStopTimeForm-".$command."-".$remainingSetbacks."-".$updateData['zone_id'];
                                    $value        = "zonalValueForm-".$command."-".$remainingSetbacks."-".$updateData['zone_id'];
                                    $weekday      = "zonalWeekdayForm-".$command."-".$remainingSetbacks."-".$updateData['zone_id'];
                                    if ($updateData[$startTime]==null) {
                                        Session::flash('error', $error_start.'Zonal Setback Start Time'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    } else if ($updateData[$stopTime]==null) {
                                        Session::flash('error', $error_start.'Zonal Setback Stop Time'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    } else if ($updateData[$value]==null) {
                                        Session::flash('error', $error_start.'Zonal Setback Setpoint'.$error_end);
                                        return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                    }
                                    $int_start  = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                                    $int_stop   = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                                    $currentSetback->starttime  = $updateData[$weekday].":".$int_start;
                                    $currentSetback->stoptime   = $updateData[$weekday].":".$int_stop;
                                    $currentSetback->setback    = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                                    $currentSetback->index      = $remainingSetbacks;
                                    $currentSetback->save();
                                    $newZoneRecnums[] = $currentSetback->recnum;
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
                log::info($indexString);
                log::info($newZoneRecnums);
                if (isset($newZoneRecnums)) {
                    setback_conflict_resolution($newZoneRecnums, $single = false);
                }
            }
            $function = $type->function;
            try {
                SetpointMappingController::DeploySetpoints($sid);
            } catch (Exception $e) {
                log::info($e);
            }
            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
        } else {
            /*INDIVIDUAL SENSORS*/
            $deviceTypes = DeviceType::where('IO', 'Input')
                      ->groupby('function')
                      ->orderby('function')
                      ->get();
            $hyster_array = array();
            foreach ($deviceTypes as $type) {
                $hyster_array[$type->command] = $type->hysteresis;
            }
            foreach ($sensors as $sensor) {
                $id = $sensor->device_id;
                $deviceCommand = $sensor->command;
                $indexString = "sbStartTimeForm-".$id."-".$deviceCommand."-0";
                if (array_key_exists($indexString, $updateData)) {
                    $setbackIndex = 1;
                    $setbacksFound = 0;
                    // Update the current setback
                    foreach ($setbacks as $setback) {
                        if ($setback->device_id == $sensor->device_id && $setback->command == $sensor->command) {
                            $currentSetback = DeviceSetback::where('recnum', $setback->recnum)->first();
                            $startTime = "sbStartTimeForm-".$id."-".$deviceCommand."-".$setbackIndex;
                            $setbacksFound++;
                                 
                            if (array_key_exists($startTime, $updateData)) {
                                $stopTime = "sbStopTimeForm-".$id."-".$deviceCommand."-".$setbackIndex;
                                $value = "sbValueForm-".$id."-".$deviceCommand."-".$setbackIndex;
                                $weekday = "sbWeekdayForm-".$id."-".$deviceCommand."-".$setbackIndex;
                                if ($updateData[$startTime]==null) {
                                    Session::flash('error', $error_start.'Setback Start Time'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                } else if ($updateData[$stopTime]==null) {
                                    Session::flash('error', $error_start.'Setback Stop Time'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                } else if ($updateData[$value]==null) {
                                    Session::flash('error', $error_start.'Setback Setpoint'.$error_end);
                                    return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                                }
                                $int_start = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                                $currentSetback->starttime = $updateData[$weekday].":".$int_start;
                                $int_stop = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                                $currentSetback->stoptime = $updateData[$weekday].":".$int_stop;
                                $currentSetback->setback = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                                $currentSetback->index = $setbacksFound;
                                $currentSetback->save();
                                $newSensorRecnums[] = $currentSetback->recnum;
                            } else {
                                $currentSetback->delete();
                            }
                              $setbackIndex++;
                        }
                    }
                    // Add new setback
                    for ($remainingSetbacks = ($setbacksFound + 1); $remainingSetbacks < 17; $remainingSetbacks++) {
                        $startTime = "sbStartTimeForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                        if (array_key_exists($startTime, $updateData)) {
                            $currentSetback = new DeviceSetback();
                            $currentSetback->created_at = date("Y-m-d H:i:s");
                            $currentSetback->system_id = (int)$sid;
                            $currentSetback->device_id = $id;
                            $currentSetback->command = $deviceCommand;
                            $currentSetback->index = $remainingSetbacks;
                            $startPeriod = "sbStartPeriodForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                            $stopTime = "sbStopTimeForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                            $stopPeriod = "sbStopPeriodForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                            $value = "sbValueForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                            $weekday = "sbWeekdayForm-".$id."-".$deviceCommand."-".$remainingSetbacks;
                            if ($updateData[$startTime]==null) {
                                Session::flash('error', $error_start.'Setback Start Time'.$error_end);
                                return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                            } else if ($updateData[$stopTime]==null) {
                                Session::flash('error', $error_start.'Setback Stop Time'.$error_end);
                                return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                            } else if ($updateData[$value]==null) {
                                Session::flash('error', $error_start.'Setback Setpoint'.$error_end);
                                return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
                            }
                            $int_start = setback_time_form_parse($updateData[$startTime], $sid, $bid);
                            $currentSetback->starttime = $updateData[$weekday].":".$int_start;
                            $int_stop = setback_time_form_parse($updateData[$stopTime], $sid, $bid);
                            $currentSetback->stoptime = $updateData[$weekday].":".$int_stop;
                            $currentSetback->setback = value_convert($tempFormat, $typesArray[$sensor->command], (float)$updateData[$value]);
                            $currentSetback->index = $remainingSetbacks;
                  
                            $currentSetback->save();
                            $newSensorRecnums[] = $currentSetback->recnum;
                        } else {
                            break;
                        }
                    }
                }
            }
            log::info($newSensorRecnums);
            if (isset($newSensorRecnums)) {
                setback_conflict_resolution($newSensorRecnums, $single = true);
            }
  
            $sensor = DeviceSetpoints::Find($sensor_id);
            $sensor->setpoint = value_convert($tempFormat, $typesArray[$sensor->command], Input::get('setpoint'));
            $sensor->alarm_high = value_convert($tempFormat, $typesArray[$sensor->command], Input::get('alarm_high'));
            $sensor->alarm_low = value_convert($tempFormat, $typesArray[$sensor->command], Input::get('alarm_low'));
  
            if ($sensor->hysteresis == null) {
                if (isset($hyster_array[$sensor->command])) {
                    $sensor->hysteresis = $hyster_array[$sensor->command];
                }
            }
            if (array_key_exists('priority_alarms', $updateData)) {
                $sensor->priority_alarms = 1;
            } else {
                $sensor->priority_alarms = 0;
            }
            if (isset($sensor->setpoint)) {
                $sensor->save();
          
                $duplicates = DeviceSetpoints::where('system_id', $sid)
                ->where('device_id', $sensor->device_id)
                ->where('command', $sensor->command)
                ->where('id', '!=', $sensor_id)
                ->get();
  
                foreach ($duplicates as $duplicate) {
                    $duplicate->delete();
                }
            }
            try {
                SetpointMappingController::DeploySetpoints($sid);
            } catch (Exception $e) {
            }
            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
        }
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($id)
    {
       //
    }

 /**
   * Remap setpoints for specified device.
   * @param int    $id The building to to remap
   * @param int    $sid The system to remap
   */
    public static function mapDevice($bid, $sid, $id)
    {
        $thisBldg = Building::find($bid);
        $thisSys = System::find($sid);
        $deviceTypes = DeviceType::all();

        $device = Device::where('system_id', $sid)
        ->where('id', $id)
        ->where('device_io', 'input')
        ->where('product_id', '!=', '000')
        ->where('product_id', '!=', 'direct')
        ->where('retired', '!=', 1)
        ->orderby('updated_at', 'ASC')
        ->first();

        $type_array = array();

        foreach ($deviceTypes as $type) {// Create an array of all device types.
            $type_array[$type->command] = $type;
        }

        if (!empty($device)) {
            $product = ProductType::where('product_id', $device->product_id)->first();
            $commands = explode(',', $product->commands);
            foreach ($commands as $command) {
                $setpoint = new DeviceSetpoints();
                $setpoint->device_id = $id;
                $setpoint->system_id = $sid;
                $setpoint->command = $command;
                $setpoint->setpoint = $type_array[$command]->setpoint;
                $setpoint->hysteresis = $type_array[$command]->hysteresis;
                $setpoint->alarm_high = $type_array[$command]->alarm_high;
                $setpoint->alarm_low = $type_array[$command]->alarm_low;
                $setpoint->environmental_offset = 0;
                $setpoint->priority_alarms = 1;
                $setpoint->save();// Save new setpoints.
                unset($setpoint);
            }
        }
        return;
    }

   /**
   * Remap all setpoints for system.
   * @param int    $id The building to to remap
   * @param int    $sid The system to remap
   */
    public static function remap($id, $sid)
    {
        $device_array = array();
        $product_array = array();
        $type_array = array();
        $setpoint_array = array();

        $thisBldg = Building::find($id);
        $thisSys = System::find($sid);
        $deviceSetpoints = DeviceSetpoints::where('system_id', $sid)->get();
        $deviceTypes = DeviceType::all();
        $productTypes = ProductType::all();
    
        $devices = Device::where('system_id', $sid)
        ->where('device_io', 'input')
        ->where('product_id', '!=', '000')
        ->where('product_id', '!=', 'direct')
        ->where('retired', '!=', 1)
        ->orderby('updated_at', 'ASC')
        ->get();

        foreach ($devices as $device) {           // Create an array of all system devices.
            $device_array[$device->id] = $device;
        }
        foreach ($productTypes as $product) {     // Make product id key to table
            $product_array[$product->product_id] = $product;
        }
        foreach ($deviceTypes as $type) {         // Create an array of all device types.
            $type_array[$type->command] = $type;
        }

        foreach ($deviceSetpoints as $setpoint) { // Create an array of all system setpoints.
            if (!isset($device_array[$setpoint->device_id]) || (isset($device_array[$setpoint->device_id]) && ($device_array[$setpoint->device_id]->retired == 1))) {
                // Remove any old setpoints without associated devices.
                $setpoint->delete();
            } else {
                // Load array with known setpoints.
                $setpoint_array[$setpoint->device_id][$setpoint->command] = $setpoint;
            }
        }

        //Generate setpoints for device-command combinations lacking a setpoint entry
        foreach ($device_array as $device) {                                                    // Loop through devices.
            foreach (explode(',', $product_array[$device->product_id]->commands) as $command) {    //Loop through each of the device's associated commands
                if ($command == 2 || $device->zone > 0) {                                        // Create setpoints for voltage sensors in zone 0 as well.
                    if (!isset($setpoint_array[$device->id][$command])) {                            // Check to see if a setpoint exists already or is missing for each device/type combination.
                        $setpoint = new DeviceSetpoints();
                        $setpoint->device_id = $device->id;
                        $setpoint->system_id = $sid;
                        $setpoint->command = $command;
                        $setpoint->setpoint = $type_array[$command]->setpoint;
                        $setpoint->hysteresis = $type_array[$command]->hysteresis;
                        $setpoint->alarm_high = $type_array[$command]->alarm_high;
                        $setpoint->alarm_low = $type_array[$command]->alarm_low;
                        $setpoint->environmental_offset = 0;
                        $setpoint->priority_alarms = 0;
                        $setpoint->save();// Save new setpoints.
                        unset($setpoint);
                    }
                }
            }
        }

        unset($deviceSetpoints);

        $deviceSetpoints  = DeviceSetpoints::where('system_id', $sid)
        ->orderby('device_id')
        ->orderby('command')
        ->orderby('updated_at', 'DESC')
        ->groupby('device_id', 'command')
        ->get();

        foreach ($deviceSetpoints as $setpoint) {
            $duplicates = DeviceSetpoints::where('system_id', $sid)
            ->where('device_id', $setpoint->device_id)
            ->where('command', $setpoint->command)
            ->where('id', '!=', $setpoint->id)
            ->get();
            foreach ($duplicates as $duplicate) {
                $duplicate->delete();
            }
            unset($duplicates);
        }


        SetpointMappingController::DeploySetpoints($sid);// Send new setpoints mapping file down to algorithms to read.
        return Redirect::route('setpointmapping.index', [$id, $sid]);
    }
  /**
   * Send a new setpoint mapping file to a remote machine
   * @param int    $system_id The system to send a mapping file too
   */
    public static function DeploySetpoints($system_id)
    {
        // Grab full config data and create config.txt in /storage
        $devices = DeviceSetpoints::where('system_id', $system_id)
        ->join('device_types', 'device_types.command', '=', 'device_setpoints.command')
        ->select('device_types.setpoint AS setpoint2', 'device_types.hysteresis AS hysteresis2', 'device_types.alarm_high AS alarm_high2', 'device_types.alarm_low AS alarm_low2', 'device_types.*', 'device_setpoints.*')
        ->get();

        $setbacks = DeviceSetback::where('system_id', $system_id)//Use join to add device type data to this query.
        ->orderBy('starttime', 'ASC')
        ->get();
 

        $content = '';
    
        foreach ($devices as $device) {
            $numSB = 0;
            $setbacksString = null;
            foreach ($setbacks as $setback) {// Put all stored setbacks into a single string.
                if ($setback->device_id == $device->device_id && $setback->command == $device->command) {
                    $numSB = max($numSB, $setback->index);
                    $setbacksString .= $setback->starttime               . ' ';
                    $setbacksString .= $setback->stoptime                . ' ';
                    $setbacksString .= sprintf("%0.1f", $setback->setback) . ' ';
                }
            }
            $content .= sprintf("%05d", $device->device_id)             . ' ';
            $content .= sprintf("%02d", $device->command)               . ' ';
            $content .= $numSB                                         . ' ';
            $content .= sprintf("%0.4f", $device->setpoint)             . ' ';
            $content .= $setbacksString                                     ;
            $content .= sprintf("%0.4f", $device->hysteresis)           . ' ';
            $content .= sprintf("%0.4f", $device->alarm_high)           . ' ';
            $content .= sprintf("%0.4f", $device->alarm_low)            . ' ';
            $content .= sprintf("%0.3f", $device->environmental_offset) . ' ';
            $content .= '0'                                            . ' ';
            $content .= $device->state_above_setpoint                       ;
            $content .= "\n";
        }

        RemoteTask::deployFile($system_id, '/var/2020_mapping', 'algo_in_mapping.txt', $content);
        RemoteTask::deployFile($system_id, '/var/2020_algorithm/table_updates', 'update.txt', 'algo_in_mapping.txt');

        //if($GLOBALS['session_error'] != ""){
        //  Session::flash('error', $GLOBALS['session_error']);
        //}
        //else{
        //  Session::flash('error', '$GLOBALS[session_error]');
        //}
    }
}

/**
* For F values, convert to C, if warrantedd
* @return The converted value
*/
function value_convert($units, $function, $value)
{
    if (($units == "F") && ($function == 'Temperature')) {
        return (($value)-32)*(5/9);
    } else {
        return $value;
    }
}

function convert_to_system($units, $function, $value)
{
    if (($units == "F") && ($function == 'Temperature')) {
        return ((($value)*(9/5)) + 32);
    } else {
        return $value;
    }
}

function setback_time_form_parse($time_string, $sid, $bid)
{
    $seconds = 0;
    $boom = explode(":", $time_string);
    $boom_size = sizeof($boom);
    switch ($boom_size) {
        case 1:
            /*Only hours provided*/
            $seconds = $boom[0] * 3600;
            break;
        case 2:
            /*Hours and minutes provided*/
            $seconds = ($boom[0] * 3600) + ($boom[1] * 60);
            break;
        case 3:
            /*Hours and minutes and seconds provided*/
            $seconds = ($boom[0] * 3600) + ($boom[1] * 60) + $boom[2];
            break;
        default:
            Session::flash('error', 'Setback time format should be HH:MM:SS. Please check your setback and try again.');
            return Redirect::to(route('setpointmapping.index', [$bid, $sid]));
        break;
    }
    unset($boom);
    return $seconds;
}
/**
  * Change all the attribute values of each form input.
  * @private
  * @param  {int}     $meridiem  0 = PM and 1 = AM
  * @param  {int}     $time_s    time in seconds
  * @param  {String}  $stopstart  "START" or "STOP"
*/
function setback_time_rules($meridiem, $time_s, $stopstart)
{
    static $twelveHourMinute = 43200; /* = (12 * 3600) */
    static $twentyFourHourMinute = 86400; /*  = (24 * 3600) */

    if ($stopstart == "START") {
        if (( (int) $time_s >= $twelveHourMinute) && ( (int) $meridiem == 0)) {
            return $time_s - $twelveHourMinute;
        } else if (( (int) $time_s < $twelveHourMinute) && ( (int) $meridiem == 1)) {/*PM*/
            return (int) $time_s + $twelveHourMinute;
        } else {
            return (int) $time_s;
        }
    } else if ($stopstart == "STOP") {
        if (( (int) $time_s == $twelveHourMinute ) && ( (int) $meridiem == 1 )) {/*AM*/
            return $twelveHourMinute;
        } else if (( (int) $time_s >= $twelveHourMinute) && ( (int) $meridiem == 0)) {
            if ((int) $time_s == $twelveHourMinute) {
                return (int) $twentyFourHourMinute;
            }
            return $time_s - $twelveHourMinute;
        } else if ((int) $time_s != $twelveHourMinute && (int) $meridiem == 1) {/*PM*/
            return (int) $time_s + $twelveHourMinute;
        } else {
            return (int) $time_s;
        }
    }
}

function setback_conflict_resolution($recnum, $single)
{
    $setbacks = DeviceSetback::wherein('device_setback.recnum', $recnum)
    ->join('devices', function ($join) {
        $join->on('devices.id', '=', 'device_setback.device_id');
        $join->on('devices.system_id', '=', 'device_setback.system_id');
    })
    ->join('device_types', 'device_types.command', '=', 'device_setback.command')
    ->select('device_setback.*', 'devices.name', 'device_types.function as dev_function')
    ->orderby('device_setback.device_id')
    ->get();
  
    $sb_entry = array();
    $rec_num = 0;
    foreach ($setbacks as $sb) {
        $start = explode(":", $sb->starttime);
        $stop = explode(":", $sb->stoptime);

        $sb_entry[$rec_num] = [
        'recnum' => $sb->recnum,
        'starttime' => $start[1],
        'stoptime' => $stop[1],
        'dotw' => $start[0],
        'device_id' => $sb->device_id,
        'device_name' => $sb->name,
        'device_function' => $sb->dev_function,
        'index' => $sb->index,
        'created_at' => $sb->created_at,
        'error' => false
        ];
        $rec_num++;
    }
    $inner;
    $outter;
    $dev_start_key = 0;
    $dev_end_key = 0;
    $key;
  //Check individul setback for when starttime > stoptime
    for ($key = 0; $key < $rec_num; $key++) {
        if ((integer) $sb_entry[$key]['starttime'] >= (integer) $sb_entry[$key]['stoptime']) { /*check for backward setback*/
            $GLOBALS['session_error'] = $GLOBALS['session_error'].'&bull; Removed setback#'.$sb_entry[$key]['index'].' for stop time preceeding start time.<br>';
            if ($key == 0 || $single) {
                Session::flash('error', $GLOBALS['session_error']);
            }
            $sb_entry[$key]['error'] = true;
        }
    }
    $Total_error_message = 0;
    for ($key = 0; $key < $rec_num; $key++) {
        $later_conflict = (integer) '-1';
        if (($sb_entry[$dev_start_key]['device_id'] == $sb_entry[$key]['device_id']) && ($key != (sizeof($sb_entry)-1))) {
            /* find entries for device with dev_start_key index */
            $dev_end_key = $key;
        } else if (($sb_entry[$dev_start_key]['device_id'] == $sb_entry[$key]['device_id']) && ($key == (sizeof($sb_entry)-1))) {
            $dev_end_key = $key;
            if ($dev_start_key != $dev_end_key) {
                /* if there is more than one set back for the device */
                /* increment through all setback combinations for this device */
                for ($inner = $dev_start_key; $inner <= ($dev_end_key-1); $inner++) {
                    for ($outter = ($dev_start_key + 1); $outter <= $dev_end_key; $outter++) {
                        if (same_day($sb_entry[$inner]['dotw'], $sb_entry[$outter]['dotw'])) {
                            /* Check setback combination for conflict */
                            $later_conflict = conflicts($sb_entry[$inner], $inner, $sb_entry[$outter], $outter, $Total_error_message, $single);
                            if (((integer) $later_conflict >= 0) && ((integer) $later_conflict < $rec_num)) {
                                $sb_entry[$later_conflict]['error'] = true;
                                $Total_error_message++;
                            }
                        }
                    }
                }
            }
        } else {
            if ($dev_start_key != $dev_end_key) {
                /* if there is more than one set back for the device */
                /* increment through all setback combinations for this device */
                for ($inner = $dev_start_key; $inner <= ($dev_end_key-1); $inner++) {
                    for ($outter = ($dev_start_key + 1); $outter <= $dev_end_key; $outter++) {
                        if (same_day($sb_entry[$inner]['dotw'], $sb_entry[$outter]['dotw'])) {
                            /* Check setback combination for conflict */
                            $later_conflict = conflicts($sb_entry[$inner], $inner, $sb_entry[$outter], $outter, $Total_error_message, $single);
                            if (((integer) $later_conflict >= 0) && ((integer) $later_conflict < $rec_num)) {
                                $sb_entry[$later_conflict]['error'] = true;
                                $Total_error_message++;
                            }
                        }
                    }
                }
            }
            /* set to search for new dev */
            $dev_start_key = $key;
            $dev_end_key = $key;
        }
    }
  
    $recnums_for_removal = array();
    foreach ($sb_entry as $key => $sbe) {
        if ($sbe['error'] === true) {
            if (isset($sbe['recnum'])) {
                $recnums_for_removal[] = $sbe['recnum'];
            }
        }
    }
    DeviceSetback::destroy($recnums_for_removal);
}

/**
  * Find conflicts between two different setbacks.
  * Conflict type
  * StartTime ------------------- StopTime           StartTime ---------- StopTime        StartTime ---------- StopTime                     StartTime ---------- StopTime
  *       StartTime ---- StopTime           StartTime ---------- StopTime                       StartTime ---------- StopTime       StartTime ------------------------- StopTime
  * @private
  * @param  {Obj}     $A_setback    Current setback
  * @param  {Int}     $A_index      Current setback index
  * @param  {Obj}     $B_setback    Next setback
  * @param  {Int}     $B_index      Next setback index
  * @param  {Int}     $Total_error_message     Total number of conflict found
  * @param  {Boolean} $single       True if individual device, False if Zonal or Global
*/
function conflicts($A_setback, $A_index, $B_setback, $B_index, $Total_error_message, $single)
{
    if ((($A_setback['error'] === false) && ($B_setback['error'] === false)) && ($A_setback['recnum'] != $B_setback['recnum'])) {/*ensure neither entry has already been marked as erroneous*/
        if ((integer) $A_setback['starttime'] >= (integer) $A_setback['stoptime']) { /*check for backward setback*/
            $GLOBALS['session_error'] = $GLOBALS['session_error'].'&bull; Removed setback for stop time preceeding start time.<br>';
            if ($Total_error_message == 0 || $single) {
                Session::flash('error', $GLOBALS['session_error']);
            }
            return (integer) $A_index;
        } else if ((integer) $B_setback['starttime'] >= (integer) $B_setback['stoptime']) { /*check for backward setback*/
            $GLOBALS['session_error'] = $GLOBALS['session_error'].'&bull; Removed setback for stop time preceeding start time.<br>';
            return (integer) $B_index;
        }

        if (( ((integer) $A_setback['starttime']>=(integer) $B_setback['starttime']) && ((integer) $A_setback['starttime']<(integer) $B_setback['stoptime'] ) )
         || ( ((integer) $A_setback['starttime']<=(integer) $B_setback['starttime']) && ((integer) $A_setback['stoptime']>=(integer) $B_setback['stoptime']) )
         || ( ((integer) $A_setback['stoptime']>(integer) $B_setback['starttime']) && ((integer) $A_setback['stoptime']<=(integer) $B_setback['stoptime']) )
         || ( ((integer) $A_setback['starttime']>=(integer) $B_setback['starttime']) && ((integer) $A_setback['stoptime']<=(integer) $B_setback['stoptime']) ) ) {
            /*CONFLICT*/
            $for_removal = (integer) ((int)$A_setback['index'] > (int)$B_setback['index'])?(int)$A_setback['index']:(int)$B_setback['index'];
            $for_keeps = ((int)$A_setback['index'] < (int)$B_setback['index'])?(int)$A_setback['index']:(int)$B_setback['index'];
            $Devi_name = $single ? $A_setback['device_name'] : '';
            $GLOBALS['session_error'] = $GLOBALS['session_error'].'<span style="text-align:center;"><big><b> Removed '.$Devi_name.' - '.$A_setback['device_function'].' setback #'.$for_removal.'</b> (Due to date / time conflict with previous setback #'.$for_keeps.').</big></span><br>';
            if ($Total_error_message == 0 || $single) {
                  Session::flash('error', $GLOBALS['session_error']);
            }
            return (integer) ((int)$A_setback['index'] > (int)$B_setback['index'])?$A_index:$B_index;/*Mark the entry, with the higher index, for removal*/
        }
    }
    return (integer) '-1';
}

function same_day($day1, $day2)
{
    $isSameDay = false;

    switch ($day1) {
        case 9:
            $isSameDay = true;
            break;
        case 8:
            if (($day2 == 6) || ($day2 == 0) || ($day2 == 9) || ($day2 == 8)) {
                $isSameDay = true;
            }
            break;
        case 6:
        case 0:
            if (($day2 == 9) || ($day2 == 8) || ($day2 == 0) || ($day2 == 6)) {
                $isSameDay = true;
            }
            break;
        case 7:
            if ((($day2 <= 5) && ($day2 >= 1)) || ($day2 == 9) || ($day2 == 7)) {
                $isSameDay = true;
            }
            break;
        case 1:
            if ($day2 == 1) {
                $isSameDay = true;
            }
        case 2:
            if ($day2 == 2) {
                $isSameDay = true;
            }
        case 3:
            if ($day2 == 3) {
                $isSameDay = true;
            }
        case 4:
            if ($day2 == 4) {
                $isSameDay = true;
            }
        case 5:
            if (($day2 == 9) || ($day2 == 7) || ($day2 == 5)) {
                $isSameDay = true;
            }
        default:
            # code...
            break;
    }
    return $isSameDay;
}
