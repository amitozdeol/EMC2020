

  var chart = [];
  //============================= Variable -  skeleton data ===============================
  //Visual representation of xaxis_skeleton - https://files.slack.com/files-pri/T3ZFMEJ1K-F6U6L443T/screen_shot_2017-08-28_at_1.00.10_pm.png
  var xaxis_skeleton = {};
  //visual representation of jsondata_skeleton - https://files.slack.com/files-pri/T3ZFMEJ1K-F6U35A708/screen_shot_2017-08-28_at_1.03.15_pm.png
  var jsondata_skeleton = {}
  //visual representation of legendDataArray - https://files.slack.com/files-pri/T3ZFMEJ1K-F6U6NVA49/screen_shot_2017-08-28_at_1.04.36_pm.png
  var legendDataArray = [];
  for (var i in functionList) {
    var function_name = functionList[i];
    legendDataArray[function_name] ={};
    legendDataArray[function_name]["id"] = [];
    legendDataArray[function_name]["name"] = [];
    jsondata_skeleton[function_name] ={};
    xaxis_skeleton[function_name] ={};
    for (var i = 0; i < InitData[function_name]["dev_id"].length; i++) {
      var curr_arr_val_id = InitData[function_name]["dev_id"][i];
      var curr_arr_val_name = InitData[function_name]["dev_name"][i];
      var currentname_Y_axis = curr_arr_val_name+"("+curr_arr_val_id+")";
      var currentname_X_axis = curr_arr_val_id+"time";
      legendDataArray[function_name]["id"].push(curr_arr_val_id);
      legendDataArray[function_name]["name"].push(currentname_Y_axis);
      jsondata_skeleton[function_name][currentname_Y_axis] = curr_arr_val_id;
      jsondata_skeleton[function_name][currentname_X_axis] = "";
      xaxis_skeleton[function_name][currentname_Y_axis] = currentname_X_axis;
    }
  }
  //============================= Variable -  skeleton data ===============================
  var subchartbool = true;
  if ($(window).width() < 960 || routePrefix == 'touchscreen') {
      //if on mobile phones and EMC touchscreen, hide the subchart.
     subchartbool = false;
  }
  var device_data_id = [];
  //================================make chart skeleton =================================
  for (var i = 0; i < functionList.length; i++) {
    //resize chart according to number of devices each function has.
    if (InitData[functionList[i]]["dev_id"].length > 20) {
      $('#'+functionList[i].replace(/\s+/g, '')+'wrapper').addClass('col-xs-12');
    }else{
      $('#'+functionList[i].replace(/\s+/g, '')+'wrapper').addClass('col-xs-12 col-sm-6');
    }
    (function(i) {
      var myfunc      = functionList[i];
      var isDigitalCT = InitData[myfunc]['Digital'] == 0 ? InitData[myfunc]['chart_type']: 'area'; //Chart Type for Digital/Analog
      chart[i] = c3.generate({
        bindto: '#'+myfunc.replace(/\s/g, '')+'chart',
        data: {
          json: jsondata_skeleton[myfunc],
          xs: xaxis_skeleton[myfunc],
          xFormat: '%Y-%m-%d %H:%M:%S',
          type: isDigitalCT,
        },
        tooltip: {
          format: {
            value: function (value, ratio, id) {
                return value.toFixed(2) +' '+InitData[myfunc]['Units'];
            }
          },
          grouped: false, // Default true
        },
        legend: {
          show: false
        },
        padding: {
          top: 20,
          right: 50
        },
        grid: {
           x: {
               show: true
           },
           y: {
               show: true
           }
        },
        subchart: {
            show: subchartbool
          },
        axis : {
            x : {
                type : 'timeseries',
                tick: {
                    fit: InitData[myfunc]['chart_type'] == "bar" ? true : false,
                    format: '%m-%d-%Y %I:%M %p',
                    rotate: 60
                },
            },
            y: {
              label: {
                  text: myfunc,
                  position: 'outer-middle'
              },
              tick : {
                  format : function (y) {
                      if(InitData[myfunc]['Digital'] == 0){ //don't format tick when analog device
                        return y.toFixed(2); //round up-to two decimal places
                      }
                      else{
                        if(y == 1)
                          return "on";
                        else if(y ==0)
                          return "off";
                        // else{
                        //   var format = d3.time.format("%H:%M:%S");
                        //   return format(new Date(new Date('01-01-2017 00:00:00').getTime() + (y * 1000)));
                        // }
                      }
                  }
              }
            }
        },
        title: {
          text: myfunc
        }
      });
    })(i);
    var index = 0;
    // ================== Add seperate div for legends ============================
    (function(i) {
      d3.select('#'+functionList[i].replace(/\s+/g, '')+'legend').insert('div', '#'+functionList[i].replace(/\s+/g, '')+'chart').attr('class', 'legend').selectAll('div')
       .data(legendDataArray[functionList[i]]["name"])
       .enter().append('div')
       .attr('data-id', function(id) {
         return id;
       })
       .attr('style', function(id) {
         return 'background-color: white; padding: 0px 10px; border: 1px solid;';
       })
       .html(function(id) {
         return id;
       })
       .each(function(id) {
         //d3.select(this).append('span').style
         d3.select(this).append('span').style('background-color', chart[i].color(id));
         d3.select(this).attr("data-val", legendDataArray[functionList[i]]["id"][index] );
         index++;
       })
       .on('mouseover', function(id) {
         chart[i].focus(id);
         //$(this).siblings().addClass("c3-legend-item-hidden");
       })
       .on('mouseout', function(id) {
         chart[i].revert();
         $(this).siblings().each(function() {
             if (!$(this).hasClass("c3-permanent")) {
               $(this).removeClass("c3-legend-item-hidden");
             }
          });
       })
       .on('click', function(id) {
         $(this).toggleClass("c3-legend-items-hidden c3-permanent");
         chart[i].toggle(id);
       });
     })(i);
  }
  //================================ ~make chart skeleton~ =================================
  //================================ Add chart data=================================
  if( $('.js-reports-load').length ) {
    var emptychartcount = 0;
    var reportCheck = function(ajax_call)
    {
      //ajax call for every chart with different type of functions
      if(ajax_call < functionList.length) {
        var funcNam = functionList[ajax_call].replace(/\s+/g, '');
        // Load Last week Data for water, by default
        // Water does not contain most recent data. Only data 4 days prior today's date
        if (funcNam == "Water") {
          $('#'+funcNam+'wrapper').find(".time-range").each(function(i, e){
            e.dataset.range == "Last Week" ? $(e).addClass('active') : $(e).removeClass('active')
          });
          PerChartFilter("Last Week", funcNam, ajax_call);
        }else{
          $('.loading'+funcNam.toLowerCase()).show(); //loading gif
          $('.nodata'+funcNam.toLowerCase()).hide();  //"No data to display" message
          $('.loading-message').html('Loading');      //"Loading" message
            var request = $.ajax({
                url: 'reports/ajax',
                data: {
                  dataFunction: functionList[ajax_call],
                  dataFuncDeviceID: legendDataArray[functionList[ajax_call]]["id"],
                  dataFuncDeviceName: legendDataArray[functionList[ajax_call]]["name"],
                  timeVtime: false,
                  startdateToFetch: fetchDate,
                  enddateToFetch: fetchLimit
                },
                method: "POST",
                complete: function(data, textStatus, jqXHR){
                  if (typeof data.responseJSON.data == 'undefined'){  //if no data
                    $('.'+funcNam+'wrapper').hide();  //this is the entire chart container
                    $('#cb3'+funcNam).prop( 'checked', false);  //show/hide button in dropdown of "Add chart"
                    $('.nodata'+funcNam.toLowerCase()).show();
                    emptychartcount = emptychartcount+1;
                  }else{
                    $('.nodata'+funcNam.toLowerCase()).hide();
                  }
                  $('.loading'+funcNam.toLowerCase()).hide();
                  ajax_call = ajax_call+1;
                  setTimeout(reportCheck(ajax_call), 100);
                },
                success: function(data, textStatus, jqXHR){
                  var xaxis = {};
                  var yaxis = {};
                  var dev_current_obj = InitData[functionList[ajax_call]];
                  for (var i = 0; i < dev_current_obj['dev_id'].length; i++) {
                    var Dev_name = dev_current_obj['dev_name'][i];
                    var Dev_id = dev_current_obj['dev_id'][i];
                    var Dev_zone = dev_current_obj['zone_id'][i];
                    //assigning corresponding x-axis to y-axis
                    xaxis[Dev_name+"("+Dev_id+")"] = Dev_id+"time";
                  }
                  if (data.DeviceNameForLegend != undefined) {
                    ActiveLegends(data.DeviceNameForLegend, funcNam);                
                    //redraw the chart after very ajax call
                    chart[ajax_call].load({
                      xs: xaxis,
                      json: data.data,
                    });
                  }else{
                    chart[ajax_call].unload();
                  }
                }
              });
        }
      }else{
        if(emptychartcount == ajax_call){
          $('#missingcharts').css('display', 'grid');
          $('#missingcharts').show();
        }
      }
    };
    reportCheck(0); //initial call to function
  }
  //================================ Add chart data=================================
  //========================= zone selection button functionality ==========================
  function zoneButtons(zoneid, functionName, chartindex, activeButton){
    //All Zones
    if (zoneid == 0) {
      d3.select('#'+functionName.replace(/\s+/g, '')+'legend')
        .select('.legend')
        .selectAll('div')
        .each(function(id){
        $(this).removeClass("c3-legend-items-hidden");
        $(this).toggle(true);
      });
      chart[chartindex].show();
    }
    //Other Zones
    else{
      var showthesedevices = [];
      var showthesedevices_id = []
       for (var i = 0; i < InitData[functionName]['zone_id'].length; i++) {
          if (InitData[functionName]['zone_id'][i] == zoneid) {
            var Dev_name = InitData[functionName]['dev_name'][i];
            var Dev_id = InitData[functionName]['dev_id'][i];
            showthesedevices.push(Dev_name+"("+Dev_id+")");
            showthesedevices_id.push(Dev_id);
          }
       }
      chart[chartindex].hide();
      chart[chartindex].show(showthesedevices);
      //Show legends when data-id attribute is equal to any value in showthesedevices_id
      d3.select('#'+functionName.replace(/\s+/g, '')+'legend')
        .select('.legend')
        .selectAll('div').each(function(id){
            if (showthesedevices_id.indexOf(this.dataset.val) >=0) {
              $(this).toggle(true);
              $(this).removeClass("c3-legend-item-hidden");
            }else{
              $(this).toggle(false);
            }
          });
    }
    //only keep 1 button active at a time
    $(activeButton).siblings().not(this).removeClass('active');
  }
  $(document).ready(function(){
    //================== GLOBAL - show/hide starttime/endtime input field when choose custom option ===================
    $('#Globaltimerange-select').change(function() {
        if($(this).val() === "Custom"){
          $('#SD-label').show();
          $('#ED-label').show();
        }else{
          $('#SD-label').hide();
          $('#ED-label').hide();
        }
        $('.global-alert').show();
    });
    if (SelectOptionTime === "Custom") {
      document.getElementsByName("start-date").value = startDate;
      document.getElementsByName("end-date").value = endDate;
      $('#SD-label').show();
      $('#ED-label').show();
    }
    //================== GLOBAL - show/hide starttime/endtime input field when choose custom option ===================
    //show hide legends with click of the icon
    $('.legend-button').on('click', function(){
      $(this).siblings('.legendContainer').toggle();
      $(this).toggleClass("buttonactive");
    });

    //Bar chart
    // $('.Re-chart').on('click', function(){
    //   $(this).toggleClass("buttonactive");
    //   var chartID = $(this).data("id");
    //   var functionname = $(this).attr("data-func");
    //   var NOSpaceFuncName = functionname.replace(/\s+/g, '');
    //   $.ajax({
    //       url: system_id+'/reports/ajax',
    //       data: {
    //         dataFunction: functionname,
    //         timeVtime: true,
    //         dataFuncDeviceID: legendDataArray[functionname]["id"],
    //         startdateToFetch: fetchDate,
    //         enddateToFetch: fetchLimit
    //       },
    //       method: "POST",
    //       complete: function(data, textStatus, jqXHR){
    //         if (data.statusText == "timeout" || typeof data.responseJSON.data == 'undefined'){
    //           $('.nodata'+NOSpaceFuncName.toLowerCase()).show();
    //         }
    //         $('.loading'+NOSpaceFuncName.toLowerCase()).hide();
    //       },
    //       success: function(data, textStatus, jqXHR){
    //         var xaxis = {};
    //         var yaxis = {};
    //         var dev_current_obj = device_object[functionname];
    //         for (var key in dev_current_obj) {
    //           if (dev_current_obj.hasOwnProperty(key)) {
    //             var Dev_name = dev_current_obj[key]['name'];
    //             var Dev_id = dev_current_obj[key]['id'];
    //             var Dev_zone = dev_current_obj[key]['zone_id'];
    //             //assigning corresponding x-axis to y-axis
    //             xaxis[Dev_name+"("+Dev_id+")"] = Dev_id+"time";
    //             for (var i = 0; i < data.data[Dev_name+"("+Dev_id+")"].length; i++) {
    //               var a = data.data[Dev_name+"("+Dev_id+")"][i].split(':'); // split it at the colons
    //               // minutes are worth 60 seconds. Hours are worth 60 minutes.
    //               var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    //               data.data[Dev_name+"("+Dev_id+")"][i] = seconds;
    //             }
    //           }
    //         }
    //         //redraw the chart after very ajax call
    //         //chart[chartID].load({unload: true});
    //         chart[10] = c3.generate({
    //           bindto: '#'+functionname.replace(/\s/g, '')+'chart',
    //           data: {
    //             json: data.data,
    //             xs: xaxis,
    //             xFormat: '%Y-%m-%d %H:%M:%S',
    //             type: 'bar',
    //           },
    //           tooltip: {
    //             format: {
    //               value: function (value, ratio, id) {
    //                   var format = d3.time.format("%H:%M:%S");
    //                   return format(new Date(new Date('01-01-2017 00:00:00').getTime() + (value * 1000)))+' '+'hours';
    //               }
    //             },
    //             grouped: false, // Default true
    //           },
    //           legend: {
    //             show: false
    //           },
    //           padding: {
    //             top: 20,
    //             right: 50
    //           },
    //           grid: {
    //              x: {
    //                  show: true
    //              },
    //              y: {
    //                  show: true
    //              }
    //           },
    //           subchart: {
    //               show: subchartbool,
    //             },
    //           axis : {
    //               x : {
    //                   type : 'timeseries',
    //                   tick: {
    //                       fit: false,
    //                       format: '%m-%d-%Y',
    //                       rotate: 60
    //                   },
    //               },
    //               y: {
    //                 label: {
    //                     text: 'Hours',
    //                     position: 'outer-middle'
    //                 },
    //                 tick : {
    //                     format : function (y) {
    //                       var format = d3.time.format("%H:%M:%S");
    //                       return format(new Date(new Date('01-01-2017 00:00:00').getTime() + (y * 1000)));
    //                     }
    //                 }
    //               }
    //           },
    //           title: {
    //             text: functionname+ "Time Vs Time"
    //           }
    //         });
    //       },
    //       timeout: 60000 // sets timeout to 1 minute
    //     });
    // });

    //============================ expand/compress charts ================================
    var chart_container = '';
    var ChartId = '';
    var expandbutton_pressed = 0;
    $('.expand-button').on('click', function(){
      if (document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled) {
          var i = document.getElementById(this.dataset.chart);
              // in full-screen?
              if (
                  document.fullscreenElement ||
                  document.webkitFullscreenElement ||
                  document.mozFullScreenElement ||
                  document.msFullscreenElement
              ) {
                  // exit full-screen
                  if (document.exitFullscreen) {
                      document.exitFullscreen();
                  } else if (document.webkitExitFullscreen) {
                      document.webkitExitFullscreen();
                  } else if (document.mozCancelFullScreen) {
                      document.mozCancelFullScreen();
                  } else if (document.msExitFullscreen) {
                      document.msExitFullscreen();
                  }
                  $(chart_container).css({"width": '', "height": '', 'padding': '0 15px 0'});
                  chart[ChartId].resize({height: parseInt($('.chart-container').css('min-height'))});
                  chart_container = '';
                  ChartId = '';
              }
              else {
                  // go full-screen
                  if (i.requestFullscreen) {
                      i.requestFullscreen();
                  } else if (i.webkitRequestFullscreen) {
                      i.webkitRequestFullscreen();
                  } else if (i.mozRequestFullScreen) {
                      i.mozRequestFullScreen();
                      //only needed for firefox
                      setTimeout(function(){
                        chart_container = "#"+this.dataset.chart;
                        ChartId = this.dataset.id;
                        $(chart_container).css({"width": screen.width+"px", "height": screen.height+"px"});
                        chart[ChartId].resize();
                      }, 500);
                  } else if (i.msRequestFullscreen) {
                      i.msRequestFullscreen();
                  }
                  chart_container = "#"+this.dataset.chart;
                  ChartId = this.dataset.id;
                  $(chart_container).css({"width": screen.width+"px", "height": screen.height+"px"});
                  chart[ChartId].resize();
              }
      }
      // For midori browser and other old browsers.
      else{
        expandbutton_pressed++;
        chart_container = "#"+this.dataset.chart;
        ChartId = this.dataset.id;
        if (expandbutton_pressed % 2 == 0) {  //compress
          $(chart_container).css({"width": '', "height": '', 'padding': '0 15px 0','position': 'relative', 'top': '', 'left': '', 'z-index': ''});
            chart[ChartId].resize({height: parseInt($('.chart-container').css('min-height'))});
        }else{  //expand
            $(chart_container).css({"width": screen.width+"px", "height": (screen.height-10)+"px", 'position': 'fixed', 'top': '10px', 'left': 0, 'z-index': 10000, 'padding': '0'});
            chart[ChartId].resize();
        }
      }
    });
    //need fixed width for c3 charts. Percentage width giving me error
    $( window ).resize(function() {
      SmallScreenToFullScreen();  //triggered when user rotate their phone while in full-screen mode.
      DecreaseMinHeight();
    }).resize();
    function SmallScreenToFullScreen(){
      if (chart_container != '' || ChartId != '' ) {
        if(document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement){
          $(chart_container).css({"width": screen.width+"px", "height": screen.height+"px"});
          chart[ChartId].resize();
        }else{
          $(chart_container).css({"width": '', "height": '', 'padding': '0 15px 0'});
          chart[ChartId].resize({height: parseInt($('.chart-container').css('min-height'))});
          chart_container = '';
          ChartId = '';
        }
      }
    }
    function DecreaseMinHeight(){
      //reduce min-height for chart container
      //change min-height to 300px so we caould draw chart upto 300px. This is for very small phone sizes(phones in landscape mode)
      if (parseInt($('.chart-container').css('min-height')) > $(window).height()) {
        $('.chart-container').css({'min-height': '300px'});
      }
    }
  });
  //========================================== ~expand/compress charts~ ============================================
  function PerChartFilter(timerange, functionname, chartIndex){
    //======================= get Start End Date =======================
    var NOSpaceFuncName = functionname.replace(/\s+/g, '');
    var startDate = '';
    var endDate = '';
    var d = new Date();
    endDate = formatDate(d);
    switch(timerange) {
        case "Today":
            startDate = formatDate(d);
            break;
        case "Last Week":
            d = new Date(d.getTime() - 7 * 24 * 60 * 60 * 1000);
            startDate = formatDate(d);
            break;
        case "Last Month":
            d = new Date(d.setMonth(d.getMonth() - 1));
            startDate = formatDate(d);
            break;
        case "Last Year":
            d.setFullYear(d.getFullYear() - 1);
            startDate = formatDate(d);
            break;
        case "Custom":
            startDate = document.getElementsByName('start-date-modal')[chartIndex].value;
            endDate = document.getElementsByName('end-date-modal')[chartIndex].value;
            break;
        default:
            break;
    }
    document.getElementsByName('start-date-modal')[0].value = startDate;
    document.getElementsByName('start-date-modal')[0].value = endDate;
    fetchDate = startDate;  //globally save this date
    fetchLimit = endDate;   //globally save this date
    var difference_start = new Date(startDate);
    var difference_end = new Date(endDate);
    //======================= ~get Start End Date~ =======================
    if(difference_end.getTime() - difference_start.getTime() < 0){  //for custom modal
      var alertdiv = '<div class="alert alert-warning alert-dismissable fade in"><a class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning!</strong> Start time should always be smaller than end time.</div>';
      $('#modal-body-'+NOSpaceFuncName).prepend(alertdiv);
    }else{
      $('#close'+NOSpaceFuncName).click(); //close modal
      $('.loading'+NOSpaceFuncName.toLowerCase()).show();
      $('.nodata'+NOSpaceFuncName.toLowerCase()).hide();
      $('.loading-message').html('Loading');
      $.ajax({
          url: 'reports/ajax',
          data: {
            dataFunction: functionname,
            timeVtime: false,
            dataFuncDeviceID: legendDataArray[functionname]["id"],
            dataFuncDeviceName: legendDataArray[functionname]["name"],
            startdateToFetch: startDate,
            enddateToFetch: endDate
          },
          method: "POST",
          complete: function(data, textStatus, jqXHR){
            if (data.statusText == "timeout" || typeof data.responseJSON.data == 'undefined'){
              $('.nodata'+NOSpaceFuncName.toLowerCase()).show();
            }
            $('.loading'+NOSpaceFuncName.toLowerCase()).hide();
          },
          success: function(data, textStatus, jqXHR){
            var xaxis = {};
            var yaxis = {};
            var dev_current_obj = InitData[functionname];
            for (var i = 0; i < dev_current_obj['dev_id'].length; i++) {
              var Dev_name = dev_current_obj['dev_name'][i];
              var Dev_id = dev_current_obj['dev_id'][i];
              var Dev_zone = dev_current_obj['zone_id'][i];
              //assigning corresponding x-axis to y-axis
              xaxis[Dev_name+"("+Dev_id+")"] = Dev_id+"time";
            }
            if (data.DeviceNameForLegend != undefined) {
              ActiveLegends(data.DeviceNameForLegend, functionname);
              //redraw the chart after very ajax call
              //chart[chartIndex].load({unload: true});
              //redraw the chart after very ajax call
              chart[chartIndex].load({
                xs: xaxis,
                json: data.data,
              });
            }else{
              chart[chartIndex].unload();
            }
          },
          timeout: 60000 // sets timeout to 1 minute
        });
    }
  }

  // hide the legends that don't have any data
  function ActiveLegends(legends, functionname){
    d3.select('#'+functionname+'legend')
      .select('.legend')
      .selectAll('div')
      .each(function(id){
        $(this).removeClass("c3-legend-items-hidden c3-permanent");        
        if (legends.indexOf(id.trim()) < 0) {
          $(this).addClass("c3-legend-items-hidden c3-permanent"); 
        }
      });
  }

  function formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();
      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;
      return [year, month, day].join('-');
  }
  //======================== hide/show charts =========================
  $( '.dropdown-menu a' ).on( 'click', function( event ) {
    var show_missing_chart = true,
        target      = $( event.currentTarget ),
         val        = target.attr( 'data-value' ),
         inp        = target.find( 'input' );

    // Trigger window resize event     
    if(typeof(Event) === 'function') {
      // modern browsers
      window.dispatchEvent(new Event('resize'));
    }else{
      // for IE and other old browsers
      // causes deprecation warning on modern browsers
      var evt = window.document.createEvent('UIEvents'); 
      evt.initUIEvent('resize', true, false, window, 0); 
      window.dispatchEvent(evt);
    }

    inp.prop( 'checked', !inp.prop("checked") );
    if (!inp.is(':checked')) {
      $('.'+val+'wrapper').fadeOut('fast');
    }else{
      $('.'+val+'wrapper').fadeIn('fast');
    }
    $( event.target ).blur();
    // If any chart is visible, don't show "mission Chart" panel
    setTimeout(function() {
      $('.chart-wrapper').each(function(){
        if ($(this).is(":visible")) {
          show_missing_chart = false;
        }
      })
      if (show_missing_chart) {
        $('#missingcharts').show();
      }else{
        $('#missingcharts').hide();
      }
    }, 400);
    
    
    return false;
  });
