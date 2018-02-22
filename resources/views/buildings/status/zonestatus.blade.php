<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/tgl.css', '/css/grid.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<style>
  /* remove padding for extra mobile space */
  #zone-main-div .panel-body{
    padding: 0;
  }
</style>
<?php
  $help_id='zonestatus';
  $TabTitle = "Click to view these devices";
  $AuthFlag=True;
  function StatusLogicEncode($Status, $Inhibited, $Retired) {
    // Retired overides all, then inhibit overrides status active, if none the undefined
    // Valid States 0 - uncommisioned, 4 - active 5,7 Retired, 6 -  inhibited
    $Bitwise_Status = $Retired + ($Inhibited << 1) + ($Status << 2);
    return $Bitwise_Status;
  }
  function StatusLogicDecode($Bitwise_Status) {
    switch ($Bitwise_Status) {
      case 4: $statusd[0] = "Active";
        $statusd[1] = "#1ccc94";
        break;
      case 6: $statusd[0] = "Inhibited";
        $statusd[1] = "#CCCC1C";
        break;
      case 5:
      case 7: $statusd[0] = "Retired";
        $statusd[1] = "#1ccc94";
        break;
      Default: $statusd[0] = "Not Defined";
        $statusd[1] = "#94cc1c";
    }
    return $statusd;
  }
  function DevStatusDecode($Status, $Inhibited, $Retired){
    return StatusLogicDecode(StatusLogicEncode($Status, $Inhibited, $Retired));
  }
?>
<?php $title="Zone Status"; ?>
@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="overflow: hidden; margin: 30px 10px;     position: relative;">
    <div id="zone-main-div">
      <?php
        if (isset($_GET['mode'])) {
          $mode = $_GET['mode'];
        } else {
          $mode = 'both';
        }
        $mode = "both";
      ?>
      <?php
        function zoneloopout($ZoneNames, $devicesout,$SysTemperatureFormat,$zone,$ZStatus,$ActiveAlarms,$AuthFlag,$timestampsArray, $currentTime, $devicesOutCurrent, $mappingOutputs,$AlarmCodes,$CurrentDeviceData)
          {
            $set_control_tour = ($zone == 0)?true:false;
            $ControlZoneprev = -1;
            $ZColor = 1;
            $ilen = 0;
            $k=1;
            $Controlgrid = false;
            $ControlZoneItem = 0;
            ?>
            @foreach ($ZoneNames as $zonearray)
            <?php
              $ZColor = !$ZColor;
              if ($ZColor) {
                $zbkcolor = "#29516D";
              } else {
                $zbkcolor = "#2B3C51";
              }
            ?>
            @if ($zonearray[2] == 1 )
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="width:100%; margin-top: 5px; color: white; background-color:<?php echo($zbkcolor);?>">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding block_emc_heading">
                    {!!$zonearray[1]!!}
                </div>
              </div>
              <div id="controlzone{!!$zonearray[0]!!}" class="gridlayout" data-columns style="background:#969696; float: left; width: 100%;">
              @foreach ($devicesout as $device)
                <?php $togglestate=1; ?>
                @if ($device->zone == $zone || $zonearray[0] == $device->zone)
                  <div class="@if($set_control_tour) control_zones_tour @endif control_item{!!$zone.$zonearray[0]!!} item">
                    <div class="row block_emc" style=" text-align:center; width: 100%; padding: 0px;">
                      <div class="col-xs-8 col-xs-offset-4 col-sm-8 col-sm-offset-4 " style="display: flex; align-items: center; justify-content: flex-end; margin-top: 10px;">
                        <div class="zoneSelector bypasstitle">
                          Set Bypass:&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="@if($set_control_tour) control_zones_tour @endif arrow-menu-icon arrow-menu-icon-path" data-toggle= "collapse" data-target=".{!!$device->id!!}"><!--TOGGLE ANIMATED BUTTON-->
                        <span></span>
                        <svg  viewbox="0 0 54 54">
                          <path d="M16.500,27.000 C16.500,27.000 24.939,27.000 38.500,27.000 C52.061,27.000 49.945,15.648 46.510,11.367 C41.928,5.656 34.891,2.000 27.000,2.000 C13.193,2.000 2.000,13.193 2.000,27.000 C2.000,40.807 13.193,52.000 27.000,52.000 C40.807,52.000 52.000,40.807 52.000,27.000 C52.000,13.000 40.837,2.000 27.000,2.000 "></path>
                        </svg>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding in {!!$device->id!!}" style="padding: 0px; background-color: rgb(62, 98, 123);"> <!--NAME / STATUS ITEMS-->
                      <div class="col-xs-8  row-padding" style="text-shadow: 0px 0px 2px #000020"><!--NAME-->
                        @if($device->name != "")
                          {!!$device->name!!}
                        @else
                          <small>
                            Device&nbsp;ID:
                          </small>
                          [{!!$device->id!!}]
                        @endif
                        <br>
                        <small>
                          <span title="Unique MAC Address">[{!!$device->mac_address!!}]</span>
                        </small>
                        <?php /*initialize*/ $disabled = ""; ?>
                        <br>
                        <small>
                          @if($device->physical_location != "")
                            {!!$device->physical_location !!}
                          @else
                            <span style="color: inherit;">
                              Location not found
                            </span>
                          @endif
                        </small>
                        @if( !array_key_exists( $device->id, $mappingOutputs ) )
                          <br>
                          <span style="color:red;" data-toggle="tooltip" data-placement="bottom" title="Contact your system administrator to gain control of this device.">
                            Missing Algorithm
                          </span>
                          <?php $disabled = "disabled"; ?>
                        @else
                          <?php $disabled = ""; ?>
                        @endif
                      </div>
                      <div class="col-xs-4 row-padding"><!--ALARM-->
                        <?php
                          $j=0;  $Alarmdsp=0;
                          foreach ($ActiveAlarms as $alarm)  {
                            if($alarm->device_id == $device->id){
                            $currentalarmws[$j]=$alarm->alarm_state;
                            if ($currentalarmws[$j] >  $Alarmdsp) {
                              $Alarmdsp=$currentalarmws[$j];
                              $Alarmindex=$alarm->id;
                            }
                            $j++;
                            }
                          }  // alarm foreach
                          //  $Alarmdsp = $device->alarm_state;
                          if ($Alarmdsp == 0) {
                            echo '<i class="fa fa-check-circle fa-3x" style="color: #00B000"></i>';
                          } elseif ($Alarmdsp == 1) {
                            echo '<i class="fa fa-exclamation-triangle fa-3x" style="color: #DDDD22"></i>';
                          } else {
                            echo '<i class="fa fa-exclamation-triangle fa-3x" style="color: #DD2222"></i>';
                          }
                        ?>
                      </div>
                      <div class="@if($set_control_tour) control_zones_tour @endif col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding"><!--STATUS / STATE / LAST CHANGE STATE-->
                        <ul class="list-group">
                          <li class="list-group-item " style="text-align: left; color: black;"><!--STATUS-->
                              Status:
                            <?php
                              $Statusd = DevStatusDecode($device->status,$device->inhibited,$device->retired);
                              echo("<font color='".$Statusd[1]."'><b>".$Statusd[0]."</b></font>");
                            ?>
                          </li>
                          <li class="list-group-item" style="text-align: left; color: black;"><!--STATE-->
                              State:
                            <?php
                              if($disabled == "disabled") {
                                echo 'N/A';
                              } else{
                                //     Determine Status of Sensor -->
                                $Statusd = DevStatusDecode($device->status, $device->inhibited, $device->retired);
                                $sysid=$device->system_id;
                                // one state for outputs
                                $Reporting=0;
                                $Reporttime="-";
                                $toggleid=0;
                                $toggleid=$device->id;
                                $togglestate=1;  // turns on if were off
                                $tstatedsp="On";
                                $OverRide[$k][1] = false;
                                $OverRide[$k][2] = true;
                                foreach ($CurrentDeviceData as $dstate) {
                                  if($dstate->id == $device->id){
                                    $Reporting=1;
                                    if (strcmp($Statusd[0],"Active") == 0) {
                                      if ($dstate->current_state==1) {
                                        echo("<font color='green'><b>ON</b></font>");
                                        $togglestate=0;  // turn off is was on
                                        $tstatedsp="Off";
                                      } else {
                                        echo("<font color='blue' ><b>OFF</b></font>");
                                      }
                                    } else {
                                      echo("<font color='blue'><b>OFF</b></font>");
                                    }
                                    $Reporttime=$dstate->datetime;
                                    if ($togglestate==1) {
                                      $OverRide[$k][1] = true;
                                      $OverRide[$k][2] = false;
                                    }  else {
                                      $OverRide[$k][2] = true;
                                      $OverRide[$k][1] = false;
                                    }
                                    $OverRide[$k][1] = true;
                                    $OverRide[$k][2] = false;
                                    // echo($OverRide[$k][1]) ;
                                  }
                                }
                                if ($Reporting==0) {
                                  echo("<font color='#1c94c4'><b>No Reports</b></font>");
                                }
                              }
                            ?>
                          </li>
                          <li class="list-group-item" style="text-align: left; color: black;"><!--LAST CHANGE STATE-->
                            Last State Change:
                            <?php
                              if($disabled != "disabled") {
                                $Reporttimecom=0;
                                $Reporttimedsp="0000-00-00 00:00:00";
                                if (isset($mappingOutputs[$device->id]->updated_at)) {
                                  $Reporttimecom = strtotime($mappingOutputs[$device->id]->updated_at);
                                  // $Reporttimedsp = $mappingOutputs[$device->id]->updated_at;
                                  if(isset( $timestampsArray[$device->id][$device->device_types_id])) {
                                    $Reporttimedsp = $timestampsArray[$device->id][$device->device_types_id];
                                  } else
                                    echo $device->id.' - '. $device->device_types_id;
                                }
                                ?>
                                <b>
                                  {!!$Reporttimedsp!!}
                                </b>
                                <?php
                                // echo($Reporttimedsp);
                                $i = 0;
                                $ilen = 0;
                              } else {
                                echo '<b>No Reports</b>';
                              }
                            ?>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="@if($set_control_tour) control_zones_tour @endif col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding collapse {!!$device->id!!}" style="background-color: #3E627B;text-shadow: 0px 0px 2px #000020;" ><!--BYPASS ITEMS-->
                      @if(!Request::is('EMC/*'))
                        {!! Form::open(array("role" => "form", "name"=>"instruction")) !!}
                        <div class="col-md-12 col-lg-12 row-padding"><!--BYPASS MODE-->
                          <div class="col-xs-5 col-sm-6 col-md-6 col-lg-8" style="text-align: right;">
                            <small>
                              Bypass Mode:
                            </small>
                          </div>
                          <div class="@if($set_control_tour) control_zones_tour @endif col-xs-7 col-sm-6 col-md-6 col-lg-4"  style="text-align: left;">
                              <input class="tgl tgl-skewed"
                                type="checkbox"
                                name="Override"
                                id="bypass_mode{!!$device->id!!}"
                                @if($togglestate) checked @endif {!!$disabled!!}>
                              <label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="bypass_mode{!!$device->id!!}"></label>
                          </div>
                        </div>
                        <div class="@if($set_control_tour) control_zones_tour @endif col-md-12 col-lg-12"><!--TITLE / DROPDOWN -->
                          <div class="col-sm-12 col-md-12">
                            <div class="col-xs-12 row-padding">
                              Bypass Time
                              <?php
                                $mpotime="15";
                                if(isset($mappingOutputs[$device->id])) {
                                  $mpotime=$mappingOutputs[$device->id]->overridetime;
                                }
                              ?>
                              <div>
                                {!! Form::select('Overridetime', array( '-1' => 'Reset', '5' => '5 Minutes', '15' => '15 Minutes','30' => '30 Minutes', '60' => '60 Minutes','90' => '90 Minutes', '120' => '120 Minutes'), $mpotime, array('class' => 'form-control', 'style' => 'color:black; height:34px; font-size:14px;', $disabled)) !!}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row row-padding"> <!--BUTTONS-->
                          <div class="@if($set_control_tour) control_zones_tour @endif col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-top: 10px; text-align: center;">
                            <button class="btn btn-primary" {!!$disabled!!} type="submit" name="Bypass" style="width: 100%; min-width: 90px;">
                              @if($disabled == "disabled")
                                No Bypass
                              @else
                                Bypass
                              @endif
                            </button>
                          </div>
                          <div class="@if($set_control_tour) control_zones_tour @endif col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-top: 10px;">
                          @if($disabled == "disabled")
                            <button class='btn btn-primary' {!!$disabled!!} type='submit' name='Toggle' style="width: 100%; min-width: 90px;">
                              No Toggle
                            </button>
                          @else
                            <button class='btn btn-primary' {!!$disabled!!} type='submit' name='Toggle' style="width: 100%; min-width: 90px;">
                              Toggle {!!$tstatedsp!!}
                            </button>
                            <input name="Togglestate" type="hidden" value="{!!$togglestate!!}">
                          @endif
                          </div>
                          <input name="device" type="hidden" value="{!!$device->id!!}">
                          <!-- If the product type of a device is not defined this will throw an error.
                          Add the product type on the product types page. -->
                          <input name="command" type="hidden" value="{!!$device->product_commands!!}">
                        </div>
                        {!!Form::close()!!}
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding"><!--INSTRUCTION TIME-->
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <small>
                              Bypass Time Remaining:
                            </small>
                            @if(!isset($mappingOutputs[$device->id]) )
                              No Instruction
                            @elseif( ( (strtotime($mappingOutputs[$device->id]->updated_at) + $mappingOutputs[$device->id]->overridetime*60 ) > $currentTime )  )
                              @if(isset($devicesOutCurrent[$device->id][$device->product_commands]->alarm_index))
                                @if($devicesOutCurrent[$device->id][$device->product_commands]->alarm_index == 40)
                                  Key Switch Bypass Off
                                @elseif($devicesOutCurrent[$device->id][$device->product_commands]->alarm_index == 41)
                                  Key Switch Bypass On
                                @else
                                <?php
                                  $difference = strtotime($mappingOutputs[$device->id]->updated_at) + $mappingOutputs[$device->id]->overridetime*60 - $currentTime;
                                ?>
                                <span class="days" value="{!!$difference!!}"></span>
                                <span class="hours" value="{!!$difference!!}"></span>
                                <span class="minutes" value="{!!$difference!!}"></span>
                                <span class="seconds" value="{!!$difference!!}"></span>
                                <span class="timedout" value="{!!$difference!!}"></span>
                                @endif
                              @endif
                            @else
                              No Instruction
                            @endif
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                  </div>
                @endif
                <?php $set_control_tour = false; ?>
              @endforeach
              </div>
            @endif
            <!-- for grid resizing -->
            <script type="text/javascript">
                if (document.querySelector('.control_item{!!$zone.$zonearray[0]!!}') !== null) {
                  var TotControlItem = document.querySelectorAll('.control_item{!!$zone.$zonearray[0]!!}').length;
                  var grid = document.getElementById("controlzone{!!$zonearray[0]!!}");
                  switch(TotControlItem) {
                    case 1:
                    case 2:
                    grid.setAttribute("data-content-small", "1 .column.size-1of1");
                    grid.setAttribute("data-content-med", "1 .column.size-1of1");
                    grid.setAttribute("data-content-large", "1 .column.size-1of1");
                        break;
                    default:
                    grid.setAttribute("data-content-small", "1 .column");
                    grid.setAttribute("data-content-med", "2 .column.size-1of2");
                    grid.setAttribute("data-content-large", "3 .column.size-1of3");
                  }
                }
            </script>
          @endforeach
        <?php } ?> <!-- end of zoneloopout function -->
        <?php
          // determine if zone information is available
          $ZStatus=0;
          $numOutZones = 1; /*One for zone 0*/
          $zoneOutArray;
          $i=1;
          $zoneOutArray[0] = 0;
          foreach  ($devicesout as $device)  { /*check all output devices*/
            $ZStatus=1;
            if(FALSE === array_search($device->zone, $zoneOutArray, TRUE)){
              $zoneOutArray[$i]=$device->zone;
              $i++;
              $numOutZones++;
            }
          }
        ?>
        <ul id="myTab" class="myTab nav nav-tabs">
          <li class="active"><a class="control_zones_tour" data-toggle="tab" data-parent="#collapse-container" href="#system-device-out" >&nbsp;&nbsp;CONTROL&nbsp;&nbsp;ZONES</a></li>
          <li><a class="sensor_zones_tour" data-toggle="tab" data-parent="#collapse-container" href="#system-device-in">&nbsp;&nbsp;SENSOR&nbsp;&nbsp;ZONES</a></li>
        </ul>
        <div id="myTabContent" class="myTabContent tab-content row">
          <!-- CONTROL OUTPUTS CHECK -->
          @if ($mode=="output" or $mode=="both")
            <!-- <div id="control-zones" class="col-xs-12 row-detail slim-emc-row-detail" data-toggle="collapse" data-parent="#collapse-container" href="#system-device-out" style="margin-top:40px;">
              &nbsp;&nbsp;CONTROL&nbsp;&nbsp;ZONES
            </div> -->
            <div id="system-device-out" class="tab-pane fade in active" style="margin: 0px;">
              @if ($ZStatus!=1) <!--if no output devs found for this system-->
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                  <div class="row-padding transparent_blue_white border_blue_white" style="padding:20px 15px;">
                    None&nbsp;Defined:
                    <p><small><small>
                      Contact your system administrator for information on setting up output devices for you building.
                    </small></small></p>
                  </div>
                  <div class="col-xs-3 row-padding" style="text-align: center">
                  </div>
                </div>
              @endif
              <!---\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
                    CONTROL OUTPUTS (Non-Retired)
                  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->
              @if ($ZStatus==1)
                <div class="tabs">
                  <div class="col-xs-12 col-sm-6 col-sm-offset-6 zoneSelector emc-tabs row-padding" >
                    <div class="col-xs-12 col-sm-8" style="color: white;">Select&nbsp;a&nbsp;zone&nbsp;:</div>
                    <div class="col-xs-12 col-sm-4">
                      <select onchange="ControlZoneOptions(this)" class="control_zones_tour form-control" style="height:34px; font-size:14px;">
                        <option value="0" data-toggle="collapse" data-parent="#system-device-out" title="{!!$TabTitle!!}"><!--CONTROL-TAB-0-->
                          &nbsp;All&nbsp;Zones
                        </option>
                        <!--Spawn more tabs according to number of zones-->
                        @for ($i = 0; $i < sizeof($ZoneNames); $i++)
                          <option value="{!!$ZoneNames[$i][0]!!}" data-toggle="collapse" data-parent="#system-device-out" title="{!!$TabTitle!!}"><!--CONTROL-TAB-->
                            &nbsp;{!!$ZoneNames[$i][1]!!}
                          </option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="row">
                  </div>
                  <!-- tab details -->
                  <!-- for "All Zones" -->
                  <div id="tabsout-0" class="container-fluid collapse in" style="padding:0px;"><!--TABSOUT-->
                    <div id="out-0" class="block_emc_see_through"><!--OUT-->
                      {!!zoneloopout($ZoneNames, $devicesout,$SysTemperatureFormat,0,$ZStatus,$ActiveAlarms,$AuthFlag,$timestampsArray, $currentTime, $devicesOutCurrent, $mappingOutputs,$AlarmCodes,$CurrentDeviceData)!!}
                    </div>
                  </div>
                  <!-- for "other Zones" -->
                  @for ($k = 0; $k < sizeof($ZoneNames); $k++)
                    <div id="tabsout-{!!$ZoneNames[$k][0]!!}" class="container-fluid collapse" style="padding:0px;"><!--TABSOUT-->
                      <div id="out-{!!$ZoneNames[$k][0]!!}" class="block_emc_see_through"><!--OUT-->
                        {!!zoneloopout($ZoneNames, $devicesout,$SysTemperatureFormat,$ZoneNames[$k][0],$ZStatus,$ActiveAlarms,$AuthFlag,$timestampsArray, $currentTime, $devicesOutCurrent, $mappingOutputs,$AlarmCodes,$CurrentDeviceData)!!}
                      </div>
                    </div>
                  @endfor
                  <script type="text/javascript">
                    function ControlZoneOptions(s){
                      if (s[s.selectedIndex].value!=0) {
                        $("#tabsout-0:visible").collapse('hide');
                      }
                      @for($zo=0; $zo < count($ZoneNames); $zo++)
                        if({!!$ZoneNames[$zo][0]!!} != s[s.selectedIndex].value){
                            /*hide all zones, except the one selected*/
                            $("#tabsout-{!!$ZoneNames[$zo][0]!!}:visible").collapse('hide');
                        }
                      @endfor
                      /*toggle the selected zone*/
                      $("#tabsout-"+s[s.selectedIndex].value).collapse('toggle');
                    }
                  </script>
                </div>
              @endif
              <!-- zone controls loop -->
            </div>
            <!-- end outputs -->
          @endif

          <!-- Begin Inputs SENSOR ZONES -->
          <?php    // determine is there is input zones defined  and number devices must be ordered by zone
            $ZStatus=0;
            $numInZones = 1; /*One for zone 0*/
            $zoneInArray;
            $i=1;
            $zoneInArray[0] = 0;
            foreach ($InputDevices as $device) {
              $ZStatus = 1;
              if(FALSE === array_search($device['zone'], $zoneInArray, TRUE)){
                $zoneInArray[$i] = $device['zone'];
                $i++;
                $numInZones++;
              }
            }
          ?>
          <?php
            function zoneloopin($ZoneNames, $SysTemperatureFormat,$zone,$ZStatus,$ActiveAlarms,$buildingID,$systemID,$AlarmCodes,$CurrentDeviceData,$InputDevices)  {
              $set_sensor_tour = ($zone == 0)?true:false;
              $SensorZoneprev = -1;
              $Sensorgrid = false;
              $SensorZoneItem = 0;
            ?>
            <script type="text/javascript">
              function setReportFunction(functionName){
                if(confirm("Load Past Reports in New Window?")){
                  window.open("{!!URL::route('reports.index', [$buildingID, $systemID])!!}");
                }
              }
            </script>
            @foreach ($ZoneNames as $zonearray)
              @if ($zonearray[0] == $zone || $zone == 0)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="width:100%; margin-top: 5px; color: white; background-color:#2B3C51;">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding block_emc_heading">
                      {!!$zonearray[1]!!}
                  </div>
                </div>
                <div id="sensorzone{!!$zonearray[0]!!}" class="gridlayout" data-columns style="background:#969696; float: left; width: 100%;">
                @foreach ($InputDevices as $device)
                  @if ($device['zone'] == $zone || ($zone == 0 && $zonearray[0] == $device['zone']))
                    <div class="@if($set_sensor_tour) sensor_zones_tour @endif sensor_item{!!$zone.$zonearray[0]!!} item">
                      <div class="row block_emc" style="width: 100%;">
                      <div class="col-xs-12"><!--NAME / ALARM-->
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 row-padding" style="text-shadow: 0px 0px 2px #000020"><!--NAME / LOCATION / STATUS / OFFSET-->
                          @if($device['name'] != "")
                            <span title="ID: {!!$device['id']!!}"> {!!$device['name']!!}</span>
                            <br>
                          @else
                            <small>
                              Device&nbsp;ID:
                            </small>
                            [{!!$device['id']!!}]
                            <br>
                          @endif
                          <small>
                            <span title="Unique MAC Address">[{!!$device['mac_address']!!}]</span>
                          </small><br>
                          <small>
                            @if($device['physical_location'] != "")
                              {!!$device['physical_location'] !!}
                            @else
                              Location not found
                            @endif
                          </small>
                          <br>
                          <small>
                            Status:
                          </small>
                          <?php
                            $Statusd = DevStatusDecode($device['status'], $device['inhibited'], $device['retired']);
                            echo("<font color=" . $Statusd[1] . "'><b>" . $Statusd[0] . "</b></font>");
                          ?>
                        </div>
                          <?php /*ALARM LOGIC*/
                            // look for active alarm state from alarmlog
                            $code_found = False;
                            $Alarmindex = 0;
                            $Alarm_type = 0;
                            $AAkey = 0;
                            $j=0;
                            $Alarmdsp=0;
                            foreach ($ActiveAlarms as $key => $alarm)  {
                              if($alarm->device_id == $device['id']){
                                $currentalarmws[$j]=$alarm->alarm_state;
                                if ($currentalarmws[$j] >  $Alarmdsp) {
                                  $Alarmdsp=$currentalarmws[$j];
                                  $Alarmindex=$alarm->id;
                                  $Alarm_type = $alarm->alarm_code_id;
                                }
                                $j++;
                              }
                            }  // alarm foreach
                            if ($Alarmdsp == 0) {
                              $alarm_image = "fa-check-circle";
                              $Alarmcolor="#00B000";
                            } elseif ($Alarmdsp == 1) {
                              $alarm_image = "fa-exclamation-triangle";
                              $Alarmcolor="#DDDD22";
                            } else {
                              $alarm_image = "fa-exclamation-triangle";
                              $Alarmcolor = "#DD2222";
                            }
                            /*Provide Alarm Reason*/
                            $alarm_code_description = "";
                            if ($Alarmdsp == 0) {
                              $alarm_code_description = "";
                            } else {
                              $code_found = False;
                            }
                            foreach ($AlarmCodes as $codes) {
                              if($codes->id == $Alarm_type){
                                $alarm_code_description = $codes->description;
                                $destination = url('building/'.(int)$buildingID.'/system/'.$systemID.'/alarmstatus');
                                $code_found = True;
                              }
                            }
                            if($code_found == False){
                              $alarm_code_description = "";
                            }
                            
                          ?>
                        @if($code_found == True)<!--ALARM-->
                          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 row-padding" style="padding-top: 10px; " data-toggle="tooltip" data-placement="left" title="{!!$alarm_code_description!!}">
                            @if(Auth::check())
                            <a href="{!!$destination!!}" class="img-responsive">
                            @endif
                              <i class="fa {!!$alarm_image!!} fa-3x" style="color: <?php echo $Alarmcolor;?>"></i>
                            @if(Auth::check())
                            </a>
                            @endif
                        @else
                          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 row-padding" style="padding-top: 10px;" data-toggle="tooltip" data-placement="left" title="{!!$alarm_code_description!!}">
                            <i class="fa {!!$alarm_image!!} fa-3x" style="color: <?php echo $Alarmcolor;?>"></i>
                        @endif
                        </div>
                      </div>
                      <!--INPUT VALUES FOR EACH DEVICE TYPE ASSOCIATED WITH THE DEVICE-->
                      <div class="@if($set_sensor_tour) sensor_zones_tour @endif col-xs-12" style="padding:0px;">
                        <?php
                          //     Determine Status of Sensor -->
                          $Statusd = DevStatusDecode($device['status'], $device['inhibited'], $device['retired']);
                          // for wireless and bacnet devices need to loop through all possible commands
                          // to determine status and latest value of each command
                          // output variables in an array for each commands and the loop thru to display
                          // first find the applible command types
                          //$commandvec = $device['product_commands'] . ",";
                          $i = 0;
                          $ilen = 0;
                          $Reporttimews[] = "-";
                          $ReportIntervalws[] = 5;          // defalut value
                          $unitsws[] = "";
                          $currentvaluews[] = "";
                          $functionws[] = "";
                          $functionid[] = "";
                          $currentalarmws[] = "";
                          $Reporting = 0;
                          $inhibitflag=False;
                          foreach ($device['command'] as $command) {
                            $Reporting = 1;
                            $grayflag=false;
                            if($command['current_state'] == 1){
                              $PState[$i] = "<small>Setpoint: </small><font color='#1ccc94'><b>Exceeded</b></font><BR>";
                            }else{
                              $PState[$i] = "<small>Setpoint: </small><font color='#1c94c4'><b>Not Exceeded</b></font><BR>";
                            }
                            $Reporttimews[$i] = $command['last_report'];
                            if (($SysTemperatureFormat)=="F" && $command['function']=='Temperature') {
                              $currentvaluews[$i]= ConvFunc::convertCelciusToFarenheit($command['current_value']);
                            } else {
                              $currentvaluews[$i] = number_format($command['current_value'], 1);
                            }
                            $currentalarmws[$i] = $command['alarm_code'];
                            $inhibitflag=($device['inhibited']==0)?False:True;
                            $T=$command['units'];
                            $functionws[$i] = $command['function'];
                            $functionid[$i] = $command['id'];
                            $unitsws[$i] = ConvFunc::unitconv($T,$SysTemperatureFormat);
                            $i++;
                          }
                          $ilen = $i;
                          
                          if ($Reporting == 0) { /*ERROR */
                        ?>
                            <div class='col-xs-6 col-sm-4 col-md-4 col-lg-3 row-padding text-capitalize border_blue_white' style="text-align: center; min-height:150px">
                              <font color='grey'>
                                <b>No Reports Found</b>
                              </font>
                            </div>
                            <?php
                            $grayflag=true;
                          }else { /* "ACTIVE" */
                            // for agiven device see if active not reporting alarm #10
                            // if so set gray flag to override value color
                            foreach ($ActiveAlarms as $alarm) {
                              if($alarm->alarm_code_id == 11 && $alarm->device_id == $device['id']){
                                $grayflag=true;
                              }
                            }
                            for ($j = 0; $j < $ilen; $j++) {
                              if ($inhibitflag){
                                $grayflag=True;
                              }
                              // now check last report time if alarms are not set or cleared
                              if (!$grayflag)  {
                                $start_date = new DateTime($Reporttimews[$j]);
                                $now=date("Y-m-d H:i:s");
                                $since_start = $start_date->diff(new DateTime($now));
                                $minutes = $since_start->days * 24 * 60;
                                $minutes += $since_start->h * 60;
                                $minutes += $since_start->i;
                              }
                              $statecolor="#FFFFFF";
                              if ($inhibitflag) {
                                $statecolor="#666666";
                              } else {
                                switch ($currentalarmws[$j]) {
                                  case  0 : $statecolor="#BAEFDE";/*green*/
                                    break;
                                  case  1 : $statecolor="#FEFF7F";/*yellow*/
                                    break;
                                  case  2 : $statecolor="#FF4C4C";/*red*/
                                }
                              }
                              /*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*
                                    DISPLAY RELEVANT INPUTS
                              *-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*-.-*/
                            ?>
                              <div class='col-xs-6 row-padding text-capitalize border_blue_white'>
                                <a class="path-link" style="text-decoration:none; color:white; text-shadow: 0px 0px 2px #000020;" title="Go to {!!$functionws[$j]!!} Reports" onclick="setReportFunction('{!!$functionws[$j]!!}')">
                                  @if($functionws[$j]=="Digital")
                                    {!!$functionws[$j]!!}
                                    <BR>
                                    <span color='{!!$statecolor!!}'>
                                      <b>{!!$currentvaluews[$j]!!}</b><br>
                                      {!!$PState[$j]!!}
                                    </span>
                                    <BR>
                                  @else
                                    {!!$functionws[$j]!!}
                                    <BR>
                                    <span color='{!!$statecolor!!}'>
                                      <b>{!!$currentvaluews[$j]!!}</b>
                                    </span>
                                    {!!$unitsws[$j]!!}
                                    <BR>
                                    {!!$PState[$j]!!}
                                  @endif
                                </a>
                              </div>
                              <?php
                              $functionws[$j] = "";
                              $currentvaluews[$j] = "";
                              $unitsws[$j] = "";
                            }
                          }  // else
                        ?>
                      </div>
                      <div class="@if($set_sensor_tour) sensor_zones_tour @endif col-xs-12 col-sm-12 col-md-12 row-padding" style="padding-left: 30px; padding-top: 20px; text-align:center; text-shadow: 0px 0px 2px #000020;"><!--LAST REPORT-->
                          <small>
                            Last Report:
                          </small>
                          <?php
                            $Reporttimecom=0;
                            $Reporttimedsp="0000&#8209;00&#8209;00 00:00:00";
                            for ($j = 0; $j < $ilen; $j++) {
                              if (strtotime($Reporttimews[$j]) >= $Reporttimecom) {
                                $Reporttimecom = strtotime($Reporttimews[$j]);
                                $Reporttimedsp = $Reporttimews[$j];
                                $Reporttimedsp = str_replace("-", "&#8209;", $Reporttimedsp);/*replace hyphens with 'non-breaking hyphen'*/
                              }
                            }
                            echo($Reporttimedsp);
                            $i = 0;
                            $ilen = 0;
                          ?>
                      </div>
                    </div>
                    </div>
                  @endif
                @endforeach
                </div>
              @endif
              <!-- for grid resizing -->
              <script type="text/javascript">
                if (document.querySelector('.sensor_item{!!$zone.$zonearray[0]!!}') !== null) {
                  var TotSensorItem = document.querySelectorAll('.sensor_item{!!$zone.$zonearray[0]!!}').length;
                  var grid = document.getElementById("sensorzone{!!$zonearray[0]!!}");
                  switch(TotSensorItem) {
                    case 1:
                    case 2:
                    grid.setAttribute("data-content-small", "1 .column.size-1of1");
                    grid.setAttribute("data-content-med", "1 .column.size-1of1");
                    grid.setAttribute("data-content-large", "1 .column.size-1of1");
                        break;
                    default:
                    grid.setAttribute("data-content-small", "1 .column");
                    grid.setAttribute("data-content-med", "2 .column.size-1of2");
                    grid.setAttribute("data-content-large", "3 .column.size-1of3");
                  }
                }
              </script>
            @endforeach
            <?php
              $ss = HTML::style("css/grid.css");
              echo $ss; //load grid.css file after the javascript
            ?>
            <?php
            }  // end input functions
          ?>
          @if ($mode=="input" or $mode=="both")
            <!-- SENSOR INPUT CHECKS  -->
            <!-- <div id="sensor-zones" class="tab-pane fade"  data-toggle="collapse" data-parent="#collapse-container" href="#system-device-in">
              &nbsp;&nbsp;SENSORS&nbsp;&nbsp;ZONES
            </div> -->
            <div id="system-device-in" class="tab-content tab-pane fade">
              @if ($ZStatus!=1)
                <div class="row block_emc">
                  <div class="col-xs-9 row-padding">
                    None Defined:
                    <p><small>
                        Contact your system administrator for information on setting up sensors for your building.
                    </small></p>
                  </div>
                  <div class="col-xs-3 row-padding" style="text-align: center">
                  </div>
                </div>
              @endif
              <!---\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
                SENSOR INPUTS (Non-Retired)
              \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\-->
              @if ($ZStatus==1)
                <div class="tabs">
                  <div class="col-xs-12 col-sm-6 col-sm-offset-6 zoneSelector emc-tabs row-padding" >
                    <div class="col-xs-12 col-sm-8" style="color: white;">Select&nbsp;a&nbsp;zone&nbsp;:</div>
                    <div class="col-xs-12 col-sm-4">
                      <select onchange="SensorZoneOptions(this)" class="sensor_zones_tour form-control" style="height:34px; font-size:14px;">
                        <option value="0" data-toggle="collapse" data-parent="#system-device-in" title="{!!$TabTitle!!}"><!--SENSOR-TABS-0-->
                          &nbsp;All&nbsp;Zones
                        </option>
                        <!--Spawn more tabs according to number of zones-->
                        @for ($i = 0; $i < sizeof($ZoneNames); $i++)
                          <option value="{!!$ZoneNames[$i][0]!!}" data-toggle="collapse" data-parent="#system-device-in" title="{!!$TabTitle!!}"><!--SENSOR-TABS-->
                            &nbsp;{!!$ZoneNames[$i][1]!!}
                          </option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="row"></div>
                  <!-- tab details -->
                  <!-- for "All Zones" -->
                  <div id="tabsin-0" class="container-fluid collapse in" style="padding:0px;"><!--TABSIN-->
                    <div id="in-0" class="block_emc_see_through"><!--IN-->
                      {!!zoneloopin($ZoneNames,$SysTemperatureFormat,0,$ZStatus,$ActiveAlarms, $Building,$System,$AlarmCodes,$CurrentDeviceData,$InputDevices)!!}
                    </div>
                  </div>
                @for ($k = 0; $k < sizeof($ZoneNames); $k++)
                  <div id="tabsin-{!!$ZoneNames[$k][0]!!}" class="container-fluid collapse" style="padding:0px;"><!--TABSIN-->
                    <div id="in-{!!$ZoneNames[$k][0]!!}" class="block_emc_see_through"><!--IN-->
                      {!!zoneloopin($ZoneNames,$SysTemperatureFormat,$ZoneNames[$k][0],$ZStatus,$ActiveAlarms, $Building,$System,$AlarmCodes,$CurrentDeviceData,$InputDevices)!!}
                    </div>
                  </div>
                @endfor
                  <script type="text/javascript">
                  /*show "all zones" by default*/
                    function SensorZoneOptions(s){
                      if (s[s.selectedIndex].value!=0) {
                        $("#tabsin-0:visible").collapse('hide');
                      }
                      @for($zo=0; $zo < count($ZoneNames); $zo++)
                        if({!!$ZoneNames[$zo][0]!!} != s[s.selectedIndex].value){
                          /*hide all zones, except the one selected*/
                          $("#tabsin-{!!$ZoneNames[$zo][0]!!}:visible").collapse('hide');
                        }
                      @endfor
                      /*toggle the selected zone*/
                      $("#tabsin-"+s[s.selectedIndex].value).collapse('toggle');
                    }
                  </script>
                </div>
              @endif
              <!-- zone sensors loop -->
            </div>
          @endif   <!-- end input -->
        </div>
    </div>
  </main>
  <!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
    <?
      //Cache control
      //Add last modified date of a file to the URL, as get parameter.
      $import_scripts = ['/js/salvattore.min.js', '/js/bootstrap-tabcollapse.js'];
      foreach ($import_scripts as $value) {
        $filename = public_path().$value;
        if (file_exists($filename)) {
            $appendDate = substr($value."?v=".filemtime($filename), 1);
            echo HTML::script($appendDate);
        }
      }
    ?>
  <script language"javascript" type"text/javascript">
    $(document).ready(function(){
      if ($('ul').hasClass('ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all'))
        $('ul').removeClass('ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all');
      $('#myTab').tabCollapse();
      $('.arrow-menu-icon').click(function(e){
        e.preventDefault();
        $(this).toggleClass('isOpen');
        $(this).prev().toggle(function() {
              $(this).html('Set Bypass:&nbsp;&nbsp;&nbsp;');
          });
      });
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="control_zones_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Control Zones\
        </a>\
        <a href="javascript:void(0);" class="sensor_zones_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Sensor Zones\
        </a>'
      );
      //restart salvattore for grid resizing.
      window.salvattore.rescanMediaQueries();
    });
  </script>

@stop