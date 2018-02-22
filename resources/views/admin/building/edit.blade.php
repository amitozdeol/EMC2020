<?php
  $admincss='admin';
  $title="Edit Building";  
?>

@extends('layouts.wrapper')

@section('content')
  <?php
    $help_id='building';
  ?>
  <style>
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
    @media (max-width: 1080px) {
      .btn-sm{
        font-size: 12px;
      }
    }
  </style>
  <div class="title">
    <h2>Edit Building</h2>
  </div>
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
    @if(Route::currentRouteName() === 'admin.building.edit')
    <div class="pull-right">
      <br>
      {!!Form::open(['route'=>['admin.building.destroy', $thisBldg->id], 'method'=>'DELETE'])!!}
        {!!Form::submit('Delete Building', ['class'=>'btn btn-danger btn-sm pull-right js-confirm', 'data-confirm'=>"This will remove all systems and devices associated with the bulding and CANNOT be undone.\n\nAre you sure you want to continue?"])!!}
      {!!Form::close()!!}
    </div>
    @endif
  </div>


  <br>

  {!!Form::open(['route'=>['admin.building.update', $thisBldg->id], 'method'=>'PUT', 'class'=>'form-spaced well form-horizontal'])!!}

  <div class="row">

    <div class="col-sm-6">
      {!!Form::label('name', 'Building Name')!!}
      {!!Form::text('name', $thisBldg->name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'name'])!!}
    </div>
    <div class="col-sm-6">
      {!!Form::label('name', 'Customer')!!}
      {!!Form::select('customer_id', $customer_list, $thisBldg->customer_id, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}
    </div>

    <div class="col-xs-12"><br><br>{{-- Spacing --}}</div>

    <div class="col-sm-6">
      {!!Form::label('address1', 'Address')!!}
      {!!Form::text('address1', $thisBldg->address1, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}

      {!!Form::label('address2', 'Address 2')!!}
      {!!Form::text('address2', $thisBldg->address2, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}
    </div>

    <div class="col-sm-6">
      {!!Form::label('city', 'City/Town')!!}
      {!!Form::text('city', $thisBldg->city, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}

      {!!Form::label('state', 'State')!!}
      {!!Form::select('state', $us_state_abbrevs_names, $thisBldg->state, ['class' => 'form-control','style'=> 'height:34px; font-size:14px;', 'id'=>'state'])!!}

      {!!Form::label('zip', 'Zip Code')!!}
      {!!Form::text('zip', $thisBldg->zip, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}

      @if(Request::is('building/create') && isset($customer_list))
        {!!Form::label('customer_id', 'Customer')!!}
        {!!Form::select('customer_id', $customer_list, NULL, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;'])!!}
      @endif
    </div>

  </div>

  <br><br>

  <div class="row">
    <br>

    <div class="col-sm-10 col-sm-offset-1">

      <div class="col-xs-12 col-sm-4 col-md-3 pull-right">
        {!!Form::submit('Submit', ['class'=>'btn btn-primary btn-sm col-xs-12'])!!}
        <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-3 pull-left">
        {!!HTML::link (URL::route('admin.building.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
        <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
      </div>

    </div>

  </div>

  {!!Form::close()!!}


{!!Form::close()!!}



@stop
