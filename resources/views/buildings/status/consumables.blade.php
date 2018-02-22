<?php
  $chartJS='chartimport';
  $title="Consumerable";  
?>

@extends('layouts.wrapper')

@section('content')

@if ($thisBldg->name == "526 West 139th Street")
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Consumables Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/yellowbutton.png')}} Oil &emsp; {{HTML::image('images/caution.png')}}  <span class="yellow-alarm">Tank #1 Low</span></h4>
			<div>
				<div id="tankbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Tank #1</th>
							<th>Tank #2</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltanklow.png')}}
						        </td>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank2.png')}}
						        </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="oilchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script type="text/javascript">
					var chart = new Highcharts.Chart({
			              chart: { renderTo: 'oilchart', type: 'line' },
			              legend: { enabled: 1 },
			              loading: {hideDuration: 0},
			              title: {text: 'Oil Levels By Tank'},
			              plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
			              xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			              yAxis: {title: { text: 'Gallons' } },
			              series: [
						            {
						            	name: 'Tank 1',
						            	data: [900, 800, 650, 600, 580, 540, 494, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, 200, 150]
						            }, {
							            name: 'Tank 2',
							            data: [400, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 900, 850, 700, 620, 570, 500, 400]
							        }
						        ]
			          });
				</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Gas</h4>
			<div>
				<div id="gaschart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script>
					var chart = new Highcharts.Chart({
			        	chart: { renderTo: 'gaschart', zoomType: 'xy', marginBottom: 80 },
			        title: { text: 'Gas Usage and Outside Air' },
			        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			        yAxis: [{ // Primary yAxis
			            labels: {
			                formatter: function() {
			                    return this.value +'\xB0C';
			                },
			                style: {
			                    color: '#89A54E'
			                }
			            },
			            title: {
			                text: 'Temperature (\xB0C)',
			                style: {
			                    color: '#89A54E'
			                }
			            },
			        }, { // Secondary yAxis
			            gridLineWidth: 0,
			            title: {
			                text: 'Usage (cf)',
			                style: {
			                    color: '#4572A7'
			                }
			            },
			            labels: {
			                formatter: function() {
			                    return this.value +' cf';
			                },
			                style: {
			                    color: '#4572A7'
			                }
			            }
			        }],
			        tooltip: { shared: true },
			        legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
			        series: [{
			            name: 'Zone 1 Usage',
			            color: '#4572A7',
			            type: 'column',
			            yAxis: 1,
			            data: [6000, 5000, 4000, 3000, 2000, 1000, 2000, 3000, 5000, 6000, 6500, 7000],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Zone 2 Usage',
			            type: 'column',
			            color: '#AA4643',
			            yAxis: 1,
			            data: [5500, 4800, 3400, 2500, 1800, 800, 2200, 3200, 4500, 5000, 4000, 3500],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Temperature',
			            color: '#89A54E',
			            type: 'spline',
			            data: [5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8],
			            tooltip: {
			                valueSuffix: '\xB0C'
			            }
			        }]
			    });
			</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Water Usage</h4>
			<div>
				<div id="watertank" style="width: 250px; height: 250px; margin-left: auto; margin-right: auto"></div>
					<script>
						var chart = new Highcharts.Chart({
						    chart: {
							    renderTo: 'watertank',
						        type: 'gauge',
						        plotBackgroundColor: null,
						        plotBackgroundImage: null,
						        plotBorderWidth: 0,
						        plotShadow: false
						    },
						    title: { text: 'Make-Up Water Usage' },
						    pane: {
						        startAngle: -150,
						        endAngle: 150,
						        background: [{
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#FFF'],
						                    [1, '#333']
						                ]
						            },
						            borderWidth: 0,
						            outerRadius: '109%'
						        }, {
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#333'],
						                    [1, '#FFF']
						                ]
						            },
						            borderWidth: 1,
						            outerRadius: '107%'
						        }, {
						            // default background
						        }, {
						            backgroundColor: '#DDD',
						            borderWidth: 0,
						            outerRadius: '105%',
						            innerRadius: '103%'
						        }]
						    },
						    // the value axis
						    yAxis: {
						        min: 0,
						        max: 80,

						        minorTickInterval: 'auto',
						        minorTickWidth: 1,
						        minorTickLength: 10,
						        minorTickPosition: 'inside',
						        minorTickColor: '#666',

						        tickPixelInterval: 30,
						        tickWidth: 2,
						        tickPosition: 'inside',
						        tickLength: 10,
						        tickColor: '#666',
						        labels: {
						            step: 2,
						            rotation: 'auto'
						        },
						        title: {
						            text: 'gal'
						        },
						        plotBands: [{
						            from: 0,
						            to: 40,
						            color: '#55BF3B' // green
						        }, {
						            from: 40,
						            to: 60,
						            color: '#DDDF0D' // yellow
						        }, {
						            from: 60,
						            to: 80,
						            color: '#DF5353' // red
						        }]
						    },
						    series: [{
						        name: 'Gallons',
						        data: [35],
						        tooltip: {
						            valueSuffix: ' gal'
						        }
						    }]
						});
					</script>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">Water Usage</a></li>
   							<li><a href="#tabs-2" class="text-color">Make-up Water Usage</a></li>
  						</ul>
					  	<div id="tabs-1">
					    	<div id="waterchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				    	<script>
					    	var chart = new Highcharts.Chart({
				        		chart: { renderTo: 'waterchart', type: 'column', margin: [ 50, 50, 100, 80] },
					            title: { text: 'Monthly Average Water Usage' },
					            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
					            yAxis: { min: 0, title: { text: 'Usage (gal)' } },
					            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
					            tooltip: {
					                formatter: function() {
					                    return '<b>'+ this.series.name +'</b><br/>'+
					                        this.x +': '+ this.y +'gal';
					                }
					            },
					            series: [{
					                name: 'Zone 1',
					                data: [84.4, 121.8, 120.1, 220, 219.6, 219.5, 119.1, 118.4, 118,
					                    117.3, 116.8, 115],
					                dataLabels: {
					                    enabled: true,
					                    rotation: -90,
					    				x: 4,
					                    y: -20
					                }
					            }, {
					    			name: 'Zone 2',
					    			data: [89.4, 111.8, 110.1, 210, 214.6, 213.5, 100.1, 98.4, 115, 112.3, 126.8, 105],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					           	}, {
					    			name: 'Zone 3',
					    			data: [64.4, 101.8, 100.1, 180, 199.6, 179.5, 89.1, 98.4, 98, 107.3, 103.8, 95],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 4',
					    			data: [5.4, 5.8, 6.1, 6, 5.6, 5.5, 5.1, 5.4, 4, 4.3, 4.8, 5],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 5',
					    			data: [82.4, 116.8, 160.1, 200, 204.6, 206.5, 109.1, 98.4, 88, 101.3, 105.8, 109],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					            }]
					        });
				    	</script>
				  	</div>
					<div id="tabs-2">
						<div id="waterchart2" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
						<script>
						var chart = new Highcharts.Chart({
				            chart: { renderTo: 'waterchart2', zoomType: 'x', spacingRight: 20 },
				            tooltip: { shared: true },
				            legend: { enabled: false },
				            title: { text: 'Make-Up Water Usage' },
				            subtitle: {
				                text: document.ontouchstart === undefined ?
				                    'Click and drag in the plot area to zoom in' :
				                    'Pinch the chart to zoom in'
				            },
				            xAxis: {
				                type: 'datetime',
				                maxZoom: 4000 * 3600,
				                title: {
				                    text: null
				                }
				            },
				            yAxis: {
				                title: {
				                    text: 'Gallons'
				                },
				                plotLines : [{
									value : 60,
									color : 'red',
									dashStyle : 'shortdash',
									width : 2,
									label : {
										text : 'Limit'
									}
								}]
				            },
				            plotOptions: {
				                area: {
				                    fillColor: {
				                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
				                        stops: [
				                            [0, Highcharts.getOptions().colors[0]],
				                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
				                        ]
				                    },
				                    lineWidth: 1,
				                    marker: {
				                        radius: 2
				                    },
				                    shadow: false,
				                    states: {
				                        hover: {
				                            lineWidth: 1
				                        }
				                    },
				                    threshold: null
				                }
				            },
				            series: [{
				                type: 'area',
				                name: 'Tank 1',
				                pointInterval: 3600 * 4000,
				                pointStart: Date.UTC(2014, 0, 01),
				                data: [
										0, 4, 8, 16, 24, 40, 0, 8, 8, 20, 32, 24,
										0, 8, 12, 24, 24, 40, 0, 4, 8, 24, 32, 24,
										0, 8, 12, 20, 24, 20, 0, 8, 8, 20, 24, 28,
										0, 4, 16, 20, 28, 20, 0, 4, 16, 16, 24, 20,
										0, 8, 16, 20, 16, 24, 0, 4, 8, 16, 24, 20,
										0, 8, 12, 20, 32, 28, 0, 8, 12, 16, 28, 20,
										0, 8, 12, 24, 32, 32, 0, 4, 16, 24, 16, 28,
										0, 8, 16, 20, 24, 20, 0, 8, 16, 20, 32, 40,
										0, 4, 12, 24, 24, 32, 0, 8, 8, 20, 28, 24,
										0, 4, 16, 16, 16, 36, 0, 4, 12, 12, 32, 40,
										0, 8, 8, 16, 20, 28, 0, 8, 16, 24, 28, 24,
										0, 8, 12, 16, 28, 24, 0, 8, 16, 16, 28, 36,
										0, 8, 12, 24, 16, 40, 0, 4, 16, 12, 20, 40,
										0, 8, 8, 20, 16, 24, 0, 8, 16, 12, 24, 20,
										0, 4, 8, 12, 24, 20, 0, 4, 12, 20, 20, 36,
										0, 4, 16, 20, 32, 36, 0, 8, 8, 20, 28, 40,
										0, 4, 8, 20, 16, 24, 0, 8, 16, 24, 32, 36,
										0, 8, 12, 16, 20, 32, 0, 8, 8, 16, 28, 32,
										0, 8, 8, 24, 24, 20, 0, 8, 12, 16, 16, 24,
										0, 4, 12, 16, 24, 32, 0, 8, 8, 16, 28, 36,
										0, 8, 16, 12, 24, 24, 0, 4, 16, 12, 20, 28,
										0, 4, 16, 24, 32, 40, 0, 4, 16, 24, 28, 20,
										0, 8, 8, 16, 20, 28, 0, 8, 8, 12, 28, 36,
										0, 8, 8, 12, 16, 40, 0, 4, 16, 12, 28, 24,
										0, 8, 8, 16, 28, 40, 0, 4, 12, 24, 16, 20,
										0, 4, 16, 16, 28, 32, 0, 4, 8, 12, 24, 36,
										0, 8, 12, 12, 28, 32, 0, 4, 16, 12, 28, 24,
										0, 4, 12, 20, 32, 20, 0, 8, 8, 16, 28, 32,
										0, 4, 12, 20, 24, 20, 0, 8, 8, 12, 28, 36,
										0, 8, 12, 20, 24, 24, 0, 4, 12, 16, 28, 28,
										0, 8, 8, 20, 24, 28, 0, 8, 16, 24, 16, 28,
										0, 8, 16, 16, 32, 40, 0, 4, 8, 12, 16, 28,
										0, 8, 8, 12, 20, 24, 0, 4, 16, 20, 32, 20,
										0, 8, 8, 20, 24, 36, 0, 8, 8, 16, 20, 32,
										0, 8, 12, 12, 20, 20, 0, 8, 16, 12, 16, 40,
										0, 8, 8, 20, 16, 36, 0, 8, 12, 16, 32, 28,
										0, 4, 16, 12, 20, 35
				                ]
				            }]
					    });
						</script>
					</div>
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Electric</h4>
			<div>
				<div id="meterbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Electricity Meter</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 825px; height: 150px">
						            {{HTML::image('images/elecmeters.png')}}
						        </td>
					        </tr>
						</tbody>
					</table>
				</div>
				<div id="elecchart" style="width: 95%; margin-left: auto; margin-right: auto; float:left; padding: 1px"><div>
				<script>
					var chart = new Highcharts.Chart({
        				chart: { renderTo: 'elecchart', type: 'line' },
				        title: { text: 'Monthly Average Electricity Usage' },
				        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				        yAxis: { title: { text: 'Usage (kWh)' } },
				        plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
				        tooltip: {
				            enabled: false,
				            formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
				                    this.x +': '+ this.y +'kWh';
				            }
				        },
				        series: [{
				            name: 'Apt 5A',
				            data: [1000, 900, 1100, 1080, 800, 1200, 1140, 1060, 1000, 890, 800, 850]
				        }, {
							name: 'Apt 5B',
							data: [900, 1111, 1150, 1210, 1250, 1000, 900, 950, 980, 1000, 990, 970],
				       	}, {
							name: 'Apt 5C',
							data: [850, 870, 900, 950, 1000, 1100, 1050, 1020, 1030, 1100, 1060, 1040],
						}, {
							name: 'Apt 5D',
							data: [700, 700, 700, 700, 700, 800, 800, 800, 800, 700, 700, 700],
						}, {
							name: 'Apt 5E',
							data: [800, 880, 960, 1020, 1100, 1080, 1050, 980, 950, 920, 950, 960],
				        }]
				    });
				</script>
			</div>
		</div>
	</div>
@elseif ($thisBldg->name == "400 West 139th Street")
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Consumables Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

		<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Oil</h4>
			<div>
				<div id="tankbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Tank #1</th>
							<th>Tank #2</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank1.png')}}
						        </td>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank2.png')}}
						        </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="oilchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script type="text/javascript">
					var chart = new Highcharts.Chart({
			              chart: { renderTo: 'oilchart', type: 'line' },
			              legend: { enabled: 1 },
			              loading: {hideDuration: 0},
			              title: {text: 'Oil Levels By Tank'},
			              plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
			              xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			              yAxis: {title: { text: 'Gallons' } },
			              series: [
						            {
						            	name: 'Tank 1',
						                data: [900, 800, 650, 600, 580, 540, 494, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 800]
						            }, {
							            name: 'Tank 2',
							            data: [400, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 900, 850, 700, 620, 570, 500, 400]
							        }
						        ]
			          });
				</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/yellowbutton.png')}} Gas &emsp; {{HTML::image('images/caution.png')}}  <span class="yellow-alarm">Usage Mismatch</span></h4>
			<div>
				<div id="gaschart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script>
					var chart = new Highcharts.Chart({
			        	chart: { renderTo: 'gaschart', zoomType: 'xy', marginBottom: 80 },
			        title: { text: 'Gas Usage and Outside Air' },
			        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			        yAxis: [{ // Primary yAxis
			            labels: {
			                formatter: function() {
			                    return this.value +'\xB0C';
			                },
			                style: {
			                    color: '#89A54E'
			                }
			            },
			            title: {
			                text: 'Temperature (\xB0C)',
			                style: {
			                    color: '#89A54E'
			                }
			            },
			        }, { // Secondary yAxis
			            gridLineWidth: 0,
			            title: {
			                text: 'Usage (cf)',
			                style: {
			                    color: '#4572A7'
			                }
			            },
			            labels: {
			                formatter: function() {
			                    return this.value +' cf';
			                },
			                style: {
			                    color: '#4572A7'
			                }
			            }
			        }],
			        tooltip: { shared: true },
			        legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
			        series: [{
			            name: 'Zone 1 Usage',
			            color: '#4572A7',
			            type: 'column',
			            yAxis: 1,
			            data: [6000, 5000, 4000, 3000, 2000, 1000, 2000, 3000, 5000, 6000, 6500, 7000],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Zone 2 Usage',
			            type: 'column',
			            color: '#AA4643',
			            yAxis: 1,
			            data: [5500, 4800, 3400, 2500, 1800, 6800, 2200, 3200, 4500, 5000, 4000, 3500],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Temperature',
			            color: '#89A54E',
			            type: 'spline',
			            data: [5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8],
			            tooltip: {
			                valueSuffix: '\xB0C'
			            }
			        }]
			    });
			</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Water Usage</h4>
			<div>
				<div id="watertank" style="width: 250px; height: 250px; margin-left: auto; margin-right: auto"></div>
					<script>
						var chart = new Highcharts.Chart({
						    chart: {
							    renderTo: 'watertank',
						        type: 'gauge',
						        plotBackgroundColor: null,
						        plotBackgroundImage: null,
						        plotBorderWidth: 0,
						        plotShadow: false
						    },
						    title: { text: 'Make-Up Water Usage' },
						    pane: {
						        startAngle: -150,
						        endAngle: 150,
						        background: [{
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#FFF'],
						                    [1, '#333']
						                ]
						            },
						            borderWidth: 0,
						            outerRadius: '109%'
						        }, {
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#333'],
						                    [1, '#FFF']
						                ]
						            },
						            borderWidth: 1,
						            outerRadius: '107%'
						        }, {
						            // default background
						        }, {
						            backgroundColor: '#DDD',
						            borderWidth: 0,
						            outerRadius: '105%',
						            innerRadius: '103%'
						        }]
						    },
						    // the value axis
						    yAxis: {
						        min: 0,
						        max: 80,

						        minorTickInterval: 'auto',
						        minorTickWidth: 1,
						        minorTickLength: 10,
						        minorTickPosition: 'inside',
						        minorTickColor: '#666',

						        tickPixelInterval: 30,
						        tickWidth: 2,
						        tickPosition: 'inside',
						        tickLength: 10,
						        tickColor: '#666',
						        labels: {
						            step: 2,
						            rotation: 'auto'
						        },
						        title: {
						            text: 'gal'
						        },
						        plotBands: [{
						            from: 0,
						            to: 40,
						            color: '#55BF3B' // green
						        }, {
						            from: 40,
						            to: 60,
						            color: '#DDDF0D' // yellow
						        }, {
						            from: 60,
						            to: 80,
						            color: '#DF5353' // red
						        }]
						    },
						    series: [{
						        name: 'Gallons',
						        data: [35],
						        tooltip: {
						            valueSuffix: ' gal'
						        }
						    }]
						});
					</script>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">Water Usage</a></li>
   							<li><a href="#tabs-2" class="text-color">Make-up Water Usage</a></li>
  						</ul>
					  	<div id="tabs-1">
					    	<div id="waterchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				    	<script>
					    	var chart = new Highcharts.Chart({
				        		chart: { renderTo: 'waterchart', type: 'column', margin: [ 50, 50, 100, 80] },
					            title: { text: 'Monthly Average Water Usage' },
					            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
					            yAxis: { min: 0, title: { text: 'Usage (gal)' } },
					            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
					            tooltip: {
					                formatter: function() {
					                    return '<b>'+ this.series.name +'</b><br/>'+
					                        this.x +': '+ this.y +'gal';
					                }
					            },
					            series: [{
					                name: 'Zone 1',
					                data: [84.4, 121.8, 120.1, 220, 219.6, 219.5, 119.1, 118.4, 118,
					                    117.3, 116.8, 115],
					                dataLabels: {
					                    enabled: true,
					                    rotation: -90,
					    				x: 4,
					                    y: -20
					                }
					            }, {
					    			name: 'Zone 2',
					    			data: [89.4, 111.8, 110.1, 210, 214.6, 213.5, 100.1, 98.4, 115, 112.3, 126.8, 105],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					           	}, {
					    			name: 'Zone 3',
					    			data: [64.4, 101.8, 100.1, 180, 199.6, 179.5, 89.1, 98.4, 98, 107.3, 103.8, 95],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 4',
					    			data: [5.4, 5.8, 6.1, 6, 5.6, 5.5, 5.1, 5.4, 4, 4.3, 4.8, 5],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 5',
					    			data: [82.4, 116.8, 160.1, 200, 204.6, 206.5, 109.1, 98.4, 88, 101.3, 105.8, 109],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					            }]
					        });
				    	</script>
				  	</div>
					<div id="tabs-2">
						<div id="waterchart2" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
						<script>
						var chart = new Highcharts.Chart({
				            chart: { renderTo: 'waterchart2', zoomType: 'x', spacingRight: 20 },
				            tooltip: { shared: true },
				            legend: { enabled: false },
				            title: { text: 'Make-Up Water Usage' },
				            subtitle: {
				                text: document.ontouchstart === undefined ?
				                    'Click and drag in the plot area to zoom in' :
				                    'Pinch the chart to zoom in'
				            },
				            xAxis: {
				                type: 'datetime',
				                maxZoom: 4000 * 3600,
				                title: {
				                    text: null
				                }
				            },
				            yAxis: {
				                title: {
				                    text: 'Gallons'
				                },
				                plotLines : [{
									value : 60,
									color : 'red',
									dashStyle : 'shortdash',
									width : 2,
									label : {
										text : 'Limit'
									}
								}]
				            },
				            plotOptions: {
				                area: {
				                    fillColor: {
				                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
				                        stops: [
				                            [0, Highcharts.getOptions().colors[0]],
				                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
				                        ]
				                    },
				                    lineWidth: 1,
				                    marker: {
				                        radius: 2
				                    },
				                    shadow: false,
				                    states: {
				                        hover: {
				                            lineWidth: 1
				                        }
				                    },
				                    threshold: null
				                }
				            },
				            series: [{
				                type: 'area',
				                name: 'Tank 1',
				                pointInterval: 3600 * 4000,
				                pointStart: Date.UTC(2014, 0, 01),
				                data: [
										0, 4, 8, 16, 24, 40, 0, 8, 8, 20, 32, 24,
										0, 8, 12, 24, 24, 40, 0, 4, 8, 24, 32, 24,
										0, 8, 12, 20, 24, 20, 0, 8, 8, 20, 24, 28,
										0, 4, 16, 20, 28, 20, 0, 4, 16, 16, 24, 20,
										0, 8, 16, 20, 16, 24, 0, 4, 8, 16, 24, 20,
										0, 8, 12, 20, 32, 28, 0, 8, 12, 16, 28, 20,
										0, 8, 12, 24, 32, 32, 0, 4, 16, 24, 16, 28,
										0, 8, 16, 20, 24, 20, 0, 8, 16, 20, 32, 40,
										0, 4, 12, 24, 24, 32, 0, 8, 8, 20, 28, 24,
										0, 4, 16, 16, 16, 36, 0, 4, 12, 12, 32, 40,
										0, 8, 8, 16, 20, 28, 0, 8, 16, 24, 28, 24,
										0, 8, 12, 16, 28, 24, 0, 8, 16, 16, 28, 36,
										0, 8, 12, 24, 16, 40, 0, 4, 16, 12, 20, 40,
										0, 8, 8, 20, 16, 24, 0, 8, 16, 12, 24, 20,
										0, 4, 8, 12, 24, 20, 0, 4, 12, 20, 20, 36,
										0, 4, 16, 20, 32, 36, 0, 8, 8, 20, 28, 40,
										0, 4, 8, 20, 16, 24, 0, 8, 16, 24, 32, 36,
										0, 8, 12, 16, 20, 32, 0, 8, 8, 16, 28, 32,
										0, 8, 8, 24, 24, 20, 0, 8, 12, 16, 16, 24,
										0, 4, 12, 16, 24, 32, 0, 8, 8, 16, 28, 36,
										0, 8, 16, 12, 24, 24, 0, 4, 16, 12, 20, 28,
										0, 4, 16, 24, 32, 40, 0, 4, 16, 24, 28, 20,
										0, 8, 8, 16, 20, 28, 0, 8, 8, 12, 28, 36,
										0, 8, 8, 12, 16, 40, 0, 4, 16, 12, 28, 24,
										0, 8, 8, 16, 28, 40, 0, 4, 12, 24, 16, 20,
										0, 4, 16, 16, 28, 32, 0, 4, 8, 12, 24, 36,
										0, 8, 12, 12, 28, 32, 0, 4, 16, 12, 28, 24,
										0, 4, 12, 20, 32, 20, 0, 8, 8, 16, 28, 32,
										0, 4, 12, 20, 24, 20, 0, 8, 8, 12, 28, 36,
										0, 8, 12, 20, 24, 24, 0, 4, 12, 16, 28, 28,
										0, 8, 8, 20, 24, 28, 0, 8, 16, 24, 16, 28,
										0, 8, 16, 16, 32, 40, 0, 4, 8, 12, 16, 28,
										0, 8, 8, 12, 20, 24, 0, 4, 16, 20, 32, 20,
										0, 8, 8, 20, 24, 36, 0, 8, 8, 16, 20, 32,
										0, 8, 12, 12, 20, 20, 0, 8, 16, 12, 16, 40,
										0, 8, 8, 20, 16, 36, 0, 8, 12, 16, 32, 28,
										0, 4, 16, 12, 20, 35
				                ]
				            }]
					    });
						</script>
					</div>
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Electric</h4>
			<div>
				<div id="meterbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Electricity Meter</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 825px; height: 150px">
						            {{HTML::image('images/elecmeters.png')}}
						        </td>
					        </tr>
						</tbody>
					</table>
				</div>
				<div id="elecchart" style="width: 95%; margin-left: auto; margin-right: auto; float:left; padding: 1px"><div>
				<script>
					var chart = new Highcharts.Chart({
        				chart: { renderTo: 'elecchart', type: 'line' },
				        title: { text: 'Monthly Average Electricity Usage' },
				        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				        yAxis: { title: { text: 'Usage (kWh)' } },
				        plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
				        tooltip: {
				            enabled: false,
				            formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
				                    this.x +': '+ this.y +'kWh';
				            }
				        },
				        series: [{
				            name: 'Apt 5A',
				            data: [1000, 900, 1100, 1080, 800, 1200, 1140, 1060, 1000, 890, 800, 850]
				        }, {
							name: 'Apt 5B',
							data: [900, 1111, 1150, 1210, 1250, 1000, 900, 950, 980, 1000, 990, 970],
				       	}, {
							name: 'Apt 5C',
							data: [850, 870, 900, 950, 1000, 1100, 1050, 1020, 1030, 1100, 1060, 1040],
						}, {
							name: 'Apt 5D',
							data: [700, 700, 700, 700, 700, 800, 800, 800, 800, 700, 700, 700],
						}, {
							name: 'Apt 5E',
							data: [800, 880, 960, 1020, 1100, 1080, 1050, 980, 950, 920, 950, 960],
				        }]
				    });
				</script>
			</div>
		</div>
	</div>
@elseif ($thisBldg->name == "191 Joralemon Street")
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Consumables Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Oil</h4>
			<div>
				<div id="tankbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Tank #1</th>
							<th>Tank #2</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank1.png')}}
						        </td>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank2.png')}}
						        </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="oilchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script type="text/javascript">
					var chart = new Highcharts.Chart({
			              chart: { renderTo: 'oilchart', type: 'line' },
			              legend: { enabled: 1 },
			              loading: {hideDuration: 0},
			              title: {text: 'Oil Levels By Tank'},
			              plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
			              xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			              yAxis: {title: { text: 'Gallons' } },
			              series: [
						            {
						            	name: 'Tank 1',
						                data: [900, 800, 650, 600, 580, 540, 494, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 800]
						            }, {
							            name: 'Tank 2',
							            data: [400, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 900, 850, 700, 620, 570, 500, 400]
							        }
						        ]
			          });
				</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Gas</h4>
			<div>
				<div id="gaschart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script>
					var chart = new Highcharts.Chart({
			        	chart: { renderTo: 'gaschart', zoomType: 'xy', marginBottom: 80 },
				        title: { text: 'Gas Usage and Outside Air' },
				        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				        yAxis: [{ // Primary yAxis
				            labels: {
				                formatter: function() {
				                    return this.value +'\xB0C';
				                },
				                style: {
				                    color: '#89A54E'
				                }
				            },
				            title: {
				                text: 'Temperature (\xB0C)',
				                style: {
				                    color: '#89A54E'
				                }
				            },
				        }, { // Secondary yAxis
				            gridLineWidth: 0,
				            title: {
				                text: 'Usage (cf)',
				                style: {
				                    color: '#4572A7'
				                }
				            },
				            labels: {
				                formatter: function() {
				                    return this.value +' cf';
				                },
				                style: {
				                    color: '#4572A7'
				                }
				            }
				        }],
				        tooltip: { shared: true },
				        legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
				        series: [{
				            name: 'Zone 1 Usage',
				            color: '#4572A7',
				            type: 'column',
				            yAxis: 1,
				            data: [6000, 5000, 4000, 3000, 2000, 1000, 2000, 3000, 5000, 6000, 6500, 7000],
				            tooltip: {
				                valueSuffix: ' cf'
				            }
				        }, {
				            name: 'Zone 2 Usage',
				            type: 'column',
				            color: '#AA4643',
				            yAxis: 1,
				            data: [5500, 4800, 3400, 2500, 1800, 800, 2200, 3200, 4500, 5000, 4000, 3500],
				            tooltip: {
				                valueSuffix: ' cf'
				            }
				        }, {
				            name: 'Temperature',
				            color: '#89A54E',
				            type: 'spline',
				            data: [5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8],
				            tooltip: {
				                valueSuffix: '\xB0C'
				            }
				        }]
				    });
				</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/yellowbutton.png')}} Water Usage &emsp; {{HTML::image('images/caution.png')}}  <span class="yellow-alarm">Make-up Water High</span></h4>
			<div>
				<div id="tankboxbad" style="width: 250px; height: 250px; margin-left: auto; margin-right: auto"></div>
					<script>
						var chart = new Highcharts.Chart({
						    chart: {
							    renderTo: 'tankboxbad',
						        type: 'gauge',
						        plotBackgroundColor: null,
						        plotBackgroundImage: null,
						        plotBorderWidth: 0,
						        plotShadow: false
						    },
						    title: { text: 'Make-Up Water Usage' },
						    pane: {
						        startAngle: -150,
						        endAngle: 150,
						        background: [{
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#FFF'],
						                    [1, '#333']
						                ]
						            },
						            borderWidth: 0,
						            outerRadius: '109%'
						        }, {
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#333'],
						                    [1, '#FFF']
						                ]
						            },
						            borderWidth: 1,
						            outerRadius: '107%'
						        }, {
						            // default background
						        }, {
						            backgroundColor: '#DDD',
						            borderWidth: 0,
						            outerRadius: '105%',
						            innerRadius: '103%'
						        }]
						    },
						    // the value axis
						    yAxis: {
						        min: 0,
						        max: 80,

						        minorTickInterval: 'auto',
						        minorTickWidth: 1,
						        minorTickLength: 10,
						        minorTickPosition: 'inside',
						        minorTickColor: '#666',

						        tickPixelInterval: 30,
						        tickWidth: 2,
						        tickPosition: 'inside',
						        tickLength: 10,
						        tickColor: '#666',
						        labels: {
						            step: 2,
						            rotation: 'auto'
						        },
						        title: {
						            text: 'gal'
						        },
						        plotBands: [{
						            from: 0,
						            to: 40,
						            color: '#55BF3B' // green
						        }, {
						            from: 40,
						            to: 60,
						            color: '#DDDF0D' // yellow
						        }, {
						            from: 60,
						            to: 80,
						            color: '#DF5353' // red
						        }]
						    },
						    series: [{
						        name: 'Gallons',
						        data: [65],
						        tooltip: {
						            valueSuffix: ' gal'
						        }
						    }]
						});
					</script>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">Water Usage</a></li>
   							<li><a href="#tabs-2" class="text-color">Make-up Water Usage</a></li>
  						</ul>
					  	<div id="tabs-1">
					    	<div id="waterchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				    	<script>
					    	var chart = new Highcharts.Chart({
				        		chart: { renderTo: 'waterchart', type: 'column', margin: [ 50, 50, 100, 80] },
					            title: { text: 'Monthly Average Water Usage' },
					            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
					            yAxis: { min: 0, title: { text: 'Usage (gal)' } },
					            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
					            tooltip: {
					                formatter: function() {
					                    return '<b>'+ this.series.name +'</b><br/>'+
					                        this.x +': '+ this.y +'gal';
					                }
					            },
					            series: [{
					                name: 'Zone 1',
					                data: [84.4, 121.8, 120.1, 220, 219.6, 219.5, 119.1, 118.4, 118,
					                    117.3, 116.8, 115],
					                dataLabels: {
					                    enabled: true,
					                    rotation: -90,
					    				x: 4,
					                    y: -20
					                }
					            }, {
					    			name: 'Zone 2',
					    			data: [89.4, 111.8, 110.1, 210, 214.6, 213.5, 100.1, 98.4, 115, 112.3, 126.8, 105],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					           	}, {
					    			name: 'Zone 3',
					    			data: [64.4, 101.8, 100.1, 180, 199.6, 179.5, 89.1, 98.4, 98, 107.3, 103.8, 95],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 4',
					    			data: [5.4, 5.8, 6.1, 6, 5.6, 5.5, 5.1, 5.4, 4, 4.3, 4.8, 5],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 5',
					    			data: [82.4, 116.8, 160.1, 200, 204.6, 206.5, 109.1, 98.4, 88, 101.3, 105.8, 109],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					            }]
					        });
				    	</script>
				  	</div>
					<div id="tabs-2">
						<div id="waterchart2" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
						<script>
							var chart = new Highcharts.Chart({
					            chart: { renderTo: 'waterchart2', zoomType: 'x', spacingRight: 20 },
					            tooltip: { shared: true },
					            legend: { enabled: false },
					            title: { text: 'Make-Up Water Usage' },
					            subtitle: {
					                text: document.ontouchstart === undefined ?
					                    'Click and drag in the plot area to zoom in' :
					                    'Pinch the chart to zoom in'
					            },
					            xAxis: {
					                type: 'datetime',
					                maxZoom: 4000 * 3600,
					                title: {
					                    text: null
					                }
					            },
					            yAxis: {
					                title: {
					                    text: 'Gallons'
					                },
					                plotLines : [{
										value : 60,
										color : 'red',
										dashStyle : 'shortdash',
										width : 2,
										label : {
											text : 'Limit'
										}
									}]
					            },
					            plotOptions: {
					                area: {
					                    fillColor: {
					                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
					                        stops: [
					                            [0, Highcharts.getOptions().colors[0]],
					                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
					                        ]
					                    },
					                    lineWidth: 1,
					                    marker: {
					                        radius: 2
					                    },
					                    shadow: false,
					                    states: {
					                        hover: {
					                            lineWidth: 1
					                        }
					                    },
					                    threshold: null
					                }
					            },
					            series: [{
					                type: 'area',
					                name: 'Tank 1',
					                pointInterval: 3600 * 4000,
					                pointStart: Date.UTC(2014, 0, 01),
					                data: [
											0, 4, 8, 16, 24, 40, 0, 8, 8, 20, 32, 24,
											0, 8, 12, 24, 24, 40, 0, 4, 8, 24, 32, 24,
											0, 8, 12, 20, 24, 20, 0, 8, 8, 20, 24, 28,
											0, 4, 16, 20, 28, 20, 0, 4, 16, 16, 24, 20,
											0, 8, 16, 20, 16, 24, 0, 4, 8, 16, 24, 20,
											0, 8, 12, 20, 32, 28, 0, 8, 12, 16, 28, 20,
											0, 8, 12, 24, 32, 32, 0, 4, 16, 24, 16, 28,
											0, 8, 16, 20, 24, 20, 0, 8, 16, 20, 32, 40,
											0, 4, 12, 24, 24, 32, 0, 8, 8, 20, 28, 24,
											0, 4, 16, 16, 16, 36, 0, 4, 12, 12, 32, 40,
											0, 8, 8, 16, 20, 28, 0, 8, 16, 24, 28, 24,
											0, 8, 12, 16, 28, 24, 0, 8, 16, 16, 28, 36,
											0, 8, 12, 24, 16, 40, 0, 4, 16, 12, 20, 40,
											0, 8, 8, 20, 16, 24, 0, 8, 16, 12, 24, 20,
											0, 4, 8, 12, 24, 20, 0, 4, 12, 20, 20, 36,
											0, 4, 16, 20, 32, 36, 0, 8, 8, 20, 28, 40,
											0, 4, 8, 20, 16, 24, 0, 8, 16, 24, 32, 36,
											0, 8, 12, 16, 20, 32, 0, 8, 8, 16, 28, 32,
											0, 8, 8, 24, 24, 20, 0, 8, 12, 16, 16, 24,
											0, 4, 12, 16, 24, 32, 0, 8, 8, 16, 28, 36,
											0, 8, 16, 12, 24, 24, 0, 4, 16, 12, 20, 28,
											0, 4, 16, 24, 32, 40, 0, 4, 16, 24, 28, 20,
											0, 8, 8, 16, 20, 28, 0, 8, 8, 12, 28, 36,
											0, 8, 8, 12, 16, 40, 0, 4, 16, 12, 28, 24,
											0, 8, 8, 16, 28, 40, 0, 4, 12, 24, 16, 20,
											0, 4, 16, 16, 28, 32, 0, 4, 8, 12, 24, 36,
											0, 8, 12, 12, 28, 32, 0, 4, 16, 12, 28, 24,
											0, 4, 12, 20, 32, 20, 0, 8, 8, 16, 28, 32,
											0, 4, 12, 20, 24, 20, 0, 8, 8, 12, 28, 36,
											0, 8, 12, 20, 24, 24, 0, 4, 12, 16, 28, 28,
											0, 8, 8, 20, 24, 28, 0, 8, 16, 24, 16, 28,
											0, 8, 16, 16, 32, 40, 0, 4, 8, 12, 16, 28,
											0, 8, 8, 12, 20, 24, 0, 4, 16, 20, 32, 20,
											0, 8, 8, 20, 24, 36, 0, 8, 8, 16, 20, 32,
											0, 8, 12, 12, 20, 20, 0, 8, 16, 12, 16, 40,
											0, 8, 8, 20, 16, 36, 0, 8, 12, 16, 32, 28,
											0, 4, 16, 12, 40, {y: 65, marker: { symbol: 'url(images/stop.png)' } }
					                ]
					            }]
						    });
						</script>
					</div>
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Electric</h4>
			<div>
				<div id="meterbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Electricity Meter</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 825px; height: 150px">
						            {{HTML::image('images/elecmeters.png')}}
						        </td>
					        </tr>
						</tbody>
					</table>
				</div>
				<div id="elecchart" style="width: 95%; margin-left: auto; margin-right: auto; float:left; padding: 1px"><div>
				<script>
					var chart = new Highcharts.Chart({
        				chart: { renderTo: 'elecchart', type: 'line' },
				        title: { text: 'Monthly Average Electricity Usage' },
				        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				        yAxis: { title: { text: 'Usage (kWh)' } },
				        plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
				        tooltip: {
				            enabled: false,
				            formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
				                    this.x +': '+ this.y +'kWh';
				            }
				        },
				        series: [{
				            name: 'Apt 5A',
				            data: [1000, 900, 1100, 1080, 800, 1200, 1140, 1060, 1000, 890, 800, 850]
				        }, {
							name: 'Apt 5B',
							data: [900, 1111, 1150, 1210, 1250, 1000, 900, 950, 980, 1000, 990, 970],
				       	}, {
							name: 'Apt 5C',
							data: [850, 870, 900, 950, 1000, 1100, 1050, 1020, 1030, 1100, 1060, 1040],
						}, {
							name: 'Apt 5D',
							data: [700, 700, 700, 700, 700, 800, 800, 800, 800, 700, 700, 700],
						}, {
							name: 'Apt 5E',
							data: [800, 880, 960, 1020, 1100, 1080, 1050, 980, 950, 920, 950, 960],
				        }]
				    });
				</script>
			</div>
		</div>
	</div>
@else
	<div class="page-title" style="height: 95px; line-height: 55px"><h3>{{ $thisBldg->name }} - Consumables Detail</h3></div>

	<div class="page-nav">
		<a href="{{ URL::to('building', array($thisBldg->id, 'system', $thisSystem->id)) }}" style="color: white">Back to {{ $thisBldg->name }}, System {{$thisSystem->id}} Overview</a>
	</div>

	<br>

	<div style="margin-left: auto; margin-right: auto; width: 90%;">
		<div id="accordion">
			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Oil</h4>
			<div>
				<div id="tankbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Tank #1</th>
							<th>Tank #2</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank1.png')}}
						        </td>
						        <td style="width: 250px; height: 150px">
						            {{HTML::image('images/oiltank2.png')}}
						        </td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="oilchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script type="text/javascript">
					var chart = new Highcharts.Chart({
			              chart: { renderTo: 'oilchart', type: 'line' },
			              legend: { enabled: 1 },
			              loading: {hideDuration: 0},
			              title: {text: 'Oil Levels By Tank'},
			              plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
			              xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			              yAxis: {title: { text: 'Gallons' } },
			              series: [
						            {
						            	name: 'Tank 1',
						                data: [900, 800, 650, 600, 580, 540, 494, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 800]
						            }, {
							            name: 'Tank 2',
							            data: [400, 300, 280, {y: 250, marker: { symbol: 'url(images/stop.png)' } }, {
						                    dataLabels: {
						                        enabled: true,
						                        align: 'right',
						                        format: '{y} (700 delivered on 6/15)',
						                        style: {
						                            fontWeight: 'bold'
						                        },
						                        x: 3,
						                        verticalAlign: 'bottom',
						                        overflow: true,
						                        crop: false
						                    },  y: 950
						                }, 900, 850, 700, 620, 570, 500, 400]
							        }
						        ]
			          });
				</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Gas</h4>
			<div>
				<div id="gaschart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				<script>
				var chart = new Highcharts.Chart({
			        chart: { renderTo: 'gaschart', zoomType: 'xy', marginBottom: 80 },
			        title: { text: 'Gas Usage and Outside Air' },
			        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
			        yAxis: [{ // Primary yAxis
			            labels: {
			                formatter: function() {
			                    return this.value +'\xB0C';
			                },
			                style: {
			                    color: '#89A54E'
			                }
			            },
			            title: {
			                text: 'Temperature (\xB0C)',
			                style: {
			                    color: '#89A54E'
			                }
			            },
			        }, { // Secondary yAxis
			            gridLineWidth: 0,
			            title: {
			                text: 'Usage (cf)',
			                style: {
			                    color: '#4572A7'
			                }
			            },
			            labels: {
			                formatter: function() {
			                    return this.value +' cf';
			                },
			                style: {
			                    color: '#4572A7'
			                }
			            }
			        }],
			        tooltip: { shared: true },
			        legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
			        series: [{
			            name: 'Zone 1 Usage',
			            color: '#4572A7',
			            type: 'column',
			            yAxis: 1,
			            data: [6000, 5000, 4000, 3000, 2000, 1000, 2000, 3000, 5000, 6000, 6500, 7000],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Zone 2 Usage',
			            type: 'column',
			            color: '#AA4643',
			            yAxis: 1,
			            data: [5500, 4800, 3400, 2500, 1800, 800, 2200, 3200, 4500, 5000, 4000, 3500],
			            tooltip: {
			                valueSuffix: ' cf'
			            }
			        }, {
			            name: 'Temperature',
			            color: '#89A54E',
			            type: 'spline',
			            data: [5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5, -0.2, 0.8],
			            tooltip: {
			                valueSuffix: '\xB0C'
			            }
			        }]
			    });
			</script>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Water Usage</h4>
			<div>
				<div id="watertank" style="width: 250px; height: 250px; margin-left: auto; margin-right: auto"></div>
					<script>
						var chart = new Highcharts.Chart({
						    chart: {
							    renderTo: 'watertank',
						        type: 'gauge',
						        plotBackgroundColor: null,
						        plotBackgroundImage: null,
						        plotBorderWidth: 0,
						        plotShadow: false
						    },
						    title: { text: 'Make-Up Water Usage' },
						    pane: {
						        startAngle: -150,
						        endAngle: 150,
						        background: [{
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#FFF'],
						                    [1, '#333']
						                ]
						            },
						            borderWidth: 0,
						            outerRadius: '109%'
						        }, {
						            backgroundColor: {
						                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						                stops: [
						                    [0, '#333'],
						                    [1, '#FFF']
						                ]
						            },
						            borderWidth: 1,
						            outerRadius: '107%'
						        }, {
						            // default background
						        }, {
						            backgroundColor: '#DDD',
						            borderWidth: 0,
						            outerRadius: '105%',
						            innerRadius: '103%'
						        }]
						    },
						    // the value axis
						    yAxis: {
						        min: 0,
						        max: 80,

						        minorTickInterval: 'auto',
						        minorTickWidth: 1,
						        minorTickLength: 10,
						        minorTickPosition: 'inside',
						        minorTickColor: '#666',

						        tickPixelInterval: 30,
						        tickWidth: 2,
						        tickPosition: 'inside',
						        tickLength: 10,
						        tickColor: '#666',
						        labels: {
						            step: 2,
						            rotation: 'auto'
						        },
						        title: {
						            text: 'gal'
						        },
						        plotBands: [{
						            from: 0,
						            to: 40,
						            color: '#55BF3B' // green
						        }, {
						            from: 40,
						            to: 60,
						            color: '#DDDF0D' // yellow
						        }, {
						            from: 60,
						            to: 80,
						            color: '#DF5353' // red
						        }]
						    },
						    series: [{
						        name: 'Gallons',
						        data: [35],
						        tooltip: {
						            valueSuffix: ' gal'
						        }
						    }]
						});
					</script>
					<div id="tabs">
  						<ul>
    						<li><a href="#tabs-1" class="text-color">Water Usage</a></li>
   							<li><a href="#tabs-2" class="text-color">Make-up Water Usage</a></li>
  						</ul>
					  	<div id="tabs-1">
					    	<div id="waterchart" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
				    	<script>
					    	var chart = new Highcharts.Chart({
				        		chart: { renderTo: 'waterchart', type: 'column', margin: [ 50, 50, 100, 80] },
					            title: { text: 'Monthly Average Water Usage' },
					            xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
					            yAxis: { min: 0, title: { text: 'Usage (gal)' } },
					            legend: { layout: 'horizontal', align: 'center', x: 0, verticalAlign: 'bottom', y: 0, floating: true, backgroundColor: '#FFFFFF' },
					            tooltip: {
					                formatter: function() {
					                    return '<b>'+ this.series.name +'</b><br/>'+
					                        this.x +': '+ this.y +'gal';
					                }
					            },
					            series: [{
					                name: 'Zone 1',
					                data: [84.4, 121.8, 120.1, 220, 219.6, 219.5, 119.1, 118.4, 118,
					                    117.3, 116.8, 115],
					                dataLabels: {
					                    enabled: true,
					                    rotation: -90,
					    				x: 4,
					                    y: -20
					                }
					            }, {
					    			name: 'Zone 2',
					    			data: [89.4, 111.8, 110.1, 210, 214.6, 213.5, 100.1, 98.4, 115, 112.3, 126.8, 105],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					           	}, {
					    			name: 'Zone 3',
					    			data: [64.4, 101.8, 100.1, 180, 199.6, 179.5, 89.1, 98.4, 98, 107.3, 103.8, 95],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 4',
					    			data: [5.4, 5.8, 6.1, 6, 5.6, 5.5, 5.1, 5.4, 4, 4.3, 4.8, 5],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					    		}, {
					    			name: 'Zone 5',
					    			data: [82.4, 116.8, 160.1, 200, 204.6, 206.5, 109.1, 98.4, 88, 101.3, 105.8, 109],
					    			dataLabels: {
					    				enabled: true,
					    				rotation: -90,
					    				x: 4,
					                    y: -20
					    			}
					            }]
					        });
				    	</script>
				  	</div>
					<div id="tabs-2">
						<div id="waterchart2" style="width: 95%; margin-left: auto; margin-right: auto; float: left; padding: 1px"></div>
						<script>
							var chart = new Highcharts.Chart({
					            chart: { renderTo: 'waterchart2', zoomType: 'x', spacingRight: 20 },
					            tooltip: { shared: true },
					            legend: { enabled: false },
					            title: { text: 'Make-Up Water Usage' },
					            subtitle: {
					                text: document.ontouchstart === undefined ?
					                    'Click and drag in the plot area to zoom in' :
					                    'Pinch the chart to zoom in'
					            },
					            xAxis: {
					                type: 'datetime',
					                maxZoom: 4000 * 3600,
					                title: {
					                    text: null
					                }
					            },
					            yAxis: {
					                title: {
					                    text: 'Gallons'
					                },
					                plotLines : [{
										value : 60,
										color : 'red',
										dashStyle : 'shortdash',
										width : 2,
										label : {
											text : 'Limit'
										}
									}]
					            },
					            plotOptions: {
					                area: {
					                    fillColor: {
					                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
					                        stops: [
					                            [0, Highcharts.getOptions().colors[0]],
					                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
					                        ]
					                    },
					                    lineWidth: 1,
					                    marker: {
					                        radius: 2
					                    },
					                    shadow: false,
					                    states: {
					                        hover: {
					                            lineWidth: 1
					                        }
					                    },
					                    threshold: null
					                }
					            },
					            series: [{
					                type: 'area',
					                name: 'Tank 1',
					                pointInterval: 3600 * 4000,
					                pointStart: Date.UTC(2014, 0, 01),
					                data: [
											0, 4, 8, 16, 24, 40, 0, 8, 8, 20, 32, 24,
											0, 8, 12, 24, 24, 40, 0, 4, 8, 24, 32, 24,
											0, 8, 12, 20, 24, 20, 0, 8, 8, 20, 24, 28,
											0, 4, 16, 20, 28, 20, 0, 4, 16, 16, 24, 20,
											0, 8, 16, 20, 16, 24, 0, 4, 8, 16, 24, 20,
											0, 8, 12, 20, 32, 28, 0, 8, 12, 16, 28, 20,
											0, 8, 12, 24, 32, 32, 0, 4, 16, 24, 16, 28,
											0, 8, 16, 20, 24, 20, 0, 8, 16, 20, 32, 40,
											0, 4, 12, 24, 24, 32, 0, 8, 8, 20, 28, 24,
											0, 4, 16, 16, 16, 36, 0, 4, 12, 12, 32, 40,
											0, 8, 8, 16, 20, 28, 0, 8, 16, 24, 28, 24,
											0, 8, 12, 16, 28, 24, 0, 8, 16, 16, 28, 36,
											0, 8, 12, 24, 16, 40, 0, 4, 16, 12, 20, 40,
											0, 8, 8, 20, 16, 24, 0, 8, 16, 12, 24, 20,
											0, 4, 8, 12, 24, 20, 0, 4, 12, 20, 20, 36,
											0, 4, 16, 20, 32, 36, 0, 8, 8, 20, 28, 40,
											0, 4, 8, 20, 16, 24, 0, 8, 16, 24, 32, 36,
											0, 8, 12, 16, 20, 32, 0, 8, 8, 16, 28, 32,
											0, 8, 8, 24, 24, 20, 0, 8, 12, 16, 16, 24,
											0, 4, 12, 16, 24, 32, 0, 8, 8, 16, 28, 36,
											0, 8, 16, 12, 24, 24, 0, 4, 16, 12, 20, 28,
											0, 4, 16, 24, 32, 40, 0, 4, 16, 24, 28, 20,
											0, 8, 8, 16, 20, 28, 0, 8, 8, 12, 28, 36,
											0, 8, 8, 12, 16, 40, 0, 4, 16, 12, 28, 24,
											0, 8, 8, 16, 28, 40, 0, 4, 12, 24, 16, 20,
											0, 4, 16, 16, 28, 32, 0, 4, 8, 12, 24, 36,
											0, 8, 12, 12, 28, 32, 0, 4, 16, 12, 28, 24,
											0, 4, 12, 20, 32, 20, 0, 8, 8, 16, 28, 32,
											0, 4, 12, 20, 24, 20, 0, 8, 8, 12, 28, 36,
											0, 8, 12, 20, 24, 24, 0, 4, 12, 16, 28, 28,
											0, 8, 8, 20, 24, 28, 0, 8, 16, 24, 16, 28,
											0, 8, 16, 16, 32, 40, 0, 4, 8, 12, 16, 28,
											0, 8, 8, 12, 20, 24, 0, 4, 16, 20, 32, 20,
											0, 8, 8, 20, 24, 36, 0, 8, 8, 16, 20, 32,
											0, 8, 12, 12, 20, 20, 0, 8, 16, 12, 16, 40,
											0, 8, 8, 20, 16, 36, 0, 8, 12, 16, 32, 28,
											0, 4, 16, 12, 20, 35
					                ]
					            }]
						    });
						</script>
					</div>
				</div>
			</div>

			<h4 class="row-detail" style="width: 100%">{{HTML::image('images/greenbutton.png')}} Electric</h4>
			<div>
				<div id="meterbox" style="margin-left: auto; margin-right: auto">
					<table style="margin-left: auto; margin-right: auto">
						<thead>
							<th>Electricity Meter</th>
						</thead>
						<tbody>
						    <tr>
						        <td style="width: 825px; height: 150px">
						            {{HTML::image('images/elecmeters.png')}}
						        </td>
					        </tr>
						</tbody>
					</table>
				</div>
				<div id="elecchart" style="width: 95%; margin-left: auto; margin-right: auto; float:left; padding: 1px"><div>
				<script>
					var chart = new Highcharts.Chart({
        				chart: { renderTo: 'elecchart', type: 'line' },
				        title: { text: 'Monthly Average Electricity Usage' },
				        xAxis: { categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'] },
				        yAxis: { title: { text: 'Usage (kWh)' } },
				        plotOptions: { line: { dataLabels: { enabled: true }, enableMouseTracking: false } },
				        tooltip: {
				            enabled: false,
				            formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
				                    this.x +': '+ this.y +'kWh';
				            }
				        },
				        series: [{
				            name: 'Apt 5A',
				            data: [1000, 900, 1100, 1080, 800, 1200, 1140, 1060, 1000, 890, 800, 850]
				        }, {
							name: 'Apt 5B',
							data: [900, 1111, 1150, 1210, 1250, 1000, 900, 950, 980, 1000, 990, 970],
				       	}, {
							name: 'Apt 5C',
							data: [850, 870, 900, 950, 1000, 1100, 1050, 1020, 1030, 1100, 1060, 1040],
						}, {
							name: 'Apt 5D',
							data: [700, 700, 700, 700, 700, 800, 800, 800, 800, 700, 700, 700],
						}, {
							name: 'Apt 5E',
							data: [800, 880, 960, 1020, 1100, 1080, 1050, 980, 950, 920, 950, 960],
				        }]
				    });
				</script>
			</div>
		</div>
	</div>
@endif

@stop
