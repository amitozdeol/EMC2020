<?php
  $touchscreen='touchscreenJS';
?>

@extends('layouts.wrapper')

@section('content')
  @if(!count($systemsData))
    <div class="alert alert-warning alert-dismissible" role="alert">
      There are no systems set up for this building yet.
      @if(Auth::user()->role === 8)
        <a href="{!!URL::to('building', array($thisBldg->id, 'newsystem'))!!}" class="alert-link">Add one here</a>.
      @endif
    </div>
  @endif

  <div class="page-title" style="height: 95px; line-height: 55px"><h3>{!!$thisBldg->name!!} - System {!!$thisSystem->id!!} Overview</h3></div>

  @foreach ($sysParams as $param)
    <?php $group_name = strtolower($param->group_name); ?>
    <div class="row-detail">
      <a href="{!!URL::to( 'EMC', [$thisSystem->id, 'detail', $param->group_number] )!!}" title="{!! $param->group_name !!}">
        {!!HTML::image('images/greenbutton.png', '', ['draggable'=>'false'])!!}
      </a>
      {!! $param->group_name !!}
    </div>
  @endforeach


  <div class="row-detail">
    <a href="{!!URL::to('EMC', [$thisSystem->id, 'zonestatus'] )!!}" title="ZONE DASHBOARD --TEMP">
      {!!HTML::image('images/greenbutton.png', '', ['draggable'=>'false'])!!}
    </a>
    {!! "EMC ZONE STATUS" !!}
  </div>

  <div class="row-detail">
      <a href="{!!URL::to('EMC', [$thisSystem->id, 'devicestatus'] )!!}" title="EMC DEVICE STATUS">
        {!!HTML::image('images/yellowbutton.png')!!}
      </a>
      {!! "EMC DEVICE STATUS" !!}
    </div>

@stop
