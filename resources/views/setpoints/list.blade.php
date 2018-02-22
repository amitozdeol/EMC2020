
@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <?php $title="Setpoint"; ?>
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_css = ['/css/building/mytabs.css', '/css/tgl.css'];    //add file name
    foreach ($import_css as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::style($appendDate);
      }
    }
  ?>
  {{HTML::style('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css')}}
  <style>
      /*--------------ON/OFF Toggle------------------*/
      .tgl-skewed + .tgl-btn {
        background: #FF5722;
        font-size: 1em;
      }
      .setpoint-dropdown{
        height: 34px !important;
        font-size: 17px;
      }
      .setpoint-button{
        font-size: 15px;
        padding: 5px;
        width: 100%;
      }
    /*~~~~~~~~~~~~~~---ON/OFF Toggle-~~~~~~~~~~~~~~~~~~~~~*/
    #pageload{
      display: none;
    }
    /* remove padding for extra mobile space */
    #setpoint-main-div .panel-body{
      padding: 0;
    }
    .mb-5{
      margin-bottom: 5px;
    }
    /*--------------------------------------------------------------
    Button - TOOLBAR
    --------------------------------------------------------------*/
    .toolbar{
      margin-top: -5px;
    }
    .toolbar li {
      float:right;
      list-style: none;
      width: 25%;
      background: #192535;
      margin-top: 10px;
      text-align: center;
      -webkit-box-shadow: 2px 2px 7px 0px rgba(0,0,0,0.75);
      -moz-box-shadow: 2px 2px 7px 0px rgba(0,0,0,0.75);
      box-shadow: 2px 2px 7px 0px rgba(0,0,0,0.75);
    }
    .toolbar .globalbutton{
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
    }
    .toolbar .zonalbutton{
      border-top-left-radius: 8px;
      border-bottom-left-radius: 8px;
      border-right: 2px solid #000;
    }
    .GZButton{
      color: rgba(255, 255, 255, 0.77);
    }
    .GZButton:hover{
      color: white;
    }
    .form-control[readonly]{
      background-color: white;
    }
    @media screen and (max-width: 1080px){
      .setpoint-setting .fa, .zonal-setpoint .fa{
        font-size: 24px;
        padding-top: 3px;
      }
      .zonal-setpoint .fa{
        font-size: 24px;
        padding-top: 3px;
      }
      .toolbar li {
        width: 50px;
        height: 35px;
      }
      .toolbar{
        margin-top: -2px;
      }
    }
    @media screen and (min-width: 500px){
      .tgl + .tgl-btn {
        height: 1.3em;
      }
      .tgl-skewed + .tgl-btn:after, .tgl-skewed + .tgl-btn:before {
        line-height: 1.3em;
      }
    }
  </style>
  <main id="page-content-wrapper" role="main" style="margin: 30px 10px; position: relative;">
    <div id="setpoint-main-div">
  
      <?php
        $twelveHourSeconds = 43200;
      ?>
      <div id="pageload">
        @if(in_array(Auth::user()->auth_role,[7,8]))
          @if($remapRequired == 'YES')
            <div class="container">
              <div class="alert alert-warning alert-dismissible" role="alert" style="text-align:center;">
                There are recently added devices, you should remap your device setpoints!
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">
                    &times;
                  </span>
                  <span class="sr-only">
                    Close
                  </span>
                </button>
              </div>
            </div>
          @endif<!--remap promt-->
        @endif
        <ul id="myTab" class="myTab nav nav-tabs" style="font-size: 15px;">
          <?php $set_setpoint_tab_tour = true; ?>
          @foreach($functionsArray as $key => $deviceFunction)
            <li class="<?php if($key == 0)echo "active";?>">
              <a class="@if($set_setpoint_tab_tour) setpoint_tab_tour @endif" href="#{{str_replace(' ','',$deviceFunction)}}group" id="{{str_replace(' ','',$deviceFunction)}}title" name="{{str_replace(' ','',$deviceFunction)}}title" data-toggle="tab">
                {{$deviceFunction}} Sensors
              </a>
            </li>
            <?php $set_setpoint_tab_tour = false; ?>
          @endforeach
        </ul>
        <?php $i = 0; ?>
        <div id="myTabContent" class="myTabContent tab-content row">
        <?php
          $set_global_setpoint_tour = true;
          $set_zonal_setpoint_tour = true;
        ?>
        @foreach($functionsArray as $deviceFunction)
          @if($i == 0)
          <div class="transparent_blue_white tab-pane fade in active" id="{{str_replace(' ','',$deviceFunction)}}group" name="{{str_replace(' ','',$deviceFunction)}}group" style="text-shadow: none;">
          @else
          <div class="transparent_blue_white tab-pane fade" id="{{str_replace(' ','',$deviceFunction)}}group" name="{{str_replace(' ','',$deviceFunction)}}group" style="text-shadow: none;">
          @endif
          <!-- top row = global edit button and select a zone -->
          <?php $set_single_setpoint_tour = true; ?>
          <div class="col-xs-12  row-padding" style="background: #3a506d; padding-left: 5px; padding-right: 5px; border-radius: 10px;">
            <div class="col-xs-6 col-sm-4 zoneSelector " style="padding-top:9px; padding-bottom: 9px; min-height: 1px; border-radius: 10px;"> <!-- select a zone -->
              <select onchange="SensorZoneOptions(this)" id="Sensorzoneoptions{{str_replace(' ','',$deviceFunction)}}" class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif form-control" style="height:34px; font-size:14px;">
                <!--Spawn more tabs according to number of zones-->
                @foreach($zonesArray as $zone)
                  @if(isset($functionZone[$deviceFunction][$zone]))
                    @if($zone != 0)
                      <option value="{{$zone}}" data-functionname="{{$deviceFunction}}" data-command={{$commandFunctions[$deviceFunction]}} data-toggle="collapse" data-parent="#system-device-in" ><!--ZONE TABS-->
                        {{ isset($zoneNameArray[$zone]) ? ($zoneNameArray[$zone]) : ("Zone".$zone) }}
                      </option>
                    @endif
                  @endif
                @endforeach
              </select>
            </div>
            <div> <!-- global/zonal edit button -->
              <ul class="toolbar cf col-xs-6 col-sm-4 col-sm-offset-4 ">
                <li class="globalbutton">
                  <a style="display: block;" class="zonal-setpoint @if($set_global_setpoint_tour) global_setpoint_change_tour @endif GZButton" title="Edit Global Setpoints" id="globalModalButton-{{$commandFunctions[$deviceFunction]}}" 
                  data-toggle="modal" data-target="#global{{$commandFunctions[$deviceFunction]}}Modal" data-backdrop="static">
                    <i class="fa fa-globe"  aria-hidden="true"></i>
                  </a>
                </li>
                <li class="zonalbutton">
                  <a style="display: block;" class="zonal-setpoint @if($set_zonal_setpoint_tour) zonal_setpoint_change_tour @endif GZButton" title="Edit {{isset($zoneNameArray[$zone]) ? ($zoneNameArray[$zone]) : ('Zone'.$zone)}} {{$deviceFunction}} Setpoints" 
                  id="zonalModalButton{{str_replace(' ','',$deviceFunction)}}" data-toggle="modal" data-target="#zonal{{$commandFunctions[$deviceFunction]}}{{$zone}}Modal" data-backdrop="static">
                    <i class="fa fa-pencil-square"  aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
                {{Form::open(['route'=>['setpointmapping.update', $thisBldg->id, $sid, -1*$commandFunctions[$deviceFunction]], "method" => "put"])}}
                <!-- Modal - GLOBAL FUNCTION-->
                <div class="modal fade modal-setback" option="false" id="global{{$commandFunctions[$deviceFunction]}}Modal" tabindex="-1" role="dialog" aria-labelledby="{{str_replace(' ','',$deviceFunction)}}ModalLabel" aria-hidden="true" style="color: black">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="font-weight:bold">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">
                            &times;
                          </span>
                        </button>
                        <h3 class="emc-modal-title" id="{{str_replace(' ','',$deviceFunction)}}ModalLabel" style="text-align:center; margin: 0px;">
                            Global {{$deviceFunction}}
                        </h3>
                      </div>
                      <div class="modal-body row" id="{{str_replace(' ','',$deviceFunction)}}ModalBody" name="{{str_replace(' ','',$deviceFunction)}}ModalBody" style="font-weight:normal">
                        <div class="form-inline" style="text-align:center">
                          <div class="col-sm-12 form-group">
                            <label for="global_{{$deviceFunction}}">
                              <h4 class="emc-modal-title">
                                Global {{$deviceFunction}} Setpoint:
                              </h4>
                            </label>
                          </div>
                          <div class="col-sm-12 col-md-6 col-md-offset-3 form-group">
                            {{$functionWithoutSpaces = str_replace(' ','',$deviceFunction);}}
                            <?php $thisclass = ($set_global_setpoint_tour)? "global_setpoint_change_tour form-control":"form-control"; ?>
                            {{Form::text("global_$functionWithoutSpaces",Null,["class" => ($set_global_setpoint_tour)? "global_setpoint_change_tour form-control":"form-control", "style" => "width:100%", "placeholder" => "Global Setpoint", "id" => "global_$functionWithoutSpaces", "title" => "Global setpoint for all $deviceFunction sensors."])}}
                          </div>
                        </div>
                        <div class="row" style="margin:10px;">
                        </div>
                        <div class="setback-group col-xs-12 hidden" style="margin-bottom: 5px; padding: 10px; background-color: #DBDBDB;" name="globalGroup-{{$commandFunctions[$deviceFunction]}}-0-0" id="globalGroup-{{$commandFunctions[$deviceFunction]}}-0-0">
                          <div class="setback-label col-xs-12 " id="globalNumber0" name="globalNumber0" style="text-align:center;">
                            Setback 0
                          </div>
                          <div class="setback-starttime col-xs-12 col-lg-6 mb-5" id="globalStartTime0" name="globalStartTime0" data-toggle="tooltip" data-placement="top" title="Global Start Time">
                            {{Form::text("globalStartTimeForm-$commandFunctions[$deviceFunction]-0-0",null,["class" => "setback-starttime-form form-control input-sm","placeholder" => "Start Time"])}}
                          </div>
                          <div class="setback-stoptime col-xs-12 col-lg-6 mb-5" id="globalStopTime0" name="globalStopTime0" data-toggle="tooltip" data-placement="top" title="Global Stop Time">
                            {{Form::text("globalStopTimeForm-$commandFunctions[$deviceFunction]-0-0",null,["class" => "setback-stoptime-form form-control input-sm", "placeholder" => "Stop Time"])}}
                          </div>
                          <div class="setback-values col-xs-12 col-lg-5 col-lg-offset-1" id="globalValue0" name="globalValue0" data-toggle="tooltip" data-placement="top" title="Global Setback Setpoint">
                            {{Form::text("globalValueForm-$commandFunctions[$deviceFunction]-0-0",null, ["class" => "form-control input-sm", "type" => "number", "placeholder" => "Global Setback Setpoint"])}}
                          </div>
                          <div class="setback-weekdays col-xs-10 col-sm-11 col-lg-5" id="globalWeekday0" name="globalWeekday0">
                            {{Form::select("globalWeekdayForm-$commandFunctions[$deviceFunction]-0-0",["9" => "EVERYDAY", "7" => "WEEKDAYS", "8" => "WEEKENDS", "0" => "SUNDAYS", "1" => "MONDAYS", "2" => "TUESDAYS", "3" => "WEDNESDAYS", "4" => "THURSDAYS", "5" => "FRIDAYS", "6" => "SATURDAYS"], 9, ["class" => "form-control input-sm setpoint-dropdown", "type" => "number"])}}
                          </div>
                          <div class="setback-delete col-xs-2 col-sm-1" id="globalDelete0" name="globalDelete0" style="line-height: 30px;">
                            <button type="button" class="btn btn-xs btn-danger" id="globalDeleteButton-{{$commandFunctions[$deviceFunction]}}-0-0" name="globalDeleteButton-{{$commandFunctions[$deviceFunction]}}-0-0"  onclick="globalDeleteButton(this)" data-toggle="tooltip" data-placement="left" title="Delete">
                              &nbsp;&minus;&nbsp;
                            </button>
                          </div>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" id="globalAddButton" name="globalAddButton" data-setbackgroup="globalGroup-{{$commandFunctions[$deviceFunction]}}-0-0"  style="margin-top:10px">
                            <button type="button" id="largeAddButton{{str_replace(' ','',$deviceFunction)}}" name="largeAddButton{{str_replace(' ','',$deviceFunction)}}" class="btn btn-md btn-primary setpoint-button setback-plus" onclick="globalAddButton(this)" style="display:block; width: 100%;">
                              Add Setback
                            </button>
                        </div>
                        <div class="col-xs-12" style="margin:10px">
                          <div class="col-xs-6">
                            {{Form::submit('Save', ["class"=>($set_global_setpoint_tour)?"global_setpoint_change_tour btn btn-lg btn-primary confirm setpoint-button":"btn btn-lg btn-primary confirm setpoint-button", "data-confirm" => "Are you sure you want to save your changes? Changes will effect all $deviceFunction sensors.", "name" => "saveGlobal$functionWithoutSpaces", "id" => "saveGlobal$functionWithoutSpaces"])}}
                          </div>
                          <div class="col-xs-6">
                            <button type="button" class="@if($set_global_setpoint_tour) global_setpoint_change_tour @endif btn btn-lg btn-primary confirm setpoint-button"  data-confirm='Are you sure you want to cancel? All unsaved changes will be lost.' data-dismiss="modal" aria-label="Close">
                              Cancel
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {{Form::close()}}
                <!-- END Modal - GLOBAL FUNCTION-->
                <!-- Modal - BY ZONE -->
                @foreach($zonesArray as $zone)
                  @if(isset($functionZone[$deviceFunction][$zone]))
                    @if($zone != 0)
                    {{Form::open(['route'=>['setpointmapping.update', $thisBldg->id, $sid, -1*$commandFunctions[$deviceFunction]], "method" => "put"])}}
                      {{ Form::hidden('zone_id', $zone) }}
                      <div class="modal fade modal-setback" id="zonal{{$commandFunctions[$deviceFunction]}}{{$zone}}Modal" tabindex="-1" role="dialog" aria-labelledby="{{str_replace(' ','',$deviceFunction)}}ModalLabel" aria-hidden="true" style="color: black">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content" style="font-weight:bold">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">
                                  &times;
                                </span>
                              </button>
                              <h3 class="emc-modal-title" id="{{$zone}}{{str_replace(' ','',$deviceFunction)}}ModalLabel" style="text-align:center; margin: 0px;">
                                {{isset($zoneNameArray[$zone]) ? ($zoneNameArray[$zone]) : ("Zone".$zone)}} {{$deviceFunction}}
                              </h3>
                            </div>
                            <div class="modal-body row" id="{{'Zone'.$zone}}{{str_replace(' ','',$deviceFunction)}}ModalBody" name="{{$zone}}{{str_replace(' ','',$deviceFunction)}}ModalBody" style="font-weight:normal">
                              <div class="form-inline" style="text-align:center">
                                <div class="form-group">
                                  <label for="zone_id">
                                    <h4 class="emc-modal-title">
                                      {{isset($zoneNameArray[$zone]) ? ($zoneNameArray[$zone]) : ("Zone".$zone)}} {{$deviceFunction}} Setpoint:
                                    </h4>
                                  </label>
                                </div>
                                <div class ="col-sm-12 col-md-6 col-md-offset-3 form-group">
                                  {{$functionWithoutSpaces = str_replace(' ','',$deviceFunction);}}
                                  {{Form::text("zonal_$functionWithoutSpaces$zone",Null,["class" => ($set_zonal_setpoint_tour)?"zonal_setpoint_change_tour form-control":"form-control", "style" => "width:100%", "placeholder" => "Zonal Setpoint", "id" => "zonal_$functionWithoutSpaces$zone", "title" => "Zonal setpoint for all $deviceFunction sensors in the chosen zone."])}}
                                </div>
                              </div>
                              <div class="row" style="margin:10px;"></div>
                              <div class="setback-group col-xs-12 hidden" style="margin-bottom: 5px; padding: 10px; background-color: #DBDBDB;" name="zonalGroup-{{$commandFunctions[$deviceFunction]}}-0-{{$zone}}" id="zonalGroup-{{$commandFunctions[$deviceFunction]}}-0-{{$zone}}">
                                <div class="setback-label col-xs-12" id="zonalNumber0{{$zone}}" name="zonalNumber0" style="text-align:center;">
                                  Setback 0
                                </div>
                                <div class="setback-starttime col-xs-12 col-lg-6 mb-5" id="zonalStartTime0{{$zone}}" name="zonalStartTime0" data-toggle="tooltip" data-placement="top" title="Global Start Time">
                                  {{Form::text("zonalStartTimeForm-$commandFunctions[$deviceFunction]-0-$zone",null,["class" => "setback-starttime-form form-control input-sm","placeholder" => "Start Time"])}}
                                </div>
                                <div class="setback-stoptime col-xs-12 col-lg-6 mb-5" id="zonalStopTime0{{$zone}}" name="zonalStopTime0" data-toggle="tooltip" data-placement="top" title="Global Stop Time">
                                  {{Form::text("zonalStopTimeForm-$commandFunctions[$deviceFunction]-0-$zone",null,["class" => "setback-stoptime-form form-control input-sm", "placeholder" => "Stop Time"])}}
                                </div>
                                <div class="setback-values col-xs-12 col-lg-5 col-lg-offset-1" id="zonalValue0{{$zone}}" name="zonalValue0" data-toggle="tooltip" data-placement="top" title="Zonal Setback Setpoint">
                                  {{Form::text("zonalValueForm-$commandFunctions[$deviceFunction]-0-$zone",null, ["class" => "form-control input-sm", "type" => "number", "placeholder" => "Zonal Setback Setpoint"])}}
                                </div>
                                <div class="setback-weekdays col-xs-10 col-sm-11 col-lg-5" id="zonalWeekday0{{$zone}}" name="zonalWeekday0">
                                  {{Form::select("zonalWeekdayForm-$commandFunctions[$deviceFunction]-0-$zone",["9" => "EVERYDAY", "7" => "WEEKDAYS", "8" => "WEEKENDS", "0" => "SUNDAYS", "1" => "MONDAYS", "2" => "TUESDAYS", "3" => "WEDNESDAYS", "4" => "THURSDAYS", "5" => "FRIDAYS", "6" => "SATURDAYS"], 9, ["class" => "form-control input-sm setpoint-dropdown", "type" => "number"])}}
                                </div>
                                <div class="setback-delete col-xs-2 col-sm-1" id="zonalDelete0{{$zone}}" name="zonalDelete0" style="line-height: 30px;">
                                  <button type="button" class="btn btn-xs btn-danger" id="zonalDeleteButton-{{$commandFunctions[$deviceFunction]}}-0-{{$zone}}" name="zonalDeleteButton-{{$commandFunctions[$deviceFunction]}}-0-{{$zone}}"  onclick="globalDeleteButton(this)" data-toggle="tooltip" data-placement="left" title="Delete">
                                    &nbsp;&minus;&nbsp;
                                  </button>
                                </div>
                              </div>
                              <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" id="zonalAddButton{{$zone}}" name="zonalAddButton" data-setbackgroup="zonalGroup-{{$commandFunctions[$deviceFunction]}}-0-{{$zone}}" style="margin-top:10px">
                                  <button type="button" id="largeAddButton{{str_replace(' ','',$deviceFunction)}}{{$zone}}" name="largeAddButton{{str_replace(' ','',$deviceFunction)}}" class="col-xs-12 btn btn-md btn-primary setpoint-button setback-plus" onclick="globalAddButton(this)" style="display:block; width: 100%;">
                                    Add Setback
                                  </button>
                              </div>
                              <div class="col-xs-12" style="margin:10px">
                                <div class="col-xs-6">
                                  {{Form::submit('Save', ["class"=>($set_zonal_setpoint_tour)?"zonal_setpoint_change_tour btn btn-lg btn-primary confirm setpoint-button":"btn btn-lg btn-primary confirm setpoint-button", "name" => "saveZonal$functionWithoutSpaces$zone", "data-confirm" => "Are you sure you want to save your changes? Changes will effect all $deviceFunction sensors within this zone.", "id" => "saveZonal$functionWithoutSpaces$zone"])}}
                                </div>
                                <div class="col-xs-6">
                                  <button type="button" class="@if($set_zonal_setpoint_tour) zonal_setpoint_change_tour @endif btn btn-lg btn-primary confirm setpoint-button" data-confirm='Are you sure you want to cancel? All unsaved changes will be lost.' data-dismiss="modal" aria-label="Close">
                                    Cancel
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      {{Form::close()}}
                    @endif
                  @endif
                @endforeach
                <?php $set_zonal_setpoint_tour = false; ?>
                <!--END - Modal - BY ZONE -->
            </div>
          </div>
        <?php
          $zone_count = 0;
          $set_single_setpoint_tour = true;
          ?>
          @foreach($zonesArray as $zone)
            @if(isset($functionZone[$deviceFunction][$zone]))
              @if($zone != 0)
                  {{Form::open(['route'=>['setpointmapping.update', $thisBldg->id, $sid, -1*$commandFunctions[$deviceFunction]], "method" => "put"])}}
                  <input name="zone_id" value="{{$zone}}" type="hidden">
                  <div class="container-fluid collapse" id="{{str_replace(' ','',$deviceFunction)}}{{str_replace(' ','',$zonesArray[$zone])}}" style="padding:0px;">
                    <?php $zone_count++; ?> <!-- show first zone when page loads -->
                    <div class="row"></div>
                    {{Form::close()}}
                    <!-- Grid Data -->
                    @foreach($devIdList as $deviceID)
                      @foreach($systemCommands as $sCommand)
                        @if(isset($megarray[$deviceFunction][$zone][$deviceID][$sCommand]))
                          @if($deviceFunction == 'Temperature')
                            <div class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif col-sm-6 col-md-4" id="{{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint_id']}}" style="font-family: helvetica">
                              <div class="block_emc block_emc_setpoints" style="overflow: hidden;">
                                {{Form::open(['route'=>['setpointmapping.update', $thisBldg->id, $sid, $megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint_id']], "method" => "put"])}}
                                <div class="col-xs-12" style="text-align: center;">
                                  <big><big>
                                    {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name']}}
                                  </big></big>
                                  <br>
                                </div>
                                <div style="position: absolute; top: 0; right: 20px; z-index: 2;"><!--Settings Button-->
                                  <div class="setpoint-setting @if($set_single_setpoint_tour) single_setpoint_change_tour @endif " title="Settings" style="width: 100%;" id="sensor{{$i}}ModalButton" data-toggle="modal" data-target="#sensor{{$i}}Modal" data-backdrop="static">
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                  </div>
                                </div>
                                <div class="col-xs-12" style="background-color: rgb(62, 98, 123); padding: 0px; padding-bottom: 10px;">
                                  <div class="col-xs-12"><!--priority alarms-->
                                    <div style="color: #26EF4F;text-align: center; font-weight: lighter;">
                                      @if((int)$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['priority_alarm'] == 1)
                                      Priority Alarm
                                      @else
                                      <br>
                                      @endif
                                    </div>
                                  </div>
                                  <div class="row" style="font-weight: normal" id="list{{$i}}" name="list{{$i}}"><!--current settings-->
                                    <div class="col-xs-12" id="setpoint_list{{$i}}" name="setpoint_list{{$i}}" style="padding-bottom:2px; padding-top:2px">
                                      <div class="col-xs-6 block_emc_heading" style="text-align:right; padding-right: 0px;">
                                        Setpoint:
                                      </div>
                                      <div class="col-xs-6">
                                        {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint']}} &deg;{{$tempFormat}}
                                      </div>
                                    </div>
                                    <div><!--alarm limits-->
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; text-align: center;">
                                          <small>
                                            Alarm Limits:
                                          </small>
                                        </div>
                                      </p>
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" id="alarm_low_list{{$i}}" name="alarm_low_list{{$i}}" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; ">
                                          <div class="col-xs-6 block_emc_heading" style="text-align:right; color: #428BCA;">
                                            Lower:
                                          </div>
                                          <div class="col-xs-6">
                                            {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_low']}} {{$commandUnits[$deviceFunction]}}
                                          </div>
                                        </div>
                                      </p>
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" id="alarm_high_list{{$i}}" name="alarm_high_list{{$i}}" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; ">
                                          <div class="col-xs-6 block_emc_heading" style="text-align:right; color: red;">
                                            Upper:
                                          </div>
                                          <div class="col-xs-6">
                                            {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_high']}} {{$commandUnits[$deviceFunction]}}
                                          </div>
                                        </div>
                                      </p>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal - TEMPERATURE-->
                                <div class="modal fade modal-setback" id="sensor{{$i}}Modal" tabindex="-1" role="dialog" aria-labelledby="sensor{{$i++}}ModalLabel" aria-hidden="true" style="color: black">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">
                                            &times;
                                          </span>
                                        </button>
                                        <h3 class="emc-modal-title" id="myModalLabel" style="text-align:center; margin: 0px;">
                                          {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name']}} Setpoints
                                        </h3>
                                      </div>
                                      <div class="modal-body row" id="{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name'])}}{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['command'])}}{{str_replace(' ','',$deviceFunction)}}ModalBody" name="{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name'])}}{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['command'])}}{{str_replace(' ','',$deviceFunction)}}ModalBody">
                                        <div class="row" style="font-weight: normal;" id="form{{$i}}" name="form{{$i}}">
                                          <div class="row">
                                            <div class="col-xs-6">
                                              <div class="form-group" id="setpoint_form{{$i}}" name="setpoint_form{{$i}}">
                                                <label for="field1A">Setpoint:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('setpoint',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%;color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "setpoint", "title" => "setpoint"])}}
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-xs-6">
                                              <div class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif form-group">
                                                <label for="field1B">Priority Alarm:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::checkbox('priority_alarms',1,$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['priority_alarm'],["class" => "tgl tgl-skewed", "id" => "priority_alarms$i", "title" => "Choose to show this device in the main alarm reports."])}}
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="priority_alarms{{$i}}"></label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-xs-6" id="alarm_low_form{{$i}}" name="alarm_low_form{{$i}}">
                                              <div class="form-group">
                                                <label for="field2A">Lower Alarm Limit:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('alarm_low',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_low'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%; color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "alarm_low", "title" => "alarm_low"])}}
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-xs-6" id="alarm_high_form{{$i}}" name="alarm_high_form{{$i}}">
                                              <div class="form-group">
                                                <label for="field2B">Upper Alarm Limit:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('alarm_high',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_high'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%; color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "alarm_high", "title" => "alarm_high"])}}
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row" style="margin:10px;">
                                        </div>
                                        <div class="setback-group hidden col-xs-12" style="margin-bottom: 5px; padding: 10px; background-color: #DBDBDB;" name="setbackGroup-{{$deviceID}}-{{$sCommand}}-0" id="setbackGroup-{{$deviceID}}-{{$sCommand}}-0">
                                          <div class="setback-label col-xs-12" id="sbNumber0" name="sbNumber0" style="text-align:center;">
                                            Setback 0
                                          </div>
                                          <div class="setback-starttime col-xs-12 col-lg-6 mb-5" id="sbStartTime0" name="sbStartTime0" data-toggle="tooltip" data-placement="top" title="Start Time">
                                            {{Form::text("sbStartTimeForm-$deviceID-$sCommand-0",null,["class" => "setback-starttime-form form-control input-sm", "placeholder" => "Start Time"])}}
                                          </div>
                                          <div class="setback-stoptime col-xs-12 col-lg-6 mb-5" id="sbStopTime0" name="sbStopTime0" data-toggle="tooltip" data-placement="top" title="Stop Time">
                                            {{Form::text("sbStopTimeForm-$deviceID-$sCommand-0",null,["class" => "setback-stoptime-form form-control input-sm", "placeholder" => "Stop Time"])}}
                                          </div>
                                          <div class="setback-values col-xs-12 col-lg-5 col-lg-offset-1" id="sbValue0" name="sbValue0" data-toggle="tooltip" data-placement="top" title="Setback Setpoint">
                                            {{Form::text("sbValueForm-$deviceID-$sCommand-0", null, ["class" => "form-control input-sm", "type" => "number", "placeholder" => "Setback Setpoint"])}}
                                          </div>
                                          <div class="setback-weekdays col-xs-10 col-sm-11 col-lg-5" id="sbWeekday0" name="sbWeekday0">
                                            {{Form::select("sbWeekdayForm-$deviceID-$sCommand-0",["9" => "EVERYDAY", "7" => "WEEKDAYS", "8" => "WEEKENDS", "0" => "SUNDAYS", "1" => "MONDAYS", "2" => "TUESDAYS", "3" => "WEDNESDAYS", "4" => "THURSDAYS", "5" => "FRIDAYS", "6" => "SATURDAYS"], 9, ["class" => "form-control input-sm setpoint-dropdown", "type" => "number"])}}
                                          </div>
                                          <div class="setback-delete col-xs-2 col-sm-1" id="sbDelete0" name="sbDelete0" style="line-height: 30px;">
                                            <button type="button" class="btn btn-xs btn-danger" id="sbDeleteButton" name="sbDeleteButton"  onclick="deleteButton(this)" data-toggle="tooltip" data-placement="left" title="Delete">
                                              &nbsp;&minus;&nbsp;
                                            </button>
                                          </div>
                                        </div>
                                        <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" id="addButton" name="addButton" data-setbackgroup="setbackGroup-{{$deviceID}}-{{$sCommand}}-0" style="margin-top:10px">
                                          <button type="button" class="btn btn-md btn-primary setpoint-button setback-plus" onclick="addButton(this)" style="display:block;">
                                            Add Setback
                                          </button>
                                        </div>
                                        <div class="col-xs-12" style="margin-top: 10px; margin-bottom: 10px; margin-left: -10px;">
                                          <div class="col-xs-6">
                                            {{Form::submit('Save', ["class"=>($set_single_setpoint_tour)?"single_setpoint_change_tour btn btn-lg btn-primary confirm setpoint-button":"btn btn-lg btn-primary confirm setpoint-button", "style"=>"font-size: 15px; padding: 5px; width: 100%;", "data-confirm" => "Are you sure you want to save your changes?", "name" => "saveButton$i", "id" => "saveButton$i"])}}
                                          </div>
                                          <div class="col-xs-6">
                                            <button type="button" class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif btn btn-lg btn-primary confirm setpoint-button" data-confirm='Are you sure you want to cancel? All unsaved changes will be lost.' data-dismiss="modal" aria-label="Close">
                                              Cancel
                                            </button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- END Modal - TEMPERATURE -->
                                {{Form::close()}}
                              </div>
                            </div>
                            <?php $set_single_setpoint_tour = false; ?>
                          @else
                            <div class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif col-sm-6 col-md-4" id="{{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint_id']}}" style="font-family: helvetica">
                              <div class="block_emc block_emc_setpoints" style="overflow: hidden;">
                                {{Form::open(['route'=>['setpointmapping.update', $thisBldg->id, $sid, $megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint_id']], "method" => "put"])}}
                                <div class="col-xs-12" style="text-align: center;margin-bottom:3pt;">
                                  <big><big>
                                  {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name']}}
                                  </big></big>
                                  <br>
                                </div>
                                <div class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif " style="position: absolute; top: 0; right: 20px; z-index:2;"><!--Settings Button-->
                                  <div class="setpoint-setting" title="Settings" style="width: 100%;" id="sensor{{$i}}ModalButton" data-toggle="modal" data-target="#sensor{{$i}}Modal" >
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                  </div>
                                </div>
                                <div class="col-xs-12" style="background-color: rgb(62, 98, 123); padding: 0px; padding-bottom: 10px;">
                                  <div class="col-xs-12"><!--priority alarms-->
                                    <div style="color: #26EF4F;text-align: center; font-weight: lighter;">
                                      @if((int)$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['priority_alarm'] == 1)
                                      Priority Alarm
                                      @else
                                      <br>
                                      @endif
                                    </div>
                                  </div>
                                  <div class="row" style="font-weight: normal" id="list{{$i}}" name="list{{$i}}"><!--current settings-->
                                    <div class="col-xs-12" id="setpoint_list{{$i}}" name="setpoint_list{{$i}}" style="padding-bottom:2px; padding-top:2px">
                                      <div class="col-xs-6 block_emc_heading" style="text-align:right; padding-right: 0px;">
                                        Setpoint:
                                      </div>
                                      <div class="col-xs-6">
                                        {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint']}} {{$commandUnits[$deviceFunction]}}
                                      </div>
                                    </div>
                                    <div><!--alarm limits-->
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; text-align: center;">
                                          <small>
                                            Alarm Limits:
                                          </small>
                                        </div>
                                      </p>
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" id="alarm_low_list{{$i}}" name="alarm_low_list{{$i}}" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; ">
                                          <div class="col-xs-6 block_emc_heading" style="text-align:right; color: #428BCA;">
                                            Lower:
                                          </div>
                                          <div class="col-xs-6">
                                            {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_low']}} {{$commandUnits[$deviceFunction]}}
                                          </div>
                                        </div>
                                      </p>
                                      <p>
                                        <div class="col-xs-10 col-xs-offset-1" id="alarm_high_list{{$i}}" name="alarm_high_list{{$i}}" style="padding-bottom:2px; padding-top:2px; background-color: #2B3C51; ">
                                          <div class="col-xs-6 block_emc_heading" style="text-align:right; color: red;">
                                            Upper:
                                          </div>
                                          <div class="col-xs-6">
                                            {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_high']}} {{$commandUnits[$deviceFunction]}}
                                          </div>
                                        </div>
                                      </p>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal - NON-TEMPERATURE -->
                                <div class="modal fade modal-setback" id="sensor{{$i}}Modal" tabindex="-1" role="dialog" aria-labelledby="sensor{{$i++}}ModalLabel" aria-hidden="true" style="color: black">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">
                                            &times;
                                          </span>
                                        </button>
                                        <h3 class="emc-modal-title" id="myModalLabel" style="text-align:center; margin: 0px;">
                                          {{$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name']}} Setpoints
                                        </h3>
                                      </div>
                                      <div class="modal-body row" id="{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name'])}}{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['command'])}}{{str_replace(' ','',$deviceFunction)}}ModalBody" name="{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['device_name'])}}{{str_replace(' ','',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['command'])}}{{str_replace(' ','',$deviceFunction)}}ModalBody">
                                        <div class="setpoint-group row" style="font-weight: normal;" id="form{{$i}}" name="form{{$i}}">
                                          <div class="row">
                                            <div class="col-xs-6">
                                              <div class="form-group" id="setpoint_form{{$i}}" name="setpoint_form{{$i}}">
                                                <label for="field1A">Setpoint:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('setpoint',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%;color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "setpoint", "title" => "setpoint"])}}
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-xs-6">
                                              <div class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif form-group">
                                                <label for="field1B">Priority Alarm:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::checkbox('priority_alarms',1,$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['priority_alarm'],["class" => "tgl tgl-skewed", "id" => "priority_alarms$i", "title" => "Choose to show this device in the main alarm reports."])}}
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="priority_alarms{{$i}}"></label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-xs-6" id="alarm_low_form{{$i}}" name="alarm_low_form{{$i}}">
                                              <div class="form-group">
                                                <label for="field2A">Lower Alarm Limit:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('alarm_low',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_low'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%; color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "alarm_low", "title" => "alarm_low"])}}
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-xs-6" id="alarm_high_form{{$i}}" name="alarm_high_form{{$i}}">
                                              <div class="form-group">
                                                <label for="field2B">Upper Alarm Limit:</label>
                                                <div class="form-control-wrapper">
                                                  {{Form::text('alarm_high',$megarray[$deviceFunction][$zone][$deviceID][$sCommand]['alarm_high'],["class" => ($set_single_setpoint_tour)?"single_setpoint_change_tour form-control":"form-control","style" => "width: 90%; color:black; border-radius: 2px; padding: 0; height: 24px", "placeholder" => "0", "id" => "alarm_high", "title" => "alarm_high"])}}
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row" style="margin:10px;"></div>
                                        <div class="setback-group hidden col-xs-12" style="margin-bottom: 5px; padding: 10px; background-color: #DBDBDB;" name="setbackGroup-{{$deviceID}}-{{$sCommand}}-0" id="setbackGroup-{{$deviceID}}-{{$sCommand}}-0">
                                          <div class="setback-label col-xs-12" id="sbNumber0" name="sbNumber0" style="text-align:center;">
                                            Setback 0
                                          </div>
                                          <div class="setback-starttime col-xs-12 col-lg-6 mb-5" id="sbStartTime0" name="sbStartTime0" data-toggle="tooltip" data-placement="top" title="Start Time">
                                            {{Form::text("sbStartTimeForm-$deviceID-$sCommand-0",null,["class" => "setback-starttime-form form-control input-sm ", "placeholder" => "Start Time"])}}
                                          </div>
                                          <div class="setback-stoptime col-xs-12 col-lg-6 mb-5" id="sbStopTime0" name="sbStopTime0" data-toggle="tooltip" data-placement="top" title="Stop Time">
                                            {{Form::text("sbStopTimeForm-$deviceID-$sCommand-0",null,["class" => "setback-stoptime-form form-control input-sm ", "placeholder" => "Stop Time"])}}
                                          </div>
                                          <div class="setback-values col-xs-12 col-lg-5 col-lg-offset-1" id="sbValue0" name="sbValue0" data-toggle="tooltip" data-placement="top" title="Setback Setpoint">
                                            {{Form::text("sbValueForm-$deviceID-$sCommand-0", $megarray[$deviceFunction][$zone][$deviceID][$sCommand]['setpoint'], ["class" => "form-control input-sm", "type" => "number"])}}
                                          </div>
                                          <div class="setback-weekdays col-xs-10 col-sm-11 col-lg-5" id="sbWeekday0" name="sbWeekday0">
                                            {{Form::select("sbWeekdayForm-$deviceID-$sCommand-0",["9" => "EVERYDAY", "7" => "WEEKDAYS", "8" => "WEEKENDS", "0" => "SUNDAYS", "1" => "MONDAYS", "2" => "TUESDAYS", "3" => "WEDNESDAYS", "4" => "THURSDAYS", "5" => "FRIDAYS", "6" => "SATURDAYS"], 9, ["class" => "form-control input-sm setpoint-dropdown", "type" => "number"])}}
                                          </div>
                                          <div class="setback-delete col-xs-2 col-sm-1" id="sbDelete0" name="sbDelete0" style="line-height: 30px;">
                                            <button type="button" class="btn btn-xs btn-danger" id="sbDeleteButton" name="sbDeleteButton"  onclick="deleteButton(this)" data-toggle="tooltip" data-placement="left" title="Delete">
                                              &nbsp;&minus;&nbsp;
                                            </button>
                                          </div>
                                        </div>
                                        <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" id="addButton" name="addButton" data-setbackgroup="setbackGroup-{{$deviceID}}-{{$sCommand}}-0" style="margin-top:10px">
                                          <button type="button" class="btn btn-md btn-primary setpoint-button setback-plus" onclick="addButton(this)" style="display:block;width: 100%;">
                                            Add Setback
                                          </button>
                                        </div>
                                        <div class="col-xs-12" style="margin:10px">
                                          <div class="col-xs-6">
                                            {{Form::submit('Save', ["class"=>($set_single_setpoint_tour)?"single_setpoint_change_tour btn btn-lg btn-primary confirm setpoint-button":"btn btn-lg btn-primary confirm setpoint-button", "data-confirm" => "Are you sure you want to save your changes?", "name" => "saveButton$i", "id" => "saveButton$i"])}}
                                          </div>
                                          <div class="col-xs-6">
                                            <button type="button" class="@if($set_single_setpoint_tour) single_setpoint_change_tour @endif btn btn-lg btn-primary confirm setpoint-button" data-confirm='Are you sure you want to cancel? All unsaved changes will be lost.' data-dismiss="modal" aria-label="Close">
                                              Cancel
                                            </button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!--END Modal - NON-TEMPERATURE-->
                                {{Form::close()}}
                              </div>
                            </div>
                            <?php $set_single_setpoint_tour = false; ?>
                          @endif
                        @endif
                      @endforeach
                    @endforeach
                  </div>
              @endif
            @endif
          @endforeach
          </div>
          
          <?php $set_global_setpoint_tour = false; ?>
        @endforeach
        </div>
        <br>
        <Br>
        @if(in_array(Auth::user()->auth_role,[7,8]))
          <div class="col-xs-12" style="text-align: center;">
            {{ Form::open(['route' => ['setpointmapping.remap', $thisBldg->id, $sid], 'method' => 'post']) }}
            {{ Form::submit('Remap Devices',["class" => "btn btn-primary btn-md js-confirm", "style"=>"margin-bottom: 10px;", "title" => "Remap devices to update setpoints list.", "data-confirm" => "Are you sure you want to remap setpoints? This may take a while to complete."]) }}
            {{ Form::close() }}
          </div>
          <?php
      /*
          foreach ($remapDevices as $rd) {
            echo($rd."<br>!");
          }
          echo("megarray:".sizeof($megarray).". functionsArray:".sizeof($functionsArray).". zonesArray:".sizeof($zoneArray).".devIdList:".sizeof($devIdList).". systemCommands:".sizeof($systemCommands).".<br>");
          foreach ($functionsArray as $fa) {
            echo($fa."<br>");
            foreach ($zonesArray as $za) {
              echo($za."<br>");
              foreach ($devIdList as $dev) {
                echo($dev."<br>");
                foreach ($systemCommands as $sc) {
                  if(isset($megarray[$fa][$za][$dev][$sc])){
                    echo($megarray[$fa][$za][$dev][$sc]["device_name"]."<br>");
                  }
                }
              }
            }
          }
      */    ?>
        @endif
      </div>
    </div>
  </main>
    <!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
  <?
  	//Cache control - Scripts
  	//Add last modified date of a file to the URL, as get parameter.
  	$import_scripts = ['/js/bootstrap-tabcollapse.js'];
  	foreach ($import_scripts as $value) {
  		$filename = public_path().$value;
  		if (file_exists($filename)) {
  				$appendDate = substr($value."?v=".filemtime($filename), 1);
  				echo HTML::script($appendDate);
  		}
  	}
  ?>
  {{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.4/flatpickr.js') }}
  <script>
    $(document).ready(function() {
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="global_setpoint_change_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Global Setpoints\
        </a>\
        <a href="javascript:void(0);" class="zonal_setpoint_change_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Zonal Setpoints\
        </a>\
        <a href="javascript:void(0);" class="single_setpoint_change_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Individual Setpoints\
        </a>'
      );
      $('#myTab').tabCollapse();
      $('#pageload').show();
      var back_set_devs = {{json_encode($back_set_devs)}};
      var setbacks      = {{json_encode($setbacks)}};
      var twelveHourSeconds = 43200;
      /******************************************
       *  Add Setback to the every single device
       * ****************************************/
      back_set_devs.forEach(function(ds){
        var setbackObj;
        var index = 1;
        var setbackStartTime, setbackStartTimeForm;
        var setbackStopTime, setbackStopTimeForm;
        var setbackValue, setbackValueForm;
        var setbackWeekday, setbackWeekdayForm;
        var deletebutton, deletebuttonForm;
        var function_name = ds.function.replace(/ /g,"");
        var device_name   = ds.device_name.replace(/ /g,"");
        var setbackModal  = document.getElementById(device_name+ds.command+function_name+"ModalBody");
        var setbackGroup  = setbackModal.getElementsByClassName('setback-group')[0];
        var addButton     = setbackModal.getElementsByClassName('setback-plus')[0];
        addButton.innerHTML = "&plus;";
        setbacks.forEach(function(setback){
        //Create list of setbacks in modal.
          if((setback.device_id == ds.device_id) && (setback.command == ds.command) && (setback.index == ds.setback_index)){
            /*Generate another setback form*/
            if(ds.setback_index != 0){
              setbackObj = setbackGroup.cloneNode('True');
            }
            else{
              setbackObj = setbackGroup;
            }
            setbackObj.id = "setbackGroup"+ds.setback_index;
            $(setbackObj).attr('name',"setbackGroup-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index);
            if(ds.setback_index != 0){
              setbackModal.insertBefore(setbackObj,addButton.parentNode);
            }
            $(setbackObj).removeClass('hidden');
            setbackLabel = setbackObj.getElementsByClassName("setback-label")[0];;
            setbackLabel.id = "sbNumber"+ds.setback_index;
            setbackLabel.innerHTML = "Setback "+ds.setback_index;
            setbackStartTime = setbackObj.getElementsByClassName("setback-starttime")[0];
            setbackStartTime.id = "sbStartTime"+ds.setback_index;
            setbackStartTime.name = "sbStartTime"+ds.setback_index;
            setbackStartTimeForm = setbackStartTime.getElementsByClassName("setback-starttime-form")[0];
            setbackStartTimeForm.name = "sbStartTimeForm-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index;
            starttime = setback.starttime.split(':');
            stoptime = setback.stoptime.split(':');
            // ================= START time ================= 
            if(starttime[1] == 0){
              setbackStartTimeForm.value = null;
            }
            else {
              var sb_time = parseInt(starttime[1]);
              var date = new Date(null);
              date.setSeconds(sb_time); // specify value for SECONDS here
              var result = date.toISOString().substr(11, 8);  //Convert to HH:MM:SS
              setbackStartTimeForm.value = result;
            }
            setbackStopTime = setbackObj.getElementsByClassName("setback-stoptime")[0];
            setbackStopTime.id = "sbStopTime"+ds.setback_index;
            setbackStopTimeForm = setbackStopTime.getElementsByClassName("setback-stoptime-form")[0];
            setbackStopTimeForm.name = "sbStopTimeForm-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index;
            // ================= STOP time ================= 
            if(parseInt(stoptime[1]) == 0){
              setbackStopTimeForm.value = null;
            }else{
              var sb_time = parseInt(stoptime[1]);
              var date = new Date(null);
              date.setSeconds(sb_time); // specify value for SECONDS here
              var result = date.toISOString().substr(11, 8);  //Convert to HH:MM:SS
              setbackStopTimeForm.value = result;
            }
            setbackValue = setbackObj.getElementsByClassName("setback-values")[0];
            setbackValue.id = "sbValue"+ds.setback_index;
            setbackValueForm = setbackValue.firstElementChild;
            setbackValueForm.name = "sbValueForm-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index;
            setbackWeekday = setbackObj.getElementsByClassName("setback-weekdays")[0];
            setbackWeekday.id = "sbWeekday"+ds.setback_index;
            setbackWeekdayForm = setbackWeekday.firstElementChild;
            setbackWeekdayForm.name = "sbWeekdayForm-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index;
            setbackWeekdayForm.value = parseInt(starttime[0]);
            deletebutton = setbackObj.getElementsByClassName("setback-delete")[0];
            deletebutton.id = "sbDelete"+ds.setback_index;
            deletebuttonForm = deletebutton.firstElementChild;
            deletebuttonForm.id = "sbDeleteButton-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index;
            deletebuttonForm.name = "sbDeleteButton-"+ds.device_id+"-"+ds.command+"-"+ds.setback_index
            if("{{$tempFormat}}" == "F" && function_name == 'Temperature'){
              setpoint = parseFloat(setback.setback*(9/5)+32).toFixed(2);
              ds.units = "&deg;F";
            }else{
              setpoint = parseFloat(setback.setback).toFixed(2);
              ds.units = "&deg;C";
            }
            setbackValueForm.value = setpoint;
            setbackValueForm.id = "sbValue"+ds.setback_index;
            index++;
            addtimemodal(setbackStartTimeForm, setbackStopTimeForm );
          }
        });
      });
        
      //On page load show hide the specific data per zone select
      @foreach($functionsArray as $deviceFunction)
          SensorZoneOptions(document.getElementById("Sensorzoneoptions{{str_replace(' ','',$deviceFunction)}}"));
      @endforeach
    });
    
    //hide and show the zone content
    function SensorZoneOptions(s){
      var functionname = s[s.selectedIndex].dataset.functionname;
      var command = s[s.selectedIndex].dataset.command;
      functionname = functionname.replace(/ +/g, "");
      @foreach($zonesArray as $zone)
        if ({{$zone}}==s[s.selectedIndex].value) {
          $("#"+functionname+s[s.selectedIndex].value).collapse('toggle');
          $('#zonalModalButton'+functionname).attr('data-target', '#zonal'+command+s[s.selectedIndex].value+'Modal');   // change which modal to show
        }else{
          $("#"+functionname+{{$zone}}+":visible").collapse('hide');
        }
      @endforeach
    }
    $("body").tooltip({
      selector: '[data-toggle="tooltip"]' //to show tooltip in dynamic element
    });

    function ConfirmForm(){
        return confirm("You are about to submit a form to Google.nn" +"Click OK to continue or Cancel to abort.");
    }

    function globalAddButton(thisButton){
      alert("Changes to global or zonal Setbacks will remove previous setbacks for effected devices. To preserve your devices previous setbacks, you must update devices individually. Click Cancel to prevent setback overwriting.");
      var setbackModal  = getClosest(thisButton, '.modal-body');
      var setbackGroup  = setbackModal.getElementsByClassName('setback-group')[0]; // Using a hidden template. This div contains all the form element of this particular setback
      var button_group  = thisButton.parentNode;
      var type          = button_group.getAttribute("name").replace('AddButton','');
      var values        = button_group.dataset.setbackgroup.split('-');
      var command       = values[1];
      var index         = Number(values[2]) + 1;
      var zone          = Number(values[3]);
      if(values[2] == '0'){         //If adding first setback, hide the big "Add Setback" add button text and replace it with "+"
        thisButton.innerHTML = "&plus;";
      }
      button_group.dataset.setbackgroup = type+"Group-"+command+"-"+index+"-"+zone;       //Change data-setbackgroup attribute to right index, So I can access the last created setback Group
      setback = setbackGroup.cloneNode('True');       //This creates new setback groups from template setback html code
      globalChangeAttribute(setback, type, command, index, zone, timemodal = true);
      //insert this new setback above add button
      setbackModal.insertBefore(setback,button_group);
      if($(setback).hasClass('hidden')){
        $(setback).removeClass('hidden');
      }
      if(index == 16){
        thisButton.style.display = 'none';
      }
    }/*~~~ END globalAddButton ~~~*/
    function globalDeleteButton(button){
      var setbackModal    = getClosest(button, '.modal-body');
      var setbackGroup    = getClosest(button, '.setback-group'); 
      var next_setback    = setbackGroup.nextElementSibling;
      var addButton       = setbackModal.getElementsByClassName('setback-plus')[0];
      var addButton_group = addButton.parentElement;
      var values          = $(button).attr('name').split('-');
      var command         = values[1];
      var index           = Number(values[2]);
      var zone            = Number(values[3]);
      var type            = button.parentNode.id.replace('Delete','').replace(index,'');      //global or zonal
      if(addButton.style.display == 'none'){
        addButton.style.display = 'block';
      }
      if(values[2] == '1' && next_setback.id.search('AddButton') !== -1){  /*If no setbacks are currently displayed and next_setback is just a add button*/
        addButton.innerHTML = "Add Setback";
      }
      //DELETE setback
      setbackGroup.remove();
      while(next_setback != null && next_setback.id.search('AddButton') == -1){ /*If a setback already exists*/
        globalChangeAttribute(next_setback, type, command, index, zone, timemodal = false)
        index++;
        next_setback = next_setback.nextElementSibling;
      }
      addButton_group.dataset.setbackgroup = type+"Group-"+command+"-"+(index-1)+"-"+zone;  //Update the "data-setbackgroup" attribute
    }
    /**
      * Change all the attribute values of each form input. FORM ZONAL AND GLOBAL 
      * @private
      * @param  {Element} setback  Current setback div
      * @param  {String}  type     Either "zonal" or "Global"
      * @param  {Int}     command  Current device command
      * @param  {Int}     index    Current setback number. Starts with 1
      * @param  {Int}     zone     zone id. For Global- zone id = 0
      * @param  {Boolean} Timemodal  True if wanna add modal for time selection
    */
    function globalChangeAttribute(setback, type, command, index, zone, TimeModal){
      setback.id  = type + "Group-" + command +"-" + index + "-" + zone;
      setback.setAttribute('name',type + "Group-" + command + "-" + index + "-" + zone);
      setbackLabel = setback.getElementsByClassName("setback-label")[0];
      setbackLabel.id = type + "Number" + index;
      setbackLabel.setAttribute('name',type + "Number" + index);
      setbackLabel.innerHTML = "Setback " + index;
      setbackStartTime = setback.getElementsByClassName("setback-starttime")[0];
      setbackStartTime.id = type + "StartTime" + index;
      setbackStartTime.setAttribute('name',type + "StartTime" + index);
      setbackStartTimeForm = setbackStartTime.getElementsByClassName("setback-starttime-form")[0];
      setbackStartTimeForm.name = type + "StartTimeForm-" + command + "-" + index + "-" + zone;
      setbackStopTime = setback.getElementsByClassName("setback-stoptime")[0];
      setbackStopTime.id = type + "StopTime" + index;
      setbackStopTime.setAttribute('name',type + "StopTime" + index);
      setbackStopTimeForm = setbackStopTime.getElementsByClassName("setback-stoptime-form")[0];
      setbackStopTimeForm.name = type + "StopTimeForm-" + command + "-" + index + "-" + zone;
      setbackValue = setback.getElementsByClassName("setback-values")[0];
      setbackValue.id = type + "Value" + index;
      setbackValue.setAttribute('name',type + "Value" + index);
      setbackValueForm = setbackValue.firstElementChild;
      setbackValueForm.name = type + "ValueForm-" + command + "-" + index + "-" + zone;
      setbackWeekday = setback.getElementsByClassName("setback-weekdays")[0];
      setbackWeekday.id = type + "Weekday" + index;
      setbackWeekday.setAttribute('name',type + "Weekday" + index);
      setbackWeekdayForm = setbackWeekday.firstElementChild;
      setbackWeekdayForm.name = type + "WeekdayForm-" + command + "-" + index + "-" + zone;
      deletebutton = setback.getElementsByClassName("setback-delete")[0];
      deletebutton.id = type + "Delete" + index;
      deletebutton.setAttribute('name',type + "Delete" + index);
      deletebuttonForm = deletebutton.firstElementChild;
      deletebuttonForm.id = type + "DeleteButton-" + command + "-" + index + "-" + zone;
      deletebuttonForm.name = type + "DeleteButton-" + command + "-" + index + "-" + zone;
      if (TimeModal) {
        addtimemodal(setbackStartTimeForm, setbackStopTimeForm);
      }
    }
    function deleteButton(button){
      var setbackModal    = getClosest(button, '.modal-body');
      var setbackGroup    = getClosest(button, '.setback-group'); 
      var next_setback    = setbackGroup.nextElementSibling;
      var addButton       = setbackModal.getElementsByClassName('setback-plus')[0];
      var addButton_group = addButton.parentElement;
      var values          = button.name.split('-');
      var id              = values[1];
      var command         = Number(values[2]);
      var index           = Number(values[3]);
      if(addButton.style.display == 'none'){
        addButton.style.display = 'block';
      }
      if(values[3] == '1' && next_setback.id.search('addButton') !== -1){ /*If no setbacks are currently displayed and next_setback is just a add button*/
        addButton.innerHTML = "Add Setback";
      }
      //DELETE setback
      setbackGroup.remove();
      while(next_setback != null && next_setback.id.search('addButton') == -1){ /*If a setback already exists*/
        ChangeAttributes(next_setback, id, command, index, timemodal = false);
        index++;
        next_setback = next_setback.nextElementSibling;
      }
      addButton_group.dataset.setbackgroup = "setbackGroup-"+"-"+id+"-"+command+"-"+(index-1);  //Update the "data-setbackgroup" attribute
    }
    function addButton(thisButton){
      var setbackModal  = getClosest(thisButton, '.modal-body');
      var setbackGroup  = setbackModal.getElementsByClassName('setback-group')[0]; // Using a hidden template. This div contains all the form element of this particular setback
      var button_group  = thisButton.parentNode;
      // If there's already a setback present, change the values
      if (button_group.dataset.setbackgroup.trim() === button_group.previousElementSibling.getAttribute("name").trim()) {
        var values      = button_group.dataset.setbackgroup.split('-');
      }else{
        var values      = button_group.previousElementSibling.getAttribute("name").split('-');
      }
      var id            = values[1];
      var command       = Number(values[2]);
      var index         = Number(values[3])+1;
      if(values[3] == '0'){
        thisButton.innerHTML = "&plus;";
      }
      button_group.dataset.setbackgroup = "setbackGroup-"+id+"-"+command+"-"+index;       //Change data-setbackgroup attribute to right index, So I can access the last created setback Group
      /*Create setback group*/
      setback = setbackGroup.cloneNode('True');
      ChangeAttributes(setback, id, command, index, timemodal = true);
      setbackModal.insertBefore(setback,button_group);
      if($(setback).hasClass('hidden')){
        $(setback).removeClass('hidden');
      }
      if(index == 16){
        thisButton.style.display = 'none';
      }
    }
    /**
      * Change all the attribute values of each form input. FOR SINGLE DEVICES 
      * @private
      * @param  {Element} setback  Current setback div
      * @param  {Int}     command  Current device ID
      * @param  {Int}     command  Current device command
      * @param  {Int}     index    Current setback number. Starts with 1    
      * @param  {Boolean} Timemodal  True if wanna add modal for time selection
    */
    function ChangeAttributes(setback, id, command, index, timemodal){
      setback.id = "setbackGroup" + index;
      setback.setAttribute('name',"setbackGroup-" + id + "-" + command +"-" + index);// name = "setbackGroup-" + id + "-" + command +"-" + index;
      
      setbackLabel = setback.getElementsByClassName("setback-label")[0];
      setbackLabel.id = "sbNumber" + index;
      setbackLabel.innerHTML = "Setback " + index;
      setbackStartTime = setback.getElementsByClassName("setback-starttime")[0];
      setbackStartTime.id = "sbStartTime" + index;
      setbackStartTimeForm = setbackStartTime.getElementsByClassName("setback-starttime-form")[0];
      setbackStartTimeForm.name = "sbStartTimeForm-" + id + "-" + command + "-" + index;
      setbackStopTime = setback.getElementsByClassName("setback-stoptime")[0];
      setbackStopTime.id = "sbStopTime" + index;
      setbackStopTimeForm = setbackStopTime.getElementsByClassName("setback-stoptime-form")[0];
      setbackStopTimeForm.name = "sbStopTimeForm-" + id + "-" + command + "-" + index;
      setbackValue = setback.getElementsByClassName("setback-values")[0];
      setbackValue.id = "sbValue" + index;
      setbackValueForm = setbackValue.firstElementChild;
      setbackValueForm.name = "sbValueForm-" + id + "-" + command + "-" + index;
      setbackWeekday = setback.getElementsByClassName("setback-weekdays")[0];
      setbackWeekday.id = "sbWeekday" + index;
      setbackWeekdayForm = setbackWeekday.firstElementChild;
      setbackWeekdayForm.name = "sbWeekdayForm-" + id + "-" + command + "-" + index;
      deletebutton = setback.getElementsByClassName("setback-delete")[0];
      deletebutton.id = "sbDelete" + index;
      deletebuttonForm = deletebutton.firstElementChild;
      deletebuttonForm.id = "sbDeleteButton" + index;
      deletebuttonForm.name = "sbDeleteButton-" + id + "-" + command + "-" + index;
      if (timemodal) {
        addtimemodal(setbackStartTimeForm, setbackStopTimeForm);
      }
    }
    /**
    * Get the closest matching element up the DOM tree.
    * @private
    * @param  {Element} elem     Starting element
    * @param  {String}  selector Selector to match against
    * @return {Boolean|Element}  Returns null if not match found
    */
    var getClosest = function ( elem, selector ) {
        // Element.matches() polyfill
        if (!Element.prototype.matches) {
            Element.prototype.matches =
                Element.prototype.matchesSelector ||
                Element.prototype.mozMatchesSelector ||
                Element.prototype.msMatchesSelector ||
                Element.prototype.oMatchesSelector ||
                Element.prototype.webkitMatchesSelector ||
                function(s) {
                    var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                        i = matches.length;
                    while (--i >= 0 && matches.item(i) !== this) {}
                    return i > -1;
                };
        }
        // Get closest match
        for ( ; elem && elem !== document; elem = elem.parentNode ) {
            if ( elem.matches( selector ) ) return elem;
        }
        return null;
    };
    //This adds a modal to easily select Start time and Stop time input.
    function addtimemodal(starttime, stoptime){
      startname = $(starttime).attr("name");
      stopname = $(stoptime).attr("name");
      $(starttime).flatpickr({
          altFormat: "h:i:s K",
          altInput:true, 
          enableTime: true,
          noCalendar: true,
          enableSeconds: true,
          dateFormat: "H:i:s",
          minuteIncrement: 1,
          allowInput: true,
          // disableMobile: "true",
      });
      $(stoptime).flatpickr({
        altFormat: "h:i:s K",
        altInput:true, 
        enableTime: true,
        noCalendar: true,
        enableSeconds: true,
        dateFormat: "H:i:s",
        minuteIncrement: 1,
        allowInput: true
        // disableMobile: "true"
      });
    }
   </script>
@stop
