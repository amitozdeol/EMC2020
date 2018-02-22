<?php
class ReportsController extends BaseController
{
  /**
   * Displays an empty reports chart with all available devices listed
   * @param  integer $building_id
   * @param  integer $system_id
   * @return View
   */
    public function index($building_id, $system_id)
    {
        $building = Building::find($building_id);
        $system = System::find($system_id);
        $routePrefix = Input::get('routeprefix');
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
        /**
     * This will set up the range and type of data for the current report
     * If this data is in the session then it must have gotten there from the
     * form being submitted.
     * If it's not set then we'll default to the current day and the first
     * available data type
     */
        if (Session::get('startDate') !== null) {
            $startDate = new DateTime(Session::get('startDate'));
        } else {
            $startDate = new DateTime();
        }
        if (Session::get('endDate') !== null) {
            $endDate = new DateTime(Session::get('endDate'));
        } else {
            $endDate = new DateTime();
        }
        if (!$function = Session::get('function')) {
            $function = 0;
        }
        if (!$retired = Session::get('retired')) {
            $retired = 0;
        }
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
                              ->get();
        //Get a list of function names(not distinct), commands, units, digital(0 or 1)
        $FunctionObj = DeviceType::select('function', 'command', 'units', 'digital')
        ->whereIn('command', function ($subquery) use ($system_id, $retired) {
            $subquery //convert comma seperated string into rows of values
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
        ->get();
        //Add function name property to DeviceObj.
        foreach ($DeviceObj as $CurDevice) {
            foreach ($FunctionObj as $CurFunc) {
                if ($CurDevice->command == $CurFunc->command) {
                    $CurDevice->function = $CurFunc->function;
                }
            }
        }
        $FuncData = [];
        //create $FuncData from FunctionObj and $DeviceObj, that'll be passed on to the view
        foreach ($FunctionObj as $cur_func) {
            if (! array_key_exists($cur_func->function, $FuncData)) {
                $FuncData[$cur_func->function] = [];
                $FuncData[$cur_func->function]["Digital"] = $cur_func->digital;
                $FuncData[$cur_func->function]["Units"] = $cur_func->units;
                $FuncData[$cur_func->function]["dev_name"] = [];
                $FuncData[$cur_func->function]["dev_id"] = [];
                $FuncData[$cur_func->function]["zone_name"] = [];
                $FuncData[$cur_func->function]["zone_id"] = [];
                if ($cur_func->function == "Water") {
                    $FuncData[$cur_func->function]["chart_type"] = "bar";
                } else {
                    $FuncData[$cur_func->function]["chart_type"] = "line";
                }
                foreach ($DeviceObj as $cur_device) {
                    if ($cur_device->function == $cur_func->function) {
                        array_push($FuncData[$cur_func->function]["dev_name"], $cur_device->name);
                        array_push($FuncData[$cur_func->function]["dev_id"], $cur_device->id);
                        array_push($FuncData[$cur_func->function]["zone_name"], $cur_device->zonename);
                        array_push($FuncData[$cur_func->function]["zone_id"], $cur_device->zone);
                    }
                }
            }
        }
        $functionList = [];     //list of distinct function names
        foreach ($FunctionObj as $cur_func) {
            if (! in_array($cur_func->function, $functionList)) {
                array_push($functionList, $cur_func->function);
            }
        }
        //Convert Celcius to F
        $function = $functionList[$function];
        if ($function === 'Temperature' && $system->temperature_format == 'F') {
            $FuncData['Temperature']["Units"] = "degrees F";
        }
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
         $data['thisBldg']        = $building;
         $data['thisSystem']      = $system;
         $data['retired']         = $retired;
         $data['startDate']       = $startDate;
         $data['endDate']         = $endDate;
         $data['functionList']    = $functionList;
         $data['InitData']        = $FuncData;
         $data['items']           = $items;
         $data['routePrefix']     = $routePrefix;
        return View::make('reports.index', $data);
    }

  /**
   * Handle reports form submissions
   * @param  integer $building_id
   * @param  integer $system_id
   * @return Redirect
   */
    public function update($building_id, $system_id)
    {
        $SelectOptionTime = Input::get('timerange');
        $retired = intval(Input::get('retired'));
    
        // $datediff = date_diff($startDate, $endDate);
        // // Check that the time range is no more that 7 days
        // if($datediff->days > 7) {
        //   $endDate = clone $startDate;
        //   $endDate->add(new DateInterval('P6D'));
        //
        //   Session::flash('error', 'You have requested too much data at once. The results will be limited to a 7 day range ending at <strong>' . date_format($endDate, 'j F, Y') . '</strong>');
        // }
        log::info("update");
        $startDate = new DateTime();
        $endDate = new DateTime();

        switch ($SelectOptionTime) {
            case "Today":
                $startDate = new DateTime();
                $endDate = new DateTime();
                break;
            case "Last Week":
                $startDate->sub(new DateInterval('P7D'));
                $endDate = new DateTime();
                break;
            case "Last Month":
                $startDate->sub(new DateInterval('P1M'));
                $endDate = new DateTime();
                break;
            case "Last Year":
                $startDate->sub(new DateInterval('P1Y'));
                $endDate = new DateTime();
                break;
            case "Custom":
                $startDate = new DateTime(Input::get('start-date'));
                $endDate   = new DateTime(Input::get('end-date'));
                break;
        }
        // Check for a backwards time range
        if ($endDate < $startDate) {
            Session::flash('error', '<strong>Whoops</strong> It looks like the Start Date is after the End Date.');
            return Redirect::back();
        }
        /**
     * When the page reloads it will check the session for these values before falling back to the defaults
     */
        Session::flash('startDate', date_format($startDate, 'Y-m-d'));
        Session::flash('endDate', date_format($endDate, 'Y-m-d'));
        Session::flash('retired', $retired);
        Session::flash('SelectOptionTime', $SelectOptionTime);
        return Redirect::route('reports.index', [$building_id, $system_id]);
    }


  /**
   * Responde to background calls for reports data
   * @param  integer $building_id
   * @param  integer $system_id
   * @return string A json encoded array of the data being requested
   */
    public function ajax($building_id, $system_id)
    {
        $system = System::find($system_id);
        $json = [];
        $startfetchDate = Input::get('startdateToFetch');
        $endfetchDate = Input::get('enddateToFetch');
        $DEV_ID_Array = Input::get('dataFuncDeviceID');
        $DEV_Names_Array = Input::get('dataFuncDeviceName');
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
            $chart_type = Input::get('dataFunction');
            //$chart_type = "Relay";
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
            $DigitalDevice = DB::table('device_types')
            ->select('digital')
            ->where('function', $chart_type)
            ->first();
            $device_data = [];
            //================= For Digital function type ===========================
            if ($DigitalDevice->digital == 1) { //Digital
                $startTime = date_format($startfetchDate, 'Y-m-d 00:00:00');
                $endTime = date_format($endfetchDate, 'Y-m-d 23:59:59');
                if (Input::get('timeVtime') == "true") {  //time vs time bar chart
                    if (!Cache::has('report'.$chart_type.$system_id.$startTime.$endTime.'timeVtime')) {
                        $device_data = DB::table('events')
                        ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as duration, created_at, device_id'))
                        ->where('system_id', $system_id)
                        ->where('cleared_at', '>=', $startTime)
                        ->where('created_at', '<=', $endTime)
                        ->where('id', '>', 0)
                        ->whereIn('device_id', $DEV_ID_Array)
                        ->orderByRaw('CAST(created_at AS DATE) ASC')
                        ->groupBy(DB::raw('CAST(created_at AS DATE)'))
                        ->get();
                        foreach ($device_data as $data_point) {
                            $device_name = Device::where('system_id', $system_id)->where('id', $data_point->device_id)->pluck('name');
                            // Make an array to store each devices data if needed
                            if (! isset($json['data'][$device_name.'('.$data_point->device_id.')']) || ! isset($json['data'][$data_point->device_id.'time'])) {
                                $json['data'][$device_name.'('.$data_point->device_id.')'] = [];
                                $json['data'][$data_point->device_id.'time'] = [];
                                $json['DeviceNameForLegend'] = [];
                            }
                            // Add data
                            array_push($json['data'][$device_name.'('.$data_point->device_id.')'], $data_point->duration);
                            // Add time
                            array_push($json['data'][$data_point->device_id.'time'], $data_point->created_at);
                            // Add DeviceName in Array
                            if (!in_array($device_name.'('.$data_point->device_id.')', $json['DeviceNameForLegend'], true)) {
                                array_push(
                                    $json['DeviceNameForLegend'],
                                    $device_name.'('.$data_point->device_id.')'
                                );
                            }
                        }
                        Cache::put('report'.$chart_type.$system_id.$startTime.$endTime.'timeVtime', json_encode($json), 10);
                    } else {
                        $json = json_decode(Cache::get('report'.$chart_type.$system_id.$startTime.$endTime.'timeVtime'));
                    }
                } else {
                    if (!Cache::has('report'.$chart_type.$system_id.$startTime.$endTime.'1')) {    // '1' is for Digital devices
                        $device_data = DB::table('events')
                        ->where('system_id', $system_id)
                        ->where('cleared_at', '>=', $startTime)
                        ->where('created_at', '<=', $endTime)
                        ->where('id', '>', 0)
                        ->whereIn('device_id', $DEV_ID_Array)
                        ->orderBy('created_at', 'ASC')
                        ->get();
                        foreach ($device_data as $data_point) {
                            $DEV_index = array_search($data_point->id, $DEV_ID_Array);
                            $DEV_name = $DEV_Names_Array[$DEV_index];
                            // $device_name = Device::where('system_id', $system_id)->where('id', $data_point->device_id)->pluck('name');
                            // Make an array to store each devices data if needed
                            if (! isset($json['data'][$DEV_name]) || ! isset($json['data'][$data_point->device_id.'time'])) {
                                $json['data'][$DEV_name] = [];
                                $json['data'][$data_point->device_id.'time'] = [];
                                $json['DeviceNameForLegend'] = [];
                            }
                            /* Add 0 to created_at-1 location, 1 at created_at location.
                             Add 1 to cleared_at location, 0 at cleared_at+1 location.
                            */
                            $time_add_sub = [$data_point->created_at => -1, $data_point->cleared_at => 1];
                            foreach ($time_add_sub as $date => $value) {
                                // Add data
                                array_push($json['data'][$DEV_name], 0);  //add 0 value before/after created/cleared_at
                                // Add time
                                array_push($json['data'][$data_point->device_id.'time'], date("Y-m-d H:i:s", strtotime($date) + $value)); //subtract/add 1 second from created/cleared_at
                                // Add data
                                array_push($json['data'][$DEV_name], 1); //add 1 value at created_at and cleared_at
                                // Add time
                                array_push($json['data'][$data_point->device_id.'time'], $date);  //add created/cleared_at time
                            }
                            // Add DeviceName in Array
                            if (!in_array($DEV_name, $json['DeviceNameForLegend'], true)) {
                                array_push(
                                    $json['DeviceNameForLegend'],
                                    $DEV_name
                                );
                            }
                        }
                        Cache::put('report'.$chart_type.$system_id.$startTime.$endTime.'1', json_encode($json), 1);
                    } else {
                        $json = json_decode(Cache::get('report'.$chart_type.$system_id.$startTime.$endTime.'1'));
                    }
                }
            } //================= For Analog function type ===========================
            else {
                if (!Cache::has('report'.$chart_type.$system_id.$startTime.$endTime.'0')) {    // '0' is for Analog devices
                    //===================Pick the right database table====================
                    if ($currentdatediff->days <= 14) {  //first two weeks
                        $data_table_name = $daterange <=2 ? 'device_data' : 'device_data_hourly_ave';
                    } else {
                        $data_table_name = $daterange <=2 ? 'device_data_long_term' : 'device_data_hourly_ave';
                    }
                    if ($daterange > 300) {
                        //getting day average
                        $device_data = DB::table($data_table_name)
                          ->select(DB::raw('DATE_FORMAT(datetime, "%Y-%m-%d") as Days, AVG(current_value) as current_value, AVG(setpoint) as setpoint'), 'id', 'system_id', 'command', 'datetime')
                          ->where('system_id', $system_id)
                          ->where('date', '>=', $startTime)
                          ->where('date', '<=', $endTime)
                          ->where('id', '>', 0)
                          ->whereIn('command', function ($subquery1) use ($chart_type) {
                            $subquery1->from('device_types')
                            ->select('command')
                            ->where('function', $chart_type);
                          })
                          ->whereIn('id', $DEV_ID_Array)
                          ->orderBy('date', 'ASC')
                          ->groupBy('Days', 'id', 'system_id', 'command')
                          ->get();
                    } else {
                        $device_data = DB::table($data_table_name)
                        ->where('system_id', $system_id)
                        ->where('date', '>=', $startTime)
                        ->where('date', '<=', $endTime)
                        ->where('id', '>', 0)
                        ->whereIn('command', function ($subquery1) use ($chart_type) {
                            $subquery1->from('device_types')
                            ->select('command')
                            ->where('function', $chart_type);
                        })
                        ->whereIn('id', $DEV_ID_Array)
                        ->orderBy('date', 'ASC')
                        ->get();
                    }
                    /*create a json with device_name(device_id) as property name
                    "$json['data'][$data_point->id.'time']" contains the time data to be used for Multiple
                    X-Y graph lines*/
                    foreach ($device_data as $data_point) {
                        /**
             * Temperature data has the option of being converted to Farenheit.
             * Whether it's converted or not depends on the
             * `system`.`temperature_format` field containing a 'C' or an 'F'
             */
                        if ($chart_type === 'Temperature' && $system->temperature_format == 'F') {
                            $data_point->current_value = ConvFunc::convertCelciusToFarenheit($data_point->current_value);
                        }
                        // Device Names
                        $DEV_index = array_search($data_point->id, $DEV_ID_Array);
                        $DEV_name = $DEV_Names_Array[$DEV_index];
                        // Make an array to store each devices data if needed
                        if (! isset($json['data'][$DEV_name]) || ! isset($json['data'][$data_point->id.'time'])) {
                            $json['data'][$DEV_name] = [];
                            $json['data'][$data_point->id.'time'] = [];
                            $json['DeviceNameForLegend'] = [];
                        }
                        // Add data
                        array_push($json['data'][$DEV_name], floatval($data_point->current_value));
                        // Add time
                        array_push($json['data'][$data_point->id.'time'], $data_point->datetime);
                        // Add DeviceName in Array
                        if (!in_array($DEV_name, $json['DeviceNameForLegend'], true)) {
                            array_push($json['DeviceNameForLegend'], $DEV_name);
                        }
                    }
                    Cache::put('report'.$chart_type.$system_id.$startTime.$endTime.'0', json_encode($json), 10);
                } else {
                    $json = json_decode(Cache::get('report'.$chart_type.$system_id.$startTime.$endTime.'0'));
                }
            }
          // Done getting data
        } else {
            $json['error'] = ['fetchDate is not a DateTime object'];
        }
        // The response is sent back as JSON and handled by JS in the page
        return Response::json($json);
    }

  /**
   * Display the data export form
   * @param  integer $building_id The current building's ID
   * @param  integer $system_id   The current system's ID
   * @return View
   */
    public function export($building_id, $system_id)
    {
        $building = Building::find($building_id);
        $system = System::find($system_id);
        $zones = Zone::where('system_id', $system_id)->get();
        $devices = Device::where('system_id', $system_id)
        ->where('id', '!=', '0')
        ->where('retired', '=', '0')
        ->orderBy('id', 'ASC')
        ->get();
    
        $productTypes = ProductType::whereIn('product_id', function ($subquery) use ($system_id) {
            $subquery
              ->select('product_id')
              ->distinct()
              ->from('devices')
              ->where('system_id', $system_id);
        })->select('commands', 'product_id')->get();
        // Assemble a list of commands being reported so we know what can be in the reports
        $commands = [];
        $command_productID = [];
        $i = 0;
        foreach ($productTypes as $productType) {
            $explodedCommands = explode(',', $productType->commands);
            foreach ($explodedCommands as $explodedCommand) {
                if (! in_array($explodedCommand, $commands)) {
                    array_push($commands, $explodedCommand);
                    $command_productID[$i]['command'] = $explodedCommand;
                    $command_productID[$i]['product_id'] = $productType->product_id;
                    $i++;
                }
            }
        }
        // Get a list of unique functions that are being reported
        $reportingFunctions = DeviceType::distinct()
        ->select('function', 'digital', 'command')
        ->whereIn('command', $commands)
        ->get();

        //======================= For events tab =======================
        //digital commands only
        $filteredCommand = [];
        foreach ($reportingFunctions as $reporting) {
            if ($reporting->digital == 1) {
                array_push($filteredCommand, $reporting->command);
            }
        }
        //digital devices only
        $event_devices = [];
        foreach ($devices as $device) {
            foreach ($command_productID as $value) {
                if (in_array($value['command'], $filteredCommand) && $value['product_id'] == $device->product_id) {
                    array_push($event_devices, $device);
                }
            }
        }

        $data['thisBldg']   = $building;
        $data['thisSystem'] = $system;
        $data['zones']      = $zones;
        $data['devices']    = $devices;
        $data['events_devices'] = $event_devices;
        $data['functions']  = $reportingFunctions;
        $data['abc']        = $filteredCommand;
        return View::make('reports.export', $data);
    }
  /**
   * Look up data types reported by a given device
   * @param  integer $building_id The current building's ID
   * @param  integer $system_id   The current system's ID
   * @return JSON
   */
    public static function filter($building_id, $system_id)
    {
        $system_id = intval($system_id);
        $device_id = Input::get('device_id');
        $json = [];
        $device = Device::join('product_types', 'devices.product_id', '=', 'product_types.product_id')
        ->where('devices.system_id', $system_id)
        ->where('devices.id', $device_id)
        ->first();
        $device_types = DeviceType::whereIn('command', explode(',', $device->commands))
        ->orderBy('id', 'DESC')
        ->get();
        foreach ($device_types as $device_type) {
            array_push($json, $device_type->function);
        }
        return Response::json($json);
    }

  /**
   * Generate a fileof data requested for export
   * @param  integer $building_id The current building's ID
   * @param  integer $system_id   The current system's ID
   * @return Redirect
   */
    public function download($building_id, $system_id)
    {
        $startdate        = new DateTime(Input::get('startdate'));
        $enddate          = new DateTime(Input::get('enddate'));
        $function         = Input::get('function');
        $event            = Input::get('event');
        $system           = System::find($system_id);
        $device_selection = Input::get('device_id');
        $minDurationRange = -999;
        $maxDurationRange = 99999;
        $minValueRange    = -999;
        $maxValueRange = 99999;
        $minSetpointRange = -999;
        $maxSetpointRange = 99999;
        // storing  request (ie, get/post) global array to a variable
        $requestData= $_REQUEST;
        // If no search is made, keep it empty
        if (empty($requestData['search']['value'])) {
            $requestData['search']['value'] = '';
        }
        /* *****************************************************************
                                  For Device Events tab
        ******************************************************************* */
        if ($event == 'true') {
            //If date object is correct format
            if ((gettype($startdate) === 'object')&&(gettype($enddate) === 'object')) {
                // Start getting data
                $startTime = date_format($startdate, 'Y-m-d 00:00:00');
                $endTime = date_format($enddate, 'Y-m-d 23:59:59');
       
                // ==================== SEARCH ========================
                //duration slider
                if (!empty($requestData['columns'][3]['search']['value'])) {
                    $rangeArray = explode("-", $requestData['columns'][3]['search']['value']);
                    $minDurationRange = $rangeArray[0];
                    $maxDurationRange = $rangeArray[1];
                }
                // =========================================== For Single device =============================================
                if ($device_selection != 'all') {
                    $name_column = false;
                    $device_id = intval($device_selection);
                    $columns = [
                      // datatable column index  => database column name
                      0=> 'created_at',     // for order by created_at
                      1=> 'cleared_at',     //for order by cleared_at
                      2=> 'duration',
                      3=> 'description'
                            ];
                    $query = Events::where('events.system_id', $system_id)
                                  ->where('events.device_id', $device_id)
                                  ->where('events.created_at', '>=', $startTime)
                                  ->where('events.cleared_at', '<=', $endTime)
                                  ->leftjoin('alarm_codes', function ($join) {
                                    $join->on('alarm_codes.id', '=', 'events.alarm_code_id');
                                  });
                    $filtered_query = Events::select(DB::raw('events.created_at, events.cleared_at, events.duration, events.description'))
                                  ->where('events.system_id', $system_id)
                                  ->where('events.device_id', $device_id)
                                  ->where('events.created_at', '>=', $startTime)
                                  ->where('events.cleared_at', '<=', $endTime)
                                  ->where('events.created_at', 'like', '%'.$requestData['columns'][1]['search']['value'].'%')
                                  ->where('events.cleared_at', 'like', '%'.$requestData['columns'][2]['search']['value'].'%')
                                  ->where('events.duration', '>=', $minDurationRange)
                                  ->where('events.duration', '<=', $maxDurationRange)
                                  ->leftjoin('alarm_codes', function ($join) {
                                    $join->on('alarm_codes.id', '=', 'events.alarm_code_id');
                                  });
                } // =========================================== For All devices ===============================================
                else {
                    $name_column = true;
                    $columns = [
                      // datatable column index  => database column name
                      0=> 'name',           // for order by name from devices table
                      1=> 'created_at',     // for order by created_at
                      2=> 'cleared_at',     //for order by cleared_at
                      3=> 'duration',
                      4=> 'description'
                            ];
                    $devicesID = Input::get('device_list');
                    $query = Events::where('events.system_id', $system_id)
                                  ->where('events.created_at', '>=', $startTime)
                                  ->where('events.cleared_at', '<=', $endTime)
                                  ->whereIn('events.device_id', $devicesID)
                                  ->leftjoin('alarm_codes', function ($join) {
                                    $join->on('alarm_codes.id', '=', 'events.alarm_code_id');
                                  })
                                  ->join('devices', function ($join) {
                                    $join->on('devices.id', '=', 'events.device_id')
                                    ->on('devices.system_id', '=', 'events.system_id');
                                  });
                    $filtered_query = Events::where('events.system_id', $system_id)
                                  ->where('events.created_at', '>=', $startTime)
                                  ->where('events.cleared_at', '<=', $endTime)
                                  ->whereIn('events.device_id', $devicesID)
                                  ->where('devices.name', 'like', '%'.$requestData['columns'][0]['search']['value'].'%')
                                  ->where('events.created_at', 'like', '%'.$requestData['columns'][1]['search']['value'].'%')
                                  ->where('events.cleared_at', 'like', '%'.$requestData['columns'][2]['search']['value'].'%')
                                  ->where('events.duration', '>=', $minDurationRange)
                                  ->where('events.duration', '<=', $maxDurationRange)
                                  ->leftjoin('alarm_codes', function ($join) {
                                    $join->on('alarm_codes.id', '=', 'events.alarm_code_id');
                                  })
                                  ->join('devices', function ($join) {
                                    $join->on('devices.id', '=', 'events.device_id')
                                          ->on('devices.system_id', '=', 'events.system_id');
                                  });
                }
                // If anything is selected in dropdown of description column, further filter the query
                if (isset($requestData['columns'][4]['search']['value']) && strlen($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] !="all") {
                    $filtered_query = $filtered_query->where('events.alarm_code_id', '=', $requestData['columns'][4]['search']['value']);
                }
                //ANY SEARCH IS MADE  -  If any search is made then change "$totalFiltered" count
                if (!empty($requestData['columns'][0]['search']['value']) ||
                  !empty($requestData['columns'][1]['search']['value']) ||
                  !empty($requestData['columns'][2]['search']['value']) ||
                  !empty($requestData['columns'][3]['search']['value']) ||
                  !empty($requestData['columns'][4]['search']['value'])) {
                      $device_data_rows = $filtered_query->select(DB::raw('count(*) as count'))
                                                ->first()->count;
                } // NO SEARCH IS MADE - create "$totalData" as session object
                else {
                    $description_column = $query->select(DB::raw('distinct alarm_codes.id as id, alarm_codes.description as description'))
                                      ->get();
                    $description_list = [];
                    $alarm_code_list = [];
                    foreach ($description_column as $value) {
                        if (!is_null($value->id)) {
                            array_push($description_list, $value->description);
                            array_push($alarm_code_list, $value->id);
                        }
                    }
                    Session::put("description_list", $description_list);
                    Session::put("alarm_code_list", $alarm_code_list);

                    $query_result = $query->select(DB::raw('count(*) as count, min(duration) as min_duration, max(duration) as max_duration'))
                                ->first();
                    $device_data_rows = $query_result->count;
                    Session::put("totalData", $device_data_rows);  // Moved $totalData into a session object so that I don't have to do the query every time I search for something.
                    Session::put("min_duration", $query_result->min_duration);  // Moved $min_duration into a session object so that I don't have to do the query every time I search for something.
                    Session::put("max_duration", $query_result->max_duration);  // Moved $max_duration into a session object so that I don't have to do the query every time I search for something.
                }
                $totalFiltered = $device_data_rows;  // when there is no search parameter then total number rows = total number filtered rows.

                //====================================== GRAB DATA ======================================
                // Get all the data for exporting purposes
                if ($requestData['length'] == "-1") {
                    $requestData['length'] = Session::get('totalData');
                    $requestData['start'] = 0;
                }
                $select_query = 'events.created_at, events.cleared_at, events.duration, events.description, events.alarm_code_id, alarm_codes.description as alarm_description';
                if ($name_column) {
                    $select_query = 'devices.name as name, events.created_at, events.cleared_at, events.duration, events.description, events.alarm_code_id, alarm_codes.description as alarm_description';
                }
                $device_data = $filtered_query->select(DB::raw($select_query))
                                      ->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'])
                                      ->take($requestData['length'])->skip($requestData['start'])
                                      ->get();
                $data = [];
                foreach ($device_data as $data_point) {
                    $nestedData = [];
                    if ($name_column) { //Add name column to the table
                        $nestedData[] = $data_point->name;
                    }
                    $nestedData[] = (string)$data_point->created_at;
                    $nestedData[] = (string)$data_point->cleared_at;
                    $nestedData[] = $data_point->duration;
                    $nestedData[] = $data_point->description;
                    $data[] = $nestedData;
          
                    // Description in alarm_code table is null when alarm_code_id is 0. So use description from events table
                    if (intval($data_point->alarm_code_id) == 0) {
                        $AC_list  = Session::get('alarm_code_list');
                        $D_list = Session::get('description_list');
                        if (!in_array(intval($data_point->alarm_code_id), $AC_list)) {
                            array_push($AC_list, intval($data_point->AC_id));
                            Session::put("alarm_code_list", $AC_list);
                            array_push($D_list, $data_point->description);
                            Session::put("description_list", $D_list);
                        }
                    }
                }

                $queries = DB::getQueryLog();
                $json_data = [
                        "duration"        => [Session::get('min_duration'), Session::get('max_duration')],
                        "description"     => [Session::get('description_list'), Session::get('alarm_code_list')],   // Contains data for dropdown in description column
                        "draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                        "recordsTotal"    => intval(Session::get('totalData')),  // total number of records
                        "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                        "data"            => $data,  // total data array
                        'queries'         => $queries,
                          ];
                echo json_encode($json_data);
            }
        } /* *****************************************************************
                                  For Device Data tab
        ******************************************************************* */
        else {
            //If date object is correct format
            if ((gettype($startdate) === 'object')&&(gettype($enddate) === 'object')) {
                // Start getting data
                $startTime = date_format($startdate, 'Y-m-d');
                $endTime = date_format($enddate, 'Y-m-d');

                $device_data = [];
                $startdatediff = date_diff($startdate, new DateTime());
                $enddatediff = date_diff($enddate, new DateTime());
                if (($startdatediff->days <= 14) && ($enddatediff->days <= 14)) {
                    $table_name = 'device_data';
                } else {
                    $table_name = 'device_data_long_term';
                }
                // ==================== SEARCH ========================
                if (!empty($requestData['columns'][3]['search']['value'])) { // value_slider
                    $rangeArray = explode("-", $requestData['columns'][3]['search']['value']);
                    if ($function == "Temperature") {
                        $rangeArray[0] = ConvFunc::convertFarenheittoCelcius($rangeArray[0]);
                        $rangeArray[1] = ConvFunc::convertFarenheittoCelcius($rangeArray[1]);
                    }
                    $minValueRange = $rangeArray[0];
                    $maxValueRange = $rangeArray[1];
                }
                if (!empty($requestData['columns'][4]['search']['value'])) { // Setpoint_slider
                    $rangeArray = explode("-", $requestData['columns'][4]['search']['value']);
                    if ($function == "Temperature") {
                        $rangeArray[0] = ConvFunc::convertFarenheittoCelcius($rangeArray[0]);
                        $rangeArray[1] = ConvFunc::convertFarenheittoCelcius($rangeArray[1]);
                    }
                    $minSetpointRange = $rangeArray[0];
                    $maxSetpointRange = $rangeArray[1];
                }
                // =========================================== For Single device =============================================
                if ($device_selection != 'all') {
                    $name_column = false;
                    $device_id = intval($device_selection);
                    $columns = [
                      // datatable column index  => database column name
                      0=> 'date',     // for order by date
                      2=> 'datetime', //for order by time
                      3=> 'current_value',
                      4=> 'setpoint'
                            ];
                    $query = DB::table($table_name)
                              ->where($table_name.'.system_id', $system_id)
                              ->where($table_name.'.id', $device_id)
                              ->where($table_name.'.date', '>=', $startTime)
                              ->where($table_name.'.date', '<=', $endTime)
                              ->whereIn($table_name.'.command', function ($subquery1) use ($function) {
                                $subquery1->from('device_types')
                                ->select('command')
                                ->where('function', $function);
                              })
                              ->join('alarm_codes', function ($join) use ($table_name) {
                                $join->on('alarm_codes.id', '=', $table_name.'.alarm_index');
                              });
                    $filtered_query = DB::table($table_name)
                              ->where('system_id', $system_id)
                              ->where('id', $device_id)
                              ->where('date', '>=', $startTime)
                              ->where('date', '<=', $endTime)
                              ->where('date', 'like', '%'.$requestData['columns'][1]['search']['value'].'%')
                              ->where('datetime', 'like', '%'.$requestData['columns'][2]['search']['value'].'%')
                              ->where('current_value', '>=', $minValueRange)
                              ->where('current_value', '<=', $maxValueRange)
                              ->where('setpoint', '>=', $minSetpointRange)
                              ->where('setpoint', '<=', $maxSetpointRange)
                              ->whereIn('command', function ($subquery1) use ($function) {
                                $subquery1->from('device_types')
                                  ->select('command')
                                  ->where('function', $function);
                              })
                              ->join('alarm_codes', function ($join) use ($table_name) {
                                $join->on('alarm_codes.id', '=', $table_name.'.alarm_index');
                              });
                    /**
           * The `device_data` table only holds two weeks of data. Anything older
           * than that will be in the archives table
           */
                    /**
           * Use a subquery to look up commands that match this data type. Only
           * those commands will be selected from `device_data`.
           */
          
                    //If any search is made then change "$totalFiltered" count
                    if (!empty($requestData['columns'][1]['search']['value']) ||
                      !empty($requestData['columns'][2]['search']['value']) ||
                      !empty($requestData['columns'][3]['search']['value']) ||
                      !empty($requestData['columns'][4]['search']['value'])) {
                              $device_data_rows = $filtered_query->select(DB::raw('count(*) as count, max(current_value) as max_current_value, min(current_value) as min_current_value, max(setpoint) as max_setpoint, min(setpoint) as min_setpoint'))
                                              ->first();
                    } else {  //No search is made - create "$totalData" as session object
                              $device_data_rows = $query->select(DB::raw('count(*) as count, max(current_value) as max_current_value, min(current_value) as min_current_value, max(setpoint) as max_setpoint, min(setpoint) as min_setpoint'))
                                      ->first();
                              Session::put("totalData", $device_data_rows->count);  // Moved $totalData into a session object so that I don't have to do the query every time I search for something.
                    }
                    $totalFiltered = $device_data_rows->count;  // when there is no search parameter then total number rows = total number filtered rows.
                    //get the data
                    if ($requestData['length'] == "-1") {   //For "all" the data
                              $requestData['length'] = Session::get('totalData');
                              $requestData['start'] = 0;
                    }
                    $device_data = $filtered_query->select($table_name.'.*', 'alarm_codes.description', 'alarm_codes.severity')
                                        ->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'])
                                        ->take($requestData['length'])->skip($requestData['start'])
                                        ->get();
                } // =========================================== For All devices ===============================================
                else {
                    $name_column = true;

                    /*Export data for all of the devices of a given function type*/
                    /*---------EXPORT BY FUNCTION------------------*/
                    $device_functions = DeviceType::where('function', $function)->get();
                    $all_products = ProductType::all();
                    $relevant_products = [];
                    $relevant_commands = [];
                    foreach ($device_functions as $df) {
                        foreach ($all_products as $ap) {
                            $boom = explode(',', $ap->commands);
                            foreach ($boom as $boomlet) {
                                if ($boomlet == $df->command) {
                                    $relevant_products[] = $ap->product_id;
                                    $relevant_commands[] = $df->command;
                                }
                            }
                        }
                    }
                    $columns = [
                      // datatable column index  => database column name
                      0=>'name',
                      1=> 'date',
                      2=> 'datetime',
                      3=> 'current_value',
                      4=> 'setpoint'
                            ];
                    $query = DB::table($table_name)
                              ->where($table_name.'.system_id', $system_id)
                              ->where($table_name.'.date', '>=', $startTime)
                              ->where($table_name.'.date', '<=', $endTime)
                              ->whereIn($table_name.'.command', $relevant_commands)
                              ->join('devices', function ($join) use ($table_name) {
                                $join->on('devices.id', '=', $table_name.'.id');
                                $join->on('devices.system_id', '=', $table_name.'.system_id');
                              })
                              ->leftJoin('alarm_codes', function ($join) use ($table_name) {
                                $join->on($table_name.'.alarm_index', '=', 'alarm_codes.id');
                              });
                    $filtered_query = DB::table($table_name)
                              ->where($table_name.'.system_id', $system_id)
                              ->where($table_name.'.date', '>=', $startTime)
                              ->where($table_name.'.date', '<=', $endTime)
                              ->where('devices.name', 'like', '%'.$requestData['columns'][0]['search']['value'].'%')
                              ->where($table_name.'.date', 'like', '%'.$requestData['columns'][1]['search']['value'].'%')
                              ->where($table_name.'.datetime', 'like', '%'.$requestData['columns'][2]['search']['value'].'%')
                              ->where($table_name.'.current_value', '>=', $minValueRange)
                              ->where($table_name.'.current_value', '<=', $maxValueRange)
                              ->where($table_name.'.setpoint', '>=', $minSetpointRange)
                              ->where($table_name.'.setpoint', '<=', $maxSetpointRange)
                              ->whereIn($table_name.'.command', $relevant_commands)
                              ->join('devices', function ($join) use ($table_name) {
                                $join->on('devices.id', '=', $table_name.'.id');
                                $join->on('devices.system_id', '=', $table_name.'.system_id');
                              })
                              ->leftJoin('alarm_codes', function ($join) use ($table_name) {
                                $join->on($table_name.'.alarm_index', '=', 'alarm_codes.id');
                              });
                    /**
          * The `device_data` table only holds two weeks of data. Anything older
          * than that will be in the archives table
          */
                    /**
          * Use a subquery to look up commands that match this data type. Only
          * those commands will be selected from `device_data`.
          */

                    //If any search is made then change "$totalFiltered" count
                    if (!empty($requestData['columns'][0]['search']['value']) ||
                      !empty($requestData['columns'][1]['search']['value']) ||
                      !empty($requestData['columns'][2]['search']['value']) ||
                      !empty($requestData['columns'][3]['search']['value']) ||
                      !empty($requestData['columns'][4]['search']['value'])) {
                              $device_data_rows = $filtered_query->select(DB::raw('count(*) as count, max(current_value) as max_current_value, min(current_value) as min_current_value, max(setpoint) as max_setpoint, min(setpoint) as min_setpoint'))
                                               ->first();
                    } else {  //No search is made - create "$totalData" as session object
                              $device_data_rows = $query->select(DB::raw('count(*) as count, max(current_value) as max_current_value, min(current_value) as min_current_value, max(setpoint) as max_setpoint, min(setpoint) as min_setpoint'))
                                      ->first();
                              Session::put("totalData", $device_data_rows->count);  // Moved $totalData into a session object so that I don't have to do the query every time I search for something.
                    }
                    $totalFiltered = $device_data_rows->count;  // when there is no search parameter then total number rows = total number filtered rows.
                    // Get the data
                    if ($requestData['length'] == "-1") {   //For "all" the data
                              $requestData['length'] = Session::get('totalData');
                              $requestData['start'] = 0;
                    }
                    $device_data = $filtered_query->select($table_name.'.*', 'devices.name', 'alarm_codes.description', 'alarm_codes.severity')
                                        ->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'])
                                        ->take($requestData['length'])->skip($requestData['start'])
                                        ->get();
                }
            }

            $data = [];
            $dataseverity = [];    //Alarm severity
            $datadescription = [];
            foreach ($device_data as $data_point) {
                if ($function == 'Temperature') {
                    $data_point->current_value = number_format(ConvFunc::convertCelciusToFarenheit($data_point->current_value), 3, '.', '');
                    $data_point->setpoint = number_format(ConvFunc::convertCelciusToFarenheit($data_point->setpoint), 3, '.', '');
                } else {
                    $data_point->current_value = number_format($data_point->current_value, 3, '.', '');
                    $data_point->setpoint = number_format($data_point->setpoint, 3, '.', '');
                }
                $nestedData = [];
                if ($name_column) { //Add name column to the table
                    $nestedData[] = $data_point->name;
                }
                $exploded = explode(" ", $data_point->datetime); //Limit is unspecified, so it will return all the substrings;
                $nestedData[] = date("d-m-Y", strtotime($exploded[0])); // date
                $nestedData[] = date("g:i a", strtotime($exploded[1]));// 24-hour time to 12-hour time
                $nestedData[] = $data_point->current_value;   // current value
                $nestedData[] = $data_point->setpoint;        // setpoint

                $dataseverity[] = $data_point->severity;
                $datadescription[] = $data_point->description;
                $data[] = $nestedData;
            }
            if ($function == 'Temperature') {
                $device_data_rows->min_current_value = number_format(ConvFunc::convertCelciusToFarenheit($device_data_rows->min_current_value), 3, '.', '');
                $device_data_rows->max_current_value = number_format(ConvFunc::convertCelciusToFarenheit($device_data_rows->max_current_value), 3, '.', '');
                $device_data_rows->min_setpoint = number_format(ConvFunc::convertCelciusToFarenheit($device_data_rows->min_setpoint), 3, '.', '');
                $device_data_rows->max_setpoint = number_format(ConvFunc::convertCelciusToFarenheit($device_data_rows->max_setpoint), 3, '.', '');
            }
            $queries = DB::getQueryLog();

            $json_data = [
                      "current_value"   => [$device_data_rows->min_current_value, $device_data_rows->max_current_value],
                      "setpoint"        => [$device_data_rows->min_setpoint, $device_data_rows->max_setpoint],
                      "draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                      "recordsTotal"    => intval(Session::get('totalData')),  // total number of records
                      "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                      "data"            => $data,  // total data array
                      'queries'         => $queries,
                      "severity"        => $dataseverity,
                      "description"     => $datadescription,
                  ];
            echo json_encode($json_data);
        }
    }
}
