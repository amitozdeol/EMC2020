<?php $title="Report"; ?>

<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/tgl.css', '/js/c3-0.4.14/c3.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>
<style>
  .panel.with-nav-tabs .panel-heading{
    padding: 5px 5px 0 5px;
  }
  .panel.with-nav-tabs .nav-tabs{
    border-bottom: none;
  }
  .panel.with-nav-tabs .nav-justified{
    margin-bottom: -1px;
  }
    /*** PANEL PRIMARY ***/
  .with-nav-tabs.panel-primary .nav-tabs > li > a,
  .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
      color: #fff;
      cursor: pointer;
  }
  .with-nav-tabs.panel-primary .nav-tabs > .open > a,
  .with-nav-tabs.panel-primary .nav-tabs > .open > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > .open > a:focus,
  .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
    color: #fff;
    background-color: #3071a9;
    border-color: transparent;
  }
  .with-nav-tabs.panel-primary .nav-tabs > li.active > a,
  .with-nav-tabs.panel-primary .nav-tabs > li.active > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > li.active > a:focus {
    color: #428bca;
    background-color: #fff;
    border-color: #428bca;
    border-bottom-color: transparent;
  }
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu {
      background-color: #428bca;
      border-color: #3071a9;
  }
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a {
      color: #fff;   
  }
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
      background-color: #3071a9;
  }
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a,
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
  .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
      background-color: #4a9fe9;
  }
  .chart-container{
    background: white;
    box-shadow: black 0.1em 0.1em 0.2em;
    padding: 5px;
    min-height: 500px;
  }
  .legend span {
    display: inline-block;
    margin-left: 7px;
    margin-right: 7px;
    padding: 5px;
  }
  .legendContainer{
    text-align: center;
    height: 400px;
    width: 30%;
    overflow-y: scroll;
    position: absolute;
    top: 55px;
    right: 40px;
    display: none;
  }
  .chart-buttons{
    position: absolute;
    cursor: pointer;
    padding: 5px;
    border: 1px solid;
    box-shadow: black 0.1em 0.1em 0.2em;
  }
  .chart-buttons:hover, .buttonactive{
    background: #989898;
    border-radius: 4px;
  }
  .legend-button{
    top: 5px;
    right: 30px;
  }
  .expand-button{
    top: 5px;
    right: 70px;
  }
  .chart_outer{
    /* padding: 0 15px 0; */
    position: relative;
  }
  .btn-sm{
    padding: 10px;
  }
  .js-reports-load{
    padding-bottom: 15px;
    padding-top: 10px;
  }
  .nodata{
    position: absolute;
    top: 50%;
    left: 40%;
    z-index: 1;
  }
  .c3-title{
    font: 20px sans-serif !important;
  }
  /* zoom selected rectangle  */
  .c3-brush .extent {
      fill-opacity: .5;
  }
  .tab-selector{
    background-color: #ddd;
    box-shadow: #000 0.1em -0.1em 0.15em;
    border-radius: 4px 4px 0 0;
  }
  .ChartFunction-dropdown{
    display: inline-block;
    margin: -20px 0 0;
    vertical-align: middle;
  }
  .dropdown-pull-right {
    float: right !important;
    right: 0;
    left: auto;
  }
  .dropdown-pull-right>.dropdown-menu {
    right: 0;
    left: auto;
    top: initial;
  }
  /* .tabtodropdown{
    padding: 0 15px;
  } */
  .c3-legend-items-hidden {
      opacity: 0.4;
  }
  @media screen and (max-width: 600px){
    .legendContainer{
      width: 50%;
    }
    .legend{
      font-size: 12px;
    }
    .chart-wrapper{
      padding: 15px 0 !important;
    }
    /* .tabtodropdown{
      padding: 0;
    } */
  }
  @media screen and (max-width: 1080px){
    #missingcharts .fa{
      font-size: 30px;
    }
    .legend-button{
      right: 15px;
    }
    .expand-button{
      right: 55px;
    }
    .chart_outer{
      padding: 0;
    }
    .chart-container{
      min-height: 300px;
    }
  }
</style>
@extends('layouts.wrapper')
@section('content')
  @include('buildings.DashboardSidebar')
  <main id="page-content-wrapper" role="main" style="margin: 30px 10px; position: relative;">
    <div id="report-main-div">
      <br>
      <div class="report-container" style="width: 100%;">
        <!-- set timerange to Today when user first load the page -->
        @if( !Session::has("SelectOptionTime"))
          {!!Session::flash('SelectOptionTime', 'Today')!!}
        @endif
        <div class="panel panel-default">
          <label class="panel panel-heading" style="margin-bottom: 0; width: 100%;">Global Settings</lable>
          <div class="panel panel-heading" style="padding: 0px 20px;">
            <!-- hide alert, only show when select an option from dropdown -->
            <div class="global-alert alert alert-warning alert-dismissable fade in" style="display: none;">
              <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Warning!</strong> This will load all the charts for a time frame, which will be slower. We recommend using custom chart filter for faster loading.
            </div>
          {!!Form::open(['route'=>['reports.update', $thisBldg->id, $thisSystem->id] ])!!}
            <div>
              <label class="reports_tour">
                Filter
                <?php
                  if($routePrefix != 'touchscreen')   
                    $timerange_selection = ['Today' => 'Today', 'Last Week' => 'Last Week', 'Last Month' => 'Last Month', 'Last Year' => 'Last Year', 'Custom' => 'Custom'];
                  else //Touchscreen
                    $timerange_selection = ['Today' => 'Today', 'Last Week' => 'Last Week'];
                ?>
                {!! Form::select('timerange', $timerange_selection, Session::get("SelectOptionTime"), ['class'=>'form-control', 'id'=>'Globaltimerange-select']) !!}
              </label>
              <label class="reports_tour" id="SD-label" style="display: none;">
                Start Date
                {!!Form::input('date', 'start-date', date_format($startDate, 'Y-m-d'), ['class' => 'form-control'])!!}
              </label>
              <label class="reports_tour" id="ED-label" style="display: none;">
                End Date
                {!!Form::input('date', 'end-date', date_format($endDate, 'Y-m-d'), ['class' => 'form-control'])!!}
              </label>
              <div class="form-group" style="display: inline-block; margin: 0; vertical-align: bottom; padding: 0 10px;">
                <div class="reports_tour ">
                    <label>
                      Show Retired Devices
                      {!! Form::checkbox('retired', 1, $retired, ['class' => 'tgl tgl-skewed', 'id'=>'retiredcheckbox']) !!}
                      <label class='tgl-btn' data-tg-off='HIDE' data-tg-on='SHOW' for='retiredcheckbox' style="margin: 5px 0 0;"></label>
                    </label>
                </div>
              </div>
            {!!Form::submit('Submit', ['class'=>'reports_tour btn btn-default btn-sm', 'style' => 'display: inline-block;'])!!}
            </div>
          {!!Form::close()!!}
          </div>
        </div>
        <!-- Add more chart button -->
        <div class="col-lg-12">
          <div class="dropdown-pull-right button-group" style="margin-bottom: 5px;">
            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" style="color: #333;">Add Charts&nbsp;
              <i class="fa fa-bar-chart" aria-hidden="true" style="font-size: 1em;"></i>
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              @for ($i = 0; $i < count($functionList); $i++)
                <li><a href="#" class="small" data-value="{!!str_replace(' ', '', $functionList[$i])!!}" tabIndex="{!!$i!!}">
                  <input class='tgl tgl-skewed' id='cb3{!!str_replace(' ', '', $functionList[$i])!!}' type='checkbox' checked>
                  <label class='tgl-btn' data-tg-off='HIDE' data-tg-on='SHOW' for='cb3{!!str_replace(' ', '', $functionList[$i])!!}' style="display: inline-block"></label>
                  <h4 class="ChartFunction-dropdown" data-tg-off='HIDE' data-tg-on='SHOW' for='cb3{!!str_replace(' ', '', $functionList[$i])!!}'>&nbsp;{!!$functionList[$i]!!}</h4>
                </a></li>
              @endfor
            </ul>
          </div>
        </div>

        <div class="row" style="width: 100%; overflow: hidden; margin: auto;">
          <!-- REPORTS CONTAINER -->
          @for ($i = 0; $i < count($functionList); $i++)
            <div id = "{!!str_replace(' ', '', $functionList[$i]).'wrapper'!!}" class="js-reports-load {!!str_replace(' ', '', $functionList[$i])!!}wrapper chart-wrapper" >
              <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                  <ul class="nav nav-tabs">
                    <li class="time-range {!!Session::get('SelectOptionTime') == 'Today' ? 'active' : ''!!}" data-range="Today">
                      <a data-toggle="tab" onclick="PerChartFilter('Today', '{!!$functionList[$i]!!}', '{!!$i!!}')">Today</a>
                    </li>
                    <li class="time-range{!!Session::get('SelectOptionTime') == 'Last Week' ? 'active' : ''!!}" data-range="Last Week">
                      <a data-toggle="tab" onclick="PerChartFilter('Last Week', '{!!$functionList[$i]!!}', '{!!$i!!}')">Last Week</a>
                    </li>
                    @if($routePrefix != 'touchscreen')
                      <li class="time-range {!!Session::get('SelectOptionTime') == 'Last Month' ? 'active' : ''!!}" data-range="Last Month">
                        <a data-toggle="tab" onclick="PerChartFilter('Last Month', '{!!$functionList[$i]!!}', '{!!$i!!}')">Last Month</a>
                      </li>
                      <li class="time-range {!!Session::get('SelectOptionTime') == 'Last Year' ? 'active' : ''!!}" data-range="Last Year">
                        <a data-toggle="tab" onclick="PerChartFilter('Last Year', '{!!$functionList[$i]!!}', '{!!$i!!}')">Last Year</a>
                      </li>
                      <li class="time-range {!!Session::get('SelectOptionTime') == 'Custom' ? 'active' : ''!!}" data-range="Custom">
                        <a data-toggle="modal" data-target="#CustomDateModal{!!str_replace(' ', '', $functionList[$i])!!}">Custom</a>
                      </li>
                    @endif
                  </ul>
                </div>
                <div class="panel-body">
                    <!-- Custom Date Modal -->
                    <div id="CustomDateModal{!!str_replace(' ', '', $functionList[$i])!!}" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select Custom Date for {!!str_replace(' ', '', $functionList[$i])!!}</h4>
                          </div>
                          <div class="modal-body" id="modal-body-{!!$functionList[$i]!!}">
                            <label>
                              Start Date
                              {!!Form::input('date', 'start-date-modal', date_format($startDate, 'Y-m-d'), ['class' => 'form-control'])!!}
                            </label>
                            <label>
                              End Date
                              {!!Form::input('date', 'end-date-modal', date_format($endDate, 'Y-m-d'), ['class' => 'form-control'])!!}
                            </label>
                          </div>
                          <div class="modal-footer">
                            <button type="button" id="close{!!$functionList[$i]!!}" class="pull-left btn btn-default btn-sm" data-dismiss="modal">Close</button>
                            <button type="button" id="submit{!!$functionList[$i]!!}" class="btn btn-primary btn-sm" onclick="PerChartFilter('Custom', '{!!$functionList[$i]!!}', '{!!$i!!}')">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-content">
                      <div id = "{!!str_replace(' ', '', $functionList[$i]).'chart-outer'!!}" class="chart_outer row">
                          <!-- chart -->
                          <div id="{!!str_replace(' ', '', $functionList[$i]).'chart'!!}" class="chart-container">
                          </div>
                          <!-- legend -->
                          <div id = "{!!str_replace(' ', '', $functionList[$i]).'legend'!!}" class="legendContainer">
                          </div>
                          <!-- Hide expand button in android and IOS app -->
                          @if(strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) <= 0 && strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/1.0/Android")) <= 0)
                            <!-- top-right expand button -->
                            <i class="expand-button chart-buttons fa fa-expand fa-lg" aria-hidden="true" data-chart="{!!str_replace(' ', '', $functionList[$i]).'wrapper'!!}" data-id="{!!$i!!}" ></i>
                          @endif
                          <!-- top-right legend button -->
                          <i  class="legend-button chart-buttons fa fa-caret-square-o-down fa-lg" aria-hidden="true"></i>
                          <!-- top-right legend button -->
                          <!-- <i class="Re-chart chart-buttons fa fa-caret-square-o-down fa-lg" data-id="{!!$i!!}" data-func="{!!$functionList[$i]!!}" aria-hidden="true"></i> -->
                      </div>
                      <!-- zone selection -->
                      <div id="{!!str_replace(' ', '', $functionList[$i]).'zone-toggles'!!}" style="text-align: center; margin-top: 5px;">
                        <div class="reports_tour" data-toggle="buttons">
                          <div class="btn-group">
                            <button type="button" class="btn btn-primary" onclick="zoneButtons(0, '{!!$functionList[$i]!!}', {!!$i!!}, this)" >All Devices</button>
                            <?php
                              $unique_zones = array();
                            ?>
                              @foreach($InitData[$functionList[$i]]['zone_name'] as $index => $zonename)
                                @if ( !in_array($zonename, $unique_zones) )
                                <?php
                                  $unique_zones[] = $zonename;
                                ?>
                                  <button type="button" class="btn btn-primary" onclick='zoneButtons({!!$InitData[$functionList[$i]]["zone_id"][$index]!!}, "{!!$functionList[$i]!!}", {!!$i!!}, this)'>{!!$zonename!!}</button>
                                @endif
                              @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          @endfor
          <!-- END REPORTS CONTAINER -->
          <br>
        </div>
        <!-- only display when none of the charts have nay data in it.  -->
        <div id="missingcharts" style="display: none; text-align: center; color: #8f8f8f; vertical-align: middle;" class="well">
          <i class="fa fa-area-chart" aria-hidden="true" style="font-size: 4em;"></i>
          <h2>
            Select a chart by clicking "Add Chart" button.
          </h2>
        </div>
      </div>
    </div>
  </main>
<!-- ================================================== LOCAL CUSTOM SCRIPTS============================================================== -->
  <script>
    var system_id     = {!!$thisSystem->id!!};
    var functionList  = {!!json_encode($functionList)!!};
    var InitData      = {!!json_encode($InitData)!!};
    var SelectOptionTime = "{!!Session::get("SelectOptionTime")!!}";
    var startDate     = "{!!Session::get("startDate")!!}";
    var endDate       = "{!!Session::get("endDate")!!}";
    var fetchDate     = '{!!date_format($startDate, 'n/d/Y')!!}';
    var fetchLimit    = '{!!date_format($endDate, 'n/d/Y')!!}';
    var routePrefix   = '{!!$routePrefix!!}';
    //append loading/"No data" div container
    for (var i = 0; i < functionList.length; i++) {
      var loadingDiv = '<div class="'+'loading loading'+functionList[i].replace(/\s+/g, '').toLowerCase()+'"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div><span class="loading-message"></span></div>';
      var NoDataDiv = '<div class="nodata nodata'+functionList[i].replace(/\s+/g, '').toLowerCase()+'" style="display: none;"><span class="nodata-message">No data to display.</span></div>';
      $('.chart_outer').eq(i).prepend(loadingDiv);
      $('.chart_outer').eq(i).prepend(NoDataDiv);
    }
    $(document).ready(function(){
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="reports_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Reports\
        </a>'
      );
    });
  </script>
  <!-- Load d3v3.5.js and c3.js -->
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_scripts = ['/js/c3-0.4.14/c3.min.js', '/js/d3-3.5.17/d3.min.js', '/js/c3Chart.js'];
    foreach ($import_scripts as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::script($appendDate);
      }

    }
  ?>
@stop