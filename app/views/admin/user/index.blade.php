<?php
  $admincss='admin';
  $title="User";
  
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
    .list-group-item{
      min-height: 44px;
    }
    .btn-sm{
      font-size: 14px;
    }
    .nextline{
      display: none;
    }
    @media (max-width: 1080px) {
      .btn-sm{
        font-size: 12px;
      }
      .nextline{
        display: block;
      }
    }
  </style>
  <div class="title">
    <h2>Users</h2>
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
      {{HTML::link(URL::route('admin.user.create'), 'New User', ['class'=>'btn btn-primary btn-sm pull-right'])}}
  </div>
  <br>

  <div class="well" style="overflow: hidden; padding: 5px;">
    @foreach($customers as $customer)
      <div class="col-md-6">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>{{$customer->name}}</strong></h3>
          </div>
          <div class="panel-body">
            @foreach($users as $user)
              @if($customer->id == $user->customer_id)
                <p id="{{$user->id}}">
                  <strong>{{$user->first_name}}&nbsp;{{$user->last_name}}</strong><br class="nextline">
                  {{HTML::link(URL::route('admin.user.edit', $user->id), $user->email, array('style' => 'word-wrap: break-word;'))}}
                </p>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="col-sm-8 col-sm-offset-2">
    <br>
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">EAW Staff</h3>
      </div>
      <div class="panel-body">
        @foreach($users as $user)
          @if($user->customer_id == 0)
            <p id="{{$user->id}}">
              <strong>{{$user->first_name}} {{$user->last_name}}</strong>
              {{HTML::link(URL::route('admin.user.edit', $user->id), $user->email)}}
            </p>
          @endif
        @endforeach
      </div>
    </div>
  </div>

@stop
