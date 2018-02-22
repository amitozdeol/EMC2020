<?php
  $touchscreen='touchscreenJS';
?>

@extends('layouts.wrapper')

@section('content')

<div class="page-title"><h3>{!! $thisBldg->name !!} - System {!! $thisSystem->id !!} - {!!$sysDetail[0]['group_name']!!} Detail</h3></div>

<div class="page-nav">
  <a href="{!! URL::previous() !!}" style="color: white">Back to {!! $thisBldg->name !!}, System {!!$thisSystem->id!!} Overview</a>
</div>

<br>

<div style="margin-left: auto; margin-right: auto; width: 90%;">
  <div id="accordion">
  @foreach ($sysDetail as $category)
                <?php   {

                   $alarmnum=$category->alarm_state;
                   $title="Unable to identify alarm";

              // now look in alarm log for latest active alarm at the level of $alarmnum
             // place that alarm in title   \
            // not found  indicate no alarms for 0, display not defined for 1 and 2 if not found
                   if ($alarmnum<1) {
                           if ($alarmnum==0) {$title="No Active Alarms";} else {$title="No Alarms Defined";}
                      }
                   else {
                       $AlarmCode = DB::table('alarms')
                       ->select('alarms.created_at as cdate','alarms.description','device_id')
                       ->join('alarm_codes','alarm_codes.id','=','alarms.alarm_code_id')
                       ->where('alarms.id',$category->alarm_index)
                       ->where('active',1)
                       ->limit ('1')
                       ->get();


             // dd(DB::getQueryLog());


                   foreach ($AlarmCode as $Acode) {

                        $AlarmDesc=$Acode->description;

                       $Did="Num.".$Acode->device_id;
                       $title="Device - ".$Did." Alarm- ".$AlarmDesc." occurred at ".$Acode->cdate;

                   }
            }  // end alarm >0 else






                 if ($alarmnum == -1) {
                    $alarmdsp="";
                } elseif ($alarmnum == 0) {
                    $alarmdsp="images/greenbutton-bk.png";
                } elseif ($alarmnum == 1) {
                    $alarmdsp="images/yellowbutton-bk.png";
                } else {
                    $alarmdsp="images/redbutton-bk.png";
                }


          }
                ?>
        <h4 class="row-detail" style="width: 100%; font-size: 14pt" title="{!! $title !!}">{!!$alarmdsp!=""?(HTML::image($alarmdsp)):"&nbsp;&nbsp;&nbsp;"!!} {!!$category->subgroup_name!!} </h4>
    <div>
      <div class="tabs">
          <ul>
        @foreach ($categories as $item)
          @if ($item->subgroup_number == $category->subgroup_number)
              <li><a href="#tabs-{!! $item->itemnumber !!}" class="text-color">{!! $item->subgroup_name !!}: All</a></li>
              @if ($item->subgroup_name == "Temperatures")
                @for ($i = 1; $i <= $numTempZones; $i++) <!-- Spawn more tabs according to number of zones -->
                  <li><a href="#tabs-{!! $i+1 !!}" class="text-color">{!! "Zone " . $zoneTempCharts[$i]['zone'] !!}</a></li>
              @endfor
            @elseif ($item->subgroup_name == "Humidity")
                @for ($i = 1; $i <= $numHumZones; $i++) <!-- Spawn more tabs according to number of zones -->
                  <li><a href="#tabs-{!! $i+1 !!}" class="text-color">{!! "Zone " . $zoneHumCharts[$i]['zone'] !!}</a></li>
              @endfor
            @endif
            @endif
          @endforeach
          </ul>

          @foreach ($categories as $item)
            @if ($item->subgroup_number == $category->subgroup_number)
            <div id="tabs-{!! $item->itemnumber !!}">
              <div id="{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}" style="width: 90%; margin-left: 0; margin-right: auto"></div>
            </div>
            @if ($item->subgroup_name == "Temperatures")
                @for ($i = 1; $i <= $numTempZones; $i++) <!-- Spawn more divs according to number of zones (with zone label tagged on) -->
                  <div id="tabs-{!! $i+1 !!}">
                  <div id="{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}-{!! $i !!}" style="width: 90%; margin-left: 0; margin-right: auto">{!! $i !!}</div>
                </div>
              @endfor
            @elseif ($item->subgroup_name == "Humidity")
                @for ($i = 1; $i <= $numHumZones; $i++) <!-- Spawn more divs according to number of zones (with zone label tagged on) -->
                  <div id="tabs-{!! $i+1 !!}">
                  <div id="{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}-{!! $i !!}" style="width: 90%; margin-left: 0; margin-right: auto">{!! $i !!}</div>
                </div>
              @endfor
            @endif
          @endif
        @endforeach
      </div>
    </div>
  @endforeach




  </div>
</div>




@stop
