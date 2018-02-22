<?php $title="System"; ?>

<!--buildings.systems.blade.php-->
@extends('layouts.wrapper')

@section('content')

  <?php
   if ($thisSystem->name==NULL) {
             $sysname="SYSTEM ".$thisSystem->id;}
       else  {
             $sysname= strtoupper($thisSystem->name);
       }
  ?>

  <div class="page-title" style="height: 95px; line-height: 55px">
    <h3>
      {{$thisBldg->name}} - {{$sysname}} Overview
    </h3>
  </div>

  <br>

  @foreach ($sysParams as $param)
    <?php   {
       // loop thru groups with same name
       // determine highest level of alarm
       // once new group name display group and alarm level
       $alarmstate = DB::table('web_mapping_system')
                        ->where('system_id',$thisSystem->id)
                        ->where('active',1)
                        ->where('group_number',$param->group_number)
                        ->get();
      // $group_name = strtolower($param->group_name);
       $alarmnum=-1;
       $title="No Active Alarms";
       foreach ($alarmstate as $alst) {
       $alarmstatus=$alst->alarm_state;
           if ($alarmnum<$alarmstatus)  {
               $alarmnum=$alarmstatus;
               $alarmindex=$alst->alarm_index;
           }

            // now look in alarm log for latest active alarm at the level of $alarmnum
         // place that alarm in title   \
         // not found  indicate no alarms for 0, display not defined for 1 and 2 if not found
          if ($alarmnum<1) {
              if ($alarmnum==0) {
                $title="No Active Alarms";
              } else {
                $title="No Alarms Defined";
              }
          }
          else {
            $AlarmCode = DB::table('alarms')
                       ->select('alarms.created_at as cdate','alarms.description','device_id')
                       ->Join('alarm_codes','alarm_codes.id','=','alarms.alarm_code_id')
                       ->where('alarms.id',$alst->alarm_index)
                       ->where('active',1)
                       ->limit ('1')
                       ->get();

            foreach ($AlarmCode as $Acode) {
                $AlarmDesc=$Acode->description;
                if ($Acode->device_id==0) {
                  $Did="System";
                } else {
                  $Did="Num.".$Acode->device_id;
                }
                $title="Device - ".$Did." Alarm- ".$AlarmDesc." occurred at ".$Acode->cdate;
            }
          }  // end alarm >0 else
        }
        if ($alarmnum == -1) {
          $alarmdsp="images/greenbutton.png";
        } elseif ($alarmnum == 0) {
          $alarmdsp="images/greenbutton.png";
        } elseif ($alarmnum == 1) {
          $alarmdsp="images/yellowbutton.png";
        } else {
          $alarmdsp="images/redbutton.png";
        }
    }
    ?>

    <div class="col-sm-12 row-detail block_emc" >
      <a href="./{{$thisSystem->id . '/detail/' . $param->group_number}}" title="{{ $title }}">
        <!--{{HTML::image($alarmdsp)}}-->
      </a>
      {{ $param->group_name }}
    </div>
  @endforeach

  <div class="col-sm-12 row-detail block_emc" >
    <?php  {  // temp zone status calculator move to parser  when ready

       // look for active alarm state from alarmlog for zones
      $alarmstate = DB::table('alarms')
        ->where('system_id',$thisSystem->id)
        ->where('active',1)
        ->orderby('created_at','desc')
        ->orderby('command','asc')
        ->orderby('device_id','desc')
        ->limit ('1')
        ->get();
      $j=0;  $Alarmdsp=0;
      foreach ($alarmstate as $alarm)  {
        $currentalarmws[$j]=$alarm->alarm_state;
        if ($currentalarmws[$j] >  $Alarmdsp) {
          $Alarmdsp=$currentalarmws[$j];
          $Alarmindex=$alarm->id;
        }
      }  // alarm foreach
    }
    ?>

    <a href="./{{$thisSystem->id}}/zonestatus" title="ZONE DASHBOARD --TEMP">
      <?php {
        // look for active alarm state from alarmlog for system
        $alarmstate = DB::table('alarms')
          ->where('system_id',$thisSystem->id)
          ->where('active',1)
          ->orderby('created_at','desc')
          ->orderby('command','asc')
          ->orderby('device_id','desc')
          ->limit ('1')
          ->get();
        $j=0;  $Alarmdsp=0;
        foreach ($alarmstate as $alarm)  {
          $currentalarmws[$j]=$alarm->alarm_state;
          if ($currentalarmws[$j] >  $Alarmdsp) {
            $Alarmdsp=$currentalarmws[$j];
            $Alarmindex=$alarm->id;
          }
        }  // alarm foreach
        if ($Alarmdsp==0) {
          print_r(HTML::image('images/greenbutton.png'));
        } elseif ($Alarmdsp==1) {
          print_r(HTML::image('images/yellowbutton.png'));
        } else {
          print_r(HTML::image('images/redbutton.png'));
        }
      }
      ?>
    </a>
    {{ "EMC ZONE STATUS" }}
  </div>
  <div class="col-sm-12 row-detail block_emc" >
    <a href="./{{$thisSystem->id}}/alarmstatus" class="img-responsive" title="EMC ALARM STATUS">
      <?php {
        if ($Alarmdsp==0) {
          print_r(HTML::image('images/greenbutton.png'));
        } elseif ($Alarmdsp==1) {
          print_r(HTML::image('images/yellowbutton.png'));
        } else {
          print_r(HTML::image('images/redbutton.png'));
        }
      }
       ?>
    </a>
    {{ "ALARMS" }}
  </div>
  <div class="col-sm-12 row-detail block_emc" >
    <a href="./{{$thisSystem->id}}/eventstatus" title="SYSTEM EVENTS">
      <?php {
        // look for active alarm state from alarmlog for system
        $alarmstate = DB::table('alarms')
          ->where('system_id',$thisSystem->id)
          ->where('active',1)
          ->where('command',2)
          ->orderby('created_at','desc')
          ->orderby('command','asc')
          ->orderby('device_id','desc')
          ->limit ('1')
          ->get();
        $j=0;  $AlarmdspDS=0;
        foreach ($alarmstate as $alarm)  {
          $currentalarmws[$j]=$alarm->alarm_state;
          if ($currentalarmws[$j] >  $AlarmdspDS) {
            $AlarmdspDS=$currentalarmws[$j];
            $AlarmindexDS=$alarm->id;
          }
        }  // alarm foreach
      }
      ?>
    </a>
    {{ "CONTROL EVENTS" }}
  </div>
  <div class="col-sm-12 row-detail block_emc">
    <a href="./{{$thisSystem->id}}/devicestatus" title="EMC DEVICE STATUS">
      <?php {
        // look for active alarm state from alarmlog for system
        $alarmstate = DB::table('alarms')
          ->where('system_id',$thisSystem->id)
          ->where('active',1)
          ->where('command',2)
          ->orderby('created_at','desc')
          ->orderby('command','asc')
          ->orderby('device_id','desc')
          ->limit ('1')
          ->get();
        $j=0;  $AlarmdspDS=0;
        foreach ($alarmstate as $alarm)  {
          $currentalarmws[$j]=$alarm->alarm_state;
          if ($currentalarmws[$j] >  $AlarmdspDS) {
            $AlarmdspDS=$currentalarmws[$j];
            $AlarmindexDS=$alarm->id;
          }
        }  // alarm foreach
        if ($AlarmdspDS==0) {
          print_r(HTML::image('images/greenbutton.png'));
        } elseif ($AlarmdspDS==1) {
          print_r(HTML::image('images/yellowbutton.png'));
        } else {
          print_r(HTML::image('images/redbutton.png'));
        }
      }
      ?>
    </a>
    {{ "EMC HARDWARE SYSTEM STATUS" }}
  </div>

@stop
