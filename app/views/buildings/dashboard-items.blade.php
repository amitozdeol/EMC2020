<!--System Page-->
<?php $dashboard_page='Fullwidth';
      $title="Dashboard"; ?>
@extends('layouts.wrapper')
@section('content')
  <?
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_css = ['/css/bootstrap-dashboard.css','/css/responsive_table.css'];    //add file name
    foreach ($import_css as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::style($appendDate);
      }

    }
  ?>
    @include('buildings.DashboardSidebar')
    <!-- this container loads the sidebar pages.
          If there's no page id in the url, load dashboard with all the charts
          Different page id is associated with sidebar items, that loads different pages-->
    <main id="page-content-wrapper" role="main" style="overflow: hidden; margin: 30px 10px;     position: relative;">
      @if(in_array(Auth::user()->auth_role,[7,8]))
        <!-- Only show system log for admins and syadmins -->
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#dashboardtab">Dashboard</a></li>
          <li><a data-toggle="tab" href="#systemlogtab">System Log</a></li>
        </ul>
      @endif
      <div class="tab-content">
        <!-- DASHBOARD TAB -->
        <div id="dashboardtab" class="tab-pane fade in active" style="margin-top: 10px;">
          @if(isset($InitData['Water']))
            <!-- WATER -->
            <div class="widgets col-xs-12 col-sm-6 col-lg-4">
                <div class="panel panel-default" style="position: relative;">
                    <div class="panel-body" style="position: relative;">
                      <div class="col-xs-6">
                        <div class="row">
                          <svg id="Waterfillgauge" width="100%" height="100"></svg>
                        </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="row" style="text-align: center;">
                            <h4>Water reading</h4>
                            <label id = "water-loadingmessage"></label><br>
                            <div id="water-info">
                              @if(isset($InitData['Water']['data']))
                                <label><b>Date: </b><span id="waterDate" class="text-muted">{{date("F jS, Y", strtotime($InitData['Water']['data']['datetime']))}}</span></label>
                                <label><b>Alarm: </b><span id="waterAlarm" class="text-muted">{{$InitData['Water']['data']['description']}}</span></label>
                              @else
                                <label><span class="text-muted">No data found</span></label>
                              @endif
                            </div>
                        </div>
                      </div>
                    </div>
                    <div title="Refresh data" class="widget-refresh">
                      <i id="waterwidgetrefresh" class="fa fa-refresh" aria-hidden="true" onclick="waterAjax(this)"></i>
                    </div>
                </div>
            </div>
          @endif
            <!-- WEATHER -->
            <div class="widgets col-xs-12 col-sm-6 {{ isset($InitData['Water']) ? 'col-lg-4' : 'col-lg-6' }}">
                <div class="panel weathercard-depth">
                    <div class="panel-body weather-card" style="position: relative; padding: 0;">
                      <div class="front-weather">
                        <h4>Outside Temperature</h4>
                        <label class="col-xs-6"  id="weather-loadingmessage"></label>
                        <div class="col-xs-6 weather-info" style="text-align: center;">
                          <div class="row">
                            <div class="col-xs-12">
                              <label id = "weather-description"></label><br>
                              <span id = "weather-current" class="text-muted"></span><br>
                              <span id = "weather-low-high" class="text-muted"></span>
                            </div>
                          </div>
                        </div>
                        <div class="weather-container col-xs-6">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                              <div class="icon weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <div title="Refresh data" class="widget-refresh" style="display: table;">
                          <a class="text-muted" href="https://openweathermap.org/" style="font-size: 10px; display: table-cell; vertical-align: middle;">OpenWeatherMap.org&nbsp;</a>
                          <i id="weatherwidgetrefresh" class="fa fa-refresh widgetrefresh" aria-hidden="true" onclick="weatherAPI(this, false)"></i>
                        </div>
                        <button class="btn btn-info weather_banner_button" style="position: absolute; right: 0; top: 0;">Forecast</button>
                      </div>
                      <div class="back-weather hidden">
                        <h4>Forecast</h4>
                        <div class="weather-container col-xs-4 col-sm-4 col-lg-4">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                              <h5 class="dayofweek"></h5>
                              <div class="forecast-descr"></div>
                              <div class="forecast-highlow"></div><br>
                              <div class="weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <div class="weather-container col-xs-4 col-sm-4 col-lg-4">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                            <h5 class="dayofweek"></h5>
                            <div class="forecast-descr"></div>
                            <div class="forecast-highlow"></div><br>
                              <div class="weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <div class="weather-container col-xs-4 col-sm-4 col-lg-4">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                            <h5 class="dayofweek"></h5>
                            <div class="forecast-descr"></div>
                            <div class="forecast-highlow"></div><br>
                              <div class="weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <div class="weather-container col-xs-4 col-sm-4 col-lg-4">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                            <h5 class="dayofweek"></h5>
                            <div class="forecast-descr"></div>
                            <div class="forecast-highlow"></div><br>
                              <div class="weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <div class="weather-container col-xs-4 col-sm-4 col-lg-4">
                          <div class="row">
                            <div class="vert-align" style="text-align: center;">
                            <h5 class="dayofweek"></h5>
                            <div class="forecast-descr"></div>
                            <div class="forecast-highlow"></div><br>
                              <div class="weather-icon-container"></div>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-info weather_banner_button" style="position: absolute; right: 0; top: 0;">Today</button>
                      </div>
                    </div>
                </div>
            </div>
            <!-- ACTIVE ALARMS -->
            <div class="widgets col-xs-12 col-sm-6  {{ isset($InitData['Water']) ? 'col-lg-4' : 'col-lg-6' }}">
                <div class="panel panel-success">
                    <div class="panel-body" style="padding: 0; text-align: center; min-height: 150px;">
                      <h4>Active Alarm</h4>
                      <table class="alarm-table table table-fixed table-condensed" style="font-size: 11px;">
                        <thead class="alarm-table-head">
                          <tr>
                            <th>Device</th>
                            <th>Description</th>
                            <th>Start Time</th>
                          </tr>
                        </thead>
                          @if(count($alarms) == 0)
                            <tbody class="alarm-table-body">
                              <tr class="list-group-item-success" style="overflow: hidden; font-size: 12px;">
                                <td>No Active Alarms</td>
                              </tr>
                          @else
                            <tbody class="alarm-table-body alarm-scrollable">
                              @foreach($alarms as $alarm)
                                <tr class="{{$alarm->alarm_state == 1 ? 'list-group-item-warning' : ($alarm->alarm_state == 2 ? 'list-group-item-danger' : '')}}" style="overflow: hidden;">
                                  <td><b>{{$alarm->name}}</b></td>
                                  <td>{{$alarm->description}}</td>
                                  <td>{{$alarm->created_at}}</td>
                                </tr>
                              @endforeach
                          @endif
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
          <br>
          <?
            if(isset($InitData['Relay'])){
              //$charttypes = ["pieChart", "RuntimeBar", "SynchronousChart"];
              $charttypes = ["RuntimeBar", "SynchronousChart"];
            }
            else{
              $charttypes = ["SynchronousChart"];
            }
          ?>
          <!--=============== Chart Containers ====================-->
          <div class="col-xs-12">
            <div class="row">
              @foreach($charttypes as $dbchart) <!-- RuntimeBar or SynchronousChart -->
                <!-- charts container -->
                  <!-- temporary remove pie chart -->
                  @if(false)
                    <!-- Relay pie chart -->
                    <div class="col-sm-6">
                      <div id="pieChartRelay" class="chartRelay well" style="margin: 5px;">
                        <div class="loading loadingRelay">
                          <div class="spinner">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                          </div>
                          <span class="loading-message"></span>
                        </div>
                        <div class="nodata nodataRelay" style="display: none;"><span class="nodata-message">No data to display.</span></div>
                        <div class="legendContainer" id="legend-container-PiRelay"></div>
                        <i class="d3-legend-button fa fa-object-group fa-lg" aria-hidden="true"></i>
                      </div>
                    </div>
                  @endif
                <div id="{{$dbchart}}" class="ChartContainers panel panel-primary" style="width: 100%">
                  <div class="panel-heading clearfix" style="color: black;">
                    <!--=================  Time filter for each chart ================--><!-- dropdown for time-range -->
                    <div class="input-group" style="width: 100%">
                      <div class="tabtodropdown" style="display: inline-block;">
                        <select class="d3-form-control {{$dbchart}}-toolbar-tour" onchange="TimeSelect(this, '{{$dbchart}}')">
                            <option value="{{$dbchart == 'SynchronousChart' ? 'Today' : 'Last Week' }}">{{$dbchart == 'SynchronousChart' ? 'Today' : 'Last Week' }}</option>
                            <option value="{{$dbchart == 'SynchronousChart' ? 'Last Week' : 'Last two Weeks' }}">{{$dbchart == 'SynchronousChart' ? 'Last Week' : 'Last 2 Weeks' }}</option>
                            <option value="Last Month" >Last Month</option>
                            <option value="Last Year">Last Year</option>
                            <option value="Custom"  data-target="#CustomDateModal{{$dbchart}}">Custom</option>
                            <option value="Custom1" disabled style="display:none;">Custom</option>
                        </select>
                      </div>
                      <!-- Custom date Modal -->
                      <div id="CustomDateModal{{$dbchart}}" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Select Custom Date for Runtime</h4>
                            </div>
                            <div class="modal-body" id="modal-body-{{$dbchart}}">
                              <label>
                                Start Date
                                {{Form::input('date', 'start-date-modal'.$dbchart , date_format($startDate, 'Y-m-d'), ['class' => 'form-control'])}}
                              </label>
                              <label>
                                End Date
                                {{Form::input('date', 'end-date-modal'.$dbchart , date_format($endDate, 'Y-m-d'), ['class' => 'form-control'])}}
                              </label>
                            </div>
                            <div class="modal-footer">
                              <button type="button" id="close{{$dbchart}}" class="pull-left btn btn-default btn-sm" data-dismiss="modal">Close</button>
                              <button type="button" id="submit{{$dbchart}}" class="btn btn-primary btn-sm" onclick="PerChartFilter('Custom', '{{$dbchart}}')">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Zoom out, Zone filter and expand button -->
                      <div class="input-group-btn pull-right" style="width: auto;">
                        <div class="btn-group">
                          @if($dbchart == 'SynchronousChart')
                            <div type="button" class="btn btn-default disabled {{$dbchart}}-toolbar-tour" id="zoom-out-d3">
                              Zoom Out
                            </div>
                            <div type="button" class="btn btn-primary d3-btn-select {{$dbchart}}-toolbar-tour">
                                <input type="hidden" class="btn-select-input" id="" name="" value="" />
                                <span class="btn-select-value" style="float: left;">Select a zone</span>
                                <div class="btn-select-arrow" style="float: right;"><span class='glyphicon glyphicon-chevron-down'></span></div>
                                <ul>
                                  <li value="-1">All zones</li>
                                  @foreach($InitData['Temperature']['zone_temp'] as $zone)
                                    @for ($i = 0; $i < count($zone['zonenames']); $i++)
                                      <li value="{{$zone['zones'][$i]}}">{{$zone['zonenames'][$i]}}</li>
                                    @endfor
                                  @endforeach
                                </ul>
                            </div>
                          @endif
                          <!-- Hide expand button in android and IOS app -->
                          @if(strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) <= 0 && strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/1.0/Android")) <= 0)
                            <!-- top-right expand button -->
                            <div type="button" class="btn btn-default expand-button {{$dbchart}}-toolbar-tour" data-chart="{{$dbchart}}" style="border-color: #2e6da4;">
                              <i class="fa fa-expand" aria-hidden="true" style="line-height: inherit;"></i>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="hostOverview">
                    <div id="charts-wrapper">
                      @if($dbchart == "SynchronousChart")
                        <div class="panel panel-default Synchronous-chart-tour"style="margin-bottom: 0px; text-align: center;">
                          <label class="d3-chart-title">Temperature</label>
                          <?php
                            // Only show help tour for one chart type
                            $i = 0;   
                            $len = count($InitData['Temperature']['zone_temp']);
                          ?>
                          @foreach($InitData['Temperature']['zone_temp'] as $zone)
                            <!-- temperature chart -->
                            <div class="chart-wrapper">
                              <div class="d3-chart-setting">
                                <i class="d3-legend-button fa fa-filter fa-lg btn btn-sm btn-primary @if($i == $len - 1)Synchronous-chart-tour @endif" data-cname="{{$zone['temp_range']}}" aria-hidden="true">&nbsp;Filter</i>
                                <div class="top-loaders-container top-loader-{{$zone['temp_range']}}"> <!-- Loading circle - Stay visible until all the zones are loaded -->
                                  <div class="load-container">
                                    <div class="circle"></div>
                                  </div>
                                </div>
                              </div>
                              <div id="tempchart{{$zone['temp_range']}}"  class="tempchart" data-charttype="{{$dbchart}}">
                                  <div class="loading loadingSynchronous{{$zone['temp_range']}}">
                                    <div class="spinner">
                                      <div class="rect1"></div>
                                      <div class="rect2"></div>
                                      <div class="rect3"></div>
                                      <div class="rect4"></div>
                                      <div class="rect5"></div>
                                    </div>
                                    <span class="loading-message"></span>
                                  </div>
                                  <div class="d3-center nodata{{$zone['temp_range']}}" style="display: none;"><span class="nodata-message">No data to display.</span></div>
                                  <svg version="1.1" class="svg-content @if($i == $len - 1){{$dbchart}}-chart-tour @endif"></svg>
                                  <div class="legendContainer-d3" id="legend-container-{{$zone['temp_range']}}"></div>
                                  <!-- Missing Device Modal -->
                                  <div id="myModal-{{$zone['temp_range']}}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Missing Report</h4>
                                        </div>
                                        <div class="modal-body">
                                          <p><b class="modal-body-title"></b></p>
                                          <p>There's no data reported for this device in selected time frame.</p>
                                          <p class="modal-body-values"></p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <? $i++; ?>
                          @endforeach
                        </div>
                        @if(isset($InitData['Relay']))
                          <div class="panel panel-default Synchronous-chart-tour"  style="margin-bottom: 0;">
                            <!-- Relay chart -->
                            <div class="chart-wrapper">
                              <!-- <i class="d3-legend-button fa fa-filter fa-lg btn btn-sm btn-primary"  data-cname="Relay" aria-hidden="true">&nbsp;Filter</i> -->
                              <div id="chartRelay" data-charttype="{{$dbchart}}">
                                <div class="loading loadingRelay">
                                  <div class="spinner">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                  </div>
                                  <span class="loading-message"></span>
                                </div>
                                <div class="d3-center nodataRelay" style="display: none;"><span class="nodata-message">No data to display.</span></div>
                                <svg version="1.1" class="svg-content"></svg>
                                <div class="legendContainer-RD" id="legend-container-Relay"></div>
                                <!-- Missing Device Modal -->
                                <div id="myModal-Relay" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Missing Report</h4>
                                        </div>
                                        <div class="modal-body">
                                          <p><b class="modal-body-title"></b></p>
                                          <p>There's no data reported for this device in selected time frame.</p>
                                          <p class="modal-body-values"></p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      <!-- BAR CHART -->
                      @else
                        <div class="chart-wrapper panel panel-default " style="margin-bottom: 0;">
                          <label class="d3-chart-title">Runtime chart</label>
                          <i class="d3-legend-button fa fa-filter fa-lg btn btn-sm btn-primary"  data-cname="RuntimeRelay" aria-hidden="true">&nbsp;Filter</i>
                          <div id="BarChartRelay" class="RuntimeBarChart" data-charttype="{{$dbchart}}">
                            <div class="loading loadingRelayBar">
                              <div class="spinner">
                                <div class="rect1"></div>
                                <div class="rect2"></div>
                                <div class="rect3"></div>
                                <div class="rect4"></div>
                                <div class="rect5"></div>
                              </div>
                              <span class="loading-message"></span>
                            </div>
                            <div class="d3-center nodataRelayBar" style="display: none;"><span class="nodata-message">No data to display.</span></div>
                            <svg version="1.1" class="svg-content d3-barchart-tour"></svg>
                            <div class="d3-barchart-tour left-transparent"></div>   <!-- left y axis tour -->
                            <div class="d3-barchart-tour right-transparent"></div>  <!-- right y axis tour -->
                            <div class="legendContainer-d3" id="legend-container-RuntimeRelay"></div>
                            <!-- Missing Device Modal -->
                            <div id="myModal-RelayFunction" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Missing Report</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p><b class="modal-body-title"></b></p>
                                    <p>There's no data reported for this device in selected time frame.</p>
                                    <p class="modal-body-values"></p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        @if(in_array(Auth::user()->auth_role,[7,8]))
          <!-- SYSTEM LOG TAB -->
          <div id="systemlogtab" class="tab-pane fade" style="overflow: hidden; background: white;">
            <div class="col-xs-12">
              <h3>System log</h3>
              <div class="col-xs-12" style="margin-bottom: 15px;">
                <div class="form-group col-xs-12">
                  <label for="log_type">Select a log type:</label>
                  <select id="systemlog_type" class="form-control">
                    <option value="0">All</option>
                    @foreach($log_types as $log)
                      <option value="{{$log->id}}">{{$log->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-xs-6">
                  <label for="systemlog_sd">Start Date:</label>
                  {{Form::input('date', 'systemlog_sd', date_format($startDate, 'Y-m-d'), ['class' => 'form-control', 'id'=>'systemlog_sd'])}}
                </div>
                <div class="form-group col-xs-6">
                  <label for="systemlog_ed">End Date:</label>
                  {{Form::input('date', 'systemlog_ed', date_format($endDate, 'Y-m-d'), ['class' => 'form-control', 'id'=>'systemlog_ed'])}}
                </div>
                <button type="submit" class="btn btn-default" onclick="systemlogAJAX(1)">Submit</button>
              </div>
              <div class="col-xs-12">
                <div class="loading systemlog_loading hidden">
                  <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                  </div>
                  <span class="loading-message"></span>
                </div>
                <!-- Table for displaying current log values -->
                <table class="responsive-table table table-hover">
                  <thead>
                    <tr class="theadrow"style="font-size: 13px;">
                        <th scope="col" style="vertical-align:top">Application Name</th>
                        <th scope="col" style="vertical-align:top">Report</th>
                        <th scope="col" style="vertical-align:top">Date</th>
                    </tr>
                  </thead>
                  <tbody id="systemlog_tbody">
                    <tr class="hidden">
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif
      </div>
    </main>
  <!-- =============================================== LOCAL CUSTOM SCRIPTS========================================================== -->
  <script type="text/javascript">
    var InitData = {{json_encode($InitData)}};
    var system_id = {{$thisSystem->id}};
    var InitstartDate = '{{date_format($startDate, 'Y-m-d')}}';
    var InitendDate = '{{date_format($endDate, 'Y-m-d')}}';
    var alarmData = {{json_encode($alarms)}};
    $(document).ready(function() {
      //Help tour
      $('.pmd-floating-action').prepend('\
          <a href="javascript:void(0);" class="SynchronousChart-toolbar-tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
            Toolbar\
          </a>\
          <a href="javascript:void(0);" class="Synchronous-chart-tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
            Chart\
          </a>'
        );
      if (InitData.hasOwnProperty('Relay')) {
        $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="d3-barchart-tour pmd-floating-action-btn btn btn-sm btn-default   tour-floating-options">\
            Bar Chart\
          </a>');
      }

      // Flipping animation in weather widget
      $('.weather_banner_button').click(function(){
        $(".weather-card").toggleClass('flipped');
        $(".front-weather, .back-weather").toggleClass("hidden");
      });
      weatherAPI('#weatherwidgetrefresh', cache = true);
    });
    // =================== Weather ===================
    // Animate weather refresh icon
    $('.widgetrefresh').addClass('fa-spin');
    $( "#weather-loadingmessage" ).html("LOOKING OUTSIDE FOR YOU... ONE SEC");
    $(".weather-info").hide();
    function weatherAPI(elem, checkCache){
      $(elem).addClass('fa-spin');
      $( "#weather-loadingmessage" ).show().html("LOOKING OUTSIDE FOR YOU... ONE SEC");
      $(".weather-info").hide();
      //Call the weather API. 
      var request = $.ajax({
        url: system_id + '/weather',
        method: "POST",
        data : {
          cache : checkCache   // If checkCache is false, cache will not be used. New data will get generated
        },
        success: function (data, textStatus, jqXHR) {
          $(elem).removeClass('fa-spin');
          if (data.current.weather_current == null && (data.current.error == 404 || data.current.error == 401)) {
            $("#weather-loadingmessage").html("No Data found");
          }else{
            $("#weather-loadingmessage").hide();
            $(".weather-info").show();
            $("#weather-description").html(data.current.weather_condition);
            $("#weather-current").html("<b>Current: </b>"+data.current.weather_current+"°F");
            $("#weather-low-high").html("<b>Low: </b>"+data.current.weather_temp_min + "°F | " + "<b>High: </b>"+data.current.weather_temp_max+"°F");
  
            $(".front-weather .weather-icon-container").removeClass().addClass(data.current.element.css_class+" weather-icon-container");
            $(".front-weather .weather-icon-container").html(data.current.element.html);
            
            data.forecast.forEach(function(day, i){
              $(".back-weather .dayofweek:eq("+i+")").html(day.dayofweek);
              $(".back-weather .forecast-descr:eq("+i+")").html(day.weather_condition);
              $(".back-weather .forecast-highlow:eq("+i+")").html("<b>High: </b>"+day.avg_temp_max+"°F | <b>Low: </b>"+day.avg_temp_min+"°F");
              $(".back-weather .weather-icon-container:eq("+i+")").html(day.element.svg);
            });
          }
        },error: function (xhr, ajaxOptions, thrownError) {
          $("#weather-loadingmessage").html("No Data found");
        }
      });
    }
    // ==================== System Log tab ====================
    @if(in_array(Auth::user()->auth_role,[7,8]))
      function systemlogAJAX(page){
        var log_type = $("#systemlog_type").val();
        var start_date = $("#systemlog_sd").val();
        var end_date = $("#systemlog_ed").val();

        $('.systemlog_loading').removeClass('hidden').show();
        var request = $.ajax({
          url: system_id + '/systemlog?page='+page,
          method: "POST",
          dataType: 'json',
          data : {
            log_types : log_type,
            s_date : start_date,
            e_date : end_date
          },
          success: function (response, textStatus, jqXHR) {
            $('.systemlog_loading').hide();   // Hide loading icon
            $('#systemlog_tbody').contents(':not("#systemlog_tbody tr:first-child")').remove(); // remove all row from tbody except the first one.
            $("#systemlogtab .pagination").remove();  // Remove pagination
            if (response.data.data.length > 0) {
              // Add new rows in the table body
              response.data.data.forEach(function(element){
                $table_row = $('#systemlog_tbody tr:first-child').clone().removeClass('hidden');
                $table_row.append("<th scope='row'>"+element.application_name+"</th>");
                $table_row.append("<td data-title='Report'>"+element.report+"</td>");
                $table_row.append("<td data-title='Date'>"+element.datetime+"</td>");

                $('#systemlog_tbody').append($table_row);
              });
              $("#systemlogtab").append(response.links);  // Add pagination
            }else{
              $table_row = $('#systemlog_tbody tr:first-child').clone().removeClass('hidden');
              $table_row.append("<th scope='row'></th>");
              $table_row.append("<td data-title='Report'>No data found</td>");
              $table_row.append("<td data-title='Date'></td>");

              $('#systemlog_tbody').append($table_row);
            }
          }
        });
      }

      // When pagination button clicked
      $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        systemlogAJAX(page);
      });
    @endif
  </script>

  <? 
    //Cache control
    //Add last modified date of a file to the URL, as get parameter.
    $import_scripts = ['/js/bootstrap-tabcollapse.js', '/js/d3v4/d3.min.js', '/js/vendor/d3Chart.js'];
    foreach ($import_scripts as $value) {
      $filename = public_path().$value;
      if (file_exists($filename)) {
          $appendDate = substr($value."?v=".filemtime($filename), 1);
          echo HTML::script($appendDate);
      }

    }
  ?>


@stop
