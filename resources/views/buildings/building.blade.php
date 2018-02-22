<?php $title="Building"; ?>

@extends('layouts.wrapper')

@section('content')
  @if(!count($systemsData))
    <div class="alert alert-warning alert-dismissible" role="alert">
      There are no systems set up for this building yet.
      @if(Auth::user()->auth_role === 8)
        <a href="{!!URL::to('building', array($thisBldg->id, 'newsystem'))!!}" class="alert-link">Add one here</a>.
      @endif
    </div>
  @endif

  <div class="page-title" style="height: 95px; line-height: 55px"><h3>{!!$thisBldg->name!!} - Choose System</h3></div>


  @foreach($systemsData as $system)
    <div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 micro-row-detail" style="background-color: #2B3C51; margin-bottom: 0px; min-height: 75px; width:100%; text-align: center;" title="{!!$building_info!!}">
          <h3 style="padding-left: 10px;">{!!$system->name!!}</h3>
        </div>
      </div>
      <div class="row">
        <a class="col-xs-12 col-sm-2 col-md-2 row-detail building-overview-item" href="{!!URL::route('building.system', [$thisBldg->id, $system->id])!!}">
          <img src="{!! asset('images/AptBldg.png') !!}">
          <br>
           <p>Dashboard</p>
        </a>
        <a class="col-xs-12 col-sm-2 col-md-2 row-detail building-overview-item" href="{!!URL::route('alarms', [$thisBldg->id, $system->id])!!}">
		{!!HTML::image($alarm_icons[$system->alarm_severity])!!}
          <br>
          <p>Alarms</p>
        </a>
        <!--<span class="col-xs-12 col-sm-2 col-md-2 row-detail building-overview-item"
           data-toggle="popover"
           data-placement="bottom"
           data-trigger="click"
           title="Building Info"
           data-content="{!!$building_info!!}">
          <img src="{!!}}">
          <br>
          <p>System Info</p>
        </span>-->
        <?php $system_status_id = 1;/*as per dashboard_items table*/?>
        <a class="col-xs-12 col-sm-4 col-md-4 row-detail building-overview-item" href="{{URL::route('building.dashboard', [$thisBldg->id, $system->id, $system_status_id])!!}">
          <img src="{!! asset('images/BuildSet_panel.png') !!}">
          <br>
           <p>System Status</p>
        </a>

        <a class="col-xs-12 col-sm-2 col-md-2 row-detail building-overview-item" href="{!!URL::route('eventstatus', [$thisBldg->id, $system->id])!!}">
          <img src="{!! asset('images/events-icon.png') !!}">
          <br>
          <p>Events</p>
        </a>

        <a class="col-xs-12 col-sm-2 col-md-2 row-detail building-overview-item" href="{!!URL::route('setpointmapping.index', [$thisBldg->id, $system->id])!!}">
          <img src="{!! asset('images/Control_panel.png') !!}">
          <br>
          <p>Setpoints</p>
        </a>
      </div>
    </div>
    <br>

  @endforeach
  <script language"javascript" type"text/javascript">
	  $(document).ready(function(){
      //refresh page every 30 seconds
      $('.StopRefresh').on('click', function(e){
        if(!$(this).hasClass('open')){
          console.log("dropdown is open");
          stop();
        }
        else start();
      });

	    $('.StartRefresh').on('click', function(){
	      start();
	    });
	    $(document).on('click', function (e) {
	      //anything except this element - reload
	      if(!$(e.target).is('.StopRefresh')&& timeout==0) {
	        start();
	      }
	    });
	    //refresh page every 30 seconds
      $(window).scroll(function () {
        //set scroll position in session storage
        sessionStorage.scrollPos = $(window).scrollTop();
      });
      var init = function () {
        //get scroll position in session storage
        $(window).scrollTop(sessionStorage.scrollPos || 0)
      };
      window.onload = init;
	  });

	  var timeout = setTimeout("location.reload(true);",300000);
	  function stop() {
	    clearTimeout(timeout);
	    timeout = 0;
	  }
	  function start() {
	    timeout = setTimeout("location.reload(true);",300000);
	  }
	</script>


@stop
