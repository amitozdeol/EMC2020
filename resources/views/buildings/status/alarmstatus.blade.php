<?php $title="Alarm Status"; ?>

<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/building/mytabs.css', '/css/grid.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<style>
  .block_emc h3{
    font-size: 2rem;
  }
</style>
@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="margin: 30px 10px; position: relative;">
    <div class="auto-refresh hidden">
    </div>

    <?php
      $help_id='alarms';
      $collabelcolor="#FFFFFF";
      $CountHist=Count($sysAlarmsHist);
      $CountAct=Count($sysAlarmsActive);
      $Act="in";
      $Arch="";
      $set_active_alarm_tour = true;
    ?>

    <?php
      if ($thisSystem->name==NULL) {
        $sysname="SYSTEM ".$thisSystem->id;
      } else {
        $sysname= strtoupper($thisSystem->name);
      }
    ?>

    <ul id="myTab" class="myTab nav nav-tabs">
      <!-- check if any post request was made my Active or History and make that active -->
      <li class="<?php if($PageA>1 || $PageH==1) echo"active"; ?>"><a class="active_alarms_tour " href="#active-alarms" data-toggle="tab">ACTIVE ALARMS</a></li>
      <li class="<?php if($PageH>1) echo"active"; ?>"><a class="past_alarms_tour " href="#archived-alarms" data-toggle="tab">ALARMS HISTORY</a></li>
    </ul>
    <div id="myTabContent" class="myTabContent tab-content row" style="margin-bottom: 100pt;">
      <div id="active-alarms" class="tab-pane fade <?php if($PageA>1 || $PageH==1) echo"in active "; echo($Act); ?>">
        <div>
          <?php
            alarmheader("A",$TotalA,$PageA,$Filter,$CountAct);
            if ($TotalA>=3) {
              echo '<div class="col-xs-12 gridlayout" data-columns style="float:left; padding: 0px;">';
              alarmslist("A",$sysAlarmsActive,$System,$Building,$Filter);
              echo '</div>';
            }else
              alarmslist("A",$sysAlarmsActive,$System,$Building,$Filter);
          ?>
        </div>
      </div>
      <div id="archived-alarms" class="tab-pane fade <?php if($PageH>1) echo"in active "; echo($Arch); ?>" >
        <?php
          alarmheader("H",$TotalH,$PageH,$Filter,$CountHist);
          if ($TotalH>=3) {
            echo '<div class="gridlayout" data-columns style="float:left;">';
            alarmslist("H",$sysAlarmsHist,$System,$Building,$Filter);
            echo '</div>';
          }else
            alarmslist("H",$sysAlarmsHist,$System,$Building,$Filter);
        ?>
      </div>
    </div>



    <?php
      function HumanReadableTime($sec){
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
        else $duration = "0 minutes";
        return $duration;
      }

      function alarmslist ($mode,$Database,$System,$Building,$Filter) //Alarm listing function
      {
        $buildingID=(int)$Building->id;
        $systemID=(int)$System->id;
        $validflag=False;   //initialize
        $collabelcolor="white";
        if ($mode=="A") {
          $Astate=1;
        } else {
          $Astate=0;
        }
        $AlarmFlag=false; //initialize
        $i=0;             //initilalize
        if($mode == 'A'){
          $set_active_alarm_tour = true;
          $set_past_alarm_tour = false;
        }else{
          $set_active_alarm_tour = false;
          $set_past_alarm_tour = true;
        }
        foreach ($Database as $Almlist)  {
          $i++;
          if ($i==21) {
            break;
          }   // only show up to 20 records
          // Alarm State Image
          if ($Astate==$Almlist->active)  {
            $Alarmstate=$Almlist->alarm_state;
            $AlarmFlag=true;
              if ($Alarmstate == 2) {
                $Alarmcolor="#DD2222";
                $AlarmName="Critical";
              } else {
                $Alarmcolor="#DDDD22";
                $AlarmName="Warning";
              }
              // Device ID
              $Devid=$Almlist->device_id;
              // find device name
              $DeviceName=DB::table('devices')
                ->where('id',$Devid)
                ->where('system_id',$systemID)
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
              $DevCommand=$Almlist->command;
              // find command function
              $CommDsp="";
              $Commandfunc=DB::table('device_types')
                ->where('command',$DevCommand)
                ->limit ('1')
                ->get();
              foreach ($Commandfunc as $CF) {
                $CommDsp=$CF->function;
              }
              if ($Devid==0) {
                $Devdsp="System";
                $CommDsp="";
              }  else {
                if ($validflag) {
                  $Devdsp="<h3>".$DName."</h3>";
                } else {
                  $Devdsp = "Device-".$Devid." no longer found in Device Table";
                }
              }
              $validflag=False;  // reset for next device
              $clearat=$Almlist->cleared_at;
              $now = time();
              $cleardsp="--";
              if ($clearat=="0000-00-00 00:00:00") {
                $cleardsp="--";
                $duration = HumanReadableTime($now-strtotime($Almlist->created_at));
                //$duration=ConvFunc::sec2hms($now-strtotime($Almlist->created_at));
                $resolve="";
              } else {
                $duration=$Almlist->duration;
                $seconds = strtotime("1970-01-01 $duration UTC");
                $duration = HumanReadableTime($seconds);
                $cleardsp=$clearat;
                $resolve=$Almlist->resolution;
              }
            ?>
            @if ($mode=="A")
              <div class="@if($set_active_alarm_tour) alarms_tour @endif item block_emc" style="background-color: #123E5D; border-color: white;">
            @else
              <div class="@if($set_past_alarm_tour) past_alarms_tour @endif item block_emc" style="background-color: #2B3C51; border-color: white;">
            @endif
              {!! Form::open(array("role" => "form")) !!}
              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><!--NAME/IMAGE/DESCRIPTION-->
                  <div class="row" style="background: #071a27; margin-left: -15px; margin-right: -15px;">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"><!--DEVICE NAME-->
                      <font style="color: <?php echo $collabelcolor; ?>;">
                        <b>
                          {!!$Devdsp !!}
                        </b>
                      </font>
                    </div>
                    @if ($mode=="A")
                      <?php /*Provide Links, based on alarm type*/
                        $remedy = "none";
                        switch($Almlist->alarm_code_id){
                          /*Crit/Mod High/Low Value Alarms*/
                          case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 25: case 26: case 27: case 28: case 29: case 30: case 31: case 32:
                            $destination = URL::route('setpointmapping.index', [$buildingID,$systemID]);
                            $tooltip_text = "Go to setpoints page to adjust your alarm levels";
                            $remedy = "setpoints";
                            break;
                          /*Device Reports Delayed/Missing / Control Error Alarms*/
                          case 10: case 11: case 48:
                            $destination = url('building/'.$buildingID.'/system/'.$systemID.'/zonestatus');
                            $tooltip_text = "View device information";
                            $remedy = "status"; //routing to zonestatus proved difficult; pending fix, routing to setpoints
                            break;
                          default:
                            $destination = "";
                            $tooltip_text = "No remedy found";
                            $remedy = "none";
                            break;
                        }
                      ?>
                      @if($remedy == "none")<!--ALARM IMAGE-->
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 row-padding" data-toggle="tooltip" data-placement="bottom" title="{!!$tooltip_text!!}" style="text-align: center;">
                      @else
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 row-padding" @if(Auth::check()) style="cursor: pointer; text-align: center;" data-toggle="tooltip" data-placement="bottom" title="{!!$tooltip_text!!}" @endif >
                      @endif
                          <i class="fa fa-exclamation-triangle fa-3x" style="color: <?php echo $Alarmcolor; ?>"></i>
                        </div>
                    @else
                      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 row-padding">
                        <!--<font style="color: <?php echo $Alarmcolor; ?>;">
                          <b>
                            {!!$AlarmName !!}
                          </b>
                        </font>-->
                      </div>
                    @endif
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-padding"><!--DESCRIPTION-->
                    <font style="color: <?php echo $collabelcolor; ?>;">
                      <b>
                        {!!$Almlist->description!!}
                      </b>
                    </font>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: left"><!--START/END/DURATION TIMES-->
                  <ul class="@if($set_active_alarm_tour) alarms_tour @endif list-group">
                    <li class="list-group-item" style="color: black;">
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
                  @if ($mode=="H")
                    <!--STOP TIME-->
                    <li class="list-group-item" style="color: black;">
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
                  @endif
                    <li class="list-group-item" style="color: black;">
                      <small>
                        Duration:
                      </small>
                      <b>
                        {!!$duration!!}
                      </b>
                    </li>

                <!--RESOLUTION OPTIONS-->
                  @if ($mode=="A")<!--RESOLUTION OPTION-->
                    </ul>
                    <div class="@if($set_active_alarm_tour) alarms_tour @endif col-xs-12 col-sm-12 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--FIX ISSUE-->
                      <button class="btn btn-primary <?php if($destination == "") echo " disabled"; ?>" style="width: 100%; white-space: normal;" name="Alarm" onclick="window.location.href = '{!!$destination!!}'; return false;">
                        {!!$tooltip_text!!}
                      </button>
                    </div>
                    <div class="@if($set_active_alarm_tour) alarms_tour @endif col-xs-12 col-sm-12 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--CLEAR-->
                      <button class="btn btn-danger" style="width: 100%; white-space: normal;" type="submit" name="Alarm">
                        Clear Alarm
                      </button>
                      <input class="form-control" style="color: black" name="Alarm:{!!$Almlist->id!!}[resolution]" type="hidden" value="manual">
                    </div>
                  @else
                    <li class="list-group-item" style="color: black;">
                      <!--RESOLUTION-->
                      <small>
                        Resolution:
                      </small>
                      <b>
                        {!!$resolve!!}
                      </b>
                    </li>
                  @endif
                  </ul>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
        <?php
          }
          $set_active_alarm_tour = false;
          $set_past_alarm_tour = false;
        }
        if (!$AlarmFlag)  {
          if ($mode=="A") {
            $Activedsp="ACTIVE";
          } else {
            $Activedsp="ARCHIVED";
          }
          if ($Filter=="All") {
            $Filterdsp="";
          } elseif ($Filter=="1")  {
            $Filterdsp="PRIORITY";
          } elseif ($Filter=="0")  {
            $Filterdsp="SYSTEM";
          } elseif ($Filter=="input") {
            $Filterdsp="SENSOR";
          } else {
            $Filterdsp="CONTROL";
          }
          echo("<h4 class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='text-align: center; width: 100%; font-size: 14pt' title='Alarms'> NO ".$Activedsp ." ".$Filterdsp." ALARMS FOUND FOR THIS SYSTEM</h4>");
        }
      }
    ?>
    <?php   // alarmheader labels
      function alarmheader ($mode,$Total,$Page,$Filter,$Count)
      {
        $collabelcolor="#111111";
        $i=0;
        //  calculate total number of records
        {
        ?>
        <div class="grid-item" style="color: white; background-color: #969696;">
          <div ><!--BUTTONS-->
            {!! Form::open(array("role" => "form", "name"=>"header")) !!}
            <div class="alarms_tour col-xs-12 col-sm-12 col-md-12 col-lg-6"><!--PRIORITY/ALL-->
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center" data-toggle="tooltip" data-placement="top" title="Set Priority alarms by editing your devices' Setpoints."><!--BUTTON: PRIORITY-->
                <button class="btn btn-primary" style="width: 100%;" type="submit" name="Priority" value="1">
                  Priority
                </button>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--BUTTON: ALL-->
                <button class="btn btn-primary" style="width: 100%;" type="submit" name="All" value="All">
                  All
                </button>
              </div>
            </div>
            @if ($mode=="A")
              <div class="alarms_tour col-xs-12 col-sm-12 col-md-12 col-lg-6"><!--NEXT/PREV-->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--BUTTON: PREVIOUS-->
                  <button class="btn btn-primary" style="width: 100%;" type="submit" name="PrevA" value="{!!$Filter!!}" {!!$Page==1?"disabled":""!!}>
                    Prev
                  </button>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--BUTTON: NEXT-->
                  <button class="btn btn-primary" style="width: 100%;" type="submit" name="NextA" value="{!!$Filter!!}" {!!$Count<21?"disabled":""!!}>
                    Next
                  </button>
                </div>
                <input class="form-control" style="color: black" name="PageA" type="hidden" value="{!!$Page!!}">
                </div>
            @else
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6"><!--NEXT/PREV-->
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--BUTTON: PREVIOUS-->
                  <button class="btn btn-primary" style="width: 100%;" type="submit" name="PrevH" value="{!!$Filter!!}" {!!$Page==1?"disabled":""!!}>
                    Prev
                  </button>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row-padding" style="text-align: center"><!--BUTTON: NEXT-->
                  <button class="btn btn-primary" style="width: 100%;" type="submit" name="NextH" value="{!!$Filter!!}" {!!$Count<21?"disabled":""!!}>
                    Next
                  </button>
                </div>
                <input class="form-control" style="color: black" name="PageH" type="hidden" value="{!!$Page!!}">
              </div>
            @endif
            <input class="form-control" style="color: black" name="Filter" type="hidden" value="{!!$Filter!!}">
            {!! Form::close() !!}
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="grid" style="text-align: center">
              <?php
              {
                if ($mode=="A") {
                  $Activedsp="ACTIVE";
                } else {
                  $Activedsp="ARCHIVED";
                }
                if ($Filter=="All") {
                  $Filterdsp="ALL";
                } elseif ($Filter=="1")  {
                  $Filterdsp="PRIORITY";
                } elseif ($Filter=="0")  {
                  $Filterdsp="SYSTEM";
                } elseif ($Filter=="input") {
                  $Filterdsp="SENSOR";
                } else {
                  $Filterdsp="CONTROL";
                }
              if($Filter == "All") {
                echo("<h4 class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>".$Filterdsp." ".$Activedsp ." ALARMS</h4>");
              } else {
                echo("<h4 class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>".$Activedsp ." ".$Filterdsp." ALARMS</h4>");
              }
            }
            ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: center">
            <?php
            {
              $RecdspLo=(($Page-1)*20)+1;
              if ($Count<21) {
                $RecdspHi=($RecdspLo+$Count-1);
              } else {
                $RecdspHi=($RecdspLo+$Count-1)-1;             }
                if (($Count>0) or ($Page>1)) {
                  echo("<h4 class='row-padding' style='width: 100%; font-size: 14pt' title='Alarms'>Displaying Records ".$RecdspLo ." thru ".$RecdspHi." of ".$Total."</h4>");
                }
              }
            ?>
            </div>
          </div>

        </div>
        <?php
        }
      }
    ?>
  </main>
<!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_scripts = ['/js/bootstrap-tabcollapse.js', '/js/salvattore.min.js'];
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
    $('#myTab').tabCollapse();
    $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="alarms_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Alarms\
        </a>'
      );

    //restart salvattore for grid resizing.
    window.salvattore.rescanMediaQueries();
  });
</script>
@stop