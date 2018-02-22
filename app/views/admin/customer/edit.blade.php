<?php
  $admincss='admin';
  $title="Edit Customer";  
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
    <h2>Edit Customer</h2>
  </div>
  <div class="row">
    <br>
    <br>
    <br>
    {{Form::open(['route'=>['admin.customer.destroy', $customer->id], 'method'=>'DELETE'])}}
      {{Form::submit('Delete Customer', ['class'=>'btn btn-danger btn-sm pull-right js-confirm', 'data-confirm'=>"This will remove all users and buildings associated with the account and CANNOT be undone.\n\nAre you sure you want to continue?"])}}
    {{Form::close()}}
  </div>
  <br>

  {{Form::open(['route'=>['admin.customer.update', $customer->id], 'method'=>'PUT', 'class'=>'form-spaced well form-horizontal'])}}

    <div class="row">
      <div class="col-xs-12">
        {{Form::label('name', 'Customer Name')}}
        {{Form::text('name', $customer->name, ['class'=>'form-control input-lg', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Customer Name', 'id'=>'name'])}}
        <br>
      </div>


      <div class="col-sm-6">
        {{Form::label('address1', 'Address')}}
        {{Form::text('address1', $customer->address1, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Address', 'id'=>'address1'])}}

        {{Form::label('address2', 'Address 2')}}
        {{Form::text('address2', $customer->address2, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Address 2', 'id'=>'address2'])}}

        {{Form::label('city', 'City')}}
        {{Form::text('city', $customer->city, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'City', 'id'=>'city'])}}

        {{Form::label('state', 'State')}}
        {{Form::select('state', $us_state_abbrevs_names, $customer->state, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'state'])}}

        {{Form::label('zip', 'Zip')}}
        {{Form::text('zip', $customer->zip, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Zip', 'id'=>'zip'])}}
      </div>

      <div class="col-sm-6">
        {{Form::label('email1', 'Primary Contact Email')}}
        {{Form::email('email1', $customer->email1, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Primary Contact Email', 'id'=>'email1'])}}

        {{Form::label('email2', 'Secondary Contact Email')}}
        {{Form::email('email2', $customer->email2, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Secondary Contact Email', 'id'=>'email2'])}}
      </div>

    </div>

    <div class="row">
      <br>

      <div class="col-sm-10 col-sm-offset-1">

        <div class="col-xs-12 col-sm-4 col-md-3 pull-right">
          {{Form::submit('Submit', ['class'=>'btn btn-primary btn-sm col-xs-12'])}}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 pull-left">
          {{HTML::link (URL::route('admin.customer.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])}}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {{Form::close()}}

@stop
