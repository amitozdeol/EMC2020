<?php
  $admincss='admin';
  $title="Reset Password";
  
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
    <h2>Reset Password</h2>
  </div>
  <div class="row">
    <br>
    <br>
    <br>
    @if(Session::has('message'))
      <div class="alert {{ Session::get('alert-class') }}" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('message') }}
      </div>
    @endif
  </div>

  {{Form::open(['route' => ['admin.user.update_password', $user->id], 'class'=>'form-spaced well form-horizontal'])}}

    <div class="row">

      <div class="col-sm-8 col-sm-offset-2">
        {{Form::label('Email', 'Email')}}
        {{Form::text('email', $user->email, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'email', 'disabled'])}}
        <br>
        {{Form::label('password', 'New Password')}}
        {{Form::password('password', ['class'=>'form-control input-lg', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Password', 'id'=>'password'])}}
        <br>
        {{Form::label('re-password', 'Retype Password')}}
        {{Form::password('re-password', ['class'=>'form-control input-lg', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Retype Password', 'id'=>'re-password'])}}

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
          {{HTML::link (URL::route('admin.user.edit', $user->id), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])}}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {{Form::close()}}

@stop
