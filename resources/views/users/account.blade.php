<?php $title="User Account"; ?>

@extends('layouts.wrapper')

@section('content')
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_css = ['/css/tgl.css', "/css/building/mytabs.css"];    //add file name
    foreach ($import_css as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::style($appendDate);
      }
    }
  ?>

  <?php $help_id='youraccount'; ?>

  <style type="text/css">
    .borderlist {
      list-style-position:inside;
      padding: 5px 0px;
      margin: 1px;
      font-size: 14pt;
    }
    .row-detail-relief{
      color:#336;
      text-shadow: 2px 2px 16px rgba(170,170,200,1.9);
    }
    .title{
      left: 0;
      width: 100%;
      max-height: 50px;
      text-align: center;
      background: white;
      margin-top: -5px;
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
    .usercontrol, .admincontrol{
      padding-left: 10px;
    }
    @media (max-width: 1080px) {
      .btn-sm{
        font-size: 12px;
      }
      .panel-body{
        padding: 5px 0px;
      }
      .tgl + .tgl-btn{
        width: 4em;
      }
      .usercontrol, .admincontrol, .adminsystem{
        padding: 0px;
      }
    }
    @media(max-width: 400px){
      table{
        font-size: 12px;
      }
    }
  </style>

  <!-- ========================================================================================================================
                                  USER DETAILS
    ======================================================================================================================== -->
    <div class="title">
      <h2>Your Account</h2>
    </div>
      <br>
      <br>
      <br>
      @if(Session::has('message'))
        <div class="alert {!! Session::get('alert-class') !!}" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! Session::get('message') !!}
        </div>
      @endif
    <div class="col-xs-12 row-padding">
      <div class="user_account_tour pull-right">
        {!!HTML::link(URL::route('account.password.index'), 'Update Password', ['class'=>'btn btn-info btn-sm pull-right col-xs-12', 'style'=>'font-weight: bold;'])!!}
      </div>
    </div>
  {!!Form::model($user, ['route' => 'account.update', 'method'=>'POST'])!!}
    <div class="col-xs-12 info-box" style="padding-bottom: 15px;">
      <h2 class="col-xs-12">User Details</h2>
      <div class="user_account_tour col-xs-12 col-sm-6">
        {!!Form::label('email', 'Email')!!}
        {!!Form::email('email', NULL, ['class'=>'form-control input-lg', 'placeholder'=>'Email', 'id'=>'email', 'required'])!!}
        <br>
      </div>
      <div class="user_account_tour col-xs-12 col-sm-3">
        {!!Form::label('mobile_number', 'Mobile Number')!!}
        {!!Form::text('mobile_number', Input::old('mobile_number'), ['class'=>'form-control input-lg', 'placeholder'=>'XXXXXXXXXX', 'id'=>'mobile_number', 'type'=>"tel",'title'=>'10-digit US phone number'])!!}
      </div>

      <div class="user_account_tour col-xs-12 col-sm-3">
        {!!Form::label('mobile_carrier', 'Mobile Carrier')!!}
        {!!Form::select('mobile_carrier', $mobile_carriers, $user->mobile_carrier, ['class' => 'form-control input-lg', 'id'=>'mobile_carrier']) !!}
        <a href="http://freecarrierlookup.com/" target="_blank">Look it up</a>
      </div>

      <div class="user_account_tour col-sm-2">
        {!!Form::label('prefix', 'Prefix')!!}
        {!!Form::select('prefix', array(' ','Mx.','Ms.','Mrs.','Mr.'), $user->prefix, ['class' => 'form-control', 'id'=>'prefix']) !!}
      </div>

      <div class="user_account_tour col-sm-3">
        {!!Form::label('first_name', 'First Name')!!}
        {!!Form::text('first_name', Input::old('first_name'), ['class'=>'form-control', 'placeholder'=>'First Name', 'id'=>'first_name'])!!}
      </div>

      <div class="user_account_tour col-sm-2">
        {!!Form::label('middle_initial', 'Middle Initial')!!}
        {!!Form::text('middle_initial', Input::old('middle_initial'), ['class'=>'form-control', 'placeholder'=>'Middle Initial', 'id'=>'middle_initial'])!!}
      </div>

      <div class="user_account_tour col-sm-3">
        {!!Form::label('last_name', 'Last Name')!!}
        {!!Form::text('last_name', Input::old('last_name'), ['class'=>'form-control', 'placeholder'=>'Last Name', 'id'=>'last_name'])!!}
      </div>

      <div class="user_account_tour col-sm-2">
        {!!Form::label('suffix', 'Suffix')!!}
        {!!Form::select('suffix', array(' ','Sr.','Jr.','III','M.D.','PhD','CPA'), $user->suffix, ['class' => 'form-control', 'id'=>'suffix']) !!}
      </div>
    </div>
    <br>
    <!-- ========================================================================================================================
                                  CUSTOMER SUBSCRIPTIONS
    ======================================================================================================================== -->
    <div class="col-xs-12" style="margin-bottom: 20px; padding: 0px;">
      <div class="col-sm-12" style="padding: 0px;">
        <h2 style="padding-top: 20px">Alarm Email Subscriptions</h2>
        @if( !count( (array)$customer_buildings_for_navbar ) && Auth::user()->role < 6)
          <div class="alert alert-warning">You don't have access set up for any buildings.</div>
        @endif
        @if(Auth::user()->role < 6)
          <ul id="myTab" class="myTab nav nav-tabs" style="font-size: 15px;">
            <?php
              $count=0;
              $set_user_account_tour = true;
            ?>
            @foreach($buildings as $building)
              <li class="<?php if($count == 0) echo"active ";?>"><a class="@if($set_user_account_tour) user_account_tour @endif" href="#{!!str_replace(' ','',$building->name)!!}-content" data-toggle="tab" id="building-{!!$building->id!!}-title">{!!$building->name!!}</a></li>
              <?php $count++; $set_user_account_tour = false;?>
            @endforeach
          </ul>
          <div id="myTabContent" class="myTabContent tab-content row">
            <?php
              $count=0;
              $set_user_account_tour = true;
              $set_sys_tour = true;
            ?>
            @foreach($buildings as $building)
              <div class="tab-pane fade <?php if($count == 0) echo"in active ";?>" id="{!!str_replace(' ','',$building->name)!!}-content">
                <?php $found_system = 0; $first_system=true; $count++;?>
                <!-- SYSTEMS DROP-DOWN -->
                <div style="margin: 5px;">
                  <select @if($set_sys_tour)id="first-system-select"@endif onchange="UserSystemDropdown(this,{!!$building->id!!})" class="@if($set_user_account_tour) user_account_tour @endif form-control" style="height:34px; font-size:14px; text-align-last: center;">
                    @foreach($systems as $system)
                      @if($system->building_id == $building->id)
                        <option id="system-{!!$system->id!!}-title" value="{!!$system->id!!}" data-toggle="collapse" data-href="{!!str_replace('/','',str_replace(' ','',$system->name))!!}-content">
                          {!!$system->name!!}
                        </option>
                        <?php $set_sys_tour = false; ?>
                      @endif
                    @endforeach
                  </select>
                </div>
                @foreach($systems as $system)
                  @if($system->building_id == $building->id)
                    <div class="container-fluid collapse{!!($first_system)?' in':'';!!}" id="{!!str_replace(' ','',$system->name)!!}-content" style="margin-bottom: 15px;">
                      <?php $first_system=false;?>
                      <!-- SENSORS -->
                      <div class="@if($set_user_account_tour) user_account_tour @endif col-sm-7" style="padding: 0px;">
                        <h3><b>Sensors</b></h3>
                        <table class="table table-responsive table-bordered info-box table-striped">
                          <thead>
                            <tr>
                              <th>Description</th>
                              <th>Email</th>
                              <th>Text</th>
                            </tr>
                          </thead>
                          @if(isset($commands[$system->id]))
                            @foreach($commands[$system->id] as $function => $command)
                              <tbody>
                                <tr><td colspan="15" style="background: #c3c3c3;"><strong>{!!$command!!} Sensors</strong></td></tr>
                                <tr>
                                  <td><strong>Warning</strong></td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"
                                        id="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"
                                        {!!(isset($sensor_alerts[$system->id]['warning'][$function]['email']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"
                                        id="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"
                                        {!!(isset($sensor_alerts[$system->id]['warning'][$function]['sms']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"></label>
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><strong>Critical</strong></td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"
                                        id="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"
                                        {!!(isset($sensor_alerts[$system->id]['critical'][$function]['email']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"></label>
                                    </div>
                                  </td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"
                                        id="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"
                                        {!!(isset($sensor_alerts[$system->id]['critical'][$function]['sms']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"></label>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                            @endforeach
                          @endif
                        </table>
                      </div>
                      <!-- CONTROLS -->
                      <div class="@if($set_user_account_tour) user_account_tour @endif col-sm-5 usercontrol">
                          <h3><b>Controls</b></h3>
                          <table class="table table-responsive table-bordered info-box table-striped">
                            <thead>
                              <tr>
                                <th>Description</th>
                                <th>Email</th>
                                <th>Text</th>
                              </tr>
                            </thead>
                            @foreach($alarm_codes as $alarm_code)
                              @if($alarm_code->alarm_class == "control")
                              <tbody>
                                <tr>
                                  <td><strong>{!!$alarm_code->description!!}</strong></td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'alarm-'.$system->id.'-'.$alarm_code->id.'-email'!!}"
                                        id="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-email"
                                        {!!(isset($control_alerts[$system->id][$alarm_code->id]['email']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-email">
                                      </label>
                                    </div>
                                  </td>
                                  <td>
                                    <div style="display: inline-block;">
                                      <input class="tgl tgl-skewed"
                                        type="checkbox"
                                        name="{!!'alarm-'.$system->id.'-'.$alarm_code->id.'-sms'!!}"
                                        id="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-sms"
                                        {!!(isset($control_alerts[$system->id][$alarm_code->id]['sms']))?"checked":""!!}>
                                      <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-sms">
                                      </label>
                                    </div>
                                  </td>
                                </tr>
                              </tbody>
                              @endif
                            @endforeach
                        </table>
                      </div>
                    </div>
                    <?php $set_user_account_tour = false; ?>
                  @endif
                @endforeach
              </div>
            @endforeach
          </div>
        <!-- *************************************************************************************************************************************
                                                    Show only if an EAW staff member
        ************************************************************************************************************************************** -->
        @elseif(Auth::user()->role >= 6)
          <ul id="myTabAdmin" class="myTab nav nav-tabs" style="font-size: 15px;">
            <li class="active"><a href="#server-content" data-toggle="tab" id="server-title">Server Monitoring</a></li>
            <li><a href="#cusomer-operations-content" data-toggle="tab" id="cusomer-operations-title">Customer Operations Monitoring</a></li>
          </ul>
          <div id="myTabContent" class="myTabContent tab-content row " style="margin-bottom: 15px;">
            <!-- For selecting server based subsriptions -->
            <div class="tab-pane fade in active" id="server-content" style="padding-bottom: 10px;">
              <div class="col-sm-6">
                <!-- SERVER -->
                <table class="table table-responsive table-bordered info-box table-striped" >
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Email</th>
                      <th>Text</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($log_types as $log_type)
                    @if($log_type->id <= round(count($log_types)/2))
                      <tr>
                        <td class="ServerLogName"><strong>{!!str_replace('_', ' ', $log_type->name)!!}</strong></td>
                        <td>
                          <div style="display: inline-block;">
                            <input class="tgl tgl-skewed"
                              type="checkbox"
                              name="{!!'log-0-'.$log_type->id.'-email'!!}"
                              id="log-0-{!!$log_type->id!!}-email"
                              {!!(isset($log_alerts[0][$log_type->id]['email']))?"checked":""!!}>
                            <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="log-0-{!!$log_type->id!!}-email"></label>
                          </div>
                        </td>
                        <td>
                          <div style="display: inline-block;">
                            <input class="tgl tgl-skewed"
                              type="checkbox"
                              name="{!!'log-0-'.$log_type->id.'-sms'!!}"
                              id="log-0-{!!$log_type->id!!}-sms"
                              {!!(isset($log_alerts[0][$log_type->id]['sms']))?"checked":""!!}>
                            <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="log-0-{!!$log_type->id!!}-sms"></label>
                          </div>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                  </tbody>
                </table>
              </div>
              <div class="col-sm-6">
                <table class="table table-responsive table-bordered info-box table-striped">
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Email</th>
                      <th>Text</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($log_types as $log_type)
                    @if($log_type->id > round(count($log_types)/2))
                      <tr>
                        <td class="ServerLogName"><strong>{!!str_replace('_', ' ', $log_type->name)!!}</strong></td>
                        <td>
                          <div style="display: inline-block;">
                            <input class="tgl tgl-skewed"
                              type="checkbox"
                              name="{!!'log-0-'.$log_type->id.'-email'!!}"
                              id="log-0-{!!$log_type->id!!}-email"
                              {!!(isset($log_alerts[0][$log_type->id]['email']))?"checked":""!!}>
                            <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="log-0-{!!$log_type->id!!}-email"></label>
                          </div>
                        </td>
                        <td>
                          <div style="display: inline-block;">
                            <input class="tgl tgl-skewed"
                              type="checkbox"
                              name="{!!'log-0-'.$log_type->id.'-sms'!!}"
                              id="log-0-{!!$log_type->id!!}-sms"
                              {!!(isset($log_alerts[0][$log_type->id]['sms']))?"checked":""!!}>
                            <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="log-0-{!!$log_type->id!!}-sms"></label>
                          </div>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <!-- -------------------------------------------------------------------------------------------------------------------------------------
            For selecting system specific email notifications
            ------------------------------------------------------------------------------------------------------------------------------------------- -->
            <div class="tab-pane fade" id="cusomer-operations-content" style="padding-bottom: 10px;">
              <div class="col-sm-6">
                <strong>Select a Customer: </strong>
                <select onchange="AdminSystemDropdown(this)" class="form-control" style="height:34px; font-size:14px; text-align-last: center;">
                  @foreach($customers as $customer)
                    <option id="customer-{!!$customer->id!!}-title" value="{!!$customer->id!!}" data-toggle="collapse" data-href="{!!str_replace(' ','',$customer->name)!!}-content">
                      {!!$customer->name!!} : {!!$customer->address1!!}, {!!$customer->city!!}, {!!$customer->state!!}, {!!$customer->zip!!}
                    </option>
                  @endforeach
                </select>
              </div>
              <?php $first_customer=true;?>
              @foreach($customers as $customer)
                <div class="collapse{!!($first_customer)?' in':'';!!}" id="{!!str_replace(' ','',$customer->name)!!}-content">
                  <div class="col-sm-6">
                    <?php $first_customer=false;?>
                    <strong>Select a Building:</strong>
                    <select onchange="AdminBuildingSystemDropdown(this)" class="form-control" style="height:34px; font-size:14px; text-align-last: center;">
                      @foreach($buildings as $building)
                        @if($building->customer_id == $customer->id)
                          <option id="building-{!!$building->id!!}-title" value="{!!$building->customer_id!!}" data-toggle="collapse" data-href="{!!str_replace(' ','',$building->name)!!}-content">
                            {!!$building->name!!} : {!!$building->address1!!}, {!!$building->city!!}, {!!$building->state!!}, {!!$building->zip!!}
                          </option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <?php $found_building = 0;?>
                  <?php $first_building=true;?>
                  <div class="col-sm-12 row-padding">
                    @foreach($buildings as $building)
                      @if($building->customer_id == $customer->id)
                        <?php $found_building = 1;?>
                        <div class="container-fluid collapse{!!($first_building)?' in':'';!!}" id="{!!str_replace(' ','',$building->name)!!}-content" style="padding: 0px; margin: 0px -10px;">
                          <?php $first_building=false;?>
                          <?php $found_system = 0; ?>
                          @foreach($systems as $system)
                            @if($system->building_id == $building->id)
                              <?php $found_system = 1; ?>
                              <div class="panel panel-info">
                                <h3 class="panel-heading panel-title" id="system-{!!$system->id!!}-title">
                                  <strong>{!!$system->name!!}</strong>
                                </h3>
                                <div class=" panel-body container-fluid collapse in" id="{!!str_replace(' ','',$system->name)!!}-content" style="margin-bottom: 15px;">
                                  <!-- SENSORS -->
                                  <div class="col-sm-7 plain-list" style="padding: 0px;">
                                    <h3><b>Sensors</b></h3>
                                    <table class="table table-responsive table-bordered info-box table-striped" >
                                      <thead>
                                        <tr>
                                          <th>Description</th>
                                          <th>Email</th>
                                          <th>Text</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @if(isset($commands[$system->id]))
                                          @foreach($commands[$system->id] as $function => $command)
                                            <tr><td colspan="15" style="background: #c3c3c3;"><strong>{!!$command!!} Sensors</strong></td></tr>
                                            <tr>
                                              <td><strong>Warning</strong></td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"
                                                  id="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"
                                                  {!!(isset($sensor_alerts[$system->id]['warning'][$function]['email']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="{!!'warning-'.$system->id.'-'.$function.'-email'!!}"></label>
                                                </div>
                                              </td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed "
                                                  type="checkbox"
                                                  name="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"
                                                  id="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"
                                                  {!!(isset($sensor_alerts[$system->id]['warning'][$function]['sms']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="{!!'warning-'.$system->id.'-'.$function.'-sms'!!}"></label>
                                                </div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td><strong>Critical</strong></td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"
                                                  id="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"
                                                  {!!(isset($sensor_alerts[$system->id]['critical'][$function]['email']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="{!!'critical-'.$system->id.'-'.$function.'-email'!!}"></label>
                                                </div>
                                              </td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed "
                                                  type="checkbox"
                                                  name="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"
                                                  id="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"
                                                  {!!(isset($sensor_alerts[$system->id]['critical'][$function]['sms']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="{!!'critical-'.$system->id.'-'.$function.'-sms'!!}"></label>
                                                </div>
                                              </td>
                                            </tr>
                                          @endforeach
                                        @endif
                                      </tbody>
                                    </table>
                                  </div>
                                  <!-- CONTROLS -->
                                  <div class="col-sm-5 plain-list admincontrol">
                                    <h3><b>Controls</b></h3>
                                    <table class="table table-responsive table-bordered info-box table-striped" >
                                      <thead>
                                        <tr>
                                          <th>Description</th>
                                          <th>Email</th>
                                          <th>Text</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($alarm_codes as $alarm_code)
                                          @if($alarm_code->alarm_class == "control")
                                          <tr>
                                            <td>{!!$alarm_code->description!!}</td>
                                            <td>
                                              <div style="display: inline-block;">
                                                <input class="tgl tgl-skewed"
                                                type="checkbox"
                                                name="{!!'alarm-'.$system->id.'-'.$alarm_code->id.'-email'!!}"
                                                id="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-email"
                                                {!!(isset($control_alerts[$system->id][$alarm_code->id]['email']))?"checked":""!!}>
                                                <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-email"></label>
                                              </div>
                                            </td>
                                            <td>
                                              <div style="display: inline-block;">
                                                <input class="tgl tgl-skewed"
                                                type="checkbox"
                                                name="{!!'alarm-'.$system->id.'-'.$alarm_code->id.'-sms'!!}"
                                                id="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-sms"
                                                {!!(isset($control_alerts[$system->id][$alarm_code->id]['sms']))?"checked":""!!}>
                                                <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="alarm-{!!$system->id!!}-{!!$alarm_code->id!!}-sms"></label>
                                              </div>
                                            </td>
                                          @endif
                                        @endforeach
                                      </tbody>
                                    </table>
                                  </div>
                                  <div class="col-xs-12" style="text-align: center;">
                                    <h3><b>System</b></h3>
                                  </div>
                                  <div class="col-xs-12" style="padding: 0px;">
                                    <!-- SYSTEM -->
                                    <div class="col-xs-12 col-sm-6 adminsystem">
                                      <table class="table table-responsive table-bordered info-box table-striped" >
                                        <!-- ============ SYSTEM TITLE LIST ITEM 1 ============ -->
                                        <thead>
                                          <tr>
                                            <th>Description</th>
                                            <th>Email</th>
                                            <th>Text</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($log_types as $log_type)
                                            @if($log_type->id <= round(count($log_types)/2))
                                            <tr>
                                              <td>{!!str_replace('_', ' ', $log_type->name)!!}</td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'log-'.$system->id.'-'.$log_type->id.'-email'!!}"
                                                  id="log-{!!$system->id!!}-{!!$log_type->id!!}-email"
                                                  {!!(isset($log_alerts[$system->id][$log_type->id]['email']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="log-{!!$system->id!!}-{!!$log_type->id!!}-email"></label>
                                                </div>
                                              </td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'log-'.$system->id.'-'.$log_type->id.'-sms'!!}"
                                                  id="log-{!!$system->id!!}-{!!$log_type->id!!}-sms"
                                                  {!!(isset($log_alerts[$system->id][$log_type->id]['sms']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="log-{!!$system->id!!}-{!!$log_type->id!!}-sms"></label>
                                                </div>
                                              </td>
                                            @endif
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                    <div class="col-sm-6 adminsystem">
                                      <table class="table table-responsive table-bordered info-box table-striped" >
                                        <!-- ============ SYSTEM TITLE LIST ITEM 2 ============ -->
                                        <thead>
                                          <tr>
                                            <th>Description</th>
                                            <th>Email</th>
                                            <th>Text</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($log_types as $log_type)
                                            @if($log_type->id > round(count($log_types)/2))
                                            <tr>
                                              <td>{!!str_replace('_', ' ', $log_type->name)!!}</td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'log-'.$system->id.'-'.$log_type->id.'-email'!!}"
                                                  id="log-{!!$system->id!!}-{!!$log_type->id!!}-email"
                                                  {!!(isset($log_alerts[$system->id][$log_type->id]['email']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="EMAIL" for="log-{!!$system->id!!}-{!!$log_type->id!!}-email"></label>
                                                </div>
                                              </td>
                                              <td>
                                                <div style="display: inline-block;">
                                                  <input class="tgl tgl-skewed"
                                                  type="checkbox"
                                                  name="{!!'log-'.$system->id.'-'.$log_type->id.'-sms'!!}"
                                                  id="log-{!!$system->id!!}-{!!$log_type->id!!}-sms"
                                                  {!!(isset($log_alerts[$system->id][$log_type->id]['sms']))?"checked":""!!}>
                                                  <label class="tgl-btn" data-tg-off="OFF" data-tg-on="TEXT" for="log-{!!$system->id!!}-{!!$log_type->id!!}-sms"></label>
                                                </div>
                                              </td>
                                            @endif
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            @endif
                          @endforeach
                          @if($found_system == 0)
                          <div class="col-xs-12 alert alert-warning">This building has no systems set up yet.</div>
                          @endif
                        </div>
                      @endif
                    @endforeach
                  </div>

                  @if($found_building == 0)
                    <div class="col-xs-12 alert alert-warning">This customer has no buildings set up yet.</div>
                  @endif
                </div>
            @endforeach
          </div><br><br>
        @endif
      </div>
    </div>

    <div class="row">
      <br>

      <div class="col-sm-10 col-sm-offset-1">

        <div class="user_account_tour col-xs-12 col-sm-4 col-md-3 pull-right">
          {!!Form::submit('Submit', ['class'=>'btn btn-primary btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>
        <div class="user_account_tour col-xs-12 col-sm-4 col-md-3 pull-left">
          {!!HTML::link (URL::route('account.index'), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
          <div class="col-xs-12"><br>{{-- Spacing for mobile --}}</div>
        </div>

      </div>

    </div>
  {!!Form::close()!!}

  <?
    //Cache control - Scripts
    //Add last modified date of a file to the URL, as get parameter.
    $import_scripts = ['/js/bootstrap-tabcollapse.js'];
    foreach ($import_scripts as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::script($appendDate);
      }

    }
  ?>
  <script language"javascript" type"text/javascript">
    $(document).ready(function(){
      $('#myTab').tabCollapse();
      $('#myTabAdmin').tabCollapse();
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="user_account_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Need Help?\
        </a>'
      );
      var system_options = document.getElementById('first-system-select').options;
      var i,min_val = 10000;
      for(i = 0; i< system_options.length; i++){
        min_val = (min_val > system_options[i].value)? system_options[i].value: min_val;
      }
      $('#first-system-select').val(min_val).change();
    });

    function UserSystemDropdown(s,building_id){
      var select_ref = s[s.selectedIndex].dataset.href;
      @foreach($systems as $system)
        if(building_id == Number({!!$system->building_id!!})){
          console.log("same building");
          if(select_ref != "{!!str_replace('/','',str_replace(' ','',$system->name))!!}-content"){
            setTimeout(function () {
              $("#{!!str_replace('/','',str_replace(' ','',$system->name))!!}-content").collapse('hide');
            }, 100);
          }
        }else{
          console.log("guess we're not in the same building");
        }
      @endforeach
      console.log(select_ref);
      if(!$("#"+select_ref).hasClass('in')){
        setTimeout(function () {
          $("#"+select_ref+":hidden").collapse('show');
        }, 100);
      }
    }
    /*show first system one by default*/
    function AdminSystemDropdown(s){
      var content = s[s.selectedIndex].dataset.href;
      @foreach($customers as $customer)
          var localcontent = "{!!str_replace(' ','',$customer->name)!!}-content";
          if(content.trim() != localcontent.trim())
            $("#"+localcontent+":visible").collapse('hide');
      @endforeach
      /*toggle the selected system*/
      $("#"+content).delay(300).collapse('toggle');
    }
    @if(Auth::user()->role >= 6)
      function AdminBuildingSystemDropdown(s){
        var content = s[s.selectedIndex].dataset.href;
        @foreach($buildings as $building)
          if({!!$building->customer_id!!} == s[s.selectedIndex].value){
            var localcontent = "{!!str_replace(' ','',$building->name)!!}-content";
            if(content.trim() != localcontent.trim())
              $("#"+localcontent+":visible").collapse('hide');
          }
        @endforeach
        /*toggle the selected system*/
        $("#"+content).collapse('toggle');
      }
    @endif  </script>
@stop
