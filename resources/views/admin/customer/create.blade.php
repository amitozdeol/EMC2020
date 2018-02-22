<?php
  $admincss='admin';
  $title="Create Customer";  
?>

@extends('layouts.wrapper')

@section('content')
  <?php
    $help_id='customers';
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
    <h2>Create New Customer</h2>
  </div>
  <div class="row">
    <br><br><br>
  </div>
  {!!Form::open(['route'=>'admin.customer.store', 'class'=>'form-spaced well form-horizontal'])!!}

    <div class="row">
      <div class="col-xs-12">
        {!!Form::label('name', 'Customer Name*')!!}
        {!!Form::text('name', Input::old('name'), ['class'=>'form-control input-lg','style'=> 'height:34px; font-size:14px;',  'placeholder'=>'Customer Name', 'id'=>'name', 'required'])!!}
        <br>
      </div>


      <div class="col-sm-6">
        {!!Form::label('address1', 'Address*')!!}
        {!!Form::text('address1', Input::old('address1'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Address', 'id'=>'address1','required'])!!}

        {!!Form::label('address2', 'Address 2')!!}
        {!!Form::text('address2', Input::old('address2'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Address 2', 'id'=>'address2'])!!}

        {!!Form::label('city', 'City*')!!}
        {!!Form::text('city', Input::old('city'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'City', 'id'=>'city', 'required'])!!}

        {!!Form::label('state', 'State*')!!}
        {!!Form::select('state', $us_state_abbrevs_names, NULL, ['class' => 'form-control','style'=> 'height:34px; font-size:14px;', 'id'=>'state', 'required'])!!}

        {!!Form::label('zip', 'Zip')!!}
        {!!Form::text('zip', Input::old('zip'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Zip', 'id'=>'zip'])!!}
      </div>

      <div class="col-sm-6">
        {!!Form::label('email1', 'Primary Contact Email')!!}
        {!!Form::email('email1', Input::old('email1'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Primary Contact Email', 'id'=>'email1'])!!}

        {!!Form::label('email2', 'Secondary Contact Email')!!}
        {!!Form::email('email2', Input::old('email2'), ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Secondary Contact Email', 'id'=>'email2'])!!}
      </div>

    </div>

    <div class="row">
      <br>

      <div class="col-sm-10 col-sm-offset-1">

        <div class="col-xs-12 col-sm-4 col-md-3 pull-right">
          {!!Form::submit('Submit', ['class'=>'btn btn-primary btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 pull-left">
          {!!HTML::link (URL::route('admin.customer.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {!!Form::close()!!}

@stop
