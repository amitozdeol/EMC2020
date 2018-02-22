<?php $title="Analytics"; ?>

@extends('layouts.wrapper')

@section('content')

<div class="page-title"><h3>{!! $thisBldg->name !!} - Analytics Detail</h3></div>

<div class="page-nav">
	<a href="{!! URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) !!}" style="color: white">Back to {!! $thisBldg->name !!}, System {!!$thisSystem->id!!} Overview</a>
</div>

<br>

<div style="margin-left: auto; margin-right: auto; width: 90%;">
	<div id="accordion">
		<h4 class="row-detail" style="width: 100%">{!!HTML::image('images/greenbutton.png')!!} Alarm Totals</h4>
		<div>
			<div id="monthtotals" style="width: 85%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<pre id="csv" style="display:none">Time and Place,Alarm Numbers
				Oct 400 W 139th,3
				Nov 526 W 139th,6
				Dec 243 Manhattan,2
				Jan 243 Manhattan,2
				Jan 38 Fort Washington,4
				Feb 243 Manhattan,3
				Feb 38 Fort Washington,6
				Mar 243 Manhattan,5
				Jan 526 W 139th,8
				Mar 38 Fort Washington,3
				Jan 400 W 139th,8
				Oct 243 Manhattan,2
				Mar 526 W 139th,5
				Mar 400 W 139th,2
				Nov 243 Manhattan,3
				Feb 526 W 139th,4
				Mar 100 Devine,2
				Oct 38 Fort Washington,6
				Mar 610 Waring,4
				Mar 1631 Eastern Pkwy,4
				Mar 191 Joralemon,7
				Feb 400 W 139th,2
				Mar 41 Eileen,1
				Feb 100 Devine,3
				Feb 610 Waring,5
				Feb 191 Joralemon,2
				Feb 41 Eileen,3
				Nov 38 Fort Washington,1
				Oct 526 W 139th,5
				Jan 100 Devine,4</pre>
			<div id="currentmonth" style="width: 85%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
		</div>

		<h4 class="row-detail" style="width: 100%">{!!HTML::image('images/greenbutton.png')!!} Alarm Durations - Current Month</h4>
				<div>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">243 Manhattan Ave.</a></li>
   							<li><a href="#tabs-2" class="text-color">38 Fort Washington</a></li>
   							<li><a href="#tabs-3" class="text-color">526 W 139th St.</a></li>
  						</ul>
					  	<div id="tabs-1">
							<div id="durations-1" style="width: 85%; margin-left: auto; margin-right: auto; float: left;  padding: 1px"></div>
				</div>
				<div id="tabs-2">
					<div id="durations-2" style="width: 85%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				</div>
				<div id="tabs-3">
					<div id="durations-3" style="width: 85%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
