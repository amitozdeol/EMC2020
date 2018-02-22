<?php $title="Unsubscribe"; ?>

@extends('layouts.wrapper')

@section('content')

  <div class="row col-sm-10 col-sm-offset-1">
    <h1 align="center">Unsubscribe Request</h1>
  </div>
  <div class="col-sm-10 col-sm-offset-1" >
    <p style="font-size:16px">You are requesting to unsubscribe from recieving future email messages regarding
      <u>{!!$alarm_code['description']!!}</u> alerts
      @if(isset($alert['function']))
       for {!!'<u>' .$alert['function'].'</u>'!!}
      @endif</u>.
      <br>
      In order to stop receiving certain alarms like this, you may need to adjust the alarm levels for a single/particular device. <br>
    </p>
    <br>

    <div class="row ">
      <p class="col-md-4 offset-md-4" style="float:left; font-size:16px">Click on the Setpoint button to go to your <b>setpoint</b> page.</p>
      <div class="col-xs-12 col-sm-4 col-md-3 " style="float:right;">
        <a href = "{!!URL::route('setpointmapping.index',[$alert['building_id'],$alert['system_id']])!!}" class="btn btn-primary btn-lg col-xs-12">Setpoint</a>
        <div class="col-xs-12"><br><!-- Spacing for mobile --></div>
      </div>
    </div>

    <div class="row">
      <p class="col-md-4 offset-md-4" style="float:left; font-size:16px">No thanks, unsubscribe me from these emails.</p>
      <div class="col-xs-12 col-sm-4 col-md-3" style="float:right;">
        <a href = "{!!URL::route('unsubscribe', $alert['unsubscribe_key'])!!}" style = "box-shadow: black 0.1em 0.1em 0.2em;"class="btn btn-default active btn-lg col-xs-12">Unsubscribe</a>
        <div class="col-xs-12"><br><!-- Spacing for mobile --></div>
      </div>
    </div>
  </div>


@stop
