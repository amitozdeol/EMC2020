<?php
  $admincss='admin';
  $title="Building";  
?>
<?php
  $help_id='building';
?>
@extends('layouts.wrapper')

@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/grid.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }

  }
?>
<style>
  .customer{
    margin: 5px;
    overflow: hidden;
  }
  .title{
    left: 0;
    width: 100%;
    max-height: 50px;
    text-align: center;
    position: absolute;
    box-shadow: black 0px 0.1em 0.15em;
  }
  h2{
    margin-top: 10px;
  }
  .editbtn{
    top: 0;
    right: 0;
    z-index: 2;
    width: 30px;
    color: #24649b;
    position: absolute;
    text-shadow: 1px 1px 1px #000;
  }
  .editbtn:hover{
    color: #091016;
  }
</style>
<div class="title"><h2>Buildings</h2></div>
<div class="row">
  <br>
  <br>
  <br>
  @if(Session::has('message'))
    <div class="alert {!! Session::get('alert-class') !!}" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {!! Session::get('message') !!}
    </div>
  @endif
  <a href="{!!URL::route('admin.building.create')!!}" class="btn btn-primary btn-sm pull-right">
    <span class="visible-xs glyphicon glyphicon-plus">
    </span>
    <span class="hidden-xs">New Building</span>
  </a>
</div>
<hr>
<div class="gridlayout" data-columns>
  @foreach($customers as $customer)
    <div class="customer" style="position: relative;">
      <a href="{!!URL::route('admin.customer.edit', $customer->id)!!}" class="editbtn">
        <i class="fa fa-pencil-square fa-lg" aria-hidden="true"></i>
      </a>
      <div class="col-xs-12" style="padding-left: 0px; padding-right: 30px;">
        <h2 style="display:inline">
          {!!$customer->name!!}
        </h2>
      </div>

      <div class="col-xs-12 building">
        @foreach($buildings as $building)
          @if($customer->id === $building->customer_id)
              <a href="{!!URL::route('admin.building.edit', $building->id)!!}">
                <h4 class="system">
                  {!!$building->name!!}<br>
                  <small style="color: white;text-shadow: 0px 0px 10px #000;">Building #{!!$building->id!!}</small>
                </h4>
              </a>
          @endif
        @endforeach
      </div>
    </div>
  @endforeach
</div>
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_scripts = ['/js/salvattore.js'];
  foreach ($import_scripts as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::script($appendDate);
    }

  }
?>

@stop
