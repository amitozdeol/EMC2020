<?php $removeviewport = 1;
      $title="Edit Dashboard Map";      
?>

@extends('layouts.wrapper')
@section('content')
  <main id="page-content-wrapper" role="main">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <?
      //Cache control
      //Add last modified date of a file to the URL, as get parameter.
      $import_css = ['/css/building/mytabs.css'];    //add file name
      foreach ($import_css as $value) {
        $filename = public_path().$value;
        if (file_exists($filename)) {
            $appendDate = substr($value."?v=".filemtime($filename), 1);
            echo HTML::style($appendDate);
        }
      }
    ?>

    <!-- CSS -->
    <style type="text/css">
      @foreach($backgroundImagePaths as $key=>$backgroundImagePath)
        #content-{!!$key!!} .view_parent_furnace_boiler {
          background-repeat: no-repeat;
          margin: auto;
          position: relative;
          width: auto;
          background-size:cover;
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
      .form{
        position: relative;
        text-align: center;
      }

      .red {
        border-top:4px solid red;
      }
      .container {
          margin-top: 10px;
      }

      .nav-tabs > li {
          position:relative;
      }

      .nav-tabs > li > a {
          display:inline-block;
      }

      .nav-tabs > li > span {
          display:none;
          cursor:pointer;
          position:absolute;
          right: 6px;
          top: 8px;
          color: red;
      }
      .align {
        float: right;
      }
      .nav-tabs > li:hover > span {
          display: inline-block;
      }
      .control-device{
        color: rgb(255,255,255);
        font-size: 24pt;
        min-width:100px;
        min-height:50px;
        padding: 4px;
        border-radius: 10px;
        background-color: rgba(35, 80, 109, 0.95);
        border-width: 0px;
        box-shadow: 0px 0px 4px 4px rgb(50,50,65);
      }
      .control-device:hover,
      .control-device:focus,
      .control-device.focus{
        font-size: 25pt;
        background-color: rgba(55, 100, 129, 0.99);
        cursor:pointer;
        text-shadow: 0 0 5px #FFFFFF, 0 0 3px #0000FF;
        box-shadow: 0px 0px 4px 5px rgb(50,50,65);
      }
      @media screen and (max-width: 1080px){
        .furnace-tabs{
          min-height: 165px;
        }
      }
    </style>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- {!!ini_set('MAX_EXECUTION_TIME', -1);!!} --> <!--fixed the Fatal error: Maximum execution time of 300 seconds exceeded -->

    <!-- to add new element -->

    <div class="container">
      <div class="col-xs-12 align">
        <div class="col-xs-6">
          <?php $back_url = URL::route('building.dashboard', [$thisBldg->id, $thisSystem->id, $pageid->id]);?>
          <div class="page-nav" style="cursor: pointer; width:30%;" onclick="window.location='{!!$back_url!!}';">
            Back
          </div>
        </div>
        <div class="col-xs-6">
          @include('dashboard-maps.help_edit')
        </div>
      </div>
      <div class="col-xs-12 form-inline">
        <?php
        if(!isset($mapID)){
          $mapID = 0;
        }
        $previous ='previous';
        $current = 'current';
        ?>
        <!-- this section is for Available Devices -->
        <div class = "col-xs-12 row-padding dropdown form-group">
          <div><!--AVAILABLE-DEVICES/AVAILABLE-CONTROLS/SAVE-POSITIONS-->
            <div class="col-xs-4">
              <button class="btn btn-info dropdown-toggle" type="button" id="dropdownForm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Available Devices
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownForm1">
                {!! Form::open( ) !!}
                <select class="selectpicker form-group2"  style="color:black" onchange="selectionValue(this.value)" >
                  <optgroup>
                    <option>
                      Nothing selected yet
                    </option>
                    @foreach($newAvailableDevices as $k=>$newAvailableDevice)
                      @if($newAvailableDevice['name'] == 1)
                        {!!   $newAvailableDevice['name'] = "there is no Device Available"; !!}
                      @endif
                      <?php
                      $previous = $newAvailableDevice['zone'];
                      ?>
                      @if($previous != $current)
                        <option disabled="disabled"></option>
                        </optgroup>
                        <optgroup label="{!! ConvFunc::findzonename($zonenames, $newAvailableDevice['zone'])!!}">
                      @endif
                      <option value="{!!$newAvailableDevice['id']!!},{!!$newAvailableDevice['command']!!},{!! $newAvailableDevice['name']!!}"  >
                        Zone: {!! ConvFunc::findzonename($zonenames, $newAvailableDevice['zone'])!!} -> Name:{!! $newAvailableDevice['name']!!} --- ID:{!!$newAvailableDevice['id']!!} ---  {!!$newAvailableDevice['commandString']!!} sensor
                      </option>
                      <?php
                      $current = $newAvailableDevice['zone'];
                      ?>
                    @endforeach
                    {!!Form::hidden('id','',['id'=>'AddElementid','style'=>'color:black'] ) !!}
                    {!!Form::hidden('command','',['id'=>'AddElementCommand','style'=>'color:black'] ) !!}
                    {!!Form::hidden('label','',['id'=>'AddElementName','style'=>'color:black'] ) !!}
                    {!!Form::hidden('x_position','00',['id'=>'x_position','style'=>'color:black'] ) !!}
                    {!!Form::hidden('y_position','00',['id'=>'y_position','style'=>'color:black'] ) !!}
                    {!!Form::hidden('map_idForNew', $mapID ,['id'=>'map_idForNew','style'=>'color:black'] ) !!}
                    {!!Form::hidden('icon', ' ' ,['id'=>'map_idForNew'] ) !!}
                  </optgroup>
                </select>
                {!!Form::submit('Create the Element', ['class'=>'hello',  'value'=>'SavetheDevice','name'=>'SavetheDevice' ,'style'=>'color:black']) !!}
                {!!Form::submit('Add Title',['value'=>'AddTitle', 'name'=>'AddTitle' ,'style'=>'color:black'])!!}
                {!!Form::submit('Add Season',['value'=>'AddSeason', 'name'=>'AddSeason' ,'style'=>'color:black'])!!}
                {!!  Form::close()  !!}
              </ul>
            </div>
            <!-- Available Devices End -->

            <?php
            $previous2 ='previous';
            $current2 = 'current';
            ?>

            <!-- this section is for available controllers -->
            <div class = "col-xs-4 dropdown form-group">
              <button class="btn btn-info dropdown-toggle" type="button" id="dropdownForm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Available Controls
                <span class="caret"></span>
              </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownForm1">
                {!! Form::open() !!}
                <select class="selectpicker form-group2"  style="color:black" onchange="selectionValue(this.value)" >
                  <optgroup>
                    <option>
                      Nothing selected yet
                    </option>

                    @foreach($availableDevices as $availableDevice)
                        {!!$outputExist = 0; !!}
                      @if($availableDevice['device_io'] == 'output')
                          {!!$outputExist = 1; !!}
                      @endif
                    @endforeach

                    @if($outputExist = 1)
                      @foreach($availableDevices as $availableDevice)
                        @if($availableDevice['name'] == Null)
                          {!!   $availableDevice['name'] = "Name is not available"; !!}
                        @endif
                        @if($availableDevice['device_io'] == 'output')
                          <?php $previous2 = $newAvailableDevice['zone']; ?>
                          @if($previous2 != $current2)
                            <option disabled="disabled">
                            </option>
                            </optgroup>
                            <optgroup label="{!! ConvFunc::findzonename($zonenames, $availableDevice['zone'])!!}"><br>
                          @endif
                          <option value="{!!$availableDevice['id']!!},{!!$availableDevice['command']!!},{!! $availableDevice['name']!!}"  >
                            Zone: {!! ConvFunc::findzonename($zonenames, $availableDevice['zone'])!!} -> Name:{!! $availableDevice['name']!!} --- ID:{!!$availableDevice['id']!!} ---   {!!$availableDevice['commandString']!!} Control
                          </option>
                        @endif
                        </option>
                        <?php  $current2 = $availableDevice['zone']; ?>
                      @endforeach
                      {!!Form::hidden('id','',['id'=>'AddElementid1','style'=>'color:black'] ) !!}
                      {!!Form::hidden('command','',['id'=>'AddElementCommand1','style'=>'color:black'] ) !!}
                      {!!Form::hidden('label','',['id'=>'AddElementName1','style'=>'color:black'] ) !!}
                      {!!Form::hidden('x_position','00',['id'=>'x_position','style'=>'color:black'] ) !!}
                      {!!Form::hidden('y_position','00',['id'=>'y_position','style'=>'color:black'] ) !!}
                      {!!Form::hidden('icon', ' ' ,['id'=>'icon'] ) !!}
                      {!!Form::hidden('map_idForNew1',$mapID,['id'=>'map_idForNew1','style'=>'color:black'] ) !!}
                    </optgroup>
                  </select>
                  {!!Form::submit('Create the Element', ['value'=>'SavetheControl','name'=>'SavetheControl' ,'style'=>'color:black']) !!}
                @else
                  <option>
                    There is No Controls exist for this System
                  </option>
                @endif
                {!!Form::submit('Add Title',['value'=>'AddTitle', 'name'=>'AddTitle' ,'style'=>'color:black'])!!}
                {!!Form::submit('Add Season',['value'=>'AddSeason', 'name'=>'AddSeason' ,'style'=>'color:black'])!!}
              </ul>
            </div>
            {!!Form::close()!!}
            <!-- available Controls end -->

            <!-- Save Positions -->
            <div class="col-xs-4 form form-group">
              {!!Form::open()!!}
              @foreach($apartments as $apartment)
                {!! Form::hidden('dmi-'.$apartment['id'].'-x_position', $apartment['x-pos'],      ['id'=>'dmi-'.$apartment['id'].'-x_position', 'style'=>'color:black'])!!}
                {!! Form::hidden('dmi-'.$apartment['id'].'-y_position', $apartment['y-pos'],      ['id'=>'dmi-'.$apartment['id'].'-y_position', 'style'=>'color:black'])!!}
              @endforeach

              @foreach($dashboard_map_items_outputs as $dashboard_map_items_output)
                {!! Form::hidden('dmi-'.$dashboard_map_items_output['dashboardID'].'-x_position', $dashboard_map_items_output['x-pos'],      ['id'=>'dmi-'.$dashboard_map_items_output['dashboardID'].'-x_position', 'style'=>'color:black'])!!}
                {!! Form::hidden('dmi-'.$dashboard_map_items_output['dashboardID'].'-y_position', $dashboard_map_items_output['y-pos'],      ['id'=>'dmi-'.$dashboard_map_items_output['dashboardID'].'-y_position', 'style'=>'color:black'])!!}
              @endforeach

              @foreach($TagsAndSeasons as $TagsAndSeason)
                @if(($TagsAndSeason['label'] == 'Season') || ($TagsAndSeason['label'] == 'Tag'))
                  {!! Form::hidden('dmi-'.$TagsAndSeason['id'].'-x_position', $TagsAndSeason['x-pos'],      ['id'=>'dmi-'.$TagsAndSeason['id'].'-x_position', 'style'=>'color:black'])!!}
                  {!! Form::hidden('dmi-'.$TagsAndSeason['id'].'-y_position', $TagsAndSeason['y-pos'],      ['id'=>'dmi-'.$TagsAndSeason['id'].'-y_position', 'style'=>'color:black'])!!}
                @endif
              @endforeach

              <!-- <input type="submit" name="positionSave" value="Save New Positions" style="color:black"> -->

              {!! Form::submit('Save New Positions', ['value'=>'Save New Positions','name'=>'positionSave', 'class' => 'btn btn-info'] ) !!}
              {!!Form::close()!!}
            </div>
          </div>
          <!-- Save Position end -->
        </div>

          <!-- TITLE AREA -->
          <div class="col-xs-12 row-padding">

            <!-- tab navigations-->
            <ul id="myTab" class="myTab nav nav-tabs">
              @foreach($dashboard_maps as $key=>$dashboard_map)
                @if($key == $mapID)
                  <li role="presentation" class="active"><a href="#content-{!!$key!!}"  id="conten-{!!$key!!}"  onclick="setParentId(this.id);"  data-toggle="tab">{!!$dashboard_map['tab_names']!!} </a></li>
                @else
                  <li role="presentation"><a href="#content-{!!$key!!}" id="conten-{!!$key!!}" onclick="setParentId(this.id);" data-toggle="tab">{!!$dashboard_map['tab_names']!!} </a></li>
                @endif
              @endforeach
              <li><a href="#content-{!!$key+1!!}" class="add-contact" id="conten-{!!$key+1!!}"  onclick="setParentId((this.id));" data-toggle="tab">+ Add New Tab</a></li>

            </ul>

            <!-- image upload drop down-->
            <div class="col-xs-6 form form-group" style="text-align: left;">
              <div>
                <div class = "dropdown form-group">
                  <button class="btn btn-info dropdown-toggle" type="button" style=" padding-bottom: 8px; padding-top: 8px; border-radius: 10px; background-color: rgba(50, 50, 50, 0.7); transition: all 0.3s ease-out; border-top-width: 4px;border-bottom-width: 4px;border-right-width: 4px;border-left-width: 4px;" id="dropdownForm1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Image Upload
                    <span class="caret red" style="border-left-width: 5px; border-right-width: 5px; border-bottom-width: 4px;"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownForm1" style="width:300px; padding-bottom: 0px; padding-top: 0px; border-radius: 10px; background-color: rgba(38, 154, 188, 0.56); transition: all 0.3s ease-out; border-top-width: 4px;border-bottom-width: 4px;border-right-width: 4px;border-left-width: 4px;">
                      {!! Form::open(array('id'=>'uploadForm',  'files'=>true)) !!}
                      <br>{!! Form::label('image', 'Upload Image',['style'=>'color:black']) !!}<br>
                      {!! Form::label('image', 'Files must be in .png format',['style'=>'color:red']) !!}<br>
                      {!! Form::label('image', 'Files may not exceed 2MB',['style'=>'color:red']) !!}
                      {!! Form::file('image',  ['id'=>'img1',  'style'=>'color:blue']) !!}<br>


                      {!! Form::hidden('map_idForNew2',$mapID,['id'=>'map_idForNew2','style'=>'color:black'] ) !!}

                      <div class ="ss-item-required">

                        {!! Form::label('tabName', 'Please enter a Tab Name here', ['style'=>'color:black'])!!}<p>
                        {!! Form::text('tabName', '' ,['id'=>'tabName', 'style'=>'color:black', 'placeholder'=>'Tab Name'])!!}<br><br>

                        {!! Form::label('titleName','Please enter a Title name',['style'=>'color:black']) !!}
                        {!! Form::text('titleName','', ['id'=>'titleName', 'style'=>'color:black','placeholder'=>'Title Name'])!!}<br><br>

                      </div>
                      <!-- {!! Form::label('zoneNumber','Please enter the zone number',['style'=>'color:black']) !!} -->
                      {!! Form::hidden('zoneNumber','0', ['id'=>'zoneNumber', 'style'=>'color:black','placeholder'=>'Zone Number', 'autocomplete'=>'off'])!!}

                      <p><p><p><p><p><p>
                      <p><p><p><p><p><p>
                      {!! Form::submit('Submit', ['name'=>'newfile', 'onclick'=>'return formcheck();', 'style'=>'color:black']) !!}
                    {!! Form::close() !!}
                  </ul>
                </div>
              </div>
            </div>
            <!-- image upload drop down end-->

            <!-- Delete Tag -->
            <div class="col-xs-6 form form-group" style="text-align: left;">
              <div>
                {!!Form::open(array('id'=>'deleteTagForm'))!!}
                {!!Form::hidden('map_idForNew3', $mapID ,['id'=>'map_idForNew3'] ) !!}
                {!!Form::submit('Delete This Tab', ['name'=>'deleteTag', 'onclick'=> 'return my_confirm();','style'=>'color:black; padding-top:8px; padding-bottom:8px;'])!!}
              {!!Form::close()!!}
            </div>
          </div>
          <!-- END TITLE AREA -->

          <!-- ///////////////////////Tabs sections/////////////////////////// -->
          <div id="myTabContent" class="myTabContent tab-content">
            @foreach($dashboard_maps as $key=>$map)
              @if($key == $mapID)
                <div class="tab-pane fade in active" id="content-{!!$key!!}" >
              @else
                <div role="tabpanel" class="tab-pane fade" id="content-{!!$key!!}">
              @endif


              <div style="text-align:center">
                <div class='view_parent_furnace_boiler' id="picture_container" style="border: 1px solid #AAAACC; box-shadow: 0px 0px 2px 3px #5588aa; margin-top:15px;">

                  <img src="{!! asset('images/backgroundImage/'.$map->background_image) !!}">

                  <!--==================================================== INPUT DMIs ==============================================================-->
                  @foreach($apartments as $apartment)
                    @if($map->id == $apartment['map_id'])
                      <?php
                      $color='';
                      $voltage_img='images/battery_yellow_charging_bk.png';
                      $occupancy_img='images/greybutton-bk.png';
                      $humidity_img='images/humidity_grey_small.png';
                      $digital_img='images/redbutton-bk.png';
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
                      }else if(($apartment['tag'] === 'Digital') && ($apartment['status'] == "ON")){
                        $digital_img = 'images/greenbutton-bk.png';
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
                            if($apartment['status'] == 'OFF'){
                              $occupancy_img = 'images/greenbutton-bk.png';
                            }
                          }
                        }
                      }
                      ?>

                    <div class="device-container" id="{!!$apartment['device_id']!!},{!!$apartment['command']!!},{!!$apartment['map_id']!!}" name="dmi-{!!$apartment['id']!!}"  style="left: {!!$apartment['x-pos']!!}%; top: {!!$apartment['y-pos']!!}%; " data-toggle="tooltip"  title="{!!$apartment['name']!!}<br>{!!$apartment['tag']!!}">
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
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      {!!Form::open()!!}
                      {!!Form::hidden('command', $apartment['command'],['id'=>'command','style'=>'color:black'] ) !!}
                      {!!Form::hidden('device_id', $apartment['device_id'],['id'=>'device_id','style'=>'color:black'] ) !!}
                      {!!Form::hidden('map_id', $apartment['map_id'],['id'=>'map_id','style'=>'color:black'] ) !!}
                      {!!Form::submit('X', ['name'=>'delete', 'style'=>'color:black'])!!}
                      {!!Form::close()!!}
                    </div>

                    @endif
                  @endforeach

                    @foreach($DashboardMapItems as $DashboardMapItem)

                      @if($map->id == $DashboardMapItem['map_id'])

                        @if($DashboardMapItem['label'] == 'Tag')
                        <div class="device-container" id="{!!$DashboardMapItem['device_id']!!},{!!$DashboardMapItem['command']!!},{!!$DashboardMapItem['map_id']!!}" name="dmi-{!!$DashboardMapItem['id']!!}" style="left: {!!$DashboardMapItem['x_position']!!}%; top: {!!$DashboardMapItem['y_position']!!}%;">
                          <a class="btn btn-#fff" style="padding-bottom: 0px; padding-top: 0px; color:#428bca; " data-toggle="tooltip"
                          data-placement="bottom"><h3><b>{!!strtoupper($DashboardMapItem->printLabel)!!}</b></h3></a>
                          {!!Form::open()!!}
                          {!!Form::hidden('command', $DashboardMapItem['command'],['id'=>'command','style'=>'color:black'] ) !!}
                          {!!Form::hidden('device_id', $DashboardMapItem['device_id'],['id'=>'device_id','style'=>'color:black'] ) !!}
                          {!!Form::hidden('map_id', $DashboardMapItem['map_id'],['id'=>'map_id','style'=>'color:black'] ) !!}
                          {!!Form::submit('X', ['name'=>'deleteTitle', 'style'=>'color:black'])!!}
                          {!!Form::close()!!}
                        </div>
                        @endif
                      @endif

                      @if($map->id == $DashboardMapItem['map_id'])
                        @if($DashboardMapItem['label'] == 'Season')
                          <?php
                            if($thisSystem->season_mode == '0') {
                              $season = 'Summer';
                              $seasonPicture = 'images/summer.png';
                            }else{
                              $season = 'Winter';
                              $seasonPicture = 'images/winter.png';
                            }
                          ?>
                          <div class="device-container" id="{!!$DashboardMapItem['device_id']!!},{!!$DashboardMapItem['command']!!},{!!$DashboardMapItem['map_id']!!}" name="dmi-{!!$DashboardMapItem['id']!!}" style="left: {!!$DashboardMapItem['x_position']!!}%; top: {!!$DashboardMapItem['y_position']!!}%; ">
                            <a class="btn btn- my-class" style="padding-bottom: 0px; padding-top: 0px; color:#428bca; border-radius: 100px; " data-toggle="tooltip" title="<b>Status</b>&nbsp{!!'On'!!}" data-placement="bottom">
                              {!!HTML::image($seasonPicture)!!}
                              </h5><FONT FACE = "desdemona">
                                <h4><b>{!!strtoupper($season)!!}</b></h4></FONT></a>
                            {!!Form::open()!!}
                            {!!Form::hidden('command', $DashboardMapItem['command'],['id'=>'command','style'=>'color:black'] ) !!}
                            {!!Form::hidden('device_id', $DashboardMapItem['device_id'],['id'=>'device_id','style'=>'color:black'] ) !!}
                            {!!Form::hidden('map_id', $DashboardMapItem['map_id'],['id'=>'map_id','style'=>'color:black'] ) !!}
                            {!!Form::submit('X', ['name'=>'deleteSeason', 'style'=>'color:black'])!!}
                            {!!Form::close()!!}
                          </div>
                        @endif
                      @endif
                    @endforeach <!-- end of apartment display -->

                  @foreach($dashboard_map_items_outputs as $kkg=>$dashboard_map_items_output)
                    @if($map->id == $dashboard_map_items_output['map_id'])
                      <div class="device-container " id="{!!$dashboard_map_items_output['device_id']!!},{!!$dashboard_map_items_output['command']!!},{!!$dashboard_map_items_output['map_id']!!}" name="dmi-{!!$dashboard_map_items_output['dashboardID']!!}" style="left:{!!$dashboard_map_items_output['x-pos']!!}%; top:{!!$dashboard_map_items_output['y-pos']!!}%; ">
                        {!!Form::open()!!}
                        {!!Form::hidden('command', $dashboard_map_items_output['command'],['id'=>'command','style'=>'color:black'] ) !!}
                        {!!Form::hidden('device_id', $dashboard_map_items_output['id'],['id'=>'device_id','style'=>'color:black'] ) !!}
                        {!!Form::hidden('map_id', $dashboard_map_items_output['map_id'],['id'=>'map_id','style'=>'color:black'] ) !!}
                        <div class="control-device">
                          {!!$dashboard_map_items_output['name']!!}
                        </div>
                        {!!Form::submit('X', ['name'=>'delete', 'style'=>'color:black'])!!}
                        {!!Form::close()!!}
                      </div>
                    @endif
                  @endforeach <!-- end of dashboard_map_items_output  -->
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-xs-12 typical-height"></div>
  </main>
  <?php
    function StatusLogicEncode($S, $I, $R) {
      // Retired overides all, then inhibit overrides status active, if none the undefined
      // Valid States 0 - uncommisioned, 4 - active 5,7 Retired, 6 -  inhibited
      $SV = $R + ($I << 1) + ($S << 2);

      return $SV;
    }

    function StatusLogicDecode($SV) {
      switch ($SV) {
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

    if (isset($_GET['mode'])) {
      $mode = $_GET['mode'];
    } else {
      $mode = 'both';
    }
    $mode = "both";
  ?>
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_scripts = ['/js/vendor/jquery.ui.Touch_punch.min.js'];
    foreach ($import_scripts as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::script($appendDate);
      }

    }
  ?>
  <script language"javascript" type"text/javascript">
    //check to see if the file is jpg, yes return false, else return true
    window.onload = function () {

      var form = document.getElementById('uploadForm'),
        imageInput = document.getElementById('img1');

      form.onsubmit = function () {
        var isValid = /\.png$/i.test(imageInput.value);
        var isJPG = /\.jpe?g$/i.test(imageInput.value);
        if (isJPG) {
          alert('No JPG files allowed!, only png');
          return false;

        }else{
          return true;
        }

      };
    };

    $(document).ready(function(){
      $(".view_parent_furnace_boiler").droppable();
    });
    /*     *******important ******      var adjustx = ((100 / $('#picture_container').width())*finalxPosX );
      var adjusty = ((100 / $('#picture_container').height())*finalxPosX );*/
    var xPos = 0;
    var yPos = 0;
    var xPosition = 0;
    var yPosition = 0;

    $(document).ready(function() {
      $(".device-container").draggable({
        grid: [5, 5],
        containment: ".picture_container",
        snap:true,
        snapMode: "inner",
        /*finding the DMI' id*/
        stop: function(){
          var elementName = $(this).attr('name');
          var elementID = $(this).attr('id');
          elementID = elementID.split(",");
          var parent = $(this).parent();
          yPosition = (parseInt($(this).css('top'))/parent.height()*100);//+"%";
          xPosition = (parseInt($(this).css('left'))/parent.width()*100);//+"%";

          /*assigning the div id to variable for further display and debugging purpose*/
          for (var k = 0; k < elementID.length; k++) {
              var device_id = elementID[0];
              var command   = elementID[1];
              var map_id    = elementID[2];
          }

          /*assigining values to variables*/
          $('#'+elementName+'-device_id').val(device_id);
          $('#'+elementName+'-command').val(command);
          $('#'+elementName+'-map_id').val(map_id);
          $('#'+elementName+'-x_position').val(xPosition);//xPosition.toFixed(2) to round the number to two decimal
          $('#'+elementName+'-y_position').val(yPosition);//yPosition.toFixed(2)
        },

        revert: 'invalid',

      });
    });

    /*Choose new element from the drop down menu*/
    function selectionValue(value)
    {

      var value1 = value.split(",");
      var AddElementid = value1[0];
      var AddElementCommand = value1[1];
      var AddElementName = value1[2];

      $('#AddElementCommand').val(AddElementCommand);
      $('#AddElementid').val(AddElementid);
      $('#AddElementName').val(AddElementName);
      $('#AddElementCommand1').val(AddElementCommand);
      $('#AddElementid1').val(AddElementid);
      $('#AddElementName1').val(AddElementName);
    }

    function setParentId(id) {
      //console.log(id);
      var map_idForNew = id.replace('conten-','');
      $('#map_idForNew').val(map_idForNew);
      $('#map_idForNew1').val(map_idForNew);
      $('#map_idForNew2').val(map_idForNew);
      $('#map_idForNew3').val(map_idForNew);
      $('#map_idForNew4').val(map_idForNew);
      $('#map_idForNew5').val(map_idForNew);
    }

    function my_confirm() {
      if( confirm("Are you sure you want to remove this tab?")) {// To change the background image of the map, click <b>Upload Image</b>. <b>do not delete the tab.</b> ") ) {
        return true;
      }else {
        // do something
        return false;
      }
    }

    function formcheck() {
      var fields = $(".ss-item-required").find("select, textarea, input").serializeArray();

      $.each(fields, function(i, field) {
        if (!field.value)
          alert(field.name + ' is required');
      });
      console.log(fields);
    }

    $(function() {
      // Fix input element click problem
      $('.dropdown-menu').click(function(e) {
        e.stopPropagation();
      });

      $('.add-contact').click(function(e) {
        e.preventDefault();
        var id = ($(".nav-tabs").children().length)-1;
        $('#newTabId').val(id);
        $(this).closest('li').before('<li><a href="#content-'+id+'" id="conten-'+id+'" onclick="setParentId(this.id)" >New Tab</a><span>x</span></li>');
        $('.tab-content').append('<div class="tab-pane" id="conten-'+id+'">Contact Form: New Contact '+id+'</div>');
        $(this).hide();
      });

      $('.nav-tabs').on('click', 'a', function(e){
        e.preventDefault();
      });
      $('.nav-tabs').on('click', 'span', function(e){
        e.preventDefault();
      });

      $(".nav-tabs").on("click", "span", function(e){
        e.preventDefault();
        $(".add-contact").show();
      })
      .on("click", "span", function () {
        var anchor = $(this).siblings('a');
        $(anchor.attr('href')).remove();
        $(this).parent().remove();
        $(".nav-tabs li").children('a').first().click();
      });
    });

  </script>


@stop
