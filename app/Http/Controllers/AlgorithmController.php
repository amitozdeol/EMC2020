<?php

class AlgorithmController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public static function index($id, $sid)
    {

        // dd($help_id);

        $thisBldg = Building::find($id);

        $algorithms = Algorithm::all();

        $mappedOutputs = MappingOutput::where('system_id',$sid)
        ->get();

        $devices = Device::where('system_id',$sid)
            ->get();

        $retiredDevices = Device::where('system_id', $sid)
            ->where('retired',1)
            ->get();

        $zone = Zone::where('system_id',$sid)
            ->get();
        $zone_names = array();
        foreach ($zone as $zone_num) {
            if(strlen($zone_num->zonename) > 0)
            $zone_names[$zone_num->zone] = $zone_num->zonename;
        }

        $retired_devices = array();
        $used_retired_devices = array();
        foreach ($retiredDevices as $device) {
            $retired_devices[$device->id] = $device->id;
        }

        foreach($mappedOutputs as $output) {
            if($output->inputs !== ''){
                $inputs = explode(', ', str_replace('.', '', $output->inputs));
                foreach($inputs as $index => $input) {
                    $input_id = explode(' ', $input);
                    if(array_key_exists((int)$input_id[0], $retired_devices) !== false) {
                        if(array_key_exists((int)$input_id[0], $used_retired_devices) == false) {
                            $used_retired_devices[$output->device_id] = $output->device_id;
                        }
                    }
                }

            }
            if($output->reserveinputs !== ''){
                $reserve_inputs = explode(', ', str_replace('.', '', $output->reserveinputs));
                foreach($reserve_inputs as $reserve_input) {
                    $input_id = explode(' ', $reserve_input);
                    if(array_key_exists((int)$input_id[0], $retired_devices) !== false) {
                        if(array_key_exists((int)$input_id[0], $used_retired_devices) == false ) {
                            $used_retired_devices[$output->device_id] = $output->device_id;
                        }
                    }
                }
            }
        }

        $deviceTypes = DeviceType::all();

        $device_names_list = array();
        $device_types_list = array();
        $device_types_names = array();
        foreach ($devices as $device) {
            $device_names_list[$device->id] = $device;
        }

        foreach ($deviceTypes as $type) {
            $device_types_list[$type->command] = $type;
            $device_types_names[$type->command] = $type->name;
        }




        return View::make('algorithms.list')
            ->with('thisBldg', $thisBldg)
            ->with('id', $id)
            ->with('sid', $sid)
            ->with('mappedOutputs', $mappedOutputs)
            ->with('device_names_list', $device_names_list)
            ->with('device_types_list', $device_types_list)
            ->with('used_retired_devices', $used_retired_devices)
            ->with('algorithms', $algorithms)
            ->with('zone_names',$zone_names)
            ->with('device_types_names',$device_types_names);
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id, $sid)
    {
        $thisBldg = Building::find($id);

        $algorithms = Algorithm::whereIn('customer_id',[0,$thisBldg->customer_id])
            ->get();

        $productTypes = ProductType::all();

        $deviceTypeFunctions = DeviceType::where('IO','Input')
            ->where('algorithm_active',1)
            ->groupby('function')
            ->orderBy('function')
            ->get();

        $deviceTypes = DeviceType::where('IO','Input')
            ->orderby('function', 'ASC')
            ->get();

        $device_types_names = array();
        foreach ($deviceTypes as $devt) {
            $device_types_names[$devt->command] = $devt->name;
        }

        $deviceTypesAvailable = Device::where('system_id',$sid)
            ->groupby('product_id')
            ->get();

        $typesArray = array();
        foreach($deviceTypes as $type){
            $typesArray[$type->command] = $type->function;
        }

        $devices = Device::where('system_id',$sid)
            ->orderby('zone','ASC','id', 'ASC')
            ->where('status',1)
            ->where('retired',0)
            ->get();

        $outDevices = MappingOutput::where('system_id',$sid)
            ->orderby('zone','ASC','name', 'ASC')
            ->get();

        $usedDevices = array();
        foreach ($outDevices as $device) {
            $usedDevices[$device->device_id] = $device->name;
        }

        $agorithmTemps = array();
        $algorithmTemps[0] = 'General';
        foreach($algorithms as $algorithm){
            $algorithmTemps[$algorithm->id] = $algorithm->algorithm_name;
        }

        $zones = Zone::where('system_id',$sid)
            ->orderBy('zone','ASC')
            ->get();

        $zone_array = array();
        /*$zone_array[0] = 'No Zone';*/
        foreach($zones as $zone) {
            $zone_array[$zone->zone] = $zone->zonename;
        }

        $zone = Zone::where('system_id',$sid)
            ->get();
        $zone_names = array();
        foreach ($zone as $zone_num) {
            if(strlen($zone_num->zonename) > 0)
            $zone_names[$zone_num->zone] = $zone_num->zonename;
        }

        $outputDevices[0] = 'Create Virtual Device';

        foreach($devices as $device){
            if($device->device_io == 'output' and $device->name != null and $device->zone != 0 and !array_key_exists($device->id, $usedDevices)){
                $outputDevices[$device->id] = $device->name;
            }
        }

        return View::make('algorithms.addform')
            ->with('id'                  , $id)
            ->with('algorithm'           , $algorithms)
            ->with('algorithmTemps'      , $algorithmTemps)
            ->with('outputDevices'       , $outputDevices)
            ->with('thisBldg'            , $thisBldg)
            ->with('sid'                 , $sid)
            ->with('thisBldg'            , $thisBldg)
            ->with('devices'             , $devices)
            ->with('outDevices'          , $outDevices)
            ->with('deviceTypesAvailable', $deviceTypesAvailable)
            ->with('productTypes'        , $productTypes)
            ->with('deviceTypeFunctions' , $deviceTypeFunctions)
            ->with('deviceTypes'         , $deviceTypes)
            ->with('zone_array'          , $zone_array)
            ->with('typesArray'          , $typesArray)
            ->with('zone_names'          , $zone_names)
            ->with('device_types_names',$device_types_names);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($id, $sid)
    {

        if( null != Input::get('savetemp') ) {

            $algTemps = Algorithm::all();
            $tempArray = array();
            foreach($algTemps as $temp) {
                $tempArray[$temp->id] = $temp->function_type;
            }
            $input = Input::all();
            $thisBldg = Building::find($id);

            $newTemp = new Algorithm();
            $newTemp->customer_id = $thisBldg->customer_id;
            $newTemp->algorithm_name = $input['name'];
            if($input['algorithm_id'] != 0) {
                $newTemp->function_type = $tempArray[$input['algorithm_id']];
            }
            else {
                $newTemp->function_type = 'Virtual';
            }
            $newTemp->description = '';
            $newTemp->inputs_req = $input['min_required_inputs'];
            $newTemp->inputs_max = 0;
            $newTemp->polarity = $input['polarity'];
            $newTemp->logicmode = $input['logicmode'];
            $newTemp->ondelay = $input['ondelay'];
            $newTemp->offdelay = $input['offdelay'];
            $newTemp->duration = $input['duration'];
            $newTemp->votes = $input['min_required_inputs'];
            $newTemp->season = $input['season'];
            $newTemp->response = $input['response'];
            $newTemp->basesetpoint = null;
            $newTemp->save();

            return Redirect::back();
        }


        $output = new MappingOutput();

        $maxID = Device::where('system_id',$sid)
            ->max('id');

        foreach(Input::except('_token', 'onDelayFactor', 'offDelayFactor', 'durationFactor', 'toggleDurationFactor', 'sensor', 'save','name','virtual_device_type') as $key => $value) {
            $output->$key = $value;
        }

        if((int)$output->device_id != 0){
          $controlDevice = Device::where('system_id',$sid)
          ->where('id',$output->device_id)
          ->first();
          $output->device_type = $controlDevice->device_types_id;
          $output->zone = $controlDevice->zone;
        }
        if($output->device_id == 0){
            $outputDevice = new Device();
            $outputDevice->id = $maxID + 1;
            $outputDevice->building_id = $id;
            $outputDevice->system_id = $sid;
            $outputDevice->instance = 0;
            $outputDevice->device_mode = 'virtual';
            $outputDevice->device_function = 'Virtual';
            $outputDevice->zone = Input::get('zone');
            $outputDevice->short_address = 0;
            $outputDevice->location = 0;
            $outputDevice->name = Input::get('algorithm_name');
            $outputDevice->physical_location = 'the cloud';
            $outputDevice->comments = Input::get('description');
            $outputDevice->bacnet_object_type = 0;
            if(Input::get('virtual_device_type') == 'virtual_input'){
                $outputDevice->device_types_id = 36;
                $outputDevice->status = '1';
                $outputDevice->device_io = 'input';
                $outputDevice->product_id = 'V2';
                $outputDevice->functional_description = ' ';
            }else{
                $outputDevice->device_types_id = 19;
                $outputDevice->device_io = 'output';
                $outputDevice->product_id = 'V1';
                $outputDevice->functional_description = ' ';
            }
            $outputDevice->save();
            
            $output->device_id = $outputDevice->id;
            $output->device_type = $outputDevice->device_types_id;
            $output->zone = Input::get('zone');
            
        }
        $template = Algorithm::Find($output->algorithm_id);
        $output->system_id = $sid;
        $onFactor = (int)Input::get('onDelayFactor');
        $offFactor = (int)Input::get('offDelayFactor');
        $durFactor = (int)Input::get('durationFactor');
        if(null != Input::get('toggleDurationFactor')) {
            $togFactor = (int)Input::get('toggleDurationFactor');
        }
        if(is_null($template)){
          $output->function_type = 'General';
        }
        else{
          $output->function_type = $template->function_type;
        }
        $output->ondelay = $output->ondelay*$onFactor;
        $output->offdelay = $output->offdelay*$offFactor;
        $output->duration = $output->duration*$durFactor;
        if(null != Input::get('toggleDurationFactor')) {
            $output->default_toggle_duration = $output->default_toggle_duration*$togFactor;
        } else {
            $output->default_toggle_duration = 0;
        }
        if(null != Input::get('default_toggle_percent_on')){
            $output->default_toggle_percent_on = Input::get('default_toggle_percent_on');
        }else{
            $output->default_toggle_percent_on = 0;
        }
        $output->active_inputs = Input::get('inputs');
        $output->lost_inputs = ' ';
        $output->total_inputs = Input::get('min_required_inputs');
        $output->override = 0;
        $output->overridetime = 0;
        $output->save();
        AlgorithmController::DeployMapping($sid);
        return Redirect::route("system.editSystem", [$id, $sid]);
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
    public function edit($id, $sid, $outid)
    {
        $thisBldg = Building::find($id);

        $oldOutput = MappingOutput::find($outid);

        $algorithms = Algorithm::all();

        $productTypes = ProductType::all();

        $currentTime = time();

        $deviceTypes = DeviceType::all();
        $device_types_names = array();
        foreach ($deviceTypes as $value) {
            $device_types_names[$value->command] = $value->name;
        }

        $deviceTypeFunctions = DeviceType::where('IO','Input')
            ->groupby('function')
            ->orderBy('function')
            ->get();

        $deviceTypes = DeviceType::where('IO','Input')
            ->orderby('function', 'ASC')
            ->get();
        $deviceTypesAvailable = Device::where('system_id',$sid)
            ->groupby('product_id')
            ->get();
        $devices = Device::where('system_id',$sid)
            ->where('id','!=',99999)
            // ->where('status',1)
            // ->where('retired',0)
            ->orderby('zone','ASC','id', 'ASC')
            ->get();

        $algOutputDevice = new Device();
        foreach ($devices as $dev) {
            if($dev->id == $oldOutput->device_id){
                $algOutputDevice = $dev;
            }
        }
        $algOutputProduct = new ProductType();
        foreach ($productTypes as $prod) {
            if($prod->product_id == $algOutputDevice->product_id){
                $algOutputProduct = $prod;
            }
        }

        $algorithmTemps = array();
        foreach($algorithms as $algorithm){
            $algorithmTemps[$algorithm->id] = $algorithm->algorithm_name;
        }

        $outDevices = MappingOutput::where('system_id',$sid)
            ->orderby('zone','ASC','device_id', 'ASC')
            ->get();

        $zone = Zone::where('system_id',$sid)
            ->get();
        $zone_names = array();
        foreach ($zone as $zone_num) {
            if(strlen($zone_num->zonename) > 0)
            $zone_names[$zone_num->zone] = $zone_num->zonename;
        }

        return View::make('algorithms.editform')
            ->with('id', $id)
            ->with('algorithm', $algorithms)
            ->with('algorithmTemps', $algorithmTemps)
            ->with('thisBldg', $thisBldg)
            ->with('sid', $sid)
            ->with('outid', $outid)
            ->with('thisBldg', $thisBldg)
            ->with('devices', $devices)
            ->with('deviceTypesAvailable',$deviceTypesAvailable)
            ->with('productTypes', $productTypes)
            ->with('outDevices', $outDevices)
            ->with('deviceTypeFunctions',$deviceTypeFunctions)
            ->with('deviceTypes', $deviceTypes)
            ->with('currentTime',$currentTime)
            ->with('oldOutput', $oldOutput)
            ->with('zone_names', $zone_names)
            ->with('device_types_names',$device_types_names)
            ->with('algOutputDevice',$algOutputDevice)
            ->with('algOutputProduct',$algOutputProduct);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, $sid, $outid)
    {
        $thisBldg = Building::find($id);

        $form = MappingOutput::Find($outid);

        $outputDevices = MappingOutput::all();

        $lostDevices = Alarms::where('system_id',$sid)
            ->where('alarm_code_id',11)
            ->where('active',1)
            ->groupby('device_id')
            ->orderby('updated_at','DESC')
            ->get();

        $lostInputs = "";
        $activeInputs = "";

        foreach(Input::except('_token', '_method', 'onDelayFactor', 'offDelayFactor', 'durationFactor', 'toggleDurationFactor', 'sensor', 'save', 'templates') as $key => $value) {
            $form->$key = $value;
        }
        if((int)$form->logicmode != 0)
          $form->min_required_inputs = 0;

        $template = Algorithm::Find($form->algorithm_id);

        $onFactor = (int)Input::get('onDelayFactor');
        $offFactor = (int)Input::get('offDelayFactor');
        $durFactor = (int)Input::get('durationFactor');
        if(null != Input::get('toggleDurationFactor')) {
            $togFactor = (int)Input::get('toggleDurationFactor');
        }
        /*Check if device name needs to be updated*/
        $algorithmName = Input::get('algorithm_name');
        $od = MappingOutput::Find($outid);
        $device_id = $od->device_id;
        DB::table('devices')
            ->where('system_id',$sid)
            ->where('id',$device_id)
            ->update(['name' => $algorithmName]);


        $form->function_type = $template->function_type;
        $form->ondelay = $form->ondelay*$onFactor;
        $form->offdelay = $form->offdelay*$offFactor;
        $form->duration = $form->duration*$durFactor;
        if(null != Input::get('toggleDurationFactor')) {
            $form->default_toggle_duration = $form->default_toggle_duration*$togFactor;
        } else {
          $form->default_toggle_duration = 0;
        }
        if(null != Input::get('default_toggle_percent_on')) {
            $form->default_toggle_percent_on = Input::get('default_toggle_percent_on');
        } else {
          $form->default_toggle_percent_on = 0;
        }

        $primary_inputs = array ();
        $secondary_inputs = array ();

        $numInputs = count( explode(',', Input::get('inputs')));
        if(strlen(Input::get('inputs')) <= 0) {
            $numInputs = 0;
        }

        if('' != Input::get('inputs')){
            foreach(explode(', ', str_replace('.', '', Input::get('inputs'))) as $index => $device) {
                $primary_inputs[$index] = explode(' ', $device);
            }
        }

        if('' != Input::get('reserveinputs')){
            foreach(explode(', ', str_replace('.', '', Input::get('reserveinputs'))) as $index => $device) {
                $secondary_inputs[$index] = explode(' ', $device);
            }
        }
        $activeCount = $numInputs;

        foreach ($primary_inputs as $input) {
            $foundLost = 0;
            foreach ($lostDevices as $lostDevice) {
                if ($lostDevice->device_id == $input[0] && $lostDevice->command == $input[1]) {
                    $foundLost = 1;
                    if($lostInputs != ""){
                        $lostInputs .= ", " . $input[0] . " " . $input[1];
                    } else {
                        $lostInputs = $input[0] . " " . $input[1];
                    }
                    $activeCount -= 1;
                    break;
                }
            }

            if ($foundLost == 0) {
                if($activeInputs != ""){
                    $activeInputs .= ", " . $input[0] . " " . $input[1];
                } else {
                    $activeInputs = $input[0] . " " . $input[1];
                }
            }
        }

        foreach ($secondary_inputs as $input) {
            $foundLost = 0;
            foreach ($lostDevices as $lostDevice) {
                if ($lostDevice->device_id == $input[0] && $lostDevice->command == $input[1]) {
                    $foundLost = 1;
                    if($lostInputs != ""){
                        $lostInputs .= ", " . $input[0] . " " . $input[1];
                    } else {
                        $lostInputs = $input[0] . " " . $input[1];
                    }
                    break;
                }
            }

            if($activeCount < $numInputs && $foundLost == 0) {
                if($activeInputs != ""){
                    $activeInputs .= ", " . $input[0] . " " . $input[1];
                } else {
                    $activeInputs = $input[0] . " " . $input[1];
                }
                $activeCount += 1;
            }


        }
        if($activeInputs != '') {
            $activeInputs .= ".";
        }
        if($lostInputs != '') {
            $lostInputs .= ".";
        }

        foreach(explode(', ', str_replace('.', '', Input::get('reserveinputs'))) as $index => $device) {
            $secondary_inputs[$index] = explode(' ', $device);
        }

        $form->active_inputs = $activeInputs;
        $form->lost_inputs = $lostInputs;
        $form->overridetime = 0;
        $form->save();

        AlgorithmController::DeployMapping($sid);

        return Redirect::route("system.editSystem", [$id, $sid]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id,$sid,$outid)
    {
        $output = MappingOutput::Find($outid);
        $device_id = $output->device_id;
        $command = $output->device_type;
        if( $command == 19 ) {
            $device = Device::where('system_id',$sid)
                ->where('id',$device_id)
                ->where('device_types_id',19)
                ->first();

            $device->delete();
        }

        $output->delete();

        AlgorithmController::DeployMapping($sid);

        return Redirect::route("system.editSystem", [$id, $sid]);
    }


    /**
    * Send a new algorithm mapping file to a remote machine
    * This mapping cover output devices, for use by ./alg_bot
    * @param int    $system_id The system to send a mapping file too
    */
    public static function DeployMapping($system_id)
    {
        // Grab full config data and create config.txt in /storage
        $mappings = MappingOutput::where('system_id', $system_id)
            ->get();

        $content = "";

        foreach ($mappings as $mapping) {


          $numInputs = count( explode(',', $mapping->active_inputs));
          if(strlen($mapping->active_inputs) <= 0) {
            $numInputs = 0;
          }

          $content .= sprintf("%05d",$mapping->device_id)    . ' ';
          $content .= sprintf("%02d",$mapping->device_type)  . ' ';
          $content .= $mapping->logicmode                    . ' ';
          $content .= $mapping->min_required_inputs          . ' ';
          $content .= $mapping->season                       . ' ';
          $content .= $mapping->polarity                     . ' ';
          $content .= $mapping->ondelay                      . ' ';
          $content .= $mapping->offdelay                     . ' ';
          $content .= $mapping->duration                     . ' ';
          $content .= $mapping->response                     . ' ';
          if($mapping->default_state == '2'){/*for toggle default*/
            $content .= ((int)$mapping->default_toggle_percent_on) . ':' . ((int)$mapping->default_toggle_duration) . ' ';
          } else {
            $content .= $mapping->default_state              . ' ';
          }
          $content .= $numInputs                             . ' ';
          $content .= str_replace(['.',','], ['',''], $mapping->active_inputs);
          $content .= "\n";
        }

        RemoteTask::deployFile($system_id, '/var/2020_mapping', 'algo_out_mapping.txt', $content);
        RemoteTask::deployFile($system_id, '/var/2020_algorithm/table_updates', 'update.txt', 'algo_out_mapping.txt');
    }
}
