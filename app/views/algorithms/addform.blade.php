<?php $title="Add Algorithm"; ?>

@extends('layouts.wrapper')

@section('content')

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script>

    var templates = {
      @foreach($algorithm->toArray() as $alg)
         {{$alg['id']}}: {
          @foreach($alg as $key => $value)
            {{$key}}: '{{$value}}',
          @endforeach
         },
      @endforeach
    };
    var commandTypes = {
      @foreach($deviceTypes->toArray() as $type)
        {{$type['command']}}:{
          @foreach($type as $key => $value)
            {{$key}}: '{{$value}}',
            @endforeach
        },
        @endforeach
    };


    var deviceZones = {
      @foreach($devices as $device)
        {{$device->id}}: {{$device->zone}},
      @endforeach
    };
    deviceZones[0] = 0;


    function updatePrimaryList(id, type){
          var devices = $("#inputs").val();

          var oldInVal = $("#inputs").val();
          var newInVal = oldInVal.replace('.','');
          if(newInVal === "empty" || newInVal === ""){

              $("#inputs").prop('value',id + ' ' + type + '.');
          }
          else if(newInVal !== "empty" || newInVal !== ""){

              $("#inputs").prop('value',newInVal + ', ' + id + ' ' + type + '.');
          }

          var reserve = $("#reserveinputs").val();
          var single = '' + id + ' ' + type + '.';
          var multiple1 = ', '+ id + ' ' + type + '';
          var multiple2 = '' + id + ' ' + type + ', ';
          var end1 = ''+ id + ' ' + type + '.';
          var end2 = ', ' + id + ' ' + type + '.';
          var existSingle = reserve.search(single);
          var existMultiple1 = reserve.search(multiple1);
          var existMultiple2 = reserve.search(multiple2);
          var existend1 = reserve.search(end1);
          var existend2 = reserve.search(end2);
          var newReserve;
          if(existMultiple1 !== -1){
              newReserve = reserve.replace(multiple1,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){
              newReserve = reserve.replace(multiple2,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existend1 !== -1){
              newReserve = reserve.replace(end1,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existend2 !== -1){
              newReserve = reserve.replace(end2,'');
              newReserve = newReserve + '.';
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){
              newReserve = reserve.replace(single,'');
              $("#reserveinputs").prop('value',newReserve);
          }

          var deviceCount = (devices.match(/,/g) || []).length;
          if(devices == "" || devices === null){
              $("#save").prop('disabled',false);
              $("#save").prop('value','Save');
              deviceCount = 0;
          }
          else{
            deviceCount+=1;
          }

    }

    function updateReserveList(id, type){

          var oldInVal = $("#reserveinputs").val();
          var newInVal = oldInVal.replace('.','');

          if(newInVal === "empty" || newInVal === ""){

              $("#reserveinputs").prop('value',id + ' ' + type+ '.');
          }
          else if(newInVal !== "empty" || newInVal !== ""){

              $("#reserveinputs").prop('value',newInVal + ', ' + id + ' ' + type + '.');
          }

          var primary = $("#inputs").val();
          var single = '' + id + ' ' + type + '.';
          var multiple1 = ', '+ id + ' ' + type + '';
          var multiple2 = '' + id + ' ' + type + ', ';
          var end1 = ''+ id + ' ' + type + '.';
          var end2 = ', ' + id + ' ' + type + '.';
          var existSingle = primary.search(single);
          var existMultiple1 = primary.search(multiple1);
          var existMultiple2 = primary.search(multiple2);
          var existend1 = primary.search(end1);
          var existend2 = primary.search(end2);
          var newReserve;
          if(existMultiple1 !== -1){

              newReserve = primary.replace(multiple1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){

              newReserve = primary.replace(multiple2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existend1 !== -1){
              newReserve = primary.replace(end1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existend2 !== -1){
              newReserve = primary.replace(end2,'');
              newReserve = newReserve + '.';
              $("#inputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){

              newReserve = primary.replace(single,'');
              $("#inputs").prop('value',newReserve);
          }

          var devices = $("#inputs").val();
          var deviceCount = (devices.match(/,/g) || []).length;
          if(devices === "" || devices === null){
              $("#save").prop('value','Select Devices');
               $("#save").prop('disabled',true);
               deviceCount = 0;
          }
          else{
            deviceCount+=1;
          }
      }

    function clearList(id, type){

          var primary = $("#inputs").val();
          var single = '' + id + ' ' + type + '';
          var multiple1 = ', '+ id + ' ' + type + '';
          var multiple2 = '' + id + ' ' + type + ', ';
          var end1 = ', '+ id + ' ' + type + '.';
          var end2 = '' + id + ' ' + type + '.';
          var existSingle = primary.search(single);
          var existMultiple1 = primary.search(multiple1);
          var existMultiple2 = primary.search(multiple2);
          var existend1 = primary.search(end1);
          var existend2 = primary.search(end2);
          var newReserve;
          if(existMultiple1 !== -1){
              newReserve = primary.replace(multiple1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){
              newReserve = primary.replace(multiple2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existend1 !== -1){
              newReserve = primary.replace(end1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existend2 !== -1){
              newReserve = primary.replace(end2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){
              newReserve = primary.replace(single,'');
              $("#inputs").prop('value',newReserve);
          }

          var reserve = $("#reserveinputs").val();
          var ressingle = '' + id + ' ' + type + '';
          var resmultiple1 = ', '+ id + ' ' + type + '';
          var resmultiple2 = '' + id + ' ' + type + ', ';
          var resEnd1 = ', '+ id + ' ' + type + '.';
          var resEnd2 = '' + id + ' ' + type + '.';
          var resexistSingle = reserve.search(ressingle);
          var resexistMultiple1 = reserve.search(resmultiple1);
          var resexistMultiple2 = reserve.search(resmultiple2);
          var resexistend1 = reserve.search(resEnd1);
          var resexistend2 = reserve.search(resEnd2);
          var resnewReserve;
          if(resexistMultiple1 !== -1){
              resnewReserve = reserve.replace(resmultiple1,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistMultiple2 !== -1){

              resnewReserve = reserve.replace(resmultiple2,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistend1 !== -1){

              resnewReserve = reserve.replace(resEnd1,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistend2 !== -1){

              resnewReserve = reserve.replace(resEnd2,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistSingle !== -1){

              resnewReserve = reserve.replace(ressingle,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }

          var devices = $("#inputs").val();
          var deviceCount = (devices.match(/,/g) || []).length;
          if(devices === "" || devices === null){
              $("#save").prop('value','Select Devices');
               $("#save").prop('disabled',true);
               deviceCount = 0;
          }
          else{
            deviceCount+=1;
          }


    }

    function tempFill(tempID){
        $("#logicmode").prop('value',templates[tempID].logicmode);
        if(templates[tempID].logicmode != 0)
          $("#min_required_inputs").prop('disabled',true);
        else if(templates[tempID].logicmode == 0)
          $("#min_required_inputs").prop('disabled',false);

        $("#min_required_inputs").prop('value',templates[tempID].votes);
        $("#polarity").prop('value',templates[tempID].polarity);
        $("#season").prop('value',templates[tempID].season);
        $("#response").prop('value',templates[tempID].response);
        $("#ondelay").prop('value',templates[tempID].ondelay);
        $("#offdelay").prop('value',templates[tempID].offdelay);
        $("#duration").prop('value',templates[tempID].duration);
    }

    function sensorTitle(tempID){
        if(tempID in templates){
          @foreach($deviceTypes as $type)
            SensorGroup = '{{str_replace(' ','',$type->function)}}Sensors';
            $('#{{str_replace(' ','',$type->function)}}ButtonLabel').removeClass('active');

            if(templates[tempID].algorithm_name === '{{$type->function}}'){
              $("#{{str_replace(' ','',$type->function)}}Sensors").show();
              $('#{{str_replace(' ','',$type->function)}}ButtonLabel').addClass('active');
            }

            else if(!($("#{{$type->function}}Sensors").hasClass("hidden"))){
              $("#{{str_replace(' ','',$type->function)}}Sensors").hide();
            }
          @endforeach
    }
    }

    function displaySensors(type,typeName){
      var newFunction = typeName.replace(' ','');
      device = document.getElementById(type+"Label");
      if(!($("#" + device.id).hasClass("active"))){
        $("#"+newFunction+"Sensors").show();
      }
      else if($("#"+device.id).hasClass("active")){
        $("#"+newFunction+"Sensors").hide();
      }
    }

    $(function() {
      $("#default_state").change(function() {
        console.log(this.value);
        if(this.value == '2') {
          $("#default_toggle_percent_on").removeAttr('disabled');
          $("#default_toggle_duration").removeAttr('disabled');
          $("#toggleDurationFactor").removeAttr('disabled');
        } else {
          $("#default_toggle_percent_on").attr('disabled','disabled');
          $("#default_toggle_duration").attr('disabled','disabled');
          $("#toggleDurationFactor").attr('disabled','disabled');
        }
      });

      $("#device_id").change(function(item){
          $("[name=zone").val(deviceZones[this.value]);
          if(this.value == '0') {
            $("#zone").removeAttr('disabled');
          } else {
            $("#zone").attr('disabled','disabled');
          }
      });
      $("#algorithm_id").change(function(){
          tempFill(this.value);
          sensorTitle(this.value);
      });
      $("#helpDialog").dialog({
        autoOpen: false,
        show:{
          effect: "fade",
          duration: 1000
        }
      });
      $("#helpButton").click(function(){
        $("#helpDialog").dialog("open");
      });
      @foreach($deviceTypesAvailable as $device)

        @foreach($productTypes as $product)

          @if($product->product_id == $device->product_id)
            @foreach($deviceTypes as $type)
              var commandType = '{{$type->function}}';
              var commandTypeFix = commandType.replace(' ','');
              var commandsString = '{{$product->commands}}';
              var commands = commandsString.split(',');
              var commandFound = commands.indexOf("" + {{$type->command}} + "");
              if(commandFound != -1){
                $('#' + commandTypeFix + 'ButtonLabel').removeAttr('disabled');
              }
            @endforeach
          @endif

        @endforeach
      @endforeach
        @if($outDevices != NULL)
          $('#VirtualDevicesButtonLabel').removeAttr('disabled');
        @endif

      @foreach($deviceTypes as $type)
      var commandType = '{{$type->function}}';
      var commandTypeFix = commandType.replace(' ','');
          $("#" + commandTypeFix + "Sensors").hide();
          $("#" + commandTypeFix + "Sensors").removeClass("hidden");
      @endforeach
      $("#VirtualDevicesSensors").hide();
      $("#VirtualDevicesSensors").removeClass("hidden");
      sensorTitle( document.getElementById("algorithm_id").value);
      $("#accordian").accordion({heightStyle: "content"});
      $("#logicmode").change(function(){
        if($(this).val() !== '0'){
          $("#votesLabel").html("Minimum Active Inputs");
        } else{
          $("#votesLabel").html("Votes");
        }
      });
      $('form').on("submit", function(){
        var votes = $("#min_required_inputs").val();
        var devices = $("#inputs").val();
        var deviceCount = (devices.match(/,/g) || []).length;
        deviceCount+=1;
        if(votes > deviceCount){
            alert('The number of votes you have chosen exceeds the number of primary devices being referenced. We have lowered this number to the maximum of '+deviceCount+' votes.');
            $("#min_required_inputs").prop('value',deviceCount);
        }
        $("#min_required_inputs").prop('disabled',false);
      });
    });

  </script>
  <style>
  #format {
      margin-top: 2em;

  }
  #helpButton{
      icons:{
        primary: "ui-icon-info";
      };
  }

</style>


{{Form::open(['route'=>['algorithm.store', $thisBldg->id, $sid], "method" => "post", "class"=>"js-supress-enter"])}}
<div class="row">
  <div class="col-xs-12">
    <h3>Add Algorithm</h3>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-6" style="text-align: center;">
    <label for="algorithm_name"><h4>Algorithm Name</h4></label>
    {{Form::text('algorithm_name','Algorithm '. count($outDevices),["class" => "form-control input-lg  ", "style" => "color:black", "placeholder" => "Algorithm Name", "id" => "algorithm_name", "title" => "Individual name of this algorithm."])}}
  </div>
  <div class="col-xs-12 col-md-6" style="text-align: center;">
    <label for="device_id"><h4>Output Device</h4></label><br>
    {{Form::select('device_id', $outputDevices, 0 ,["class" => "form-control input-lg", "style" => "color:black; text-align: center","id" => "device_id","name" => "device_id", "title" => "Choose a device to control."])}}
    <div class=" col-xs-12 btn-group" data-toggle="buttons" style="text-shadow:none; padding:10px;">
      <div class="col-xs-6">
        <label class="btn btn-primary btn-lg emc-radio-btn">
          <input type="radio" name="virtual_device_type" id="virtual_input" value="virtual_input" autocomplete="off" style="visibility: hidden">
          VIRTUAL INPUT
        </label>
      </div>
      <div class="col-xs-6">
        <label class="btn btn-primary btn-lg emc-radio-btn">
          <input type="radio" name="virtual_device_type" id="virtual_output" value="virtual_output" autocomplete="off" style="visibility: hidden">
          VIRTUAL OUTPUT
        </label>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <?php
    $i = 0;
    $commandArray = array();
    $commandIndex = 0;
  ?>
  <div class="form-group col-xs-12 col-md-6" style="text-align: center;">
    <label for="function_type"><h4>Template</h4></label>
    {{Form::select('algorithm_id', $algorithmTemps, $algorithmTemps[0] ,["class" => "form-control input-lg", "style" => "color:black; text-align: center","id" => "algorithm_id","name" => "algorithm_id", "title" => "Choose your basic algorithm template."])}}
  </div>
  <div class="col-xs-12 col-md-6" style="text-align: center;">
      <label for="sensorDisplay"><h4>Sensor Types</h4></label>
      <div class="col-xs-12 btn-group" style="text-align:center" data-toggle="buttons" id="sensorDisplay" title="Types of sensors available.">

      @foreach($deviceTypeFunctions as $type)
        <!-- @if((int)$type->algorithm_active) -->
          @if(array_search($type->function,$commandArray, false) === false)
            <?php $commandArray[$commandIndex++] = $type->function; ?>
            <label class="col-xs-6 col-sm-3 col-md-3 btn btn-primary" id="{{str_replace(' ','',$type->function)}}ButtonLabel" disabled="true" for="{{$type->function}}Button"><input type="checkbox" id="{{$type->function}}Button" onChange="displaySensors(this.id,'{{$type->function}}')">{{$type->function}}</label>
            <?php $i++; ?>
            @if($i%4==0)
              </div>
              <br>
              <div class="col-xs-12 btn-group" style="text-align:center" data-toggle="buttons" id="sensorDisplay" title="Types of sensors available.">
             @endif
          @endif
        <!-- @endif -->
      @endforeach
      <label class="col-xs-6 col-sm-3 col-md-3 btn btn-primary" id="VirtualDevicesButtonLabel" disabled="true" for="VirtualDevicesButton"><input type="checkbox" id="VirtualDevicesButton" onChange="displaySensors(this.id,'VirtualDevices')">Other Devices</label>
      <?php $i++; ?>
    </div>
        <br>

        <div class="col-xs-12 btn-group" style="text-align:center" data-toggle="buttons" id="sensorDisplay" title="Types of sensors available.">
    </div>
  </div>
<br>
</div>

<div class="col-xs-12 row-padding" id="sensors" style="min-height: 30px; width: 100%; text-align: center;">
  <label for="sensors"><h4 id="sensorTitle">Sensors</h4></label>
  <?php $i = 0; ?>
    @foreach($deviceTypeFunctions as $types)

      <!-- @if((int)$types->algorithm_active) -->
        <div id="{{str_replace(' ','',$types->function)}}Sensors" name="{{$types->function}}Sensors" class="hidden">
        @foreach($deviceTypes as $type)
          @if($types->function == $type->function)
            @foreach($devices as $sensor)
              @foreach($productTypes as $product)
                @if($sensor->product_id == $product->product_id && (int)$sensor->zone != 0)
                  <?php
                  $deviceTypeArray = explode(',',$product->commands);
                  if(in_array($type->command,$deviceTypeArray) && (int)$sensor->id != 0){
                     echo
                      '<div class="col-xs-6 col-sm-4 row-padding" id="sensor'.$i.'" name="sensor'.$i.'" style="text-align:center;vertical-align:text-bottom; min-height: 120px; max-height:none; ">
                        <h4>'.$sensor->name.'</h4>
                            <h5><small>Zone: </small>'.$zone_names[$sensor->zone].'<br><small>ID: </small>'.$sensor->id.'<br><small>Sensor Type: </small>'.$device_types_names[$type->command].'</h5>
                            <div id="format" class="btn-group" data-toggle="buttons" style="margin-top: 1px" title="Sensor use state.">';
                                    echo '<label class="btn btn-primary" style="width:100%">
                                        <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="0">Primary
                                    </label>
                                    <label class="btn btn-primary" style="width:100%">
                                        <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateReserveList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="1">Reserve
                                    </label>
                                    <label class="btn btn-primary active" style="width:100%">
                                        <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="2">Not Used
                                    </label>';
                            echo '</div>
                        </div>';
                  }
                  ?>
                @endif
              @endforeach
            @endforeach
          @endif
        @endforeach
      </div>
      <!-- @endif -->
    @endforeach
    <div id="VirtualDevicesSensors" name="VirtualDevicesSensors" class="hidden">
    @foreach($outDevices as $sensor)
        <?php
           echo
            '<div class="col-xs-6 col-sm-4 row-padding" id="sensor'.$i.'" name="sensor'.$i.'" style="text-align:center;">
              <h4>'.$sensor->algorithm_name.'</h4>
                  <h5><small>Zone: </small>'.$zone_names[$sensor->zone].'<br><small>ID: </small>'.$sensor->device_id.'<br><small>Sensor Type: </small>Virtual</h5>
                  <div id="format" class="btn-group" data-toggle="buttons" style="margin-top: 1px" title="Sensor use state.">
                          <label class="btn btn-primary" style="width:100%">
                              <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->device_id.', '.$sensor->device_type.');" autocomplete="off" value="0">Primary
                          </label>
                          <label class="btn btn-primary" style="width:100%">
                              <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateReserveList('.$sensor->device_id.', '.$sensor->device_type.');" autocomplete="off" value="1">Reserve
                          </label>
                          <label class="btn btn-primary active" style="width:100%">
                              <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->device_id.', '.$sensor->device_type.');" autocomplete="off" value="2">Not Used
                          </label>';
                  echo '</div>
              </div>';
        ?>
    @endforeach
  </div>
</div>
<br>

<h4 style="font-weight: bold;">Algorithm Parameters:</h4>

<div class="form-group">
    <div class="row" style="text-align:center">
        <div class="col-xs-6 col-sm-4 col-md-2">Logic Mode<br>
          {{Form::select('logicmode',['0' => 'AND', '1' => 'OR', '2' =>'NAND', '3' => 'NOR', '4' => 'XOR'],0,["class" => "form-control", "style" => "color:black", "id" => "logicmode", "title" => "Choose the mode which the sensors will be compared."])}}
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">Priority Event<br>
          {{Form::select('priority_events',['0' => 'Off', '1' => 'On'],1,["class" => "form-control", "style" => "color:black", "id" => "priority_events", "title" => "Choose whether this algorithm state should be monitored."])}}
        </div>
       <div class="col-xs-6 col-sm-4 col-md-2"><text id="votesLabel">Votes</text><br>
          {{Form::text('min_required_inputs',1,["class" => "form-control", "style" => "color:black", "placeholder" => "# Votes", "id" => "min_required_inputs", "title" => "Minimum number of sensors required to activate."])}}
       </div>
       <div class="col-xs-6 col-sm-4 col-md-2">Polarity Mode<br>
          {{Form::select('polarity',['0' => 'Direct', '1' => 'Inverse'],0,["class" => "form-control", "style" => "color:black", "id" => "polarity", "title" => "Choose direct or inverse polarity."])}}
       </div>
       <div class="col-xs-6 col-sm-4 col-md-2">Active Season<br>
          {{Form::select('season',['0' => 'Winter', '1' => 'Summer', '2' => 'Year-Round'],0,["class" => "form-control", "style" => "color:black", "id" => "season", "title" => "Choose whether to observe or ignore the season."])}}
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">Response<br>
          {{Form::select('response',['0' => 'Off', '1' => 'On'], 1,["class" => "form-control", "style" => "color:black", "id" => "response", "title" => "Turn response on or off." ])}}
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">Zone<br>
          {{Form::select('zone',$zone_array,0,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "zone", "title" => "Zone associated with this algorithm."])}}
        </div>
    </div>
    <div class="row" style="text-align:center">
        <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">On Delay<br>
            <div class="input-group">
                {{Form::text('ondelay',0,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "ondelay", "title" => "Time delay before activation."])}}
                <span class="input-group-btn">{{Form::select('onDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])}}</span>
            </div>
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">Off Delay<br>
            <div class="input-group">
                {{Form::text('offdelay',0,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "offdelay", "title" => "Time delay before deactivation."])}}
                <span class="input-group-btn">{{Form::select('offDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])}}</span>
            </div>
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">Duration<br>
            <div class="input-group">
                {{Form::text('duration',0,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "duration", "title" => "Duration of time to remain active before automatically deactivating."])}}
                <span class="input-group-btn">{{Form::select('durationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])}}</span>
            </div>
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3" style="margin-bottom: 15px;">Default State<br>
          {{Form::select('default_state', [0 => 'Off', 1 => 'On', 2 => 'Toggle'], 0, ["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "default_state", "title" => "If the inputs to the algorithm have stopped reporting or have, themselves, defaulted, the algorithm will assume the chosen state"])}}
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-4  col-sm-offset-0 col-md-3">Toggle Percent On<br>
          {{Form::text('default_toggle_percent_on',0,["class" => "form-control", "disabled", "style" => "color:black", "placeholder" => "0", "id" => "default_toggle_percent_on", "title" => "Percentage of the Toggle Period time which the algorithm will choose to be ON/High, in the event of a defaulted state"])}}
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">Toggle Period<br>
            <div class="input-group">
                {{Form::text('default_toggle_duration',0,["class" => "form-control", "disabled", "style" => "color:black", "placeholder" => "0", "id" => "default_toggle_duration", "title" => "The time of one complete toggling cycle of ON/High and OFF/Low states, in the event of a defaulted state"])}}
                <span class="input-group-btn">{{Form::select('toggleDurationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "id" => "toggleDurationFactor", "disabled", "style" => "color:black"])}}</span>
            </div>
        </div>
    </div><br>
    <div class="row" style=" margin-bottom: 50px">
      <div class="col-xs-12" style="margin-bottom: 5px">Description<br>
      {{Form::textarea('description',NULL,["class" => "form-control", "rows" => "2", "style" => "color:black; resize: vertical; max-height: 200px;", "height" => "1000px", "id" => "description","placeholder" => "Enter Description", "title" => "Brief description of the algorithms purpose or function."])}}</div><br>
        <div>{{Form::text('inputs',NULL,["id" => "inputs", "style" => "color:black", "name" => "inputs", "style" => "display:none"])}}</div>
        <div>{{Form::text('reserveinputs',NULL,["id" => "reserveinputs", "style" => "color:black", "name" => "reserveinputs", "style" => "display:none"])}}</div>
        <div class="col-xs-12" style="margin-top: 15px;">
        {{Form::submit('Select Devices', ["class"=>"col-xs-12 col-md-3 col-md-offset-1 btn btn-lg btn-primary", "name" => "save", "id" => "save", "disabled" => "true"])}}
        <a class="col-xs-12 col-md-3 col-md-offset-1 btn btn-lg btn-primary" href="{{URL::route('system.editSystem', [$thisBldg->id, $sid])}}" style="text-align:center">Cancel</a>
        <button class="col-xs-12 col-md-3 col-md-offset-1 btn btn-lg btn-primary" type="button" data-toggle="modal" data-target="#templateModal" onclick="fillAlgTempName()">Save as Template</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" style="color:black; text-align:center">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalLabel">New Algorithm Template</h4>
      </div>
      <div class="modal-body row">
        <div style="text-align:left" class="col-xs-6 col-xs-offset-3">
          <label>Name:</label>
        {{Form::text('name',NULL,["id" => "name", "class" => "form-control"])}}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        {{Form::submit('Save Template', ["class"=>"btn btn-primary", "name" => "savetemp", "id" => "savetemp"])}}
      </div>
    </div>
  </div>
</div>
{{Form::close()}}

@stop
@stop
