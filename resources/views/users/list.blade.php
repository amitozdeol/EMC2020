<?php $title="Users"; ?>

@extends('layouts.wrapper')

@section('content')
<?php $set_users_tour = true; ?>
<style>
  .customer{
    margin: 5px;
    overflow: hidden;
  }
  .title{
    left: 0;
    width: 100%;
    max-height: 50px;
    margin-top: -5px;
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
    .editbutton{
      padding: 4px 10px;
    }
  }
</style>
<div class="title" style="background-color: white"><h2>Users</h2></div>
<div class="row">
  <br>
  <br>
  <br>
    <a href="{!!URL::route('user.create')!!}" class="users_tour btn btn-primary btn-sm pull-right">
      <span class="visible-xs">
        <span class="glyphicon glyphicon-plus"></span>
      </span>
      <span class="hidden-xs">Add New User</span>
    </a>
</div>
<br>
<div class="users_tour well" style="background-color: #767676;">
  @foreach($users as $user)
  <div class="panel panel-info">
    <div class="panel-heading">
      <div class="panel-title" style="font-size: 18px;">
        <strong>
          {!!$user->prefix!!} {!!$user->first_name!!} {!!$user->middle_initial!!} {!!$user->last_name!!} {!!$user->suffix!!}
        </strong>
        <a href="{!!url('user/'.$user->id.'/edit');!!}" class="@if($set_users_tour) users_tour @endif btn btn-primary btn-sm pull-right editbutton" style="color: white; margin-top: -5px;">
          <span class="visible-xs" style="font-size: 15px;">
            <span class="glyphicon glyphicon-edit"></span>
          </span>
          <span class="hidden-xs">Edit</span>
        </a>
      </div>
    </div>
    <div class="panel-body">
      <strong>Email: </strong><small>{!!$user->email!!}</small>
      <br>
      <strong>Mobile Phone: </strong><small>{!!'('.substr($user->mobile_number,0,3).') '.substr($user->mobile_number,3,3).'-'.substr($user->mobile_number,6,4)!!}</small>
      <br>
      <strong>Mobile Carrier: </strong><small><?php echo (isset($mobile_carrier[$user->mobile_carrier]))?($mobile_carrier[$user->mobile_carrier]): ($user->mobile_carrier); ?> </small>
    </div>
  </div>
  <br>
  <?php $set_users_tour = false; ?>
  @endforeach
</div>
<script>
  $(document).ready(function(){
    $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="users_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Users\
        </a>'
      );
  });
</script>
@stop
