<?php $title="Data Export"; ?>

@extends('layouts.wrapper')

@section('content')
  {{HTML::style('https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/b-print-1.5.1/r-2.2.1/datatables.min.css')}}
  {{HTML::style('css/ion.rangeSlider.css')}}
  {{HTML::style('css/ion.rangeSlider.skinHTML5.css')}}
  <style>
      .dataTables_wrapper .dataTables_processing{
        background-color: #333;
        height: 60px;
        padding-bottom: 20px;
        z-index: 2;
      }
      .redClass{
        background: #d33434 !important;
      }
      tfoot th{
        background-color: #123E5D;
        border: 1px solid white;
        color: white;
        text-align: center;
      }
      /* Desktop */
      @media screen and (max-width:640px){
        .dataTables_wrapper .dataTables_length{ margin-top:5px; position: relative; float: right; }
        div.dt-buttons{ float: left !important; position: relative; }
      }
      /* Mobile */
      @media screen and (min-width:640px){
        .dataTables_wrapper .dataTables_length{ margin-top:5px; position: absolute; right: 0; }
        div.dt-buttons{ float: left; position: absolute; }
      }
  </style>
  <?php
    $help_id='dataexport';
  ?>
  <br>
  <div class="js-validate-export col-xs-12 border_blue_white" style="padding: 10px 10px 60px 10px;">
    <ul id="myTab" class="myTab nav nav-tabs">
      <li class="active"><a href="#devicedata" data-toggle="tab">Device Data</a></li>
      <li><a href="#deviceevents" data-toggle="tab">Device Events</a></li>
    </ul>
    <div id="myTabContent" class="myTabContent tab-content row">
        <div class="tab-pane fade in active" id="devicedata">
            <label class="transparent_blue_white" style="width:100%">Device Data</label>
            <div  style="overflow: hidden;">
              <div class="export_data_tour col-xs-12 col-sm-6 " style="padding: 15px">
                {{Form::label('device', 'Devices')}}
                <select class="form-control input-lg" name="device_id" id="device_id" required>
                  <option value="">Select a Device</option>
                  <option value="all" name="All Devices">All Devices - By Data Type</option>
                  @foreach($zones as $zone)
                    <optgroup label="{{$zone->zonename}}">
                      @foreach($devices as $device)
                        @if($zone->zone === $device->zone)
                            @if($device->name != NULL && $device->name != ' ')
                              <option value="{{$device->id}}" name="{{$device->name}}">
                              {{$device->name}}
                            @else
                              <option value="{{$device->id}}" name="{{'Device-'.$device->id}}">
                              {{'Device-'.$device->id}}
                            @endif
                        </option>
                        @endif
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
              </div>
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('function', 'Data Type')}}
                <select class="form-control input-lg" name="function" id="function" required>
                  <option value="">Select a Function Type</option>
                  @foreach($functions as $function)
                    <option value="{{$function->function}}">{{$function->function}}</option>
                  @endforeach
                </select>
              </div>
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('startdate', 'Start Date')}}
                {{Form::input('date', 'startdate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
              </div>
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('enddate', 'End Date')}}
                {{Form::input('date', 'enddate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
                <div id= "Error" class="alert alert-danger alert-dismissable" style="position: absolute; z-index: 2; font-size: 15px; text-shadow: none; display:none;">
                  <strong>Error!</strong> End Date should always be chronologically after the Start Date
                </div>
              </div>
              <br>
              <div class="col-xs-12" style="padding: 15px">
                {{Form::button('Load Data', ['id'=> 'submitbutton', 'class'=>'export_data_tour btn btn-primary btn-lg col-xs-8 col-xs-offset-2', "style"=>"background-color: #428bca"])}}
              </div>
            </div>
            <!--************************** Tables **************************-->
            <div class="col-xs-12" style="margin: 30px 0; background-color: rgba(0,0,40,0.2); overflow: hidden; padding: 10px;">
                <!-- Device Data - Without name column -->
                <table class="display dataTable responsive table-without-name" cellspacing="0" role="grid" style="table-layout: auto; width: 100%; display: none;">
                  <caption class="device_data_table-title" style="text-align: center; color: white;"></caption>
                    <thead>
                        <tr class="row-header-table">
                            <th>Date</th>
                            <th>Time</th>
                            <th class="col-device-type">Type</th>
                            <th>Setpoint</th>
                        </tr>
                    </thead>
                    <thead class="hidden-xs">
                        <tr class="row-search-table">
                            <td><input type="text" data-column="1" class="search-input-text device_data_search-input-field"></td>  <!-- date -->
                            <td><input type="text" data-column="2" class="search-input-text device_data_search-input-field"></td>  <!-- time -->
                            <td><input type="text" data-column="3" class="value-slider"/></td>  <!-- values -->
                            <td><input type="text" data-column="4" class="setpoint-slider"/></td>  <!-- Setpoint -->
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="row-header-table">
                            <th>Date</th>
                            <th>Time</th>
                            <th class="col-device-type">Type</th>
                            <th>Setpoint</th>
                        </tr>
                    </tfoot>
                </table>
                <!-- Device Data - With name column -->
                <table class="display dataTable responsive table-with-name" cellspacing="0" role="grid" style="table-layout: auto; width: 100%">
                  <caption class="device_data_table-title" style="text-align: center; color: white;"></caption>
                    <thead>
                        <tr class="row-header-table">
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="col-device-type">Type</th>
                            <th>Setpoint</th>
                        </tr>
                    </thead>
                    <thead class="hidden-xs">
                        <tr class="row-search-table">
                            <td><input type='text' data-column='0' class='device_data_search-input-field'></td>  <!-- name -->
                            <td><input type="text" data-column="1" class="device_data_search-input-field"/></td>  <!-- date -->
                            <td><input type="text" data-column="2" class="device_data_search-input-field"/></td>  <!-- time -->
                            <td><input type="text" data-column="3" class="value-slider"/></td>  <!-- values -->
                            <td><input type="text" data-column="4" class="setpoint-slider"/></td>  <!-- Setpoint -->
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="row-header-table">
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="col-device-type">Type</th>
                            <th>Setpoint</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- Events -->
        <div class="tab-pane fade" id="deviceevents">
            <label class="transparent_blue_white" style="width:100%">Device Events</label>
            <div style="overflow: hidden;">
              <!-- Device names dropdown -->
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('device', 'Devices')}}
                <select class="form-control input-lg" name="device_id" id="event_device_id" required>
                  <option value="">Select a Device</option>
                  <option value="all" name="All Devices">All Devices - By Data Type</option>
                  @foreach($zones as $zone)
                    <optgroup label="{{$zone->zonename}}">
                      @foreach($events_devices as $device)
                        @if($zone->zone === $device->zone)
                            @if($device->name != NULL && $device->name != ' ')
                              <option value="{{$device->id}}" name="{{$device->name}}">
                              {{$device->name}}
                            @else
                              <option value="{{$device->id}}" name="{{'Device-'.$device->id}}">
                              {{'Device-'.$device->id}}
                            @endif
                        </option>
                        @endif
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
              </div>
              <!-- Start Date -->
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('event_startdate', 'Start Date')}}
                {{Form::input('date', 'event_startdate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
              </div>
              <!-- End Date -->
              <div class="export_data_tour col-xs-12 col-sm-6" style="padding: 15px">
                {{Form::label('event_enddate', 'End Date')}}
                {{Form::input('date', 'event_enddate', date('Y-m-d', strtotime('yesterday')), ['class'=>'form-control input-lg'])}}
                <div id= "event_Error" class="alert alert-danger alert-dismissable" style="position: absolute; z-index: 2; font-size: 15px; text-shadow: none; display:none;">
                  <strong>Error!</strong> End Date should always be chronologically after the Start Date
                </div>
              </div>
              <br>
              <!-- Submit Button -->
              <div class="col-xs-12" style="padding: 15px">
                {{Form::button('Load Data', ['id'=> 'submit_event_button', 'class'=>'export_data_tour btn btn-primary btn-lg col-xs-8 col-xs-offset-2', "style"=>"background-color: #428bca"])}}
              </div>
            </div>
            <!-- Events -->
            <div class="col-xs-12" style="margin: 30px 0; background-color: rgba(0,0,40,0.2); overflow: hidden; padding: 10px;">
              <table class="display dataTable responsive Etable-with-name" cellspacing="0" role="grid" style="table-layout: auto; width: 100%;">
                <caption class="event_table-title" style="text-align: center; color: white;"></caption>
                  <thead>
                      <tr class="row-header-table">
                          <th>Name</th>
                          <th>Created Date</th>
                          <th>Ending Date</th>
                          <th>Duration</th>
                          <th>Description</th>
                      </tr>
                  </thead>
                  <thead class="hidden-xs">
                      <tr class="row-search-table">
                          <td><input type='text' data-column='0' class='event_search-input-field'></td>   <!-- name -->
                          <td><input type="text" data-column="1" class="event_search-input-field"/></td>  <!-- created date -->
                          <td><input type="text" data-column="2" class="event_search-input-field"/></td>  <!-- ending date -->
                          <td><input type="text" data-column="3" class="duration-slider"/></td>        <!-- duration -->
                          <td><select data-column="4" class="description-list"><option value="">All</option></select></td>     <!-- Description -->
                      </tr>
                  </thead>
                  <tfoot>
                      <tr class="row-header-table">
                          <th>Name</th>
                          <th>Created Date</th>
                          <th>Ending Date</th>
                          <th>Duration</th>
                          <th>Description</th>
                      </tr>
                  </tfoot>
              </table>
              <table class="display dataTable responsive Etable-without-name" cellspacing="0" role="grid" style="table-layout: auto; width: 100%; display:none;">
                <caption class="event_table-title" style="text-align: center; color: white;"></caption>
                  <thead>
                      <tr class="row-header-table">
                          <th>Created Date</th>
                          <th>Ending Date</th>
                          <th>Duration</th>
                          <th>Description</th>
                      </tr>
                  </thead>
                  <thead class="hidden-xs">
                      <tr class="row-search-table">
                          <td><input type="text" data-column="1" class="event_search-input-field"/></td>  <!-- created date -->
                          <td><input type="text" data-column="2" class="event_search-input-field"/></td>  <!-- ending date -->
                          <td><input type="text" data-column="3" class="duration-slider"/></td>        <!-- duration -->
                          <td><select data-column="4" class="description-list"><option value="">All</option></select></td>     <!-- Description -->
                      </tr>
                  </thead>
                  <tfoot>
                      <tr class="row-header-table">
                          <th>Created Date</th>
                          <th>Ending Date</th>
                          <th>Duration</th>
                          <th>Description</th>
                      </tr>
                  </tfoot>
              </table>
            </div>
        </div>
    </div>  
  </div>
  {{HTML::script('js/vendor/ion.rangeSlider.js')}}
  {{HTML::script('js/vendor/moment.min.js')}}
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/b-print-1.5.1/r-2.2.1/datatables.min.js"></script>
  <script>
    var data_table,
        data_current_table,
        event_table,
        event_current_table,
        initialize = true,
        value_range = {"min":0, "max":100},
        setpoint_range = {"min":0, "max":100},
        duration_range = {"min":0, "max":100},
        value_slider, setpoint_slider, duration_slider;

    $(document).ready(function(){
      $('.pmd-floating-action').prepend('\
        <a href="javascript:void(0);" class="export_data_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
          Export Data\
        </a>'
      );
      // ========================= Script for Device Data tab  =========================
      // Device data - Data Type select element "onChange"
      $('#function').on('change', function() {
          $('.col-device-type').html(this.value); //change heading for table device type column from "value" to "temperature" or something else..
          $('.device_data_table-title').html("<strong>Data for "+$('#device_id').find('option:selected').attr("name") + " with function type "+this.value+"</strong>"); // Add table title
          // Show the appropriate table and hide the other
          if ($('#device_id').find('option:selected').val() != "all") {
            $('.table-with-name').hide();
            data_current_table = $('.table-without-name');
          }else{
            $('.table-without-name').hide();
            data_current_table = $('.table-with-name');
          }
          data_current_table.show();
          //Init a timeout variable to be used below
          var timeout = null;
          // search fields
          $('.device_data_search-input-field').on( 'keyup', function () {   // for text boxes
            //clear the timeout if it's already been set.
            // This will prevent the previous task from executing
            // If it has been less that 500 millseconds.
            var element = this;
            clearTimeout(timeout);
            timeout = setTimeout(function(){
              var i =$(element).attr('data-column');  // getting column index
              var v =$(element).val();  // getting search input value
              data_table.columns(i).search(v).draw();
            }, 500);
          });
          //Range slider for "Value" column
          data_current_table.find('.value-slider').ionRangeSlider({
            type: "double",
            min: 1000000,
            max: 2000000,
            grid: true,
            grid_snap: true,
            onFinish: function (data) {
              var i = data.input.attr('data-column');
              var v = data.from+"-"+data.to;
                data_table.columns(i).search(v).draw();
            },
          });
          value_slider = data_current_table.find('.value-slider').data("ionRangeSlider");

          //Range slider for "Setpoint" column
          data_current_table.find('.setpoint-slider').ionRangeSlider({
            type: "double",
            min: 1000000,
            max: 2000000,
            grid: true,
            grid_snap: true,
            onFinish: function (data) {
              var i = data.input.attr('data-column');
              var v = (data.from-1)+"-"+(data.to+1);
                data_table.columns(i).search(v).draw();
            },
          });
          setpoint_slider = data_current_table.find('.setpoint-slider').data("ionRangeSlider");
      });

      $('#submitbutton').on('click', function(){
        //destroy the table and redraw
        if(data_table != undefined) {
          data_table.destroy();
        }
        initialize = true;
        var startDate = $('#startdate').val().replace('-','/');
        var endDate = $('#enddate').val().replace('-','/');
        if(startDate > endDate){
          $('#Error').show();
        }else{
          filename = $('#startdate').val()+"--"+$('#enddate').val()+"--"+$('#function').val()+"--"+$('#device_id').find('option:selected').attr("name");
          data_table = data_current_table.DataTable({
                    dom: 'Blrtip',
                    buttons:[{
                      extend: 'collection',
                      text: 'Export',
                      buttons: [
                        {extend: 'copy'},
                        {extend: 'csv', filename: filename},
                        {extend: 'excel', filename: filename},
                        {extend: 'print', filename: filename},
                        {extend: 'pdf', filename: filename, 
                          customize: function (doc) { 
                                      doc.content[2].table.widths = Array(doc.content[2].table.body[0].length + 1).join('*').split('');
                                    } 
                        }
                      ],
                    }],
                    lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "All"]],
                    responsive : true,
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        url: 'export',
                        data: {
                          'startdate' : $('#startdate').val(), 
                          'enddate' : $('#enddate').val(),
                          'function' : $('#function').val(),
                          'device_id' : $('#device_id').val(),
                          'event'     : false
                        },
                        method: "POST",
                        error: function(){  // error handling
                            data_current_table.append('<tbody class="myTable-error"><tr><th colspan="30">No data found in the table.</th></tr></tbody>');
                            data_current_table.parent().find(".dataTables_processing").css("display", "none");
                        },
                        complete: function(){
                          $('#Error').hide(); //hide the 'start-date is greater than end-date error'
                        }
        
                      },
                      "fnInitComplete": function(oSettings, json) {
                        //first time loading
                        if (initialize) {
                          initialize = false;
                          value_range.min = parseInt(json.current_value[0]); value_range.max = Math.ceil(parseFloat(json.current_value[1]));
                          setpoint_range.min = parseInt(json.setpoint[0]); setpoint_range.max = Math.ceil(parseFloat(json.setpoint[1])); 
                          // change range for value_slider
                          value_slider.update({
                              min: value_range.min,
                              max: value_range.max,
                              from: value_range.min,
                              to: value_range.max
                          });    
                          // change range for setpoint_slider
                          setpoint_slider.update({
                              min: setpoint_range.min,
                              max: setpoint_range.max,
                              from: setpoint_range.min,
                              to: setpoint_range.max
                          });                  
                        }
                      },
                      "drawCallback": function( settings) {
                          // callback after draw function
                      },
                      "createdRow": function( row, data, dataIndex){
                        var json = this.api().ajax.json();
                        if( json.severity[dataIndex] != null){
                            $(row).addClass('redClass').addClass('ttop');
                            $(row).attr('title', '<label>'+json.description[dataIndex]+'</label>' );
   
                            /* Apply the tooltips */
                            data_table.$('tr').tooltip( {
                                    placement : 'bottom',
                                    html : true
                            } ); 
                        }
                      }
                  });
        }  
      });

      // ========================= Script for Events tab  =========================
      var name_column;
      //All devices for events
      var deviceList = {{json_encode($events_devices)}};
      deviceList = deviceList.map(function(element){
        return element.id;
      })

      // Events data - Data Type select element "onChange"
       $('#event_device_id').on('change', function() {
          $('.event_table-title').html("<strong>Data for "+$(this).find('option:selected').attr("name") +"</strong>"); // Add table title
          // Show the appropriate table and hide the other
          if ($(this).find('option:selected').val() != "all") {
            $('.Etable-with-name').hide();
            event_current_table = $('.Etable-without-name');
            name_column = false;
          }else{
            $('.Etable-without-name').hide();
            event_current_table = $('.Etable-with-name');
            name_column = true;
          }
          event_current_table.show();

          //Init a timeout variable to be used below
          var timeout = null;
          // search fields
          $('.event_search-input-field').on( 'keyup', function () {   // for text boxes
            //clear the timeout if it's already been set.
            // This will prevent the previous task from executing
            // If it has been less that 500 millseconds.
            var element = this;
            clearTimeout(timeout);
            timeout = setTimeout(function(){
              var i =$(element).attr('data-column');  // getting column index
              var v =$(element).val();  // getting search input value
              event_table.columns(i).search(v).draw();
            },500);
          });

          //Range slider for "duration" column
          event_current_table.find('.duration-slider').ionRangeSlider({
            type: "double",
            onFinish: function (data) {
              var i = data.input.attr('data-column');
              var v = data.from_pretty+"-"+data.to_pretty;
                event_table.columns(i).search(v).draw();
            },
          });
          duration_slider = event_current_table.find('.duration-slider').data("ionRangeSlider");

          $(".description-list").change(function(){
            var i =$(this).attr('data-column');  // getting column index
            var v =$(this).val();  // getting search input value
            event_table.columns(i).search(v).draw();
          })
      });

      $('#submit_event_button').on('click', function(){
        //destroy the table and redraw
        if(event_table != undefined) {
          event_table.destroy();
        }
        initialize = true;
        var startDate = $('#event_startdate').val().replace('-','/');
        var endDate = $('#event_enddate').val().replace('-','/');
        if(startDate > endDate){
          $('#event_Error').show();
        }else{
          filename = $('#event_startdate').val()+"--"+$('#event_enddate').val()+"--"+$('#event_device_id').find('option:selected').attr("name");
          event_table = event_current_table.DataTable({
                    dom: 'Blrtip',
                    buttons:[{
                      extend: 'collection',
                      text: 'Export',
                      buttons: [
                        {extend: 'copy'},
                        {extend: 'csv', filename: filename},
                        {extend: 'excel', filename: filename},
                        {extend: 'print', filename: filename},
                        {extend: 'pdf', filename: filename, customize: function (doc) { doc.content[2].table.widths = Array(doc.content[2].table.body[0].length + 1).join('*').split('');} }
                      ],
                    }],
                    lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "All"]],
                    responsive : true,
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        url: 'export',
                        data: {
                          'startdate' : $('#event_startdate').val(), 
                          'enddate'   : $('#event_enddate').val(),
                          'device_id' : $('#event_device_id').val(),
                          'device_list': deviceList,
                          'event'     : true
                        },
                        method: "POST",
                        error: function(){  // error handling
                            event_current_table.append('<tbody class="myTable-error"><tr><th colspan="30">No data found in the table.</th></tr></tbody>');
                            event_current_table.parent().find(".dataTables_processing").css("display", "none");
                        },
                        complete: function(){
                          $('#event_Error').hide(); //hide the 'start-date is greater than end-date error'
                        }
                      },
                      "fnInitComplete": function(oSettings, json) {
                        //first time loading
                        if (initialize) {
                          initialize = false;
                          duration_range.min = "1970/01/01 "+json.duration[0]; duration_range.max = "1970/01/01 "+json.duration[1];
                          description_list = json.description[0];
                          alarm_code_list  = json.description[1];
                          // change range for value_slider
                          duration_slider.update({
                              min: moment(duration_range.min, "YYYY/MM/DD HH:mm:ss").format("X"),
                              max: moment(duration_range.max, "YYYY/MM/DD HH:mm:ss").format("X"),
                              from: moment(duration_range.min, "YYYY/MM/DD HH:mm:ss").format("X"),
                              to: moment(duration_range.max, "YYYY/MM/DD HH:mm:ss").format("X"),
                              prettify: function (num) {
                                  return moment(num, "X").format("HH:mm:ss");
                              }
                          });
                          //Add options in description select element
                          $(".description-list option").remove();
                          $(".description-list").append('<option value="all">All</option>')
                          for (let index = 0; index < description_list.length; index++) {
                            $(".description-list").append('<option value="'+alarm_code_list[index]+'">'+description_list[index]+'</option>');
                          }
                
                        }
                      },
                      // Format the datetime in created date and ending date column. 
                      // Format duration column, add "Hours" after time
                      columnDefs: [ {
                        targets: name_column ? [1, 2] : [0, 1], // Created Date and Ending Date
                        render: function(data, type, row){
                            return moment(data, 'YYYY/MM/DD HH:mm:ss').format('DD/MM/YYYY hh:mm A')
                          }
                      },{
                        targets: name_column ? 3 : 2,   // Duration Column
                        render: function(data, type, row){
                            return data+" Hours";
                          }
                      }
                     ]
                      // "drawCallback": function( settings) {
                      //     // callback after draw function
                      // },
                      // "createdRow": function( row, data, dataIndex){
                      //   var json = this.api().ajax.json();
                      //   if( json.severity[dataIndex] != null){
                      //       $(row).addClass('redClass').addClass('ttop');
                      //       $(row).attr('title', '<label>'+json.description[dataIndex]+'</label>' );
   
                      //       /* Apply the tooltips */
                      //       event_table.$('tr').tooltip( {
                      //               placement : 'bottom',
                      //               html : true
                      //       } ); 
                      //   }
                      // }
                  });
        }  
      });
    });
  </script>
@stop
