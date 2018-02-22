@extends('layouts.wrapper')

@section('content')
<div class="col-xs-12" style="background-color: #419CB0; padding-bottom: 20px;">
  <div class="col-xs-12 shortest-height"></div>
  <div class="col-xs-12 col-md-10 col-md-offset-1" style="text-align: center; color: white; background-color: rgba(11, 11, 25, 0.54);">
    <h2 style="margin-top: 10px; letter-spacing: 0.05em;">LOGIN</h2>
  </div>
  {{HomeController::login_form()}}
</div>
@stop
