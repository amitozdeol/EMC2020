@extends('layouts.wrapper')

@section('content')

<div class="col-xs-12" style="background: url(images/home_page/blurred_blue_circular_scale.png); min-height: 1000px;">
    <div class="col-xs-12 col-md-10 col-md-offset-1">
        <h3>
            <a href="{{URL::to('home')}}" class="path-link">HOME&nbsp;></a>&nbsp;<b>SERVICES</b>
        </h3>
    </div>

    <div class="col-xs-12 shortest-height"></div>

    <div class="col-xs-12 col-md-10 col-md-offset-1 transparent_blue_white border_blue_white">
    	<p>SERVICES</p>
        <div class="col-xs-12 col-md-2">
            <img src="images/home_page/service-icons-security.png">
        </div>
        <div class="col-xs-12 col-md-8" style="padding: 30px 0px;">
            <p><small><small><small>
                Contact us today to find out more about the quality services we offer, to bring you the most from your building automation system.
            </small></small></small></p>
        </div>
        <div class="col-xs-12 col-md-2">
            <img src="images/home_page/service-icons-router.png">
        </div>
        <div class="col-xs-12 shortest-height"></div>
    </div><!--end transparent_blue_white-->

    <div class="col-xs-12 shortest-height"></div>
</div>


@stop
