<?php
  $admincss='admin';
  $title="Customer";  
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
    <h2>Customers</h2>
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
      {{HTML::link(URL::route('admin.customer.create'), 'New Customer', ['class'=>'btn btn-primary btn-sm pull-right'])}}
  </div>
  <br>

<div class="well">
  @foreach($customers as $customer)
    <h2 id="{{$customer->id}}">
      {{HTML::link(URL::route('admin.customer.edit', $customer->id), $customer->name)}}
    </h2>
    <div class="row">
      <ul class="list-group" style="color: black;">
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Address 1:</strong>
          @if($customer->address1 !== '')
            {{$customer->address1}}
          @endif
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Address 2:</strong>
          @if($customer->address2 !== '')
            {{$customer->address2}}
          @endif
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>City, State:</strong>
          {{$customer->city}}
          {{$customer->state}}
          @if($customer->state !== '' && $customer->zip !== '')
            ,
          @endif
          {{$customer->zip}}
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Email:</strong>
          @if($customer->email1 !== NULL && $customer->email1 !== '')
            {{HTML::mailto($customer->email1)}}
          @endif
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Email 2:</strong>
          @if($customer->email2 !== NULL && $customer->email2 !== '')
            {{HTML::mailto($customer->email2)}}
          @endif
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Last Updated:</strong> {{date('d M, Y - g:i:s A', strtotime($customer->updated_at))}}
        </li>
        <li class="list-group-item col-xs-12 col-sm-6">
          <strong>Created:</strong> {{date('d M, Y - g:i:s A', strtotime($customer->created_at))}}
        </li>
      </ul>

    <!-- <div class="col-sm-6">
      @if($customer->address1 !== '')
        <p class="lead">{{$customer->address1}}</p>
      @endif
      @if($customer->address2 !== '')
        <p>{{$customer->address2}}</p>
      @endif
      <p>
        {{$customer->city}}
        {{$customer->state}}
        @if($customer->state !== '' && $customer->zip !== '')
          ,
        @endif
        {{$customer->zip}}
      </p>
    </div>

    <div class="col-sm-6">
      @if($customer->email1 !== NULL && $customer->email1 !== '')
        <p>{{HTML::mailto($customer->email1)}}</p>
      @endif
      @if($customer->email2 !== NULL && $customer->email2 !== '')
        <p>{{HTML::mailto($customer->email2)}}</p>
      @endif
      <p><strong>Last Updated:</strong> {{date('d M, Y - g:i:s A', strtotime($customer->updated_at))}}</p>
      <p><strong>Created:</strong> {{date('d M, Y - g:i:s A', strtotime($customer->created_at))}}</p>
    </div> -->
    </div>
    <hr>
  @endforeach
</div>

@stop
