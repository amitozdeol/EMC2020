<?php

    // $system = System::find($sid);
    // $id = $system->building_id;
class TouchScreenController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($sid)
  {
    $thisSystem = System::find($sid); // Lookup passed system from DB
    $thisBldg = Building::find($thisSystem->building_id); // Lookup passed building from DB
    $systems = System::where('building_id', $thisBldg->id)->get();

    // Check for system in web_mapping_system
    $check = WebMappingSystem::where('system_id', $sid)->first();
    if ($check) {
      // If system is there, build page using web_mapping_system with specific system
      $sysParams = WebMappingSystem::select(array('group_number', 'group_name'))->where('system_id', $sid)->distinct()->get();
    }
    else {
      // If system isn't there, build page using web_mapping_default
      $sysParams = WebMappingDefault::select(array('group_number', 'group_name'))->distinct()->get();
    }

    return View::make('touchscreen.index', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem))
    ->with('systemsData', $systems)
    ->with('sysParams', $sysParams);
  }


  /*--------------------------------------------------------------------------
  |
  | Device Status page
  |-------------------------------------------------------------------------*/
  public function devicestatus($sid)
  {
    $system = System::find($sid);
    $id = $system->building_id;

    $thisBldg = Building::find($id); // Lookup info for selected building

               // $systems = System::where('building_id', $thisBldg->id)->get(); // Lookup all systems associated with selected building for nav dropdown

    //$thisSys = System::find($sid); // Lookup info for selected system

    $devicesout = Device::where('system_id',$sid) // Lookup devices which are outputs
                                     ->where('device_io','output')
                                     ->where('status','1')
                                     ->where('retired','<>','1')
                                     ->orderby('zone')
                                     ->get();


    $devicesin = Device::where('system_id', $sid) // Lookup devices which are intputs
                                     ->where('device_io','input')
                                     ->where('status','1')
                                     ->where('retired','<>','1')
                                     ->orderby('zone')
                                     ->get();
    //$thisBldg = Building::find($id); // Lookup passed building from DB
    //$thisSystem = System::find($sid); // Lookup passed system from DB
    $systems = System::where('building_id', $thisBldg->id)->get();
    $products = ProductType::all(); // Lookup all products available to this system
    return View::make('touchscreen.devicestatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
                ->with('devicesout', $devicesout)
                ->with('devicesin', $devicesin)
                ->with('systemsData', $systems)
                ->with('products', $products);
  }


  /*--------------------------------------------------------------------------
  |
  | Zone Status page
  |-------------------------------------------------------------------------*/

public function zonestatus($sid)
  {
    $system = System::find($sid);
    $id = $system->building_id;

    $thisBldg = Building::find($id); // Lookup info for selected building

               // $systems = System::where('building_id', $thisBldg->id)->get(); // Lookup all systems associated with selected building for nav dropdown

    //$thisSys = System::find($sid); // Lookup info for selected system

    $devicesout = Device::where('system_id',$sid) // Lookup devices which are outputs
                                     ->where('device_io','output')
                                     ->where('status','1')
                                     ->where('retired','<>','1')
                                     ->orderby('zone')
                                     ->get();


    $devicesin = Device::where('system_id', $sid) // Lookup devices which are intputs
                                     ->where('device_io','input')
                                     ->where('status','1')
                                     ->where('retired','<>','1')
                                     ->orderby('zone')
                                     ->get();
    $thisBldg = Building::find($id); // Lookup passed building from DB
    $thisSystem = System::find($sid); // Lookup passed system from DB
    $systems = System::where('building_id', $thisBldg->id)->get();


    return View::make('touchscreen.zonestatus', array('thisBldg' => $thisBldg,'thisSystem' => $thisSystem))
                ->with('devicesout', $devicesout)
                ->with('devicesin', $devicesin)
                ->with('systemsData', $systems);

  }


  public function system($sid)
  {
    $system = System::find($sid);
    $id = $system->building_id;

    $thisBldg = Building::find($id); // Lookup passed building from DB
    $thisSystem = System::find($sid); // Lookup passed system from DB
    $systems = System::where('building_id', $thisBldg->id)->get();
    $sysAlarms = Alarms::where('system_id', $sid)->where('active',1)->get();
    // Check for system in web_mapping_system
    $check = WebMappingSystem::where('system_id', $sid)->first();
    if ($check) {
      // If system is there, build page using web_mapping_system with specific system
      $sysParams = WebMappingSystem::select(array('group_number', 'group_name'))->where('system_id', $sid)->where('active',1)->distinct()->get();
    }
    else {
      // If system isn't there, build page using web_mapping_default
      $sysParams = WebMappingDefault::select(array('group_number', 'group_name'))->distinct()->where('active',1)->get();
    }

    return View::make('buildings.system', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem))
    ->with('systemsData', $systems)
    ->with('sysParams', $sysParams)
    ->with('sysAlarms',$sysAlarms);
  }

  public function detail($sid, $gid)
  {
    $system = System::find($sid);
    $id = $system->building_id;

    $thisBldg = Building::find($id); // Lookup passed building from DB
    $thisSystem = System::find($sid); // Lookup passed system from DB
    $systems = System::where('building_id', $thisBldg->id)->get();

    // Check for system in web_mapping_system
    $check = WebMappingSystem::where('system_id', $sid)->first();
    if ($check) {
      // If system is there, build page using web_mapping_system with specific system
      $sysDetail = WebMappingSystem::select(array('group_name', 'subgroup_number', 'subgroup_name','alarm_state','alarm_index'))->where('system_id', $sid)->where('group_number', $gid)->where('active',1)->distinct()->get();
      $categories = WebMappingSystem::select(array('subgroup_number', 'subgroup_name', 'itemnumber','alarm_state','alarm_index'))->where('system_id', $sid)->where('group_number', $gid)->where('active', 1)->get();
    } else {
      // If not there, get default subgroup details for this specific group
      $sysDetail = WebMappingDefault::select(array('group_name', 'subgroup_number', 'subgroup_name','alarm_state','alarm_index'))->where('group_number', $gid)->where('active',1)->distinct()->get();
      $categories = WebMappingDefault::select(array('subgroup_number', 'subgroup_name', 'itemnumber','alarm_state','alarm_index'))->where('group_number', $gid)->where('active',1)->get();
    }
      //-------------------------------------------//
      // Check if there is temperature data to report
      //-------------------------------------------//
      $check = DeviceData::where('system_id', $sid)
                            ->where(function($query)
                              {
                                $query->where('command', 11)
                                      ->orWhere('command', 1);
                              })
                              ->first();
      if ($check) {
        // Get last 60 data points, in DESC order (will correct to chronological order later)
        $tempObj = DeviceData::select(array('id', 'datetime', 'current_value'))->where('system_id', $sid)
                                                                                ->where(function($query)
                                                                                  {
                                                                                    $query->where('command', 11)
                                                                                          ->orWhere('command', 1);
                                                                                  })
                                                                                  ->orderBy('datetime', 'desc')->take(60)->get();

        // Create datetime array for chart x-axis timestamps
        $tempCategories = array();
        foreach ($tempObj as $item) {
          array_push($tempCategories, date('M d, Y, g:i:s A', strtotime($item->datetime)));
        }

        // Create 3d array for data: top level is zone numbers, each zone with subarray of device names for that zone, each device with subarrays containing date|value information for that device
        $tempDevices = array();
        foreach ($tempObj as $item) {
          $name = Device::select(array('name', 'zone'))->where('system_id', $sid)->where('id', $item->id)->first();
          $tempDevices[$name['zone']][$name['name']][] = date('M d, Y, g:i:s A', strtotime($item->datetime))."|".$item->current_value;
        }

        // Overall Chart initialization
        $tempChart["chart"] = array("zoomType" => "xy");
        $tempChart["title"] = array("text" => "Temperature Data: " . $tempCategories[(count($tempCategories)-1)] . " - " . $tempCategories[0]);
        $tempChart["yAxis"] = array("title"=> array("text" => "Temperature (C)"));

        $i = 0; // Counter for how many devices there are total and how many series are needed on Overall Chart
        $z = 1; // Counter to track zones

        foreach ($tempDevices as $key => $value) { // For each zone ($key = zone)
          // Zone Chart initialization
          $zoneTempcharts[$z]["chart"] = array("zoomType" => "xy");
          $zoneTempCharts[$z]["title"] = array("text" => "Zone " . $key . ": " . $tempCategories[(count($tempCategories)-1)] . " - " . $tempCategories[0]);
          $zoneTempCharts[$z]["yAxis"] = array("title"=> array("text" => "Temperature (C)"));
          $zoneTempCharts[$z]["zone"] = $key;

          $zoneSeries = 0; // Counter for how many devices are in this specific zone and how many corresponding series are needed on Zone Chart

          foreach ($value as $key2 => $data) { // For each device in this zone ($key2 = device name)
            $tempData = array_pad(array(), 60, null); // Pad an empty data array with 60 null values, each index being a timestamp

            foreach ($data as $item) { // For each data point in this device
              $itemArr = explode("|", $item); // Separate timestamp and data values
              $index = array_search($itemArr[0], $tempCategories); // Search for the index of this timestamp in datetime array, then map data to the same index in data array
              $tempData[$index] = $itemArr; // Data mapped in TIME, VALUE format for json_encode. the index in data array remains null if this device has no data for a certain timestamp
            }

            // Flip data to correct chronological order
            $tempData = array_reverse($tempData);

            // Set Zone Chart series data for this device
            $zoneTempCharts[$z]["series"][$zoneSeries] = array("name" => $key . " " . $key2, "type" => "spline", "data" => $tempData, "tooltip" => array("valueDecimals" => 2, "valueSuffix" => " C"));
            $zoneSeries++;

            // Set Overall Chart series data for this device
            $tempChart["series"][$i] = array("name" => $key . " " . $key2, "type" => "spline", "data" => $tempData, "tooltip" => array("valueDecimals" => 2, "valueSuffix" => " C"));
            $i++;
          }

          // Concluding options for Zone Chart. Flip datetime array to correct chronological order for x-axis.
          $zoneTempCharts[$z]["plotOptions"] = array("series" => array("connectNulls" => "true")); // Connect lines past null data points
          $zoneTempCharts[$z]["xAxis"] = array("categories" => array_reverse($tempCategories), "labels" => array("step" => 2, "rotation" => -70));

          // Move to next zone
          $z++;
        }

        // Concluding options for OVerall Chart. Flip datetime array to correct chronological order for x-axis.
        $tempChart["plotOptions"] = array("series" => array("connectNulls" => "true")); // Connect lines past null data points
        $tempChart["xAxis"] = array("categories" => array_reverse($tempCategories), "labels" => array("step" => 2, "rotation" => -70));

      }
      //-------------------------------------------//
      // Check if there is humidity data to report
      //-------------------------------------------//
      $check = DeviceData::where('system_id', $sid)->where('command', 10)->first();
      if ($check) {
        // Get last 60 data points, in DESC order (will correct to chronological order later)
        $humObj = DeviceData::select(array('id', 'datetime', 'current_value'))->where('system_id', $sid)->where('command', 10)->orderBy('datetime', 'desc')->take(60)->get();

        // Create datetime array for chart x-axis timestamps
        $humCategories = array();
        foreach($humObj as $item) {
          array_push($humCategories, date('M d, Y, g:i:s A', strtotime($item->datetime)));
        }

        // Create 3d array for data: top level is zone numbers, each zone with subarray of device names for that zone, each device with subarrays containing date|value information for that device
        $humDevices = array();
        foreach ($humObj as $item) {
          $name = Device::select(array('name', 'zone'))->where('system_id', $sid)->where('id', $item->id)->first();
          $humDevices[$name['zone']][$name['name']][] = date('M d, Y, g:i:s A', strtotime($item->datetime))."|".$item->current_value;
        }

        // Overall Chart initialization
        $humChart["chart"] = array("zoomType" => "xy");
        $humChart["title"] = array("text" => "Humidity Data: " . $humCategories[(count($humCategories)-1)] . " - " . $humCategories[0]);
        $humChart["yAxis"] = array("title"=> array("text" => "Humidity (%)"));

        $i = 0; // Counter for how many devices there are total and how many series are needed on Overall Chart
        $z = 1; // Counter to track zones

        foreach ($humDevices as $key => $value) { // For each zone ($key = zone)
          // Zone Chart initialization
          $zoneHumCharts[$z]["chart"] = array("zoomType" => "xy");
          $zoneHumCharts[$z]["title"] = array("text" => "Zone " . $key . ": " . $humCategories[(count($humCategories)-1)] . " - " . $humCategories[0]);
          $zoneHumCharts[$z]["yAxis"] = array("title"=> array("text" => "humerature (C)"));
          $zoneHumCharts[$z]["zone"] = $key;

          $zoneSeries = 0; // Counter for how many devices are in this specific zone and how many corresponding series are needed on Zone Chart

          foreach ($value as $key2 => $data) { // For each device in this zone ($key2 = device name)
            $humData = array_pad(array(), 60, null); // Pad an empty data array with 60 null values, each index being a timestamp

            foreach ($data as $item) { // For each data point in this device
              $itemArr = explode("|", $item); // Separate timestamp and data values
              $index = array_search($itemArr[0], $humCategories); // Search for the index of this timestamp in datetime array, then map data to the same index in data array
              $humData[$index] = $itemArr; // Data mapped in TIME, VALUE format for json_encode. the index in data array remains null if this device has no data for a certain timestamp
            }

            // Flip data to correct chronological order
            $humData = array_reverse($humData);

            // Set Zone Chart series data for this device
            $zoneHumCharts[$z]["series"][$zoneSeries] = array("name" => $key . " " . $key2, "type" => "spline", "data" => $humData, "tooltip" => array("valueDecimals" => 2, "valueSuffix" => "%"));
            $zoneSeries++;

            // Set Overall Chart series data for this device
            $humChart["series"][$i] = array("name" => $key . " " . $key2, "type" => "spline", "data" => $humData, "tooltip" => array("valueDecimals" => 2, "valueSuffix" => "%"));
            $i++;
          }

          // Concluding options for Zone Chart. Flip datetime array to correct chronological order for x-axis.
          $zoneHumCharts[$z]["plotOptions"] = array("series" => array("connectNulls" => "true")); // Connect lines past null data points
          $zoneHumCharts[$z]["xAxis"] = array("categories" => array_reverse($humCategories), "labels" => array("step" => 2, "rotation" => -70));

          // Move to next zone
          $z++;
        }

        // Concluding options for OVerall Chart. Flip datetime array to correct chronological order for x-axis.
        $humChart["plotOptions"] = array("series" => array("connectNulls" => "true")); // Connect lines past null data points
        $humChart["xAxis"] = array("categories" => array_reverse($humCategories), "labels" => array("step" => 2, "rotation" => -70));

      }

      // Make the proper view depending on which charts are available
      if (isset($tempChart) & isset($humChart)) {
        return View::make('touchscreen.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
                        'tempChart' => json_encode($tempChart, JSON_NUMERIC_CHECK), 'humChart' => json_encode($humChart, JSON_NUMERIC_CHECK),
                        'zoneTempCharts' => $zoneTempCharts,
                        'numTempZones' => count($zoneTempCharts),
                        'zoneHumCharts' => $zoneHumCharts,
                        'numHumZones' => count($zoneHumCharts)))
        ->with('systemsData', $systems)
        ->with('sysDetail', $sysDetail)
        ->with('categories', $categories);
      } else if (isset($tempChart)) {
        return View::make('touchscreen.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
                        'tempChart' => json_encode($tempChart, JSON_NUMERIC_CHECK),
                        'zoneTempCharts' => $zoneTempCharts,
                        'numTempZones' => count($zoneTempCharts)))
        ->with('systemsData', $systems)
        ->with('sysDetail', $sysDetail)
        ->with('categories', $categories);
      } else if (isset($humChart)) {
        return View::make('touchscreen.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem,
                        'humChart' => json_encode($humChart, JSON_NUMERIC_CHECK),
                        'zoneHumCharts' => $zoneHumCharts,
                        'numHumZones' => count($zoneHumCharts)))
        ->with('systemsData', $systems)
        ->with('sysDetail', $sysDetail)
        ->with('categories', $categories);
      } else {
        return View::make('touchscreen.detail', array('thisBldg' => $thisBldg, 'thisSystem' => $thisSystem))
        ->with('systemsData', $systems)
        ->with('sysDetail', $sysDetail)
        ->with('categories', $categories);
      }
  }

}
