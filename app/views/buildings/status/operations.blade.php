<?php
  $chartJS='chartimport';
  $title="Chart";
?>

@extends('layouts.wrapper')

@section('content')

<div class="page-title"><h3>{{ $thisBldg->name }} - Operations Detail</h3></div>

<div class="page-nav">
	<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
</div>

<br>

<div style="margin-left: auto; margin-right: auto; width: 90%;">
	<div id="accordion">
		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Environment</h4>
		<div>
			<div id="tabs">
  				<ul>
    				<li><a href="#tabs-1" class="text-color">Temperatures - 5th Floor</a></li>
   					<li><a href="#tabs-2" class="text-color">Temperatures - ALL</a></li>
  				</ul>
				<div id="tabs-1">
					<div id="tempchart" style="width: 90%; margin-left: 0; margin-right: auto"></div>
				</div>
				<div id="tabs-2">
					<div id="alltempschart" style="width: 90%; margin-left: 0; margin-right: auto"></div>
				</div>
			</div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Systems</h4>
		<div>
			<div id="meterbox" style="width: 50%; margin-left: auto; margin-right: auto; float: left">
				{{HTML::image('images/sysinfo.png', 'sysinfo.png', array('class' => 'operations-imgs'))}}
			</div>
			<div id="imagebox" style="width: 50%; margin-left: auto; margin-right: auto; float: left">
				{{HTML::image('images/burners.png', 'burners.png', array('class' => 'operations-imgs'))}}
			</div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Monitors</h4>
		<div>
			<div id="imagebox" style="width: 100%; margin-left: auto; margin-right: auto">
				{{HTML::image('images/meters.png', 'meters.png', array('class' => 'operations-imgs'))}}
			</div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Lighting and Security</h4>
		<div>
			<div id="textbox" style="width: 100%; margin-left: auto; margin-right: auto">

				<div class="container-fluid">
					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 row-padding" style="font-weight: bold">AREA</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">LOCATION</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">LIGHT STATUS</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">ALARM STATUS</div>
					</div>
					<div class="row" style="color: white; text-align: center; background-color: #123E5D;">&nbsp;</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3">
							<div class="operations-row-padding" style="font-weight: bold">OUTSIDE</div>
							<div class="operations-row-padding">&nbsp;</div>
							<div class="operations-row-padding">&nbsp;</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">Front Entry</div>
							<div class="operations-row-padding">Garage Area</div>
							<div class="operations-row-padding">Rear Entry</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lightoff.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">SET</div>
							<div class="operations-row-padding">SET</div>
							<div class="operations-row-padding">SET</div>
						</div>
					</div>
					<div class="row" style="color: white; text-align: center; background-color: #123E5D;">&nbsp;</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3">
							<div class="operations-row-padding" style="font-weight: bold">GROUND FLOOR</div>
							<div class="operations-row-padding">&nbsp;</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">Lobby</div>
							<div class="operations-row-padding">Conference Room</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lightoff.png') }}</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">NOT SET</div>
							<div class="operations-row-padding">SET</div>
						</div>
					</div>
					<div class="row" style="color: white; text-align: center; background-color: #123E5D;">&nbsp;</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3">
							<div class="operations-row-padding" style="font-weight: bold">STAIRWELL 1</div>
							<div class="operations-row-padding">&nbsp;</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">Top Entrance</div>
							<div class="operations-row-padding">Bottom Entrance</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">SET</div>
							<div class="operations-row-padding">SET</div>
						</div>
					</div>
					<div class="row" style="color: white; text-align: center; background-color: #123E5D;">&nbsp;</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3">
							<div class="operations-row-padding" style="font-weight: bold">STAIRWELL 2</div>
							<div class="operations-row-padding">&nbsp;</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">Top Entrance</div>
							<div class="operations-row-padding">Bottom Entrance</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">SET</div>
							<div class="operations-row-padding">SET</div>
						</div>
					</div>
					<div class="row" style="color: white; text-align: center; background-color: #123E5D;">&nbsp;</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3">
							<div class="operations-row-padding" style="font-weight: bold">ROOF</div>
							<div class="operations-row-padding">&nbsp;</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">East Roof Access</div>
							<div class="operations-row-padding">West Roof Access</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">{{ HTML::image('images/lightoff.png') }}</div>
							<div class="operations-row-padding">{{ HTML::image('images/lighton.png') }}</div>
						</div>
						<div class="col-xs-3">
							<div class="operations-row-padding">SET</div>
							<div class="operations-row-padding">SET</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} EMC Status</h4>
		<div>
			<div id="emcstatus" style="width: 100%; margin-left: auto; margin-right: auto">

			</div>
		</div>

	</div>
</div>

@stop
