<?php $title="Environment"; ?>
@extends('layouts.wrapper')

@section('content')

@if ($thisBldg->name == "246 Manhattan Ave")
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Facilities Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Front Entrance</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (50%)</li><li>Light 2: Off</li><li>Occupancy: Occupied</li><li>Thermostat (T): 70 F</li><li>Humistat (H): 50%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightonoff.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/redbutton.png')}} Boiler Room &emsp; {{HTML::image('images/stop.png')}}  <span class="red-alarm">Door Open</span></h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (80%)</li><li>Light 2: On (70%)</li><li>Occupancy: Not Occupied</li><li>Thermostat (T): 50 F</li><li>Humistat (H): 30%</li><li>Door 1 (D1): Open</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roombad1.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Common Room</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (80%)</li><li>Light 2: On (80%)</li><li>Occupancy: Occupied</li><li>Thermostat (T): 72 F</li><li>Humistat (H): 60%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightonon.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 1st Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 2nd Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>
		</div>
	</div>
@elseif ($thisBldg->name == "191 Joralemon Street")
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Facilities Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Front Entrance</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (50%)</li><li>Light 2: Off</li><li>Occupancy: Occupied</li><li>Thermostat (T): 70 F</li><li>Humistat (H): 50%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightonoff.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Boiler Room</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: Off</li><li>Light 2: On (70%)</li><li>Occupancy: Not Occupied</li><li>Thermostat (T): 50 F</li><li>Humistat (H): 30%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightoffon.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/redbutton.png')}} Common Room &emsp; {{HTML::image('images/stop.png')}}  <span class="red-alarm">Door Open</span></h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (80%)</li><li>Light 2: On (80%)</li><li>Occupancy: Occupied</li><li>Thermostat (T): 72 F</li><li>Humistat (H): 60%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Open</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roombad2.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 1st Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 2nd Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>
		</div>
	</div>
@else
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Facilities Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>
	<br>


	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Front Entrance</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (50%)</li><li>Light 2: Off</li><li>Occupancy: Occupied</li><li>Thermostat (T): 70 F</li><li>Humistat (H): 50%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightonoff.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Boiler Room</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: Off</li><li>Light 2: On (70%)</li><li>Occupancy: Not Occupied</li><li>Thermostat (T): 50 F</li><li>Humistat (H): 30%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightoffon.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Common Room</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (80%)</li><li>Light 2: On (80%)</li><li>Occupancy: Occupied</li><li>Thermostat (T): 72 F</li><li>Humistat (H): 60%</li><li>Door 1 (D1): Closed</li><li>Door 2 (D2): Closed</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/roomlightonon.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 1st Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} 2nd Floor</h4>
			<div>
				<div id="infobox" style="width: 33%; margin-left: auto; margin-right: auto; float: left">
					<ul class="text-color">
						<li>Status:</li><li>Light 1: On (100%)</li><li>Light 2: On (100%)</li>
					</ul>
				</div>
				<div id="imagebox" style="width: 67%; margin-left: auto; margin-right: auto; float: left">
					{{HTML::image('images/floorplan.png')}}
				</div>
			</div>
		</div>
	</div>
@endif

@stop
