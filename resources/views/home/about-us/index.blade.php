@extends('layouts.wrapper')

@section('content')

	<div class="col-xs-12" style="background: url(images/home_page/blurred_blue_circular_scale.png); min-height: 2000px;">
		<div class="col-xs-12 col-md-10 col-md-offset-1">
            <h3>
                <a href="{!!URL::to('home')!!}" class="path-link">HOME&nbsp;></a>&nbsp;<b>ABOUT&nbsp;US</b>
            </h3>
        </div>
	    <!--about us-->
	    <div class="col-xs-12 shorter-height" style="background-color: none;"></div>

	    <div class="col-xs-12 col-md-10 col-md-offset-1">
	    	<div class="col-xs-12 col-md-6 tallest-height transparent_blue_white border_blue_white vert-center-child">
	    		<img src="images/home_page/electronics-design.png">

	    	</div>
	    	<div class="col-xs-12 col-md-6 tallest-height transparent_blue_white border_blue_white">
	    		<p style="padding: 20px;"><small><small>
	    			At EAW, we're dedicated to providing you with the highest quality building automation devices for remote central heating control.
	    		</small></small></p>
	    		<p style="padding: 20px;"><small><small>
	    			Our New York manufacturing facility allows us to ensure our customers receive only the highest quality devices, while minimizing maintenance down-times.
	    		</small></small></p>
	    	</div>

			<div class="col-xs-12 shorter-height" style="background-color: none;"></div>

			<div class="col-xs-12 tallest-height transparent_blue_white border_blue_white">
				<p style="padding: 40px;"><small><small>
	    			Thanks to our many years of experience in electronics design, manufacturing and testing, we are able to offer robust products which have been designed with maximal cost-savings in mind, while providing the kind of service and quality you expect.
	    		</small></small></p>
	    		<img style="max-height: 220px;" src="images/home_page/manufacturing.png">
	    		<p style="padding: 40px;"><small><small>
	    			Manufacturing our own products means we can more quickly adapt to the ever changing building automation systems market.
	    		</small></small></p>
	    		<p style="padding: -40px 40px 40px 40px;"><small><small>
	    			As ongoing innovations continue to spark new applications, we are positioned to rapidly integrate these cuttting edge improvements for the benefit of our system's building owners, managers and occupants.
	    		</small></small></p>
			</div>

			<div class="col-xs-12 shortest-height" style="background-color: none;"></div>
		</div>
	</div>

@stop
