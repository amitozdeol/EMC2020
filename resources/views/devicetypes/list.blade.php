<?php
  $admincss='admin';
  $title="Device Types";
  
?>

@extends('layouts.wrapper')

@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/responsive_table.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>

  <div class="jumbotron">
    <h2>Device Type</h2>
  </div>

  <div class="row">
    <br>
    <br>
    <br>
    <button class="btn btn-sm btn-primary pull-right" type="button" data-toggle="modal" data-target="#newTypeModal">Add New Device Type</button>
  </div>

  <!--New Device Type Modal to add device type to mode table -->
  <div class="modal fade" id="newTypeModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {!!Form::open(['route'=>'admin.devicetype.store'])!!}
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="typeModalLabel">Add Device Type</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-xs-12" style="margin-bottom:10px">
                  <div class="col-xs-6 col-sm-5"><label for="name">Name</label>
                    {!!Form::text('name',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-3"><label for="function">Function</label>
                    {!!Form::text('function',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="units">Units</label>
                    {!!Form::text('units',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="IO">IO</label>
                    {!!Form::select('IO',["Input" => "Input", "Output" => "Output"],"Input",["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="mode">Mode</label>
                    {!!Form::text('mode',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="setpoint">Set Point</label>
                    {!!Form::text('setpoint',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="hysteresis">Hysteresis</label>
                    {!!Form::text('hysteresis',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="alarm_high">Alarm High</label>
                    {!!Form::text('alarm_high',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="alarm_low">Alarm Low</label>
                    {!!Form::text('alarm_low',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="powerlevel">Power Level</label>
                    {!!Form::text('powerlevel',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="reporttime">Report Time</label>
                    {!!Form::text('reporttime',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-3"><label for="setpoint_active">Setpoint Active</label>
                    {!!Form::select('setpoint_active',["1" => "Active", "0" => "Inactive"],"1",["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-3"><label for="algorithm_active">Algorithm Active</label>
                    {!!Form::select('algorithm_active',["1" => "Active", "0" => "Inactive"],"1",["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="powerlevel">Gain</label>
                    {!!Form::text('gain',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="intercept">Intercept</label>
                    {!!Form::text('intercept',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                  <div class="col-xs-6 col-sm-3"><label for="state_above_setpoint">State Above Setpoint</label>
                    {!!Form::select('state_above_setpoint',["0" => "Low", "1" => "High"],"0",["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer" style="text-align:center">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
              {!!Form::submit('Save',["class" => "btn btn-sm btn-primary"])!!}
          </div>
          {!!Form::close()!!}
        </div>
      </div>
  </div>

  <div>
    @foreach($type_ios as $type_io)
      <h2 class="row">{!!$type_io->IO!!}</h2>
      <div class="scrollleftright" style="overflow: auto;">
        <div class="direction_left">
          <div class="left">
          	<svg version="1.1" class="arrow first" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
            	<polygon fill="rgba(0, 0, 0, 0.69)" points="59.476,30.991 8.39,30.991 23.921,15.46 22.493,14.032 4.524,32 22.493,49.969 23.921,48.543
            		8.389,33.009 59.476,33.009 	"/>
            </svg>
        	</div>
        </div>
        <div class="direction_right">
          <div class="right">
		        <svg version="1.1" class="arrow second" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
            	<polygon fill="rgba(0, 0, 0, 0.69)" points="59.476,30.991 8.39,30.991 23.921,15.46 22.493,14.032 4.524,32 22.493,49.969 23.921,48.543
            		8.389,33.009 59.476,33.009 	"/>
            </svg>
        	</div>
        </div>
        <!-- Table for displaying current values found in modes table -->
        <table class="responsive-table table table-hover">
          @foreach($type_modes as $mode)
            @if( $mode->IO == $type_io->IO)
              <thead>
                <th scope="row" colspan="15" style="background-color: #000; color: white; text-align: center;">{!!$mode->mode!!}</th>
                <tr class="theadrow"style="font-size: 13px;">
                    <th scope="col" style="vertical-align:top">Name</th>
                    <th scope="col" style="vertical-align:top">Function</th>
                    <th scope="col" style="vertical-align:top">Units</th>
                    <th scope="col" style="vertical-align:top">Hysteresis</th>
                    <th scope="col" style="vertical-align:top">Set Point</th>
                    <th scope="col" style="vertical-align:top">Alarm High</th>
                    <th scope="col" style="vertical-align:top">Alarm Low</th>
                    <th scope="col" style="vertical-align:top">Power Level</th>
                    <th scope="col" style="vertical-align:top">Report Time</th>
                    <th scope="col" style="vertical-align:top">Set Point Active</th>
                    <th scope="col" style="vertical-align:top">Algorithm Active</th>
                    <th scope="col" style="vertical-align:top">Gain</th>
                    <th scope="col" style="vertical-align:top">Intercept</th>
                    <th scope="col" style="vertical-align:top">State Above Set Point</th>
                    <th></th>
                </tr>
              </thead>
              <tbody>
              @foreach( $device_types as $type )
                @if( $type->mode == $mode->mode && $type->IO == $type_io->IO)
                  <tr>
                    <th scope="row">{!!$type->name!!}</th>
                    <td data-title="Function">{!!$type->function!!}</td>
                    <td data-title="Units">{!!$type->units!!}</td>
                    <td data-title="Hysteresis">{!!$type->hysteresis!!}</td>
                    <td data-title="Set Point">{!!$type->setpoint!!}</td>
                    <td data-title="Alarm High">{!!$type->alarm_high!!}</td>
                    <td data-title="Alarm Low">{!!$type->alarm_low!!}</td>
                    <td data-title="Power Level">{!!$type->powerlevel!!}</td>
                    <td data-title="Report Time">{!!$type->reporttime!!}</td>
                    <td data-title="Set Point Active">{!!$type->setpoint_active!!}</td>
                    <td data-title="Algorithm Active">{!!$type->algorithm_active!!}</td>
                    <td data-title="Gain">{!!$type->gain!!}</td>
                    <td data-title="Intercept">{!!$type->intercept!!}</td>
                    <td data-title="State Above Set Point">{!!$type->state_above_setpoint!!}</td>
                    <td><button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#{!!str_replace(' ','',$type->command)!!}Modal">Edit</button></td>
                  </tr>

                <!-- Edit device type modal for altering current entry in modes table-->
                <div class="modal fade" id="{!!str_replace(' ','',$type->command)!!}Modal" tabindex="-1" role="dialog" aria-labelledby="deviceTypeModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        {!!Form::open(['route'=>['admin.devicetype.update', $type->recnum], 'method'=>'put'])!!}
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deviceTypeModalLabel">Edit Device Type</h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-xs-12" style="margin-bottom:10px">
                                <div class="col-xs-4 col-sm-5"><label for="name">Name</label>
                                  {!!Form::text('name',$type->name,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-3"><label for="function">Function</label>
                                  {!!Form::text('function',$type->function,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="units">Units</label>
                                  {!!Form::text('units',$type->units,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="IO">IO</label>
                                  {!!Form::select('IO',["Input" => "Input", "Output" => "Output"],$type->IO,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-4"><label for="mode">Mode</label>
                                  {!!Form::text('mode',$type->mode,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="setpoint">Set Point</label>
                                  {!!Form::text('setpoint',$type->setpoint,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="hysteresis">Hysteresis</label>
                                  {!!Form::text('hysteresis',$type->hysteresis,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="alarm_high">Alarm High</label>
                                  {!!Form::text('alarm_high',$type->alarm_high,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="alarm_low">Alarm Low</label>
                                  {!!Form::text('alarm_low',$type->alarm_low,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-4"><label for="powerlevel">Power Level</label>
                                  {!!Form::text('powerlevel',$type->powerlevel,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-2"><label for="reporttime">Report Time</label>
                                  {!!Form::text('reporttime',$type->reporttime,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-3"><label for="setpoint_active">Setpoint Active</label>
                                  {!!Form::select('setpoint_active',["1" => "Active", "0" => "Inactive"],$type->setpoint_active,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-3"><label for="algorithm_active">Algorithm Active</label>
                                  {!!Form::select('algorithm_active',["1" => "Active", "0" => "Inactive"],$type->algorithm_active,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-4"><label for="powerlevel">Gain</label>
                                  {!!Form::text('gain',$type->gain,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-4 col-sm-4"><label for="intercept">Intercept</label>
                                  {!!Form::text('intercept',$type->intercept,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                                <div class="col-xs-6 col-sm-3"><label for="state_above_setpoint">State Above Setpoint</label>
                                  {!!Form::select('state_above_setpoint',["0" => "Low", "1" => "High"],$type->state_above_setpoint,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "title" => ""])!!}
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align:center">
                          <button type="button" class="btn btn-sm btn-default pull-right" data-dismiss="modal">Close</button>
                          {!!Form::submit('Save',["class" => "btn btn-sm btn-primary pull-right"])!!}
                          {!!Form::close()!!}
                          {!!Form::open(['route'=>['admin.devicetype.destroy', $type->recnum], 'method'=>'DELETE'])!!}
                                {!!Form::submit('Delete', ['class'=>'btn btn-sm btn-danger js-confirm pull-left', 'data-confirm'=>"Are you sure you want to delete this device type?\n\nThis cannot be undone."])!!}
                          {!!Form::close()!!}
                        </div>
                      </div>
                    </div>
                </div>
                @endif
              @endforeach
              </tbody>
            @endif
          @endforeach
        </table>
      </div>
    @endforeach
  </div>

<script>
  var container = document.getElementsByClassName('scrollleftright');
  var table = document.getElementsByClassName('responsive-table');
  //hide/show the scroll button when page loads
  if (container[0].offsetWidth >= table[0].offsetWidth) {
    $('.direction_left').hide();
    $('.direction_right').hide();
  }else{
    $('.direction_left').show();
    $('.direction_right').show();
  }
  function sideScroll(element,direction,speed,distance,step){
      scrollAmount = 0;
      var slideTimer = setInterval(function(){
          if(direction == 'left'){
              element[0].scrollLeft -= step;
              element[1].scrollLeft -= step;
          } else {
              element[0].scrollLeft += step;
              element[1].scrollLeft += step;
          }
          scrollAmount += step;
          if(scrollAmount >= distance){
              window.clearInterval(slideTimer);
          }
      }, speed);
  }
  $('.direction_left').click(function() {
    sideScroll(container,'left',25,100,10);
  });
  $('.direction_right').click(function() {
    sideScroll(container,'right',25,100,10);
  });
  //hide/show the scroll button when page resize
  window.onresize = function() {
    if (container[0].offsetWidth >= table[0].offsetWidth) {
      $('.direction_left').hide();
      $('.direction_right').hide();
    }else{
      $('.direction_left').show();
      $('.direction_right').show();
    }
  }
</script>
@stop
