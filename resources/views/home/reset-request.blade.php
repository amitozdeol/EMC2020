@extends('layouts.wrapper')
@section('content')
  <div class="row">
    <h1 class="col-sm-10">Reset Your Password</h1>
  </div>
  {{Form::open()}}
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        {{Form::label('password', 'New Password')}}
        {{Form::password('password', ['class'=>'form-control input-lg', 'placeholder'=>'Password', 'id'=>'password'])}}
        <br>
        {{Form::label('re-password', 'Retype Password')}}
        {{Form::password('re-password', ['class'=>'form-control input-lg', 'placeholder'=>'Retype Password', 'id'=>'re-password'])}}
      </div>
    </div>
    @include('layouts.submit-bar')
  {{Form::close()}}
@stop