<?php $title="Dashboard"; ?>

@extends('layouts.wrapper')

@section('content')
<?php
  $set_tour = TRUE;
?>
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/button.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<style>
  .building-overview-item{
    float: none;
    display: inline-block;
  }
  .bldg-div:hover,
  .bldg-div:focus{
    background-color: #61728A;
  }
  .nav-tabs-responsive {
    overflow: auto;
  }
  .notificationRed:after, .notificationYellow:after{
    content:"?"; /* 21x21 transparent pixels */
    position: absolute;
    background: rgb(194, 0, 12);
    height:3rem;
    top:1rem;
    right:1.5rem;
    width:3rem;
    text-align: center;
    line-height: 2rem;;
    font-size: 2rem;
    font-weight: bold;
    border-radius: 50%;
    color:white;
    padding-top: 3px;
  }
  .rowmargin{
    margin:0 15px 0 15px;
  }
  @media screen and (max-width: 400px){
    .rowmargin{
      margin: 0 8px 0 8px;
    }
  }
</style>

<div class="page-title">{!!$customer->name!!} - Building Management</div>
<div style="margin:auto;overflow:hidden; text-align:center;">
  <?php
    $bld_id_arr = array(); //create a countable array
    foreach ($customer_buildings_for_navbar as $cbfn) {
      $bld_id_arr[] = $cbfn->name;
    }
    $bldg_col = "col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4";
    switch(count($bld_id_arr)){
      case 1:
        $bldg_col = "col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-4";
        break;
      case 2:
        $bldg_col = "col-xs-6 col-xs-offset-0 col-sm-4 col-sm-offset-1";
        break;
      case 3:
      default:
        $bldg_col = "col-xs-6 col-xs-offset-0 col-sm-4 col-sm-offset-0";
        break;
    }
  ?>
  @foreach($customer_buildings_for_navbar as $building)
    <div id="#{!!$building->id!!}" class="@if($set_tour) dashboard_tiles_tour @endif bldg-div {!!$bldg_col!!} " style="color:white; cursor: pointer;" data-toggle="modal" data-target="#{!!$building->id!!}">
      <span class="fa-stack fa-3x" style="margin-top:5pt; margin-bottom:5pt;">
        <i class="fa fa-building fa-stack-2x" aria-hidden="true" style="color:{!!$alarm_icons[$IDbuilding[$building->id]]!!}; padding:5pt;"></i>
      </span>
      <hr style="margin-top:15px; margin-bottom:5px;">
      <div style="font-size:1.2em; min-height:2.8em;">
		    {!!strtoupper($building->name)!!}
      </div>
    </div>
    <? $set_tour = FALSE; ?>
  @endforeach
</div>

@foreach($customer_buildings_for_navbar as $building)
<!-- Modal -->
  <div class="modal fade" id="{!!$building->id!!}" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">{!!$building->name!!}</h3>
        </div>
        <? $set_tour =TRUE; ?>
        <div style="overflow:hidden; height:100%;  width: 100%;">
          <div class="nav-tabs-responsive" style="height:99%; width: 100%; padding-right: 15px;">
            <ul class="nav nav-tabs ">
              <?php $count =count($systemsData[$building->id]); ?>
              @foreach($systemsData[$building->id] as $system)
              <!-- if there's only one system then make it active -->
              <li class="<?php if($count== 1) echo "active"?>">
                <a class="@if($set_tour) dashboard_tiles_tour @endif" data-toggle="tab" href="#{!!preg_replace("/\s+/","",$system->name)!!}">
                  {!!$system->name!!}
                </a>
              </li>
              <? $set_tour =FALSE; ?>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="tab-content">
          <? $set_tour =TRUE; ?>
          @foreach($systemsData[$building->id] as $system)
          <div id="{!!preg_replace("/\s+/","",$system->name)!!}" class="tab-pane fade <?php if($count==1)echo 'active in';?>">
            <!-- Modal body-->
              <div class="modal-body" style="padding-left: 0; padding-right: 0;">

                <div class="removemargin row" style="text-align:center;">
                  <a class="@if($set_tour) dashboard_tiles_tour @endif col-xs-4 col-sm-3 col-md-3 building-overview-item " href="{!!URL::route('building.system', [$building->id, $system->id])!!}">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-home fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Dashboard</p>
                  </a>
                  @foreach($system->items as $item)
                    <!-- SYSTEMS PAGE ITEMS -->
                    <?php
                      $this_icon = "fa-building-o";
                      $this_color = "white";
                      switch ($item->label) {
                        case 'Alarms':
                          $this_icon = "fa-bell";
                          $this_color  = $alarm_icons[$system->alarm_severity];
                          break;
                        case 'Control History':
                        case 'Control Events':
                          $this_icon = "fa-history";
                          break;
                        case 'Zone Control':
                        case 'Zone Status':
                        case 'EMC Zone Status':
                          $this_icon = "fa-gear";
                          break;
                        case 'Operations':
                        case 'Reports':
                        case 'Monitors':
                          $this_icon = "fa-area-chart";
                          break;
                        case 'System Status':
                          $this_icon = "fa-building-o";
                          break;
                        default:
                          $this_icon = "fa-building-o";
                          break;
                      }
                    ?>
                    @if($item->label == 'Operations' )<!--replace operations with reports-->
                      <a class="@if($set_tour) dashboard_tiles_tour @endif col-xs-4 col-sm-3 col-md-3 building-overview-item " href="{!!URL::route('reports.index', [$building->id, $system->id])!!}">
                        <span class="fa-stack fa-3x">
                          <i class="fa {!!$this_icon!!} fa-stack-1x" style="color: {!!$this_color!!};"></i>
                        </span>
                        <hr style="margin-top:5px; margin-bottom:5px;">
                        <p>Reports</p>
                      </a>
                    @else
                      <a class="@if($set_tour) dashboard_tiles_tour @endif col-xs-4 col-sm-3 col-md-3 building-overview-item " href="{!!URL::route('building.dashboard', [$building->id, $system->id, $item->id])!!}" >
                        <span class="fa-stack fa-3x">
                          <i class="fa {!!$this_icon!!} fa-stack-1x" style="color: {!!$this_color!!};"></i>
                        </span>
                        <hr style="margin-top:5px; margin-bottom:5px;">
                        <p>{!!$item->label!!}</p>
                      </a>
                    @endif
                  @endforeach

                  <a class="@if($set_tour) dashboard_tiles_tour @endif col-xs-3 col-sm-3 col-md-3 building-overview-item " href="{!!URL::route('setpointmapping.index', [$building->id, $system->id])!!}">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-tasks fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Setpoints</p>
                  </a>

                  <a class="@if($set_tour) dashboard_tiles_tour @endif col-xs-3 col-sm-3 col-md-3 building-overview-item " href="{!!URL::route('reports.export', [$building->id, $system->id])!!}">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-cloud-download fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Data Export</p>
                  </a>

                  <a class="col-xs-3 col-sm-3 col-md-3 building-overview-item " data-toggle="tooltip" title="For more information on participating in our water usage monitoring program, please contact your EMC distributor.">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-tint fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Water</p>
                  </a>
                  <a class="col-xs-3 col-sm-3 col-md-3 building-overview-item " data-toggle="tooltip" title="For more information on participating in our electricity monitoring program, please contact your EMC distributor.">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-bolt fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Electric</p>
                  </a>
                  <a class="col-xs-3 col-sm-3 col-md-3 building-overview-item " data-toggle="tooltip" title="For more information on participating in our lighting control program, please contact your EMC distributor.">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-lightbulb-o fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Light</p>
                  </a>

                  <a class="col-xs-3 col-sm-3 col-md-3 building-overview-item " data-toggle="tooltip" title="For more information on participating in our motion detection program, please contact your EMC distributor.">
                    <span class="fa-stack fa-3x">
                      <i class="fa fa-arrows fa-stack-1x" style="color: white;"></i>
                    </span>
                    <hr style="margin-top:5px; margin-bottom:5px;">
                    <p>Motion</p>
                  </a>
              </div>
            </div>
            <br>
            <!-- ~Modal body-->
          </div>
          <? $set_tour = FALSE; ?>
          @endforeach
          <!-- default div when no tab is active -->
          <div class="tab-pane fade <?php if($count!=1)echo 'active in';?>">
            <!-- Modal body-->
            <div class="modal-body">
              <div><br><br>Choose a system from above<br><br></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- ~Modal Content-->
    </div>
  </div>
<!-- ~Modal-->
@endforeach

<script language"javascript" type"text/javascript">
  var InitData = {!!json_encode($systemsData)!!};
  console.log('------------------------------------');
  console.log(InitData);
  console.log('------------------------------------');
  console.log('------------------------------------');
  console.log({!!json_encode($customer_buildings_for_navbar)!!});
  console.log('------------------------------------');
  $(document).ready(function(){
    $('.pmd-floating-action').prepend('\
      <a href="javascript:void(0);" class="dashboard_tiles_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
        Your Dashboard\
      </a>'
    );
    $(window).on('resize', function(){
      if ($(window).width() < 500) {
        $(".fa-stack").removeClass( "fa-3x" ).addClass( "fa-2x" );
        $(".building-overview-item").removeClass( "col-xs-3" ).addClass( "col-xs-4" );
      }
    });
    if ($(window).width() < 500) {
      $(".fa-stack").removeClass( "fa-3x" ).addClass( "fa-2x" );
      $(".building-overview-item").removeClass( "col-xs-3" ).addClass( "col-xs-4" );
    }

    //keep the modal on when redirected to this page
    $(function(){
      if(window.location.hash) {
          var hash = window.location.hash;
          $(hash).modal('toggle');
      }
    });

    //add building id hash to the url
    $('div[data-toggle="modal"]').click(function(){
      window.location.hash = $(this).attr('id');
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

  //stop reloading when modal is open or when StopRefresh class is selected
  var timeout = setInterval(function(){
    if($('.modal').hasClass('in') || $('.StopRefresh').hasClass('open')){
      //do nothing
    }
    else{
      window.location.hash = ""
      location.reload(true);
    }
  },300000);
</script>
@stop
