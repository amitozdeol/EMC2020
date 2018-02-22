<?php $title="Edit Algorithm"; ?>

@extends('layouts.wrapper')

@section('content')


<head>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script>

    var templates = {
      @foreach($algorithm->toArray() as $alg)
         {!!$alg['id']!!}: {
          @foreach($alg as $key => $value)
            {!!$key!!}: '{!!$value!!}',
          @endforeach
         },
      @endforeach
    };
    var commandTypes = {
      @foreach($deviceTypes->toArray() as $type)
        {!!$type['command']!!}:{
          @foreach($type as $key => $value)
            {!!$key!!}: '{!!$value!!}',
            @endforeach
        },
        @endforeach
    };

    function updatePrimaryList(id, type){
          var devices = $("#inputs").val();
          if(devices === ""){
              $("#save").prop('disabled',false);
              $("#save").prop('value','Save');
          }
          var deviceCount = (devices.match(/,/g) || []).length;
          deviceCount+=1;

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
          var newReserve;
          if(existMultiple1 !== -1){
              newReserve = reserve.replace(multiple1,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){
              newReserve = reserve.replace(multiple2,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(end1 !== -1){
              newReserve = reserve.replace(end1,'');
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(end2 !== -1){
              newReserve = reserve.replace(end2,'');
              newReserve = newReserve + '.';
              $("#reserveinputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){
              newReserve = reserve.replace(single,'');
              $("#reserveinputs").prop('value',newReserve);
          }
    }

    function updateSecondaryList(id, type){


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
          var newReserve;
          if(existMultiple1 !== -1){

              newReserve = primary.replace(multiple1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){

              newReserve = primary.replace(multiple2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(end1 !== -1){
              newReserve = primary.replace(end1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(end2 !== -1){
              newReserve = primary.replace(end2,'');
              newReserve = newReserve + '.';
              $("#inputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){

              newReserve = primary.replace(single,'');
              $("#inputs").prop('value',newReserve);
          }

          var devices = $("#inputs").val();
          if(devices === ""){
              $("#save").prop('value','Select Primary Devices');
               $("#save").prop('disabled',true);
          }
          var deviceCount = (devices.match(/,/g) || []).length;
          deviceCount+=1;
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
          var newReserve;
          if(existMultiple1 !== -1){
              newReserve = primary.replace(multiple1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existMultiple2 !== -1){
              newReserve = primary.replace(multiple2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(end1 !== -1){
              newReserve = primary.replace(end1,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(end2 !== -1){
              newReserve = primary.replace(end2,'');
              $("#inputs").prop('value',newReserve);
          }
          else if(existSingle !== -1){
              newReserve = primary.replace(single,'');
              $("#inputs").prop('value',newReserve);
          }

          var reserve = $("#reserveinputs").val();
          var ressingle = '' + id + ' ' + type + '.';
          var resmultiple1 = ', '+ id + ' ' + type + '';
          var resmultiple2 = '' + id + ' ' + type + ', ';
          var resEnd1 = ', '+ id + ' ' + type + '.';
          var resEnd2 = '' + id + ' ' + type + '.';
          var resexistSingle = reserve.search(single);
          var resexistMultiple1 = reserve.search(multiple1);
          var resexistMultiple2 = reserve.search(multiple2);
          var resexistEnd1 = reserve.search(resEnd1);
          var resexistEnd2 = reserve.search(resEnd2);
          var resnewReserve;
          if(resexistMultiple1 !== -1){
              resnewReserve = reserve.replace(resmultiple1,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistMultiple2 !== -1){

              resnewReserve = reserve.replace(resmultiple2,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistSingle !== -1){

              resnewReserve = reserve.replace(ressingle,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistEnd1 !== -1){

              resnewReserve = reserve.replace(resEnd1,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }
          else if(resexistEnd1 !== -1){

              resnewReserve = reserve.replace(resEnd2,'');
              $("#reserveinputs").prop('value',resnewReserve);
          }

          var devices = $("#inputs").val();
          if(devices === ""){
              $("#save").prop('value','Select Primary Devices');
               $("#save").prop('disabled',true);
          }
          var deviceCount = (devices.match(/,/g) || []).length;
          deviceCount+=1;

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
      SensorGroup = '{!!str_replace(' ','',$type->function)!!}Sensors';
      $('#{!!str_replace(' ','',$type->function)!!}ButtonLabel').removeClass('active');

        if(templates[tempID].algorithm_name === '{!!$type->function!!}'){
          $("#{!!str_replace(' ','',$type->function)!!}Sensors").show();
          $('#{!!str_replace(' ','',$type->function)!!}ButtonLabel').addClass('active');

      }

      else if(!($("#{!!$type->function!!}Sensors").hasClass("hidden"))){
        $("#{!!str_replace(' ','',$type->function)!!}Sensors").hide();
      }
      @endforeach  }
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
        $("#algorithm_id").change(function(){
            tempFill(this.value);
            sensorTitle(this.value);
        });
        $("#default_state").change(function() {
          console.log(this.value);
          if(this.value == '2') {
            $("#default_toggle_duration").removeAttr('disabled');
            $("#default_toggle_percent_on").removeAttr('disabled');
            $("#toggleDurationFactor").removeAttr('disabled');
          } else {
            $("#default_toggle_duration").attr('disabled','disabled');
            $("#default_toggle_duration").val('');
            $("#toggleDurationFactor").attr('disabled','disabled');
            $("#default_toggle_percent_on").attr('disabled','disabled');
          }
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
                var commandType = '{!!$type->function!!}';
                var commandTypeFix = commandType.replace(' ','');
                var commandsString = '{!!$product->commands!!}';
                var commands = commandsString.split(',');
                var commandFound = commands.indexOf("" + {!!$type->command!!} + "");
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
        var commandType = '{!!$type->function!!}';
        var commandTypeFix = commandType.replace(' ','');
            $("#" + commandTypeFix + "Sensors").hide;
            $("#" + commandTypeFix + "Sensors").removeClass("hidden");
        @endforeach
        $("#VirtualDevicesSensors").hide();
        $("#VirtualDevicesSensors").removeClass("hidden");
        sensorTitle( document.getElementById("algorithm_id").value);
        $("#accordian").accordion({heightStyle: "content"});
        $("#logicmode").change(function(){
          if($(this).val() !== '0') {
            $("#votesLabel").html("Minimum Active Inputs");
          } else {
            $("#votesLabel").html("Votes");
          }
        });
        $('form').submit(function(){
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
</head>

<body>
{!!Form::open(['route'=>['algorithm.update', $thisBldg->id, $sid, $oldOutput->id], "method" => "put", "class"=>"js-supress-enter"])!!}
<div class="row">
  <div class="col-xs-12 col-md-6">
    <h3>Edit Algorithm</h3>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-md-6">
    <div class="col-xs-12">
      <label for="algorithm_name">
        <h4>
          Algorithm Name
        </h4>
      </label>
    </div>
    <br>
    <div class ="col-xs-12 col-md-8" style="margin-bottom: 5px">
      {!!Form::text('algorithm_name',$oldOutput->algorithm_name,["class" => "form-control input-lg  ", "style" => "color:black", "placeholder" => "Algorithm Name", "id" => "algorithm_name", "title" => "Individual name of this algorithm."])!!}
    </div>
  </div>
  <div class="col-xs-12 col-md-6" style="text-align: center; border-style: solid; border-color: white; padding-bottom: 10px;">
    <div class="col-xs-12">
      <h4><span  style="text-align: center;">
        Algorithm Output Device Info</span>
      </h4>
    </div>
    <div class="col-xs-12">
      Name:
      @if($algOutputDevice->name != '')
        <b>{!!$algOutputDevice->name!!}</b>
      @else
        No name found for device #{!!$algOutputDevice->id!!}
      @endif
      <br>
      Product Type: <b>{!!$algOutputProduct->name or 'Unknown Product'!!}</b><br>
      Zone:
      @if($algOutputDevice->zone != '0')
        @if(isset($algOutputDevice->zone))
          <b>{!!$zone_names[$algOutputDevice->zone] or 'Unknown Zone'!!}</b>
        @else
          Unknown Zone
        @endif
      @else
        none
      @endif
      <br>
      Physical Location:
      <b>{!!$algOutputDevice->physical_location or 'Unknown Location'!!}</b>
      <br>
      Comments:
      <b>{!!$algOutputDevice->comments or 'none'!!}</b>
    </div>
  </div>
</div>
<br>
<div class="row">
  <?php
    $i = 0;
    $commandArray = array();
    $commandIndex = 0;
  ?>
  <div class="form-group col-xs-12 col-md-6" style="text-align: center;">
    <label for="function_type">
      <h4>
        Template
      </h4>
    </label>
    {!!Form::select('algorithm_id', $algorithmTemps, $oldOutput->algorithm_id ,["class" => "form-control input-lg", "style" => "color:black; text-align: center","id" => "algorithm_id","name" => "algorithm_id", "title" => "Choose your basic algorithm template."])!!}
  </div>
  <div class="col-xs-12 col-md-6" style="text-align: center;">
    <label for="sensorDisplay">
      <h4>
        Sensor Types
      </h4>
    </label>
    <div class="col-xs-12 btn-group" style="text-align:center" data-toggle="buttons" id="sensorDisplay" title="Types of sensors available.">
      @foreach($deviceTypeFunctions as $type)
        @if($type->algorithm_active)
          @if(array_search($type->function,$commandArray, false) === false)
          <?php
            $commandArray[$commandIndex++] = $type->function;
          ?>
            <label class="col-xs-6 col-sm-3 col-md-3 btn btn-primary" id="{!!str_replace(' ','',$type->function)!!}ButtonLabel" disabled="true" for="{!!$type->function!!}Button">
              <input type="checkbox" id="{!!$type->function!!}Button" onChange="displaySensors(this.id,'{!!$type->function!!}')">
              {!!$type->function!!}
            </label>
            <?php
              $i++;
            ?>
            @if($i%4==0)
              </div>
              <br>
              <div class="col-xs-12 btn-group" style="text-align:center" data-toggle="buttons" id="sensorDisplay" title="Types of sensors available.">
            @endif
          @endif
        @endif
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
<div class="col-xs-12" style="text-align: center; margin-top: 10px;">
<label for="sensors">
  <h4 id="sensorTitle" title="Select a Sensor Type to see available devices">
    Sensors
  </h4>
</label>

<div class="col-xs-12 row-padding" id="sensors" style="min-height: 30px; width: 100%;>
  <?php
    $i = 0;
  ?>
  @foreach($deviceTypeFunctions as $types)

    @if($types->algorithm_active)

      <div id="{!!str_replace(' ','',$types->function)!!}Sensors" name="{!!$types->function!!}Sensors" class="hidden">
      @foreach($deviceTypes as $type)
        @if($types->function == $type->function)
        @foreach($devices as $sensor)
        <?php 
		$devString1 = (string)$sensor->id.' '.(string)$type->command.',';
	        $devString2 = (string)$sensor->id.' '.(string)$type->command.'.';
	?>
          @if($sensor->retired != 1 || (strpos($oldOutput->inputs,$devString1) !== FALSE || strpos($oldOutput->inputs,$devString2) !== FALSE || strpos($oldOutput->reserveinputs,$devString1) !== FALSE || strpos($oldOutput->reserveinputs,$devString2)))
            @foreach($productTypes as $product)
              @if($sensor->product_id == $product->product_id && $sensor->zone != 0)
		<?php
			$deviceTypeArray = explode(',',$product->commands);
                    if(in_array($type->command,$deviceTypeArray) && $sensor->id != 0){
                   echo
                    '<div class="col-xs-6 col-sm-4 row-padding" id="sensor'.$i.'" name="sensor'.$i.'" style="text-align:center;"><h4>';

                    if($sensor->name == ''){
                      echo 'Device '.$sensor->id.'</h4>';
                    } else{
                      echo $sensor->name.'</h4>';
                    }

                          echo '<h5>';
                          if($sensor->retired == 1){
                            echo '<span style="color:red">Retired Device </span>';
                          }
                          echo '<small>Zone: </small>'.$zone_names[$sensor->zone].'<br><small>ID: </small>'.$sensor->id.'<br><small>Sensor Type: </small>'.$device_types_names[$type->command].'</h5>
                          <div id="format" class="btn-group" data-toggle="buttons" style="margin-top: 1px" title="Sensor use state.">';

                              if(strpos($oldOutput->inputs,$devString1) !== FALSE || strpos($oldOutput->inputs,$devString2) !== FALSE){
                                  echo '<label class="btn btn-primary active" style="width:100%">
                                      <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="0">Primary
                                  </label>
                                  <label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="1">Secondary
                                  </label>
                                  <label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="2">Not Used
                                  </label>';
                              }
                              else if(strpos($oldOutput->reserveinputs,$devString1) !== FALSE || strpos($oldOutput->reserveinputs,$devString2) !== FALSE){
                                  echo '<label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="0">Primary
                                  </label>
                                  <label class="btn btn-primary active" style="width:100%">
                                      <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="1">Secondary
                                  </label>
                                  <label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="2">Not Used
                                  </label> ';
                              }
                              else{
                                  echo '<label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="0">Primary
                                  </label>
                                  <label class="btn btn-primary" style="width:100%">
                                      <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="1">Secondary
                                  </label>
                                  <label class="btn btn-primary active" style="width:100%">
                                      <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->id.', '.$type->command.');" autocomplete="off" value="2">Not Used
                                  </label>';
                              }
                          echo '</div>
                      </div>';
                }
                ?>
                @endif
              @endforeach
            @endif
          @endforeach
        @endif
      @endforeach
    </div>
    @endif
  @endforeach
        <div id="VirtualDevicesSensors" name="VirtualDevicesSensors" class="hidden">
    @foreach($outDevices as $sensor)
        <?php
           echo
            '<div class="col-xs-4 row-padding" id="sensor'.$i.'" name="sensor'.$i.'" style="text-align:center;">
              <h4>'.$sensor->algorithm_name.'</h4>
                  <h5><small>Zone: </small>'.$zone_names[$sensor->zone].'<br><small>ID: </small>'.$sensor->device_id.'<br><small>Sensor Type: </small>Virtual</h5>
                  <div id="format" class="btn-group" data-toggle="buttons" style="margin-top: 1px" title="Sensor use state.">';
                          $devString1 = (string)$sensor->device_id.' '.(string)$sensor->device_type.',';
                          $devString2 = (string)$sensor->device_id.' '.(string)$sensor->device_type.'.';
                            if(strpos($oldOutput->inputs,$devString1) !== FALSE || strpos($oldOutput->inputs,$devString2) !== FALSE){
                                echo
                                '<label class="btn btn-primary active" style="width:100%">
                                    <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="0">Primary
                                </label>
                                <label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="1">Secondary
                                </label>
                                <label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="2">Not Used
                                </label>';
                            }
                            else if(strpos($oldOutput->reserveinputs,$devString1) !== FALSE || strpos($oldOutput->reserveinputs,$devString2) !== FALSE){
                                echo
                                '<label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="0">Primary
                                </label>
                                <label class="btn btn-primary active" style="width:100%">
                                    <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="1">Secondary
                                </label>
                                <label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="2">Not Used
                                </label> ';
                            }
                            else{echo
                                '<label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="primary'.$i.'" onchange="updatePrimaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="0">Primary
                                </label>
                                <label class="btn btn-primary" style="width:100%">
                                    <input type="radio" name="sensor" id="seconday'.$i.'" onchange="updateSecondaryList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="1">Secondary
                                </label>
                                <label class="btn btn-primary active" style="width:100%">
                                    <input type="radio" name="sensor" id="notused'.$i++.'" onchange="clearList('.$sensor->device_id.', '.(string)$sensor->device_type.');" autocomplete="off" value="2">Not Used
                                </label>';
                            };
            echo '</div>
              </div>';?>
    @endforeach
  </div>
</div><br>

<h4 style="font-weight: bold;">Algorithm Parameters:</h4>
<div class="form-group">
<div class="row" style="text-align:center">
   <div class="col-xs-6 col-sm-4 col-md-2">Logic Mode<br>
        {!!Form::select('logicmode',['0' => 'AND', '1' => 'OR', '2' =>'NAND', '3' => 'NOR', '4' => 'EXOR'],$oldOutput->logicmode,["class" => "form-control", "style" => "color:black", "id" => "logicmode", "title" => "Choose the mode which the sensors will be compared."])!!}
   </div>
   <div class="col-xs-6 col-sm-4 col-md-2 "><text id="votesLabel">Votes</text><br>
        {!!Form::text('min_required_inputs',$oldOutput->min_required_inputs,["class" => "form-control", "style" => "color:black", "placeholder" => "# Votes", "id" => "min_required_inputs", "title" => "Minimum number of sensors required to activate."])!!}
   </div>
   <div class="col-xs-6 col-sm-4 col-md-2 ">Polarity Mode<br>
        {!!Form::select('polarity',['0' => 'Direct', '1' => 'Inverse'],$oldOutput->polarity,["class" => "form-control", "style" => "color:black", "id" => "polarity", "title" => "Choose direct or inverse polarity."])!!}
   </div>
   <div class="col-xs-6 col-sm-4 col-md-2 ">Active Season<br>
        {!!Form::select('season',['0' => 'Winter', '1' => 'Summer', '2' => 'Year-Round'],$oldOutput->season,["class" => "form-control", "style" => "color:black", "id" => "season", "title" => "Choose whether to observe or ignore the season."])!!}
    </div>
    <div class="col-xs-6 col-sm-4 col-md-2 ">Response<br>
        {!!Form::select('response',['0' => 'Off', '1' => 'On'], $oldOutput->response,["class" => "form-control", "style" => "color:black", "id" => "response", "title" => "Turn response on or off." ])!!}
    </div>
    <div class="col-xs-6 col-sm-4 col-md-2">Priority Event Algorithm<br>
        {!!Form::select('priority_events',['0' => 'Off', '1' => 'On'],$oldOutput->priority_events,["class" => "form-control", "style" => "color:black", "id" => "priority_events", "title" => "Choose whether this algorithm state should be monitored."])!!}
    </div>
</div>
<br>
<div class="row" style="text-align:center">
    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">On Delay<br>
      <div class="input-group">
            @if((($oldOutput->ondelay)%60 == 0) && (($oldOutput->ondelay)%3600 != 0) && ((int)$oldOutput->ondelay != 0))
            {!!Form::text('ondelay',($oldOutput->ondelay/60),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "ondelay", "title" => "Time delay before activation."])!!}
                <span class="input-group-btn">{!!Form::select('onDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'60',["class" => "btn", "style" => "color:black"])!!}</span>
            @elseif((($oldOutput->ondelay)%3600 == 0)  && ((int)$oldOutput->ondelay != 0))
            {!!Form::text('ondelay',($oldOutput->ondelay/3600),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "ondelay", "title" => "Time delay before activation."])!!}
                <span class="input-group-btn">{!!Form::select('onDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'3600',["class" => "btn", "style" => "color:black"])!!}</span>
            @else
            {!!Form::text('ondelay',$oldOutput->ondelay,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "ondelay", "title" => "Time delay before activation."])!!}
                <span class="input-group-btn">{!!Form::select('onDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])!!}</span>
            @endif
          </div>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">Off Delay<br>
        <div class="input-group">
            @if((($oldOutput->offdelay)%60 == 0) && (($oldOutput->offdelay)%3600 != 0) && ((int)$oldOutput->offdelay != 0))
                {!!Form::text('offdelay',($oldOutput->offdelay/60),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "offdelay", "title" => "Time delay before deactivation."])!!}
                <span class="input-group-btn">{!!Form::select('offDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'60',["class" => "btn", "style" => "color:black"])!!}</span>
            @elseif((($oldOutput->offdelay)%3600 == 0) && ((int)$oldOutput->ondelay != 0))
                {!!Form::text('offdelay',($oldOutput->offdelay/3600),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "offdelay", "title" => "Time delay before deactivation."])!!}
                <span class="input-group-btn">{!!Form::select('offDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'3600',["class" => "btn", "style" => "color:black"])!!}</span>
            @else
                {!!Form::text('offdelay',$oldOutput->offdelay,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "offdelay", "title" => "Time delay before deactivation."])!!}
                <span class="input-group-btn">{!!Form::select('offDelayFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])!!}</span>
            @endif
        </div>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">On Duration<br>
      <div class="input-group">
        @if((($oldOutput->duration)%60 == 0) && (($oldOutput->duration)%3600 != 0)  && ((int)$oldOutput->duration != 0))
            {!!Form::text('duration',($oldOutput->duration/60),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "duration", "title" => "Duration of time to remain active before automatically deactivating."])!!}
            <span class="input-group-btn">{!!Form::select('durationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'60',["class" => "btn", "style" => "color:black"])!!}</span>
        @elseif((($oldOutput->duration)%3600 == 0) && ((int)$oldOutput->ondelay != 0))
            {!!Form::text('duration',($oldOutput->duration/3600),["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "duration", "title" => "Duration of time to remain active before automatically deactivating."])!!}
            <span class="input-group-btn">{!!Form::select('durationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'3600',["class" => "btn", "style" => "color:black"])!!}</span>
        @else
            {!!Form::text('duration',$oldOutput->duration,["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "duration", "title" => "Duration of time to remain active before automatically deactivating."])!!}
            <span class="input-group-btn">{!!Form::select('durationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "style" => "color:black"])!!}</span>
        @endif
      </div>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3" style="margin-bottom: 15px;">Default State<br>
        {!!Form::select('default_state', [0 => 'Off', 1 => 'On', 2 => 'Toggle'], $oldOutput->default_state, ["class" => "form-control", "style" => "color:black", "placeholder" => "0", "id" => "default_state", "title" => "State set if algorithms are unable to calculate the state."])!!}
    </div>
    <?php $disabledToggle = "disabled"; ?>
      @if($oldOutput->default_state == 2)
        <?php $disabledToggle = null; ?>
      @endif
    <div class="col-xs-10 col-xs-offset-1 col-sm-4  col-sm-offset-0 col-md-3 form-group">Toggle Percent On<br>
      {!!Form::text('default_toggle_percent_on',$oldOutput->default_toggle_percent_on,["class" => "form-control", $disabledToggle, "style" => "color:black",  "placeholder" => "0", "id" => "default_toggle_percent_on", "title" => "Percentage of the Toggle Period time which the algorithm will choose to be ON/High, in the event of a defaulted state"])!!}
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-md-3 form-group">Toggle Duration<br>
      <div class="input-group">
        @if((($oldOutput->default_toggle_duration)%60 == 0) && (($oldOutput->default_toggle_duration)%3600 != 0)  && ((int)$oldOutput->default_toggle_duration != 0))
            {!!Form::text('default_toggle_duration',($oldOutput->default_toggle_duration/60),["class" => "form-control", "style" => "color:black", $disabledToggle, "placeholder" => "0", "id" => "default_toggle_duration", "title" => "The time of one complete toggling cycle of ON/High and OFF/Low states, in the event of a defaulted state"])!!}
            <span class="input-group-btn">{!!Form::select('toggleDurationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'60',["class" => "btn", "id" => "toggleDurationFactor", "style" => "color:black", $disabledToggle])!!}</span>
        @elseif((($oldOutput->default_toggle_duration)%3600 == 0) && ((int)$oldOutput->ondelay != 0))
            {!!Form::text('default_toggle_duration',($oldOutput->default_toggle_duration/3600),["class" => "form-control", "style" => "color:black", $disabledToggle, "placeholder" => "0", "id" => "default_toggle_duration", "title" => "The time of one complete toggling cycle of ON/High and OFF/Low states, in the event of a defaulted state"])!!}
            <span class="input-group-btn">{!!Form::select('toggleDurationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'3600',["class" => "btn", "id" => "toggleDurationFactor", "style" => "color:black", $disabledToggle])!!}</span>
        @else
            {!!Form::text('default_toggle_duration',$oldOutput->default_toggle_duration,["class" => "form-control", "style" => "color:black", $disabledToggle, "placeholder" => "0", "id" => "default_toggle_duration", "title" => "The time of one complete toggling cycle of ON/High and OFF/Low states, in the event of a defaulted state"])!!}
            <span class="input-group-btn">{!!Form::select('toggleDurationFactor',['1' => 'Seconds', '60' => 'Minutes', '3600' => 'Hours'],'1',["class" => "btn", "id" => "toggleDurationFactor", "style" => "color:black", $disabledToggle])!!}</span>
        @endif
      </div>
    </div>
</div>

    <div class="col-xs-12" style="margin-top: 50px;margin-bottom: 50px">
        <div style="margin-bottom: 5px">Description<br>{!!Form::textarea('description',$oldOutput->description,["class" => "form-control", "rows" => "2", "style" => "color:black; resize: vertical; max-height: 200px;", "height" => "1000px", "id" => "description","placeholder" => "Enter Description", "title" => "Brief description of the algorithms purpose or function."])!!}</div><br>
        <div>{!!Form::text('inputs',$oldOutput->inputs,["id" => "inputs", "style" => "color:black", "name" => "inputs", "style" => "display:none"])!!}</div>
        <div>{!!Form::text('reserveinputs',$oldOutput->reserveinputs,["id" => "reserveinputs", "style" => "color:black", "name" => "reserveinputs", "style" => "display:none"])!!}</div>
        @if(strtotime($oldOutput->updated_at) + $oldOutput->overridetime > $currentTime )
          {!!Form::submit('Save', ["class"=>"col-xs-12 col-md-4 col-md-offset-1 btn btn-lg btn-primary", "name" => "save", "id" => "save", "js-confirm" => "Are you sure you'd like to save this change? It will clear the web instruction currently controlled by this algorithm."])!!}
        @else
          {!!Form::submit('Save', ["class"=>"col-xs-12 col-md-4 col-md-offset-1 btn btn-lg btn-primary", "name" => "save", "id" => "save"])!!}
        @endif
            <a class="col-xs-12 col-md-4 col-md-offset-2 btn btn-lg btn-primary" href="{!!URL::route('system.editSystem', [$thisBldg->id, $sid])!!}" style="text-align:center">Cancel</a>
        {!!Form::close()!!}
    </div>

</div>

{!!Form::open(['route'=>['algorithm.destroy', $thisBldg->id, $sid, $oldOutput->id], "method" => "delete"])!!}
{!!Form::submit('Delete Algorithm', ["class"=>"col-xs-12 col-md-4 col-md-offset-4 btn btn-lg btn-danger js-confirm", "data-confirm" => "Are you sure you want to permanently remove this algorithm? Doing so will mean device control will be lost, pending setup of new device control algorithm.","style"=>"margin-bottom:40px;","title"=>"WARNING: Removes current control over output device!"])!!}
{!!Form::close()!!}


</body>
@stop
@stop
