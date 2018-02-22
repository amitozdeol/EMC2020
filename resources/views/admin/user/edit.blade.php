<?php
  $admincss='admin';
  $title="Edit User";
  
?>

@extends('layouts.wrapper')

@section('content')
  <?php
    $help_id='users';
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
    .btn-sm{
      font-size: 14px;
    }
    @media (max-width: 1080px) {
      .btn-sm{
        font-size: 12px;
      }
    }
  </style>
  <div class="title">
    <h2>Edit User</h2>
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
    {!!HTML::link(URL::route('admin.user.edit_password', $user->id), 'Reset Password', ['class'=>'btn btn-default btn-sm pull-left'])!!}
    {!!Form::open(['route'=>['admin.user.destroy', $user->id], 'method'=>'DELETE'])!!}
      {!!Form::submit('Delete User', ['class'=>'btn btn-danger btn-sm pull-right js-confirm', 'data-confirm'=>"Are you sure you want to delete this user?\n\nThis cannot be undone."])!!}
    {!!Form::close()!!}
  </div>
  <br>

  {!!Form::model($user, ['route' => ['admin.user.update', $user->id], 'method'=>'PUT', 'class'=>'form-spaced well form-horizontal'])!!}

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        {!!Form::label('email', 'Email')!!}
        {!!Form::email('email', NULL, ['class'=>'form-control input-lg', 'style'=> 'height:34px; font-size:14px;',  'placeholder'=>'Email', 'id'=>'email'])!!}
        <br>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2">
        {!!Form::label('prefix', 'Prefix')!!}
        {!!Form::select('prefix', array(' ','Mx.','Ms.','Mrs.','Mr.'), $user->prefix, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'prefix']) !!}
      </div>

      <div class="col-sm-2">
        {!!Form::label('first_name', 'First Name')!!}
        {!!Form::text('first_name', $user->first_name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'First Name', 'id'=>'first_name'])!!}
      </div>

      <div class="col-sm-2">
        {!!Form::label('middle_initial', 'Middle Initial')!!}
        {!!Form::text('middle_initial', $user->middle_initial, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Middle Initial', 'id'=>'middle_initial'])!!}
      </div>

      <div class="col-sm-4">
        {!!Form::label('last_name', 'Last Name')!!}
        {!!Form::text('last_name', $user->last_name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Last Name', 'id'=>'last_name'])!!}
      </div>

      <div class="col-sm-2">
        {!!Form::label('suffix', 'Suffix')!!}
        {!!Form::select('suffix', array(' ','Sr.','Jr.','III','M.D.','PhD','CPA'), $user->suffix, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'suffix']) !!}
      </div>

      <div class="col-sm-6">
        {!!Form::label('role', 'Authorization Level')!!}
        {!!Form::select('role', $roles, NULL, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'role'])!!}
      </div>

      <div class="col-sm-6">
        {!!Form::label('customer_id', 'Customer')!!}
        {!!Form::select('customer_id', $customers, NULL, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'customer_id'])!!}
      </div>

      <div class="col-sm-3">
        {!!Form::label('mobile_number', 'Mobile Number')!!}
        {!!Form::text('mobile_number', $user->mobile_number, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'XXXXXXXXXX', 'id'=>'mobile_number', 'type'=>"tel",'title'=>'10-digit US phone number', 'required'])!!}
      </div>

      <div class="col-sm-3">
        {!!Form::label('mobile_carrier', 'Mobile Carrier')!!}
        {!!Form::select('mobile_carrier', $mobile_carriers, $user->mobile_carrier, ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'mobile_carrier']) !!}
        <a href="http://freecarrierlookup.com/" target="_blank">Look it up</a>
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
          {!!HTML::link (URL::route('admin.user.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {!!Form::close()!!}

@stop
