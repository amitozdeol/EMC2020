<!--emc2020-->
@extends('layouts.wrapper')

@section('content')

<style type="text/css">
	.sys-details-1{
		padding-left: 2em;
	}
	.sys-details-2{
		padding-left: 4em;
	}
</style>

<div class="col-xs-12" style="background: url(images/home_page/blurred_blue_circular_scale.png); min-height: 6000px;">
   <div class="col-sm-12 col-md-10 col-md-offset-1">
        <h3>
            <a href="{{URL::to('home')}}" class="path-link">HOME&nbsp;></a>&nbsp;<a href="{{URL::to('products')}}" class="path-link">PRODUCTS&nbsp;></a>&nbsp;<b>EMC&nbsp;20/20</b>
        </h3>
	</div>

    <div class="col-xs-12 shortest-height"></div>

    <div class="col-xs-12 col-md-10 col-md-offset-1 transparent_blue_white border_blue_white">
    	<div class="col-xs-12" style="padding: 40px 25px;">
    		<p><big>BENEFITS</big></p>
    	</div>

    	<div class="col-xs-12 shortest-height"></div>

    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">INTEROPERABLE</p>
    		<p style="padding: 20px;"><small>The EMC&nbsp;20/20 has been design to integrate into your building's existing control and sensing networks, saving you time and money.</small></p>
    	</div>
    	<div class="col-xs-12 col-md-5">
            <img src="images/home_page/service-icons-screen-network.png">

    	</div>

    	<div class="col-xs-12 shorter-height"></div>

    	<div class="col-xs-12 col-md-5 vert-center-child" >
            <img src="images/home_page/expandable.png">

    	</div>
    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">EXPANDABLE</p>
    		<p style="padding: 20px;"><small>By maintaining a flexible development model, the EMC&nbsp;20/20 is able to accomodate your building's needs; the EMC&nbsp;20/20 grows with you, as technology advances.</small></p>
    	</div>

    	<div class="col-xs-12 shorter-height"></div>

    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">ADAPTABLE</p>
    		<p style="padding: 20px;"><small>Thanks to the EMC&nbsp;20/20's open software architecture, systems seemlessly receive the latest updates and improvements, keeping you at the leading edge of building automation systems technology.</small></p>
    	</div>
    	<div class="col-xs-12 col-md-5" >
            <img src="images/home_page/service-icons-server.png">

    	</div>

    	<div class="col-xs-12 shorter-height"></div>

    	<div class="col-xs-12 col-md-5 vert-center-child">
            <img src="images/home_page/touch-screen.jpg">

    	</div>
    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">ACCESSIBLE</p>
    		<p style="padding: 20px;"><small>The EMC&nbsp;20/20's large, built-in touch screen allows you to monitor your system on site, while maintaining consistency with the mobile interface you use every day.</small></p>
    	</div>
    	<div class="col-xs-12 shortest-height"></div>
    </div>

    <div class="col-xs-12 typical-height"></div>

    <div class="col-xs-12 col-md-10 col-md-offset-1 transparent_blue_white border_blue_white">
    	<div class="col-xs-12" style="padding: 40px 25px;">
    		<p><big>CAPABILITIES</big></p>
    	</div>

    	<div class="col-xs-12 shortest-height"></div>

    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">SOFTWARE</p>
    		<p style="padding: 20px;"><small>We provide an intuitive, mobile-friendly user interface, with access to your building’s most relevant metrics, all from wherever you are.</small></p>
    	</div>
    	<div class="col-xs-12 col-md-5">
    		<img src="images/home_page/zone-control-heat-fan-1.png">
    	</div>

    	<div class="col-xs-12 typical-height"></div>

    	<div class="col-xs-12 col-md-5" style="background: url(images/home_page/emc-expansion-board_blurred.jpg); background-position: center; background-repeat: no-repeat; min-height: 500px;">

    	</div>
    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">EXPANSION&nbsp;UNITS</p>
    		<p style="padding: 20px;"><small>We offer a competitively designed alternative sensing and control device, built into your EMC&nbsp;20/20 building automation system.</small></p>
    	</div>

    	<div class="col-xs-12 typical-height"></div>

    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">BACnet&nbsp;MSTP</p>
    		<p style="padding: 20px;"><small>Using BACnet compatible devices, you control valves, switches, pumps, fans, burners, and much more, all from a single, easy to use interface.</small></p>
    	</div>
    	<div class="col-xs-12 col-md-5" style="background: url(images/home_page/bac-net-logo.png); background-position: center; background-repeat: no-repeat; min-height: 500px;">

    	</div>

    	<div class="col-xs-12 typical-height"></div>

    	<div class="col-xs-12 col-md-5" style="background: url(images/home_page/Inovonics-Icon.jpg); background-position: center; background-repeat: no-repeat; min-height: 500px;">

    	</div>
    	<div class="col-xs-12 col-md-7">
    		<p style="padding: 20px;">INOVONICS</p>
    		<p style="padding: 20px;"><small>The EMC&nbsp;20/20’s extensible platform allows us to bring you some of the best in environmental sensing</small></p>
    	</div>
    	<div class="col-xs-12 shortest-height"></div>
    </div>

    <div class="col-xs-12 typical-height"></div>

	<div class="col-xs-12 col-md-10 col-md-offset-1 transparent_blue_white border_blue_white" itemscope itemtype="http://schema.org/Product">
    	<div class="col-xs-12" style="padding: 40px 25px;">
    		<p><big>DETAILS</big></p>
    	</div>
    	<div class="col-xs-12" style="text-align: left; padding: 10px 0px 0px 20px;">
    		<small><small>
    		<p>Name:</p>
            <div class="border_blue_white"><small>
                <p>
                    &nbsp;<span itemprop="name">EMC 20/20</span>
                </p>
            </small></div>
            <p>Device&nbsp;Type:</p>
    		<div class="border_blue_white"><small>
	    		<p>
	    			&nbsp;Building Automation System (BAS)
	    			<br>
	    			&nbsp;Energy Management System (EMS)
				</p>
			</small></div>

			<br>
    		<p>Communication Protocols:</p>
    		<div class="border_blue_white"><small>
	    		<p>&nbsp;BACnet MSTP</p>
	    		<small>
	    		<p class="sys-details-1">Interface</p>
	    		<p class="sys-details-2">RS-485 Network Bus</p>
	    		<p class="sys-details-1">Control</p>
	    		<p class="sys-details-2">Lighting</p>
	    		<p class="sys-details-2">Boilers</p>
	    		<p class="sys-details-2">Valves</p>
	    		<p class="sys-details-2">Pumps</p>
	    		<p class="sys-details-2">Fans</p>
	    		<p class="sys-details-1">Sensing</p>
	    		<p class="sys-details-2">Water Usage</p>
	    		<p class="sys-details-2">Electricty Usage</p>
	    		<p class="sys-details-2">Environmental Variables</p>
	    		</small>
    		</small></div>
    		<div class="border_blue_white"><small>
	    		<p>&nbsp;Inovonics EchoStream Wireless</p>
	    		<small>
	    		<p class="sys-details-1">Interface</p>
	    		<p class="sys-details-2">RS-232 from Wireless Network Receiver</p>
	    		<p class="sys-details-1">Temperature Sensing</p>
	    		</small>
    		</small></div>
    		<div class="border_blue_white"><small>
	    		<p>&nbsp;EMC ZigBee Wireless</p>
	    		<small>
	    		<p class="sys-details-1">Interface</p>
	    		<p class="sys-details-2">RS-485 from Wireless Network Receiver</p>
	    		<p class="sys-details-1">Temperature Sensing</p>
	    		<p class="sys-details-1">Humidity Sensing</p>
	    		<p class="sys-details-1">Light Sensing</p>
	    		<p class="sys-details-1">Occupancy Sensing</p>
	    		<p class="sys-details-1">Air Pressure Sensing</p>
	    		</small>
    		</small></div>
    		<div class="border_blue_white"><small>
	    		<p>&nbsp;EMC Expansion Units</p>
	    		<small><p class="sys-details-1">Interface</p>
	    		<p class="sys-details-2">Internal</p>
	    		<p class="sys-details-1">Up to Eight Control Relays</p>
	    		<p class="sys-details-1">Multiple Wired Temperature Probe Inputs</p>
	    		<p class="sys-details-1">Multiple Binary Signal Inputs</p>
	    		</small>
    		</small></div>

    		<br>
    		<p>Control Algorithms:</p>
    		<div class="border_blue_white"><small>
    			<p>&nbsp;Detailed User Email Notifications</p>
    			<p>&nbsp;Flexible Configurations</p>
    			<p>&nbsp;Ability to Commission Reserve Sensors</p>
    			<p>&nbsp;Zone-Independent / Sensor-Specific Set-Points</p>
    			<p>&nbsp;Zone-Independent / Sensor-Specific Alarm Levels</p>
    			<p>&nbsp;Multiple Daily Zone-Independent / Sensor-Specific Setbacks</p>
    		</small></div>

    		<br>
    		<p>User Interface:</p>
    		<div class="border_blue_white"><small>
    			<p>&nbsp;On the Web:</p>
    			<p class="sys-details-1">Accessible from All Major Web Browsers (Firefox, Chrome, Safari, Edge)</p>
    			<p class="sys-details-1">At-a-Glance System Status Dashboards</p>
    			<p class="sys-details-1">Graphical Building Layout Integration</p>
    			<p class="sys-details-1">Long Term Graphical Visualizations</p>
    			<p class="sys-details-1">Device Reports Exports to CSV</p>
    			<p class="sys-details-1">Remote Controls Overrides</p>
    			<p>&nbsp;On the Unit:</p>
    			<p class="sys-details-1">10" HD Capacitive Touch Screen</p>
    			<p class="sys-details-1">At-a-Glance System Status Dashboards</p>
    			<p class="sys-details-1">Graphical Building Layout Integration</p>
    			<p class="sys-details-1">Short Term Graphical Visualizations</p>
    		</small></div>
    		</small></small>
    		<br>
    	</div>

    </div>

    <div class="col-xs-12 shortest-height"></div>
</div>
@stop
