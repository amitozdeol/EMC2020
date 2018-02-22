<?php $title="Maintenance"; ?>
@extends('layouts.wrapper')

@section('content')

<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Maintenance Detail</h3></div>

<div class="page-nav">
	<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
</div>

<br>

<div style="margin-left: auto; margin-right: auto; width: 90%;">
	<div id="accordion">
		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Stack Temperatures</h4>
		<div>
			<div id="stackchart" style="width: 85%; margin-left: auto; margin-right: auto; float: left"></div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Lamp Hours</h4>
				<div>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">Light - Front Entry</a></li>
   							<li><a href="#tabs-2" class="text-color">Light - Rear Entry</a></li>
  						</ul>
					  	<div id="tabs-1">
					    	<div id="lightchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left"></div>
			    	<script>
				    	var chart = new Highcharts.Chart({
			        		chart: { renderTo: 'lightchart', type: 'line', marginBottom: 80 },
				            title: { text: 'Monthly Lamp Hours' },
				            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				            yAxis: { min: 0, title: { text: 'Hours' } },
				            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
				            tooltip: {
				                formatter: function() {
				                    return '<b>'+ this.series.name +'</b><br/>'+
				                        this.x +': '+ this.y +'hours';
				                }
				            },
				            series: [{
				                name: 'Front Entry',
				                data: [740, 1480, 2960, 5920, 11840, 23680, {y: 47360, marker: { symbol: 'url(images/stop.png)' } }, 740, 1480, 2960, 5920, 11840],
				            }]
				        });
			    	</script>
			  	</div>
				<div id="tabs-2">
					<div id="lightchart2" style="width: 95%; margin-left: auto; margin-right: auto; float: left"></div>
					<script>
						var chart = new Highcharts.Chart({
			        		chart: { renderTo: 'lightchart2', type: 'line', marginBottom: 80 },
				            title: { text: 'Monthly Lamp Hours' },
				            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				            yAxis: { min: 0, title: { text: 'Hours' } },
				            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
				            tooltip: {
				                formatter: function() {
				                    return '<b>'+ this.series.name +'</b><br/>'+
				                        this.x +': '+ this.y +'hours';
				                }
				            },
				            series: [{
				                name: 'Rear Entry',
				                data: [370, 740, 1480, 2960, 5920, 11840, 23680, {y: 47360, marker: { symbol: 'url(images/stop.png)' } }, 740, 1480, 2960, 5920],
				            }]
				        });
					</script>
				</div>
			</div>
		</div>

		<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Service Alarms</h4>
		<div>
			<div id="servicetable" style="width: 100%; margin-left: auto; margin-right: auto">

				<div class="container-fluid">

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 row-padding" style="font-weight: bold">UNIT</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">LAST SERVICE</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">NEXT SERVICE</div>
						<div class="col-xs-3 row-padding" style="font-weight: bold">STATUS</div>
					</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 maintenance-row-padding">Burner 1</div>
						<div class="col-xs-3 maintenance-row-padding">Feb 27, 2013</div>
						<div class="col-xs-3 maintenance-row-padding">Feb 25, 2014</div>
						<div class="col-xs-3 maintenance-row-padding">{{HTML::image('images/redbutton.png')}}</div>
					</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 maintenance-row-padding">Burner 2</div>
						<div class="col-xs-3 maintenance-row-padding">March 10, 2014</div>
						<div class="col-xs-3 maintenance-row-padding">March 12, 2015</div>
						<div class="col-xs-3 maintenance-row-padding">{{HTML::image('images/greenbutton.png')}}</div>
					</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 maintenance-row-padding">Hot Water 1</div>
						<div class="col-xs-3 maintenance-row-padding">March 29, 2013</div>
						<div class="col-xs-3 maintenance-row-padding">March 30, 2014</div>
						<div class="col-xs-3 maintenance-row-padding">{{HTML::image('images/greenbutton.png')}}</div>
					</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 maintenance-row-padding">Hot Water 2</div>
						<div class="col-xs-3 maintenance-row-padding">March 29, 2013</div>
						<div class="col-xs-3 maintenance-row-padding">March 30, 2014</div>
						<div class="col-xs-3 maintenance-row-padding">{{HTML::image('images/yellowbutton.png')}}</div>
					</div>

					<div class="row" style="color: white; text-align: center; background-color: #01082A;">
						<div class="col-xs-3 maintenance-row-padding">Ventilation Fan</div>
						<div class="col-xs-3 maintenance-row-padding">December 15, 2013</div>
						<div class="col-xs-3 maintenance-row-padding">December 10, 2014</div>
						<div class="col-xs-3 maintenance-row-padding">{{HTML::image('images/greenbutton.png')}}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
