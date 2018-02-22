<?php $title="Edit User"; ?>

@extends('layouts.wrapper')

@section('content')
<style>
  .customer{
    margin: 5px;
    overflow: hidden;
  }
  .title{
    left: 0;
    width: 100%;
    max-height: 50px;
    margin-top: -10px;
    text-align: center;
    position: absolute;
    box-shadow: black 0px 0.1em 0.15em;
  }
  h2{
    margin-top: 10px;
  }
  .well{
    background-color: #ffffff;
    box-shadow: black 0.1em 0.1em 0.15em;
  }
  .btn-sm{
    font-size: 14px;
  }
  .Password{
    width: 90%;
  }
  .show{
    padding: 6px 0px;
  }

  @media (max-width: 1080px) {
    .btn-sm{
      font-size: 12px;
    }
    .Password{
      width: 80%;
    }
    .show{
      padding: 6px 20px;
    }
  }
</style>

<div class="title" style="background-color: white;"><h2>
  @if(Request::is('user/create'))
    Add
  @else
    Edit
  @endif
    User</h2>
</div>
<div class="row">
  <br>
  <br>
  <br>
  @if(! Request::is('user/create'))
      <a href="#" class="update_users_tour btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#passwordReset">
        Update Password
      </a>
  @endif
</div>
<br>

@if(Request::is('user/create'))
  {{Form::open(['url'=>URL::route('user.store'), 'class'=>'form-spaced well form-horizontal'])}}
@else
  {{Form::open(['url'=>'user/'.$user->id, 'method'=>'PUT', 'class'=>'form-spaced well form-horizontal'])}}
@endif

{{Form::open(['url'=>(Request::is('user/create'))?'user/store':'user/update/'.$user->id, 'class'=>'form-spaced'])}}

<div class="row">
  @if(Request::is('user/create'))
  <div class="update_users_tour col-sm-6">
  @else
  <div class="update_users_tour col-sm-6 col-sm-offset-3">
  @endif
    {{Form::label('customer_id', 'Customer Account')}}
    {{Form::text('customer_name', $customer->name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'style'=> 'height:34px; font-size:14px;', 'disabled'])}}
  </div>
  @if(Request::is('user/create'))
    <div class="col-sm-6">
      {{Form::label('role', 'Authorization Level')}}
      {{Form::select('role', $roles, ' ', ['class' => 'form-control', 'style'=> 'height:34px; font-size:14px;', 'id'=>'role'])}}
    </div>
  @endif
</div>

<div class="row">
  <div class="update_users_tour col-sm-6">
    {{Form::label('email', 'Email Address')}}
    {{Form::email('email', $user->email, ['class'=>'form-control input-lg', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Email Address', 'id'=>'email'])}}
    <br>
  </div>

  <div class="update_users_tour col-sm-3">
    {{Form::label('mobile_number', 'Mobile Number')}}
    {{Form::text('mobile_number', $user->mobile_number, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'XXXXXXXXXX', 'id'=>'mobile_number', 'type'=>"tel",'title'=>'10-digit US phone number'])}}
  </div>

  <div class="update_users_tour col-sm-3">
    {{Form::label('mobile_carrier', 'Mobile Carrier')}}
    {{Form::select('mobile_carrier', $mobile_carriers, $user->mobile_carrier,['class' => 'form-control', 'id'=>'mobile_carrier']) }}
    <a href="http://freecarrierlookup.com/" target="_blank">Not sure? Look it up</a>
  </div>
</div>
<div class="row">
  <div class="update_users_tour col-sm-1">
    {{Form::label('prefix', 'Prefix')}}
    {{Form::select('prefix', array(' ','Mx.','Ms.','Mrs.','Mr.'), $user->prefix, ['class' => 'form-control', 'id'=>'prefix']) }}
  </div>

  <div class="update_users_tour col-sm-3">
    {{Form::label('first_name', 'First Name')}}
    {{Form::text('first_name', $user->first_name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'First Name', 'required'])}}
  </div>

  <div class="update_users_tour col-sm-2">
    {{Form::label('middle_initial', 'Middle Initial')}}
    {{Form::text('middle_initial', $user->middle_initial, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Middle Initial', 'id'=>'middle_initial'])}}
  </div>

  <div class="update_users_tour col-sm-5">
    {{Form::label('last_name', 'Last Name')}}
    {{Form::text('last_name', $user->last_name, ['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Last Name', 'required'])}}
  </div>

  <div class="update_users_tour col-sm-1">
    {{Form::label('suffix', 'Suffix')}}
    {{Form::select('suffix', array(' ','Sr.','Jr.','III','M.D.','PhD','CPA'), $user->suffix, ['class' => 'form-control', 'id'=>'suffix']) }}
  </div>

  @if(Request::is('user/create'))
    <br>
    <div class="col-sm-6">
        {{Form::label('password', 'Password')}}
        <div style="display: flex;">
          {{Form::password('password', ['class'=>'form-control pull-left Password', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Password', 'required'])}}
          <i class="fa fa-eye show pull-right" title="show the password entered"></i>
        </div>
    </div>
    <div class="col-sm-6">
        {{Form::label('re-password', 'Retype Password')}}
        <div style="display: flex;">
          {{Form::password('re-password', ['class'=>'form-control pull-left Password', 'style'=> 'height:34px; font-size:14px;', 'placeholder'=>'Retype Password', 'required'])}}
          <i class="fa fa-eye show pull-right" title="show the password entered"></i>
        </div>
    </div>
  @endif

</div>
<div class="row">

  <div class="update_users_tour col-xs-12 col-xs-offset-0 col-sm-4 {{(! Request::is('user/create'))?'col-sm-offset-2':'col-sm-offset-4'}}" style="padding: 15px">

    {{Form::submit('Save', ['class'=>'btn btn-primary btn-sm col-xs-12 pull-right', 'id'=>'submitform'])}}
    {{Form::close()}}
  </div>

  @if((!Request::is('user/create'))&&(Auth::user()->id != $user->id))
    <div class="delete_users_tour col-xs-12 col-sm-4" style="padding: 15px">
      {{Form::open(['url'=>'user/'.$user->id, 'method'=>'DELETE'])}}
      {{Form::submit('Delete User', ['class'=>'btn btn-danger btn-sm js-confirm pull-right col-xs-12', 'data-confirm'=>'Are you sure you want to delete this user account?'])}}
      {{Form::close()}}
    </div>
  @endif
</div>

@if(! Request::is('user/create'))
  <!-- Password Reset Modal -->
  {{Form::open(['url'=>'user/password/'.$user->id, 'class'=>'form-spaced'])}}
  <div class="modal fade" id="passwordReset" tabindex="-1" role="dialog" aria-labelledby="passwordResetLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">
              &times;
            </span>
            <span class="sr-only">
              Close
            </span>
          </button>
          <h4 class="modal-title" id="passwordResetLabel">
            Reset Password
          </h4>
        </div>
        <div class="modal-body">
          {{Form::label('email', 'Email')}}
          {{Form::text('email', $user->email,['class'=>'form-control', 'style'=> 'height:34px; font-size:14px;', "disabled"])}}
          <!-- new password -->
          {{Form::label('password', 'New password')}}
          <div style="display: flex;">
            {{Form::password('password', ['class'=>'form-control pull-left Password passwordform', 'style'=> 'height:34px; font-size:14px;', 'required', 'name'=> 'passwordmodal'])}}
            <i class="fa fa-eye show pull-right" title="show the password entered"></i>
          </div>
          <br>
          <!-- verify password -->
          {{Form::label('re-password', 'Password Verification')}}
          <div style="display: flex;">
            {{Form::password('re-password', ['class'=>'form-control pull-left Password re_passwordform', 'style'=> 'height:34px; font-size:14px;', 'required', 'name'=> 're-passwordmodal'])}}
            <i class="fa fa-eye show pull-right" title="show the password entered"></i>
          </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
            Cancel
          </button>
          {{Form::submit('Save', ['class'=>'btn btn-primary btn-sm', 'id'=> 'submitform_modal'])}}
        </div>
      </div>
    </div>
  </div>

  {{Form::close()}}
@endif
<script>
  //password validation
  var password = document.getElementById("password");
  var confirm_password = document.getElementById("re-password");
  var passwordmodal = document.getElementsByName("passwordmodal")[0];
  var confirm_passwordmodal = document.getElementsByName("re-passwordmodal")[0];

  $('.show').mousedown( function() {
    $(this).prev().attr('type','text');
  });
  $('.show').mouseup( function() {
    $(this).prev().attr('type','password');
  });

  //for touch devices
  $(".show").bind('touchstart', function(){
      $(this).prev().attr('type','text');
  }).bind('touchend', function(){
        $(this).prev().attr('type','password');
  });

  document.getElementById("submitform").addEventListener("click", function validatePassword(){
    password.value.length<7 ? password.setCustomValidity("Your password must be 7 or more character long.") : password.setCustomValidity('');
    password.value != confirm_password.value ? confirm_password.setCustomValidity("Passwords Don't Match") : confirm_password.setCustomValidity('');
  });
  document.getElementById("submitform_modal").addEventListener("click", function validatePasswordModal(){
    passwordmodal.value.length<7 ? passwordmodal.setCustomValidity("Your password must be 7 or more character long.") : passwordmodal.setCustomValidity('');
    passwordmodal.value != confirm_passwordmodal.value ? confirm_passwordmodal.setCustomValidity("Passwords Don't Match") : confirm_passwordmodal.setCustomValidity('');
  });
  $(document).ready(function(){
    $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="update_users_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Need Help?\
        </a>'
      );
  });
</script>
@stop
