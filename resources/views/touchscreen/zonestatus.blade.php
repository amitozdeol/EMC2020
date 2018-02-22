<?php
  $touchscreen='touchscreenJS';
?>

@extends('layouts.wrapper')

@section('content')

<div class="page-title"><h3>{!! $thisBldg->name !!} - System Zone Status</h3></div>

<div class="page-nav">
    <a href="{!! URL::to('EMC', array($thisSystem->id)) !!}" style="color: white">Back to {!! $thisBldg->name !!}, System {!!$thisSystem->id!!} Overview</a>
</div>

<?php



function Expanpos($ID) {
    $BNum = "4";
    $DN = "ND";
    if ($ID < 43) {
        $BNum = "  3";
    }
    if ($ID < 29) {
        $BNum = "  2";
    }
    if ($ID < 15) {
        $BNum = "  1";
    }
    switch ($ID) {  // relays out
        case 10: case 24: case 38: case 52: $DN = " O1";
            break;
        case 11: case 25: case 39: case 53: $DN = " O2";
            break;
        case 12: case 26: case 40: case 54: $DN = " O3";
            break;
        case 13: case 27: case 41: case 55: $DN = " O4";
            break;
        // current loop out
        case 14: case 28: case 42: case 56: $DN = "CLO";
            break;
        // digital inputs
        case 5: case 19: case 33: case 47: $DN = "DI1";
            break;
        case 6: case 20: case 34: case 48: $DN = "DI2";
            break;
        case 7: case 21: case 35: case 49: $DN = "DI3";
            break;
        case 8: case 22: case 36: case 50 : $DN = "DI4";
            break;
        // current loop in
        case 9: case 23: case 37: case 51 : $DN = "CLI";
            break;

        // analog inputs
        case 1: case 15: case 29: case 43 : $DN = "AN1";
            break;
        case 2: case 16: case 30: case 44 : $DN = "AN2";
            break;
        case 3: case 17: case 31: case 45 : $DN = "AN3";
            break;
        case 4: case 18: case 32: case 46 : $DN = "AN4";
            break;
    }
    //print_r("Board- ".$BNum."<BR> Pos- ".$DN);
    $array['BN'] = "$BNum";
    $array['DN'] = "$DN";
    return $array;
}
/*
function unitconv($units,$degreeSym) {
    $newunit = $units;
    //  degrees  to symbol  for temperature

    // shorten relative humidity
    $newunit = str_replace("relative", "", strtolower($newunit));
    // shorten volts
    $newunit = str_replace("volts", "v", strtolower($newunit));
    if ($degreeSym=="F") {
        $newunit = str_replace("degrees c", "&#176"." F", strtolower($newunit));

    } else    {
       $newunit = str_replace("degrees c", "&#176"." C", strtolower($newunit));
    }

    return $newunit;

}

function valueconv($current_value,$commandparm)
{
        switch ($commandparm)  {    // commands 1,10 for temperature
         case 1:  $newvalue = number_format(($current_value*9/5)+32,1);
             break;
         case 11 :   $newvalue = number_format(($current_value*9/5)+32,1);
             break;
        default :  $newvalue = number_format($current_value, 1);
             break;
        }
      return $newvalue;
}


*/
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
            $statusd[1] = "#1c9494";
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
?>



<?php
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
} else {
    $mode = 'both';
}
$mode = "both";
// determine is zone information is available
$ZStatus=0;
foreach  ($devicesout as $device)  {
  $ZStatus=1;
}
?>



@if ($mode=="output" or $mode=="both")
<h4 class="row-detail" style="color: #AAC7B5; width: 100%; height: 25px; font-size: 14pt" data-toggle="collapse" data-parent="#collapse-container"href="#system-device-status">&nbsp;&nbsp;CONTROL ZONES</h4>
<div id="system-device-status" class="container-fluid collapse">
    <div class="row" style="color: white; background-color: #01082A;">
      @if ($ZStatus!=1)

        <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">None Defined</div>
        <div class="col-xs-3 row-padding" style="text-align: center">

        </div>
      @endif
    </div>
    <!-- Non Retired outputs -->

    <!-- zone sensors loop -->
<?php $Zoneprev = -1;
$ZColor = 1; ?>
    @foreach ($devicesout as $device)
        @if ($device->zone!=0)   <!-- Filter Zone 0 from display -->
        <?php
        if ($Zoneprev != $device->zone) {
            $ZColor = !$ZColor;
            if ($ZColor) {
                $zbkcolor = "#01082A";
            } else {
                $zbkcolor = "#01086A";
            }
            ?>
        <div class="row" style="color: white; background-color: <?php print_r($zbkcolor); ?>">
            <div class="col-xs-3 row-padding">
                {!!"ZONE - ".$device->zone!!}
            </div>

        </div>
            <?php
            $Zoneprev = $device->zone;
            $ZColor = "#01082A";
        }
        ?>
   <div class="row" style="color: white; background-color: #01082A;">



                <div class="col-xs-2 row-padding">
                            {!!$device->physical_location !!}

                </div>




                <div class="col-xs-1 row-padding">State<BR>
                <?php
                $devicestate = DB::table('device_data')
                               ->where('system_id',$device->system_id)
                               ->where('id',$device->id)
                               ->orderby('datetime','desc')
                               ->limit ('1')
                               ->get();

                // one state for outputs
                    $Reporting=0;
                    $Reporttime="-";

                foreach ($devicestate as $dstate) {
                    $Reporting=1;
                    if ($SV<5) {
                     if ($dstate->current_state==1) {
                            print_r("<font color='green' size='5'><b>ON</b></font>");

                        } else { print_r("<font color='blue' size='5'><b>OFF</b></font>"); }
                    } else { print_r("<font color='blue' size='5'><b>OFF</b></font>"); }
                   $Reporttime=$dstate->datetime;
                }
                if ($Reporting==0) {print_r("<font color='#1c94c4'><b>No Reports</b></font>");}
                ?>
               </div>

                <div class="col-xs-1 row-padding">Status<BR>
                  <?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
                        $Statusd=StatusLogicDecode($SV);
                        print_r("<font color='".$Statusd[1]."'><b>".$Statusd[0]."</b></font>");
                  ?>


                </div>



        <div class="col-xs-1 row-padding">Alarm<BR>

                <?php
                $Alarmdsp = $device->alarm_state;
               if ($Alarmdsp == 0) {
                    print_r(HTML::image('images/greenbutton-bk.png'));
                } elseif ($Alarmdsp == 1) {
                    print_r(HTML::image('images/yellowbutton-bk.png'));
                } else {
                    print_r(HTML::image('images/redbutton-bk.png'));
                }
                ?>

        </div>



 </div>

  @endif <!-- filter zone 0 -->

    @endforeach
</div>

<!-- end outputs -->
@endif
<?php    // determine is there is input zones defined
     $ZStatus=0;
     foreach  ($devicesin as $device)  {
         $ZStatus=1;
         }
?>



@if ($mode=="input" or $mode=="both")


<!-- zone Sensor Inputs  -->
<h4 class="row-detail" style="color: #AAC7B5; width: 100%; height: 25px; font-size: 14pt"  data-toggle="collapse" data-parent="#collapse-container"href="#system-devices-inwireless"> &nbsp;&nbsp; SENSORS ZONES</h4>
<div id="system-devices-inwireless" class="container-fluid collapse">
 <div class="row" style="color: white; background-color: #01082A;">
      @if ($ZStatus!=1)

        <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">None Defined</div>
        <div class="col-xs-3 row-padding" style="text-align: center">

        </div>
      @endif
    </div>

    <!-- zone sensors loop -->
        <?php $Zoneprev = -1;
        $ZColor = 1; ?>

    @foreach ($devicesin as $device)
       @if ($device->zone!=0)   <!-- Filter Zone 0 from display -->
        <?php
        if ($Zoneprev != $device->zone) {
            $ZColor = !$ZColor;
            if ($ZColor) {
                $zbkcolor = "#01082A";
            } else {
                $zbkcolor = "#01086A";
            }
            ?>
        <div class="row" style="color: white; background-color: <?php print_r($zbkcolor); ?>">
            <div class="col-xs-3 row-padding">
                {!!"ZONE - ".$device->zone!!}
            </div>

        </div>
            <?php
            $Zoneprev = $device->zone;
            $ZColor = "#01082A";
        }
        ?>
    <div class="row" style="color: white; background-color: #01082A;">
        <div class="col-xs-2 row-padding">

            {!!$device->name."<BR>"!!}
            {!!$device->physical_location !!}

        </div>

        <?php
        //     Determine Status of Sensor -->
        $SV = StatusLogicEncode($device->status, $device->inhibited, $device->retired);
        $Statusd = StatusLogicDecode($SV);

        // for wireless and bacnet devices need to loop through all possible commands
        // to determine status and latest value of each command
        // output variables in an array for each commands and the loop thru to display
        // first find the applible command types


        foreach ($systemsData as $sys)    {
            $commandvec = "";
            $commandtype = DB::table('product_types')
                ->join('devices', 'devices.product_id', '=', 'product_types.product_id')
                ->where('devices.system_id',$sys->id)
                ->where('devices.id', $device->id)
                ->get();

        }
     //   dd(DB::getQueryLog());



        foreach ($commandtype as $ct) {
            $commandvec = $ct->commands . ",";
        }

        $i = 0;
        $ilen = 0;
        $Reporttimews[] = "-";
        $unitsws[] = "";
        $currentvaluews[] = "";
        $functionws[] = "";
        $currentalarmws[] = "";
        $Reporting = 0;
        while ($commandvec <> "") {    // command loop
            $commandparm = substr($commandvec, 0, strpos($commandvec, ","));


            if ($commandparm == 2) {
                $commandparm = 0;
            } // inhibit voltage readings
        foreach ($systemsData as $sys) {
            $devicestate = DB::table('device_data')
                    ->join('devices', 'devices.id', '=', 'device_data.id')
                    ->where('device_data.system_id', $sys->id)
                    ->where('device_data.command', $commandparm)
                    ->where('device_data.id', $device->id)
                    ->orderby('datetime', 'desc')
                    ->limit('1')
                    ->get();
        }
            foreach ($devicestate as $dstate) {
                $Reporting = 1;

                if ($SV < 5) {  // report only active
                    if ($commandparm != 2) { // no state for voltage readings
                        if ($dstate->current_state == 1) {
                            //   print_r("<font color='#1ccc94'><b>ON</b></font><BR>");
                            $PState[$i] = "<font color='#1ccc94'><b>ON</b></font><BR>";
                        } else {// print_r("<font color='#1c94c4'><b>OFF</b></font><BR>");
                            $PState[$i] = "<font color='#1c94c4'><b>OFF</b></font><BR>";
                        }
                    } else {// print_r("-<BR>");
                        $PState[$i] = "-<BR>";
                    }
                } else {// print_r("<font color='#1c94c4'><b>OFF</b></font><BR>");
                    $PState[$i] = "<font color='#1c94c4'><b>OFF</b></font><BR>";
                }
                $currentstate[$i] = $dstate->current_state;
                $Reporttimews[$i] = $dstate->datetime;
                // code to convert current value to F if defined
                foreach ($systemsData as $sys) {
                if (($sys->temperature_format)=="F") {

                       $currentvaluews[$i]= ConvFunc::valueconv($dstate->current_value,$commandparm);
                     } else {
                       $currentvaluews[$i] = number_format($dstate->current_value, 1);
                     }
                }
                $currentalarmws[$i] = $dstate->alarm_state;
                // find units for command type

                $deviceunits = DB::table('device_types')
                        ->where('command', $commandparm)
                        ->get();
                foreach ($deviceunits as $Unit) {
                    $T=$Unit->units;
                    foreach ($systemsData as $sys) {
                       $unitsws[$i] = ConvFunc::unitconv($T,$sys->temperature_format);
                    }
                    $functionws[$i] = $Unit->function;
                }
                $i++;
            }
            $commandvec = substr($commandvec, strpos($commandvec, ",") + 1, strlen($commandvec) - strpos($commandvec, ","));

            $ilen = $i;
        }  // comand loop

        if ($Reporting == 0) {
            print_r("<font color='#1c94c4'><b>No Reports</b></font>");
        }
        ?>



            <?php
            for ($j = 0; $j < $ilen; $j++) {
                print_r("<div class='col-xs-2 row-padding'>");
                print_r($functionws[$j] . "<BR><font color='green' size='5'><b>" . $currentvaluews[$j] . "</b></font> " . $unitsws[$j] . "<BR>");
                print_r($PState[$j]);
                $functionws[$j] = "";
                $currentvaluews[$j] = "";
                $unitsws[$j] = "";
                print_r("</div>");
            }
            ?>



        <div class="col-xs-1 row-padding">Status<BR>
<?php
$SV = StatusLogicEncode($device->status, $device->inhibited, $device->retired);
$Statusd = StatusLogicDecode($SV);
print_r("<font color=" . $Statusd[1] . "'><b>" . $Statusd[0] . "</b></font>");
?>


        </div>

        <div class="col-xs-1 row-padding">Alarm<BR>

<?php
$Alarmdsp = 0;
for ($j = 0; $j < $ilen; $j++) {
    if ($currentalarmws[$j] > $Alarmdsp) {
        $Alarmdsp = $currentalarmws[$j];
    }
}

if ($Alarmdsp == 0) {
    print_r(HTML::image('images/greenbutton-bk.png'));
} elseif ($Alarmdsp == 1) {
    print_r(HTML::image('images/yellowbutton-bk.png'));
} else {
    print_r(HTML::image('images/redbutton-bk.png'));
}
?>

        </div>

<div class="col-xs-2 row-padding">Last Report<BR>
  <?php
  $Reporttimecom=0;
  $Reporttimedsp="0000-00-00 00:00:00";

  for ($j = 0; $j < $ilen; $j++) {

    if (strtotime($Reporttimews[$j]) >= $Reporttimecom) {

        $Reporttimecom = strtotime($Reporttimews[$j]);
        $Reporttimedsp = $Reporttimews[$j];
    }
}    print_r($Reporttimedsp);
     $i = 0;
     $ilen = 0;
 ?>
</div>


    </div>



      @endif  <!-- zone 0 filter -->

    @endforeach
</div>


@endif   <!-- end input -->
@stop
