<?php

class BuildingController extends BaseController
{

  /**
   * Display a customizable dashboard page
   * @param  int     $building_id  The building ID for the system being iewed
   * @param  int     $system_id    The system ID for the system being viewed
   * @param  integer $dashboard_id The dashboard page being displayed
   * @return object                The view being rendered
   */
    public function page($building_id, $system_id, $dashboard_id = 0)
    {
        $building = Building::find($building_id);
        $system = System::find($system_id);
        /**
    * Sidebar menu items when loading dashbaord page
    * When loading any other page, it'll load 1 item
    */
        $items = DashboardItem::where('system_id', $system_id)
        ->where('parent_id', $dashboard_id)
        ->whereRaw("label not LIKE '%EMC Hardware System%'")
        ->orderBy('order')
        ->remember(10)->get();
        /**
     * If there are no top level menu items then use the default
     * (i.e. system_id == 0)
     */
        if (!count($items)) {
            $items = DashboardItem::where('system_id', 0)
            ->where('parent_id', $dashboard_id)
            ->whereRaw("label not LIKE '%EMC Hardware System%")
            ->orderBy('order')
            ->remember(10)->get();
        }

        $parent = DashboardItem::find($items[0]->parent_id);

        /**
     * Display one of the statically defined pages
     *
     * These are very general reports that will look and behave the same across
     * all systems. They are displayed on there own, so they must be the only
     * item listed on their page.
     *
     */
        if (count($items) === 1) {
            switch ($items[0]->chart_type) {
                case 'ZONE':
                    return $this::zonestatus($building_id, $system_id, $dashboard_id, $parent);
                break;

                case 'ALARM':
                    return $this::alarmstatus($building_id, $system_id, $dashboard_id, $parent);
                break;

                case 'DEVICE':
                    return $this::devicestatus($building_id, $system_id, $dashboard_id, $parent);
                break;

                case 'EVENT':
                    return $this::eventstatus($building_id, $system_id, $dashboard_id, $parent);
                break;

                case 'FURNACE':
                    return self::dashboardMap($building_id, $system_id, $dashboard_id, $parent);
                break;

                default:
                    break;
            }
        }
        // ================= Dashboard D3 chart skeleton data ========================
        $startDate = new DateTime();
        $endDate = new DateTime();
        $retired = 0;

        //Get a list of commands, device id, device name, product id, zonename and zone id
        $DeviceObj = ProductType::select(DB::raw("CONVERT( SUBSTRING_INDEX(SUBSTRING_INDEX(t.commands, ',', n.n), ',', -1), UNSIGNED INTEGER) as command, devices.id, devices.product_id, devices.name, zone_labels.zonename, zone_labels.zone"))
                              ->from(DB::raw('product_types t CROSS JOIN (
                                SELECT a.N + b.N * 10 + 1 n FROM
                                  (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a ,
                                  (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                                ORDER BY n ) n'))
                              ->join('devices', 't.product_id', '=', 'devices.product_id')
                              ->join('zone_labels', function ($q) {
                                          $q->on('zone_labels.system_id', '=', 'devices.system_id')
                                              ->on('zone_labels.zone', '=', 'devices.zone');
                              })
                              ->whereRaw("n.n <= 1 + (LENGTH(t.commands) - LENGTH(REPLACE(t.commands, ',', '')))")
                              ->where('devices.system_id', $system_id)
                              ->where('devices.retired', $retired)
                              ->remember(10)->get();

        //Get a list of function names(not distinct), commands, units, digital(0 or 1)
        $FunctionObj = DeviceType::select('function', 'command', 'units', 'digital')
        ->whereIn('command', function ($subquery) use ($system_id, $retired) {
            $subquery //convert comma seperated commands into rows of values
            ->select(DB::raw("distinct SUBSTRING_INDEX(SUBSTRING_INDEX(t.commands, ',', n.n), ',', -1) as command"))
            ->from(DB::raw('product_types t CROSS JOIN (
              SELECT a.N + b.N * 10 + 1 n FROM
                (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a ,
                (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
              ORDER BY n ) n'))
            ->whereRaw("n.n <= 1 + (LENGTH(t.commands) - LENGTH(REPLACE(t.commands, ',', '')))")
            ->whereIn('product_id', function ($subquery1) use ($system_id, $retired) {
                $subquery1
                  ->select('product_id')
                  ->distinct()
                  ->from('devices')
                  ->where('system_id', $system_id)
                  ->where('retired', $retired);
            })
            ->orderBy('command', 'asc');
        })
        ->remember(10)->get();

        //Add function name property to DeviceObj by comparing commands from both query results
        foreach ($DeviceObj as $CurDevice) {
            foreach ($FunctionObj as $CurFunc) {
                if ($CurDevice->command == $CurFunc->command) {
                    $CurDevice->function = $CurFunc->function;
                }
            }
        }

        /* key = function name
         value = Multiple function properties- Device names, zones, units, Digital...
        */
        $FuncData = [];
        $all_zones = [];
        //create $FuncData from FunctionObj and $DeviceObj, that'll be passed on to the view
        foreach ($FunctionObj as $cur_func) {
            if (! array_key_exists($cur_func->function, $FuncData)) { //only unique functions
                $FuncData[$cur_func->function] = [];
                $FuncData[$cur_func->function]["Digital"] = $cur_func->digital;
                $FuncData[$cur_func->function]["Units"] = $cur_func->units;
                $FuncData[$cur_func->function]["dev_name"] = [];
                $FuncData[$cur_func->function]["dev_id"] = [];
                $FuncData[$cur_func->function]["zone_name"] = [];
                $FuncData[$cur_func->function]["zone_id"] = [];
                $FuncData[$cur_func->function]["commands"] = [];
                foreach ($DeviceObj as $cur_device) {
                    if ($cur_device->function == $cur_func->function) {
                        array_push($FuncData[$cur_func->function]["dev_name"], $cur_device->name);
                        array_push($FuncData[$cur_func->function]["dev_id"], $cur_device->id);
                        array_push($FuncData[$cur_func->function]["zone_name"], $cur_device->zonename); //zone name for each device
                        array_push($FuncData[$cur_func->function]["zone_id"], $cur_device->zone);   //zone id for each device
                        array_push($FuncData[$cur_func->function]["commands"], $cur_device->command);   //command for each device
                    }
                }
                if ($cur_func->function == "Temperature") {
                    if ($system->temperature_format == 'F') {
                        $FuncData[$cur_func->function]["Units"] = "degrees F";
                    }
                    //return list of zone ID's seperated by comma for each temperature range - low, medium, high
                    $FuncData[$cur_func->function]["zone_temp"] = Zone::select(DB::raw("GROUP_CONCAT(zone SEPARATOR ', ') as zones, temp_range, GROUP_CONCAT(zonename SEPARATOR ', ') as zonenames"))
                                                              ->where('system_id', $system_id)
                                                              ->groupBy('temp_range')
                                                              ->get()->toArray();
                    //convert comma seperated list into an array
                    for ($i=0; $i < count($FuncData[$cur_func->function]["zone_temp"]); $i++) {
                        $FuncData[$cur_func->function]["zone_temp"][$i]["zones"]=array_map('intval', explode(",", $FuncData[$cur_func->function]["zone_temp"][$i]["zones"]));
                        $FuncData[$cur_func->function]["zone_temp"][$i]["zonenames"]=explode(",", $FuncData[$cur_func->function]["zone_temp"][$i]["zonenames"]);
                    }
                    //order the zones so that high temperature always at top, and then medium and then low
                    $order = array('high', 'medium', 'low');
                    usort($FuncData[$cur_func->function]["zone_temp"], function ($a, $b) use ($order) {
                        $pos_a = array_search($a['temp_range'], $order);
                        $pos_b = array_search($b['temp_range'], $order);
                        return $pos_a - $pos_b;
                    });
                }
                // Get current Water meter reading
                if ($cur_func->function == "Water") {
                    $UtilityData = DeviceDataCurrent::select('device_data_current.datetime', 'device_data_current.current_value', 'device_data_current.setpoint', 'device_data_current.alarm_state', 'device_setpoints.alarm_high', 'device_setpoints.alarm_low')
                                            ->join('device_setpoints', function ($join) {
                                                $join->on('device_setpoints.system_id', '=', 'device_data_current.system_id')
                                                    ->on('device_setpoints.device_id', '=', 'device_data_current.id')
                                                    ->on('device_setpoints.command', '=', 'device_data_current.command');
                                            })
                                            ->where('device_data_current.system_id', $system_id)
                                            ->whereIn('device_data_current.id', $FuncData[$cur_func->function]["dev_id"])
                                            ->whereIn('device_data_current.command', $FuncData[$cur_func->function]["commands"]);
                    if ($UtilityData->exists()) {
                              $UtilityData = $UtilityData->first()->toArray();
                              $UtilityData['description'] = $UtilityData['alarm_state'] == 0 ? "No Alarm" :  ($UtilityData['alarm_state'] == 1 ? "Moderately High/Low Report" : "Critically High/Low Report" );
                              $FuncData[$cur_func->function]["data"] = $UtilityData;
                    }
                }
            }
        }

        // Alarm Data
        $functionList = [];     //list of distinct function names
        foreach ($FunctionObj as $cur_func) {
            if (! in_array($cur_func->function, $functionList)) {
                array_push($functionList, $cur_func->function);
            }
        }

        $active_alarms = Alarms::select('alarms.*', 'devices.name')
                            ->join('devices', function ($join) {
                                $join->on('devices.id', '=', 'alarms.device_id');
                                $join->on('devices.system_id', '=', 'alarms.system_id');
                            })
                            ->where('alarms.system_id', $system_id)
                            ->where('alarms.active', 1)
                            ->orderBy('alarms.created_at', 'desc')
                            ->remember(10)->get();
                            DB::setFetchMode(PDO::FETCH_ASSOC);
        //System log tab
        $log_types = LogType::select('name', 'id')->get();
        /**
     * A reference list of all devices is used to make a series for each on on
     * the chart. The page's JS will also need this in order to toggle visibility
     * on each series when plotting data or choosing a zone to display.
     */
        // $seriesReference = [];
        // $seriesCount = count($device_list);
        // foreach ($device_list as $device) {
        //   array_push($seriesReference, intval($device->id));
        // }
        $data['alarms'] = $active_alarms;
        $data['retired']         = $retired;
        $data['startDate']       = $startDate;
        $data['endDate']         = $endDate;
        $data['functionList']    = $functionList;
        $data['InitData']        = $FuncData;
        /* The building and system information may be needed in the navbar and sidebar */
        $data['thisBldg']    = $building;
        $data['thisSystem']  = $system;
        $data['items']       = $items;
        $data['log_types']   = $log_types;

        if ($dashboard_id) {
            $data['parent'] = $parent;
        }
        if (Request::is('EMC/*')) {
            return View::make('buildings.dashboard-items-touch', $data);
        } else {
            return View::make('buildings.dashboard-items', $data);
        }
    }

  /**
  * Responde to background calls for System Log tab
  * @param  integer $building_id
  * @param  integer $system_id
  * @return string A json encoded array of the data being requested {application_name, report, datetime}
  */
    public function SystemLogAjax($building_id, $system_id)
    {
        $log_type   = Input::get("log_types");
        $start_date = Input::get("s_date");
        $end_date   = Input::get("e_date");

        $s_date_obj = new DateTime($start_date);
        $e_date_obj = new DateTime($end_date);
        $start_date = date_format($s_date_obj, 'Y-m-d 00:00:00');
        $end_date   = date_format($e_date_obj, 'Y-m-d 23:59:59');

        $SystemLogData = SystemLog::select('application_name', 'report', 'datetime')
                              ->where('system_id', '=', $system_id)
                              ->where('datetime', '>=', $start_date)
                              ->where('datetime', '<=', $end_date)
                              ->orderBy('datetime', 'desc');
        if ($log_type != 0) {
            $SystemLogData = $SystemLogData->where('log_type', '=', $log_type);
        }
        $data = $SystemLogData->paginate(15);
        $res = array(
        'data' => json_decode($data->toJson()),   // data
        'links' => $data->links()->render()   // link to each page
        );
        return Response::json($res);
    }

 /**
   * Responde to background calls for Water Widget
   * @param  integer $building_id
   * @param  integer $system_id
   * @return string A json encoded array of the data being requested {current_value, alarm_state, alarm_low, alarm_high, datetime, description, setpoint}
   */
    public function WaterAjax($building_id, $system_id)
    {
        $device_id = Input::get("dev_id");
        $command = Input::get("command");
        // Get current Water meter reading
        $UtilityData = DeviceDataCurrent::select('device_data_current.datetime', 'device_data_current.current_value', 'device_data_current.setpoint', 'device_data_current.alarm_state', 'device_setpoints.alarm_high', 'device_setpoints.alarm_low')
                                      ->join('device_setpoints', function ($join) {
                                          $join->on('device_setpoints.system_id', '=', 'device_data_current.system_id')
                                              ->on('device_setpoints.device_id', '=', 'device_data_current.id')
                                              ->on('device_setpoints.command', '=', 'device_data_current.command');
                                      })
                                      ->where('device_data_current.system_id', $system_id)
                                      ->whereIn('device_data_current.id', $device_id)
                                      ->whereIn('device_data_current.command', $command);
        if ($UtilityData->exists()) {
            $UtilityData = $UtilityData->first()->toArray();
            $UtilityData['description'] = $UtilityData['alarm_state'] == 0 ? "No Alarm" :  ($UtilityData['alarm_state'] == 1 ? "Moderately High/Low Report" : "Critically High/Low Report" );
            $UtilityData['datetime'] = date("F jS, Y", strtotime($UtilityData['datetime']));
        }
        return Response::json($UtilityData);
    }

  /**
   * Responde to background calls for Weather Widget
   * Server side API call protects the API Token ID to be visible to the client
   * @param  integer $building_id
   * @param  integer $system_id
   * @return string A json encoded array of the data being requested {current temperature, max temperature, min temperature, condition, css_class, html code}
   */
    public function WeatherAjax($building_id, $system_id)
    {
        $CheckCache = Input::get("cache");
        function grabNewData($building_id, $system_id)
        {
            $json = [];
            $client   = new GuzzleHttp\Client();
            //Get City zip code
            $zip_code = Building::select('zip')
                            ->where('id', '=', $building_id)
                            ->first();
            // This contains all the classes and HTML code to display appropriate weather icons, depending on weather condition
            $weather_obj = array( 0 => array("condition" => 'clear',
              'css_class' => 'icon sunny',
              'html' => '<div class="sun"><div class="rays"></div></div>'
            ),1 => array(
              'condition' => 'clouds',
              'css_class' => 'icon cloudy',
              'html' => '<div class="cloud"></div><div class="cloud"></div>'
            ),2 => array(
              'condition' => 'shower',
              'css_class' => 'icon rainy',
              'html' => '<div class="cloud"></div><div class="rain"></div>'
            ),3 => array(
              'condition' => 'rain',
              'css_class' => 'icon sun-shower',
              'html' => '<div class="cloud"></div><div class="sun"><div class="rays"></div></div><div class="rain"></div>'
            ),4 => array(
              'condition' => 'thunderstorm',
              'css_class' => 'icon thunder-storm',
              'html' => '<div class="cloud"></div><div class="lightning"><div class="bolt"></div><div class="bolt"></div></div>'
            ),5 => array(
              'condition' => 'snow',
              'css_class' => 'icon flurries',
              'html' => '<div class="cloud"></div><div class="snow"><div class="flake"></div><div class="flake"></div></div>'
            ),6 => array(
              'condition' => 'mist',
              'css_class' => 'icon cloudy',
              'html' => '<div class="cloud"></div><div class="cloud"></div>'
            )
              );
            $forecast_obj = array( 0 => array("condition" => 'clear',
                    'svg' => '<svg class="sunshine" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path class="sun-full" d="M256,144c-61.8,0-112,50.2-112,112s50.2,112,112,112s112-50.2,112-112S317.8,144,256,144z M256,336
                                  c-44.2,0-80-35.8-80-80s35.8-80,80-80s80,35.8,80,80S300.2,336,256,336z" />
                              <path class="sun-ray-eight" d="M131.6,357.8l-22.6,22.6c-6.2,6.2-6.2,16.4,0,22.6s16.4,6.2,22.6,0l22.6-22.6c6.2-6.3,6.2-16.4,0-22.6
                                  C147.9,351.6,137.8,351.6,131.6,357.8z" />
                              <path class="sun-ray-seven" d="M256,400c-8.8,0-16,7.2-16,16v32c0,8.8,7.2,16,16,16s16-7.2,16-16v-32C272,407.2,264.8,400,256,400z" />
                              <path class="sun-ray-six" d="M380.5,357.8c-6.3-6.2-16.4-6.2-22.6,0c-6.3,6.2-6.3,16.4,0,22.6l22.6,22.6c6.2,6.2,16.4,6.2,22.6,0
                                  s6.2-16.4,0-22.6L380.5,357.8z" />
                              <path class="sun-ray-five" d="M448,240h-32c-8.8,0-16,7.2-16,16s7.2,16,16,16h32c8.8,0,16-7.2,16-16S456.8,240,448,240z" />
                              <path class="sun-ray-four" d="M380.4,154.2l22.6-22.6c6.2-6.2,6.2-16.4,0-22.6s-16.4-6.2-22.6,0l-22.6,22.6c-6.2,6.2-6.2,16.4,0,22.6
                                  C364.1,160.4,374.2,160.4,380.4,154.2z" />
                              <path class="sun-ray-three" d="M256,112c8.8,0,16-7.2,16-16V64c0-8.8-7.2-16-16-16s-16,7.2-16,16v32C240,104.8,247.2,112,256,112z" />
                              <path class="sun-ray-two" d="M131.5,154.2c6.3,6.2,16.4,6.2,22.6,0c6.3-6.2,6.3-16.4,0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6,0
                                  c-6.2,6.2-6.2,16.4,0,22.6L131.5,154.2z" />
                              <path class="sun-ray-one" d="M112,256c0-8.8-7.2-16-16-16H64c-8.8,0-16,7.2-16,16s7.2,16,16,16h32C104.8,272,112,264.8,112,256z" />
                            </svg>'
                ),1 => array(
                    "condition" => 'cloudy',
                    "svg" => '<svg class="windy-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <g class="cloud-wrap">
                              <path class="cloud" d="M417,166.1c-24-24.5-57.1-38.8-91.7-38.8c-34.6,0-67.7,14.2-91.7,38.8c-52.8,2.5-95,46.2-95,99.6
                              c0,55,44.7,99.7,99.7,99.7c5.8,0,11.6-0.5,17.3-1.5c20.7,13.5,44.9,20.9,69.7,20.9c24.9,0,49.1-7.3,69.8-20.9
                              c5.7,1,11.5,1.5,17.3,1.5c54.9,0,99.6-44.7,99.6-99.7C512,212.3,469.8,168.5,417,166.1z M412.4,333.3c-8.3,0-16.4-1.5-24-4.4
                              c-17.5,15.2-39.8,23.8-63.1,23.8c-23.2,0-45.5-8.5-63-23.8c-7.6,2.9-15.8,4.4-24,4.4c-37.3,0-67.7-30.4-67.7-67.7
                              c0-37.3,30.4-67.7,67.7-67.7c3.2,0,6.4,0.2,9.5,0.7c18.1-24.6,46.5-39.4,77.5-39.4c30.9,0,59.4,14.8,77.5,39.4
                              c3.1-0.5,6.3-0.7,9.6-0.7c37.3,0,67.6,30.4,67.6,67.7C480,303,449.7,333.3,412.4,333.3z" />
                              </g>
                              <path class="wind-three" d="M144,352H16c-8.8,0-16,7.2-16,16s7.2,16,16,16h128c8.8,0,16-7.2,16-16S152.8,352,144,352z" />
                              <path class="wind-two" d="M16,320h94c8.8,0,16-7.2,16-16s-7.2-16-16-16H16c-8.8,0-16,7.2-16,16S7.2,320,16,320z" />
                              <path class="wind-one" d="M16,256h64c8.8,0,16-7.2,16-16s-7.2-16-16-16H16c-8.8,0-16,7.2-16,16S7.2,256,16,256z" />
                            </svg>'
                  ),2 => array(
                    "condition" => 'shower',
                    "svg" => '<svg class="rain-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path class="raindrop-one" d="M96,384c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S96,366.3,96,384z" />
                              <path class="raindrop-two" d="M225,480c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S225,462.3,225,480z" />
                              <path class="raindrop-three" d="M352,448c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S352,430.3,352,448z" />
                              <path d="M400,64c-5.3,0-10.6,0.4-15.8,1.1C354.3,24.4,307.2,0,256,0s-98.3,24.4-128.2,65.1c-5.2-0.8-10.5-1.1-15.8-1.1
                              C50.2,64,0,114.2,0,176s50.2,112,112,112c13.7,0,27.1-2.5,39.7-7.3c29,25.2,65.8,39.3,104.3,39.3c38.5,0,75.3-14.1,104.3-39.3
                              c12.6,4.8,26,7.3,39.7,7.3c61.8,0,112-50.2,112-112S461.8,64,400,64z M400,256c-17.1,0-32.9-5.5-45.9-14.7
                              C330.6,269.6,295.6,288,256,288c-39.6,0-74.6-18.4-98.1-46.7c-13,9.2-28.8,14.7-45.9,14.7c-44.2,0-80-35.8-80-80s35.8-80,80-80
                              c10.8,0,21.1,2.2,30.4,6.1C163.7,60.7,206.3,32,256,32s92.3,28.7,113.5,70.1c9.4-3.9,19.7-6.1,30.5-6.1c44.2,0,80,35.8,80,80
                              S444.2,256,400,256z" />
                            </svg>'
                  ),3 => array(
                    'condition' => 'rain',
                    'svg' => '<svg class="rain-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path class="raindrop-one" d="M96,384c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S96,366.3,96,384z" />
                              <path class="raindrop-two" d="M225,480c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S225,462.3,225,480z" />
                              <path class="raindrop-three" d="M352,448c0,17.7,14.3,32,32,32s32-14.3,32-32s-32-64-32-64S352,430.3,352,448z" />
                              <path d="M400,64c-5.3,0-10.6,0.4-15.8,1.1C354.3,24.4,307.2,0,256,0s-98.3,24.4-128.2,65.1c-5.2-0.8-10.5-1.1-15.8-1.1
                              C50.2,64,0,114.2,0,176s50.2,112,112,112c13.7,0,27.1-2.5,39.7-7.3c29,25.2,65.8,39.3,104.3,39.3c38.5,0,75.3-14.1,104.3-39.3
                              c12.6,4.8,26,7.3,39.7,7.3c61.8,0,112-50.2,112-112S461.8,64,400,64z M400,256c-17.1,0-32.9-5.5-45.9-14.7
                              C330.6,269.6,295.6,288,256,288c-39.6,0-74.6-18.4-98.1-46.7c-13,9.2-28.8,14.7-45.9,14.7c-44.2,0-80-35.8-80-80s35.8-80,80-80
                              c10.8,0,21.1,2.2,30.4,6.1C163.7,60.7,206.3,32,256,32s92.3,28.7,113.5,70.1c9.4-3.9,19.7-6.1,30.5-6.1c44.2,0,80,35.8,80,80
                              S444.2,256,400,256z" />
                            </svg>'
                  ),4 => array(
                    'condition' => 'thunderstorm',
                    'svg' => '<svg class="thunder-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path d="M400,64c-5.3,0-10.6,0.4-15.8,1.1C354.3,24.4,307.2,0,256,0s-98.3,24.4-128.2,65.1c-5.2-0.8-10.5-1.1-15.8-1.1
                              C50.2,64,0,114.2,0,176s50.2,112,112,112c13.7,0,27.1-2.5,39.7-7.3c12.3,10.7,26.2,19,40.9,25.4l24.9-24.9
                              c-23.5-7.6-44.2-21.3-59.6-39.9c-13,9.2-28.8,14.7-45.9,14.7c-44.2,0-80-35.8-80-80s35.8-80,80-80c10.8,0,21.1,2.2,30.4,6.1
                              C163.7,60.7,206.3,32,256,32s92.3,28.7,113.5,70.1c9.4-3.9,19.7-6.1,30.5-6.1c44.2,0,80,35.8,80,80s-35.8,80-80,80
                              c-17.1,0-32.9-5.5-45.9-14.7c-10.4,12.5-23.3,22.7-37.6,30.6L303,312.2c20.9-6.6,40.5-16.9,57.3-31.6c12.6,4.8,26,7.3,39.7,7.3
                              c61.8,0,112-50.2,112-112S461.8,64,400,64z" />
                              <polygon class="bolt" points="192,352 224,384 192,480 288,384 256,352 288,256 " />
                            </svg>'
                  ),5 => array(
                    'condition' => 'snow',
                    'svg' => '<svg class="snow-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path d="M512,176c0-61.8-50.2-112-112-112c-5.3,0-10.6,0.4-15.8,1.1C354.3,24.4,307.2,0,256,0s-98.3,24.4-128.2,65.1
                              c-5.2-0.8-10.5-1.1-15.8-1.1C50.2,64,0,114.2,0,176s50.2,112,112,112c13.7,0,27.1-2.5,39.7-7.3c29,25.2,65.8,39.3,104.3,39.3
                              c38.5,0,75.3-14.1,104.3-39.3c12.6,4.8,26,7.3,39.7,7.3C461.8,288,512,237.8,512,176z M354.1,241.3C330.6,269.6,295.6,288,256,288
                              c-39.6,0-74.6-18.4-98.1-46.7c-13,9.2-28.8,14.7-45.9,14.7c-44.2,0-80-35.8-80-80s35.8-80,80-80c10.8,0,21.1,2.2,30.4,6.1
                              C163.7,60.7,206.3,32,256,32s92.3,28.7,113.5,70.1c9.4-3.9,19.7-6.1,30.5-6.1c44.2,0,80,35.8,80,80s-35.8,80-80,80
                              C382.9,256,367.1,250.5,354.1,241.3z" />
                          
                              <path class="snowflake-one" d="M131.8,349.9c-1.5-5.6-7.3-8.9-12.9-7.4l-11.9,3.2c-1.1-1.5-2.2-3-3.6-4.4c-1.4-1.4-2.9-2.6-4.5-3.6l3.2-11.9
                            c1.5-5.6-1.8-11.4-7.4-12.9c-5.6-1.5-11.4,1.8-12.9,7.4l-3.2,12.1c-3.8,0.3-7.5,1.2-10.9,2.9l-8.8-8.8c-4.1-4.1-10.8-4.1-14.8,0
                            c-4.1,4.1-4.1,10.8,0,14.9l8.8,8.8c-1.6,3.5-2.6,7.2-2.9,11l-12,3.2c-5.6,1.5-9,7.2-7.5,12.9c1.5,5.6,7.3,8.9,12.9,7.4l11.9-3.2
                            c1.1,1.6,2.2,3.1,3.7,4.5c1.4,1.4,2.9,2.6,4.4,3.6l-3.2,11.9c-1.5,5.6,1.8,11.4,7.4,12.9c5.6,1.5,11.3-1.8,12.8-7.4l3.2-12
                            c3.8-0.3,7.5-1.3,11-2.9l8.8,8.8c4.1,4.1,10.7,4,14.8,0c4.1-4.1,4.1-10.7,0-14.8l-8.8-8.8c1.7-3.5,2.7-7.2,2.9-11l12.1-3.2
                            C130,361.3,133.3,355.6,131.8,349.9z M88.6,371c-4.1,4.1-10.8,4.1-14.9,0c-4.1-4.1-4.1-10.8,0-14.8c4.1-4.1,10.8-4.1,14.9,0
                            S92.6,366.9,88.6,371z" />
                              <path class="snowflake-two" d="M304.8,437.6l-12.6-7.2c0.4-2.2,0.7-4.4,0.7-6.7c0-2.3-0.3-4.5-0.7-6.7l12.6-7.2c5.9-3.4,7.9-11,4.5-16.8
                            c-3.4-5.9-10.9-7.9-16.8-4.5l-12.7,7.3c-3.4-2.9-7.2-5.2-11.5-6.7v-14.6c0-6.8-5.5-12.3-12.3-12.3s-12.3,5.5-12.3,12.3V389
                            c-4.3,1.5-8.1,3.8-11.5,6.7l-12.7-7.3c-5.9-3.4-13.5-1.4-16.9,4.5c-3.4,5.9-1.4,13.4,4.5,16.8l12.5,7.2c-0.4,2.2-0.7,4.4-0.7,6.7
                            c0,2.3,0.3,4.5,0.7,6.7l-12.5,7.2c-5.9,3.4-7.9,11-4.5,16.9s10.9,7.9,16.8,4.5l12.7-7.3c3.4,2.9,7.2,5.1,11.5,6.7V473
                            c0,6.8,5.5,12.3,12.3,12.3s12.3-5.5,12.3-12.3v-14.6c4.3-1.5,8.2-3.8,11.5-6.7l12.7,7.3c5.9,3.4,13.4,1.4,16.8-4.5
                            C312.8,448.6,310.7,441.1,304.8,437.6z M256,436c-6.8,0-12.3-5.5-12.3-12.3c0-6.8,5.5-12.3,12.3-12.3s12.3,5.5,12.3,12.3
                            C268.3,430.5,262.8,436,256,436z" />
                              <path class="snowflake-three" d="M474.2,396.2l-12.1-3.2c-0.3-3.8-1.2-7.5-2.9-11l8.8-8.8c4.1-4.1,4.1-10.8,0-14.9c-4.1-4.1-10.7-4.1-14.8,0
                            l-8.8,8.8c-3.5-1.6-7.1-2.6-11-2.9l-3.2-12.1c-1.5-5.6-7.2-8.9-12.9-7.4c-5.6,1.5-8.9,7.3-7.4,12.9l3.2,11.9
                            c-1.6,1.1-3.1,2.3-4.5,3.7c-1.4,1.4-2.5,2.9-3.6,4.5l-11.9-3.2c-5.6-1.5-11.4,1.9-12.9,7.4c-1.5,5.6,1.9,11.4,7.4,12.9l12,3.2
                            c0.3,3.8,1.3,7.5,3,11l-8.8,8.8c-4.1,4.1-4.1,10.7,0,14.8c4.1,4.1,10.7,4.1,14.8,0l8.8-8.8c3.5,1.7,7.2,2.7,11,3l3.2,12
                            c1.5,5.6,7.2,8.9,12.9,7.4c5.6-1.5,9-7.2,7.5-12.9l-3.2-11.9c1.5-1.1,3-2.2,4.5-3.6c1.4-1.4,2.5-2.9,3.6-4.5l11.9,3.2
                            c5.6,1.5,11.4-1.9,12.9-7.4C483.1,403.5,479.8,397.8,474.2,396.2z M438.3,402.9c-4.1,4.1-10.8,4.1-14.9,0c-4.1-4.1-4.1-10.7,0-14.9
                            c4.1-4.1,10.8-4.1,14.9,0C442.4,392.2,442.4,398.9,438.3,402.9z" />
                            </svg>'
                  ),6 => array(
                    'condition' => 'mist',
                    'svg' => '<svg class="sun-cloud" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                              <path class="sun-half" d="M127.8,259.1c3.1-4.3,6.5-8.4,10-12.3c-6-11.2-9.4-24-9.4-37.7c0-44.1,35.7-79.8,79.8-79.8
                                  c40,0,73.1,29.4,78.9,67.7c11.4,2.3,22.4,5.7,32.9,10.4c-0.4-29.2-12-56.6-32.7-77.3C266.1,109,238,97.4,208.2,97.4
                                  c-29.9,0-57.9,11.6-79.1,32.8c-21.1,21.1-32.8,49.2-32.8,79.1c0,17.2,3.9,33.9,11.2,48.9c1.5-0.1,3-0.1,4.4-0.1
                                  C117.3,258,122.6,258.4,127.8,259.1z" />
                              <path class="cloud" d="M400,256c-5.3,0-10.6,0.4-15.8,1.1c-16.8-22.8-39-40.5-64.2-51.7c-10.5-4.6-21.5-8.1-32.9-10.4
                                  c-10.1-2-20.5-3.1-31.1-3.1c-45.8,0-88.4,19.6-118.2,52.9c-3.5,3.9-6.9,8-10,12.3c-5.2-0.8-10.5-1.1-15.8-1.1c-1.5,0-3,0-4.4,0.1
                                  C47.9,258.4,0,307.7,0,368c0,61.8,50.2,112,112,112c13.7,0,27.1-2.5,39.7-7.3c29,25.2,65.8,39.3,104.3,39.3
                                  c38.5,0,75.3-14.1,104.3-39.3c12.6,4.8,26,7.3,39.7,7.3c61.8,0,112-50.2,112-112S461.8,256,400,256z M400,448
                                  c-17.1,0-32.9-5.5-45.9-14.7C330.6,461.6,295.6,480,256,480c-39.6,0-74.6-18.4-98.1-46.7c-13,9.2-28.8,14.7-45.9,14.7
                                  c-44.2,0-80-35.8-80-80s35.8-80,80-80c7.8,0,15.4,1.2,22.5,3.3c2.7,0.8,5.4,1.7,8,2.8c4.5-8.7,9.9-16.9,16.2-24.4
                                  C182,241.9,216.8,224,256,224c10.1,0,20,1.2,29.4,3.5c10.6,2.5,20.7,6.4,30.1,11.4c23.2,12.4,42.1,31.8,54.1,55.2
                                  c9.4-3.9,19.7-6.1,30.5-6.1c44.2,0,80,35.8,80,80S444.2,448,400,448z" />
                          
                              <path class="ray ray-one" d="M16,224h32c8.8,0,16-7.2,16-16s-7.2-16-16-16H16c-8.8,0-16,7.2-16,16S7.2,224,16,224z" />
                              <path class="ray ray-two" d="M83.5,106.2c6.3,6.2,16.4,6.2,22.6,0c6.3-6.2,6.3-16.4,0-22.6L83.5,60.9c-6.2-6.2-16.4-6.2-22.6,0
                                  c-6.2,6.2-6.2,16.4,0,22.6L83.5,106.2z" />
                              <path class="ray ray-three" d="M208,64c8.8,0,16-7.2,16-16V16c0-8.8-7.2-16-16-16s-16,7.2-16,16v32C192,56.8,199.2,64,208,64z" />
                              <path class="ray ray-four" d="M332.4,106.2l22.6-22.6c6.2-6.2,6.2-16.4,0-22.6c-6.2-6.2-16.4-6.2-22.6,0l-22.6,22.6
                                  c-6.2,6.2-6.2,16.4,0,22.6S326.2,112.4,332.4,106.2z" />
                              <path class="ray ray-five" d="M352,208c0,8.8,7.2,16,16,16h32c8.8,0,16-7.2,16-16s-7.2-16-16-16h-32C359.2,192,352,199.2,352,208z" />
                            </svg>'
                  )
                );
            function kelvinToFahrenheit($value)
            {
                  return round((1.8*($value - 273) + 32));
            }
            try {
                  // =============================== Current Weather API Call ==================================
                  $json['current'] = [];
                  $json['forecast'] = [];
                  $res = $client->request('GET', "http://api.openweathermap.org/data/2.5/weather?zip=".$zip_code->zip."&APPID=d2229baaa223ea731d3737d46f0b7ef8");
                  $weatherAPI_data = json_decode($res->getBody(), true);
                  $json['current']['weather_current'] = kelvinToFahrenheit($weatherAPI_data['main']['temp']);
                  $json['current']['weather_temp_min'] = kelvinToFahrenheit($weatherAPI_data['main']['temp_min']);
                  $json['current']['weather_temp_max'] = kelvinToFahrenheit($weatherAPI_data['main']['temp_max']);
                  $json['current']['weather_condition'] = ucfirst($weatherAPI_data['weather'][0]['description']);
        
                for ($i=0; $i < count($weather_obj); $i++) {
                    $element = $weather_obj[$i];
                    if (stripos($json['current']['weather_condition'], $element['condition'])!== false) {
                        $json['current']['element'] = $element;
                        break;
                    } else {
                        $json['current']['element'] = $weather_obj[1];  // If condition not met, show cloudy
                    }
                }
                  $json['error']['current'] = $res->getStatusCode();

                  //======================= Forecast weather API Call ====================================
                  $res = $client->request('GET', "http://api.openweathermap.org/data/2.5/forecast?zip=".$zip_code->zip."&APPID=d2229baaa223ea731d3737d46f0b7ef8");
                  $weatherAPI_data = json_decode($res->getBody(), true);
                  $forecastlist = $weatherAPI_data['list'];
                  $future_dates = [];
                  $data = [];
                  // Loop through all the forecast data
                for ($i=0; $i < count($forecastlist); $i++) {
                    $todaydate = date('Y-m-d');
                    $curr_date = date('Y-m-d', strtotime($forecastlist[$i]['dt_txt']));
                    if ($todaydate != $curr_date) {  //Skip today's forecast
                        if (!in_array($curr_date, $future_dates)) {   // Create seperate $data object for each day
                            array_push($future_dates, $curr_date);
                            $fo_size = sizeof($future_dates) - 1;
                            $dayofweek = date('l', strtotime($curr_date));
                            $data[$fo_size]['date'] = $curr_date;
                            $data[$fo_size]['dayofweek'] = $dayofweek;
                            $data[$fo_size]['avg_index'] = 0;
                            $data[$fo_size]['avg_temp'] = 0;
                            $data[$fo_size]['avg_temp_min'] = 0;
                            $data[$fo_size]['avg_temp_max'] = 0;
                            $data[$fo_size]['weather_condition'] = ucfirst($forecastlist[$i]['weather'][0]['description']);
                        }
                        $data[$fo_size]['avg_index'] +=1;
                        $data[$fo_size]['avg_temp'] += $forecastlist[$i]['main']['temp'];
                        $data[$fo_size]['avg_temp_min'] += $forecastlist[$i]['main']['temp_min'];
                        $data[$fo_size]['avg_temp_max'] += $forecastlist[$i]['main']['temp_max'];
                    }
                }
                  // Average out temperature values for each day
                foreach ($data as $main) {
                    $main['avg_temp'] = kelvinToFahrenheit($main['avg_temp']/$main['avg_index']);
                    $main['avg_temp_min'] = kelvinToFahrenheit($main['avg_temp_min']/$main['avg_index']);
                    $main['avg_temp_max'] = kelvinToFahrenheit($main['avg_temp_max']/$main['avg_index']);
                    for ($i=0; $i < count($forecast_obj); $i++) {
                        $element = $forecast_obj[$i];
                        if (stripos($main['weather_condition'], $element['condition'])!== false) {
                            $main['element'] = $element;
                            break;
                        } else {
                            $main['element'] = $forecast_obj[1];  // If condition not met, show cloudy
                        }
                    }
                    array_push($json['forecast'], $main);
                }

                  $json['error']['forecast'] = $res->getStatusCode();
                  Cache::put('weather'.$system_id, json_encode($json), 120);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                  $response = $e->getResponse()->getStatusCode();
                  log::info($response."Invalid response from Weather API.");
                  $json['error'] = $response;
            }
            return $json;
        }

        if (Cache::has('weather'.$system_id)) {
            if ($CheckCache == 'true') {
                $json = json_decode(Cache::get('weather'.$system_id));
            } else {
                $json = grabNewData($building_id, $system_id);
            }
        } else {
            $json = grabNewData($building_id, $system_id);
        }
        return Response::json($json);
    }

  /**
   * Responde to background calls for chart data
   * @param  integer $building_id
   * @param  integer $system_id
   * @return string A json encoded array of the data being requested
   */
    public function Chartajax($building_id, $system_id)
    {
        $json = [];
        //for missing devices. This will open a modal in the view that will print out last report value
        if (Input::has('missing')) {
            $dev_id = Input::get("id");
            $dev_command = Input::get("command");
            $device_data = DB::table("device_data_current")
                          ->select('datetime')
                          ->where('system_id', $system_id)
                          ->where('id', $dev_id)
                          ->where('command', $dev_command)
                          ->first();
            $json['date'] = $device_data->datetime;
        } //for devices that have data. This will graph the data
        else {
            $system  = System::find($system_id);
            $startfetchDate = Input::get('startdateToFetch');
            $endfetchDate = Input::get('enddateToFetch');
            //For digital, IDs = DeviceID, for analog IDs = zoneID
            $zoneID = Input::get('zoneID');
            $dev_id_name = Input::get('dev_id_name');
  
  
            if (! ($startfetchDate = new DateTime($startfetchDate))) {
                $json['error'] = ['Cannot make date from startdateToFetch value'];
            }
            if (! ($endfetchDate = new DateTime($endfetchDate))) {
                $json['error'] = ['Cannot make date from enddateToFetch value'];
            }
            if ($endfetchDate < $startfetchDate) {
                Session::flash('error', '<strong>Whoops</strong> It looks like the Start Date is after the End Date.');
                return Redirect::back();
            }
            if (gettype($startfetchDate) === 'object' && gettype($endfetchDate) === 'object') {  //if date is the right object type
                // Start getting data
                $function_type = Input::get('dataFunction');
                $digital = Input::get('Digital');
                //$function_type = "Relay";
                $startTime = date_format($startfetchDate, 'Y-m-d');
                $endTime = date_format($endfetchDate, 'Y-m-d');
                $json['starttime'] = $startTime;
                $json['endtime'] = $endTime;
                /**
         * The `device_data` table only holds two weeks of data. Anything older
         * than that will be in the archives table 'device_data_long_term'
         *
         * The `device_data_hourly_ave` table only holds hourly data. If asked for more than 7 days data,
         * 'device_data_hourly_ave' will be used.
         */
                /**
         * Use a subquery to look up commands that match this data type. Only
         * those commands will be selected from `device_data`.
        */
                /**
         * Use a subquery to look up active device id's for the system
        */
                $currentdatediff = date_diff($startfetchDate, new DateTime());
                //get enddate-startdate value. If current date daterange = 0;
                $daterange = date_diff($startfetchDate, $endfetchDate)->days;
                //Check if digital or analog. `device_data_hourly_ave` table only contains analog values
  
                $device_data = array();
                //================= For Digital device type ===========================
                if ($digital) { //Digital
                    $startTime = date_format($startfetchDate, 'Y-m-d 00:00:00');
                    $endTime = date_format($endfetchDate, 'Y-m-d 23:59:59');
  
                    if (Input::get('timeVtime') == "true") {  //time vs time bar chart
                        if (!Cache::has('D3events'.$system_id.$zoneID.$startTime.$endTime.'timeVtime')) {
                            $device_data = DB::table('events')
                              ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as duration, DATE(created_at) as created_date, device_id'))
                              ->where('system_id', $system_id)
                              ->where('cleared_at', '<=', $endTime)
                              ->where('created_at', '>=', $startTime)
                              ->where('id', '>', 0)
                              ->whereIn('device_id', function ($subquery2) use ($zoneID, $system_id, $function_type) {
                                  $subquery2->from('devices')
                                  ->select('id')
                                  ->where('system_id', $system_id)
                                  //->where('zone', $zoneID)
                                  ->where('retired', 0)
                                  ->whereIn('device_function', $function_type);
                              })
                                          ->groupBy(DB::raw('DATE(created_at)'), 'device_id')
                                          ->orderBy('created_at', 'asc')
                                          ->get();
    
                            $device_data_line = DB::table('device_data_hourly_ave')
                                                ->select('datetime', 'current_value', 'setpoint', 'id')
                                                ->where('system_id', $system_id)
                                                ->where('datetime', '>=', $startTime)
                                                ->where('datetime', '<=', $endTime)
                                                ->where('id', '>', 0)
                                                ->whereIn('command', function ($subquery1) {
                                                    $subquery1->from('device_types')
                                                    ->select('command')
                                                    ->where('function', 'Temperature');
                                                })
                                                ->whereIn('id', function ($subquery2) use ($system_id) {
                                                    $subquery2->from('devices')
                                                    ->select('id')
                                                    ->where('system_id', $system_id)
                                                    ->where('name', 'Outside');
                                                })
                                                ->orderBy('unix_time', 'ASC')
                                                ->get();
  
                            $daysArray = [];
                            $json["device_names"] = [];
                            $json['lines'] = []; //Line chart data
                            //Temperature data
                            foreach ($device_data_line as $data_point) {
                                          $cur_time = $data_point->datetime;
                                          /**
                 * Temperature data has the option of being converted to Farenheit.
                 * Whether it's converted or not depends on the
                 * `system`.`temperature_format` field containing a 'C' or an 'F'
                 */
                                if ($system->temperature_format == 'F') {
                                      $data_point->current_value = ConvFunc::convertCelciusToFarenheit($data_point->current_value);
                                      $data_point->setpoint = ConvFunc::convertCelciusToFarenheit($data_point->setpoint);
                                }
                                          array_push($json['lines'], array('name' => "Outside".'('.$data_point->id.')', "time" => $cur_time, 'temp' => $data_point->current_value, 'setpoint'=> $data_point->setpoint));
                            }

                            //Bar chart data
                            foreach ($device_data as $data_point) {
                                          // Device data should always be within the provided date range
                                if ($data_point->created_date <= $endTime) {
                                    $device_name = '';
                                    for ($j=0; $j < count($dev_id_name["dev_name"]); $j++) {
                                        if ($data_point->device_id == $dev_id_name["dev_id"][$j]) {
                                            $device_name = $dev_id_name["dev_name"][$j];
                                            break;
                                        }
                                    }
                                    //make an array to store each day
                                    if (!in_array($data_point->created_date, $daysArray)) {
                                        array_push($daysArray, $data_point->created_date);
                                        $json['bars'][count($daysArray)-1] = [];
                                    }
                                    $index = array_search($data_point->created_date, $daysArray);
                                    //seperate out each day
                                    array_push($json['bars'][$index], array('name' => $device_name.'('.$data_point->device_id.')', 'duration' => $data_point->duration, 'Day'=> $data_point->created_date));
                                    // array for all the device names
                                    if (!in_array($device_name.'('.$data_point->device_id.')', $json["device_names"])) {
                                        array_push($json["device_names"], $device_name.'('.$data_point->device_id.')');
                                    }
                                }
                            }
                            if (!empty($json['lines'])) {
                                          array_push($json["device_names"], 'Outside');
                                          array_push($json["device_names"], 'Setpoint');
                            }
                            Cache::put('D3events'.$system_id.$zoneID.$startTime.$endTime.'timeVtime', json_encode($json), 10);
                        } else {
                            $json = json_decode(Cache::get('D3events'.$system_id.$zoneID.$startTime.$endTime.'timeVtime'));
                        }
                        //Also add line chart name into array
                        //array_push($json["device_names"], $dev_name_line);
                    } else {
                        if (!Cache::has('D3events'.$system_id.$zoneID.$startTime.$endTime)) {
                            $device_data = DB::table('events')
                                ->where('system_id', $system_id)
                                ->where('cleared_at', '>=', $startTime)
                                ->where('created_at', '<=', $endTime)
                                ->where('id', '>', 0)
                                ->whereIn('device_id', function ($subquery2) use ($zoneID, $system_id, $function_type) {
                                    $subquery2->from('devices')
                                    ->select('id')
                                    ->where('system_id', $system_id)
                                    ->where('zone', $zoneID)
                                    ->where('retired', 0)
                                    ->whereIn('device_function', $function_type);
                                })
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                            $Devices = [];
                            foreach ($device_data as $data_point) {
                                          //device name
                                          $device_name = '';
                                for ($j=0; $j < count($dev_id_name["dev_name"]); $j++) {
                                    if ($data_point->device_id == $dev_id_name["dev_id"][$j]) {
                                        $device_name = $dev_id_name["dev_name"][$j];
                                        break;
                                    }
                                }
                                          //$device_name = $dev_id_name['name'];
                                          // Make an array to store each devices data if needed
                                if (!in_array($device_name.$data_point->device_id, $Devices)) {
                                    array_push($Devices, $device_name.$data_point->device_id); //push new device into Devices array
                                    $TotDevice = count($Devices);
                                    $json['data'][$TotDevice-1]['Data'] = [];
                                    $json['DeviceNameForLegend'] = [];
                                }
                                          $index = array_search($device_name.$data_point->device_id, $Devices);        //find the index for current device
                                          $json['data'][$index]['name'] = $device_name.'('.$data_point->device_id.')';
                                          $json['data'][$index]['zone'] = $zoneID;
                                          /* Add 0 to created_at-1 location, 1 at created_at location.
                                            Add 1 to cleared_at location, 0 at cleared_at+1 location.
                                          */
                                if (isset($json['data'][$index]['Data'])) {     //check if the array is initialized for that device
                                          $data_count= count($json['data'][$index]['Data']);
                                          //Add data
                                          $json['data'][$index]['Data'][$data_count]['startdate'] = $data_point->created_at;
                                          $json['data'][$index]['Data'][$data_count]['enddate'] = $data_point->cleared_at;
                                          $json['data'][$index]['Data'][$data_count]['description'] = $data_point->description;
                                          $json['data'][$index]['Data'][$data_count]['duration'] = $data_point->duration;
                                }
    
                                          // Add DeviceName in Array
                                if (!in_array($device_name.'('.$data_point->device_id.')', $json['DeviceNameForLegend'], true)) {
                                      array_push($json['DeviceNameForLegend'], $device_name.'('.$data_point->device_id.')');
                                }
                            }
                            Cache::put('D3events'.$system_id.$zoneID.$startTime.$endTime, json_encode($json), 10);
                        } else {
                            $json = json_decode(Cache::get('D3events'.$system_id.$zoneID.$startTime.$endTime));
                        }
                    }
                } //================= For Analog function type ===========================
                else {
                    if (!Cache::has('D3DeviceData'.$system_id.$zoneID.$startTime.$endTime)) {
                        //===================Pick the right database table ====================
                        if ($currentdatediff->days <= 14) {  //first two weeks
                            $data_table_name = $daterange <=2 ? 'device_data' : 'device_data_hourly_ave';
                        } else {
                            $data_table_name = $daterange <=2 ? 'device_data_long_term' : 'device_data_hourly_ave';
                        }
    
                        if ($daterange > 300) {
                            //getting day average
                            $device_data = DB::table($data_table_name)
                            ->select(DB::raw("DATE_FORMAT(datetime, \"%Y-%m-%d\") as Days, AVG(current_value) as current_value, AVG(setpoint) as setpoint"), 'id', 'system_id', 'command', 'datetime')
                            ->where('system_id', $system_id)
                            ->where('date', '>=', $startTime)
                            ->where('date', '<=', $endTime)
                            ->where('id', '>', 0)
                            ->whereIn('command', function ($subquery1) use ($function_type) {
                                $subquery1->from('device_types')
                                ->select('command')
                                ->where('function', $function_type);
                            })
                              ->whereIn('id', function ($subquery2) use ($zoneID, $system_id) {
                                            $subquery2->from('devices')
                                            ->select('id')
                                            ->where('system_id', $system_id)
                                            ->where('zone', $zoneID);
                              })
                              ->orderBy('Days', 'ASC')
                              ->groupBy('Days', 'id', 'system_id', 'command')
                              ->get();
                        } else {
                            $device_data = DB::table($data_table_name)
                            ->where('system_id', $system_id)
                            ->where('date', '>=', $startTime)
                            ->where('date', '<=', $endTime)
                            ->where('id', '>', 0)
                            ->whereIn('command', function ($subquery1) use ($function_type) {
                                $subquery1->from('device_types')
                                ->select('command')
                                ->where('function', $function_type);
                            })
                              ->whereIn('id', function ($subquery2) use ($zoneID, $system_id) {
                                            $subquery2->from('devices')
                                            ->select('id')
                                            ->where('system_id', $system_id)
                                            ->where('zone', $zoneID);
                              })
                              ->orderBy('unix_time', 'ASC')
                              ->get();
                        }
    
                        $Devices = [];
                        for ($i=0; $i < count($device_data); $i++) {
                            $data_point = $device_data[$i];
                            $device_name = '';
                            for ($j=0; $j < count($dev_id_name["dev_name"]); $j++) {
                                if ($data_point->id == $dev_id_name["dev_id"][$j]) {
                                    $device_name = $dev_id_name["dev_name"][$j];
                                    break;
                                }
                            }
                            $cur_time = $data_point->datetime;
                            /**
               * Temperature data has the option of being converted to Farenheit.
               * Whether it's converted or not depends on the
               * `system`.`temperature_format` field containing a 'C' or an 'F'
               */
                            if ($function_type === 'Temperature' && $system->temperature_format == 'F') {
                                $data_point->current_value = ConvFunc::convertCelciusToFarenheit($data_point->current_value);
                            }
    
                            //initialize Data array for each device
                            if (!in_array($device_name.$data_point->id, $Devices)) {
                                array_push($Devices, $device_name.$data_point->id); //push new device into Devices array
                                $TotDevice = count($Devices);
                                $json['data'][$TotDevice-1]['Data'] = [];
                            }
                            $index = array_search($device_name.$data_point->id, $Devices);        //find the index for current device
                            $json['data'][$index]['name'] = $device_name.'('.$data_point->id.')';
                            $json['data'][$index]['zone'] = $zoneID;
    
                            if (isset($json['data'][$index]['Data'])) {     //check if the array is initialized for that device
                                $uniqueDeviceSize = count($json['data'][$index]['Data']);   //Last index of "Data" array
                                $json['data'][$index]['Data'][$uniqueDeviceSize]['time'] = $cur_time;
                                $json['data'][$index]['Data'][$uniqueDeviceSize]['value'] = floatval($data_point->current_value);
                            }
                        }
                        Cache::put('D3DeviceData'.$system_id.$zoneID.$startTime.$endTime, json_encode($json), 10);
                    } else {
                        $json = json_decode(Cache::get('D3DeviceData'.$system_id.$zoneID.$startTime.$endTime));
                    }
                }
                // Done getting data
            } else {
                $json['error'] = ['fetchDate is not a DateTime object'];
            }
        }
        // The response is sent back as JSON and handled by JS in the page
        return Response::json($json);
    }

  /*--------------------------------------------------------------------------
  * Trigger creation of system shutdown message
  --------------------------------------------------------------------------*/
    public function systemShutdown($bid, $sid)
    {
        SystemLog::info($sid, 'Generated System Shutdown file for system #'.$sid, 5);
        RemoteTask::deployFile($sid, '/var/2020_command', "xx_ss.emc", "shutdown now\n");

        /*
        TODO:  Log deployFile.
        */
        //return "Completed shutdown command send.";
    }

  /*--------------------------------------------------------------------------
  |
  | Device Status page
  |-------------------------------------------------------------------------*/
    public function devicestatus($id, $sid, $dashboard_id = 0, $parent = 0)
    {
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
        $thisBldg = Building::find($id); // Lookup info for selected building

        $devicesout = Device::where('system_id', $sid) // Lookup devices which are outputs
        ->where('device_io', 'output')
        ->where('status', '1')
        ->where('retired', '<>', '1')
        ->orderby('zone')
        ->orderby('product_id')
        ->orderby('id')
        ->get();

        $devicesin = Device::where('system_id', $sid) // Lookup devices which are intputs
        ->where('device_io', 'input')
        ->where('status', '1')
        ->where('retired', '<>', '1')
        ->orderby('zone')
        ->orderby('product_id')
        ->orderby('id')
        ->get();

        //$thisBldg = Building::find($id);            // Lookup passed building from DB
        $thisSystem = System::find($sid);           // Lookup passed system from DB
        $systems = System::where('building_id', $thisBldg->id)->get();
        $products = ProductType::all();             // Lookup all products available to this system
        $zonenames = Zone::where('system_id', $sid)->get();
        return View::make('buildings.status.devicestatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
        ->with('devicesout', $devicesout)
        ->with('devicesin', $devicesin)
        ->with('systemsData', $systems)
        ->with('parent', DashboardItem::find($dashboard_id))
        ->with('products', $products)
        ->with('zonename', $zonenames)
        ->with('items', $items);
    }


  /*--------------------------------------------------------------------------
  |
  | Zone Status page
  |-------------------------------------------------------------------------*/

    public function zonestatus($id, $sid, $dashboard_id = 0, $parent = 0)
    {
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
        $thisBldg = Building::find($id); // Lookup passed building from DB
        $thisSystem = System::find($sid); // Lookup passed system from DB
        $currentTime = time();
        $input = Input::except('_token');

        /* Check for any web commands instructed by User. */
        if (isset($input['Toggle'])) {
            BuildingController::DeployInstruction($id, $sid, 0, $input);
        } elseif (isset($input['Bypass'])) {  // when bypass button pressed
            $mappingOutput = MappingOutput::where('system_id', $sid)
                                    ->where('device_id', $input['device'])
                                    ->where('device_type', $input['command'])
                                    ->first();

            $mappingOutput->overridetime = $input['Overridetime'];
            $mappingOutput->updated_at = $currentTime;
            $mappingOutput->save();

            BuildingController::DeployInstruction($id, $sid, 1, $input);
            // return Redirect::route("zonestatus", [$id, $sid]);
        }
    
        $systemTemperature = $thisSystem->temperature_format;

        $devicesout = Device::where('system_id', $sid) // Lookup devices which are outputs
        ->join('product_types', 'product_types.product_id', '=', 'devices.product_id')
        ->where('device_io', 'output')
        ->where('status', '1')
        ->where('retired', '0')
        ->select('devices.*', 'product_types.commands AS product_commands')
        ->get();

        $devicesin = Device::where('system_id', $sid) // Lookup devices which are inputs
        ->join('product_types', 'product_types.product_id', '=', 'devices.product_id')
        ->where('device_io', 'input')
        ->where('status', '1')
        ->where('retired', '0')
        ->select('devices.*', 'product_types.commands AS product_commands')
        ->get();

        $devicesCurrent = DeviceDataCurrent::where('system_id', $sid)
        ->orderby('datetime', 'DESC')
        ->groupby('command', 'id')
        ->get();

        $alarms = DB::table('alarms')
        ->where('system_id', $sid)
        ->where('active', 1)
        ->orderby('device_id', 'asc')
        ->orderby('created_at', 'desc')
        ->get();

        $alarm_codes = DB::table('alarm_codes')->get();
    
        $timeStampsArray = array();
        $devicesOutCurrent = array();

        $device_types = DB::table('device_types')->get();
        $command_units = array();
        foreach ($device_types as $types) {
            $command_units[$types->command] = [$types->units,$types->function];
        }
        $products = ProductType::all();
        $product_types = array();
        foreach ($products as $product) {
            $product_types[$product->product_id] = $product;
        }
        /*Create a struct for input devices*/
        foreach ($devicesin as $indev) {
            $input_dev_struct[$indev->id] = [
            'id'=>$indev->id,
            'name'=>$indev->name,
            'mac_address'=>$indev->mac_address,
            'physical_location'=>$indev->physical_location,
            'status'=>$indev->status,
            'inhibited'=>$indev->inhibited,
            'retired'=>$indev->retired,
            'zone'=>$indev->zone,
            'alarm_code'=>0,
            'alarm_severity'=>0,
            'product_commands'=>$indev->product_commands,
            'command' => []
            ];
            foreach ($devicesCurrent as $ddc) {
                if ($indev->id == $ddc->id) {
                    $dev_data = [
                    'id'=>$ddc->command,
                    'units'=>$command_units[$ddc->command][0],
                    'function'=>$command_units[$ddc->command][1],
                    'current_state'=>$ddc->current_state,
                    'current_value'=>$ddc->current_value,
                    'last_report'=>$ddc->datetime,
                    'setpoint'=>$ddc->setpoint,
                    'alarm_severity'=>$ddc->alarm_state,
                    'alarm_code'=>$ddc->alarm_index,
                    ];
                    //$timeStampsArray[$ddc->id][$ddc->command] = $ddc->datetime;
                    //$devicesOutCurrent[$ddc->id][$ddc->command] = $ddc;
                    $input_dev_struct[$indev->id]['command'][$ddc->command] = $dev_data;
                }
            }
        }


        foreach ($devicesCurrent as $currentOut) {
            $timeStampsArray[$currentOut->id][$currentOut->command] = $currentOut->datetime;
            $devicesOutCurrent[$currentOut->id][$currentOut->command] = $currentOut;
        }

        //$systems = System::where('id',$thisSystem->id)->get();
        $buliding_id_holder = $thisSystem->building_id;

        $zones = Zone::where('system_id', $thisSystem->id)->orderby('zone')->get();
        $zonename = array();
        $outputzone = 0;
        foreach ($zones as $theZones) {
            foreach ($devicesout as $do) {
                if ($do->zone == $theZones->zone) {
                    $outputzone = 1;
                }
            }
            array_push($zonename, array($theZones->zone, $theZones->zonename, $outputzone));
            $outputzone = 0;
        }

        $mappingout = MappingOutput::where('system_id', $thisSystem->id)
                                    ->orderby('device_id')->get();
        $mappingOutputs = array();
        foreach ($mappingout as $output) {
            $mappingOutputs[$output->device_id] = $output;
        }

        return View::make('buildings.status.zonestatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
        ->with('ActiveAlarms', $alarms)
        ->with('AlarmCodes', $alarm_codes)
        ->with('devicesout', $devicesout)
        ->with('InputDevices', $input_dev_struct)
        ->with('SysTemperatureFormat', $systemTemperature)
        ->with('parent', DashboardItem::find($dashboard_id))
        ->with('ZoneNames', $zonename)
        ->with('timestampsArray', $timeStampsArray)
        ->with('devicesOutCurrent', $devicesOutCurrent)
        ->with('currentTime', $currentTime)
        ->with('mappingOutputs', $mappingOutputs)
        ->with('Building', $buliding_id_holder)
        ->with('System', $sid)
        ->with('CurrentDeviceData', $devicesCurrent)
        ->with('DeviceTypes', $device_types)
        ->with('items', $items);
    }
  /*--------------------------------------------------------------------------
  |
  | Alarm Status page
  |-------------------------------------------------------------------------*/

    public function alarmstatus($id, $sid, $dashboard_id = 0, $parent = 0)
    {
        $thisBldg = Building::find($id); // Lookup passed building from DB
        $thisSystem = System::find($sid); // Lookup passed system from DB
        $systems = System::where('building_id', $thisBldg->id)->get();
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

        $take = 20;
        $pageH = Input::get('PageH') ? Input::get('PageH') : 1;
        $pageA = Input::get('PageA') ? Input::get('PageA') : 1;

        $filter="All"; //for initialization
        $input = Input::except('_token');

        if (isset($input['All'])) {
            $filter=$input['All'];
            unset($input['All']);
            $pageH=1;
            $pageA=1;
        }

        if (isset($input['Priority'])) {
            $filter=$input['Priority'];
            unset($input['Priority']);
            $pageH=1;
            $pageA=1;
        }

        if (isset($input['System'])) {
            $filter=$input['System'];
            unset($input['System']);
            $pageH=1;
            $pageA=1;
        }

        if (isset($input['Sensors'])) {
            $filter=$input['Sensors'];
            unset($input['Sensors']);
            $pageH=1;
            $pageA=1;
        }


        if (isset($input['Controls'])) {
            $filter=$input['Controls'];
            unset($input['Controls']);
            $pageH=1;
            $pageA=1;
        }

        if (isset($input['PrevH'])) {
            $filter=$input['PrevH'];
            unset($input['PrevH']);
            if ($pageH>1) {
                $pageH = $pageH-1;
            } else {
                $pageH = 1;
            }
        }

        if (isset($input['NextH'])) {
            $filter=$input['NextH'];
            unset($input['NextH']);
            $pageH=$pageH+1;
        }

        if (isset($input['PrevA'])) {
            $filter=$input['PrevA'];
            unset($input['PrevA']);
            if ($pageA > 1) {
                $pageA = $pageA-1;
            } else {
                $pageA = 1;
            }
        }

        if (isset($input['NextA'])) {
            $filter=$input['NextA'];
            unset($input['NextA']);
            $pageA=$pageA+1;
        }
        if (isset($input['Tab'])) {
            unset($input['Tab']);
            foreach ($input as $key => $value) {
                $temp = explode(":", $key);
                if ($temp=="A") {
                    $pactive = 1;
                } else {
                    $pactive = 0;
                }
            }
            $page=$page+1;
        }

        $skipH = ($pageH - 1) * $take;
        $skipA = ($pageA - 1) * $take;
        if (isset($input['Alarm'])) {
            unset($input['Alarm']);
            // for each device retrieve from db, update fields, and save
            foreach ($input as $key => $value) {
                $temp = explode(":", $key);
                // first find created_at
                $AlarmsCreate = Alarms::where('id', $temp[1])->get();
                foreach ($AlarmsCreate as $CD) {
                    $CreateDate=$CD->created_at;
                }
                $date=date_create();
                $now = time();
                date_timestamp_set($date, $now);
                $create=date_format($date, "Y-m-d H:i:s");
                $duration=ConvFunc::sec2hms($now-strtotime($CreateDate));
                DB::table('alarms')
                ->where('id', $temp[1])
                ->update(array('resolution' => 'Manual','active'=>0,'cleared_at'=>$create,
                'updated_at'=>$create,'duration'=>$duration));
            }
        }

        // first calculate total records for active
        if ($filter=="All") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
            ->where('active', 1)
            ->count();
        } elseif ($filter == "1") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
             ->leftJoin('device_setpoints', function ($join) {
                $join->on('alarms.device_id', '=', 'device_setpoints.device_id')
                ->on('alarms.command', '=', 'device_setpoints.command');
             })
             ->where('device_setpoints.system_id', $sid)
             ->where('device_setpoints.priority_alarms', 1)
             ->where('alarms.device_id', '<>', 0)
             ->where('active', 1)
             ->count();
        } elseif ($filter=="0") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
            ->where('device_id', $filter)
            ->where('active', 1)
            ->count();
        } else {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
             ->join('devices', 'devices.id', '=', 'alarms.device_id')
             ->where('devices.system_id', $sid)
             ->where('devices.device_io', $filter)
             ->where('device_id', '<>', 0)
             ->where('active', 1)
             ->count();
        }
        $reccountActive=$sysAlarmsActive;

        if ($filter=="All") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
            ->where('active', 0)
            ->count();
        } elseif ($filter == "1") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
             ->leftJoin('device_setpoints', function ($join) {
                $join->on('alarms.device_id', '=', 'device_setpoints.device_id')
                ->on('alarms.command', '=', 'device_setpoints.command');
             })
             ->where('device_setpoints.system_id', $sid)
             ->where('device_setpoints.priority_alarms', 1)
             ->where('alarms.device_id', '<>', 0)
             ->where('active', 0)
             ->count();
        } elseif ($filter=="0") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
            ->where('device_id', $filter)
            ->where('active', 0)
            ->count();
        } else {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
             ->join('devices', 'devices.id', '=', 'alarms.device_id')
             ->where('devices.system_id', $sid)
             ->where('devices.device_io', $filter)
             ->where('device_id', '<>', 0)
             ->where('active', 0)
             ->count();
        }
        $reccountHist=$sysAlarmsHist;

        if ($filter=="All") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
            ->where('active', 1)
            ->orderby('alarms.created_at', 'desc')
            ->skip($skipA)
            ->take($take+1)
            ->get();
        } elseif ($filter == "1") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
             ->leftJoin('device_setpoints', function ($join) {
                $join->on('alarms.device_id', '=', 'device_setpoints.device_id')
                ->on('alarms.command', '=', 'device_setpoints.command');
             })
              ->where('device_setpoints.system_id', $sid)
              ->where('device_setpoints.priority_alarms', 1)
              ->where('alarms.device_id', '<>', 0)
              ->where('active', 1)
              ->select('device_setpoints.*', 'alarms.*', 'alarms.created_at AS created_at')
              ->orderby('alarms.created_at', 'DESC')
              ->skip($skipA)
              ->take($take+1)
              ->get();
        } elseif ($filter=="0") {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
            ->where('device_id', $filter)
            ->where('active', 1)
            ->orderby('alarms.created_at', 'desc')
            ->skip($skipA)
            ->take($take+1)
            ->get();
        } else {
            $sysAlarmsActive = Alarms::where('alarms.system_id', $sid)
            ->join('devices', 'devices.id', '=', 'alarms.device_id')
            ->where('devices.system_id', $sid)
            ->where('devices.device_io', $filter)
            ->where('device_id', '<>', 0)
            ->where('active', 1)
            ->select('devices.*', 'alarms.*', 'alarms.created_at AS created_at')
            ->orderby('alarms.created_at', 'desc')
            ->skip($skipA)
            ->take($take+1)
            ->get();
        }

        if ($filter=="All") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
            ->where('active', 0)
            ->orderby('alarms.cleared_at', 'desc')
            ->skip($skipH)
            ->take($take+1)
            ->get();
        } elseif ($filter == "1") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
            ->leftJoin('device_setpoints', function ($join) {
                $join->on('alarms.device_id', '=', 'device_setpoints.device_id')
                ->on('alarms.command', '=', 'device_setpoints.command');
            })
             ->where('device_setpoints.system_id', $sid)
             ->where('device_setpoints.priority_alarms', 1)
             ->where('alarms.device_id', '<>', 0)
             ->where('active', 0)
             ->select('device_setpoints.*', 'alarms.*', 'alarms.created_at AS created_at')
             ->orderby('alarms.cleared_at', 'DESC')
             ->skip($skipA)
             ->take($take+1)
             ->get();
        } elseif ($filter=="0") {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
            ->where('device_id', $filter)
            ->where('active', 0)
            ->orderby('alarms.cleared_at', 'desc')
            ->skip($skipH)
            ->take($take+1)
            ->get();
        } else {
            $sysAlarmsHist = Alarms::where('alarms.system_id', $sid)
             ->join('devices', 'devices.id', '=', 'alarms.device_id')
             ->where('devices.system_id', $sid)
             ->where('devices.device_io', $filter)
             ->where('device_id', '<>', 0)
             ->orderby('alarms.cleared_at', 'desc')
             ->where('active', 0)
             ->skip($skipH)
             ->take($take+1)
             ->get();
        }

        // update resolution field for active devices if clear button is clicked
         return View::make('buildings.status.alarmstatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
                ->with('parent', $parent)
                ->with('sysAlarmsActive', $sysAlarmsActive)
                ->with('sysAlarmsHist', $sysAlarmsHist)
                ->with('systemsData', $systems)
                ->with('parent', DashboardItem::find($dashboard_id))
                ->with('Filter', $filter)
                ->with('PageA', $pageA)
                ->with('PageH', $pageH)
                ->with('TotalA', $reccountActive)
                ->with('TotalH', $reccountHist)
                ->with('Building', $thisBldg)
                ->with('System', $thisSystem)
                ->with('items', $items);
    }

  /*
  |--------------------------------------------------------------------------
  | EventsStatus Functions
  |--------------------------------------------------------------------------
  |
  */

  /*
  duration_totalling:
  Receives an array of duration times, of the format HH:MM:SS. Returns an integer total, in seconds
  */
    public static function duration_totalling($add_dur_time_array)
    {
        $sum_in_seconds = 0;
        foreach ($add_dur_time_array as $duration) {
            $sum_in_seconds = $sum_in_seconds + BuildingController::duration_to_time($duration);
        }
        return $sum_in_seconds;
    }
  /*
    duration_to_time:
    Receives a single duration time, of the format HH:MM:SS. Returns the equivalent time, in seconds.
  */
    public static function duration_to_time($duration)
    {
        $boom = explode(':', $duration);
        for ($i = 0; $i < 3; $i++) {
            if (!isset($boom[$i])) {
                $boom[] = 0;
            }
        }
        return ((int)$boom[2] + ((int)$boom[1] * 60) + ((int)$boom[0] * 3600));
    }

  /*
    string_to_time:
    Receives a string, in the mysql DB's format, and returns the UNIX time equivalent.
  */
    public static function string_to_time($string)
    {
        $split = explode(' ', $string);
        $upper = explode('-', $split[0]);
        $lower = explode(':', $split[1]);
        return mktime($lower[0], $lower[1], $lower[2], $upper[1], $upper[2], $upper[0]);
    }
  /*
    create_duration:
    Receives a start_time and end_time, in seconds. Returns the difference of the two times, in duration format.
  */
    public static function create_duration($start_time, $end_time)
    {
        $duration = '00:00:00';
        if ($start_time > $end_time) {
            /*return 0-time*/
            return $duration;
        }
        /*else..*/
        $difference = $end_time - $start_time;

        return ConvFunc::sec2hms($difference);
    }

  /*
    eventsstatus:
    Generates data for eventsstatus.blade.php view.
  */
    public function eventstatus($id, $sid, $dashboard_id = 0, $parent = 0)
    {
        /*timing, for testing*/
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;

        $timezone = "America/New_York"; // temporaryyy
        date_default_timezone_set($timezone);

        $thisSystem = System::find($sid); // Lookup passed system from DB
        $thisBldg = Building::find($id); // Lookup info for selected building
        $systems = System::where('building_id', $thisBldg->id)->get();

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

        $default_filter = "All";

        $expand['active'] = ' ';
        $expand['history'] = ' ';
        $expand['custom_time_frame'] = ' ';
        $take = 30;
        $takelimit = ($take * 280);
        $custom_start_date = null;
        $custom_end_date = null;
        $custom_alg_report = null;

        $filter = Input::get('EventAlg') ? Input::get('EventAlg') : $default_filter;
        $input = Input::except('_token');

        if (Input::get('pageA')) {
            $pageA = Input::get('PageA');
            $expand['active'] = 'in';
        } else {
            $pageA =  1;
        }
        if (Input::get('PageH')) {
            $pageH = Input::get('PageH');
            $expand['history'] = 'in';
        } else {
            $pageH = 1;
        }

        /*DETERMINE WHICH PAGE (OF RESULTS) ARE TO BE DISPLAYED*/
        if (isset($input['NextA'])) {
            $filter = $input['NextA'];
            unset($input['NextA']);
            $pageA = $pageA + 1;
        } else if (isset($input['PrevA'])) {
            $filter=$input['PrevA'];
            unset($input['PrevA']);
            if ($pageA > 1) {
                $pageA = $pageA - 1;
            }
        } else if (isset($input['NextH'])) {
            $filter = $input['NextH'];
            unset($input['NextH']);
            $pageH = $pageH + 1;
        } else if (isset($input['PrevH'])) {
            $filter=$input['PrevH'];
            unset($input['PrevH']);
            if ($pageH > 1) {
                $pageH = $pageH - 1;
            }
        }

        $skipH = ($pageH - 1) * $take;
        $skipA = ($pageA - 1) * $take;


        if (isset($input['Event'])) {
            unset($input['Event']);

            // for each device retrieve from db, update fields, and save
            foreach ($input as $key => $value) {
                $temp = explode(":", $key);
                // first find created_at
                $EventsCreate = Events::where('id', $temp[1])->get();
                foreach ($EventsCreate as $CD) {
                    $CreateDate=$CD->created_at;
                }
                $date=date_create();
                $now = time();
                date_timestamp_set($date, $now);
                $create=date_format($date, "Y-m-d H:i:s");

                $duration=ConvFunc::sec2hms($now-strtotime($CreateDate));

                DB::table('Events')
                ->where('id', $temp[1])
                ->update(array('resolution' => 'Manual','active'=>0,'cleared_at'=>$create,'updated_at'=>$create,'duration'=>$duration));
            }
        }

        if ($filter=="All") {/**************************************************************/
            $sysEventsActive = Events::where('events.system_id', $sid)
            ->where('events.active', 1)
            ->orderby('events.created_at', 'desc')
            ->join('mapping_output', function ($join) {
                $join->on('mapping_output.device_id', '=', 'events.device_id');
                $join->on('mapping_output.system_id', '=', 'events.system_id');
            })
              ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
              ->limit($takelimit)
              ->get();
            $reccountActive = $sysEventsActive->count();
            $sysEventsActive = $sysEventsActive->splice($skipA, $take+1)->all();
            $sysEventsHist = Events::where('events.system_id', $sid)
              ->where('events.active', 0)
              ->orderby('events.created_at', 'desc')
              ->join('mapping_output', function ($join) {
                    $join->on('mapping_output.device_id', '=', 'events.device_id');
                    $join->on('mapping_output.system_id', '=', 'events.system_id');
              })
              ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
              ->limit($takelimit)
              ->get();
            $reccountHist = $sysEventsHist->count();
            $sysEventsHist = $sysEventsHist->splice($skipH, $take+1)->all();
            /*END FILTER == ALL ***************************************************************/
        } else if ($filter == "priority") {
            $sysEventsActive = Events::where('events.system_id', $sid)
            ->where('events.active', 1)
            ->orderby('events.created_at', 'desc')
            ->join('mapping_output', function ($join) {
                $join->on('mapping_output.device_id', '=', 'events.device_id');
                $join->on('mapping_output.system_id', '=', 'events.system_id');
            })
            ->where('mapping_output.priority_events', '1')
            ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
            ->limit($takelimit)
            ->get();
            $reccountActive = $sysEventsActive->count();
            $sysEventsActive = $sysEventsActive->splice($skipA, $take+1)->all();
            $sysEventsHist = Events::where('events.system_id', $sid)
            ->where('events.active', 0)
            ->orderby('events.created_at', 'desc')
            ->join('mapping_output', function ($join) {
                $join->on('mapping_output.device_id', '=', 'events.device_id');
                $join->on('mapping_output.system_id', '=', 'events.system_id');
            })
            ->where('mapping_output.priority_events', '1')
            ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
            ->limit($takelimit)
            ->get();
            $reccountHist = $sysEventsHist->count();
            $sysEventsHist = $sysEventsHist->splice($skipH, $take+1)->all();
            /*END FILTER == PRIORITY ***************************************************************/
        } else { /*FILTER BY DEVICE*/
            $sysEventsActive = Events::where('events.system_id', $sid)
            ->where('events.device_id', $filter)
            ->where('active', 1)
            ->orderby('events.created_at', 'desc')
            ->join('mapping_output', function ($join) {
                $join->on('mapping_output.device_id', '=', 'events.device_id');
                $join->on('mapping_output.system_id', '=', 'events.system_id');
            })
            ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
            ->limit($takelimit)
            ->get();
            $reccountActive = $sysEventsActive->count();
            $sysEventsActive = $sysEventsActive->splice($skipA, $take+1)->all();
            $sysEventsHist = Events::where('events.system_id', $sid)
            ->where('events.device_id', $filter)
            ->where('active', 0)
            ->orderby('events.created_at', 'desc')
            ->join('mapping_output', function ($join) {
                $join->on('mapping_output.device_id', '=', 'events.device_id');
                $join->on('mapping_output.system_id', '=', 'events.system_id');
            })
            ->select('mapping_output.*', 'events.*', 'events.created_at AS created_at')
            ->limit($takelimit)
            ->get();
            $reccountHist = $sysEventsHist->count();
            $sysEventsHist = $sysEventsHist->splice($skipH, $take+1)->all();
            /*END FILTER by DEVICE ***************************************************************/
        }

        $sysEventsAlg = MappingOutput::where('system_id', $sid)
        ->get();

        $algorithms = array();
        foreach ($sysEventsAlg as $alg) {
            $algorithms[$alg->id] = $alg;
        }

        /**~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ default totalling ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~**/
        $current_time = time();
        $today_time =+ $current_time - ( ((int)date('s')) + (((int)date('i'))*60) + (((int)date('H'))*60*60) );
        $yesterday_time = $today_time - (60*60*24);
        $week_time = $current_time - (60*60*24*7);
        $month_time = $current_time - (60*60*24*30);
        $yesterday_string = date('Y-m-d 00:00:00', $yesterday_time);

        $these_events = Events::where('events.system_id', $sid)
        ->where('events.updated_at', '>', $yesterday_string)
        ->orderby('events.device_id')
        ->limit('12000')
        ->get();

        $these_devices = array();
        foreach ($these_events as $te) {
            $these_devices[$te->device_id] = $te->device_id;
        }

        $event_devices = Device::where(function ($query) use ($these_devices, $sid) {
            $query->whereIn('id', $these_devices)
              ->where('retired', '0')
              ->where('system_id', $sid);
        })->orWhere(function ($query2) use ($sid) {
            $query2->where('device_io', 'output')
            ->where('retired', '0')
            ->where('system_id', $sid);
        })->get();

        $device_durations = array();
        foreach ($event_devices as $ed) {
            $device_durations[] = [
            'device_name' => $ed->name,
            'device_id' => $ed->id,
            'todays_total_duration' => 0,
            'todays_duration' => array(),
            'yesterdays_total_duration' => 0,
            'yesterdays_duration' => array()
            ];
        }
        /*fill device durations arrays with events durations*/
        foreach ($these_events as $te) {
            $created_time = BuildingController::string_to_time($te->created_at);
            $update_time = BuildingController::string_to_time($te->updated_at);

            foreach ($device_durations as $key => $dd) {
                if ($dd['device_id'] == $te->device_id) {
                    $device_durations[$key]['todays_duration'][] = '00:00:00';
                    $device_durations[$key]['yesterdays_duration'][] = '00:00:00';
                    if ($update_time > $today_time) {
                        //at least part of the event occurred today
                        if ($created_time > $today_time) {
                            //the entire event occurred today
                            if ($te->active == '1') {
                                //the event is currently active
                                if ($created_time < $today_time) {
                                    //the event began yesterday
                                    $te->duration = BuildingController::create_duration($today_time, $current_time);
                                } else {
                                    //the entire event occurred today
                                    $te->duration = BuildingController::create_duration($created_time, $current_time);
                                }
                            } else {
                                //event occurred to day, but is not currently active
                                if ($created_time < $today_time) {
                                    //the event began before today
                                    $te->duration = BuildingController::create_duration($today_time, $update_time);
                                } else {
                                    //the entire event occurred today
                                    //the previously calculated duration ($te->duration) should be accurrate
                                }
                            }
                            $device_durations[$key]['todays_duration'][] = $te->duration;
                        } else {
                            //the event began prior to today and ended today
                            if ($created_time > $yesterday_time) {
                                //the event began yesterday and ended today
                                $te->duration = BuildingController::create_duration($today_time, $update_time);
                            } else {
                                //the event began prior to yesterday and ended today
                                $te->duration = BuildingController::create_duration($today_time, $update_time);
                            }
                            $device_durations[$key]['todays_duration'][] = $te->duration;
                            if ($created_time > $yesterday_time) {
                                //the event began yesterday and ended today
                                $te->duration = BuildingController::create_duration($created_time, $today_time);
                            } else {
                                //the event began prior to yesterday and ended today
                                $te->duration = BuildingController::create_duration($yesterday_time, $today_time);
                            }
                            $device_durations[$key]['yesterdays_duration'][] = $te->duration;
                        }
                    } elseif ($update_time > $yesterday_time) {
                        //event ended yesterday
                        if ($created_time < $yesterday_time && $update_time > $today_time) {
                            $te->duration = BuildingController::create_duration($yesterday_time, $today_time);
                        } elseif ($created_time < $yesterday_time) {
                            $te->duration = BuildingController::create_duration($yesterday_time, $update_time);
                        } elseif ($update_time > $today_time) {
                            $te->duration = BuildingController::create_duration($created_time, $today_time);
                        }
                        $device_durations[$key]['yesterdays_duration'][] = $te->duration;
                    } else {
                        //the event ended prior to yesterday, so we don't care...
                    }
                }
            }
            unset($te);
        }

        foreach ($device_durations as $key => $dd) {
            $device_durations[$key]['todays_total_duration'] = BuildingController::duration_totalling($dd['todays_duration']);
            $device_durations[$key]['yesterdays_total_duration'] = BuildingController::duration_totalling($dd['yesterdays_duration']);
        }

        /**~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ end default totalling ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~**/
        /**~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ custom totalling ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~**/
        $custom_device_options = Device::where('system_id', $sid)
        ->where('device_io', 'output')
        ->where('retired', '0')
        ->select('name', 'id as device_id')
        ->get();


        /*determine if the custom totals fields have been set*/
        $custom_totals = null;
        $custom_start_date = new DateTime();
        $custom_end_date = new DateTime();
        if (Input::get('startdate') && Input::get('enddate') && Input::get('alg_id')) {
            $default_timeframe = 'none';
            if (Input::get('default_timeframe')) {
                $default_timeframe = Input::get('default_timeframe');
            }

            $custom_start_date = new DateTime(Input::get('startdate'));
            $custom_end_date = new DateTime(Input::get('enddate'));

            if ($default_timeframe != 'none') {
                if ($default_timeframe == 'week_timeframe') {
                    $custom_start_date = new DateTime(date('Y-m-d', $week_time));
                    $custom_end_date = new DateTime("now");
                } elseif ($default_timeframe == 'month_timeframe') {
                    $custom_start_date = new DateTime(date('Y-m-d', $month_time));
                    $custom_end_date = new DateTime("now");
                }
            } else {
                $expand['custom_time_frame'] = ' in ';
            }
            $custom_alg_report = Input::get('alg_id');

            $custom_events = Events::where(function ($query_x) use ($sid, $custom_alg_report, $custom_start_date, $custom_end_date) {
                $query_x->where('events.system_id', $sid)
                ->where('devices.system_id', $sid)
                ->where('events.device_id', $custom_alg_report)
                ->where('events.created_at', '>', date_format($custom_start_date, 'Y-m-d 00:00:00'))
                ->where('events.created_at', '<', date_format($custom_end_date, 'Y-m-d 23:59:59'));
            })
              ->orWhere(function ($query_y) use ($sid, $custom_alg_report, $custom_start_date, $custom_end_date) {
                  $query_y->where('events.system_id', $sid)
                  ->where('devices.system_id', $sid)
                  ->where('events.device_id', $custom_alg_report)
                  ->where('events.created_at', '<=', date_format($custom_start_date, 'Y-m-d 00:00:00'))
                  ->where('events.updated_at', '>=', date_format($custom_start_date, 'Y-m-d 00:00:00'));
              })
              ->join('devices', 'devices.id', '=', 'events.device_id')
              ->select('devices.name as name', 'events.duration as duration', 'events.updated_at as updated_at', 'events.created_at as created_at', 'events.active as active')
              ->get();

            foreach ($custom_device_options as $cdo) {
                if ($cdo->device_id == $custom_alg_report) {
                    $device_name = $cdo->name;
                }
            }
            if (!isset($device_name)) {
                  $device_name = "Device-".$custom_alg_report;
            }

            if ($custom_events->count() > 0) {
                foreach ($custom_events as $ce) {
                    if (BuildingController::string_to_time($ce->updated_at) < BuildingController::string_to_time(date_format($custom_end_date, 'Y-m-d 23:59:59'))) {
                        //event last updated prior to end of (i.e. within) custom period
                        if (BuildingController::string_to_time($ce->created_at) > BuildingController::string_to_time(date_format($custom_start_date, 'Y-m-d 00:00:00'))) {
                            //events began (i.e. created during) custom period
                            if ($ce->active == 1) {
                                //event is ongoing
                                $duration_array[] = BuildingController::create_duration(BuildingController::string_to_time($ce->created_at), BuildingController::string_to_time($ce->updated_at));
                            } else {
                                //duration has been calculated and is completely contained within the custom period
                                $duration_array[] = $ce->duration;
                            }
                        } else {
                            //the event began prior to the specified time frame
                            $duration_array[] = BuildingController::create_duration(BuildingController::string_to_time(date_format($custom_start_date, 'Y-m-d 00:00:00')), BuildingController::string_to_time($ce->updated_at));
                        }
                    } else {
                        if (BuildingController::string_to_time($ce->created_at) < BuildingController::string_to_time(date_format($custom_start_date, 'Y-m-d 00:00:00'))) {
                            //duration preceeds and exceeds custom time frame
                            $duration_array[] = BuildingController::create_duration(BuildingController::string_to_time(date_format($custom_start_date, 'Y-m-d 00:00:00')), BuildingController::string_to_time(date_format($custom_end_date, 'Y-m-d 23:59:59')));
                        } else {
                            //event lasst updated after the end of the custom period
                            $duration_array[] = BuildingController::create_duration(BuildingController::string_to_time($ce->created_at), BuildingController::string_to_time(date_format($custom_end_date, 'Y-m-d 23:59:59')));
                        }
                    }
                }

                  $custom_totals = [
                    'name' => $custom_events[0]->name,
                    'num_results' => $custom_events->count(),
                    'total' => BuildingController::duration_totalling($duration_array),
                    'start_time' => date_format($custom_start_date, 'm/d/Y'),
                    'end_time' => date_format($custom_end_date, 'm/d/Y')
                  ];
            } else {
                  $custom_totals = [
                    'name' => 'No Events for '.$device_name.' for this period ',
                    'num_results' => '0',
                    'total' => '0',
                    'start_time' => date_format($custom_start_date, 'm/d/Y'),
                    'end_time' => date_format($custom_end_date, 'm/d/Y')
                  ];
            }
        }
        $currentmode = Input::get('currentmode');
        // $queries = DB::getQueryLog();
    
        /**~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ end custom totalling ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~**/
        return View::make('buildings.status.eventsstatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
        ->with('sysEventsActive', $sysEventsActive)
        ->with('sysEventsAlg', $sysEventsAlg)
        ->with('sysEventsHist', $sysEventsHist)
        ->with('systemsData', $systems)
        ->with('parent', DashboardItem::find($dashboard_id))
        ->with('algorithms', $algorithms)
        ->with('Filter', $filter)
        ->with('PageA', $pageA)
        ->with('PageH', $pageH)
        ->with('TotalA', $reccountActive)
        ->with('TotalH', $reccountHist)
        ->with('today_time', $today_time)
        ->with('yesterday_time', $yesterday_time)
        ->with('device_durations', $device_durations)
        ->with('expand', $expand)
        ->with('custom_device_options', $custom_device_options)
        ->with('custom_totals', $custom_totals)
        ->with('custom_start_date', $custom_start_date)
        ->with('custom_end_date', $custom_end_date)
        ->with('start', $start)
        ->with('current_time', $current_time)
        ->with('currentmode', $currentmode)
        ->with('items', $items);
    }

/*=+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=*/
/*=+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=*/
/*=+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=*/
/*=+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=*/
/*=+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++=*/


  /*
  |--------------------------------------------------------------------------
  | Building Controller
  |--------------------------------------------------------------------------
  |
  */

    public function dashboard()
    {
        $data1 = [];
        $customer = Customer::find(Auth::user()->customer_id);
        $customer_buildings = Building::where('customer_id', Auth::user()->customer_id)
                                    ->where('access', 1)
                                    ->get();
        foreach ($customer_buildings as $building) {
            $systems = System::where('building_id', $building->id)->get();
      

            $data = [];
            $building_id_dashboard= $building->id;
            /* Get all of the active alarms for this building */
            $alarms = DB::table('alarms')
            ->where('active', 1)
            ->join('alarm_codes', 'alarms.alarm_code_id', '=', 'alarm_codes.id');
            $alarms->whereIn('system_id', function ($subquery) use ($building_id_dashboard) {
                $subquery->from('systems')->select('id')->where('building_id', $building_id_dashboard);
            });
            $alarms = $alarms->get();

            /**
       * Attach an alarm_severity property to each system object with the highest
       * alarm sevirity that applies to it
       */
            foreach ($systems as $system) {
                  //Use it to get page_id. Page_id = item->id
                  $items = DashboardItem::where('system_id', $system->id)
                  ->where('parent_id', 0)
                  ->orderBy('order')
                  ->get();

                  /**
        * If there are no top level menu items then use the default
        * (i.e. system_id == 0)
        */
                if (!count($items)) {
                    $items = DashboardItem::where('system_id', 0)
                      ->where('parent_id', 0)
                      ->orderBy('order')
                      ->get();
                }
                    $system['items'] = $items;
                    $data1['systems'][$system->id] = $system;
                    $data1['systems'][$system->id]->alarm_severity = 0;
                foreach ($alarms as $alarm) {
                    if ($alarm->system_id === $system->id && $alarm->severity > $data1['systems'][$system->id]->alarm_severity) {
                        $data1['systems'][$system->id]->alarm_severity = $alarm->severity;
                    }
                }
                    //$data1['systems'][$system->id] = $data['systems'][$system->id]
            }
            $dashboard_alarm = 0;
            foreach ($systems as $system) {
                if ($system->alarm_severity>$dashboard_alarm) {
                    $dashboard_alarm = $system->alarm_severity;
                }
            }
            $data1['systemsData'][$building->id] = $systems;
            $data1['IDbuilding'][$building->id] = $dashboard_alarm;
        }
        $data1['alarm_icons'] = [0 => 'White',
                            1 => 'Yellow',
                            2 => 'Red'];
        $data1['customer'] = $customer;

        $data1['customer_buildings_for_navbar'] = $this->customer_buildings_for_navbar;
        return View::make('buildings.dashboard', $data1);
    }

/*
  public function view($id)
  {
    $building = Building::find($id);

    return View::make('buildings.detail')->with('building', $building);
  }
*/


  /**
   * Display an overview of systems installed in the selected building
   * @param  integer $building_id The building being viewed
   * @return view                 Pass data to be rendered in a view
   */
    public function building($building_id)
    {
        $building = Building::find($building_id);
        $systems = System::where('building_id', $building->id)->get();
        $data = [];

        /* Get all of the active alarms for this building */
        $alarms = DB::table('alarms')
        ->where('active', 1)
        ->join('alarm_codes', 'alarms.alarm_code_id', '=', 'alarm_codes.id');
        $alarms->whereIn('system_id', function ($subquery) use ($building_id) {
            $subquery->from('systems')->select('id')->where('building_id', $building_id);
        });
        $alarms = $alarms->get();

        /**
     * Attach an alarm_severity property to each system object with the highest
     * alarm sevirity that applies to it
     */
        foreach ($systems as $system) {
            $data['systems'][$system->id] = $system;
            $data['systems'][$system->id]->alarm_severity = 0;
            foreach ($alarms as $alarm) {
                if ($alarm->system_id === $system->id && $alarm->severity > $data['systems'][$system->id]->alarm_severity) {
                    $data['systems'][$system->id]->alarm_severity = $alarm->severity;
                }
            }
        }

        /* Build the string for a 'system info' popover */
        $building_info = '';
        if ($building->address1 !== '') {
            $building_info .= $building->address1 . '<br>';
        }
        if ($building->address2 !== '') {
            $building_info .= $building->address2 . '<br>';
        }
        if ($building->city !== '') {
            $building_info .= $building->city;
        }
        if ($building->city !== '' && $building->state !== '') {
            $building_info .= $building->state;
        }
        if ($building->zip > 0) {
            $building_info .= $building->zip;
        }

        $data['thisBldg'] = $building;
        $data['systemsData'] = $systems;
        $data['building_info'] = $building_info;
        $data['alarm_icons'] = [0 => 'images/greenbutton.png',
                            1 => 'images/yellowbutton.png',
                            2 => 'public/images/redbutton.png'];
        if (Auth::guest()) {
            return View::make('home.index')->render();
        } else {
            return View::make('buildings.building', $data);
        }
    }


    public function system($id, $sid)
    {
        $thisBldg = Building::find($id); // Lookup passed building from DB
        $thisSystem = System::find($sid); // Lookup passed system from DB
        $sysAlarms = Alarms::where('system_id', $sid)->where('active', 1)->get();
        // Check for system in web_mapping_system
        $check = WebMappingSystem::where('system_id', $sid)->first();
        if ($check) {
            // If system is there, build page using web_mapping_system with specific system
            $sysParams = WebMappingSystem::select(array('group_number', 'group_name'))->where('system_id', $sid)->where('active', 1)->distinct()->get();
        } else {
            // If system isn't there, build page using web_mapping_default
            $sysParams = WebMappingDefault::select(array('group_number', 'group_name'))->distinct()->where('active', 1)->get();
        }

        return View::make('buildings.system', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem))
        ->with('parent', DashboardItem::find($dashboard_id))
        ->with('sysParams', $sysParams)
        ->with('sysAlarms', $sysAlarms);
    }


    public function detail($id, $sid, $gid)
    {
        $thisBldg = Building::find($id); // Lookup passed building from DB
        $thisSystem = System::find($sid); // Lookup passed system from DB
        $systems = System::where('building_id', $thisBldg->id)->get();

        // Check for system in web_mapping_system
        $check = WebMappingSystem::where('system_id', $sid)->first();
        if ($check) {
            // If system is there, build page using web_mapping_system with specific system
            $sysDetail = WebMappingSystem::select(array('group_name', 'subgroup_number', 'subgroup_name','alarm_state','alarm_index'))->where('system_id', $sid)->where('group_number', $gid)->where('active', 1)->distinct()->get();
            $categories = WebMappingSystem::select(array('subgroup_number', 'subgroup_name', 'itemnumber','alarm_state','alarm_index'))->where('system_id', $sid)->where('group_number', $gid)->where('active', 1)->get();
        } else {
            // If not there, get default subgroup details for this specific group
            $sysDetail = WebMappingDefault::select(array('group_name', 'subgroup_number', 'subgroup_name','alarm_state','alarm_index'))->where('group_number', $gid)->where('active', 1)->distinct()->get();
            $categories = WebMappingDefault::select(array('subgroup_number', 'subgroup_name', 'itemnumber','alarm_state','alarm_index'))->where('group_number', $gid)->where('active', 1)->get();
        }
        //-------------------------------------------//
        // Check if there is temperature data to report
        //-------------------------------------------//
        $timezone = "America/New_York"; // temporaryyy
        date_default_timezone_set($timezone);

        $check = DeviceData::where('system_id', $sid)
        ->where(function ($query) {
            $query->where('command', 11)
            ->orWhere('command', 1);
        })
        ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
        ->first();

        if ($check) {
          // Get in chronological order
            $tempObj = DeviceData::select()->join('devices', function ($join) {
                $join->on('devices.id', '=', 'device_data.id');
                $join->on('devices.system_id', '=', 'device_data.system_id');
            })
            ->where('device_data.system_id', $sid)
            ->where(function ($query) {
                $query->where('command', 11)
                  ->orWhere('command', 1);
            })
            ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
            ->orderBy('datetime', 'asc')
            ->get();

          // Create datetime array for chart x-axis timestamps
            $tempCategories = array();
            foreach ($tempObj as $item) {
                array_push($tempCategories, date('M d, Y, g:i:s A', strtotime($item->datetime)));
            }

          // Get data points, in ASC zone order
            $tempObj = DeviceData::select()->join('devices', function ($join) {
                    $join->on('devices.id', '=', 'device_data.id');
                    $join->on('devices.system_id', '=', 'device_data.system_id');
            })
            ->where('device_data.system_id', $sid)
            ->where(function ($query) {
                $query->where('command', 11)
                  ->orWhere('command', 1);
            })
            ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
            ->orderBy('zone', 'asc')
            ->get();

          // Grab full device list. Will have active and reporting devices removed from it in the next loop
            $deviceList = DB::table('devices')->join('product_types', 'devices.product_id', '=', 'product_types.product_id')
            ->where('system_id', 1)
            ->where(function ($query) {
                $query->where('commands', 'LIKE', '%11%')
                  ->orWhere('commands', 'LIKE', '%1%');
            })
            ->where('status', 1)
            ->where('inhibited', "<>", 1)
            ->where('retired', "<>", 1)
            ->lists('id');

          // Create 3d array for data: top level is zone id, each zone with subarray of device ids for that zone, each device with subarrays containing date|value information for that device
            $tempDevices = array();
            foreach ($tempObj as $item) {
                // Check if temps should be in C or F
                if ($thisSystem->temperature_format == "C") {
                    $tempFormat = "C";
                    $tempDevices[$item['zone']][$item['id']][] = date('M d, Y, g:i:s A', strtotime($item->datetime))."|".$item->current_value;
                } else {
                    $tempFormat = "F";
                    $tempDevices[$item['zone']][$item['id']][] = date('M d, Y, g:i:s A', strtotime($item->datetime))."|".ConvFunc::valueconv($item->current_value, $item->command);
                }
                  // Remove this device from $deviceList, if it exists there
                  $listIndex = array_search($item['id'], $deviceList);
                if ($listIndex !== false) {
                    unset($deviceList[$listIndex]);
                }
            }

          // What is left in $deviceList is devices with no data-- add them for completeness
            foreach ($deviceList as $value) {
                  $zone = DB::table('devices')->where('system_id', 1)->where('id', $value)->pluck('zone');
                  $tempDevices[$zone][$value][]=0; // Set to single zero to indicate a nonreporting device, for later
            }

          // Overall Chart initialization
            $tempChart["chart"] = array("zoomType" => "x");
            $tempChart["title"] = array("text" => "Temperature Data: " . $tempCategories[0] . " - " . $tempCategories[(count($tempCategories)-1)]);
            $tempChart["yAxis"] = array("title"=> array("text" => "Temperature (".$tempFormat.")"));
            $tempChart["tooltip"] = array("crosshairs" => array(array("color" => "black", "dashStyle" => "shortdot"), array("color" => "black", "dashStyle" => "shortdot")), "valueDecimals" => 2, "valueSuffix" => " ".$tempFormat);

            $i = 0; // Counter for how many devices there are total and how many series are needed on Overall Chart
            $z = 1; // Counter to track zones

            foreach ($tempDevices as $key => $value) { // For each zone ($key = zone id)
                  // Get zone names from zone_labels
                  $zoneName = Zone::select(array('zonename'))->where('system_id', $sid)->where('zone', $key)->first();
                if ($zoneName['zonename'] == "") {
                    $zoneName = $key; // Use number if no name is set
                } else {
                    $zoneName = $zoneName['zonename'];
                }
                    // Zone Chart initialization
                    $zoneTempCharts[$z]["chart"] = array("zoomType" => "x");
                    $zoneTempCharts[$z]["title"] = array("text" => "Zone " . $zoneName . ": " . $tempCategories[0] . " - " . $tempCategories[(count($tempCategories)-1)]);
                    $zoneTempCharts[$z]["yAxis"] = array("title"=> array("text" => "Temperature (".$tempFormat.")"));
                    $zoneTempCharts[$z]["zone"] = $zoneName;
                    $zoneTempCharts[$z]["tooltip"] = array("crosshairs" => array(array("color" => "black", "dashStyle" => "shortdot"), array("color" => "black", "dashStyle" => "shortdot")), "valueDecimals" => 2, "valueSuffix" => " ".$tempFormat);

                    $zoneSeries = 0; // Counter for how many devices are in this specific zone and how many corresponding series are needed on Zone Chart

                foreach ($value as $key2 => $data) { // For each device in this zone ($key2 = device id)
                    // If $data is set to a single 0, then this is one of the devices not reporting data-- create an empty series
                    if ($data[0] === 0) {
                        $tempData[0] = 0;
                    } else { // Otherwise the format retrieved for $data should be date|data
                        $tempData = array_pad(array(), count($tempCategories), null); // Pad an empty data array with null values, each index being a timestamp

                        foreach ($data as $item) { // For each data point in this device
                            $itemArr = explode("|", $item); // Separate timestamp and data values
                            $index = array_search($itemArr[0], $tempCategories); // Search for the index of this timestamp in datetime array, then map data to the same index in data array
                            $tempData[$index] = $itemArr; // Data mapped in TIME, VALUE format for json_encode. the index in data array remains null if this device has no data for a certain timestamp
                        }
                    }

                    // Get devices names from $key2
                    $deviceName = Device::select(array('name'))->where('system_id', $sid)->where('id', $key2)->first();

                    // Set Zone Chart series data for this device
                    if ($tempData[0] === 0) {
                        $zoneTempCharts[$z]["series"][$zoneSeries] = array("name" => $deviceName['name'], "type" => "spline", "data" => $tempData, "visible" => false);
                        $zoneSeries++;
                    } else {
                        $zoneTempCharts[$z]["series"][$zoneSeries] = array("name" => $deviceName['name'], "type" => "spline", "data" => $tempData);
                        $zoneSeries++;
                    }

                    // Set Overall Chart series data for this device
                    if ($tempData[0] === 0) {
                        $tempChart["series"][$i] = array("name" => $deviceName['name'], "type" => "spline", "data" => $tempData, "visible" => false);
                    } else {
                        $tempChart["series"][$i] = array("name" => $deviceName['name'], "type" => "spline", "data" => $tempData);
                    }
                        $i++;
                }

                    // Concluding options for Zone Chart.
                    $zoneTempCharts[$z]["plotOptions"] = array("series" => array("connectNulls" => "true", "turboThreshold" => 0)); // Connect lines past null data points
                    $zoneTempCharts[$z]["xAxis"] = array("categories" => $tempCategories, "labels" => array("enabled" => false));

                    // Move to next zone
                    $z++;
            }

          /* Make sure that disabled devices don't have any actual values */
            foreach ($tempChart['series'] as $key => $value) {
                if ($value['data'][0] === 0) {
                    foreach ($value['data'] as $key2 => $value2) {
                        if (gettype($value2) == 'array') {
                            $tempChart['series'][$key]['data'][$key2] = null;
                        }
                    }
                }
            }
            foreach ($zoneTempCharts as $zone_number => $zones) {
                foreach ($zones['series'] as $key => $value) {
                    if ($value['data'][0] === 0) {
                        foreach ($value['data'] as $key2 => $value2) {
                            if (gettype($value2) == 'array') {
                                $zoneTempCharts[$zone_number]['series'][$key]['data'][$key2] = null;
                            }
                        }
                    }
                }
            }

          // Concluding options for OVerall Chart.
            $tempChart["plotOptions"] = array("series" => array("connectNulls" => "true", "turboThreshold" => 0)); // Connect lines past null data points
            $tempChart["xAxis"] = array("categories" => $tempCategories, "labels" => array("enabled" => false));
        }
        //-------------------------------------------//
        // Check if there is humidity data to report
        //-------------------------------------------//
        $check = DeviceData::where('system_id', $sid)
        ->where('command', 10)
        ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
        ->first();

        if ($check) {
          // Get data points, in chronological order
            $humObj = DeviceData::select()->join('devices', function ($join) {
                $join->on('devices.id', '=', 'device_data.id');
                $join->on('devices.system_id', '=', 'device_data.system_id');
            })
            ->where('device_data.system_id', $sid)
            ->where('command', 10)
            ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
            ->orderBy('datetime', 'asc')
            ->get();

          // Create datetime array for chart x-axis timestamps
            $humCategories = array();
            foreach ($humObj as $item) {
                array_push($humCategories, date('M d, Y, g:i:s A', strtotime($item->datetime)));
            }

          // Get data points, in ASC zoneorder
            $humObj = DeviceData::select()->join('devices', function ($join) {
                    $join->on('devices.id', '=', 'device_data.id');
                    $join->on('devices.system_id', '=', 'device_data.system_id');
            })
            ->where('device_data.system_id', $sid)
            ->where('command', 10)
            ->whereBetween('datetime', array(date("Y-m-d H:i:s", time() - 60 * 60 * 2), date("Y-m-d H:i:s"))) // data from last 2 hours
            ->orderBy('zone', 'asc')
            ->get();

          // Grab full device list. Will have active and reporting devices removed from it in the next loop
            $deviceList = DB::table('devices')->join('product_types', 'devices.product_id', '=', 'product_types.product_id')
            ->where('system_id', 1)
            ->where('commands', 'LIKE', '%10%')
            ->where('status', 1)
            ->where('inhibited', "<>", 1)
            ->where('retired', "<>", 1)
            ->lists('id');

          // Create 3d array for data: top level is zone id, each zone with subarray of device ids for that zone, each device with subarrays containing date|value information for that device
            $humDevices = array();
            foreach ($humObj as $item) {
                $humDevices[$item['zone']][$item['id']][] = date('M d, Y, g:i:s A', strtotime($item->datetime))."|".$item->current_value;

                // Remove this device from $deviceList, if it exists there
                $listIndex = array_search($item['id'], $deviceList);
                if ($listIndex !== false) {
                    unset($deviceList[$listIndex]);
                }
            }

          // What is left in $deviceList is devices with no data-- add them for completeness
            foreach ($deviceList as $value) {
                  $zone = DB::table('devices')->where('system_id', 1)->where('id', $value)->pluck('zone');
                  $humDevices[$zone][$value][]=0; // Set to single zero to indicate a nonreporting device, for later
            }

          // Overall Chart initialization
            $humChart["chart"] = array("zoomType" => "x");
            $humChart["title"] = array("text" => "Humidity Data: " . $humCategories[0] . " - " . $humCategories[(count($humCategories)-1)]);
            $humChart["yAxis"] = array("title"=> array("text" => "Humidity (%)"));
            $humChart["tooltip"] = array("crosshairs" => array(array("color" => "black", "dashStyle" => "shortdot"), array("color" => "black", "dashStyle" => "shortdot")), "valueDecimals" => 2, "valueSuffix" => "%");

            $i = 0; // Counter for how many devices there are total and how many series are needed on Overall Chart
            $z = 1; // Counter to track zones

            foreach ($humDevices as $key => $value) { // For each zone ($key = zone id)
                  // Get zone names from zone_labels
                  $zoneName = Zone::select(array('zonename'))->where('system_id', $sid)->where('zone', $key)->first();
                if ($zoneName['zonename'] == "") {
                    $zoneName = $key; // Use number if no name is set
                } else {
                    $zoneName = $zoneName['zonename'];
                }
                    // Zone Chart initialization
                    $zoneHumCharts[$z]["chart"] = array("zoomType" => "x");
                    $zoneHumCharts[$z]["title"] = array("text" => "Zone " . $zoneName . ": " . $humCategories[0] . " - " . $humCategories[(count($humCategories)-1)]);
                    $zoneHumCharts[$z]["yAxis"] = array("title"=> array("text" => "Humidity (%)"));
                    $zoneHumCharts[$z]["zone"] = $zoneName;
                    $zoneHumCharts[$z]["tooltip"] = array("crosshairs" => array(array("color" => "black", "dashStyle" => "shortdot"), array("color" => "black", "dashStyle" => "shortdot")), "valueDecimals" => 2, "valueSuffix" => "%");

                    $zoneSeries = 0; // Counter for how many devices are in this specific zone and how many corresponding series are needed on Zone Chart

                foreach ($value as $key2 => $data) { // For each device in this zone ($key2 = device id)
                    // If $data is set to a single 0, then this is one of the devices not reporting data-- create an empty series
                    if ($data[0] === 0) {
                        $humData[0] = 0;
                    } else { // Otherwise the format retrieved for $data should be date|data
                        $humData = array_pad(array(), count($humCategories), null); // Pad an empty data array with null values, each index being a timestamp

                        // For each data point in this device
                        foreach ($data as $item) {
                            // Separate timestamp and data values
                            $itemArr = explode("|", $item);
                            // Search for the index of this timestamp in datetime array, then map data to the same index in data
                            $index = array_search($itemArr[0], $humCategories);
                            // Data mapped in TIME, VALUE format for json_encode. the index in data array remains null if this device has no data for a certain timestamp
                            $humData[$index] = $itemArr;
                        }
                    }

                    // Get devices names from $key2
                    $deviceName = Device::select(array('name'))->where('system_id', $sid)->where('id', $key2)->first();

                    // Set Zone Chart series data for this device
                    if ($humData[0] === 0) {
                        $zoneHumCharts[$z]["series"][$zoneSeries] = array("name" => $deviceName['name'], "type" => "spline", "data" => $humData, "visible" => false);
                        $zoneSeries++;
                    } else {
                        $zoneHumCharts[$z]["series"][$zoneSeries] = array("name" => $deviceName['name'], "type" => "spline", "data" => $humData);
                        $zoneSeries++;
                    }

                    // Set Overall Chart series data for this device
                    if ($humData[0] === 0) {
                        $humChart["series"][$i] = array("name" => $deviceName['name'], "type" => "spline", "data" => $humData, "visible" => false);
                    } else {
                        $humChart["series"][$i] = array("name" => $deviceName['name'], "type" => "spline", "data" => $humData);
                    }
                        $i++;
                }

                    // Concluding options for Zone Chart.
                    $zoneHumCharts[$z]["plotOptions"] = array("series" => array("connectNulls" => "true", "turboThreshold" => 0)); // Connect lines past null data points
                    $zoneHumCharts[$z]["xAxis"] = array("categories" => $humCategories, "labels" => array("enabled" => false));

                    // Move to next zone
                    $z++;
            }

          // Concluding options for OVerall Chart.
            $humChart["plotOptions"] = array("series" => array("connectNulls" => "true", "turboThreshold" => 0)); // Connect lines past null data points
            $humChart["xAxis"] = array("categories" => $humCategories, "labels" => array("enabled" => false));
        }

        // Make the proper view depending on which charts are available
        if (isset($tempChart) & isset($humChart)) {
            return View::make('buildings.status.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
            'tempChart' => json_encode($tempChart, JSON_NUMERIC_CHECK), 'humChart' => json_encode($humChart, JSON_NUMERIC_CHECK),
            'zoneTempCharts' => $zoneTempCharts,
            'numTempZones' => count($zoneTempCharts),
            'zoneHumCharts' => $zoneHumCharts,
            'numHumZones' => count($zoneHumCharts)))
            ->with('systemsData', $systems)
            ->with('parent', DashboardItem::find($dashboard_id))
            ->with('sysDetail', $sysDetail)
            ->with('categories', $categories);
        } else if (isset($tempChart)) {
            return View::make('buildings.status.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
            'tempChart' => json_encode($tempChart, JSON_NUMERIC_CHECK),
            'zoneTempCharts' => $zoneTempCharts,
            'numTempZones' => count($zoneTempCharts)))
            ->with('systemsData', $systems)
            ->with('parent', DashboardItem::find($dashboard_id))
            ->with('sysDetail', $sysDetail)
            ->with('categories', $categories);
        } else if (isset($humChart)) {
            return View::make('buildings.status.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
            'humChart' => json_encode($humChart, JSON_NUMERIC_CHECK),
            'zoneHumCharts' => $zoneHumCharts,
            'numHumZones' => count($zoneHumCharts)))
            ->with('systemsData', $systems)
            ->with('parent', DashboardItem::find($dashboard_id))
            ->with('sysDetail', $sysDetail)
            ->with('categories', $categories);
        } else {
            return View::make('buildings.status.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem))
            ->with('systemsData', $systems)
            ->with('parent', DashboardItem::find($dashboard_id))
            ->with('sysDetail', $sysDetail)
            ->with('categories', $categories);
        }
    }

  /*--------------------------------------------------------------------------
  |
  | Dashboard page
  |--------------------------------------------------------------------------
  */
  //Dashboard map function data is being pass over the furnace.blade.php
    public function dashboardMap($building_id, $system_id, $dashboard_id)
    {

        $data['thisBldg'] = Building::find($building_id);
        $data['thisSystem'] = System::find($system_id);
        $data['maps'] = [];
        $data['boiler'] = 'boiler.png'; //prints out the names

        //Use for getting all the items in sidebar
        $items = DashboardItem::where('system_id', $system_id)
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

        /*to handle the background images in the bakground folder*/
        $dashboard_maps = DashboardMap::where('system_id', $system_id)->get();
        $device_data_current = DeviceDataCurrent::where('system_id', $system_id)->get();
        $backgroundImages = glob("images/backgroundImage/"."*.png");
        // $DashboardMapItems = DashboardMapItem::get();
        $deviceTypes = DeviceType::get();
        $deviceSetpoints = DeviceSetpoints::where('system_id', $system_id)->get();
        // $data['alarmCodes'] = AlarmCodes::get();
        //to check if the Dashboard map table is emtpy if it is prompt the error message


        /*to get the file names of */
        foreach ($backgroundImages as $backgroundImage) {
            $filenames[] = substr(strrchr($backgroundImage, "/"), 1);
        }
        if (!isset($filenames)) {
            $filenames[0]='default.png';
        }

        foreach ($dashboard_maps as $dashboard_map) {
            foreach ($filenames as $filename) {
                if ($dashboard_map['background_image'] == $filename) {
                    $selectMaps[] =  $filename;
                    $backgroundImagePaths[] = ("images/backgroundImage/".$filename);
                }
            }
        }

        /*Fill $dashboard_map_items with relevant dashboard_map_items,devices,device_types, and device_data_current data*/
        $dashboard_map_items = DashboardMapItem::select('dashboard_map_items.*', 'device_data_current.*', 'devices.*', 'dashboard_map_items.label AS device_label', 'dashboard_map_items.id AS rid', 'device_types.function', 'device_types.units')
                                            ->where('dashboard_maps.system_id', $system_id)
                                            ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
                                            ->join('device_data_current', function ($join) {
                                                $join->on('device_data_current.id', '=', 'dashboard_map_items.device_id');
                                                $join->on('device_data_current.command', '=', 'dashboard_map_items.command');
                                                $join->on('device_data_current.system_id', '=', 'dashboard_maps.system_id');
                                            })
                                            ->join('devices', function ($join) {
                                                $join->on('devices.id', '=', 'dashboard_map_items.device_id');
                                                $join->on('devices.system_id', '=', 'dashboard_maps.system_id');
                                            }) //due to the lock of setpoint values for some devices, I canceled the joint for this query
                                            ->join('device_types', 'dashboard_map_items.command', '=', 'device_types.command')
                                            ->where('device_io', 'input')
                                            ->where('retired', '!=', '1')
                                            ->orderby('datetime', 'DESC')
                                            ->get();

        foreach ($deviceSetpoints as $deviceSetpoint) {
            foreach ($dashboard_map_items as $dashboard_map_item) {
                if ($dashboard_map_item->system_id == $deviceSetpoint->system_id) {
                    if (($dashboard_map_item->device_id == $deviceSetpoint->device_id) && ($dashboard_map_item->command == $deviceSetpoint->command)) {
                        $dashboard_map_item->alarm_high = $deviceSetpoint->alarm_high;
                        $dashboard_map_item->alarm_low  = $deviceSetpoint->alarm_low;
                        $dashboard_map_item->setpoint  = $dashboard_map_item->setpoint;
                    }
                }
            }
        }
        foreach ($deviceTypes as $deviceType) {
            foreach ($dashboard_map_items as $dashboard_map_item) {
                if (empty($dashboard_map_item->setpoint)) {
                    $dashboard_map_item->alarm_high = $deviceType->alarm_high;
                    $dashboard_map_item->alarm_low  = $deviceType->alarm_low;
                    $dashboard_map_item->setpoint  = $deviceType->setpoint;
                }
            }
        }

        // $data['availableDevices'] = Device::select('devices.*', 'device_data_current.*')
        //   ->where('zone','!=', '0')
        //   ->where('devices.system_id', $system_id)
        //   ->join('device_data_current', function($join){
        //     $join->on('device_data_current.id','=','devices.id');
        //     $join->on('device_data_current.system_id', '=', 'devices.system_id');
        //   })
        //   ->orderBy('zone', 'ASC')
        //   ->orderBy('name')
        //   ->orderBy('command')
        //   ->where('command' , '!=', '0')
        //   ->get();


        $dashboard_map_items_outputs = DashboardMapItem::select('mapping_output.*', 'devices.*', 'device_data_current.*', 'dashboard_map_items.x_position AS x-pos', 'dashboard_map_items.id AS dashboardID', 'dashboard_map_items.map_id AS map_id', 'dashboard_map_items.y_position AS y-pos', 'dashboard_map_items.label AS device_label', 'dashboard_map_items.id AS rid', 'device_types.function')
                                                  ->where('dashboard_maps.system_id', $system_id)
                                                  ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
                                                  ->join('device_data_current', function ($join) {
                                                    $join->on('device_data_current.id', '=', 'dashboard_map_items.device_id');
                                                    $join->on('device_data_current.command', '=', 'dashboard_map_items.command');
                                                    $join->on('device_data_current.system_id', '=', 'dashboard_maps.system_id');
                                                  })
                                                  ->join('devices', function ($join) {
                                                    $join->on('devices.id', '=', 'dashboard_map_items.device_id');
                                                    $join->on('devices.system_id', '=', 'dashboard_maps.system_id');
                                                  })
                                                  ->join('mapping_output', function ($join) {
                                                    $join->on('mapping_output.device_id', '=', 'dashboard_map_items.device_id');
                                                    $join->on('mapping_output.system_id', '=', 'devices.system_id');
                                                  })
                                                  ->join('device_types', 'dashboard_map_items.command', '=', 'device_types.command')
                                                  ->where('retired', '!=', '1')
                                                  ->get();

        foreach ($deviceSetpoints as $deviceSetpoint) {
            foreach ($dashboard_map_items_outputs as $dashboard_map_items_output) {
                if ($dashboard_map_items_output->system_id == $deviceSetpoint->system_id) {
                    if (($dashboard_map_items_output->device_id == $deviceSetpoint->device_id) && ($dashboard_map_items_output->command == $deviceSetpoint->command)) {
                        $dashboard_map_items_output->alarm_high = $deviceSetpoint->alarm_high;
                        $dashboard_map_items_output->alarm_low  = $deviceSetpoint->alarm_low;
                        $dashboard_map_items_output->setpoint  = $deviceSetpoint->setpoint;
                    }
                }
            }
        }
        foreach ($deviceTypes as $deviceType) {
            foreach ($dashboard_map_items_outputs as $dashboard_map_items_output) {
                if (empty($dashboard_map_items_output->setpoint)) {
                    $dashboard_map_items_output->alarm_high = $deviceType->alarm_high;
                    $dashboard_map_items_output->alarm_low  = $deviceType->alarm_low;
                    $dashboard_map_items_output->setpoint  = $deviceType->setpoint;
                }
            }
        }

        $a[] = null;
        $dashboard_map_items_outputs2 = DeviceDataCurrent::where('device_data_current.system_id', $system_id)
                                                    ->join('devices', function ($join) {
                                                        $join->on('devices.id', '=', 'device_data_current.id');
                                                        $join->on('devices.system_id', '=', 'device_data_current.system_id');
                                                    })
                                                    ->join('mapping_output', function ($join) {
                                                        $join->on('mapping_output.device_id', '=', 'device_data_current.id');
                                                        $join->on('mapping_output.system_id', '=', 'device_data_current.system_id');
                                                    })
                                                    ->where('retired', '!=', '1')
                                                    ->get();

        $DeviceDataCurrents = DeviceDataCurrent::orderby('datetime', 'DESC')
                                          ->where('devices.system_id', $system_id)
                                          ->join('devices', function ($join) {
                                            $join->on('devices.id', '=', 'device_data_current.id');
                                            $join->on('devices.system_id', '=', 'device_data_current.system_id');
                                          })
                                          ->where('retired', '!=', '1')
                                          ->get();


        static $moi;
        /*Add active inputs to $moi*/
        foreach ($dashboard_map_items_outputs as $key => $dashboard_map_items_output) {
            $input_commands = explode(',', (str_replace('.', '', $dashboard_map_items_output->active_inputs)));
            foreach ($input_commands as $input_command) {
                $ic=[ 'device_id'=>'0', 'command'=>'0'];
                $boom = explode(' ', trim($input_command));
                if (isset($boom[0]) && isset($boom[1])) {
                    $ic['device_id'] = $boom[0];
                    $ic['command'] = $boom[1];
                }

                $moi[] = [  /*mapping_output input*/
                'id'=> $dashboard_map_items_output->device_id,
                'command'=>$dashboard_map_items_output->command,
                'current_state'=>$dashboard_map_items_output->current_state,
                'name'=>$dashboard_map_items_output->name,
                'input'=>[
                  'name' => 'none',
                  'device_id' => (int)$ic['device_id'],
                  'command' => (int)$ic['command'],
                  'current_value' => 'none',
                  'state' => 0,
                  'alarm_state' => 0,
                  'alarm_index' => 0,
                  'active' => 1
                ]
                ];
            }
        }
        /*Add Primary inputs to $moi*/
        foreach ($dashboard_map_items_outputs as $key => $dashboard_map_items_output) {
            $input_commands = explode(',', (str_replace('.', '', $dashboard_map_items_output->inputs)));
            foreach ($input_commands as $input_command) {
                $ic=[ 'device_id'=>'0', 'command'=>'0'];
                $boom = explode(' ', trim($input_command));
                if (isset($boom[0]) && isset($boom[1])) {
                    $ic['device_id'] = $boom[0];
                    $ic['command'] = $boom[1];
                }

                $moi[] = [  /*mapping_output input*/
                'id'=> $dashboard_map_items_output->device_id,
                'command'=>$dashboard_map_items_output->command,
                'current_state'=>$dashboard_map_items_output->current_state,
                'name'=>$dashboard_map_items_output->name,
                'input'=>[
                  'name' => 'none',
                  'device_id' => (int)$ic['device_id'],
                  'command' => (int)$ic['command'],
                  'current_value' => 'none',
                  'state' => 0,
                  'alarm_state' => 0,
                  'alarm_index' => 0,
                  'active' => 0
                ]
                ];
            }
        }
        /*Make inputs active, if so indicated by active_inputs*/
        if (isset($moi)) {
            foreach ($moi as $outter_key => $outter_moi) {
                foreach ($moi as $inner_key => $inner_moi) {
                    if (/*duplicate output inputs*/
                    ($outter_moi['id'] == $inner_moi['id']) &&
                    ($outter_moi['command'] == $inner_moi['command']) &&
                    ($outter_moi['input']['device_id'] == $inner_moi['input']['device_id']) &&
                    ($outter_moi['input']['command'] == $inner_moi['input']['command']) &&
                    ($outter_moi['input']['active']  != $inner_moi['input']['active'])
                      ) {
                          $moi[$inner_key]['input']['active'] = 1;
                    }
                }
            }
            /*STRIP OUT THE DUPLICATES CAUSED BY THE DIFFERENT MAP_IDs and ACTIVE_INPUTS/INPUTS OVERLAP*/
            $moi = array_unique($moi, SORT_REGULAR);
        }


        $DeviceDataCurrents = DeviceDataCurrent::orderby('datetime', 'DESC')
        ->where('devices.system_id', $system_id)
        ->join('devices', function ($join) {
            $join->on('devices.id', '=', 'device_data_current.id');
            $join->on('devices.system_id', '=', 'device_data_current.system_id');
        })
        ->where('retired', '!=', '1')
        ->get();

        if (isset($moi)) {
            foreach ($moi as $key => $mapping_oi) {
                foreach ($DeviceDataCurrents as $ddc) {
                    if (($ddc->id == $mapping_oi['input']['device_id']) && ($ddc->command == $mapping_oi['input']['command'])) {
                        if ($ddc->name==null) {
                            $ddc->name ='id='.$ddc->id .' pid='.$ddc->product_id.'- No Name';
                        }
                        $moi[$key]['input']['current_value'] = $ddc->current_value;
                        $moi[$key]['input']['state'] = $ddc->current_state;
                        $moi[$key]['input']['alarm_state'] = $ddc->alarm_state;
                        $moi[$key]['input']['alarm_index'] = $ddc->alarm_index;
                        $moi[$key]['input']['name'] = $ddc->name;
                    }
                }
            }
        }

        $device_types = DeviceType::where('function', '=', 'Virtual')
        ->get();


        $DashboardMapItems = DashboardMapItem::where('dashboard_maps.system_id', $system_id)
        ->select('dashboard_map_items.*', 'dashboard_maps.label AS printLabel')
        ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
        ->get();

        $alarmcodes = AlarmCodes::get();
        $Alarm_Codes = array();
        foreach ($alarmcodes as $acodes) {
            $Alarm_Codes[$acodes->id]['description'] = $acodes->description;
            $Alarm_Codes[$acodes->id]['severity'] = $acodes->severity;
            $Alarm_Codes[$acodes->id]['class'] = $acodes->alarm_class;
        }
        $Devices = Device::where('system_id', $system_id)->get();
        if (!isset($backgroundImagePaths)) {
            $backgroundImagePaths[] = "images/backgroundImage/default.png";
        }
        $NoRep = DB::table('alarms')
                          ->where('system_id', $data['thisSystem']->id)
                          // ->where('device_id', $dashboard_map_item->device_id)
                          ->where('active', 1)
                          ->where('alarm_code_id', 11)
                          ->get();
        //check to see if the dashboard map items table is empty for this field if it is assign some fake array to get the page keep going
        $dar = (array)$dashboard_map_items;
        foreach ($dar as $key => $value) {
            if (empty($value)) {
                $backgroundImagePaths[] = "images/backgroundImage/default.png";
                (array)$apartments[] =[
                'id'=> 1,'setpoints'=> 1,'device_id'=>1,'command'=> 1,'map_id'=> 1 ,'inhibited'=>1,'x-pos'=>1,'y-pos'=>1,'state'=>1,'name'=> 'Test','tag'=>1,'currentVal'=>1,'status'=> 1,'high'=> 1,'low'=> 1,'location'=> 1,'desc'=> 1,'alarm_index'=> 1
                ];
            } else {
                foreach ($dashboard_map_items as $dashboard_map_item) {
                    if ((strlen($dashboard_map_item->name)) >= 15) {//to split the name
                        $text = $dashboard_map_item->name;
                        $middle = strpos($text, ' ', floor(strlen($text)*0.5));
                        $string1 = substr($text, 0, $middle);
                        $string2 = substr($text, $middle);
                        $dashboard_map_item->name = $string1."<br>".$string2;
                    }
                    if (($dashboard_map_item->function == 'Temperature')) {
                        if ($data['thisSystem']->temperature_format == 'F') {
                            /*if the function is temperature and the building requires F, convert from C to F, and round to the nearest tenth of a degree*/
                            $dashboard_map_item->current_value = round(ConvFunc::convertCelciusToFarenheit($dashboard_map_item->current_value), 1, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->alarm_low =  round(ConvFunc::convertCelciusToFarenheit($dashboard_map_item->alarm_low), 0, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->alarm_high =  round(ConvFunc::convertCelciusToFarenheit($dashboard_map_item->alarm_high), 0, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->setpoint = round(ConvFunc::convertCelciusToFarenheit($dashboard_map_item->setpoint), 0, PHP_ROUND_HALF_UP);
                        } else {
                            /*round to the nearest tenth of a degree*/
                            $dashboard_map_item->current_value = round($dashboard_map_item->current_value, 1, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->alarm_low =  round($dashboard_map_item->alarm_low, 1, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->alarm_high =  round($dashboard_map_item->alarm_high, 1, PHP_ROUND_HALF_UP);
                            $dashboard_map_item->setpoint = round($dashboard_map_item->setpoint, 1, PHP_ROUND_HALF_UP);
                        }
                    }
                    if ($dashboard_map_item->function == 'Pressure') {  //if  the function is a pressure, just rounded to 2 decimal places
                        $dashboard_map_item->current_value = round($dashboard_map_item->current_value, 2);
                    }
                    if ($dashboard_map_item->function == 'Flow') {      //if the fuction is Flow just rounded to 2 decimal places
                        $dashboard_map_item->current_value = round($dashboard_map_item->current_value, 2);
                    }
                    if ($dashboard_map_item->device_label == 'Smoke') {   //when it is smoke alarm, there should not be values just state
                        //$dashboard_map_item->current_value = '';
                        $dashboard_map_item->alarm_high = 1;
                        $dashboard_map_item->alarm_low = 0;
                    }
                    if (($dashboard_map_item->device_label == 'Burner On') || ($dashboard_map_item->device_label == 'Burner Fault')) {
                    }
                    if (($dashboard_map_item->physical_location != 'Temperature') && ($dashboard_map_item->current_state == 0)) {
                        /*shows the current state to be on*/
                        $dashboard_map_item->current_state = "ON";
                    } else {   //shows the current state to be off
                        $dashboard_map_item->current_state = "OFF";
                    }
                    if ($dashboard_map_item->name == null) {
                        $dashboard_map_item->name = "Unknown name";
                    }
                    $alarmsize = [];
                    foreach ($NoRep as $alarm) {
                        if ($alarm->device_id == $dashboard_map_item->device_id) {
                            array_push($alarmsize, $alarm);
                        }
                    }

                    (array)$apartments[] =  [
                    'id'          => $dashboard_map_item->rid,//recnum in dashboard map items table
                    'setpoints'   => $dashboard_map_item->setpoint,
                    'device_id'   => $dashboard_map_item->device_id,
                    'command'     => $dashboard_map_item->command,
                    'map_id'      => $dashboard_map_item->map_id,
                    'inhibited'   => $dashboard_map_item->inhibited,
                    'x-pos'       => $dashboard_map_item->x_position,
                    'y-pos'       => $dashboard_map_item->y_position,
                    'state'       => $dashboard_map_item->alarm_state,
                    'name'        => $dashboard_map_item->name,
                    'tag'         => $dashboard_map_item->function,
                    'currentVal'  => $dashboard_map_item->current_value,
                    'status'      => $dashboard_map_item->current_state,
                    'high'        => $dashboard_map_item->alarm_high,
                    'low'         => $dashboard_map_item->alarm_low,
                    'location'    => $dashboard_map_item->physical_location,
                    'desc'        => $dashboard_map_item->functional_description,
                    'alarm_index' => $dashboard_map_item->alarm_index,
                    'map_id'      => $dashboard_map_item->map_id,
                    'reporttime'  => $dashboard_map_item->reporttime,
                    'updated_at'  => $dashboard_map_item->updated_at,
                    'retired'     => $dashboard_map_item->retired,
                    'product_id'  => $dashboard_map_item->product_id,
                    'units'       => ConvFunc::unitconv($dashboard_map_item->units, $data['thisSystem']->temperature_format),
                    'comments'    => $dashboard_map_item->comments,
                    'overdue'     => (empty($alarmsize))?"NO":"YES",
                    'last_report_time'  =>  $dashboard_map_item->datetime
                    /*                                                  'checktime'   => $checktime,
                    'reporttime'  => $dashboard_map_item->reporttime*/
                    ];
                }

                if (empty($apartments)) {
                    foreach ($dashboard_maps as $key => $value) {
                        $x = $key+1;
                        /*if there is not data in the dashboard map items table, this will fill the empty array to avoid error message*/
                        (array)$apartments[] =[
                        'id'=> 1,'setpoints'=> 1,'device_id'=>1,'command'=> 1,'map_id'=> "$x" ,'inhibited'=>1,'x-pos'=>1,'y-pos'=>1,'state'=>1,'name'=> 'Test','tag'=>1,'currentVal'=>1,'status'=> 1,'high'=> 1,'low'=> 1,'location'=> 1,'desc'=> 1,'alarm_index'=> 1
                        ];
                    }
                }
            }
        }
        $arrayX = (array)$dashboard_maps;
        foreach ($arrayX as $key => $value) {
            if (empty($value)) {
                unset($arrayX[$key]);
            }
        }
        if (empty($arrayX)) {
            $dashboard_maps->background_image = '';
        }
        $thisBldg = Building::find($building_id);
        $thisSystem = System::find($system_id);
        $zonenames = Zone::where('system_id', $thisSystem->id)->get();
        $mappingout = MappingOutput::where('system_id', $thisSystem->id)
        ->orderby('device_id')->get();
        $mappingOutputs = array();
        foreach ($mappingout as $output) {
            $mappingOutputs[$output->device_id] = $output->overridetime;
        }
        $input = Input::except('_token');

        if (isset($input['Toggle'])) {
            BuildingController::DeployInstruction($building_id, $system_id, 0, $input);
            return Redirect::back();//to( '/' );
        } elseif (isset($input['Bypass'])) {
            $mappingOutput = MappingOutput::where('system_id', $system_id)
            ->where('device_id', $input['device'])
            ->where('device_type', $input['command'])
            ->first();
            if (!isset($mappingOutput)) {
                Session::flash("error", "<big><big>The Device is not exist</big></big>");
            } else if (!isset($input['Override'])) {
                Session::flash("error", "<big><big>Bypass Failed: Please Select \"ON\" or \"OFF\" and \"Bypass Time\", then Select \"Bypass\"</big></big>");
            } else {
                $mappingOutput->overridetime = (int)$input['Overridetime'];
                $mappingOutput->save();
                BuildingController::DeployInstruction($building_id, $system_id, 1, $input);
            }
            return Redirect::back();
        }

        $currentTime = time();
        $devicesCurrent = DeviceDataCurrent::where('system_id', $system_id)
        ->orderby('datetime', 'DESC')
        ->groupby('id', 'command')
        ->get();
        $timeStampsArray = array();
        $devicesOutCurrent = array();
        foreach ($devicesCurrent as $currentOut) {
            $timeStampsArray[$currentOut->id][$currentOut->command] = $currentOut->datetime;
            $devicesOutCurrent[$currentOut->id][$currentOut->command] = $currentOut;
        }
        $zonekey = sizeof($zonenames)-1; //6
        $mapkey = sizeof($dashboard_maps)-1; //4

        // $data['devicesout'] = Device::where('system_id',$system_id) // Lookup devices which are outputs
        //   ->where('device_io','output')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->orderby('product_id')
        //   ->orderby('id')
        //   ->get();
        // $data['devicesin'] = Device::where('system_id', $system_id) // Lookup devices which are inputs
        //   ->where('device_io','input')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->orderby('product_id')
        //   ->orderby('id')
        //   ->get();

        return View::make('buildings.furnace', $data)
        ->with('bid', $building_id)
        ->with('sid', $system_id)
        ->with('apartments', $apartments)
        ->with('dashboard_maps', $dashboard_maps)
        ->with('dashboard_map_items_outputs', $dashboard_map_items_outputs)
        ->with('DashboardMapItems', $DashboardMapItems)
        // ->with('device_data_current', $device_data_current)
        ->with('device_types', $device_types)
        ->with('Devices', $Devices)
        ->with('Alarm_Codes', $Alarm_Codes)
        ->with('backgroundImagePaths', $backgroundImagePaths)
        ->with('output_causes', $moi)
        ->with('systemsData', $system_id)
        ->with('parent', DashboardItem::find(0))
        ->with('zonenames', $zonenames)
        ->with('timestampsArray', $timeStampsArray)
        ->with('devicesOutCurrent', $devicesOutCurrent)
        ->with('currentTime', $currentTime)
        ->with('mappingOutputs', $mappingOutputs)
        ->with('mappingout', $mappingout)
        ->with('DeviceDataCurrents', $DeviceDataCurrents)
        ->with('items', $items);
    }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
    public function editDashboardMaps($building_id, $system_id)
    {
        $data['thisBldg'] = Building::find($building_id);
        $data['thisSystem'] = System::find($system_id);
        $data['maps'] = [];

        /*to handle the background images in the bakground folder*/
        // $device_types = DeviceType::where('function', '=', 'Virtual')->get();
        $dashboard_maps = DashboardMap::where('system_id', $system_id)->get();
        // $device_data_current = DeviceDataCurrent::where('system_id', $system_id)->get();
        $backgroundImages = glob("images/backgroundImage/"."*.png");
        // $Alarm_Codes = AlarmCodes::get();
        // $Devices = Device::where('system_id', $system_id)->get();
        // $DashboardMapItems = DashboardMapItem::get();
        // $product_types = ProductType::get();
        $deviceTypes = DeviceType::get();
        $deviceSetpoints = DeviceSetpoints::where('system_id', $system_id)->get();
        $data['TagsAndSeasons'] = DashboardMapItem::select('dashboard_map_items.*', 'dashboard_map_items.x_position AS x-pos', 'dashboard_map_items.y_position AS y-pos')
        ->where('dashboard_maps.system_id', $system_id)
        ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')->get();
        // $data['devicesout'] = Device::where('system_id',$system_id) // Lookup devices which are outputs
        //   ->where('device_io','output')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->get();
        // $data['devicesin']= Device::where('system_id', $system_id) // Lookup devices which are inputs
        //   ->where('device_io','input')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->orderby('product_id')
        //   ->get();
        ////////////////////////////new device output mode with bypass /////////////////////////////////
        //check to see if there is empty spaces in the array and if there is remove first,
        //then check to see if there is still anything in there if non prompt error message and die
        $arrayX = (array)$dashboard_maps;
        foreach ($arrayX as $key => $value) {
            if (empty($value)) {
                unset($arrayX[$key]);
            }
        }
        if (empty($arrayX)) {
            $dashboard_maps->background_image = 'default.png';
        }
        foreach ($backgroundImages as $backgroundImage) {
            $filenames[] = substr(strrchr($backgroundImage, "/"), 1);
        }
        foreach ($dashboard_maps as $dashboard_map) {
            foreach ($filenames as $filename) {
                if ($dashboard_map['background_image'] == $filename) {
                    $selectMaps[] =  $filename;
                    $backgroundImagePaths[] = ("images/backgroundImage/".$filename); //$dashboard_map->label-1
                }
            }
        }
        $dashboard_map_items = DashboardMapItem::select('dashboard_map_items.*', 'device_data_current.*', 'devices.*', 'dashboard_map_items.label AS device_label', 'dashboard_map_items.id AS rid', 'device_types.function')
                            ->where('devices.system_id', $system_id)
                            ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
                            ->join('device_data_current', function ($join) {
                                $join->on('device_data_current.id', '=', 'dashboard_map_items.device_id');
                                $join->on('device_data_current.command', '=', 'dashboard_map_items.command');
                                $join->on('device_data_current.system_id', '=', 'dashboard_maps.system_id');
                            })
                            ->join('devices', function ($join) {
                                $join->on('devices.id', '=', 'dashboard_map_items.device_id');
                                $join->on('devices.system_id', '=', 'dashboard_maps.system_id');
                            })
                            ->join('device_types', 'dashboard_map_items.command', '=', 'device_types.command')
                            ->where('device_io', 'input')
                            ->where('retired', '!=', '1')
                            ->get();

        foreach ($deviceSetpoints as $deviceSetpoint) {
            foreach ($dashboard_map_items as $dashboard_map_item) {
                if ($dashboard_map_item->system_id == $deviceSetpoint->system_id) {
                    if (($dashboard_map_item->device_id == $deviceSetpoint->device_id) && ($dashboard_map_item->command == $deviceSetpoint->command)) {
                        $dashboard_map_item->alarm_high = $deviceSetpoint->alarm_high;
                        $dashboard_map_item->alarm_low  = $deviceSetpoint->alarm_low;
                        $dashboard_map_item->setpoint  = $deviceSetpoint->setpoint;
                    }
                }
            }
        }

        $dashboard_map_items_array = array();

        foreach ($deviceTypes as $deviceType) {
            foreach ($dashboard_map_items as $dashboard_map_item) {
                $dashboard_map_items_array[$dashboard_map_item->id][$dashboard_map_item->command] = $dashboard_map_item;
                if (empty($dashboard_map_item->setpoint)) {
                    $dashboard_map_item->alarm_high = $deviceType->alarm_high;
                    $dashboard_map_item->alarm_low  = $deviceType->alarm_low;
                    $dashboard_map_item->setpoint  = $deviceType->setpoint;
                }
            }
        }
        if (empty($dashboard_map_items) || is_null($dashboard_map_items)) {
            die('please check the Dashboard Map Items table`s id and commands');
            /*TODO: LOG TO SYSTEM_LOG*/
        }
        $availableDevices = Device::select('devices.*', 'devices.id as realID', 'device_data_current.*', 'product_types.commands as Check', 'device_data_current.system_id AS sid')
                            ->where('zone', '!=', '0')
                            ->where('devices.system_id', $system_id)
                            ->join('device_data_current', function ($join) {
                                $join->on('device_data_current.id', '=', 'devices.id');
                                $join->on('device_data_current.system_id', '=', 'devices.system_id');
                            })
                            ->join('product_types', function ($join) {
                                $join->on('product_types.product_id', '=', 'devices.product_id');
                            })
                            ->orderBy('zone', 'ASC')
                            ->where('retired', '!=', '1')
                            ->orderBy('name')
                            ->orderBy('devices.id')
                            ->orderBy('command')
                            ->where('command', '!=', '0')
                            ->get();

        //compare command with the product_type table and see if the command is matching with the productType->commands if not dont bother
        foreach ($availableDevices as $availableDevice) {
            if (strpos($availableDevice->Check, ',') !== false) { //http://stackoverflow.com/questions/4366730/check-if-string-contains-specific-words
                $inputs = explode(',', $availableDevice->Check); //explode the commands and compare with the command
                foreach ($inputs as $input) {
                    if ($input == $availableDevice->command) {
                        $newAvailableDevice[] = $availableDevice;
                    }
                }
            } else {
                $newAvailableDevice[] = $availableDevice;
            }
        }
        if (empty($newAvailableDevice)) {
            $newAvailableDevice['name'] = 1;
        }
        foreach ($newAvailableDevice as $key => $value) {
            if (empty($value)) {
                unset($newAvailableDevice[$key]);
            }
        }
        if (empty($newAvailableDevice)) {
            $newAvailableDevice['name'] = 1;
        }
        $newAvailableDevices = $newAvailableDevice;
        foreach ($deviceTypes as $deviceType) {
            foreach ($availableDevices as $availableDevice) {
                if ($availableDevice['command'] == $deviceType['command']) {
                    $availableDevice['commandString'] = $deviceType['function'];
                }
            }
        }
        $dashboard_map_items_outputs = DashboardMapItem::select('mapping_output.*', 'devices.*', 'device_data_current.*', 'dashboard_map_items.x_position AS x-pos', 'dashboard_map_items.id AS dashboardID', 'dashboard_map_items.map_id AS map_id', 'dashboard_map_items.y_position AS y-pos', 'dashboard_map_items.label AS device_label', 'dashboard_map_items.id AS rid', 'device_types.function')
                                    ->where('dashboard_maps.system_id', $system_id)
                                    ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
                                    ->join('device_data_current', function ($join) {
                                        $join->on('device_data_current.id', '=', 'dashboard_map_items.device_id');
                                        $join->on('device_data_current.command', '=', 'dashboard_map_items.command');
                                        $join->on('device_data_current.system_id', '=', 'dashboard_maps.system_id');
                                    })
                                    ->join('devices', function ($join) {
                                        $join->on('devices.id', '=', 'dashboard_map_items.device_id');
                                        $join->on('devices.system_id', '=', 'dashboard_maps.system_id');
                                    })
                                    ->join('mapping_output', function ($join) {
                                        $join->on('mapping_output.device_id', '=', 'dashboard_map_items.device_id');
                                        $join->on('mapping_output.system_id', '=', 'devices.system_id');
                                    })
                                    ->join('device_types', 'dashboard_map_items.command', '=', 'device_types.command')
                                    ->where('retired', '!=', '1')
                                    ->get();

        foreach ($deviceSetpoints as $deviceSetpoint) {
            foreach ($dashboard_map_items_outputs as $dashboard_map_items_output) {
                if ($dashboard_map_items_output->system_id == $deviceSetpoint->system_id) {
                    if (($dashboard_map_items_output->device_id == $deviceSetpoint->device_id) && ($dashboard_map_items_output->command == $deviceSetpoint->command)) {
                        $dashboard_map_items_output->alarm_high = $deviceSetpoint->alarm_high;
                        $dashboard_map_items_output->alarm_low  = $deviceSetpoint->alarm_low;
                        $dashboard_map_items_output->setpoint  = $deviceSetpoint->setpoint;
                    }
                }
            }
        }
        foreach ($deviceTypes as $deviceType) {
            foreach ($dashboard_map_items_outputs as $dashboard_map_items_output) {
                if (empty($dashboard_map_items_output->setpoint)) {
                    $dashboard_map_items_output->alarm_high = $deviceType->alarm_high;
                    $dashboard_map_items_output->alarm_low  = $deviceType->alarm_low;
                    $dashboard_map_items_output->setpoint  = $deviceType->setpoint;
                }
            }
        }

        static $moi;
        /*Add active inputs to $moi*/
        foreach ($dashboard_map_items_outputs as $key => $dashboard_map_items_output) {
            $input_commands = explode(',', (str_replace('.', '', $dashboard_map_items_output->active_inputs)));
            foreach ($input_commands as $input_command) {
                $ic=[ 'device_id'=>'0', 'command'=>'0'];
                $boom = explode(' ', trim($input_command));
                if (isset($boom[0]) && isset($boom[1])) {
                    $ic['device_id'] = $boom[0];
                    $ic['command'] = $boom[1];
                }

                $moi[] = [  /*mapping_output input*/
                'id'=> $dashboard_map_items_output->device_id,
                'command'=>$dashboard_map_items_output->command,
                'current_state'=>$dashboard_map_items_output->current_state,
                'name'=>$dashboard_map_items_output->name,
                'input'=>[
                  'name' => 'none',
                  'device_id' => (int)$ic['device_id'],
                  'command' => (int)$ic['command'],
                  'current_value' => 'none',
                  'state' => 0,
                  'alarm_state' => 0,
                  'alarm_index' => 0,
                  'active' => 1
                ]
                ];
            }
        }
        /*Add Primary inputs to $moi*/
        foreach ($dashboard_map_items_outputs as $key => $dashboard_map_items_output) {
            $input_commands = explode(',', (str_replace('.', '', $dashboard_map_items_output->inputs)));
            foreach ($input_commands as $input_command) {
                $ic=[ 'device_id'=>'0', 'command'=>'0'];
                $boom = explode(' ', trim($input_command));
                if (isset($boom[0]) && isset($boom[1])) {
                    $ic['device_id'] = $boom[0];
                    $ic['command'] = $boom[1];
                }

                $moi[] = [  /*mapping_output input*/
                'id'=> $dashboard_map_items_output->device_id,
                'command'=>$dashboard_map_items_output->command,
                'current_state'=>$dashboard_map_items_output->current_state,
                'name'=>$dashboard_map_items_output->name,
                'input'=>[
                  'name' => 'none',
                  'device_id' => (int)$ic['device_id'],
                  'command' => (int)$ic['command'],
                  'current_value' => 'none',
                  'state' => 0,
                  'alarm_state' => 0,
                  'alarm_index' => 0,
                  'active' => 0
                ]
                ];
            }
        }
        /*Make inputs active, if so indicated by active_inputs*/
        if (isset($moi)) {
            foreach ($moi as $outter_key => $outter_moi) {
                foreach ($moi as $inner_key => $inner_moi) {
                    if (/*duplicate output inputs*/
                    ($outter_moi['id'] == $inner_moi['id']) &&
                    ($outter_moi['command'] == $inner_moi['command']) &&
                    ($outter_moi['input']['device_id'] == $inner_moi['input']['device_id']) &&
                    ($outter_moi['input']['command'] == $inner_moi['input']['command']) &&
                    ($outter_moi['input']['active']  != $inner_moi['input']['active'])
                      ) {
                          $moi[$inner_key]['input']['active'] = 1;
                    }
                }
            }
            /*STRIP OUT THE DUPLICATES CAUSED BY THE DIFFERENT MAP_IDs and ACTIVE_INPUTS/INPUTS OVERLAP*/
            $moi = array_unique($moi, SORT_REGULAR);
        }


        $DeviceDataCurrents = DeviceDataCurrent::orderby('datetime', 'DESC')
        ->where('devices.system_id', $system_id)
        ->join('devices', function ($join) {
            $join->on('devices.id', '=', 'device_data_current.id');
            $join->on('devices.system_id', '=', 'device_data_current.system_id');
        })
        ->get();
        if (isset($moi)) {
            foreach ($moi as $key => $mapping_oi) {
                foreach ($DeviceDataCurrents as $ddc) {
                    if (($ddc->id == $mapping_oi['input']['device_id']) && ($ddc->command == $mapping_oi['input']['command'])) {
                        if ($ddc->name==null) {
                            $ddc->name ='id='.$ddc->id .' pid='.$ddc->product_id.'- No Name';
                        }
                        $moi[$key]['input']['current_value'] = $ddc->current_value;
                        $moi[$key]['input']['state'] = $ddc->current_state;
                        $moi[$key]['input']['alarm_state'] = $ddc->alarm_state;
                        $moi[$key]['input']['alarm_index'] = $ddc->alarm_index;
                        $moi[$key]['input']['name'] = $ddc->name;
                    }
                }
            }
        }


        // dd($output_causes);
        if (!isset($dashboard_map_items_outputs)) {
            $backgroundImagePaths[] = "images/backgroundImage/default.png";
        }

        if (empty($dashboard_map_items_outputs)) {
            $dashboard_map_items_outputs = $dashboard_map_items;
        }


        $DashboardMapItems = DashboardMapItem::where('dashboard_maps.system_id', $system_id)
        ->select('dashboard_map_items.*', 'dashboard_maps.label AS printLabel')
        ->join('dashboard_maps', 'dashboard_map_items.map_id', '=', 'dashboard_maps.id')
        ->get();

        if (!isset($backgroundImagePaths)) {
            $backgroundImagePaths[] = "images/backgroundImage/default.png";
        }

        $dar = (array)$dashboard_map_items;
        foreach ($dar as $key => $value) {
            if (empty($value)) {
                $backgroundImagePaths[] = "images/backgroundImage/default.png";
                (array)$apartments[] =[
                'id'=> 1,'setpoints'=> 1,'device_id'=>1,'command'=> 1,'map_id'=> 1 ,'inhibited'=>1,'x-pos'=>1,'y-pos'=>1,'state'=>1,'name'=> 'Test','tag'=>1,'currentVal'=>1,'status'=> 1,'high'=> 1,'low'=> 1,'location'=> 1,'desc'=> 1,'alarm_index'=> 1
                ];
            } else {
                foreach ($dashboard_map_items as $dashboard_map_item) {
                    if ((strlen($dashboard_map_item->name)) >= 15) {
                        $text = $dashboard_map_item->name;
                        $middle = strpos($text, ' ', floor(strlen($text)*0.5));
                        $string1 = substr($text, 0, $middle);
                        $string2 = substr($text, $middle);
                        $dashboard_map_item->name = $string1."<br>".$string2;
                    }
                    if (($data['thisSystem']->temperature_format == 'F') && ($dashboard_map_item->function == 'Temperature')) {
                        /*if the function is temperature and the building requires F, convert from C to F*/
                        $dashboard_map_item->current_value = ConvFunc::convertCelciusToFarenheit(round($dashboard_map_item->current_value, 1));
                        $dashboard_map_item->alarm_low =  ConvFunc::convertCelciusToFarenheit($dashboard_map_item->alarm_low);
                        $dashboard_map_item->alarm_high =  ConvFunc::convertCelciusToFarenheit($dashboard_map_item->alarm_high);
                        $dashboard_map_item->setpoint = ConvFunc::convertCelciusToFarenheit($dashboard_map_item->setpoint);
                    }
                    if ($dashboard_map_item->function == 'Pressure') {
                        /*if  the function is a pressure, just rounded to 2 decimal places*/
                        $dashboard_map_item->current_value = round($dashboard_map_item->current_value, 2);
                    }
                    if ($dashboard_map_item->function == 'Flow') {      //if the fuction is Flow just rounded to 2 decimal places
                        $dashboard_map_item->current_value = round($dashboard_map_item->current_value, 2);
                    }
                    if ($dashboard_map_item->device_label == 'Smoke') {   //when it is smoke alarm, there should not be values just state
                        $dashboard_map_item->alarm_high = 1;
                        $dashboard_map_item->alarm_low = 0;
                    }
                    if (($dashboard_map_item->device_label == 'Burner On') || ($dashboard_map_item->device_label == 'Burner Fault')) {
                    }
                    if (($dashboard_map_item->physical_location != 'Temperature') && ($dashboard_map_item->current_state == 0)) {
                        /*shows the current state to be on*/
                        $dashboard_map_item->current_state = "ON";
                    } else {   //shows the current state to be off
                        $dashboard_map_item->current_state = "OFF";
                    }
                    if ($dashboard_map_item->name == null) {    //if there if no name on the Dashboard map look for the name om the device table
                        $dashboard_map_item->name = "Unknown name";
                    }
                    (array)$apartments[] =  [
                    'id'          => $dashboard_map_item->rid,//recnum in dashboard map items table
                    'setpoints'   => $dashboard_map_item->setpoint,
                    'device_id'   => $dashboard_map_item->device_id,
                    'command'     => $dashboard_map_item->command,
                    'map_id'      => $dashboard_map_item->map_id,
                    'inhibited'   => $dashboard_map_item->inhibited,
                    'x-pos'       => $dashboard_map_item->x_position,
                    'y-pos'       => $dashboard_map_item->y_position,
                    'state'       => $dashboard_map_item->alarm_state,
                    'name'        => $dashboard_map_item->name,
                    'tag'         => $dashboard_map_item->function,
                    'currentVal'  => $dashboard_map_item->current_value,
                    'status'      => $dashboard_map_item->current_state,
                    'high'        => $dashboard_map_item->alarm_high,
                    'low'         => $dashboard_map_item->alarm_low,
                    'location'    => $dashboard_map_item->physical_location,
                    'desc'        => $dashboard_map_item->functional_description,
                    'alarm_index' => $dashboard_map_item->alarm_index,
                    'map_id'      => $dashboard_map_item->map_id,
                    'reporttime'  => $dashboard_map_item->reporttime,
                    'updated_at'  => $dashboard_map_item->updated_at,
                    'retired'     => $dashboard_map_item->retired,
                    'product_id'  => $dashboard_map_item->product_id,
                    'units'       => ConvFunc::unitconv($dashboard_map_item->units, $data['thisSystem']->temperature_format),
                    'comments'    => $dashboard_map_item->comments,
                    'overdue'     => (empty($NoRep))?"NO":"YES",
                    'last_report_time'  =>  $dashboard_map_item->datetime
                    ];
                }
                if (empty($apartments)) {
                    foreach ($dashboard_maps as $key => $value) {
                        $x = $key+1;

                        (array)$apartments[] =[
                        'id'=> 1,'setpoints'=> 1,'device_id'=>1,'command'=> 1,'map_id'=> "$x" ,'inhibited'=>1,'x-pos'=>1,'y-pos'=>1,'state'=>1,'name'=> 'Test','tag'=>1,'currentVal'=>1,'status'=> 1,'high'=> 1,'low'=> 1,'location'=> 1,'desc'=> 1,'alarm_index'=> 1
                        ];
                        //later this field needs to dreck the page to Dashboard Map Edit page so user can add DMI's
                    }
                }
            }
        }
        $thisBldg = Building::find($building_id);
        $thisSystem = System::find($system_id);
        $zonenames = Zone::where('system_id', $thisSystem->id)->get();
        $mappingout = MappingOutput::where('system_id', $thisSystem->id)
        ->orderby('device_id')->get();
        $mappingOutputs = array();
        foreach ($mappingout as $output) {
            $mappingOutputs[$output->device_id] = $output->overridetime;
        }
        $currentTime = time();
        $devicesCurrent = DeviceDataCurrent::where('system_id', $system_id)
        ->orderby('datetime', 'DESC')
        ->groupby('id', 'command')
        ->get();
        $timeStampsArray = array();
        $devicesOutCurrent = array();
        foreach ($devicesCurrent as $currentOut) {
            $timeStampsArray[$currentOut->id][$currentOut->command] = $currentOut->datetime;
            $devicesOutCurrent[$currentOut->id][$currentOut->command] = $currentOut;
        }
        $zonekey = sizeof($zonenames)-1; //6
        $mapkey = sizeof($dashboard_maps)-1; //4
        // $data['devicesout'] = Device::where('system_id',$system_id) // Lookup devices which are outputs
        //   ->where('device_io','output')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->orderby('product_id')
        //   ->orderby('id')
        //   ->get();
        // $data['devicesin'] = Device::where('system_id', $system_id) // Lookup devices which are inputs
        //   ->where('device_io','input')
        //   ->where('status','1')
        //   ->where('retired','<>','1')
        //   ->orderby('zone')
        //   ->orderby('product_id')
        //   ->orderby('id')
        //   ->get();
        $mapID1 = DashboardMapItem::orderBy('created_at', 'DESC')
        ->limit(1)
        ->get();

        foreach ($dashboard_maps as $key => $dashboard_map) {
            foreach ($mapID1 as $mapID2) {
                if ($dashboard_map->id == $mapID2->map_id) {
                    $data['mapID'] = $key;
                }
            }
        }
        $item = DashboardItem::where('system_id', $system_id)
        ->where('label', "System Status")
        ->get()
        ->first();

        return View::make('dashboard-maps.edit', $data)
        ->with('building_id', $building_id)
        ->with('system_id', $system_id)
        ->with('pageid', $item)
        ->with('apartments', $apartments)
        ->with('dashboard_maps', $dashboard_maps)
        ->with('dashboard_map_items_outputs', $dashboard_map_items_outputs)
        ->with('DashboardMapItems', $DashboardMapItems)
        // ->with('device_data_current', $device_data_current)
        // ->with('device_types', $device_types)
        // ->with('Devices', $Devices)
        ->with('backgroundImagePaths', $backgroundImagePaths)
        // ->with('Alarm_Codes', $Alarm_Codes)
        ->with('newAvailableDevices', $newAvailableDevices)
        ->with('availableDevices', $availableDevices)
        ->with('DeviceDataCurrents', $DeviceDataCurrents)
        ->with('output_causes', $moi)
        ->with('systemsData', $system_id)
        ->with('parent', DashboardItem::find(0))
        ->with('zonenames', $zonenames)
        ->with('timestampsArray', $timeStampsArray)
        ->with('devicesOutCurrent', $devicesOutCurrent)
        ->with('currentTime', $currentTime)
        ->with('mappingOutputs', $mappingOutputs)
        ->with('mappingout', $mappingout);
    }

  /**
  * Updates the dashboard maps page.
  *
  * @param  int  $id
  * @return Response
  */
    public function updateDashboardMaps($id, $sid)
    {
        $newItem = new DashboardMapItem();
        $NewItemForMap = new DashboardMap();
        $dashboard_maps = DashboardMap::where('system_id', $sid)->get();
        $dash_map_ids = array();
        foreach ($dashboard_maps as $dm) {
            $dash_map_ids[] = $dm->id;
        }
        $DashboardMapItems = DashboardMapItem::whereIn('map_id', $dash_map_ids)->get();
    
        /*get the background png file names*/
        $backgroundImages = glob("images/backgroundImage/"."*.png");
        foreach ($backgroundImages as $backgroundImage) {
            $filenames[] = substr(strrchr($backgroundImage, "/"), 1);
        }

        foreach ($dashboard_maps as $key => $dashboard_map) {
            $selectMaps[$key] =  $dashboard_map;
        }

        if (count((array)Input::all())) {
            /**************************
            SAVE NEW POSITIONS
            ***************************/
            if (Input::get('positionSave')) {
                foreach (Input::except('_token') as $inputName => $inputValue) {
                    foreach ($DashboardMapItems as $DashboardMapItem) {
                        if ($inputName == 'dmi-'.$DashboardMapItem->id.'-x_position') {
                            $DashboardMapItem->x_position = $inputValue;
                        }
                        if ($inputName == 'dmi-'.$DashboardMapItem->id.'-y_position') {
                            $inputValueY = $inputValue;
                            $DashboardMapItem->y_position = $inputValueY;
                        }
                        $DashboardMapItem->save();
                    }
                }
                Session::flash("success", "The new positions have been updated");
            }
            /**************************
            DELETE DMI
            **************************/
            if (Input::get('delete')) {
                // dd(Input::all());
                $command = Input::get('command');
                $device_id = Input::get('device_id');
                $map_id = Input::get('map_id');
                ;
                $oldItem = DashboardMapItem::where('command', $command)
                ->where('device_id', $device_id)
                ->where('map_id', $map_id)
                ->first();


                if ($oldItem->delete()) {
                    Session::flash("success", "$oldItem->label has been removed from the list");
                }
            }
            /**************************
            DELETE SEASON
            **************************/
            if (Input::get('deleteSeason')) {
                $command = Input::get('command');
                $device_id = Input::get('device_id');
                $map_id = Input::get('map_id');
                $oldItem = DashboardMapItem::where('command', $command)

                ->where('device_id', $device_id)
                ->where('map_id', $map_id)
                ->first();

                if ($oldItem->delete()) {
                    Session::flash("success", "The Season Tag has been deleted");
                }
            }
            /**************************
            DELETE TITLE
            **************************/
            if (Input::get('deleteTitle')) {
                $command = Input::get('command');
                $device_id = Input::get('device_id');
                $map_id = Input::get('map_id');
                $oldItem = DashboardMapItem::where('command', $command)
                ->where('device_id', $device_id)
                ->where('map_id', $map_id)
                ->first();

                if ($oldItem->delete()) {
                    Session::flash("success", " The Title has been deleted");
                }
            }
            /**************************
            SAVE NEW "AVAILABLE DEVICES"
            **************************/
            if (Input::get('SavetheDevice')) {
                $newItem->command = Input::get('command');
                $newItem->device_id = Input::get('id');
                $newItem->x_position = Input::get('x_position');
                $newItem->y_position = Input::get('y_position');
                $newItem->label = Input::get('label');
                $newItem->icon = Input::get('icon');
                $mapID = Input::get('map_idForNew');
                $newItem->map_id = $selectMaps[Input::get('map_idForNew')]->id;

                $existing = 0;
                foreach ($DashboardMapItems as $DashboardMapItem) {
                    if (($DashboardMapItem->map_id == $newItem->map_id) && ($DashboardMapItem->device_id == $newItem->device_id) && ($DashboardMapItem->command == $newItem->command)) {
                        $existing = 1;
                    }
                }
                if ($existing == 0) {
                    $newItem->icon = " ";
                    $newItem->save();
                    Session::flash("success", "Device has been created");
                } else {
                    Session::flash("error", "Device is already exist");
                }
            }
            /**************************
            SAVE NEW "AVAILABLE CONTROL"
            **************************/
            if (Input::get('SavetheControl')) {
                $newItem->command = Input::get('command');
                $newItem->device_id = Input::get('id');
                $newItem->x_position = Input::get('x_position');
                $newItem->y_position = Input::get('y_position');
                $newItem->label = Input::get('label');
                $newItem->icon = Input::get('icon');
                $a = Input::get('map_idForNew1');
                $newItem->map_id = $selectMaps[Input::get('map_idForNew1')]->id;
                $existing = 0;
                foreach ($DashboardMapItems as $DashboardMapItem) {
                    if (($DashboardMapItem->map_id == $newItem->map_id) && ($DashboardMapItem->device_id == $newItem->device_id) && ($DashboardMapItem->command == $newItem->command)) {
                        $existing = 1;
                    }
                }
                if ($existing == 0) {
                    $newItem->save();
                    Session::flash("success", "$newItem->label has been created");
                } else {
                    Session::flash("error", "$newItem->label is already exist");
                }
            }
            /**************************
            ADD TITLE
            **************************/
            if (Input::get('AddTitle')) {
                $newItem->command = 334;
                $newItem->device_id = 334;
                $newItem->x_position = 00;
                $newItem->y_position = 00;
                $newItem->label = 'Tag';
                $a = Input::get('map_idForNew');
                $newItem->map_id = $selectMaps[Input::get('map_idForNew')]->id;
                $existing = 0;

                foreach ($DashboardMapItems as $DashboardMapItem) {
                    if (($DashboardMapItem->map_id == $newItem->map_id) && ($DashboardMapItem->label == $newItem->label)) {
                        $existing = 1;
                    }
                }
                if ($existing == 0) {
                    $newItem->save();
                    Session::flash("success", "the Title was successfully created");
                } else {
                    Session::flash("error", "the Title for this page already exist");
                }
            }
            /**************************
            ADD SEASON TAG
            **************************/
            if (Input::get('AddSeason')) {
                $newItem->command = 333;
                $newItem->device_id = 333;
                $newItem->x_position = 00;
                $newItem->y_position = 00;
                $newItem->label = 'Season';
                $a = Input::get('map_idForNew');
                $newItem->map_id = $selectMaps[Input::get('map_idForNew')]->id;
                $existing = 0;

                foreach ($DashboardMapItems as $DashboardMapItem) {
                    if (($DashboardMapItem->map_id == $newItem->map_id) && ($DashboardMapItem->label == $newItem->label)) {
                        $existing = 1;
                    }
                }
                if ($existing == 0) {
                    $newItem->save();
                    Session::flash("success", "the Season Tag was successfully created");
                } else {
                    Session::flash("error", "the Season Tag is already exist");
                }
            }
            /**************************
            DELETE TAB
            **************************/
            if (Input::get('deleteTag')) {
                if (isset($selectMaps[Input::get('map_idForNew3')]->id)) {
                    $ids = $selectMaps[Input::get('map_idForNew3')]->id;
                    $oldMap = DashboardMap::where('id', $ids)->first();
                    $map_id = $selectMaps[Input::get('map_idForNew3')]->id;
                    $oldItem = DashboardMapItem::where('map_id', $map_id)
                    ->get();
                    foreach ($oldItem as $old) {
                        $old->delete();
                    }
                    if ($oldMap->delete()) {
                        Session::flash("success", "Tag has been deleted");
                    } else {
                        Session::flash("error", "cant delete");
                    }
                } else {
                    Session::flash("success", "Tag has been deleted");
                }
            }

            //image upload , update and tag add with image
            $b = null;
            $dashboard_maps_for_new = DashboardMap::orderby('id', 'ASC')
            ->get();

            /**************************
            NEW TAB
            **************************/
            if (Input::get('newfile')) {
                if (Input::hasFile('image')) {//is there an image attached to the upload
                    $image = Input::file('image');
                    //important info, if the file is bigger than 2mg it wont upload unless we change the php.info file
                    $destinationPath = 'images/backgroundImage/';
                    $filename =$sid."_".$image->getClientOriginalName();//get the name of the file and add the system id in front of it

                    Input::file('image')->move($destinationPath, $filename);
                    $count = 1;
                    foreach ($dashboard_maps_for_new as $a) {
                        if ($a->id != $count) {
                            $count = $count;
                            break;
                        } else {
                            $count = $count + 1;
                        }
                    }
                    $b = Input::get('map_idForNew2');

                    //find if the image is exist, if yes go to if condition, not go to else condition
                    if (isset($selectMaps[Input::get('map_idForNew2')]->id)) {//update the old tag
                        $FindCorrectId = $selectMaps[Input::get('map_idForNew2')]->id;
                        foreach (Input::except('_token') as $inputName => $inputValue) {
                            foreach ($dashboard_maps as $dashboard_map) {
                                if ($inputName == (int)'map_idForNew2') {
                                    if ($dashboard_map->id == $FindCorrectId) {
                                        $d_map_label = Input::get('titleName');
                                        $d_map_tab_name = Input::get('tabName');
                                        $dashboard_map->background_image = $filename;
                                        $dashboard_map->zone_numbers = Input::get('zoneNumber');
                                        $dashboard_map->tab_names = $d_map_tab_name;
                                        $dashboard_map->label = ((isset($d_map_label))? $d_map_label : $d_map_tab_name );
                                        $dashboard_map->save();
                                    }
                                }
                            }
                            if ($dashboard_map->save()) {
                                Session::flash("success", "The Background image is updated");
                            } else {
                                Session::flash("error", "Could not update the background image");
                            }
                        }
                    } else { //for creating new tag with image
                        $d_map_label = Input::get('titleName');
                        $d_map_tab_name = Input::get('tabName');
                        $NewItemForMap->id = $count;
                        $NewItemForMap->background_image = $filename;
                        $NewItemForMap->zone_numbers = Input::get('zoneNumber');
                        $NewItemForMap->tab_names = $d_map_tab_name;
                        $NewItemForMap->label = ($d_map_label == '')? $d_map_tab_name : $d_map_label;
                        $NewItemForMap->system_id = $sid;
                        if (($NewItemForMap->save()) && ($NewItemForMap->label != null) && ($NewItemForMap->tab_names != null)) {
                            Session::flash("success", "New Tab Created with an image");
                        } else {
                            Session::flash("error", "please fill out the necessary fields");
                        }
                        $NewItemForMap->save();
                        Session::flash("success", "The new Background image is uploaded");
                    }
                } else { //to change the title and tab name
                    $FindCorrectId = $selectMaps[Input::get('map_idForNew2')]->id;

                    foreach (Input::except('_token') as $inputName => $inputValue) {
                        foreach ($dashboard_maps as $dashboard_map) {
                            if ($inputName == (int)'map_idForNew2') {
                                if ($dashboard_map->id == $FindCorrectId) {
                                    $dashboard_map->zone_numbers = Input::get('zoneNumber');
                                    $dashboard_map->tab_names = Input::get('tabName');
                                    $dashboard_map->label = Input::get('titleName');
                                    $dashboard_map->save();
                                }
                            }
                        }
                        if ($dashboard_map->save()) {
                            Session::flash("warning", "Title and/or Tab names updated");
                        } else {
                            Session::flash("error", "Could not change the title and tab names");
                        }
                    }
                }
            }
      
            $input = Input::except('_token');

            return Redirect::route('dashboardmaps.edit', [$id, $sid]);
        }
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id               The building ID.
   * @param  int  $sid              The system ID.
   * @param  int  $instruction      Instruction whether or not a toggle command '0' or override '1' command is being sent.
   * @param  int  $instruction_s    Instruction state '1' or '0'.
   * @param  int  $instruction_t    Time duration of the insruction.
   * @return Response
   */
    public function DeployInstruction($id, $sid, $instruction, $input)
    {
        $TOGGLE_OFF = 39;
        $TOGGLE_ON = 38;
        $OVERRIDE_OFF = 37;
        $OVERRIDE_ON = 36;
        $NORMAL_OPERATION = 35;
        $time = localtime(time(), true);
        $timestamp = ( (int)$time['tm_year'] + 1900 ) . "/" . ( (int)$time['tm_mon'] + 1 ) . "/" . $time['tm_mday'] . "/" . $time['tm_hour'] . "/" . $time['tm_min'] . "/" . $time['tm_sec'];
        $filetime = ( (int)$time['tm_year'] + 1900 ) . "_" . ( (int)$time['tm_mon'] + 1 ) . "_" . $time['tm_mday'] . "_" . $time['tm_hour'] . "_" . $time['tm_min'] . "_" . $time['tm_sec'];

        if ($instruction == 0) {
            $system_instruction = $TOGGLE_OFF - (int)$input['Togglestate'];
            $system_overridetime = 10;
        } elseif ($instruction == 1) {
            if ((int)$input['Overridetime'] >= 0) {
                /*Accomodate system status radio buttons and zonestatus toggle button*/
                if (isset($input['Override'])) {
                    if ($input['Override'] == '1') {
                        $system_instruction = $OVERRIDE_ON; /*system status on selection*/
                    } else if ($input['Override'] == '0') {
                        $system_instruction = $OVERRIDE_OFF; /*system status off selection*/
                    } else {
                        $system_instruction = $OVERRIDE_ON; /*zonestatus on selection*/
                    }
                } else {
                    $system_instruction = $OVERRIDE_OFF; /*zonestatus off selection*/
                }
                $system_overridetime = (int)$input['Overridetime']*60;
            } elseif ((int)$input['Overridetime'] == -1) {
                $system_instruction = $NORMAL_OPERATION;
                $system_overridetime = 0;
            }
        }

        $line = $input['device'] ." ". $input['command'] ." ". $system_instruction ." ". $system_overridetime ." ". $timestamp ;

        $filename = 'override_instruction_'.$sid.'.txt';

        RemoteTask::deployFile($sid, '/var/2020_algo_states/override_commands', "or_".$sid."_".$filetime.".txt", $line);
        RemoteTask::deployFile($sid, '/var/2020_algorithm/archive', "or_".$sid."_".$filetime.".txt", $line);
    }
}
