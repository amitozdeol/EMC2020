<?php $title="System Status"; ?>

<?php
  $removeviewport = 1;
  $set_sensor_tour = TRUE;
  $set_control_tour = TRUE;
?>
<style type="text/css">
  /* to display the boiler background picture*/
  @foreach($backgroundImagePaths as $key=>$backgroundImagePath)
    #content-{!!$key!!} .view_parent_furnace_boiler {
      background-repeat: no-repeat;
      margin: auto;
      position: relative;
      width: auto;
      background-size:cover;
      background-attachment: local;
      max-width: 100%;
      max-height: 100%;
      display: inline-block;
      background: white;
    }
  @endforeach

  .device-container {
    position: absolute;
    cursor: move;
    display: inline-block;
  }
  .btn-inhibited{
    background-color:#aaa;
    color:white;
  }

  .dropdown{
    text-align: left;
  }
  .row2{
    position: relative;
    background-size: cover;
    display: inline-block;
  }
  .form{
    text-align:left;
    border-image-width: 5% 2em 10% auto;
    border: 3px solid #ccc;
    background:radial-gradient(circle, black, white);
  }
  .align {
    float: right;
  }
  .alignLeft{
    float: left;
  }
  .right-care {
      border-bottom: 5px solid transparent;
      border-top: 5px solid transparent;
      border-left: 10px solid #ccc;
      display: inline-block;
      margin-bottom: 8px;
      height: 0;
      vertical-align: middle;
      width: 0;
      background: black;
  }
  .status-transition{
    -webkit-transition: all 2s ease-in-out;
    transition: all 2s ease-in-out;
  }
  .on-off-button{
    text-align: center;
    background: none;
    margin-right: 5px;
    margin-top: 5px;
    font-size: 30px;
    border-style: solid;
    border-color: white;
    border-width: 1px;
    width: 120px;
    height: 70px;
    padding-top:15px;
  }
  .on-off-button:hover,
  .on-off-button:focus,
  .on-off-button.focus {
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    background-color: #222222;
    box-shadow: 4px 4px 3px green;
  }
  a:hover,
  a:focus,
  a.focus {
    text-decoration: none;
  }
  .dmi-style{
    border-radius: 40px;
    background-color: rgba(50, 50, 65, 0.8);
    border-width: 4px;
    min-height: 40px;
    padding-top: 10px;
    padding-left: 15px;
    padding-right:15px;
    padding-bottom: 0px;
  }
  .control-device{
    color: rgb(255,255,255);
    /* font-size: 24pt;
    min-width:100px;
    min-height:50px; */
    font-size: 1.5rem;
    padding: 4px;
    border-radius: 10px;
    background-color: rgba(35, 80, 109, 0.95);
    border-width: 0px;
    box-shadow: 0px 0px 4px 4px rgb(50,50,65);
  }
  .control-device:hover,
  .control-device:focus,
  .control-device.focus{
    /* font-size: 25pt; */
    font-size: 1.6rem;
    background-color: rgba(55, 100, 129, 0.99);
    cursor:pointer;
    text-shadow: 0 0 5px #FFFFFF, 0 0 3px #0000FF;
    box-shadow: 0px 0px 4px 5px rgb(50,50,65);
  }
  .off-season-control-device{
    background-color: rgba(115, 160, 189, 0.85);
    box-shadow: 0px 0px 4px 4px rgb(100,100,115);
  }
  .furnace-tabs{
    min-height: 60px;
    padding: 5px;
  }
  @media screen and (max-width: 1080px){
    .furnace-tabs{
      min-height: 100px;
    }
  }
</style>

@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="display: table; width: 100%; padding: 30px 10px; position: relative;">
    <div class="auto-refresh hidden"></div>
      @if( Route::currentRouteName() !== 'touchscreen.system' )
        {{-- <div class="col-xs-12 col-sm-10 col-md-4 page-nav" style="cursor: pointer;" onclick="window.location='{!!$back_url!!}';" data-toggle="tooltip" data-placement="right" title="{!!$back_message!!}">
          &#10140;
        </div> --}}
      @endif
      
      <br>
      <!--<span class="js-5-min-refresh"></span>-->
    <!--=============================================================================================================-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="col-xs-12" style="min-height:800px; padding: 0;">
      <?php
        $mpotime="15";
        $arrayX = (array)$dashboard_maps;
        foreach( $arrayX as $key => $value ){
          if(empty($value)){
            unset( $arrayX[$key] );
          }
        }
        if(empty($arrayX)){
          if(Auth::check()){
            if(in_array(Auth::user()->auth_role,[7,8])){
              echo '<div class="alert alert-info alert-dismissible" role="alert" style="text-align:center;">Click on the Return to Edit Page to customize this page.</div>';
            }else{
              echo '<div class="alert alert-info alert-dismissible" role="alert" style="text-align:center;">This page has not been customized yet. Please contact your system administrator for information on setting up your system.</div>';
            }
          }
        }
      ?>
      @if(Auth::check())
        @if(in_array(Auth::user()->auth_role,[7,8]))
          <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4 btn btn-primary" style="cursor: pointer;margin-bottom:20px;" onclick="window.location='{!!URL::route('dashboardmaps.edit', [$thisBldg->id, $thisSystem->id])!!}';">
            Go To Edit Page
          </div>
        @endif
      @endif
      <div class="row"></div>

      <?php
        static $inhibted_color = "#444444";
        static $active_input_color = "white";
        static $inactive_input_color = "#444444";
        static $active_above_setpoint_bk_color = "rgba(0, 153, 0, 0.8)";
        static $active_below_setpoint_bk_color = "rgba(0, 0, 153, 0.8)";
        static $inactive_bk_color = "rgba(200, 0, 0, 0.3)";
        static $on_color = "rgb(180, 255, 200)";
        static $off_color = "rgb(170, 205 , 235)";

        $inc=0;
      ?>
      <div class="col-xs-12 emc-tab"></div>
    <!--///////////////////////////////// ZONE TABS ///////////////////////////////// -->
      <?php
      $inc = 0;
      $systemZones = array();
      $sysZones = array();
      foreach($DashboardMapItems as $dm){
        $systemZones[$dm->map_id] = $dm->printLabel;
        $inc++;
      }
      $systemZones = array_unique($systemZones,SORT_STRING);
      $numSysZones = sizeof($systemZones);
      $i = 0;
      ?>
      <ul id="myTab" class="myTab nav nav-tabs">
        @foreach($dashboard_maps as $key=>$dashboard_map)
          <li class="<?php if($key==0) echo"active";?>">
            <a id="tab-{!!$dashboard_map['id']!!}" class="@if($set_sensor_tour)furnace_sensor_tour @endif" role="button" href="#content-{!!$key!!}" data-toggle="tab">
              {!!$dashboard_map['tab_names']!!}
            </a>
          </li>
          <?php $i++; $set_sensor_tour=FALSE;?>
        @endforeach
      </ul>
      <!--///////////////////////////////// END ZONE TABS ///////////////////////////////// -->



      <!-- ////////////////////////////////////////////////////////////////////////////////// MAPS /////////////////////////// -->
      <div id="myTabContent" class="myTabContent tab-content row" style="padding: 5px;">
        <?php $inc = 0; $set_sensor_tour=TRUE;?>
            @foreach($dashboard_maps as $key=>$map)
              @if ($key == 0)
                <div class="col-xs-12 tab-pane fade in active" id="content-{!!$key!!}">
              @else
                <div role="tabpanel" class="col-xs-12 tab-pane fade" id="content-{!!$key!!}">
              @endif
                  <div style="text-align:center" class="col-xs-12 col-sm-8">
                    <div class='view_parent_furnace_boiler' id="picture_container" style="border: 1px solid #AAAACC; box-shadow: 0px 0px 2px 3px rgba(0, 0, 0, 0.7); margin-top:15px;">
                      <img src="{!! asset('images/backgroundImage/'.$map->background_image)!!}" >

                        <!-- the inputs of the devices table -->
                        @foreach($apartments as $apartment)
                          @if($map->id == $apartment['map_id'])
                            <?php
                              $color='';
                              $voltage_img='images/battery_yellow_charging_bk.png';
                              $occupancy_img='images/greybutton-bk.png';
                              $humidity_img='images/humidity_grey_small.png';
                              $digital_img='images/redbutton-bk.png';
                              $light_img='images/lightbulboff.png';
                              $val = '';
                              $overdue = '<br>';

                              if($apartment['inhibited'] == 1){
                                $val = 'inhibited';
                                $apartment['currentVal'] = 'Inhibited';
                                $color = 'images/temp_gray.png';
                                $voltage_img = 'images/battery_black_generic_bk.png';
                                $FlowAndPressure = 'images/pressure_gray.png';
                              }elseif(($apartment['name'] == 'Burner Fault') && ($apartment['currentVal'] == 0)){
                                $val = 'inhibited';
                              }elseif(($apartment['name'] == 'Burner Fault') && ($apartment['currentVal'] == 1)){
                                $val = 'danger';
                              }elseif(($apartment['tag'] == 'Temperature') && ($apartment['currentVal'] < $apartment['low'])){
                                $val = 'info';
                                $color = 'images/temp_blue.png';
                              }elseif(($apartment['name'] == 'Burner On') && ($apartment['currentVal'] == 0)){
                                $val = 'inhibited';
                              }elseif(($apartment['name'] == 'Burner On') && ($apartment['currentVal'] == 1)){
                                $val = 'success';
                              }else if(($apartment['tag'] === 'Digital') && ($apartment['currentVal'] == 0)){
                                $digital_img = 'images/greybutton-bk.png';
                              }else if(($apartment['tag'] === 'Light') && ($apartment['currentVal'] > $apartment['setpoints'])){
                                $light_img = 'images/lightbulbon.png';
                              }else{
                                /*alarm state based algorithm ----  alarm state/severity is 0 or 1 or 2. Change the necessary color*/
                                switch($apartment['state']){
                                  case 0:
                                    $val='success';
                                    $color = 'images/temp_green.png';
                                    $FlowAndPressure = 'images/pressure_green.png';
                                    $voltage_img = 'images/battery_green_bk.png';
                                    $humidity_img= 'images/humidity_blue_small.png';
                                    $apartment['state'] = "ON";
                                    break;
                                  case 1:
                                    $val='warning';
                                    $color = 'images/temp_orange.png';
                                    $FlowAndPressure = 'images/pressure_orange.png';
                                    $voltage_img = 'images/battery_orange_bk.png';
                                    $humidity_img= 'images/humidity_orange_small.png';
                                    $apartment['state'] = "OFF";
                                    break;
                                  case 2:
                                    $val='danger';
                                    $color = 'images/temp_red.png';
                                    $FlowAndPressure = 'images/pressure_red.png';
                                    $voltage_img = 'images/battery_red_bk.png';
                                    $humidity_img= 'images/humidity_red_small.png';
                                    $apartment['state'] = "OFF";
                                    break;
                                }
                                if($apartment['product_id'] === 'Z2'){
                                  if($apartment['tag'] === 'Voltage'){
                                    $voltage_img = 'images/battery_blue_plug_bk.png';
                                    if($apartment['overdue'] == 'YES'){
                                      $voltage_img = 'images/battery_red_empty_bk.png';
                                    }
                                  }else if($apartment['tag'] === 'Occupancy'){
                                    if($apartment['status'] == 1){
                                      $occupancy_img = 'images/greenbutton-bk.png';
                                    }
                                  }
                                }
                              }
                            ?>
                            <div class="@if($set_sensor_tour) furnace_sensor_tour @endif device-container" id="{!!$apartment['device_id']!!},{!!$apartment['command']!!},{!!$apartment['map_id']!!}" name="dmi-{!!$apartment['id']!!}" onclick="dmi_info(this)" style="left: {!!$apartment['x-pos']!!}%; top: {!!$apartment['y-pos']!!}%; cursor:pointer;" data-toggle="tooltip" title="{!!$apartment['name']!!}<br>
                            @if($apartment['tag'] === 'Digital')
                              @if($apartment['currentVal'] == 0)
                                OFF
                              @else
                                ON
                              @endif
                            @else
                              {!!round($apartment['currentVal'],3);!!}&nbsp;{!!$apartment['units']!!}
                            @endif
                            ">
                            <?php $set_sensor_tour=FALSE; ?>
                            @if($apartment['tag'] == 'Temperature')
                              {!!HTML::image($color)!!}
                            @elseif($apartment['tag']== 'Voltage')
                              {!!HTML::image($voltage_img)!!}
                            @elseif($apartment['tag']== 'Humidity')
                              {!!HTML::image($humidity_img)!!}
                            @elseif(($apartment['tag']== 'Flow') || ($apartment['tag']== 'Pressure') || ($apartment['tag']== 'Pressure Differential'))
                              {!!HTML::image($FlowAndPressure)!!}
                            @elseif($apartment['tag']== 'Occupancy')
                              {!!HTML::image($occupancy_img)!!}
                            @elseif($apartment['tag']=='Digital')
                              {!!HTML::image($digital_img)!!}
                            @elseif($apartment['tag']=='Low Battery')
                              {!!HTML::image($voltage_img)!!}
                            @elseif($apartment['tag']=='Light')
                              {!!HTML::image($light_img)!!}
                            @else
                              {!!HTML::image('images/caution.png')!!}
                            @endif

                            @if(($apartment['name'] == 'Burner On') && ($apartment['currentVal'] == 0))
                              {!!HTML::image('images/graybutton-bk.png')!!}
                              {!! $apartment['currentVal'] = '';!!}
                            @endif
                            @if(($apartment['name'] == 'Burner On') && ($apartment['currentVal'] == 1))
                              {!!HTML::image('images/greenbutton-bk.png')!!}
                              {!! $apartment['currentVal'] = '';!!}
                            @endif
                            @if(($apartment['name'] == 'Burner Fault') && ($apartment['currentVal'] == 0))
                              {!!HTML::image('images/graybutton-bk.png')!!}
                              {!! $apartment['currentVal'] = '';!!}
                            @endif
                            @if(($apartment['name'] == 'Burner Fault') && ($apartment['currentVal'] == 1))
                              {!!HTML::image('images/redbutton-bk.png')!!}
                              {!! $apartment['currentVal'] = '';!!}
                            @endif
                            @if($apartment['name']== 'Smoke')
                              {!! $apartment['currentVal']= '';!!}
                            @endif

                            @if($apartment['overdue'] === 'YES')
                              <span style="margin-left: -12px; ">{!!HTML::image('images/stop.png')!!}</span>
                            @endif
                          </div>
                        @endif
                      @endforeach

                  <!-- ======================================================================== OUTPUTS =========================================================================================== -->
                  @foreach($DashboardMapItems as $DashboardMapItem)
                    @if($map->id == $DashboardMapItem['map_id'])
                      @if($DashboardMapItem['label'] == 'Tag')
                        <!-- tag and Season -->
                        <div class="device-container" id="{!!$DashboardMapItem['device_id']!!},{!!$DashboardMapItem['command']!!},{!!$DashboardMapItem['map_id']!!}" name="dmi-{!!$DashboardMapItem['id']!!}" onclick="dmi_out(this)" style="left: {!!$DashboardMapItem['x_position']!!}%; top: {!!$DashboardMapItem['y_position']!!}%; cursor: pointer;">
                          <a class="btn btn-#fff" style="padding-bottom: 0px; padding-top: 0px; color:#428bca; border-radius: 100px; ">
                            <h3>
                              <b>
                                {!!strtoupper($DashboardMapItem->printLabel)!!}
                              </b>
                            </h3>
                          </a>
                        </div>
                      @endif
                    @endif
                    @if($map->id == $DashboardMapItem['map_id'])
                      @if($DashboardMapItem['label'] == 'Season')
                        <?php
                          if($thisSystem->season_mode == '1') {
                            $season = 'Summer<br>Mode';
                            $seasonPicture = 'images/summer.png';
                          }elseif($thisSystem->season_mode == '0'){
                            $season = 'Winter<br>Mode';
                            $seasonPicture = 'images/winter.png';
                          }else{
                            $season = '';
                            $seasonPicture = 'images/summer.png';
                          }
                        ?>
                        <div class="device-container" id="{!!$DashboardMapItem['device_id']!!},{!!$DashboardMapItem['command']!!},{!!$DashboardMapItem['map_id']!!}" name="dmi-{!!$DashboardMapItem['id']!!}" onclick="dmi_out(this)"     style="left: {!!$DashboardMapItem['x_position']!!}%; top: {!!$DashboardMapItem['y_position']!!}%; cursor: default;">
                          <a class="btn" style="padding-bottom: 0px; padding-top: 0px; color:#428bca; border-radius: 100px; " data-toggle="tooltip" title="<b>Status</b>&nbsp{!!'On'!!}" data-placement="bottom">
                            {!!HTML::image($seasonPicture)!!}
                            <FONT FACE = "desdemona">
                              <h4>
                                <b>
                                  {!!strtoupper($season)!!}
                                </b>
                              </h4>
                            </FONT>
                          </a>
                        </div>
                      @endif
                    @endif
                  @endforeach
                  <?php $set_control_tour = TRUE; ?>
                  @foreach($dashboard_map_items_outputs as $kkg=>$dashboard_map_items_output)
                    @if($map->id == $dashboard_map_items_output['map_id'])
                      <?php
                        $out_dmi_font_color = ($dashboard_map_items_output->current_state == 1)?$on_color:$off_color;
                        $offstyle = ($dashboard_map_items_output->season != $thisSystem->season_mode && $dashboard_map_items_output->season != '2')?"off-season-control-device":"";
                      ?>
                      <div class="@if($set_control_tour)furnace_control_tour @endif device-container control-device {!!$offstyle!!}" id="{!!$dashboard_map_items_output['device_id']!!},{!!$dashboard_map_items_output['command']!!},{!!$dashboard_map_items_output['map_id']!!}" name="  dmi-{!!$dashboard_map_items_output['dashboardID']!!}" onclick="dmi_out(this)" style="left:{!!$dashboard_map_items_output['x-pos']!!}%; top:{!!$dashboard_map_items_output['y-pos']!!}%; cursor: pointer; color:{!!$out_dmi_font_color!!}; ">
                        {!!$dashboard_map_items_output->device_label!!}
                      </div>
                      <?php $set_control_tour = FALSE; ?>
                    @endif
                  @endforeach
                </div>
              </div>
              <!-- ////////////////////////////////////////////////////////////////////////////////// END MAP /////////////////////////// -->

            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ INFO BAR ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
              <div class="col-xs-12 col-sm-4 sys-stat-info" style="padding:0px; box-shadow: 0px 0px 2px 3px rgba(0, 0, 0, 0.7); margin-top:20px;">
                  <!-- INFO BAR =================================== OUTPUTS =================================================-->
                  <?php
                    $overdue_red = "#772222";
                    $control_alarm_codes = array(36,37,38,39,40,41,45,46,47,49,50,51);/*These alarm codes are relevant to our output devices*/
                    $key = $map->id;
                  ?>

                  <?php $inorout = ($inc==0)?"in":""; ?>
                  <div id="zonelist-{!!$key!!}" class="col-xs-12 container-fluid collapse {!!$inorout!!}" style="text-align: center;text-shadow:0px 0px 10px #ffffff;">
                    <big><big>
                      {!!$map->label!!}
                    </big></big>
                  </div>
                  <?php $set_control_tour = TRUE; ?>
                  @foreach ($dashboard_map_items_outputs as $dmio)
                    @if ($dmio->map_id == $key)
                      <?php $inorout = ($inc==0)?"in":""; ?>
                      <div id="dmi-out-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}" class="container-fluid collapse {!!$inorout!!}" style=" text-align: center; " href="#{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}">
                        <?php $inc++; ?>
                        <span class="@if($set_control_tour)furnace_control_tour @endif col-xs-12" style="background-color:#29516D; color: white; text-shadow: none;" title="ID: {!!$dmio->id!!}">
                          <big>
                            {!!$dmio->device_label!!}
                          </big>
                          <br>
                          <small>{!!$dmio->device_function!!}</small>
                          <br>
                        </span>
                        <span class="col-xs-12 border_blue_white" style="margin-bottom:5px; padding-bottom:10px; text-shadow:none;">
                          <?php
                          $outstate = ($dmio->current_state == 1)?"ON":"OFF";
                          $outcolor = ($dmio->current_state == 1)?$on_color:$off_color;
                          $notoutstate = ($dmio->current_state == 1)?"OFF":"ON";
                          $seasoncolor = ($dmio->current_state == 1)?"#ee3300":"blue";
                          ?>
                          @if($dmio->season != $thisSystem->season_mode && $dmio->season != '2')
                            <hr>
                            <span style="color:{!!$seasoncolor!!}; text-shadow:0px 0px 3px #dddddd; ">
                                OFF-SEASON
                            </span>
                            <hr>
                            <span class="@if($set_control_tour)furnace_control_tour @endif " style="color:{!!$outcolor!!}; text-shadow:0px 0px 10px #222200;">
                              <big>

                                {!!$outstate!!}
                              </big>
                            </span>
                            
                              <br>
                              <span class="@if($set_control_tour)furnace_control_tour @endif ">
                                Last Report:<br>{!!$dmio->datetime!!}
                              </span>
                            
                            <br>
                          @else
                            <span class="@if($set_control_tour)furnace_control_tour @endif " style="color:{!!$outcolor!!}; text-shadow:0px 0px 10px #111100;">
                              <big><big><big>
                                {!!$outstate!!}
                              </big></big></big>
                            </span>
                            <br>
                            <span class="@if($set_control_tour)furnace_control_tour @endif ">
                              
                                {!!$dmio->datetime!!}<br>
                              
                            </span>
                          @endif
                          <div class="panel panel-primary" style="overflow: hidden;">
                          <!-- CHECK INPUTS -->
                          <a id="oi-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}" role="button" class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 btn btn-info panel-title" title="Show the reason(s) for this output's state" style="text-shadow:none;" data-toggle="collapse" data-parent="#{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}" href="#inputs-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}" >
                            Algorithm Info
                          </a>
                          <div id="inputs-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}" class="col-xs-12 container-fluid collapse panel-body" style="text-align:center;padding:0px;">

                            <?php
                              $ias; /*input above setpoint*/
                              $ibs; /*input below setpoint*/
                              $iid; /*inactive input device*/

                              foreach($output_causes as $output_key => $output_cause){
                                if(($dmio->id == $output_cause['id']) && ($dmio->command == $output_cause['command'])){/*Belongs to this output device*/

                                  if( $output_cause['input']['active'] == 1 ){
                                    if( $output_cause['input']['state'] == 1 ){
                                      $ias[] = $output_key;
                                    }else{
                                      $ibs[] = $output_key;
                                    }
                                  }else{
                                    $iid[] = $output_key;
                                  }
                                }
                              }
                              $activeseason;
                              if($dmio->season == 0){
                                $activeseason = "Winter";
                              }else if ($dmio->season == 1){
                                $activeseason = "Summer";
                              }else{
                                $activeseason = "All";
                              }
                              $alg_votes = (isset($ias))?count($ias):0;
                            ?>
                            <!-- Why is the current_state what it is... -->
                          @if(FALSE !== array_search($dmio->alarm_index,$control_alarm_codes) )
                            <div class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 border_blue_white" style="border-width:2px; background-color: #000022; color:white;" title="Overriding Voting">
                              {!!$Alarm_Codes[$dmio->alarm_index]['description']!!}<br>
                            </div>
                          @elseif($dmio->function != 'Virtual')
                            <div class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 border_blue_white" style="border-width:2px; background-color: #000022; color:white;" title="Overriding Voting">
                              Key Switch: Auto
                            </div>
                          @endif
                            <!-- Display active season -->
                            <div class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 border_blue_white" style="border-width:2px; background-color: #224466; color:white;" title="Active Season">
                              Active Season: {!!$activeseason!!}<br>
                            </div>
                            <!-- Display required votes -->
                            <div class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 border_blue_white" style="border-width:2px; background-color: #000022; color:white;" title="Showing input devices above setpoint versus required devices above setpoint for output state change">
                              Votes: {!!$alg_votes!!}/{!!$dmio->min_required_inputs!!}
                            </div>
                            <span class="@if($set_control_tour)furnace_control_tour @endif ">
                              <div class="col-xs-12 border_blue_white" style="border-width:2px; background-color:{!!$active_below_setpoint_bk_color!!}; color:{!!$active_input_color!!}; text-shadow:none;padding:3px;">
                                <small>
                                  Below Setpoint
                                </small>
                                <br>
                                @if(isset($ias))
                                  @foreach($ias as $output_key)
                                    <div class="col-xs-12 border_blue_white">
                                      {!!$output_causes[$output_key]['input']['name']!!}<br>
                                    </div>
                                  @endforeach
                                @else
                                  <i>None</i><br>
                                @endif
                              </div>
                              <div class="col-xs-12 border_blue_white" style="border-width:2px; background-color:{!!$active_above_setpoint_bk_color!!}; color:{!!$active_input_color!!}; text-shadow:none;padding:3px;">
                                <small>
                                  Above Setpoint
                                </small>
                                <br>
                                @if(isset($ibs))
                                  @foreach($ibs as $output_key)
                                    <div class="col-xs-12 border_blue_white">
                                      {!!$output_causes[$output_key]['input']['name']!!}<br>
                                    </div>
                                  @endforeach
                                @else
                                  <i>None</i><br>
                                @endif
                              </div>
                              @if(isset($iid))
                                <div class="col-xs-12 border_blue_white" style="border-width:2px; background-color:{!!$inactive_bk_color!!}; text-shadow:none;padding:3px;">
                                  <small>
                                    Inactive Inputs
                                  </small>
                                  <br>
                                  @foreach($iid as $output_key)
                                    <div class="col-xs-12 border_blue_white">
                                      {!!$output_causes[$output_key]['input']['name']!!}<br>
                                    </div>
                                  @endforeach
                                </div>
                              @endif
                            <?php
                              unset($ias);
                              unset($ibs);
                              unset($iid);
                            ?>
                            </span>
                          </div>
                          </div>
                          <br>
                        </span>

                        {!! Form::open(array("role" => "form", "name"=>"instruction")) !!}
                          @if(Auth::check())<!--OVERRIDE CONTROLS-->
                            @if(in_array(Auth::user()->auth_role,[3,4,5,6,7,8]))
                              <span class="@if($set_control_tour)furnace_control_tour @endif col-xs-12 border_blue_white" style="padding: 5px;">
                                <input name="Togglestate" type="hidden" value="{!!$dmio->current_value!!}">
                                <input name="device" type="hidden" value="{!!$dmio->id!!}">
                                <input name="command" type="hidden" value="{!!$dmio->device_types_id!!}">
                                <div class="@if($set_control_tour)furnace_control_tour @endif btn-group" data-toggle="buttons" style="text-shadow:none; padding:10px;">
                                  <label class="btn btn-primary btn-lg">
                                    <input type="radio" name="Override" id="1" value="1" autocomplete="off"> ON
                                  </label>
                                  <label class="btn btn-primary btn-lg">
                                    <input type="radio" name="Override" id="0" value="0" autocomplete="off"> OFF
                                  </label>
                                </div>

                                <div class="@if($set_control_tour)furnace_control_tour @endif col-xs-12" style="padding:10px;">
                                  Bypass Time
                                  <?php
                                    if(isset($mappingOutputs[$dmio->id])) {
                                      $mpotime=$mappingOutputs[$dmio->id];
                                    }else{
                                      $mpotime=0;
                                    }
                                  ?>
                                  <div>
                                    {!! Form::select('Overridetime', array( '-1' => 'RESET', '5' => '5 Minutes', '15' => '15 Minutes','30' => '30 Minutes', '60' => '1 Hour','90' => '1.5 Hours', '120' => '2 Hours', '180' => '3 Hours', '240' => '4 Hours'), $mpotime, array('class' => 'form-control', 'style' => 'color:black;')) !!}
                                  </div>
                                </div>
                                <button class='@if($set_control_tour)furnace_control_tour @endif col-xs-12 emc-tabs' type='submit' name='Bypass' value="" data-confirm="Bypass normal algorithm operations?" style="padding:10px;">
                                  Bypass
                                </button>
                                <button class='@if($set_control_tour)furnace_control_tour @endif col-xs-12 emc-tabs' type='submit' name='Toggle' style="padding:10px;">
                                  Toggle: {!!$notoutstate!!}
                                </button>
                              </span>
                            @endif
                          @endif
                        {!!Form::close()!!}
                      </div>
                      <?php $set_control_tour = FALSE; ?>
                    @endif
                  @endforeach
                  <!-- INFO BAR =================================== END OUTPUTS =============================================-->

                  <!-- INFO BAR =================================== INPUTS ==================================================-->
                  <?php $set_sensor_tour=TRUE; ?>
                  @foreach ($apartments as $aps)
                    @if($aps['map_id'] == $key)
                      <?php $inorout = ($inc==0)?"in":""; ?>
                      <div id="dmi-info-{!!$aps['device_id']!!}-{!!$aps['command']!!}-{!!$aps['map_id']!!}" class="container-fluid collapse {!!$inorout!!}" style="">
                          <?php $inc++; ?>
                          <span class="@if($set_sensor_tour) furnace_sensor_tour @endif  col-xs-12" style="background-color:#29516D; color: white; text-shadow: none; text-align: center;">
                            <big>
                              {!!trim($aps['name']);!!}
                              <br>
                              <small>{!!$aps['tag']!!}</small>
                            </big>
                            <br>
                          </span>
                          @if($aps['inhibited'] == 1)
                          <span class="col-xs-12 border_blue_white" style=" color: {!!$inhibted_color!!};">
                            <span class="col-xs-12" style="padding:15px; text-align: center;">
                              INHIBITED<br>
                          @elseif($aps['overdue'] == 'YES')
                          <span class="col-xs-12 border_blue_white">
                            <span class="col-xs-12" style="padding:15px; text-align: center;">
                              <span style="color: {!!$overdue_red!!}">
                                OVERDUE
                                <br>
                                Last Report:
                                <br>
                                {!!$aps['last_report_time']!!}
                                <br>
                              </span>
                          @else
                          <span class="col-xs-12 border_blue_white">
                            <span class="col-xs-12" style="padding:15px; text-align: center;">
                          @endif
                            @if($aps['state'] != 0)
                              <?php $alarmcolor = ($aps['state'] == 1)?"orange":"red";  ?>
                              <span style="color: {!!$alarmcolor!!}; text-shadow: none;">
                                <b>{!!$Alarm_Codes[$aps['alarm_index']]['description']!!}</b><br>
                              </span>
                            @endif
                            <!-- CURRENT VALUE -->
                              <span class="@if($set_sensor_tour) furnace_sensor_tour @endif">
                                <big><big><b>
                                  @if($aps['tag'] === 'Digital')
                                    @if($aps['currentVal'] == 0)
                                      OFF
                                    @else
                                      ON
                                    @endif
                                  @else
                                    {!!round($aps['currentVal'],3);!!}&nbsp;{!!$aps['units']!!}
                                  @endif
                                </b></big></big>
                              </span>
                              <br>
                              @if($aps['overdue'] === 'NO')
                                <small><span title="Last Report Time">
                                  {!!str_replace("-","&#8209;",str_replace(" ","&nbsp;",$aps['last_report_time']));!!}
                                </span></small>
                                <br>
                              @endif
                            </span>
                            <!-- SETPOINT AND ALARM LEVELS -->
                            <?php $setpoint_text = ($aps['status']==='ON')?"Vote 'No'":"Vote 'Yes'"; ?>
                            @if(Auth::check())
                              <span class="@if($set_sensor_tour) furnace_sensor_tour @endif col-xs-12 border_blue_white" style="cursor: pointer; padding:5px; background-color:#DDDDDD; text-align:center;" onclick="window.location='{!!URL::route('setpointmapping.index', [$thisBldg->id, $thisSystem->id])!!}';" title="Redirect to setpoints page to adjust setpoints and alarm levels">
                            @else
                              <span class="@if($set_sensor_tour) furnace_sensor_tour @endif col-xs-12 border_blue_white" style="padding:5px; background-color:#DDDDDD; text-align:center;" >
                            @endif
                              <span class="col-xs-12" title="{!!$setpoint_text!!}">
                                Setpoint: <b>{!!round($aps['setpoints'],3);!!}&nbsp;{!!$aps['units']!!}</b>
                              </span>
                              <br>
                              <span class="col-xs-12">
                                Alarm Levels:
                              </span>
                              <br>
                              <span class="col-xs-6 col-sm-12">
                                High:&nbsp;<b>{!!str_replace("-","&#8209;",round($aps['high'],3));!!}&nbsp;{!!$aps['units']!!}</b>
                              </span>
                              <span class="col-xs-6 col-sm-12">
                                Low:&nbsp;<b>{!!str_replace("-","&#8209;",round($aps['low'],3));!!}&nbsp;{!!$aps['units']!!}</b>
                              </span>
                            </span>

                            <!-- LOCATION & DESCRIPTION & COMMENTS-->
                            <span class="@if($set_sensor_tour) furnace_sensor_tour @endif col-xs-12" style="padding:5px 0px;">
                              Location:
                              @if((isset($aps['location']))&&($aps['location'] != ""))
                                <b>{!!$aps['location']!!}</b>
                              @else
                                <i>unknown</i>
                              @endif
                              <br>
                              @if((isset($aps['desc']))&&($aps['desc'] != ""))
                                Descripton: <b>{!!$apartment['desc']!!}</b><br>
                              @endif
                              @if((isset($aps['comments']))&&($aps['comments'] != ""))
                                Comments: <b>{!!$aps['comments']!!}</b><br>
                              @endif
                            </span>
                          </span>
                      </div>

                    @endif
                    <?php $set_sensor_tour=FALSE;?>
                  @endforeach
                  <!-- INFO BAR =================================== END INPUTS ==================================================-->

              </div>
            </div>
            @endforeach

        <!-- ////////////////////////////////////////////////////////////////////////////////// END TAB CONTENT /////////////////////////// -->



      <!--===================================================================================================================================-->
      <!--===================================================================================================================================-->
      <!--===================================================================================================================================-->
      <!--===================================================================================================================================-->
      <!--===================================================================================================================================-->
      <!--===================================================================================================================================-->
        <!-- ~~ END INFO BAR ~~ -->
        </div>
      </div><!--end my tab content -->
        <!-- </div> -->


    <div class="col-xs-12 typical-height"></div>


    <!--===================================================================================================================================-->
    <!--===================================================================================================================================-->
    <!--===================================================================================================================================-->
    <!--===================================================================================================================================-->
    <!--===================================================================================================================================-->
    <!--===================================================================================================================================-->

    <?php
      function StatusLogicEncode($S, $I, $R) {
        // Retired overides all, then inhibit overrides status active, if none the undefined
        // Valid States 0 - uncommisioned, 4 - active 5,7 Retired, 6 -  inhibited
        $SV = $R + ($I << 1) + ($S << 2);

        return $SV;
      }

      function StatusLogicDecode($SV) {
        switch ($SV) {
          case 4:
            $statusd[0] = "Active";
            $statusd[1] = "#1ccc94";
            break;
          case 6:
            $statusd[0] = "Inhibited";
            $statusd[1] = "#CCCC1C";
            break;
          case 5:
          case 7:
            $statusd[0] = "Retired";
            $statusd[1] = "#1ccc94";
            break;
          Default:
            $statusd[0] = "Not Defined";
            $statusd[1] = "#94cc1c";
          }

          return $statusd;
      }
    ?>



    <?php
      if (isset($_GET['mode'])) {
          $mode = $_GET['mode'];
      } else {
          $mode = 'both';
      }
      $mode = "both";
    ?>
  </main>
    <!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
    <?
    //Cache control
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
  <script type="text/javascript">
    //Floating help button
    $(document).ready(function(){
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="furnace_sensor_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Your Sensors\
        </a>\
        <a href="javascript:void(0);" class="furnace_control_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Your Controls\
        </a>'
      );
      $('.furnace-tabs-group').each(function(){
        var highestTab = 0;
        $('.furnace-tabs',this).each(function(){
          if($(this).height() > highestTab){
            highestTab = $(this).height();
          }
        });
        $('.furnace-tabs',this).height(highestTab);
      });
    });

    // function hideshow(which){
    //   if (!document.getElementById){
    //     return
    //   }
    //   if (which.style.display=="block"){
    //     which.style.display="none"
    //   }else{
    //     which.style.display="block"
    //   }
    // }

    $('#myTab').tabCollapse();
    /*Show zonelist- info when new tab is selected*/
    @foreach($dashboard_maps as $key=>$dashboard_map)
      $("#tab-{!!$dashboard_map['id']!!}").click(function(){
        @foreach ($systemZones as $key=>$sz)
          $("#zonelist-{!!$key!!}:visible").collapse('hide');
        @endforeach
        $("#zonelist-{!!$dashboard_map['id']!!}").collapse('show');
        close_dmi_out();
        close_dmi_info();
        <?php $count = 0;?>
        @foreach ($dashboard_map_items_outputs as $dmio)
          @if ( ($dmio->map_id == $dashboard_map['id']) && ($count == 0) )
            $("#dmi-out-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}:hidden").collapse('show');
            <?php $count++;?>
          @endif
        @endforeach

        @foreach ($apartments as $aps)
          @if ( ($aps['map_id'] == $dashboard_map['id']) && ($count == 0) )
            $("#dmi-info-{!!$aps['device_id']!!}-{!!$aps['command']!!}-{!!$aps['map_id']!!}:hidden").collapse('show');
            <?php $count++;?>
          @endif
        @endforeach

      });
    @endforeach

  function dmi_info(elmnt){
    var elmnt_id = elmnt.id;
    var key_vals = elmnt_id.split(",");
    console.log(key_vals);
    close_dmi_info();
    close_dmi_out();
    setTimeout(function(){
      $("#dmi-info-" + key_vals[0] + "-" + key_vals[1] + "-" + key_vals[2] + ":hidden").collapse('show');
      },400
    );
  }

    function dmi_out(elmnt){

      var elmnt_id = elmnt.id;
      var key_vals = elmnt_id.split(",");
      close_dmi_out();
      close_dmi_info();
      setTimeout(function(){
          $("#dmi-out-" + key_vals[0] + "-" + key_vals[1] + "-" + key_vals[2] + ":hidden").collapse('show');
        },400
      );
    }

  function close_dmi_info(){
    @foreach ($apartments as $aps)
      $("#dmi-info-{!!$aps['device_id']!!}-{!!$aps['command']!!}-{!!$aps['map_id']!!}:visible").collapse('hide');
    @endforeach
  }

    function close_dmi_out(){
      @foreach ($dashboard_map_items_outputs as $dmio)
        $("#dmi-out-{!!$dmio->device_id!!}-{!!$dmio->command!!}-{!!$dmio->map_id!!}:visible").collapse('hide');
      @endforeach
    }

    // setTimeout(
    //   function(){
    //     clearTimeout(auto_refresh);
    //   },
    //   5000
    // );
  </script>
@stop