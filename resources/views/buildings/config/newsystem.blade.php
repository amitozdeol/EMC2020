<?php $title="Add System"; ?>

@extends('layouts.wrapper')

@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/admin.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<?php
	$help_id='systemconfiguration';
?>

<div class="col-xs-12 col-sm-offset-3 col-sm-6 device-title" style="text-align: center;">
	{!! $thisBldg->name !!} - Add New System
</div>

@if (isset($newSys))
	<div style="padding: 10px">
		<div class="notify">
			New System # {!!$newSys->id !!} added.
		</div>
	</div>
@endif


<div class="col-xs-12">
	{!! Form::open(array("role" => "form")) !!}
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
  			<div class="col-xs-12 col-sm-4 row-padding">
	  			System Name
	  			{!! Form::text('name', 'EMC 20/20', array("class" => "form-control", "style" => "color:black")) !!}
  			</div>
            <div class="col-xs-12 col-sm-4 row-padding">
                Number of Extender Boards
                {!! Form::text('extender_boards', 0, array("class" => "form-control", "style" => "color:black")) !!}
            </div>
            <div class="col-xs-12 col-xs-offset-0 col-sm-3 col-sm-offset-1 row-padding" title="For Port-Forwarding, set to OFF">
				Dynamic Local IP Configuration
				<br>
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-lg btn-primary active">
						{!! Form::radio('downlink', 1, 'downlink') !!}
						ON
					</label>
					<label class="btn btn-lg btn-primary">
						{!! Form::radio('downlink', 0, 'downlink') !!}
						OFF
					</label>
				</div>
			</div>
        </div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
  			<div class="col-xs-12 col-sm-4 row-padding">
  				Number of Wired Relay Outputs
  				{!! Form::text('wired_relay_outputs', 0, array("class" => "form-control", "style" => "color:black")) !!}
				</div>
	        <div class="col-xs-12 col-sm-4 row-padding">
	        	Number of Wired Analog/Digital Sensors
	        	{!! Form::text('wired_sensors', 0, array("class" => "form-control", "style" => "color:black")) !!}
	        </div>
	        <div class="col-xs-12 col-sm-4 row-padding">
	            Number of System Zones
	            {!! Form::text('system_zones', 1, array("class" => "form-control", "style" => "color:black")) !!}
            </div>
    	</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
			<div class="col-xs-12 col-sm-4 row-padding">
				Number of Wireless EMC Sensors
				{!! Form::text('wireless_sensors', 0, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Number of Bacnet Devices
				{!! Form::text('bacnet_devices', 0, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Hardware Version
				{!! Form::select('hardware_version', array('001' => 'M4613-001', '011' => 'M4613-011'), '001', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
		</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
			<div class="col-xs-12 col-sm-4 row-padding">
				System Mode
				{!! Form::select('system_mode', array('0' => 'Reset', '1' => 'Operation', '2' => 'Commission'), 1, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Season Mode
				{!! Form::select('season_mode', array('0' => 'Winter', '1' => 'Summer'), 0, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Ethernet IP
				{!! Form::text('ethernet_ip', '0.0.0.0', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
		</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
			<div class="col-xs-12 col-sm-4 row-padding">
				Wireless IP
				{!! Form::text('wireless_ip', '0.0.0.0', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Ethernet Port
				{!! Form::text('ethernet_port', 20, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Wireless Port
				{!! Form::text('wireless_port', 20, array("class" => "form-control", "style" => "color:black")) !!}
			</div>
		</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
			<div class="col-xs-12 col-sm-4 row-padding" title="Associated with the ethernet-to-usb adapter">
				Ethernet MAC
				{!! Form::text('net_mac', '00:00:00:00:00:00', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Network Format
				{!! Form::select('coordinator_format', array('1' => 'BACnet', '2' => 'EMC Wireless', '4' => 'Inovonics', '3' => 'BACnet & EMC Wireless', '5' => 'BACnet & Inovonics', '6' => 'EMC Wireless & Inovonics'), '4', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
			<div class="col-xs-12 col-sm-4 row-padding">
				Temperature Format
				{!! Form::select('temperature_format', array('F' => 'Fahrenheit', 'C' => 'Centigrade'), '1', array("class" => "form-control", "style" => "color:black")) !!}
			</div>
		</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">&nbsp;</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">
		<div class="form-group">
  			<div class="col-xs-12" style="text-align: center"><button class="btn btn-lg btn-primary" type="submit">Save</button></div>
		</div>
	</div>
	<div class="col-xs-12 seamless_block_emc device-block stitch">&nbsp;</div>
	{!! Form::close() !!}
</div>
@stop
