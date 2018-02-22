<?php $title="Event Status"; ?>
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/building/mytabs.css', '/perfect-scrollbar/css/perfect-scrollbar.min.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<style>
  /***************side panel button***************/
    .SidePanelContainer {
      position: fixed;
      right: 0;
      border-left: 1px solid white;
      border-top: 1px solid white;
      border-bottom: 1px solid white;
      background: rgba(129, 136, 140, 0.8);
      z-index: 1028;
      -webkit-transition: all 0.3s ease-in-out;
      -moz-transition: all 0.3s ease-in-out;
      -o-transition: all 0.3s ease-in-out;
      -ms-transition: all 0.3s ease-in-out;
      color: white;
      padding-top: 2pt;
      padding-bottom: 2pt;
    }
    li {
      list-style: none;
    }
    li button {
      position: relative;
      width: 10px;
      height: 10px;
      background-color: white;
      margin: 0;
      border: 0;
      border-radius: 50%;
      padding: 0;
      outline: 0;
      }
    li button:after {
      content: '';
      width: 10px;
      height: 10px;
      position: absolute;
      /* top: 50%; */
      /* left: 50%; */
      -webkit-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
      border: 3px solid #e4e9eb;
      border-radius: 50%;
      -webkit-animation: beacon 2s infinite linear;
      animation: beacon 2s infinite linear;
      -webkit-animation-fill-mode: forwards;
      animation-fill-mode: forwards;
    }
    @-webkit-keyframes beacon {
      0% {
        width: 0;
        height: 0;
        opacity: 1;
      }
      25% {
        width: 15px;
        height: 15px;
        opacity: 0.7;
      }
      50% {
        width: 25px;
        height: 25px;
      }
      75% {
        width: 35px;
        height: 35px;
        opacity: 0.5;
      }
      100% {
        width: 50px;
        height: 50px;
        opacity: 0;
      }
    }
    @keyframes beacon {
      0% {
        width: 0;
        height: 0;
        opacity: 1;
      }
      25% {
        width: 15px;
        height: 15px;
        opacity: 0.7;
      }
      50% {
        width: 25px;
        height: 25px;
      }
      75% {
        width: 35px;
        height: 35px;
        opacity: 0.5;
      }
      100% {
        width: 50px;
        height: 50px;
        opacity: 0;
      }
    }
    #btnControl {
        display: none;
        z-index: 1029;
    }
    #total_modal{
      box-shadow: none;
      padding: 6px 12px;
    }
    #btnControl:checked + .SidePanelContainer{
      -webkit-transform: translate(-320px,0);
      -moz-transform: translate(-320px,0);
      -o-transform: translate(-320px,0);
      -ms-transform: translate(-320px,0);
    }
  /***************side panel button***************/
  /*************inside modal button*************/
    .svg-icon path,
    .svg-icon polygon,
    .svg-icon rect {
      fill: #fff;
    }
    .svg-icon circle {
      stroke: #fff;
      stroke-width: 1;
    }
    .modal__close, .modal__arrow {
      position: relative;
      border-width: 0px;
      /*margin: 1.2rem;*/
      padding: 0.6rem;
      background: rgba(0,0,0,0.3);
      border-radius: 50%;
      -webkit-transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
      transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .modal__close{
      float: right;
    }
    .modal__close svg, .modal__arrow svg{
      width: 24px;
      fill: #fff;
      pointer-events: none;
      vertical-align: top;
    }
    .modal__close:hover, .modal__arrow:hover {
      background: rgba(0,0,0,0.6);
    }
  /*************inside modal button*************/
  /*******************************
  * MODAL AS RIGHT SIDEBAR
  * Add right" in modal parent div, after class="modal".
  *******************************/
	.modal.right .modal-dialog {
		position: fixed;
		margin: auto;
    /* top: 115px; */
		width: 320px;
		height: 100%;
		-webkit-transform: translate3d(0%, 0, 0);
		    -ms-transform: translate3d(0%, 0, 0);
		     -o-transform: translate3d(0%, 0, 0);
		        transform: translate3d(0%, 0, 0);
	}
	.modal.right .modal-content {
		height: 100%;
    overflow-y: auto;
    border-radius: 0;
		border: none;
    background-color: rgba(129, 136, 140, 0.9);
	}
	.modal.right .modal-body {
		padding: 15px 15px 80px;
    height: 100%;
	}
  /*Right*/
	.modal.right.fade .modal-dialog {
		right: -320px;
		-webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
		   -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
		     -o-transition: opacity 0.3s linear, right 0.3s ease-out;
		        transition: opacity 0.3s linear, right 0.3s ease-out;
	}
	.modal.right.fade.in .modal-dialog {
		right: 0;
	}
  /* ----- MODAL STYLE ----- */

  .modal-body {
      /* max-height: calc(100vh - 210px); */
      overflow-y: auto;
  }
  /* ----- MODAL Background Animation ----- */
  .main-content {
    -webkit-transition: -webkit-transform .3s;
    transition: -webkit-transform .3s;
    transition: transform .3s;
    transition: transform .3s, -webkit-transform .3s;
  }
  body.modal-open .main-content {
    -webkit-transform: scale(.9);
            transform: scale(.9);
    -webkit-filter: blur(2px);
            filter: blur(2px);
  }
  /*----------buttons and other stuff responsiveness----------*/
  #event-main-div .panel-collapse .panel-body{
    padding: 0px;
  }
  #event-main-div .custom_totals_inner{
    padding: 15px;
  }
  #event-main-div .button_padding{
    padding-left: 5px;
    padding-right: 5px;
    margin-bottom: 5px;
  }
  @media screen and (max-width: 800px){
    #event-main-div .custom_totals_inner{
      padding: 4px;
    }
  }
</style>
@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="overflow: hidden; margin: 30px 10px; position: relative;">
    <div class="auto-refresh hidden"></div>
    <?php
      $help_id='events';
      $collabelcolor="#FFFFFF";
      $CountHist=Count($sysEventsHist);
      $CountAct=Count($sysEventsActive);
      $Act="";
      $Arch="";
    ?>
    <?php
      if ($thisSystem->name==NULL) {
        $sysname="SYSTEM ".$thisSystem->id;}
      else  {
        $sysname= strtoupper($thisSystem->name);
      }
      $set_active_tour = true;
      $set_history_tour = true;
    ?>
    <!-- Today and Yesterday's total -->
    <div class="container">
      <!-- side modal button -->
      <input type="checkbox" id="btnControl"/>
        <div class="SidePanelContainer">
          <li style="padding-left: 10px;">
            <button >
            </button>
            <label for="btnControl" id="total_modal" class="btn btn-demo" data-toggle="modal" data-target="#today_modal" style="font-size: 1.3em;">
              <b>
                Totals
              </b>
            </label>
          </li>
        </div>
        <!-- Yesterday Modal -->
        <div class="modal right fade " id="yesterday_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document" @if(Request::is('EMC/*')) style="top:0pt;" @endif>
            <div class="modal-content">
              <div class="modal-header">
                <!-- modal close button -->
                @if(!Request::is('EMC/*'))
                  <button type="button" class="modal__close" data-dismiss="modal" aria-label="Close">
                    <svg class="" viewBox="0 0 24 24 "><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"/><path d="M0 0h24v24h-24z" fill="none"/></svg>
                  </button>
                @endif
                <!-- modal today button -->
                <button id="today_btn" type="button"  class="btn btn-primary" title="Click to see today's totals" style="margin-left:10pt;">
        See Today's Totals
                </button>
              </div>
              <div class="modal-body" id="yesterday-modal-body">
                <div class="col-xs-12 transparent_blue_white" style="padding: 10px; ">
                  <div class="col-xs-12 transparent_blue_white"  style="padding: 20px; ">
                    YESTERDAY'S TOTALS<br>
                    <small>{{date('m/d/Y',$yesterday_time)}}</small><br>
                    @foreach($device_durations as $dd)
                      <div class="col-xs-12 border_blue_white transparent_blue_white" style="padding: 5px;">
                        {{$dd['device_name']}}<br>
                        <?php
                          $run_time = 0;
                          echo HumanReadableTime($dd['yesterdays_total_duration']);
                        ?>
                        <br>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div><!-- modal-content -->
          </div><!-- modal-dialog -->
        </div><!-- modal -->
        <!-- Today Modal -->
        <div class="modal right fade " id="today_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document"  @if(Request::is('EMC/*')) style="top:0pt;" @endif>
            <div class="modal-content">
              <div class="modal-header">
                <!-- modal close button -->
                @if(!Request::is('EMC/*'))
                  <button type="button" class="modal__close" data-dismiss="modal" aria-label="Close">
                    <svg class="" viewBox="0 0 24 24 "><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"/><path d="M0 0h24v24h-24z" fill="none"/></svg>
                  </button>
                @endif
                <!-- modal yesterday button -->
                <button id="yesterday_btn" type="button"  class="btn btn-primary" title="Click to see yesterday's totals" style="margin-left:10pt;">
        See Yesterday's Totals
                </button>
              </div>
                <div class="modal-body" id="today-modal-body">
                  <div class="col-xs-12 transparent_blue_white" style="padding: 10px; " >
                    <div class="col-xs-12 transparent_blue_white" style="padding: 20px; ">
                      TODAY'S TOTAL<br>
                      <small>{{date('m/d/Y h:i:s A',$current_time)}}</small><br>
                      @foreach($device_durations as $dd)
                        <div class="col-xs-12 border_blue_white transparent_blue_white" style="padding: 5px;">
                          {{$dd['device_name']}}<br>
                          <?php
                            $run_time = 0;
                            echo HumanReadableTime($dd['todays_total_duration']);
                          ?>
                          <br>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
            </div><!-- modal-content -->
          </div><!-- modal-dialog -->
        </div><!-- modal -->
      </div><!-- container -->
    <div id="event-main-div" class="main-content" style="overflow: hidden;">
      <ul id="myTab" class="myTab nav nav-tabs">
        <li class="<?php if(isset($custom_totals) || !isset($currentmode)) echo 'active'; ?>"><a class="total_events_tour" href="#custom-totals" data-toggle="tab">CUSTOM TOTALS</a></li>
        <li class="<?php if($currentmode == "A") echo 'active'; ?>"><a class="list_events_active_tour" href="#active-events" data-toggle="tab">ACTIVE CONTROL EVENTS</a></li>
        <li class="<?php if($currentmode == 'H') echo 'active'; ?>"><a class="list_events_history_tour" href="#archived-events" data-toggle="tab">CONTROL EVENTS HISTORY</a></li>
      </ul>
      <div id="myTabContent" class="myTabContent tab-content row">
        <div id='custom-totals' class="col-xs-12 container-fluid transparent_blue_white tab-pane fade <?php if(isset($custom_totals) || !isset($currentmode)) echo 'in active'; ?>" style="padding: 15px; margin-bottom: 15px;">
          {{ Form::open(array("role" => "form")) }}
          <div class="col-xs-12 transparent_blue_white" style="">
            @if(isset($custom_totals))
              {{$custom_totals['name']}}
              <br>
              ON time:
              <br>
              <div class="col-xs-12 col-sm-6 col-sm-offset-3 transparent_blue_white" style="padding: 15px">
                <?php
                  echo HumanReadableTime($custom_totals['total']);
                ?>
              </div>
              <br>
              <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                For the period from <br>{{$custom_totals['start_time']}} to {{$custom_totals['end_time']}}
              </div>
              <br>
            @else
              Get Device Run Times
            @endif
          </div>
          <div class="col-xs-12 transparent_blue_white custom_totals_inner">
            <div class="col-xs-12 col-md-6 col-md-offset-3 " style="padding: 0px;">
              {{Form::label('alg_id', 'Device')}}
              <select class="total_events_tour form-control" name="alg_id" id="alg_id" required>
                <option value="">Select a Device</option>
                  @foreach($custom_device_options as $cdo)
                    <option value="{{$cdo->device_id}}">
                      {{$cdo->name}}
                    </option>
                  @endforeach
              </select>
            </div>
            <div class="col-xs-12 transparent_blue_white custom_totals_inner" style="margin-top: 15px">
              TIME FRAME
              <div class=" col-xs-12 btn-group custom_totals_inner" data-toggle="buttons" style="text-shadow:none; padding:0px;">
                <div class="col-xs-4 col-sm-4 button_padding" onclick="weekTimeframeReset(this)" >
                  <label class="total_events_tour btn btn-primary btn-lg emc-radio-btn col-xs-12" >
                    WEEK
                    <input class="" type="radio" name="default_timeframe" id="week_timeframe" value="week_timeframe" autocomplete="off" style="display: none;">
                  </label>
                </div>
                <div class="col-xs-4 col-sm-4 button_padding" onclick="monthTimeframeReset(this)">
                  <label class="total_events_tour btn btn-primary btn-lg emc-radio-btn col-xs-12">
                    MONTH
                    <input class="" type="radio" name="default_timeframe" id="month_timeframe" value="month_timeframe" autocomplete="off" style="display: none;">
                  </label>
                </div>
                @if(!Request::is('EMC/*'))
                  <div class="total_events_tour col-xs-4 col-sm-4 button_padding" onclick="customTimeframeReset(this)">
                    <label class="col-xs-12 btn btn-primary btn-lg emc-radio-btn @if($expand['custom_time_frame'] == ' in ') active @endif" >
                      CUSTOM
                    </label>
                  </div>
                @endif
              </div>
              @if(!Request::is('EMC/*'))
                <div id='custom_timeframe' class="col-xs-12 container-fluid collapse transparent_blue_white {{$expand['custom_time_frame']}}" style="padding: 15px; margin-bottom: 15px;">
                  <div class="total_events_tour col-xs-6 col-sm-6 col-md-4 col-md-offset-2" style="padding: 15px 15px 15px 0px">
                    {{Form::label('startdate', 'Start Date')}}
                    {{Form::input('date', 'startdate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
                  </div>
                  <div class="total_events_tour col-xs-6 col-sm-6 col-md-4" style="padding: 15px 0px 15px 15px">
                    {{Form::label('enddate', 'End Date')}}
                    {{Form::input('date', 'enddate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
                  </div>
                </div>
              @endif
            </div>
          </div>
          <div class="col-xs-12 transparent_blue_white" style="padding: 15px">
            {{Form::submit('Submit', ['class'=>'total_events_tour btn btn-primary btn-lg btn-block'])}}
          </div>
          {{Form::close()}}
        </div>
        <!-- ACTIVE CONTROL EVENTS -->
        <div id="active-events" class="container-fluid tab-pane fade <?php if($currentmode == "A") echo'in active'; ?>" >
          <div>
            <?php
              eventheader("A",$sysEventsAlg,$TotalA,$PageA,$Filter,$CountAct, $algorithms);
              eventslist("A",$sysEventsActive,$thisSystem->id,$Filter, $algorithms);
            ?>
          </div>
        </div>
        <!-- CONTROL EVENTS HISTORY -->
        <div id="archived-events" class="container-fluid tab-pane fade  <?php if($currentmode == "H") echo'in active'; ?>" >
          <div>
            <?php
              eventheader("H",$sysEventsAlg,$TotalH,$PageH,$Filter,$CountHist, $algorithms);
              eventslist("H",$sysEventsHist,$thisSystem->id,$Filter, $algorithms);
            ?>
          </div>
        </div>
      
        
      <?php
        /********************************************************************functions************************************************************************************/
        function HumanReadableTime($sec){ //make duration human readable
          $timeinsec = $sec;
          $days = floor($timeinsec/86400);
          $hours = floor(($timeinsec-$days*86400)/(60 * 60));
          $min = floor(($timeinsec-($days*86400+$hours*3600))/60);
          $second = $timeinsec - ($days*86400+$hours*3600+$min*60);
          if($days > 0){
            if ($hours > 0)
              $duration = $days." Days ". $hours." Hours";
            elseif($min > 0)
              $duration = $days." Days ". $min." Minutes";
            else
              $duration = $days." Days ";
          }
          elseif($hours > 0){
            if ($min > 0)
              $duration = $hours." Hours ".$min." Minutes";
            else
              $duration = $hours." Hours ";
          }
          elseif($min > 0) $duration = $min." Minutes";
          elseif($second > 0) $duration = $second." Seconds";
          else $duration = "0";
          return $duration;
        }
        function eventslist ($mode,$Database,$System,$Filter) //Event listing function
        {
          $validflag=False;
          $collabelcolor="white";
          if ($mode=="A") {
            $Astate=1;
            $set_active_tour = true;
            $set_history_tour = false;
          } else {
            $Astate=0;
            $set_history_tour = true;
            $set_active_tour = false;
          }
          $EventFlag=false;
          $i=0;
          foreach ($Database as $Almlist)  {
            $i++;
            if ($i==21) {  break; }   // only show up to 20 records
            // Event State Image
            $CommDsp="";
            $DName="";
            if ($Astate==$Almlist->active)  {
              $Alarmstate=$Almlist->alarm_state;
              $EventFlag=true;
              if ($Alarmstate == 2) {
                $Alarmcolor="#DD2222";
                $AlarmName="Critical";
              } elseif ($Alarmstate == 1)   {
                $Alarmcolor="#DDDD22";
                $AlarmName="Warning";
              } else  {
                $Alarmcolor="#22DD22";
                $AlarmName="Normal";
              }
              $AlgID=$Almlist->device_id;
              $AlgFunc=DB::table('mapping_output')
                ->where('device_id',$AlgID)
                ->where('system_id',$System)
                ->first();
              $AName="Undefined";
              $FName="";
              // $AName=$AN->algorithm_name;
              $FName=$AlgFunc->function_type;
              // Device ID
              $Devid=$Almlist->device_id;
              // find device name
              $DeviceName=DB::table('devices')
                ->where('id',$Devid)
                ->where('system_id',$System)
                ->limit ('1')
                ->get();
              foreach ($DeviceName as $DN) {
                $validflag=True;  // set to show a device still valid in device table
                $DName=$DN->name;
                $DFunc=$DN->device_io;
                if ($DFunc=="input") {
                  $Dfuncdsp="Sensor";
                } else {
                  $Dfuncdsp="Control";
                }
              }
              $DevCommand=$Almlist->state;
              // find command function
              if ($DevCommand==1) {
                $CommDsp="On";
              } else {
                $CommDsp="Off";
              }
              $validflag=False;  // reset for next device
              $clearat=$Almlist->updated_at;
              $now = time();
              $cleardsp="--";
              if ($clearat=="0000-00-00 00:00:00") {
                /*If the event has not yet been cleared*/
                $cleardsp="--";
                $duration=HumanReadableTime($now-strtotime($Almlist->created_at));
                $resolve="";
              } else {
                $duration=$Almlist->duration;
                $seconds = strtotime("1970-01-01 $duration UTC");
                $duration = HumanReadableTime($seconds);
                $cleardsp=$Almlist->updated_at;
                $resolve=$Almlist->resolution;
              }
            }
            ?>
          <div class="@if($set_active_tour) list_events_active_tour @elseif($set_history_tour) list_events_history_tour @endif col-xs-12 col-sm-12 col-md-12 col-lg-12 block_emc" style="background-color: #123E5D; border-color: white;">
            {{ Form::open(array("role" => "form")) }}
            <div class="form-group">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><!--NAME/ALARM LEVEL/IMAGE-->
                <div class="col-xs-6 row-padding"><!--NAME-->
                  <font style="color: <?php echo $collabelcolor; ?>;">
                    <b>
                      {{$DName}}
                    </b>
                  </font>
                </div>
                @if ($mode=="A")
                  <div class="col-xs-6 row-padding" title="{{$Almlist->description}}" style="text-align: right;"><!--ALARM LEVEL/IMAGE-->
                    <i class="fa fa-exclamation-triangle fa-3x" style="color: {{$Alarmcolor}}"></i>
                  </div>
                @else
                  <div class="col-xs-6 row-padding" title="{{$Almlist->description}}" style="text-align: right;"><!--ALARM LEVEL-->
                    <font style="color: <?php echo $Alarmcolor; ?>;">
                      <b>
                        {{$AlarmName}}
                      </b>
                    </font>
                  </div>
                @endif
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding"><!-- STATE/ALG-TYPE/START TIMES/END TIMES/DURATION -->
                <ul class="list-group" style="color: black;">
                  <li class="list-group-item col-xs-12 col-sm-6 col-md-4"><!--STATE-->
                    <small>
                      State:
                    </small>
                    <b>
                      {{$CommDsp}}
                    </b>
                  </li>
                  <li class="list-group-item col-xs-12 col-sm-6 col-md-4"><!--ALGORITHM TYPE-->
                    <small>
                      Algorithm Type:
                    </small>
                    <b>
                      {{$FName}}
                    </b>
                  </li>
                  <li class="list-group-item col-xs-12 col-sm-6 col-md-4"><!--DURATION-->
                    <small>
                      Duration:
                    </small>
                    <b>
                      {{$duration}}
                    </b>
                  </li>
                  <li class="list-group-item col-xs-12 col-sm-6 col-md-4"><!--START TIMES-->
                    <small>
                      Start Time:
                    </small>
                    <b>
                      <?php
                        $date = date_create($Almlist->created_at)->getTimestamp();
                        echo date('F j, Y h:i A', $date);
                      ?>
                    </b>
                  </li>
                  <li class="list-group-item col-xs-12 col-sm-6 col-md-4"><!--END TIMES-->
                    <small>
                      End Time:
                    </small>
                    <b>
                      <?php
                        $date = date_create($cleardsp)->getTimestamp();
                        echo date('F j, Y h:i A', $date);
                      ?>
                    </b>
                  </li>
                  <li class="list-group-item hidden-xs col-sm-6 col-md-4" style="height: 43px;"><!--EMPTY-->
                  </li>
                </ul>
              </div>
            </div>
            {{ Form::close() }}
          </div>
          <?php
            $set_history_tour = false;
            $set_active_tour = false;
          }
          /* OR, IF THERE AREN'T ANY EVENTS...*/
          if (!$EventFlag)  {
            if ($mode=="A") {
              $Activedsp="ACTIVE";
            } else {
              $Activedsp="ARCHIVED";
            }
            if ($Filter=="All") {
              $Filterdsp="";
            } else if($Filter == "priority") {
              $Filterdsp="PRIORITY";
            } else {
              $AlgFunc=DB::table('mapping_output')
                ->where('device_id',$Filter)
                ->where('system_id',$System)
                ->first();
              $AName = $AlgFunc->algorithm_name;
              $FName = $AlgFunc->function_type;
              $Filterdsp = $AName;
            }
            echo "<h4 class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='text-align: center; width: 100%; font-size: 14pt' title='Events'> NO ".$Activedsp ." <i>".strtoupper($Filterdsp)."</i> EVENTS<br>FOUND FOR THIS SYSTEM</h4>";
          }
        }
        function eventheader ($mode,$sysAlg,$Total,$Page,$Filter,$Count, $algorithms)
        {
          $collabelcolor="#111111";
          $i=0;
          //  calculate total number of records
          if($mode == "A"){
            $set_active_tour = true;
            $set_history_tour = false;
          }else{
            $set_history_tour = true;
            $set_active_tour = false;
          }
          {
          ?>
          <div class="col-xs-12" style="color: white; background-color: #969696;">
            <div class="col-xs-12"><!--DROP-DOWN / BUTTONS-->
              {{ Form::open(array("role" => "form", "name"=>"header")) }}
              {{ Form::hidden('currentmode', $mode) }}<!--to retain the active tab when page loads -->
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 row-padding"><!--FILTER DROP-DOWN-->
                <select class="@if($set_active_tour) list_events_active_tour @elseif($set_history_tour) list_events_history_tour @endif form-control" style="color: black; width:100%;" name="EventAlg" onchange="this.form.submit()">
                  <option value="All">
                    Show All
                  </option>
                  <option value="priority" @if ($Filter == "priority") {{ "selected" }} @endif>
                    Priority Events
                  </option>
                  @foreach ($algorithms as $algorithm)
                    <option value="{{$algorithm->device_id}}" @if ($Filter == $algorithm->device_id) {{ "selected" }} @endif>
                      <?php echo $algorithm->algorithm_name/*." - ".$algorithm->function_type;*/ ?>
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="@if($set_active_tour) list_events_active_tour @elseif($set_history_tour) list_events_history_tour @endif col-xs-12 col-sm-12 col-md-6 col-lg-8"><!--NEXT/PREV BUTTONS-->
                @if ($mode=="A")
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center">
                    <button class="btn btn-primary button_padding" style="width: 100%;" type="submit" name="PrevA" value="{{$Filter}}" {{$Page==1?"disabled":""}}>
                      Prev
                    </button>
                  </div>
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center">
                    <button class="btn btn-primary button_padding" style="width: 100%;" type="submit" name="NextA" value="{{$Filter}}" {{$Count<21?"disabled":""}}>
                      Next
                    </button>
                  </div>
                  <input class="form-control" style="color: black" name="PageA" type="hidden" value="{{$Page}}">
                @else
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center">
                    <button class="btn btn-primary button_padding" style="width: 100%;" type="submit" name="PrevH" value="{{$Filter}}" {{$Page==1?"disabled":""}}>
                      Prev
                    </button>
                  </div>
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center">
                    <button class="btn btn-primary button_padding" style="width: 100%;" type="submit" name="NextH" value="{{$Filter}}" {{$Count<21?"disabled":""}}>
                      Next
                    </button>
                  </div>
                  <input class="form-control" style="color: black" name="PageH" type="hidden" value="{{$Page}}">
                @endif
              </div>
              <input class="form-control" style="color: black" name="Filter" type="hidden" value="{{$Filter}}">
              {{ Form::close() }}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div style="text-align: center">
              <?php
                $RecdspLo=(($Page-1)*30)+1;
                if ($Count<31) {
                  $RecdspHi=($RecdspLo+$Count-1);
                } else {
                  $RecdspHi=($RecdspLo+$Count-1)-1;             }
                  if (($Count>0) or ($Page>1)) {
                    echo("<h4>Displaying Records ".$RecdspLo ." through ".$RecdspHi." of ".$Total."</h4>");
                  }
              ?>
              </div>
            </div>
          </div>
          <?php
          }
        }
      ?>
    </div>
    <?php
      $time = microtime();
      $time = explode(" ", $time);
      $time = $time[1] + $time[0];
      $finish = $time;
      $total_time = round(($finish - $start), 4);
    ?>
    <?php
      // $ss = HTML::style("css/grid.css");
      // echo $ss; //load grid.css file after the javascript
    ?>
  </main>
  <!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_scripts = ['/js/bootstrap-tabcollapse.js', '/perfect-scrollbar/js/perfect-scrollbar.min.js'];
    foreach ($import_scripts as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::script($appendDate);
      }

    }
  ?>
  <script type="text/javascript">
    $(document).ready(function(){
      @if($expand['custom_time_frame'] != ' in ')
        var customButtonChild = document.getElementById("custom_timeframe");
        $(customButtonChild).collapse('hide');
      @endif
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="total_events_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Custom Totals\
        </a>\
        <a href="javascript:void(0);" class="list_events_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Current and Past Events\
        </a>'
      );
    });
    $(window).scroll(function () {
      //set scroll position in session storage
      sessionStorage.scrollPos = $(window).scrollTop();
    });
    var init = function () {
      //get scroll position in session storage
      $(window).scrollTop(sessionStorage.scrollPos || 0)
    };
    window.onload = init;

    function weekTimeframeReset(element){
      var monthButton,customButton,customButtonChild;
      monthButton = element.nextElementSibling;
      $(monthButton).removeClass("active");
      customButton = monthButton.nextElementSibling;
      customButtonChild = element.parentElement.nextElementSibling;
      $(customButtonChild).collapse('hide');
      $(customButton).removeClass("active");
    }
    function monthTimeframeReset(element){
      var weekButton,customButton,customButtonChild;
      weekButton = element.previousElementSibling;
      $(weekButton).removeClass("active");
      customButton = element.nextElementSibling;
      customButtonChild = element.parentElement.nextElementSibling;
      $(customButtonChild).collapse('hide');
      $(customButton).removeClass("active");
    }
    function customTimeframeReset(element){
      var monthButton,weekButton;
      monthButton = element.previousElementSibling.firstElementChild;
      $(monthButton).removeClass("active");
      weekButton = element.previousElementSibling.previousElementSibling.firstElementChild;
      $(weekButton).removeClass("active");
      customButtonChild = element.parentElement.nextElementSibling;
      $(customButtonChild).collapse('toggle');
    }

    $(function(){
      $('#myTab').tabCollapse();

      Ps.initialize(document.getElementById('today-modal-body')); //change scroolbar style
      Ps.initialize(document.getElementById('yesterday-modal-body')); //change scroolbar style
      $(function(){
        Ps.update(document.getElementById('today-modal-body'));
      });

      @if(!isset($custom_totals) && !isset($currentmode))
        if ($(window).width() > 500) {
          $('#today_modal').modal('show');
        }
      @endif
      $( "#today_btn" ).click(function() {
        $("#yesterday_modal").modal('hide');
        $("#today_modal").modal('show');
      });
      $( "#yesterday_btn" ).click(function() {
        $("#today_modal").modal('hide');
        $("#yesterday_modal").modal('show');
      });
      $('#today_modal, #yesterday_modal').on('shown.bs.modal', function (e) {
        $('#btnControl').prop('checked', true);
      });
      $('#today_modal, #yesterday_modal').on('hide.bs.modal', function (e) {
        if ((($("#today_modal").data('bs.modal') && $("#yesterday_modal").data('bs.modal')) || {isShown: false}).isShown) {
          $('#btnControl').prop('checked', false);
        }
        $('#btnControl').prop('checked', false);
      });
    });
  </script>
@stop