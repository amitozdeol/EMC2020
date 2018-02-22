<?php $title="Edit Chart"; ?>

@extends('layouts.wrapper')

    @section('content')


      {{Form::open(['route'=>['charts.update', $thisBldg->id, $thisSys->id, $id], "method" => "put"])}}

      <?if ( isset($chart_attributes[$id]) ) { $chart_exists = 1; }
      else { $chart_exists = 0; }?>

      <a class="row col-xs-offset-10 btn btn-primary" style="text-align: right"  href="{{URL::route('webmapping.index', [$thisBldg->id, $thisSys->id])}}">Return to Web Mapping Setup</a>
      <h1>Edit Chart</h1><br>
      <div class="col-xs-12">
        <div class="col-xs-12 row-padding" style="text-align:center">
          <div class="col-xs-12">
            <div class="col-xs-4">
              <h3>Chart Title</h3>
                {{Form::text('title',$charts[$id]->label,["class" => "form-control" ,"id" => "title", "style" => "color:black"])}}
            </div>
            <div class="col-xs-8">
              <h3>Description</h3>
                {{Form::text('description', $chart_exists ? $chart_attributes[$id]->description : null,["class" => "form-control" ,"id" => "description", "style" => "color:black"])}}
            </div>
          </div>
        </div>
        <div class="col-xs-12 row-padding" style="text-align:center">
          <div class="col-xs-3">
            <h3>Device Type</h3>
            <div class="col-xs-12">
              {{Form::select('device_type', $device_types, $chart_exists ? $chart_attributes[$id]->function_type_1 : $charts[$id]->chart_type_1 ,["id" =>"device_type", "class" => "form-control", "style" => "color:black"])}}
            </div>
          </div>
          <div class="col-xs-3">
            <h3>Chart Type</h3>
            <div class="col-xs-12">
              {{Form::select('chart_type', $chart_lists[($chart_exists ? $chart_attributes[$id]->function_type_1 : $charts[$id]->chart_type)], $chart_exists ? $chart_attributes[$id]->chart_label_1 : key($chart_lists[$charts[$id]->chart_type]) ,["id" =>"chart_type", "class" => "form-control", "style" => "color:black"])}}
            </div>
          </div>
          <div class="col-xs-3">
            <h3>Zone</h3>
            <div class="col-xs-12">
              {{Form::select('zone', $device_zones, "0" ,["id" =>"zone", "class" => "form-control", "style" => "color:black"])}}
            </div>
          </div>
          <div class="col-xs-3" id="x_range_1_group">
            <h3>Time Range</h3>
            <div class="col-xs-12">
              {{Form::select('x_range_1_form', $x_ranges, $chart_exists ? $chart_attributes[$id]->x_range_1 : 2 ,["id" =>"x_range_1_form", "class" => "form-control", "style" => "color:black"])}}
            </div>
          </div>
        </div>
        <div class="col-xs-12 row-padding" style="text-align:center">
          <div class="hidden-chart" id="unavailable" style="display:none">
            <h3>Curently Unavailable</h3>
            <p>Sorry, this chart type is not available for use yet.</p>
          </div>
          <img class="web-snap-shot" id="chart_snap_shot" src="/images/line_graph_snap.png" style="width:50%">
        </div>
        <div class="col-xs-12 row-padding" id="devices">
          <div class="col-xs-12" id="sensor-devices">
            <h2>Sensor Devices</h2>
            @foreach($sensors as $sensor)
              @if($sensor->function == $charts[$id]->chart_type || ($chart_exists && $sensor->function == $chart_attributes[$id]->function_type_1))
              <div class="col-md-6 form-inline" style="display:block">
              @else
              <div class="col-md-6 form-inline" style="display:none">
              @endif
                <label class="btn btn-primary text-cell-box chart-sensor">
                  <div class="form-group">
                    @if($chart_exists && array_search(''.$sensor->id.'-'.$sensor->command.'', explode(',', $chart_attributes[$id]->devices_1)) !== false )
                      {{Form::checkbox($sensor->id.'-'.$sensor->command,'1', null,["class" => "check", "checked"])}}
                    @elseif(!$chart_exists && $sensor->function == $charts[$id]->chart_type)
                      {{Form::checkbox($sensor->id.'-'.$sensor->command,'1', null,["class" => "check", "checked"])}}
                    @else
                      {{Form::checkbox($sensor->id.'-'.$sensor->command,'1', null,["class" => "check"])}}
                    @endif
                  </div>
                  <div class="text-margin-left form-group">
                    @if($sensor->name == '')
                      <b>Device:</b> {{$sensor->device_id}}<br>
                      <b>Device Type:</b> {{$sensor->function}}<br>
                      <b>Zone:</b> {{$sensor->zonename}}<br>
                      @if($sensor->physical_location != '')
                        <b>Location:</b> {{$sensor->physical_location}}
                      @else
                        <b>Location:</b> Unknown
                      @endif
                    @else
                      <b>Device:</b> {{$sensor->name}}<br>
                      <b>Device Type:</b> {{$sensor->function}}<br>
                      <b>Zone:</b> {{$sensor->zonename}}<br>
                      @if($sensor->physical_location != '')
                        <b>Location:</b> {{$sensor->physical_location}}
                      @else
                        <b>Location:</b> Unknown
                      @endif
                    @endif
                  </div>
                </label>
              </div>
            @endforeach
          </div>
          <div class="col-xs-12 hidden" id="control-devices">
            <h2>Control Devices</h2>
            @foreach($controls as $control)
              <div class="col-md-6 form-inline" style="display: none">
                <label class="btn btn-primary text-cell-box chart-control">
                  <div class="form-group">
                    {{Form::checkbox($control->id.'-'.$control->command,'1', null,["class" => "check"])}}
                  </div>
                  <div class="text-margin-left form-group">
                    @if($control->name == '')
                      <b>Device:</b> {{$control->device_id}}<br>
                      <b>Device Type:</b> {{$control->function}}<br>
                      <b>Zone:</b> {{$control->zonename}}<br>
                      @if($control->physical_location != '')
                        <b>Location:</b> {{$control->physical_location}}
                      @else
                        <b>Location:</b> Unknown
                      @endif
                    @else
                      <b>Device:</b> {{$control->name}}<br>
                      <b>Device Type:</b> {{$control->function}}<br>
                      <b>Zone:</b> {{$control->zonename}}<br>
                      @if($control->physical_location != '')
                        <b>Location:</b> {{$control->physical_location}}
                      @else
                        <b>Location:</b> Unknown
                      @endif
                    @endif
                  </div>
                </label>
              </div>

            @endforeach
          </div>
          <div class="col-xs-12 hidden" id="no-devices" style="text-align:center">
            <h2>No Devices Available</h2>
            <p> You have no devices available in this zone of the specified device type.<br>
              If this seems incorrect, then please check to make sure you have set up all devices correctly.</p>
          </div>
        </div>
        <div class="col-xs-12 row-padding" style="margin-top:15px;">
        <div class="col-xs-4" style="text-align:center;">
          {{Form::submit('Save Chart', ["id" => "save" ,"class"=>"btn btn-lg btn-primary col-xs-8 col-xs-offset-2"])}}
        </div>
        <div class="col-xs-4" style="text-align:center;">
          <a class="btn btn-lg btn-primary col-xs-8 col-xs-offset-2" href="{{URL::route('webmapping.index', [$thisBldg->id, $thisSys->id])}}">Cancel</a>
        </div>
        <div id="hidden-form-group" hidden="hidden">
      {{Form::text('chart_type_1',$chart_exists ? $chart_attributes[$id]->chart_type_1 : null,["id" => "chart_type_1"])}}
      {{Form::text('chart_type_2',$chart_exists ? $chart_attributes[$id]->chart_type_2 : null,["id" => "chart_type_2"])}}
      {{Form::text('x_axis_1',$chart_exists ? $chart_attributes[$id]->x_axis : null,["id" => "x_axis_1"])}}
      {{Form::text('x_axis_2',$chart_exists ? $chart_attributes[$id]->x_axis : null,["id" => "x_axis_2"])}}
      {{Form::text('y_axis_1',$chart_exists ? $chart_attributes[$id]->y_axis_1 : null,["id" => "y_axis_1"])}}
      {{Form::text('y_axis_2',$chart_exists ? $chart_attributes[$id]->y_axis_2 : null,["id" => "y_axis_2"])}}
      {{Form::text('function_type_1',$chart_exists ? $chart_attributes[$id]->function_type_1 : null,["id" => "function_type_1"])}}
      {{Form::text('function_type_2',$chart_exists ? $chart_attributes[$id]->function_type_2 : null,["id" => "function_type_2"])}}
      {{Form::text('chart_label_1',$chart_exists ? $chart_attributes[$id]->chart_label_1 : null,["id" => "chart_label_1"])}}
      {{Form::text('chart_label_2',$chart_exists ? $chart_attributes[$id]->chart_label_2 : null,["id" => "chart_label_2"])}}
      {{Form::text('x_range_1',$chart_exists ? $chart_attributes[$id]->x_range_1 : null,["id" => "x_range_1"])}}
      {{Form::text('x_range_2',$chart_exists ? $chart_attributes[$id]->x_range_2 : null,["id" => "x_range_2"])}}
    </div>
      {{Form::close()}}
      <div class="col-xs-4" style="text-align:center;">
        {{Form::open(['route'=>['charts.destroy', $thisBldg->id, $thisSys->id, $id], "method" => "delete"])}}
          {{Form::submit('Delete Chart', ["class"=>"col-xs-8 col-xs-offset-2 btn btn-lg btn-danger js-confirm", "data-confirm" => "Are you sure you want to permanently remove this chart? This action cannot be undone."])}}
        {{Form::close()}}
      </div>
    </div>
      </div>

      <script>
        var chart_sensors = {{json_encode($sensors)}};
        var chart_controls = {{json_encode($controls)}};
        var chart_lists = {{json_encode($chart_lists)}};
        var thisSys = {{json_encode($thisSys)}};
        var thisBldg = {{json_encode($thisBldg)}};
        @if(isset($chart_attributes[$id]))
          var chartListStart = {{json_encode($chart_attributes[$id]->chart_label_1)}}
        @endif
      </script>

    @stop

@stop
