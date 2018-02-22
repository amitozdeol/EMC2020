<?php
  $add_new_url = URL::route('algorithm.create', [$id, $sid]);
  $title="Algorithm";
  
  
?>
<style type="text/css">
.alg-index{
  color: white;
}
.alg-sub-index{
  background-color: rgba(0,0,0,0.1);
}
.alg-sub-index-dark{
  background-color: rgba(0,0,0,0.3);
}

</style>

@if($mappedOutputs->isEmpty())
  <div class="col-xs-12 device-section" style="margin-top: 15pt;">
    <div class="col-xs-12 seamless_block_emc device-title " id="noAlgorithms" style="text-align: center;">
      No Devices Currently Mapped
    </div>
    @if(Auth::user()->auth_role >= 8)
      <div class="col-xs-12 col-sm-offset-4 col-sm-4 micro-row-detail device-block" style="text-align: center; cursor: pointer; margin-top: 15pt;" onclick="window.location='{{$add_new_url}}';">
        <p>Add New Output</p>
      </div>
    @endif
  </div>
@else
  <div class="col-xs-12 device-section">
  @if(Auth::user()->auth_role >= 8)
    <div class="col-xs-12 col-sm-offset-4 col-sm-4 micro-row-detail device-block" style="text-align: center; cursor: pointer; margin-top: 15pt; margin-bottom: 10pt; padding: 8pt 0pt 5pt 0pt;" onclick="window.location='{{$add_new_url}}';">
      <p>Add New Output</p>
    </div>
  @endif
  @foreach($mappedOutputs as $output)
        <div class="col-xs-12  device-block alg-index" id="{{$output->id}}" style="text-align: center;">
          <div class="col-xs-12  device-title" ><!--Name-->
              {{$output->algorithm_name}} 
          </div>
          <div class="col-xs-12 alg-sub-index-dark" ><!--Description-->
                {{$output->description}}
          </div>
          <div class="col-xs-12 alg-sub-index-dark" ><!--Control Function-->
            <small>
              Control Function:
            </small>
            @if($output->function_type == NULL)                
              Virtual Device
            @else
              {{$output->function_type}}
            @endif
          </div>
          <div class="col-xs-12 alg-sub-index" ><!--Priority Reporting-->
              @if((int)$output->priority_events == 1)
                <span style="color: #26EF4F" title="Algorithm descisions appear in events history">
                  Priority Reporting
                </span>
              @else
                <span style="color: red">
                  Non-Priority Reporting
                </span>
              @endif
          </div>
          <div class="col-xs-12 col-md-6" ><!--Zone-->
              <small>
                Zone:
              </small>
                @if($output->zone != 0)
                    {{strtoupper($zone_names[$output->zone])}}
                @else
                    N/A
                @endif
          </div>
          <div class="col-xs-6 col-md-3" ><!--Logic-->
              <small>
              Logic:
              </small>

              @if((int)$output->logicmode == 0)
                AND
              @elseif((int)$output->logicmode == 1)
                OR
              @elseif((int)$output->logicmode == 2)
                NAND
              @elseif((int)$output->logicmode == 3)
                NOR
              @elseif((int)$output->logicmode == 4)
                XOR
              @else
                {{$output->logicmode}}
              @endif
             </div>
          <div class="col-xs-6 col-md-3" ><!--Required Votes-->
              <small>
                Required Votes:
              </small>

              @if($output->min_required_inputs == 0)
                  None
              @elseif($output->min_required_inputs > 0)
                  {{$output->min_required_inputs}}
              @endif
          </div>
          <div class="col-xs-6 col-md-3 alg-sub-index"><!--On Delay-->
              <small>
                On Delay:
              </small>

              @if((int)$output->ondelay == 0)
                None
              @elseif((($output->ondelay)%60 == 0) && (($output->ondelay)%3600 != 0) && ((int)$output->ondelay != 0))
                {{($output->ondelay/60)}}
                @if(($output->ondelay/60) > 1)
                  Minutes
                @else
                  Minute
                @endif
              @elseif((($output->ondelay)%3600 == 0) && ((int)$output->ondelay != 0))
                {{($output->ondelay/3600)}}
                @if(($output->ondelay/3600) > 1)
                  Hours
                @else
                  Hour
                @endif
              @else
                {{$output->ondelay}}
                @if(((int)$output->ondelay) > 1)
                  Seconds
                @else
                  Second
                @endif
              @endif
          </div>
          <div class="col-xs-6 col-md-3 alg-sub-index"><!--Off Delay-->
              <small>
                Off Delay:
              </small>

                @if((int)$output->offdelay == 0)
                    None
                @elseif((($output->offdelay)%60 == 0) && (($output->offdelay)%3600 != 0) && ((int)$output->offdelay != 0))
                   {{$output->offdelay/60}}
                   @if(($output->offdelay/60) > 1)
                        Minutes
                   @else
                        Minute
                   @endif
                @elseif((($output->offdelay)%3600 == 0) && ((int)$output->offdelay != 0))
                   {{$output->offdelay/3600}}
                   @if(($output->offdelay/3600) > 1)
                        Hours
                   @else
                        Hour
                   @endif
                @else
                   {{$output->offdelay}}
                   @if(((int)$output->offdelay) > 1)
                        Seconds
                   @else
                        Second
                   @endif
                @endif
          </div>
          <div class="col-xs-12 col-md-6 alg-sub-index"><!--Duration-->
              <small>
                Duration:
              </small>

                @if((int)$output->duration == 0)
                    None
                @elseif((($output->duration)%60 == 0) && (($output->duration)%3600 != 0) && ((int)$output->duration != 0))
                   {{$output->duration/60}}
                   @if(($output->duration/60) > 1)
                        Minutes
                   @else
                        Minute
                   @endif
                @elseif((($output->duration)%3600 == 0) && ((int)$output->duration != 0))
                   {{$output->duration/3600}}
                   @if(($output->duration/3600) > 1)
                        Hours
                   @else
                        Hour
                   @endif
                @else
                   {{$output->duration}}
                   @if((int)($output->duration) > 1)
                        Seconds
                   @else
                        Second
                   @endif
                @endif
          </div>
          @if($output->default_state == 2)
            <div class="col-xs-12 col-md-4"><!--Default State-->
          @else
            <div class="col-xs-12 "><!--Default State-->
          @endif
              <small>
                Default State:
              </small>
              @if($output->default_state == 0)
                OFF
              @elseif($output->default_state == 1)
                ON
              @elseif($output->default_state == 2)
                TOGGLE
              @else
                UKNOWN
              @endif  
          </div>
          @if($output->default_state == 2)
            <div class="col-xs-12 col-md-4"><!--Toggle Percent-->
                <small>
                  Toggle Percent On:
                </small>
                  {{$output->default_toggle_percent_on}}%
            </div>
            <div class="col-xs-12 col-md-4"><!--Toggle Period-->
                <small>
                  Toggle Period:
                </small>
                  @if((int)$output->default_toggle_duration == 0)
                      None
                  @elseif((($output->default_toggle_duration)%60 == 0) && (($output->default_toggle_duration)%3600 != 0) && ((int)$output->default_toggle_duration != 0))
                     {{$output->default_toggle_duration/60}}
                     @if(($output->default_toggle_duration/60) > 1)
                        Minutes
                     @else
                        Minute
                     @endif
                  @elseif((($output->default_toggle_duration)%3600 == 0) && ((int)$output->default_toggle_duration != 0))
                     {{$output->default_toggle_duration/3600}}
                     @if(($output->default_toggle_duration/3600) > 1)
                        Hours
                     @else
                        Hour
                     @endif
                  @else
                     {{$output->default_toggle_duration}}
                     @if((int)($output->default_toggle_duration) > 1)
                        Seconds
                     @else
                        Second
                     @endif
                  @endif
            </div>
          @endif
          <div class="col-xs-6 col-sm-12 col-md-3 alg-sub-index" ><!--Season-->
              <small>
                Active Season:
              </small>

                @if((int)$output->season === 0)
                  Winter
                @elseif((int)$output->season === 1)
                  Summer
                @elseif((int)$output->season === 2)
                  Year-Round
                @endif
          </div>
          <div class="col-xs-6 col-sm-12 col-md-3 alg-sub-index" ><!--Response-->
              <small>
                Response:
              </small>

                @if((int)$output->response === 1)
                    On
                @elseif((int)$output->response === 0)
                    Off
                @endif
          </div>
          <div class="col-xs-12 col-md-6 alg-sub-index" ><!--Polarity-->
              <small>
                Polarity:
              </small>
              @if((int)$output->polarity === 1)
                  On
              @elseif((int)$output->polarity === 0)
                  Off
              @endif
          </div>
          <div class="col-xs-12">
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-0 btn btn-primary" style="margin-top: 7px;" data-toggle="modal" data-target="#device{{$output->device_id}}Modal"><!--Input Devices-->
              Inputs
                @if(array_key_exists($output->device_id, $used_retired_devices))
                  &nbsp;<span style="color: red;"><b><i class="glyphicon glyphicon-warning-sign retired-warning"></i></b></span>
                @endif
            </div>
            @if(Auth::user()->auth_role >= 8)
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-0 btn btn-primary" style="margin-top: 7px; text-align: center; cursor: pointer;" onclick="window.location='{{URL::route('algorithm.edit', [$id, $sid, $output->id])}}';"><!--Edit-->
              Edit
            </div>
            @endif
          </div>
        </div>
    <!-- device Modal -->
    <div class="modal fade" id="device{{$output->device_id}}Modal" tabindex="-1" role="dialog" aria-labelledby="device{{$output->device_id}}ModalLabel" aria-hidden="true" style="color: black">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="device{{$output->device_id}}ModalLabel" style="text-align:center">Input Devices</h3>
              </div>
              <div class="modal-body">
                <div id="helpTabs" role="tabpanel">
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active col-xs-4"><a href="#active_inputs_{{$output->device_id}}" aria-controls="active_inputs_{{$output->device_id}}" role="tab" data-toggle="tab" style="text-align:center">Active</a></li>
                    <li role="presentation" class="col-xs-4"><a href="#primary_inputs_{{$output->device_id}}" aria-controls="primary_inputs_{{$output->device_id}}" role="tab" data-toggle="tab" style="text-align:center">Primary</a></li>
                    <li role="presentation" class="col-xs-4"><a href="#secondary_inputs_{{$output->device_id}}" aria-controls="secondary_inputs_{{$output->device_id}}" role="tab" data-toggle="tab" style="text-align:center">Reserve</a></li>
                  </ul>
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="primary_inputs_{{$output->device_id}}" style="overflow:auto; max-height:60vh">
                      @if($output->inputs != '')
                        @foreach(explode(', ',str_replace('.', '', $output->inputs)) as $input => $id_type)
                            <div class="col-xs-12">
                              <p>
                                <?php $values = explode(' ',$id_type); ?>
                                @if(isset($values[0]) && isset($values[1]))
                                  <b>
                                    {{(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->name:"Missing Device [".$values[0]."]")}}
                                  </b>
                                  @if((isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->retired:0) == 1)
                                  <span style="color:red">
                                    Retired Device
                                  </span>
                                  @endif 
                                  <br>
                                  {{"Device Type: ".$device_types_list[$values[1]]->function}}
                                  @if($device_types_list[$values[1]]->IO == 'Input')
                                     Sensor
                                     <br>
                                  @elseif($device_types_list[$values[1]]->IO == 'Output')
                                     Algorithm/Controller
                                     <br>
                                  @endif
                                  {{"Physical Location: ".(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->physical_location:"Index Error")}}
                                  <br>
                                  <br>
                                @endif
                            </p>
                          </div>
                        @endforeach
                      @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="secondary_inputs_{{$output->device_id}}" style="overflow: auto; max-height:60vh">
                      @if($output->reserveinputs != '')
                          @foreach(explode(', ',str_replace('.', '', $output->reserveinputs)) as $input => $id_type)
                                <div class="col-xs-12">
                                  <p>
                                    <?php $values = explode(' ',$id_type); ?>
                                    @if(isset($values[0]) && isset($values[1]))
                                      @if((isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->name:' ') !== '')
                                        <b>
                                          {{(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->name:"Missing Device [".$values[0]."]")}}
                                        </b>
                                        @if((isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->retired:0) == 1)
                                          <span style="color:red">
                                            Retired Device
                                          </span>
                                        @endif
                                        <br>
                                      @else
                                        <b>
                                          Device {{(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->id:"Index ".$values[0])}}
                                        </b>
                                        <br>
                                      @endif
                                      {{"Device Type: ".$device_types_list[$values[1]]->function}}
                                      @if($device_types_list[$values[1]]->IO == 'Input')
                                         Sensor
                                         <br>
                                      @elseif($device_types_list[$values[1]]->IO == 'Output')
                                         Algorithm/Controller
                                         <br>
                                      @endif
                                      {{"Physical Location: ".(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->physical_location:"Index Error")}}
                                      <br>
                                      <br>
                                    @endif
                                </p>
                              </div>
                          @endforeach
                      @else
                      <div style="text-align:center">
                      <h3>No Reserve Devices</h3>
                      <p>You have no reserve devices mapped to this algorithm. Click edit to add reserve inputs.</p>
                    </div>
                      @endif
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="active_inputs_{{$output->device_id}}" style="overflow: auto; max-height:60vh">
                      @if(strlen($output->active_inputs) > 0)
                          @foreach(explode(', ',str_replace('.', '', $output->active_inputs)) as $input => $id_type)
                                <div class="col-xs-12">
                                  <p>
                                    <?php $values = explode(' ',$id_type); ?>
                                    @if(isset($values[0]) && isset($values[1]))
                                      @if((isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->name:'') !== '')
                                        <b>
                                          {{$device_names_list[$values[0]]->name}}
                                        </b>
                                        @if($device_names_list[$values[0]]->retired == 1)
                                          <span style="color:red">
                                            Retired Device
                                          </span>
                                        @endif<br>
                                      @else
                                        <b>
                                          Device {{(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->id:"Index ".$values[0])}}
                                        </b>
                                        <br>
                                      @endif
                                      {{"Device Type: ".(isset($device_types_list[$values[1]])?$device_types_list[$values[1]]->function:"...")}}
                                      @if((isset($device_types_list[$values[1]])?$device_types_list[$values[1]]->IO:' ') == 'Input')
                                         Sensor
                                         <br>
                                      @elseif((isset($device_types_list[$values[1]])?$device_types_list[$values[1]]->IO:' ') == 'Output')
                                         Algorithm/Controller
                                         <br>
                                      @endif
                                      {{"Physical Location: ".(isset($device_names_list[$values[0]])?$device_names_list[$values[0]]->physical_location:"N/A")}}
                                      <br>
                                      <br>
                                    @endif
                                </p>
                              </div>
                          @endforeach
                      @else
                      <div style="text-align:center">
                      <h3>No Active Devices</h3>
                      <p>You have lost all devices that are mapped to this algorithm. Click <i>Edit</i> to add reserve inputs.</p>
                    </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  @endforeach
  </div>
@endif
