
<?php $title="Update Password"; ?>
@extends('layouts.wrapper')

@section('content')
<style>
  .title{
    left: 0;
    width: 100%;
    max-height: 50px;
    background: white;
    margin-top: -5px;
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
  @media (max-width: 1080px) {
    .btn-sm{
      font-size: 12px;
    }
  }
</style>
<div class="title">
  <h2>Reset Your Password</h2>
</div>
  {!!Form::open(['route' => 'account.password.update'])!!}

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
      <div class="col-sm-6 col-sm-offset-3">

        {!!Form::label('password', 'New Password')!!}
        {!!Form::password('password', ['class'=>'form-control input-lg', 'placeholder'=>'Password', 'id'=>'password'])!!}

        <br>

        {!!Form::label('re-password', 'Retype Password')!!}
        {!!Form::password('re-password', ['class'=>'form-control input-lg', 'placeholder'=>'Retype Password', 'id'=>'re-password'])!!}

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
          {!!HTML::link (URL::route('account.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {!!Form::close()!!}

@stop
