<?php
  $touchscreen='touchscreenJS';
?>


@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="margin: 30px 10px; position: relative;">
<div class="page-title"><h3>{{ $thisBldg->name }} - System Device Status</h3></div>

<div class="page-nav">
    <a href="{{ URL::to('EMC', array($thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
</div>

<?php
  $collabelcolor="#EED5EF";

   function Expanpos($ID)  {
    $BNum="4";
    $DN="ND";
    if ($ID<43)  {$BNum="  3";}
    if ($ID<29)  {$BNum="  2";}
    if ($ID<15)  {$BNum="  1";}
    switch ($ID)  {  // relays out
       case 10: case 24: case 38: case 52:  $DN=" O1"; break;
       case 11: case 25: case 39: case 53:  $DN=" O2"; break;
       case 12: case 26: case 40: case 54:  $DN=" O3"; break;
       case 13: case 27: case 41: case 55:  $DN=" O4"; break;
      // current loop out
       case 14: case 28: case 42: case 56:  $DN="CLO"; break;
     // digital inputs
       case 5: case 19: case 33: case 47:   $DN="DI1"; break;
       case 6: case 20: case 34: case 48:   $DN="DI2"; break;
       case 7: case 21: case 35: case 49:   $DN="DI3"; break;
       case 8: case 22: case 36: case 50 :   $DN="DI4"; break;
       // current loop in
       case 9: case 23: case 37: case 51 :   $DN="CLI"; break;

    // analog inputs
       case 1: case 15: case 29: case 43 :   $DN="AN1"; break;
       case 2: case 16: case 30: case 44 :   $DN="AN2"; break;
       case 3: case 17: case 31: case 45 :   $DN="AN3"; break;
       case 4: case 18: case 32: case 46 :   $DN="AN4"; break;


      }
    //print_r("Board- ".$BNum."<BR> Pos- ".$DN);
    $array['BN']="$BNum"; $array['DN']="$DN";
    return $array;
  }
  function StatusLogicEncode ($S,$I,$R)  {

      // Retired overides all, then inhibit overrides status active, if none the undefined
      // Valid States 0 - uncommisioned, 4 - active 5,7 Retired, 6 -  inhibited
      $SV=$R + ($I<<1) + ($S<<2);

      return $SV;
  }

  function StatusLogicDecode($SV) {
        switch ($SV)  {
          case 4: $statusd[0]="Active";
                  $statusd[1]="#1ccc94";
                  break;
          case 6: $statusd[0]="Inhibited";
                  $statusd[1]="#1c9494";
                  break;
          case 5:
          case 7: $statusd[0]="Retired";
                  $statusd[1]="#1ccc94";
                  break;
        Default: $statusd[0]="Not Defined";
                  $statusd[1]="#94cc1c";


    }

    return $statusd;
  }

  /*
  function unitconv($units) {
      $newunit = $units;
      //  degrees  to symbol
      $newunit = str_replace("degrees", "&#176", strtolower($newunit));
      // shorten relative humidity
      $newunit = str_replace("relative", "", strtolower($newunit));
      // shorten volts
      $newunit = str_replace("volts", "v", strtolower($newunit));

      return $newunit;

  }
*/
?>










  <h4 class="row-detail" style="width: 100%; height: 25px; font-size: 14pt" data-toggle="collapse" data-parent="#collapse-container"href="#system-device-status">&nbsp;&nbsp;CONTROLS STATUS</h4>
      <div id="system-device-status" class="container-fluid collapse">
             <div class="row" style="color: white; background-color: #01082A;">
            <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">EMC OUTPUT CONTROLS</div>
                <div class="col-xs-3 row-padding" style="text-align: center">

            </div>
                               </div>
                                <!-- Non Retired outputs -->

                                @foreach ($devicesout as $device)

            <div class="row" style="color: white; background-color: #01082A;">
              <!--<div class="form-group">-->
                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Device # {{$device->id}}</font><BR>
                                                                      <?php $TT= Expanpos($device->id);
                                                                            $BD=$TT['BN']; $PS=$TT['DN'];
                                                                      ?>
                                                                         {{"Board- ".$BD."<BR> Pos- ".$PS;}}

                </div>

                                                              <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Mode</font><BR>
                  {{$device->device_mode }}
                         </div>


                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Zone</font><BR>
                                                                     {{$device->zone }}
                                                                </div>


                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Name/Physical Location</font><BR>
                                                                       {{$device->name."<BR>"}}
                                                                       {{$device->physical_location }}

                                                                </div>

                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Status</font><BR>
                                                                  <?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
                  $Statusd=StatusLogicDecode($SV);
                                                                        print_r("<font color='".$Statusd[1]."'><b>".$Statusd[0]."</b></font>");
                                                                  ?>


                </div>

                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">State</font><BR>
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
                                                                            print_r("<font color='#1ccc94'><b>ON</b></font>");

                                                                        } else { print_r("<font color='#1c94c4'><b>OFF</b></font>"); }
                                                                    } else { print_r("<font color='#1c94c4'><b>OFF</b></font>"); }
                                                                   $Reporttime=$dstate->datetime;
                                                                }
                                                                if ($Reporting==0) {print_r("<font color='#1c94c4'><b>No Reports</b></font>");}
                                                                ?>
                                                               </div>
                                                                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Last Report</font><BR>
                                                                     {{$Reporttime}}
                                                                </div>
                <div class="col-xs-2 row-padding">Inputs<BR>
                                                                   <?php
                                                                   $inputmap = DB::table('mapping_output')
                                                                               ->where('system_id',$device->system_id)
                                                                               ->where('id',$device->id)
                                                                               ->limit ('1')
                                                                               ->get();
                                                                   $Alg="";
                                                                   $indisplay="";
                                                                   foreach ($inputmap as $instate) {
                                                                      $Alg=$instate->algorithm_id;
                                                                      $inputvec=$instate->input_id.",";
                                                                      // loop through input vector look for state of input and any alarms
                                                                      // create a link to each input through a bookmark
                                                                      while ($inputvec<>"") {
                                                                        $inno=substr($inputvec,0,strpos($inputvec,","));

                                                                         // look for state of device $inno
                                                                         $inputstate = DB::table('devices')
                                                                               ->leftJoin('device_data','device_data.id','=','devices.id')
                                                                               ->where('devices.system_id',$device->system_id)
                                                                               ->where('devices.id',intval($inno))
                                                                               ->orderby('datetime')
                                                                               ->limit ('1')
                                                                               ->get();
                                                                      //  dd(DB::getQueryLog());
                                                                         $incolor="#1c94c4";
                                                                         foreach ($inputstate as $ins) {
                                                                             $Alarm=$ins->alarm_state;
                                                                             $SV=StatusLogicEncode($ins->status,$ins->inhibited,$ins->retired);

                                                                              if ($SV<5 and $SV>0) {
                                                                                switch ($Alarm) {
                                                                                  case 0: $incolor="#1ccc94";
                                                                                  break;
                                                                                  case 1; $incolor="#94941c";
                                                                                  break;
                                                                                  case 2; $incolor="#cc1c1c";
                                                                                  break;
                                                                                }
                                                                             }
                                                                           $indisplay=$indisplay."<a href='#".$inno."'><font color='".$incolor."'><b>".$inno."</b></font></a>"." ";

                                                                            }
                                                                         $inputvec=substr($inputvec,strpos($inputvec,",")+1,strlen($inputvec)-strpos($inputvec,","));


                                                                       }
                                                                       // dd($inputvec);
                                                                       print_r($indisplay);
                                                                   }
                                                                   ?>
                                                                </div>
                                                              <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Algorithm</font><BR>
                                                                  <!-- add link to algorithm here open a popup with details of algorithm -->
                                                                     {{$Alg}}
                                                                </div>
                                              <!--  </div> -->

              </div>


        @endforeach
                             </div>

                               <!-- end outputs -->


                              <!-- Begin Sensors    -->
                     <h4 class="row-detail" style="width: 100%; height: 25px; font-size: 14pt"  data-toggle="collapse" data-parent="#collapse-container"href="#sensor-device-status">&nbsp;&nbsp;SENSORS STATUS</h4>
      <div id="sensor-device-status" class="container-fluid collapse">




                              <!--  wired Sensor Inputs  -->

                              <h4 class="row-detail" style="color: #AAC7B5; width: 100%; height: 15px; font-size: 12pt"  data-toggle="collapse" data-parent="#collapse-container"href="#system-devices-inwired">&nbsp;&nbsp;EMC Wired Sensors</h4>
                        <div id="system-devices-inwired" class="container-fluid collapse">

        <div class="row" style="color: white; background-color: #01082A;">
            <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">EMC Wired Sensors</div>



                                </div>

                                 @if ($devicesin==Null) {{"No Wired Sensors" }} @endif
                                 @foreach ($devicesin as $device)

              @if ($device->device_mode=="wired")
            <div class="row" style="color: white; background-color: #01082A;">
              <!--<div class="form-group">-->
                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Device # {{$device->id}}</font><BR>
                                                                      <?php $TT= Expanpos($device->id);
                                                                            $BD=$TT['BN']; $PS=$TT['DN'];
                                                                      ?>
                                                                         {{"Board- ".$BD."<BR> Pos- ".$PS;}}

                </div>


                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Zone</font><BR>
                                                                     {{$device->zone }}
                                                                </div>


                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Name/Physical Location</font><BR>
                                                                        {{$device->name."<BR>"}}
                                                                        {{$device->physical_location }}


                                                                </div>

                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Status</font><BR>
                                                                  <?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
                  $Statusd=StatusLogicDecode($SV);
                                                                        print_r("<font color='".$Statusd[1]."'><b>".$Statusd[0]."</b></font>");
                                                                  ?>


                </div>

                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">State</font><BR>
                                                                <?php
                                                                $devicestate = DB::table('device_data')
                                                                               ->join('devices','devices.id','=','device_data.id')
                                                                               ->join('device_types','devices.device_types_id','=','device_types.id')
                                                                               ->where('device_data.system_id',$device->system_id)
                                                                               ->where('device_data.id',$device->id)
                                                                               ->orderby('datetime','desc')
                                                                               ->limit ('1')
                                                                               ->get();
                                                               //  dd(DB::getQueryLog());
                                                                // one state for outputs
                                                                    $Reporting=0;
                                                                    $Reporttime="-";
                                                                    $units="";
                                                                    $currentvalue="";
                                                                     $function="";
                                                                foreach ($devicestate as $dstate) {
                                                                    $Reporting=1;
                                                                    if ($SV<5) {
                                                                     if ($dstate->current_state==1) {
                                                                            print_r("<font color='#1ccc94'><b>ON</b></font>");

                                                                        } else { print_r("<font color='#1c94c4'><b>OFF</b></font>"); }
                                                                    } else { print_r("<font color='#1c94c4'><b>OFF</b></font>"); }
                                                                   $function=$dstate->function;
                                                                   $Reporttime=$dstate->datetime;
                                                                  foreach ($systemsData as $sys) {
                                                                     if (($sys->temperature_format)=="F") {
                                                                               $currentvalue= ConvFunc::valueconv($dstate->current_value,$commandparm);
                                                                          } else {
                                                                               $currentvalue = number_format($dstate->current_value, 1);
                                                                          }
                                                                      }

                                                                  // $currentvalue=$dstate->current_value;
                                                                   $currentalarm=$dstate->alarm_state;
                                                                  // find units for command type

                                                                   $deviceunits = DB::table('device_types')
                                                                               ->where('command',$dstate->command)
                                                                               ->get();
                                                           //        foreach ($deviceunits as $Unit)    {
                                                          //             $units=$Unit->units;
                                                                   foreach ($deviceunits as $Unit) {
                                                                           $T=$Unit->units;
                                                                            foreach ($systemsData as $sys) {
                                                                               $units = ConvFunc::unitconv($T,$sys->temperature_format);
                                                                                }
                                                                           }


                                                                }
                                                                if ($Reporting==0) {print_r("<font color='#1c94c4'><b>No Reports</b></font>");}
                                                                ?>
                                                               </div>
                                                               <div class="col-xs-3 row-padding"><font style="color: <?=$collabelcolor?>;">Current Value</font><BR>
                                                                  {{$function."- ".$currentvalue." ".$units}}
                                                                </div>
                                                                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Last Report</font><BR>
                                                                     {{$Reporttime}}
                                                                </div>

                                                              <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Alarm</font><BR>
                                                                   @if ($currentalarm==0)
                                                                       {{ HTML::image('images/greenbutton-small.png', 'No Alarms')}}
                                                                   @elseif ($currentalarm==1)
                                                                       {{ HTML::image('images/yellowbutton-small.png', 'Warning Alarms')}}
                                                                   @else
                                                                       {{ HTML::image('images/redbutton-small.png', 'Critical Alarms')}}
                                                                   @endif



                                                                </div>
                                              <!--  </div> -->

              </div>

            @endif
        @endforeach
                             </div>

                               <!--  wireless Sensor Inputs  -->

                              <h4 class="row-detail" style="color: #AAC7B5; width: 100%; height: 15px; font-size: 12pt" data-toggle="collapse" data-parent="#collapse-container"href="#system-devices-inwireless">&nbsp;&nbsp;EMC Wireless Sensors</h4>
                        <div id="system-devices-inwireless" class="container-fluid collapse">

        <div class="row" style="color: white; background-color: #01082A;">
            <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">EMC Wireless Sensors</div>



                                </div>
                              <!-- Wireless sensors  -->
                                 @if ($devicesin==Null) {{"No Wireless Sensors" }} @endif


                                 @foreach ($devicesin as $device)

              @if ($device->device_mode=="wireless")

                                                  <?php // find product type
                                                  {
                                                             foreach ($products as $product) {
                                     if ($product->product_id==$device->product_id) {
                                                                                   $PN=$product->name;
                                                                                   $PF=$product->function;
                                                                                   $PM=$product->mode;
                                                                      }
                                                             }
                                                  }
                                                  ?>
            <div class="row" style="color: white; background-color: #01082A;">
              <!--<div class="form-group">-->
                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Device # {{$device->id}}</font><BR>
                                                                        {{$PN}}<BR>
                  {{"Short Address<BR>".strtoupper(dechex($device->short_address))."<BR>".
                                                                        "Mac Address<BR>".$device->mac_address }}
                </div>


                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Zone</font><BR>
                                                                     {{$device->zone }}
                                                                </div>


                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Name/Physical Location</font><BR>
                                                                      {{$device->name."<BR>"}}
                                                                      {{$device->physical_location }}


                                                                </div>

                                                                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Status</font><BR>
                                                                  <?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
                  $Statusd=StatusLogicDecode($SV);
                                                                        print_r("<font color='".$Statusd[1]."'><b>".$Statusd[0]."</b></font>");
                                                                  ?>


                </div>

                <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">State</font><BR>
                                                               <?php
                                                               // for wireless and bacnet devices need to loop through all possible commands
                                                               // to determine status and latest value of each command
                                                               // output variables in an array for each commands and the loop thru to display
                                                                // first find the applible command types



                                                               $commandvec="";
                                                                     $commandtype= DB::table('product_types')
                                                                              ->join('devices','devices.product_id','=','product_types.product_id')
                                                                              ->where('devices.id',$device->id)
                                                                              ->where('system_id',$device->system_id)
                                                                              ->get();

                                                                   //   $commandvec=($commandtype->commands);

                                                                foreach ($commandtype as $ct)  { $commandvec=$ct->commands.","; }

                                                                    $i=0;
                                                                    $ilen=0;
                                                                    $Reporttimews[]="-";
                                                                    $unitsws[]="";
                                                                    $currentvaluews[]="";
                                                                    $functionws[]="";
                                                                    $currentalarmws[]="";
                                                                     $Reporting=0;
                                                               while ($commandvec<>"") {    // command loop
                                                                        $commandparm=substr($commandvec,0,strpos($commandvec,","));




                                                                   $devicestate = DB::table('device_data')
                                                                               ->join('devices','devices.id','=','device_data.id')
                                                                            // ->join('device_types','devices.device_types_id','=','device_types.id')
                                                                               ->where('device_data.system_id',$device->system_id)
                                                                               ->where('device_data.command',$commandparm)
                                                                               ->where('device_data.id',$device->id)
                                                                               ->orderby('datetime','desc')
                                                                               ->limit ('1')
                                                                               ->get();
                                                    //       dd(DB::getQueryLog());


                                                            // dd($devicestate) ;
                                                                foreach ($devicestate as $dstate) {
                                                                    $Reporting=1;

                                                                    if ($SV<5)  {  // report only active
                                                                       if ($commandparm!=2)   { // no state for voltage readings
                                                                          if ($dstate->current_state==1) {
                                                                            print_r("<font color='#1ccc94'><b>ON</b></font><BR>");

                                                                            } else { print_r("<font color='#1c94c4'><b>OFF</b></font><BR>"); }

                                                                      } else { print_r("-<BR>");}

                                                                    } else { print_r("<font color='#1c94c4'><b>OFF</b></font><BR>"); }
                                                                   $currentstate[$i] = $dstate->current_state;
                                                                   $Reporttimews[$i]=$dstate->datetime;
                                                                   if ($commandparm==2) {
                                                                         $currentvaluews[$i]=number_format($dstate->current_value/10,1);

                                                                   } else {  foreach ($systemsData as $sys)  {
                                                                            if (($sys->temperature_format)=="F") {
                                                                               $currentvaluews[$i]= ConvFunc::valueconv($dstate->current_value,$commandparm);
                                                                            } else {
                                                                               $currentvaluews[$i] = number_format($dstate->current_value, 1);
                                                                            }
                                                                        }   //$currentvaluews[$i]=number_format($dstate->current_value,1);
                                                                   }

                                                                   //$currentvaluews[$i]=number_format($dstate->current_value,1);
                                                                   $currentalarmws[$i]=$dstate->alarm_state;
                                                                  // find units for command type

                                                                   $deviceunits = DB::table('device_types')
                                                                               ->where('command',$commandparm)
                                                                               ->get();
                                                                   foreach ($deviceunits as $Unit)    {
                                                                       foreach ($systemsData as $sys) {
                                                                              $unitsws[$i] = ConvFunc::unitconv($Unit->units,$sys->temperature_format);
                                                                                }
                                                                     //  $unitsws[$i]=ConvFunc::unitconv($Unit->units,);
                                                                       $functionws[$i]=$Unit->function;
                                                                   }
                                                                $i++;
                                                                }
                                                              $commandvec=substr($commandvec,strpos($commandvec,",")+1,strlen($commandvec)-strpos($commandvec,","));

                                                              $ilen=$i;


                                                            }  // comand loop
                                                         //  dd($ilen);
                                                         //     dd($devicestate) ;
                                                             if ($Reporting==0) {print_r("<font color='#1c94c4'><b>No Reports</b></font>");}

                                                                ?>

                                                               </div>
                                                               <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Current Value</font><BR>
                                                                    <?php for ($j=0;$j<$ilen;$j++) {

                                                                     print_r($functionws[$j]."- ".$currentvaluews[$j]." ".$unitsws[$j]."<BR>");
                                                                     $functionws[$j]="";  $currentvaluews[$j]=""; $unitsws[$j]="";

                                                                     }
                                                                    ?>

                                                                </div>

                                                                <div class="col-xs-2 row-padding"><font style="color: <?=$collabelcolor?>;">Last Report</font><BR>
                                                                 <?php for ($j=0;$j<$ilen;$j++) {
                                                                     print_r($Reporttimews[$j]."<BR>");
                                                                     $Reporttimews[$j]=""; // clear for next device
                                                                     }
                                                                    ?>
                                                                </div>

                                                              <div class="col-xs-1 row-padding"><font style="color: <?=$collabelcolor?>;">Alarm</font><BR>

                                                                  <?php
                                                                     $Alarmdsp=0;
                                                                     for ($j=0;$j<$ilen;$j++) {
                                                                     if ($currentalarmws[$j] >  $Alarmdsp) {$Alarmdsp=$currentalarmws[$j];}
                                                                   }
                                                                     $i=0; $ilen=0;
                                                                     if ($Alarmdsp==0) {
                                                                        print_r(HTML::image('images/greenbutton-bk.png'));

                                                                     } elseif ($Alarmdsp==1) {
                                                                          print_r(HTML::image('images/yellowbutton-bk.png'));
                                                                     }
                                                                     else {   print_r(HTML::image('images/redbutton-bk.png'));
                                                                     }

                                                                    ?>

                                                                </div>
                                              <!--  </div> -->

              </div>



            @endif

        @endforeach
                             </div>

                            <!--  BACNet Sensor Inputs  -->

                              <h4 class="row-detail" style="color: #AAC7B5; width: 100%; height: 15px; font-size: 12pt"  data-toggle="collapse" data-parent="#collapse-container"href="#system-devices-inbacnet">&nbsp;&nbsp;EMC BACNet Sensors</h4>
                        <div id="system-devices-inbacnet" class="container-fluid collapse">

        <div class="row" style="color: white; background-color: #01082A;">
            <div class="col-xs-9 row-padding" style="font-weight: bold; font-size: 16pt; color:#1c94c4;">EMC BACnet Sensors</div>



                                </div>
                              <!-- bacnet sensors-->

                             </div>
  </main>

@stop
