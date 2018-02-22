//keep button active even after clicked anywhere on screen
// $(".btn-group > .btn").click(function () {
//   $(this).addClass("active").siblings().removeClass("active");
// });

//show hide legends with click of the Filter button
$(document).on('click', function(e){
  if(!$(e.target).hasClass('d3-legend-button') && !$(e.target).hasClass('legend')){
        $(".legendContainer-d3").hide();
  }
  if ($(e.target).hasClass('d3-legend-button')) {
    var chart_name= $(e.target).data("cname");
    $("#legend-container-"+chart_name).toggle();
  }
});

//This is used to remove special characters(][]..) from the device name for the purpose of selecting them with d3.select
function removeSpecialChar(i) {
  var names = i.replace(/[^a-zA-Z0-9]/g, '');
  if (/^[^a-zA-Z]/g.test(names)) {
    names = "Device" + names;             //Add String to beggining of name if it only contains numbers
  }
  return names;
}
//Remove duplicate items from the array
Array.prototype.unique = function() {
  var a = this.concat();
  for(var i=0; i<a.length; ++i) {
      for(var j=i+1; j<a.length; ++j) {
          if(a[i] === a[j])
              a.splice(j--, 1);
      }
  }
  return a;
};

//========================= CONVERT SECONDS TO HH:MM:SS ============================
var convertTime = function (input, separator) {
  var pad = function (input) { return input < 10 ? "0" + input : input; };
  return [
    pad(Math.floor(input / 3600)),
    pad(Math.floor(input % 3600 / 60)),
    pad(Math.floor(input % 60)),
  ].join(typeof separator !== 'undefined' ? separator : ':');
}
//========================= CONVERT DATE TO YYYY-MM-DD =============================
Date.prototype.yyyymmdd = function () {
  var mm = this.getMonth() + 1; // getMonth() is zero-based
  var dd = this.getDate();
  return [this.getFullYear(),
  (mm > 9 ? '' : '0') + mm,
  (dd > 9 ? '' : '0') + dd
  ].join('-');
};

var charts = [];  //array of synchronous charts
var runtimebar;   //a single runtime bar chart
var updated_chart_data = [];
var currentTime = new Date();
//var svg_height = 95;  //default height of relay/Digital chart
//Visual representation of this object - https://files.slack.com/files-pri/T3ZFMEJ1K-F7D55F8AX/screen_shot_2017-10-04_at_9.13.46_am.png
var zone_specific_data = {};
var IndexForColor = 0;
// parse the date / time
var parseTime = d3.timeParse("%Y-%m-%d %H:%M:%S");
var colorsArr = ["#86619d", "#1f810d", "#dd1807", "#3f10dd", "#5d451f", "#d42069", "#217c78", "#c607c2", "#2c67e6", "#b45232", "#7e2c42", "#074e72", "#8a0174", "#28531c", "#8b49e6", "#6c7513", "#2271c0", "#756d78", "#563595", "#9a6419", "#cc3746", "#a425f9", "#564162", "#237e53", "#a05d56", "#0732cb", "#717154", "#2f5040", "#af4b8b", "#9f4faf", "#3a4484", "#965f79", "#633f41", "#cb268c", "#791196", "#773620", "#6b64c1", "#6d3073", "#5d6f9c", "#901810", "#b94968", "#2b62f9", "#4f7a41", "#bf2db0", "#33788a", "#7e678a", "#956443", "#153ab8", "#5c1db9", "#414f04", "#6c3e09", "#0b5601", "#3b4c51", "#4a4b30", "#557766", "#6c3852", "#8d6667", "#1940a6", "#8d1a33", "#296dd3", "#7a702e", "#821f63", "#db1a35", "#646aae", "#b73f9d", "#705dd3", "#c23b7a", "#1575ae", "#a643c1", "#cf3620", "#592ba7", "#732584", "#3c7e2c", "#ac35d4", "#5d01cb", "#774cf9", "#b11de7", "#832b21", "#337e40", "#743631", "#467b54", "#a65c31", "#78360c", "#98589d", "#134a84", "#6a3e1f", "#8c6a17", "#5f4507", "#5d3b73", "#554641", "#673f31", "#1e532f", "#53738a", "#3d4f1d", "#b25345", "#4b4273", "#a45c44", "#926555", "#2d5202", "#73689c", "#2c4d62", "#d61e58", "#a65579", "#767142", "#663962", "#871d53", "#6a7266", "#597a11", "#5e7654", "#bc4857", "#384f2f", "#8f608b", "#085340", "#647641", "#513c84", "#5e4052", "#4e4a1e", "#40749c", "#6a6e8a", "#594530", "#344873", "#487778", "#826c55", "#397b66", "#607278", "#866679", "#444b41", "#7c6c67", "#444762", "#205051", "#713742", "#504a06", "#9c5e68", "#4e4751", "#866b43", "#9451c1", "#ce3634", "#c3461e", "#815bc1", "#af41af", "#8a6b2f", "#8e59af", "#8a1b43", "#422fb9", "#dc1922", "#9f578b", "#585fe6", "#842a0e", "#c93858", "#8f3df9", "#7a2d52", "#0e812b", "#af5456", "#8f1922", "#184595", "#5a58f9", "#4f6bc1", "#4137a7", "#742f63", "#bd3d8c", "#6d17a7", "#c14633", "#5466d3", "#d91c47", "#682895", "#986430", "#d0237b", "#633284", "#c63969", "#9a46d4", "#8653d3", "#4970ae", "#557a2c", "#a85c1a", "#b631c2", "#7c2273", "#ab5468", "#69762d", "#bf4745", "#7a62ae", "#bd14d4", "#7455e6", "#c6299e", "#9f39e6", "#3e3e95", "#a84d9d", "#7d7015", "#b5521c", "#d03502", "#820984", "#4223cb", "#427e0f", "#b44a7a", "#812b32"];  // set the colour scale

//Move any svg element all the way to the bottom of "g" element so that it will appear on top of everything on the chart
d3.selection.prototype.moveToFront = function() {
  return this.each(function(){
    this.parentNode.appendChild(this);
  });
};
d3.selection.prototype.moveToBack = function() {  
  return this.each(function() { 
      var firstChild = this.parentNode.firstChild; 
      if (firstChild) { 
          this.parentNode.insertBefore(this, firstChild); 
      } 
  });
};
// =================== code for zone filter drop down =================== 
$(document).ready(function () {
  $(".d3-btn-select").each(function (e) {
      var value = $(this).find("ul li.selected").html();
      if (value != undefined) {
          $(this).find(".btn-select-input").val(value);
          $(this).find(".btn-select-value").html(value);
      }
  });
});

// Show the list when Zone filter button is pressed
$(document).on('click', '.d3-btn-select', function (e) {
  e.preventDefault();
  var ul = $(this).find("ul");
  if ($(this).hasClass("active")) {
      if (ul.find("li").is(e.target)) {
          var target    = $(e.target);
          var zonename  = target.html();
          var value     = target.attr("value");
          var hasx_axis = false;
          var hasData   = false;
          target.addClass("selected").siblings().removeClass("selected");
          $(this).find(".btn-select-input").val(zonename);
          $(this).find(".btn-select-value").html(zonename);
          
          // If any of the line chart has x_axis drawn, then make "hasx_axis" true
          charts.forEach(function(element){
            if (element.data.length != 0) {
              hasData = true;
            }
            if (element.linechart) {
              hasx_axis = hasx_axis || element.x_axis; 
            }
          });
          if (hasData) {
            charts.forEach(function(element){
              element.zonefilter(value, hasx_axis);
            });
          }
      }
      ul.hide();
      $(this).removeClass("active");
  }
  else {
      $('.btn-select').not(this).each(function () {
          $(this).removeClass("active").find("ul").hide();
      });
      ul.slideDown(300);
      $(this).addClass("active");
  }
});

$(document).on('click', function (e) {
  var target = $(e.target).closest(".d3-btn-select");
  if (!target.length) {
      $(".d3-btn-select").removeClass("active").find("ul").hide();
  }
});
// =================== ~code for zone filter drop down~ =================== 

//============================ expand/compress charts ================================
var ChartWidth = 0;
var ChartHeight = 0;
var fullscreentrigger = false;
var expand_charttype;
$('.expand-button').on('click', function(){
  if (document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled) {
      fullscreentrigger = true;
      expand_charttype = $(this).attr("data-chart");   //SynchronousChart or RelayBar chart
      var i = document.getElementById(expand_charttype);   //container for current chart
          // in full-screen?
          if ( document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
              // exit full-screen
              if (document.exitFullscreen) {
                  document.exitFullscreen();
              } else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
              } else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
                  // setTimeout(() => {
                  //   LargeToSmall(expand_charttype);
                  // }, 500);
              } else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
              }
              LargeToSmall(expand_charttype);
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
                  // setTimeout(() => {
                  //   SmallToLarge(i, expand_charttype);
                  // }, 500);
              } else if (i.msRequestFullscreen) {
                  i.msRequestFullscreen();
              }
              SmallToLarge(i, expand_charttype);
          }
  }
});

/* Save current chart width and height in a variable.
 * change chartcontainer width to full screen. Add scrolling
 * change chartcontainer height to full screen if it's smaller than current screen height.
 * Iterate through each chart with the same charttype and resize it
*/
function SmallToLarge(chartcontainer, charttype){
  ChartWidth  = chartcontainer.offsetWidth;     //save width and height before changing
  ChartHeight = chartcontainer.offsetHeight;
  chartcontainer.style.width    = screen.width+"px"; 
  chartcontainer.style.overflow = 'auto';
  chartcontainer.style.padding  = 0; 
  if (chartcontainer.offsetHeight <= screen.height) {
    chartcontainer.style.height = screen.height+"px";
  }
  charts.forEach(function(CHART){
    if (CHART.charttype == charttype) {
      svg_height    = CHART.height + CHART.margin.top + CHART.margin.bottom;
      CHART.width = chartcontainer.offsetWidth - CHART.margin.left - CHART.margin.right;
      CHART.resize(CHART.width, svg_height, CHART.margin.bottom, CHART.x_axis);
      updateChart(CHART.data, CHART, true);                  
    }
  })
}

/* change chartcontainer width to original. Hide scrolling
 * change chartcontainer height to original.
 * Iterate through each chart with the same charttype and resize it
*/
function LargeToSmall(c_type){
  chartcontainer = document.getElementById(c_type);
  chartcontainer.style.width    = ChartWidth+"px"; 
  chartcontainer.style.height   = ChartHeight+"px";
  chartcontainer.style.overflow = 'hidden';  
  charts.forEach(function(CHART){
    if (CHART.charttype == c_type) {
      svg_height    = CHART.height + CHART.margin.top + CHART.margin.bottom;
      CHART.width   = ChartWidth - CHART.margin.left - CHART.margin.right;
      CHART.resize(CHART.width, svg_height, CHART.margin.bottom, CHART.x_axis);      
      updateChart(CHART.data, CHART, true);                  
    }
  })

  if (typeof runtimebar !== "undefined" && runtimebar.charttype == c_type) {
    runtimebar.relayBarChartResize();
  }
  chartcontainer.style.width = "100%";
  chartcontainer.style.height = "auto";
  fullscreentrigger = false;
}
// ================================== Resize chart when resizing browser =========================================
var width = screen.width;

$( window ).resize(function() {
    // Will trigger when exit full screen by pressing ESC key. 
    setTimeout(function(){
      if (fullscreentrigger) {
        if(screen.width != window.innerWidth){
            LargeToSmall(expand_charttype);
        }
      } 
    }, 500);

    charts.forEach(function(CHART){
        CHART.width = CHART.element.offsetWidth - CHART.margin.left - CHART.margin.right;
        CHART.svg.attr("width", CHART.width + CHART.margin.left + CHART.margin.right);
        CHART.clip.attr("width", CHART.width);
        CHART.x = d3.scaleLinear().range([0, CHART.width]).domain([CHART.x_min, CHART.x_max]);
        if (CHART.x_axis) {
          xAxis   = d3.axisBottom(CHART.x)
                      .tickValues(CHART.x.ticks(10).concat(CHART.x.domain()[1]))
                      .tickFormat(d3.timeFormat("%I:%M %p %b-%d"))
                      .tickSize(-CHART.height);  //for grid
          CHART.plot.select(".Synchronous-axis--x")
                    .call(xAxis.tickValues(
                      CHART.x.ticks(10)
                        .concat(CHART.x.domain()[1])
                    ).tickFormat(d3.timeFormat("%I:%M %p %b-%d")))
                    .selectAll("text")
                    .style("text-anchor", "end")
                    .attr("dx", "-.8em")
                    .attr("dy", ".15em")
                    .attr("transform", "rotate(-45)")
                    .style("font-weight", "bold");
        }
        updateChart(CHART.data, CHART, true);                  
    })
    if (typeof runtimebar !== "undefined"){
      //Resize Bar chart and Water Widget
      runtimebar.relayBarChartResize();
    }
    if(screen.width != width){   // This condition fixed the android browser chrome bug - Calling resize event after each scroll
      if (InitData.hasOwnProperty('Water')) {
        WaterGauge.createCircle("Waterfillgauge", value);
      }
    }
});

// ========================= Chart object starts here ===============================
var Chart = function(opts) {
    // load in arguments from config object
    this.element        = opts.element;
    this.margin         = opts.margin;
    this.linechart      = opts.linechart;
    this.x_axis         = opts.x_axis;
    this.y_axis_label   = opts.axis_label;
    this.y_scale        = opts.y_scale;
    this.x_min          = opts.x_min,
    this.x_max          = opts.x_max,
    this.y_min          = 999999;
    this.y_max          = 0;
    this.y_dev_names    = [];
    this.y_padding      = (this.y_max - this.y_min)*0.05;
    this.charttype      = opts.charttype;   //Either Synchronous chart or Relay/Digital chart or more if new charts added
    this.chartname      = opts.chartname;
    this.data           = opts.data;
    this.legendSelectID = opts.legendSelectID;
    this.legendposition = opts.legendposition;
    this.AllDevices     = opts.AllDevices;
    this.svg_height     = this.x_axis ? 95 : 0;    //Initial height to be 0 or 95 depending on presence of x-axis. Chart is not present
    this.width          = this.element.offsetWidth - this.margin.left - this.margin.right;
    this.height         = this.element.offsetHeight - this.margin.top - this.margin.bottom;
    updated_chart_data[this.chartname] = [];  //this is used as clone of original data.
    // set up parent element and SVG
    //d3.select(this.element).selectAll("svg").remove();
    this.svg = d3.select(this.element).select('svg')
                      .attr('width',  this.width + this.margin.left + this.margin.right)
                      .attr('height', this.height + this.margin.top + this.margin.bottom)
                      .attr("fill", "white");
    this.clip = this.svg.append("svg:clipPath")
                      .attr("id", "clip" + this.chartname)
                      .append("svg:rect")
                      .attr('x', 0)
                      .attr('y', 0)
                      .attr("width", this.width)
                      .attr("height", this.height); // slightly wider from top and bottom for better view
    // If no x-axis then increase the height
    if(this.x_axis == false){
      this.height = this.height + this.margin.bottom + this.margin.top - 15;
      this.clip.attr("height", this.height);
    }

    // we'll actually be appending to a <g> element
    this.plot = this.svg.append('g')
        .attr('transform','translate('+this.margin.left+','+5+')');
    //legends
    if (this.legendposition == "Bottom") {
      this.boxWrapper = d3.select(this.legendSelectID)
                          .append('ul')
                          .attr('class', 'legend box-wrapper-bottom');
    }else{
      this.boxWrapper = d3.select(this.legendSelectID)
                          .append('ul')
                          .attr('class', 'legend box-wrapper');
    }
    // zoom axis -|-
    this.zoom = this.plot.append("g").attr("class", "zoom");
    var zoomHorizontal = this.zoom.append("line")
                          .attr("direction", "H")
                          .attr("class", "crossLine")
                          .attr("x1", 0)
                          .attr("x2", this.width)
                          .attr("y1", -100)
                          .attr("y2", -100); // horizontal line
    var zoomVertical = this.zoom.append("line")
                          .attr("direction", "V")
                          .attr("class", "crossLine")
                          .attr("x1", -100)
                          .attr("x2", -100)
                          .attr("y1", this.height)
                          .attr("y2", 0); // vertical line
    // tooltip
    this.tooltip = d3.select(this.element).append("div").attr("class", 'tooltip-obj').style("position", "absolute").style("display", "none");
    this.tooltip_wrapper = this.tooltip.append('xhtml:div')
                            .append('div')
                            .attr('class', 'tool-tip');
    this.tooltip_title = this.tooltip_wrapper.append("div")
                            .attr("class", "time");
    var tooltip_table = this.tooltip_wrapper.append("div")
                            .attr("class", "table")
                            .style('margin-bottom', '0px');
    var tooltip_row = tooltip_table.append("div")
                            .attr("class", "tooltip-row row" + this.chartname);
    
    if (this.linechart) {
      tooltip_row.append("div")
                              .attr("class", "circle")
                              .style("background", "white");
      tooltip_row.append("div")
                              .attr("class", "name")
                              .attr("lineN", "test")
                              .style('word-wrap', 'break-word');
      tooltip_row.append("div")
                              .attr("class", "value")
                              .attr("lineV", 0);
    }else{
      tooltip_row.append("div")
                              .attr("class", "Synchronous-runtime starttime")
                              .style('word-wrap', 'break-word');
      tooltip_row.append("div")
                              .attr("class", "Synchronous-runtime endtime");
      tooltip_row.append("div")
                              .attr("class", "Synchronous-runtime duration");
    }
    //This add blue zoom rectangle 
    this.zoomBox = this.plot.append("rect").attr("fill", "none").attr("class", "zoombox").attr("fill", "rgba(128, 196, 230,0.5)");
    // create the axis
    this.CreateAxis(this.x_axis); 
    this.AddLegends();
}

/********************************
 *  Set default axis values
 * *****************************/
Chart.prototype.CreateAxis = function(x_axis){
    // max and min for y domain. converting string values into integer if any in the same loop.
    var yMin_temp = 99999,
        yMax_temp = 0
        Global_this = this;
    // set the Domain and ranges
    this.x = d3.scaleLinear().range([0, this.width]).domain([this.x_min, this.x_max]);
    // =============== Create X Axis only once for chart =================
    if (x_axis) {
      if (this.svg_height == 95) {
        var svg_height = 150;  //Temporary change height of container if there's no data in chart
        this.height = svg_height - this.margin.top - this.margin.bottom;
        this.svg.attr("height", this.height + this.margin.top + this.margin.bottom);
        this.clip.attr("height", this.height);
        //change legend container height only when legend position is absolute 
        if (this.linechart) {
          d3.select(this.legendSelectID).style("height", this.height+"px");
        }
      }

      xAxis = d3.axisBottom(this.x)
                .tickValues(this.x.ticks(10).concat(this.x.domain()[1]))
                .tickFormat(d3.timeFormat("%I:%M %p %b-%d"))
                .tickSize(-this.height);  //for grid

      this.plot.append("g").attr("class", "axis Synchronous-axis--x")
              .attr("transform", "translate(0," + this.height + ")")
              .call(xAxis)
              .selectAll("text")
              .style("font-weight", "bold")
              .style("text-anchor", "end")
              .attr("dx", "-.8em")
              .attr("dy", ".15em")
              .attr("transform", "rotate(-45)");
    }
    this.y = this.y_scale.range([this.height, 0]);  //different scales for Temperature and relay/digital chart
    //============ Create y-axis =================
    if(this.linechart){
        this.y.domain([yMin_temp, yMax_temp]);
        yAxis = d3.axisLeft(this.y)
                  .tickSize(-this.width);
        // Create y-axis
        this.plot.append("g")
            .attr("class", "axis Synchronous-axis--y")
            .call(yAxis);
        this.plot.append("text")
            .attr("class", "yaxis_label")
            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
            .attr("transform", "translate(" + (0 - this.margin.left / 2) + "," + (this.height / 2) + ")rotate(-90)")  // text is drawn off the screen top left, move down and out and rotate
            .text(this.y_axis_label)
            .attr("stroke", "#000")
            .attr('fill', "#000");
    }else{
      var y_domain_dev_name = InitData.Relay.dev_name.concat(InitData.Digital.dev_name);
      var y_domain_dev_id = InitData.Relay.dev_id.concat(InitData.Digital.dev_id);
      this.y.domain(y_domain_dev_name);
      yAxis = d3.axisLeft(this.y);
      this.plot.append("g")
          .attr("class", "axis Synchronous-axis--y")
          .call(yAxis)
          .selectAll("text").remove();
      //return list of device names like this - devicename("deviceID")
      devices_Arr = y_domain_dev_name.map(function(d, i){ return d+"("+y_domain_dev_id[i]+")"; })
      if(this.plot.select(".background-rects").empty()){
        // add background rectangles for each devices
        this.plot.append("g").attr("class", "background-rects")
                  .selectAll(".background-rect")
                  .data(devices_Arr)
                  .enter().append("rect")
                  .attr("class", function (d) { return "background-rect background-rect"+removeSpecialChar(d); } )
                  .attr("y", function (d) { return Global_this.y(d.substring(0, d.indexOf("("))); })
                  .attr("x", 0)
                  .attr("width", Global_this.width)
                  .attr("fill", "grey")
                  .style("opacity", 0.2)
                  .attr("height", Global_this.y.bandwidth());
      }
    }
}

/********************************
 *  Set default Legend values - All the device names 
 * *****************************/
Chart.prototype.AddLegends = function(){
  for (var i = 0; i < this.AllDevices.dev_name.length; i++) {
    const name = this.AllDevices.dev_name[i]+"("+this.AllDevices.dev_id[i]+")";
    const name_tweaked = removeSpecialChar(name);
    var li = this.boxWrapper.append("li")
                .attr("class", "legend box-row box-row-" + name_tweaked + " box-row-"+this.chartname)
                .attr("box", name_tweaked)
                .attr("c_name", this.chartname)
                .attr("dev_id", this.AllDevices.dev_id[i])
                .attr("command", this.AllDevices.commands[i])
                .attr("isActive", "missing");
    li.append("span")
      .attr("class", "legend box")
      .attr("box", name_tweaked)
      .style("background", "grey")
      .style("border", "1px solid grey");
    li.append("span")
      .attr("class", "legend SyncLegend-name")
      .html(name.replace(/\_/ig, " "));
  }
}

/********************************
 *  Remove default axis values
 * *****************************/
Chart.prototype.RemoveAxis = function(){
    this.plot.select(".Synchronous-axis--x").remove();
    this.plot.select(".Synchronous-axis--y").remove();
    this.plot.select(".yaxis_label").remove();
}
/********************************
 *  Add color and name_tweaked for each device in the chart
 *  Append data for each zone into the chart this.data which contains all the data
 * *****************************/
Chart.prototype.setData = function(newData) {
  newData.map(function (e, i) {
    IndexForColor++;
    if (IndexForColor >= colorsArr.length) {
      IndexForColor = 0;
    }
    e.color = colorsArr[IndexForColor];
    e.name_tweaked = removeSpecialChar(e.name);  //Device Name with no special character, no spaces. Force device name to start with string
  });
  //concat all data from each zone into global data object and a cloned object
  if (this.initialized) {
    this.data = this.data.concat(newData);
    updated_chart_data[this.chartname] = updated_chart_data[this.chartname].concat(newData);
  }else{
    this.data = [];
    updated_chart_data[this.chartname] = [];
    this.data = this.data.concat(newData);
    updated_chart_data[this.chartname] = updated_chart_data[this.chartname].concat(newData);    
    this.initialized = true;
  }
  return newData;
}

Chart.prototype.LoadAxis = function(data) {
  // max and min for y domain. converting string values into integer if any in the same loop.
  var yMin_temp = 999999999,
      yMax_temp = 0,
      t = this.plot.transition().duration(350),
      Global_this = this;
  //set domain for x-axis
  this.x.domain([this.x_min, this.x_max]).nice();
  if (this.x_axis) {
    xAxis = d3.axisBottom(this.x)
              .tickValues(this.x.ticks(10).concat(this.x.domain()[1]))
              .tickFormat(d3.timeFormat("%I:%M %p %b-%d"))
              .tickSize(-this.height);  //for grid

    this.plot.select(".Synchronous-axis--x").transition(t).call(xAxis)
            .selectAll("text")
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-45)")
            .style("font-weight", "bold");
    
  }
  if (this.linechart) {
      data.map(function (e, index) {
        e.Data.forEach(function (d) {
          d.time = parseTime(d.time);
          d.value = +d.value;
          yMin_temp = (d.value < yMin_temp) ? d.value : yMin_temp;
          yMax_temp = (d.value > yMax_temp) ? d.value : yMax_temp;
        })
      });
      //set that min and max values to the global this variable 
      // Change the padding according to min and max 
      this.y_min = this.y_min < yMin_temp ? this.y_min : yMin_temp;
      this.y_max = this.y_max > yMax_temp ? this.y_max : yMax_temp;
      this.y_padding = (this.y_max - this.y_min)*0.05;
      //add exponential scale when the difference is greater than 200
      // if ((this.y_max - this.y_min) > 200) {
      //   this.y = d3.scalePow().exponent(-2).range([this.height, 0]);
      // }
      //======= Resize Chart height =========
      this.svg_height           =((this.y_max - this.y_min)*10) > 200 ? 200 : ((this.y_max - this.y_min)*10);
      this.svg_height           = this.svg_height < 70 ? 70 : this.svg_height;
      this.resize(this.width, this.svg_height, this.margin.bottom, this.x_axis);
      
      // set domain for y-axis
      this.y.domain([this.y_min - this.y_padding, this.y_max + this.y_padding]).range([this.height, 0]);
      yAxis = d3.axisLeft(this.y)
                .tickValues(this.y.ticks(5).concat(this.y.domain()))
                .tickSize(-this.width);
  } else {
      // Return list of device names without id in parantheses
      // Also parse the raw datetime values
      var dev_names = data.map(function (e, index) {
                        e.Data.forEach(function (d) {
                          d.startdate = parseTime(d.startdate);
                          d.enddate = parseTime(d.enddate);
                        })
                        return e.name.substring(0, e.name.indexOf("("));
                      });
      //=============== UPDATE HEIGHT -Dynamically change the height depending on number of devices reporting data ===========
      this.svg_height+=20*dev_names.length;
      this.resize(this.width, this.svg_height, this.margin.bottom, this.x_axis);

      this.y_dev_names = this.y_dev_names.concat(dev_names);
      this.y.domain(this.y_dev_names);
      yAxis = d3.axisLeft(this.y);

      this.plot.selectAll(".background-rect").remove();  //remove all temp background rectangles
      if (data && data.length) {
            this.plot.select(".background-rects") //Add background rectangles for the devices that have data
                        .selectAll(".background-rect"+data[0].zone)
                        .data(data)
                        .enter().append("rect")
                        .attr("class", function (d) { return "background-rect"+d.zone+" background-rect"+d.name_tweaked; } )
                        .attr("x", 0)
                        .attr("fill", "grey")
                        .style("opacity", 0.2)
                        .transition(t)
                        .attr("y", function (d) { return Global_this.y(d.name.substring(0, d.name.indexOf("("))); })
                        .attr("width", Global_this.width)
                        .attr("height", Global_this.y.bandwidth());
      }
  }
  // Update y axis
  this.plot.select(".Synchronous-axis--y")
      .transition(t)
      .call(yAxis);
}

/* ***************************
    Add legend and line/rectangles in the chart
*******************************/
Chart.prototype.draw = function(data) {
  Global_this = this;
  t = this.plot.transition().duration(350);
  data.map(function(e){
    /*** Legends ***/
    var li = Global_this.boxWrapper.select(".box-row-" + e.name_tweaked).lower()
                .attr("isActive", "active")
                .style("opacity", 1)
                .on("mouseover", function (d, i) {           //fade the other lines when hover over legends
                  if (d3.select(this).attr("isActive") == "active") {     //if line/rect is active
                    device_name = d3.select(this).attr("box");
                    c_name = d3.select(this).attr("c_name"); //chart name
                    //Get current chart object based on the legend you hover over
                    var curr_chart = charts.filter(function(chart){
                      return chart.chartname == c_name;
                    })[0];
                    for (var i = 0; i < curr_chart.data.length; i++) {
                      var current_line = curr_chart.data[i];
                      //if line/rect == active and 
                      //the device name of that line/rect is not equal to current hovered device name, then fade that line/rect
                      if ((curr_chart.boxWrapper.select('[box = ' + current_line.name_tweaked + ']').attr("isActive") == "active") && (device_name != current_line.name_tweaked)) {
                        curr_chart.plot.selectAll(".path" + current_line.name_tweaked).style("opacity", "0.4");               //fade lines
                        rectangle_container = curr_chart.plot.select(".Synchronous-Rects" + current_line.name_tweaked);
                        if (!rectangle_container.empty() && rectangle_container.attr("isActive") == "active") {   //If rectangle container is active
                          curr_chart.plot.selectAll(".Synchronous-Rect" + current_line.name_tweaked).style("opacity", "0.4");   //fade rectangle                          
                        }
                      }
                    }
                  }
                })
                .on("mouseout", function (d, i) {          //show all the lines/rect when hover out of the legends
                  c_name = d3.select(this).attr("c_name");
                  var curr_chart = charts.filter(function(chart){
                    return chart.chartname == c_name;
                  })[0]
                  for (var i = 0; i < curr_chart.data.length; i++) {
                    var current_line = curr_chart.data[i];
                    if (curr_chart.boxWrapper.select('[box = ' + current_line.name_tweaked + ']').attr("isActive") == "active") {
                      curr_chart.plot.selectAll(".path" + current_line.name_tweaked).style("opacity", "1");                   //show lines
                      rectangle_container = curr_chart.plot.select(".Synchronous-Rects" + current_line.name_tweaked);
                      if (!rectangle_container.empty() && rectangle_container.attr("isActive") == "active") {   //If rectangle container is active
                        curr_chart.plot.selectAll(".Synchronous-Rect" + current_line.name_tweaked).style("opacity", "1");   //show rectangle                          
                      }
                    }
                  }
                });
    li.select(".legend.box")
      .attr("box", e.name_tweaked)
      .style("background", e.color)
      .style("border", "1px solid " + e.color);
    li.select(".legend.SyncLegend-name")
      .html(e.name.replace(/\_/ig, " "));
    //Line charts
    if (Global_this.linechart) {
        var line = d3.line()
                    .curve(d3.curveCardinal)
                    .x(function (d) { return Global_this.x(d.time); })
                    .y(function (d) { return Global_this.y(d.value); });

        Global_this.plot.append("path")
                  .data([e.Data])
                  .attr("class", "line path" + e.name_tweaked) 
                  .attr("d", line)
                  .style("stroke", e.color)
                  .attr("clip-path", "url(#clip" + Global_this.chartname + ")")
                  .attr("isActive", "active");
        
        // =================line circles when hover over lines=========================
        if (Global_this.focus == null) {
          //new focus
          Global_this.focus = Global_this.plot.append("g")
                                        .attr("class", "focus")
                                        .style("display", "none")
                                        .attr("clip-path", "url(#clip)");
        }
        var focus_row = Global_this.focus
                                  .append("circle")
                                  .attr('class', 'focus-circle focus-circle-'+e.name_tweaked)
                                  .attr("isActive", "active")
                                  .attr("line", e.name_tweaked)   //data name
                                  .style("fill", e.color)  //data color
                                  .attr("r", 3.5)
                                  .style("stroke", "white")
                                  .style("stroke-width", "1.5");      
    }
    //Rectangle runtime chart
    else {
      Global_this.plot.append("g").attr("class", "Synchronous-Rects Synchronous-Rects"+e.name_tweaked)
                  .attr("isActive", "active")
                  .selectAll("rect")
                  .data(e.Data)
                  .enter().append("rect")
                  .attr("class", "rect Synchronous-Rect" + e.name_tweaked)
                  .attr("x", function (d) { 
                    return Global_this.x(d.startdate)<0 ? 0 : Global_this.x(d.startdate); 
                  })
                  .transition(t)
                  .attr("y", function (d) { return Global_this.y(e.name.substring(0, e.name.indexOf("("))); })
                  .attr("width", function (d) { 
                                sd = Global_this.x(d.startdate)<0 ? 0 : Global_this.x(d.startdate);
                                return (Global_this.x(d.enddate)) > Global_this.width ? Global_this.width-sd : (Global_this.x(d.enddate) - sd); 
                              })
                  .attr("fill", e.color)
                  .attr("height", Global_this.y.bandwidth());
                  // .style("stroke", "white")
                  // .style("stroke-width", "1px");
      Global_this.plot.selectAll(".Synchronous-Rects").moveToFront();
    }
  })
}

/* Zoom action.
*  Hide line when click on legend.
*  Update y-axis 
*  Tooltip action
*/
Action = function(chart){
  //Add rectangle for catching mouse position for tooltip
  if(chart.plot.select('.overlay').empty()){
    chart.overlay = chart.plot.append("rect")
                  .attr("class", "overlay")
                  .attr("width", chart.width)
                  .attr("height", chart.height)
                  .call(zoomdrag());
  }
  //zoom
  if (chart.linechart) {
    //line chart
      chart.overlay.on("mouseover", function () { chart.focus.style("display", null); chart.zoom.style("display", null); })
                    .on("mouseout", function () { chart.focus.style("display", "none"); chart.tooltip.style("display", "none"); chart.zoom.style("display", "none"); })
                    .on("mousemove", mousemove);
  }else{
    //runtime chart
      chart.overlay.attr("height", chart.height)
                    .on("mouseover", function () { chart.zoom.style("display", null); })
                    .on("mouseout", function () { chart.tooltip.style("display", "none"); chart.zoom.style("display", "none"); })
                    .on("mousemove", mousemove1);
  }
  chart.zoomBox.moveToFront(); //move zoomBox to the top
  chart.overlay.moveToFront(); //move overlay to the top
  chart.tooltip.moveToFront(); //move tooltip to the top

  function zoomdrag() {
    var start_H, end_H;
    return d3.drag()
      .on("start", function (d) {
        start_H = d3.event.x;
      })
      .on("drag", function (d) {
        var width = Math.abs(start_H - d3.event.x),
          _width = start_H - d3.event.x // left or right zoom box
        // zoom box direction
        if (_width < 0) chart.zoomBox.attr("width", width).attr("height", chart.height).attr("x", start_H).attr("y", 0);
        if (_width > 0) chart.zoomBox.attr("width", width).attr("height", chart.height).attr("x", d3.event.x).attr("y", 0);
      })
      .on("end", function (d) {
        chart.zoomBox.attr("width", null).attr("height", null).attr("x", null).attr("y", null)
        end_H = d3.event.x;
        // creating zoombox rect.
        var x_min, x_max;
        // zoombox direction:
        if (start_H < end_H && start_H !== end_H) {
          x_min = xAxis.scale().invert(start_H);
          x_max = xAxis.scale().invert(end_H);
        } else {
          x_min = xAxis.scale().invert(end_H);
          x_max = xAxis.scale().invert(start_H);
        }
        // For smaller screen disable zoom for 50px. This helps in scrolling the page on touch
        var disable_zoom = 5;
        if (parseInt(chart.svg.attr("width")) < 500) {
          disable_zoom = 50;
        }
        if (Math.abs(start_H - end_H) > disable_zoom) { // avoid drag on double click or small mouse moves
          // updating domains - rescaling
          charts.forEach(function (c_curr) {
            c_curr.x.domain([x_min, x_max]);
            d3.selectAll("#zoom-out-d3").classed('disabled', false);    //Activate zoom out button
            d3.selectAll("#zoom-out-d3").classed('active', false);      //Remove active class
            updateData(updated_chart_data[c_curr.chartname], c_curr, update_yaxis = true);
          });
        }
      })
  }
  // Highly edited version of http://jsfiddle.net/JYS8n/389/
  // =========================== Tooltip when hover ===========================
  // For lines
  function mousemove() {
    var bisectDate = d3.bisector(function (d) { return d.time; }).left,
        bisectValue = d3.bisector(function (d) { return d.value; }).left,
        x0 = chart.x.invert(d3.mouse(this)[0]), // mouse position on X axis
        y0 = chart.y.invert(d3.mouse(this)[1]); // mouse position on Y axis
    //array containing closest value-time for each device
    var ds = updated_chart_data[chart.chartname].map(function (e) {
                var i = bisectDate(e.Data, x0, 1),  //find the value out of this array of device names, which is closest to the current x position
                  d0 = e.Data[i - 1],
                  d1 = e.Data[i];
                if (typeof d0 !== "undefined" && typeof d1 !== "undefined") {
                  d0["name"] = e.name; d0["name_tweaked"] = e.name_tweaked; d0["color"] = e.color;
                  d1["name"] = e.name; d1["name_tweaked"] = e.name_tweaked; d1["color"] = e.color;
                  return x0 - d0.time > d1.time - x0 ? d1 : d0;
                }
                else
                  return "empty";
              });
    //remove from array if "empty"
    ds = ds.filter(function (e) {   
            return e != "empty";
          }).sort(function (a, b) { return (a.value > b.value) ? 1 : ((b.value > a.value) ? -1 : 0); });   //sort by values
    //find the value out of this array of device names, which is closest to the current y position
    var j  = bisectValue(ds, y0, 1),    
        d0 = ds[j - 1],
        d1 = ds[j];
    if (typeof d0 !== "undefined" || typeof d1 !== "undefined") {
        if (typeof d0 == "undefined") {
          var d = d1;
        } else if (typeof d1 == "undefined") {
          var d = d0;
        } else {
          var d = y0 - d0.value > d1.value - y0 ? d1 : d0;
        }
        var name = d.name;
        var name_tweaked = d.name_tweaked;
        var curr_color = d.color;
        chart.focus.selectAll('.focus-circle').attr('visibility', 'hidden');  //hide all circles
        //if the line is not hidden
        if (d3.select('circle[line="' + name_tweaked + '"]').attr("isActive") == "active") {
          chart.focus.select('circle[line="' + name_tweaked + '"]')
                    .attr("transform", "translate(" + chart.x(d.time) + "," + chart.y(d.value) + ")")    //show only focused circle
                    .attr('visibility', 'visible');
          chart.tooltip.style("display", null); //show tooltip
          chart.tooltip_title.html(d3.timeFormat("%a %I:%M %p %b-%d")(new Date(d.time)))
          // tooltip info
          chart.tooltip.select('div[lineN="test"]').html(name.replace(/\_/ig, " ").replace(/rc/ig, " rc "));
          var value = chart.linechart ? d3.format(",.2f")(d.value) + "°F" : (d.value == 0 ? "Off" : (d.value == 1 ? "On" : "")) ;
          chart.tooltip.select('div[lineV="0"]').html(value);
          //tooltip circle color
          chart.tooltip.select('div .circle').style("background", curr_color);
          // tooltip position
          if (chart.width<500) {  //Mobile view - 250 is the width of tooltip
            if (chart.y(y0) < chart.height/2) { //top half
              chart.tooltip.style("left", 0+"px"); chart.tooltip.style("top", (chart.y(y0)+20)+"px");
            }else{  //bottom half
              chart.tooltip.style("left", 0+"px"); chart.tooltip.style("top", (chart.y(y0)-50)+"px");
            }
          }else{  //Desktop view 
            if (chart.x(d.time) < chart.width - 500) {  // left side
              chart.tooltip.style("left", (chart.x(d.time)+40)+"px"); chart.tooltip.style("top", (chart.y(y0)/2)+"px");
            } else {  // right side
              chart.tooltip.style("left", (chart.x(d.time)-240)+"px"); chart.tooltip.style("top", (chart.y(y0)/2)+"px");
            }
          }
          
        }
        // zoom lines position -|-
        chart.zoom.select('line[direction="H"]').attr("y1", chart.y(y0)).attr("y2", chart.y(y0));
        chart.zoom.select('line[direction="V"]').attr("x1", chart.x(d.time)).attr("x2", chart.x(d.time));
    }
  }
  // For rectangles
  function mousemove1() {
    // custom invert function
    var xy      = d3.mouse(d3.select(this).node())  // X and Y position of mouse pointer

    // Using this example - https://bl.ocks.org/shimizu/808e0f5cadb6a63f28bb00082dc8fe3f
    var domain  = chart.y.domain();
    var range   = chart.y.range()
    var scale   = d3.scaleQuantize().domain(d3.extent(range)).range(domain.reverse());
    var dy      = scale(xy[1]); //Get current device name using y position of mouse pointer
    var y0      = chart.y(dy) + (chart.y.bandwidth()/2);

    var x0      = chart.x.invert(xy[0]);
    var tweaked = function(z){ return z.substring(0, z.indexOf("(")); } //this removes the parentheses and id from device name
    var bisectStartDate = d3.bisector(function (d) { return d.startdate; }).left,
         bisectEndDate  = d3.bisector(function (d) { return d.enddate; }).left;

    //array containing closest data for each device in x direction
    // ======= Don't change anything, it working somehow. Probably magic!! ======
    var dx = updated_chart_data[chart.chartname].map(function (e) {
                if (tweaked(e.name) == dy) {  //if dy is equal to the current device, find proper rectangle in x direction
                    var i = bisectStartDate(e.Data, x0, 0),  //find the value out of this array of device names, which is closest to the current x position
                    j = bisectEndDate(e.Data, x0, 0),
                    d0 = e.Data[i - 1], //left rectangle start date
                    d1 = e.Data[i],     //right rectangle start date
                    d2 = e.Data[j-1],   //left rectangle end date
                    d3 = e.Data[j];     //right rectangle end date
                    if (typeof d0 !== "undefined" && typeof d1 !== "undefined") {
                      d0["name"] = e.name; d0["name_tweaked"] = e.name_tweaked; d0["color"] = e.color;
                      d1["name"] = e.name; d1["name_tweaked"] = e.name_tweaked; d1["color"] = e.color;
                      // return x0 - d0.startdate > d1.startdate - x0 ? d0 : d0;
                      return d0;
                    }
                    if (typeof d2 !== "undefined" && typeof d3 !== "undefined") {
                      d2["name"] = e.name; d2["name_tweaked"] = e.name_tweaked; d2["color"] = e.color;
                      d3["name"] = e.name; d3["name_tweaked"] = e.name_tweaked; d3["color"] = e.color;
                      return x0 - d2.enddate > x0 - d3.enddate ? d3 : d2;
                    }
                    // For single data value(rectangle) in x-direction
                    if (typeof d0 !== "undefined" && typeof d3 !== "undefined") {
                      d0["name"] = e.name; d0["name_tweaked"] = e.name_tweaked; d0["color"] = e.color;
                      d3["name"] = e.name; d3["name_tweaked"] = e.name_tweaked; d3["color"] = e.color;
                      return x0 - d0.enddate > x0 - d3.enddate ? d3 : d0;
                    }
                    else
                      return "empty";
                }else{
                  return "empty";
                }
              });
    // cleanup the dx array
    // remove from array all the "empty" string
    d = dx.filter(function (e) {   
            return e != "empty";
          })[0]

    if (typeof d !== "undefined") {
      var name          = d.name;
      var name_tweaked  = d.name_tweaked;
      var curr_color    = d.color;
        chart.tooltip.style("display", null); //show tooltip
        chart.tooltip_title.html(name+" - "+d.description)
        // tooltip info
        chart.tooltip.select('div .starttime').html("<b>Start - </b>" + d3.timeFormat("%a %I:%M %p %b-%d")(new Date(d.startdate)));
        chart.tooltip.select('div .endtime').html("<b>End - </b>" + d3.timeFormat("%a %I:%M %p %b-%d")(new Date(d.enddate)));
        //tooltip duration
        chart.tooltip.select('div .duration').html("<b>Duration - </b>"+d.duration);
        
        // tooltip position
        if (chart.width<500) {  //Mobile position. 250 is the width of tooltip
          if (chart.y(dy) < chart.height/2) { //top half
            chart.tooltip.style("left", 0+"px"); chart.tooltip.style("top", ((chart.y(dy)/2)+ chart.tooltip.node().offsetHeight)+"px");
          }else{  //bottom half
            chart.tooltip.style("left", 0+"px"); chart.tooltip.style("top", ((chart.y(dy)/2)- chart.tooltip.node().offsetHeight)+"px");
          }
        }else{  // Desktop position
          if (chart.x(d.startdate) < chart.width - 500) {
            chart.tooltip.style("left", chart.x(x0)+40+"px"); chart.tooltip.style("top", (chart.y(dy)/2)+"px");
          } else {
            chart.tooltip.style("left", chart.x(x0)-220+"px"); chart.tooltip.style("top", (chart.y(dy)/2)+"px");
          }
        }
      //zoom lines position -|-start
      chart.zoom.select('line[direction="H"]').attr("y1", y0).attr("y2", y0);
      chart.zoom.select('line[direction="V"]').attr("x1", chart.x(x0)).attr("x2", chart.x(x0));
    }
  }

  // ===================================== LEGEND CLICK ==========================================
  // show hide lines and the points when click on legends
  d3.selectAll(".box-row-" + chart.chartname).on("click", function (d) {
    device_name         = d3.select(this).select(".SyncLegend-name").text();
    device_name_tweaked = d3.select(this).attr("box");

      //if legend is not active
      if (d3.select(this).attr("isActive") == "inactive") {
        d3.select(this).attr("isActive", "active");  //activate legend
        //Change opacity of legend to 1 from legend boxes
        var curr_color = d3.select(this).selectAll('li > .box').style("background");
        d3.select(this).style("opacity", 1);
        // Add the device that is previously been hidden
        updated_chart_data[chart.chartname].push({
              "name": device_name,
              "name_tweaked": device_name_tweaked,
              "Data": chart.data.filter(function (e) {
                        return e.name_tweaked == device_name_tweaked;
                      })[0]["Data"],
              "color": curr_color
            });

        if (chart.linechart) {
          chart.plot.selectAll(".line.path" + device_name_tweaked).style("opacity", 1).attr("isActive", "active");   //show lines
          chart.focus.select('circle[line="' + device_name_tweaked + '"]') //show circle on the line
                      .attr('isActive', 'active')
                      .attr('visibility', 'visible');
          updateChart(updated_chart_data[chart.chartname], chart, update_yaxis = true);
        }else{
          chart.plot.select(".Synchronous-Rects" + device_name_tweaked).attr("isActive", "active"); // Active rectangle container
          chart.plot.selectAll(".Synchronous-Rect" + device_name_tweaked).style("opacity", 1);   //show rectangles  
          chart.plot.selectAll(".background-rect" + device_name_tweaked).style("opacity", 0.2);   //show background rectangle       
          updateChart(updated_chart_data[chart.chartname], chart, update_yaxis = true);   
        }
      }
      //if legend is active, hide the line/rect when clicked on the legend
      else if(d3.select(this).attr("isActive") == "active"){
        d3.select(this).attr("isActive", "inactive");  //De-activate legends
        //fade box color in legends
        d3.select(this).style("opacity", 0.4);
        updated_chart_data[chart.chartname] = updated_chart_data[chart.chartname].filter(function (e) {
                                                return e.name_tweaked != device_name_tweaked;
                                              });
        if (chart.linechart) {
          chart.plot.selectAll(".line.path" + device_name_tweaked).style("opacity", 0).attr("isActive", "inactive");   //hide lines
          chart.focus.select('circle[line="' + device_name_tweaked + '"]') //hide circle on the line
                      .attr('isActive', 'inactive')
                      .attr('visibility', 'hidden');
          updateChart(updated_chart_data[chart.chartname], chart, update_yaxis = true);
        }else{
          chart.plot.select(".Synchronous-Rects" + device_name_tweaked).attr("isActive", "inactive"); // InActive rectangle container
          chart.plot.selectAll(".Synchronous-Rect" + device_name_tweaked).style("opacity", 0);   //hide rectangles        
          chart.plot.selectAll(".background-rect" + device_name_tweaked).style("opacity", 0);   //hide background rectangle  
          updateChart(updated_chart_data[chart.chartname], chart, update_yaxis = true); 
        }
      }
      //Missing report data - If legend device is missing d3.select(this).attr("isActive") == "missing"
      else{
        device_id   = d3.select(this).attr("dev_id");
        command     = d3.select(this).attr("command");
        chart_name  = d3.select(this).attr("c_name");
        name        = d3.select(this).select(".SyncLegend-name").text();
        var request = $.ajax({
          url: system_id + '/ajax',
          data: {
            id              : device_id,
            command         : command,
            missing         : true
          },
          method: "POST",
          complete: function (data, textStatus, jqXHR) {
            
          },
          success: function (data, textStatus, jqXHR) {
            if (typeof data.date != 'undefined') {
              $('#myModal-'+chart_name).modal('show');
              $('#myModal-'+chart_name +" .modal-body-title").text(name);
              $('#myModal-'+chart_name +" .modal-body-values").html("Last Reported time - <b>"+new Date(data.date)+"</b><br><p>Navigate to zone status page to know more about the issue</p>")
            }
          }
        });
      }
  })

  // ====================== DOUBLE CLICK =====================
  //revert zoom back to original scale - zoom out
  chart.plot.on('dblclick', refreshChart);
  d3.selectAll("#zoom-out-d3").on('click', refreshChart); //zoom button

  function refreshChart() {
    d3.selectAll("#zoom-out-d3").classed('disabled', true);    //Activate zoom out button
    d3.selectAll("#zoom-out-d3").classed('active', false);     //Remove active class
    // reverting everything to its initial scale
    charts.forEach(function (chart) {
      //only update chart when there's a change in xMin and xMax
      if (chart.x.domain()[0] != Date.parse(chart.x_min) && chart.x.domain()[1] != Date.parse(chart.x_max)) {
        chart.x.domain([chart.x_min, chart.x_max]);
        updateData(updated_chart_data[chart.chartname], chart, update_yaxis = false);
      }
    });
  }
}

/*  data = current chart lines/rectangles that are active && not hidden by legends && is inside the selected x-axis range 
    chart = current chart object
    update_yaxis = true when chart is zoomed out or zoomed in
*/
updateChart = function(data, chart, update_yaxis){
  var yMin_temp = 999999999,
      yMax_temp = 0,
      t = chart.plot.transition().duration(350);
  if (typeof chart.overlay != "undefined") {
    chart.overlay.attr("height", chart.height); //change overlay rectangle height 
  }
  //line chart
  if(chart.linechart){
      if(update_yaxis){  //when zoom in  - find new yMin and yMax from new data set
        data.map(function (e) {
          e.Data.forEach(function (d) {
            yMin_temp = (d.value < yMin_temp) ? d.value : yMin_temp;
            yMax_temp = (d.value > yMax_temp) ? d.value : yMax_temp;
          })
        });
        chart.y_min = yMin_temp;
        chart.y_max = yMax_temp;
      }else{  //when zoomed out - keep the original y_min and y_max
        yMin_temp = chart.y_min;
        yMax_temp = chart.y_max;
      }
      
      chart.y_padding = (chart.y_max - chart.y_min)*0.05;
      chart.y.domain([chart.y_min - chart.y_padding, chart.y_max + chart.y_padding]);
      var yAxis = d3.axisLeft(chart.y)
                    .tickValues(chart.y.ticks(5).concat(chart.y.domain()))
                    .tickSize(-chart.width);
      var line  = d3.line()
                    .x(function (d) { return chart.x(d.time); })
                    .y(function (d) { return chart.y(d.value); });
      chart.plot.select(".Synchronous-axis--y")
                .transition(t)
                .call(yAxis);
      // reverting lines - redraw lines without that particular line which is inactive
      for (var i = 0; i < data.length; i++) {
        var curr_device_name = data[i].name_tweaked;
        //If line is not present, then create line
        if (chart.plot.select(".line.path" + curr_device_name).empty()) {
          chart.plot.append("path")
                    .data([data[i].Data])
                    .attr("class", "line path" + data[i].name_tweaked) 
                    .transition(t)
                    .attr("d", line)
                    .style("stroke", data[i].color)
                    .attr("clip-path", "url(#clip" + chart.chartname + ")")
                    .attr("isActive", "active");
        }else{
          chart.plot.select(".line.path" + curr_device_name)
                    .data([data[i].Data])
                    .transition(t)
                    .attr("d", line);
        }
        
        chart.plot.select(".line.path" + curr_device_name).moveToFront();
        chart.overlay.moveToFront(); //move overlay to the top
      }
  }
  // relay runtime rectangles
  else{
    //update y-axis
    devices_Arr = data.map(function (d) {         //get all the device names without the trailing id number. (This is how it's showing on y-axis)
      return d.name.substring(0, d.name.indexOf("("));
    });   
    chart.y.domain(devices_Arr);
    yAxis = d3.axisLeft(chart.y);
    chart.plot.select(".Synchronous-axis--y").transition(t).call(yAxis).selectAll("text").remove();

    // reverting rectangles - redraw rectangles without that particular rectangles which is inactive
    for (var i = 0; i < data.length; i++) {
      if (data[i].Data.length<=0) {
        chart.plot.select(".Synchronous-Rects"+ data[i].name_tweaked).attr("isActive", "inactive"); //Inactivate that rectangle container
      }
      if (chart.boxWrapper.select('[box = ' + data[i].name_tweaked + ']').attr("isActive") == "active" && data[i].Data.length>0){
        chart.plot.select(".background-rect"+data[i].name_tweaked)
                  .transition(t)
                  .attr("y", chart.y(data[i].name.substring(0, data[i].name.indexOf("("))))
                  .attr("width", chart.width)
                  .attr("height", chart.y.bandwidth());

        chart.plot.select(".Synchronous-Rects"+ data[i].name_tweaked).attr("isActive", "active"); //Inactivate rectangle container
        chart.plot.selectAll(".Synchronous-Rect"+ data[i].name_tweaked)
                  .data(data[i].Data)
                  .attr("x", function (d) { 
                                return chart.x(d.startdate)<0 ? 0 : chart.x(d.startdate); 
                              })
                  .transition(t)
                  .attr("y", chart.y(data[i].name.substring(0, data[i].name.indexOf("("))))
                  .attr("width", function (d) { 
                                    sd = chart.x(d.startdate)<0 ? 0 : chart.x(d.startdate);
                                    width = (chart.x(d.enddate)) > chart.width ? chart.width-sd : chart.x(d.enddate)-sd;
                                    if(width < 0){ return 0}
                                    else{ return width; }
                                })
                  .style("opacity", 1)
                  .attr("height", chart.y.bandwidth());
      }
    }
    
  }
  
}

/*  data = current chart lines/rectangles that are active and not hidden by legends
    chart = current chart object
    update_yaxis = true when chart is zoomed in
*/
function updateData(data, chart, update_yaxis) {
  var t = chart.plot.transition().duration(350);
  chart.plot.select(".Synchronous-axis--x")
          .transition(t)
          .call(xAxis.tickValues(
            chart.x.ticks(10)
              .concat(chart.x.domain()[1])
          ).tickFormat(d3.timeFormat("%I:%M %p %b-%d")))
          .selectAll("text")
          .style("text-anchor", "end")
          .attr("dx", "-.8em")
          .attr("dy", ".15em")
          .attr("transform", "rotate(-45)")
          .style("font-weight", "bold");
  var zoomedData;
  if (update_yaxis) { //If zoomed in - change data to the one selected x-axis range
    if (chart.linechart) {
      zoomedData = data.map(function (e) {
                    var data = Object.create(e);
                    data.Data = data.Data.filter(function (e1) {
                      if (Date.parse(e1.time) > chart.x.domain()[0] && Date.parse(e1.time) < chart.x.domain()[1]) {
                        return e1;
                      }
                      return;
                    });
                    data.name = e.name;
                    data.name_tweaked = e.name_tweaked;
                    return data;
                  });
    }else{
      zoomedData = data.map(function (e) {
                    chart.plot.selectAll(".Synchronous-Rect"+ e.name_tweaked).style("opacity", 0); //hide all the rectangles
                    var data = Object.create(e);
                    data.Data = data.Data.filter(function (e1) {
                                  left = chart.x.domain()[0],
                                  right = chart.x.domain()[1],
                                  startdate = Date.parse(e1.startdate),
                                  enddate = Date.parse(e1.enddate);
                                  if ((startdate > left && enddate < right) || (startdate < left && enddate > left) || (startdate < right && enddate > right)) {  // middle || left || right
                                    return e1;
                                  }
                                  return;
                                });
                    data.name = e.name;
                    data.name_tweaked = e.name_tweaked;
                    return data;
                  });
    }
  } else { 
    zoomedData = data;
  }
  updateChart(zoomedData, chart, true); //true because- always update y-axis
}

/********************************
 *  Filter out zone based onn the zone ID :
 *  Find appropriate data. clear all the old lines and circles.
 *  Hide the chart that don't have any data. Fade the legends.
 *  Draw lines in chart where data is present
 *  hasx_axis = true -----> when any one of the temperature chart(low/medium/high) has x_axis
 * *****************************/
Chart.prototype.zonefilter = function(zoneID, hasx_axis){
  // Only applied to Temperature chart
  if (this.linechart) {
    updated_chart_data[this.chartname] = this.data.filter(function (e) {
                                            return e.zone == zoneID || zoneID == "-1";  //"-1" is "All zones" 
                                          });
    this.clearChart();
    d3.select(this.element.parentNode).style('display', 'none');  // Hide the chart that has no data
    this.boxWrapper.selectAll("li[isActive = 'active']")
                   .style("opacity", 0.4)
                   .attr("isActive", "inactive");  //fade active legends
    
    if (updated_chart_data[this.chartname].length > 0) {
      d3.select(this.element.parentNode).style('display', 'block');  // Show the chart that has data   
      if (zoneID == "-1") { //All zones
          svg_height                = this.svg_height;
          margin_bottom             = this.margin.bottom;
          
          this.resize(this.width, svg_height, margin_bottom, this.x_axis);
      }else{
        if (hasx_axis && this.x_axis == false) {  //if current chart does not have x_axis
            svg_height                = this.svg_height+95;   //add size to accomodate the new x_axis
            margin_bottom             = 60;                   //Create space for x_axis
            this.resize(this.width, svg_height, margin_bottom, !this.x_axis) // create x_axis
        }
      }
    }  
    this.draw(updated_chart_data[this.chartname]);
    updateChart(updated_chart_data[this.chartname], this, true);
  }
}

/********************************
 *  Change height and width of svg container
 *  Change height and width of clip which hold all the lines inside a chart. 
 *  Change legend container height
 *  Recreate axis
 * *****************************/
Chart.prototype.resize = function(width, svg_height, margin_bottom, x_axis_bool){
  //this.element.style.height = svg_height+"px";      //chart container height
  this.height               = svg_height - this.margin.top - margin_bottom;
  // this.width                = width - this.margin.left - this.margin.right
  this.svg.attr("height", this.height + this.margin.top + margin_bottom);
  // this.svg.attr("width", width + this.margin.left + this.margin.right);
  this.clip.attr("height", this.height);
  // this.clip.attr("width", width);
  if (this.linechart) {
    d3.select(this.legendSelectID).style("height", this.height+"px");  //change legend container height    
  }
  this.RemoveAxis();
  this.CreateAxis(x_axis_bool)
  this.plot.select(".Synchronous-axis--y").moveToBack();
  this.plot.select(".Synchronous-axis--x").moveToBack();
  this.plot.select(".yaxis_label").moveToBack();
}

//Reset chart before loading new data
Chart.prototype.clearChart = function() {
  //this.boxWrapper.selectAll(".box-row").remove();   //remove old li
  this.plot.selectAll("path.line").remove();  //remove old lines
  this.plot.selectAll(".focus-circle").remove();  //remove line circles
  this.plot.selectAll(".Synchronous-Rects").remove(); //remove old rectangles
  //Load Axis
  //this.LoadAxis(this.data);
}

var request1 = [];
function drawTemperature(S_date, E_date, initialize) {
  //for each Temperature range - high, mid, low
  for (var i = 0; i < InitData.Temperature.zone_temp.length; i++) {
    (function (i) {
      var zone_array = InitData.Temperature.zone_temp[i]['zones'],                //array of zone id's inside a temperature range
          chart_name = InitData.Temperature.zone_temp[i]['temp_range'] ? InitData.Temperature.zone_temp[i]['temp_range'] : '',       //low, medium or high
          xMin = parseTime(S_date + " 00:00:00"),
          xMax = parseTime(E_date + " 23:59:59"); 
      //first time loading chart
      if (initialize) {
          // If no Relay/Digital chart and the current temperature is bottom chart, then create x-axis
          var x_axisObj = false;
          if (!InitData.hasOwnProperty('Relay')) {  
            if(i == InitData.Temperature.zone_temp.length-1){
              x_axisObj = true;
            }
          }
          //All devices in this current zone
          var devices_perzone = {
            "dev_id"   : InitData.Temperature.dev_id.filter(function(e, i){ return zone_array.indexOf(parseInt(InitData.Temperature.zone_id[i]))>=0 }),
            "dev_name" : InitData.Temperature.dev_name.filter(function(e, i){ return zone_array.indexOf(parseInt(InitData.Temperature.zone_id[i]))>=0 }),
            "commands" : InitData.Temperature.commands.filter(function(e, i){ return zone_array.indexOf(parseInt(InitData.Temperature.zone_id[i]))>=0 }),
            "zone"     : InitData.Temperature.zone_id.filter(function(e, i){ return zone_array.indexOf(parseInt(InitData.Temperature.zone_id[i]))>=0 }),
          }
            
          charts.push(new Chart({
            charttype       : document.querySelector("#tempchart" + chart_name).dataset.charttype,  //SynchronousChart
            chartname       : chart_name,
            element         : document.querySelector("#tempchart" + chart_name),
            legendSelectID  : document.querySelector("#legend-container-" + chart_name),
            legendposition  : "Right",
            margin          : { top: 10, right: 10, bottom: x_axisObj ? 60 : 10, left: 30 },
            x_axis          : x_axisObj, // Boolean. Only create x-axis once for synchronous chart
            x_min           : xMin,
            x_max           : xMax,
            axis_label      : null,
            linechart       : true,
            y_scale         : d3.scaleLinear(),
            data            : [],
            AllDevices      : devices_perzone   // Append all the data from each zone into AllDevices
          }));
      }
      //Find the chart where we want to work on
      var GlobalChart;
      charts.forEach(function (chart) {
        if (chart_name == chart.chartname) {
          chart.initialized = initialize;   //set true false for initialize
          GlobalChart = chart;
        }
      });
      GlobalChart.x_min = xMin; //Starting date
      GlobalChart.x_max = xMax; //Ending date
      GlobalChart.clearChart(GlobalChart.data);
      
      $('.loadingSynchronous' + chart_name).show(); //loading gif
      $('.nodata' + chart_name).hide();  //"No data to display" message
      $('.loading-message').html('Loading');      //"Loading" message
      var count = 0;
      for (var j = 0; j < zone_array.length; j++) {   //Ajax call for individual zones for each temperature range
        (function (j) {
          request1.push($.ajax({
            url: system_id + '/ajax',
            data: {
              dataFunction    : "Temperature",
              digital         : InitData.Temperature.Digital,
              zoneID          : zone_array[j],
              timeVtime       : false,
              dev_id_name     : GlobalChart.AllDevices,
              startdateToFetch: S_date,
              enddateToFetch  : E_date
            },
            method: "POST",
            complete: function (data, textStatus, jqXHR) {
              count+=1;
              // show "no data" message when data return is undefined for every single zone
              if (data.statusText == "timeout" || (typeof data.responseJSON.data == 'undefined' && zone_array.length == count)) {
                $('.nodata' + chart_name).show();
              }
              if (zone_array.length == count) {   //hide loading icon next to filter button
                $('.top-loader-' + chart_name).hide();
              }else{
                $('.top-loader-' + chart_name).show();  //show loader icon next to filter button
              }
              $('.loadingSynchronous' + chart_name).hide();
            },
            success: function (data, textStatus, jqXHR) {
              if (typeof data.data != 'undefined') {
                var zonedata = GlobalChart.setData(data.data);
                GlobalChart.LoadAxis(zonedata);
                GlobalChart.draw(zonedata);
                Action(GlobalChart);
                updateChart(GlobalChart.data, GlobalChart, true);
              }
            }
          }));
        })(j);
      }    
    })(i);
  }
}

//========================== Relay Chart ==============================
function drawRelay(S_date, E_date, initialize) {
  if (InitData.hasOwnProperty('Relay')) {
    var chart_name = "RelayFunction",
        zone_array = InitData.Relay.zone_id.concat(InitData.Digital.zone_id).unique(); //array of zone id's inside a Relay and Digital range, only unique values
        xMin = parseTime(S_date + " 00:00:00"),
        xMax = parseTime(E_date + " 23:59:59");

    //change Height of chart based on number of devices
    if (initialize) {
        //object containing all the device_name, device_id, zone and commands of relay and digital function types 
        var Relay_Digital = InitData.Relay;
        Relay_Digital.dev_id = Relay_Digital.dev_id.concat(InitData.Digital.dev_id);
        Relay_Digital.dev_name = Relay_Digital.dev_name.concat(InitData.Digital.dev_name);   
        Relay_Digital.zone_id = Relay_Digital.zone_id.concat(InitData.Digital.zone_id);  
        Relay_Digital.commands = Relay_Digital.commands.concat(InitData.Digital.commands);  

        charts.push(new Chart({
          charttype       : document.querySelector("#chartRelay").dataset.charttype,  //SynchronousChart
          chartname       : chart_name,
          element         : document.querySelector("#chartRelay"),
          legendSelectID  : document.querySelector("#legend-container-Relay"),
          legendposition  : "Bottom",
          margin          : { top: 10, right: 10, bottom: 60, left: 30 },
          x_axis          : true, // Only create x-axis once for synchronous chart
          x_min           : xMin,
          x_max           : xMax,
          axis_label      : "Relay",
          linechart       : false,
          y_scale         : d3.scaleBand().padding(0.1),
          data            : [],
          initialized     : initialize,
          AllDevices      : Relay_Digital
        }));
    }
    
    //Find the chart where we want to work on
    var GlobalChart;
    charts.forEach(function (chart) {
      if (chart_name == chart.chartname) {
        chart.initialized = initialize;   //set true false for initialize
        GlobalChart = chart;
      }
    });
    GlobalChart.x_min = xMin; //Start date
    GlobalChart.x_max = xMax; //End date
    GlobalChart.clearChart(GlobalChart.data);  //Reset chart before loading new data

    $('.loadingRelay').show(); //loading gif
    $('.nodataRelay').hide();  //"No data to display" message
    $('.loading-message').html('Loading');      //"Loading" message
    for (var j = 0; j < zone_array.length; j++) {   //loop through individual zones if that chart has multiple zones in it
      (function (j) {
        var request = $.ajax({
          url: system_id + '/ajax',
          data: {
            dataFunction: ["Relay", "Digital"],
            Digital     : InitData.Relay.Digital,
            zoneID      : zone_array[j],
            dev_id_name : GlobalChart.AllDevices,
            timeVtime   : false,
            startdateToFetch: S_date,
            enddateToFetch: E_date
          },
          method: "POST",
          complete: function (data, textStatus, jqXHR) {
            if (data.statusText == "timeout" || typeof data.responseJSON.data == 'undefined') {
              $('.nodataRelay').show();
            }
            $('.loadingRelay').hide();
          },
          success: function (data, textStatus, jqXHR) {
            if (typeof data.data != 'undefined') {
              var zonedata = GlobalChart.setData(data.data);
              GlobalChart.LoadAxis(zonedata);
              GlobalChart.draw(zonedata);
              Action(GlobalChart);
              updateChart(GlobalChart.data, GlobalChart, true);
            }
          },error: function(e) {
            console.log('Error!', e);
        }
        });
      })(j);
    }
  }
}

// ============================= Load Temperature and Relay for today ==================================
drawTemperature(currentTime.yyyymmdd(), currentTime.yyyymmdd(), true);
drawRelay(currentTime.yyyymmdd(), currentTime.yyyymmdd(), true);




// Will use a pie chart in the future, hopefully
function relayPieChart() {
  var data = [10, 20, 100];

  var width = $("#pieChartRelay").width(),
    height = $("#pieChartRelay").height(),
    radius = Math.min(width, height) / 2;

  var color = d3.scaleOrdinal()
    .range(["#98abc5", "#8a89a6", "#7b6888"]);

  var arc = d3.arc()
    .outerRadius(radius - 10)
    .innerRadius(0);

  var labelArc = d3.arc()
    .outerRadius(radius - 40)
    .innerRadius(radius - 40);

  var pie = d3.pie()
    .sort(null)
    .value(function (d) { return d; });

  var svg = d3.select("#pieChartRelay").append("svg")
    .attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

  var g = svg.selectAll(".arc")
    .data(pie(data))
    .enter().append("g")
    .attr("class", "arc");

  g.append("path")
    .attr("d", arc)
    .style("fill", function (d) { return color(d.data); });

  g.append("text")
    .attr("transform", function (d) { return "translate(" + labelArc.centroid(d) + ")"; })
    .attr("dy", ".35em")
    .text(function (d) { return d.data; });
}




// ================================== Runtime Bar Chart starts here ==================================
var RuntimeBarChart = function(opts){
  //========================= remove element before updating ============================
  // d3.select(opts.element).select("svg").remove();
  d3.select(opts.legendSelectID).select("ul").remove();
  d3.select(opts.element).select(".tooltip-obj").remove();

  //variables
  this.element        = opts.element;
  this.charttype      = opts.charttype;
  this.legendSelectID = opts.legendSelectID;
  this.margin         = opts.margin;
  this.data           = [];
  this.start_date     = d3.timeParse("%Y-%m-%d")(opts.start_date);
  this.end_date       = d3.timeParse("%Y-%m-%d")(opts.end_date);
  this.width          = $(this.element).width() - this.margin.left - this.margin.right;
  this.height         = $(this.element).height() - this.margin.top - this.margin.bottom;
  this.svg            = d3.select(this.element).select('svg')
                          .attr("width", this.width + this.margin.left + this.margin.right)
                          .attr("height", this.height + this.margin.top + this.margin.bottom);
  this.plot           = this.svg.append("g")
                            .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

  this.x              = d3.scaleBand().range([0, this.width]).padding(0.1);
  this.y              = d3.scaleLinear().rangeRound([this.height, 0]);
  this.y_max_bar      = 0;
  this.x_line         = d3.scaleTime().range([0, this.width]);     //x-axis property for "Outside" temp line
  this.y_line         = d3.scaleLinear().range([this.height, 0]);  //y-axis property for "Outside" temp line

  this.barcolors      = ["#c8b384", "#dd7788", "#a5dad2", "#b1cc9f", "#84b032", "#f1dd40", "#f69431", "#f36e4b", "#f9b5b2", "#7a9460", "#223333"];
  this.colors         = d3.scaleOrdinal().range(this.barcolors);
  this.boxWrapper     = d3.select(this.legendSelectID)
                          .append('ul')
                          .attr('class', 'legend-ul box-wrapper'); //legends
  this.BarDateArray   = opts.BarDateArray;  //for x-axis
  this.Bar_group      = opts.stackedBar_group;  //group of bars inside each column(staked bars for each date)
  this.tooltip        = d3.select(this.element)
                          .append("div")
                          .attr("class", 'tooltip-obj')
                          .style("position", "absolute")
                          .style("display", "none");
  this.tooltip_wrapper= this.tooltip.append('div')
                            .attr('class', 'tool-tip');
  this.tooltip_title  = this.tooltip_wrapper.append("div")
                            .attr("class", "time title");
  var tooltip_table   = this.tooltip_wrapper.append("div")
                            .attr("class", "table")
                            .style('margin-bottom', '0px')
                            .style("display", "table");
  var tooltip_row     = tooltip_table.append("div")
                            .attr("class", "tooltip-row row")
                            .style("display", "table-cell");
  tooltip_row.append("div")
                  .attr("class", "name")
                  .style('word-wrap', 'break-word')
                  .style("width", "100px");
  tooltip_row.append("div")
                  .attr("class", "value")
                  .attr("lineV", 0);
}

/**
 * Create legend list from device_names array in data object
 */
RuntimeBarChart.prototype.createLegends = function(data){
  Global_this = this;   //chart object
  this.dev_namesArr_tweaked = data.map(function (name) { //array of all the devices names with removed spaces
                                return removeSpecialChar(name)
                            });
  // ======================================= LEGENDS =======================================
  data.map(function (name) {
    var color = Global_this.colors(name);
    var classname = 'box-row';
    if (name == "Outside" || name == "Setpoint") {
      color = name == "Outside" ? '#103e60' : '#5DA5DA';
      classname = 'box-row-line';
    }
    var li = Global_this.boxWrapper.append("li")
                                .attr("class", "legend "+classname)
                                .attr("id", "id" + removeSpecialChar(name))
                                .attr("isActive", "active");
    
    //legend box
    li.append("span")
      .attr("class", "legend box")
      .attr("box", removeSpecialChar(name))
      .style("background", color)
      .style("border", "1px solid " + color);
    //legend name
    li.append("span")
      .attr("class", "legend name")
      .style("font-size", "1.1rem")
      .html(name);
  })
}

/**
 * Create x axis for bar chart and temperature chart.
 * Create bar-axis--y, y axis for bar chart
 * Create line-axis-y, y axis for line chart
 */
RuntimeBarChart.prototype.createAXIS = function(){
  Global_this = this;   //chart object
  //========================= X-AXIS ============================
  //Add all the dates from start date to end date into an array
  this.BarDateArray = [];
  this.BarDateArray.push(this.start_date.yyyymmdd());
  var nextdate = this.start_date;
  while (nextdate < this.end_date) {
    nextdate = d3.timeDay.offset(nextdate, 1);
    this.BarDateArray.push(nextdate.yyyymmdd());  //add next day to array
  }
  this.x.domain(this.BarDateArray); //x-axis property for bar chart

  this.xMax = parseTime(this.end_date.yyyymmdd() + " 23:59:59");
  this.xMin = parseTime(this.start_date.yyyymmdd() + " 00:00:00");
  this.x_line.domain([this.xMin, this.xMax]);  //x-axis property for "Outside" and "Setpoint" temp line
   // add x-axis
  this.plot.append("g")
      .attr("class", "axis bar-axis--x")
      .attr("transform", "translate(0," + this.height + ")")
      .call(this.BarDateArray.length > 40 ? d3.axisBottom(Global_this.x).tickValues(Global_this.x.domain().filter(function (d, i) { return !(i % 10) })) : d3.axisBottom(Global_this.x))    //remove some ticks to make it clean when filter by year
      .selectAll("text")
      .style("text-anchor", "end")
      .style("stroke", "black")
      .attr("dx", "-.8em")
      .attr("dy", ".15em")
      .attr("transform", "rotate(-45)");

  // ======================== Y-AXIS DOMAIN ===============================
  this.y.domain([0, 100]);
  // ============== Y-AXIS ================
  this.y_line.domain([40, 100]); 
  //left y-axis
  this.left_yaxis = this.plot.append("g")
                        .attr("class", "axis bar-axis--y")
                        .call(d3.axisLeft(this.y))
                        .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", "0.71em")
                        .attr("text-anchor", "end")
                        .attr("fill", "#000")
                        .attr("stroke", "#000")
                        .style("font-size", "12px")
                        .text("Hours:Minutes");
  //right y-axis
  this.right_yaxis = this.plot.append("g")
                        .attr("class", "axis line-axis--y")
                        .attr("transform", "translate(" + this.width + ", 0)")
                        .call(d3.axisRight(this.y_line))
                        .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", -6)
                        .attr("dy", "-0.61em")
                        .attr("text-anchor", "end")
                        .attr("fill", "#000")
                        .text("Temperature");
}

/**
 * Update y axis. 
 * y bar axis is created by converting seconds to HH:MM:SS format
 * y line axis is created by getting temperature reading from data object
 */
RuntimeBarChart.prototype.updateAXIS = function(data){
  // ======================== Y-AXIS DOMAIN ===============================
  // LEFT Y-AXIS -> get the max totalhours value 
  //Add y0(startting y location for a bar) and y1(ending location of bar in y-axis) to the data object
  data.bars.forEach(function (d) {
    var y0_sec = 0;
    d.y_hours = d.map(function (inner_d) {
      inner_d.duration = inner_d.duration.split(':').reduce(function(acc, time){ return (60 * acc) + +time } ); //convert time from HH:MM:SS to 
      var y0 = y0_sec;
      inner_d.y0 = y0_sec;
      var y1_sec = y0_sec += +inner_d.duration;
      inner_d.y1 = y1_sec;
      return { y0: y0, y1: y1_sec };
    });
    d.totaltime = d.y_hours[d.y_hours.length - 1].y1 + d.y_hours[d.y_hours.length - 1].y1 / 5;   //add 20%(1/5) padding on top of the bar chart
  });
  this.y_max_bar = d3.max(data.bars, function (d) { return d.totaltime; });
  this.y.domain([0, this.y_max_bar]);
  // ============== Y-AXIS ================
  // format the data for right y-axis
  data.lines.forEach(function (d) {
    d.time = parseTime(d.time);
    d.setpoint = +d.setpoint;
    d.temp = +d.temp;
  });
  var y_min = Math.min(d3.min(data.lines, function (d) { return d.setpoint; }), d3.min(data.lines, function (d) { return d.temp; }));
  var y_max = Math.max(d3.max(data.lines, function (d) { return d.setpoint; }), d3.max(data.lines, function (d) { return d.temp; }));
  this.y_line.domain([y_min - 5, y_max + 5]); //add some padding
  //left y-axis
  this.left_yaxis = this.plot.select(".bar-axis--y")
                        .call(d3.axisLeft(this.y).tickFormat(function (d) {
                          return y_max < 60 ? convertTime(d) : convertTime(d).slice(0, -3);   //if time is less than a minute, include seconds to y-axis
                        }));
  //right y-axis
  this.right_yaxis = this.plot.select(".line-axis--y")
                        .attr("transform", "translate(" + this.width + ", 0)")
                        .call(d3.axisRight(this.y_line));
}

/* Draw shapes like outisde temp lines and runtime bar and setpoint lines 
*  Also update data object with current data.
*/
RuntimeBarChart.prototype.drawSHAPES = function(data){
  Global_this = this;
  this.data = data;
  var t = d3.transition().duration(500).ease(d3.easeLinear); //transition
  // ===================== Draw bars ===================== 
  //Bar_group is the group that contains all the other stacked bars for one single day
  this.Bar_group = this.plot.selectAll(".state")
                      .data(data.bars)
                      .enter()
                      .append("g")
                      .attr("class", "g")
                      .attr("transform", function (d) { return "translate(" + "0" + ",0)"; });

  this.Bar_group.each(function (data1, i) {
    //add rectangle
    rectangles = d3.select(this).selectAll(".bar" + i).data(data1)
                  .enter().append("rect")
                  .attr("class", function (d) { return "bar" + i + " bar" + removeSpecialChar(d.name); })
                  .attr("x", function (d) { return Global_this.x(d.Day); })
                  .attr("y", Global_this.height)
                  .attr("width", Global_this.x.bandwidth())
                  .attr("height", 0)
                  .attr("fill", function (d) { return Global_this.colors(d.name); })
                  .transition(t)
                  .attr("y", function (d) { return Global_this.y(d.y1); }) //rect starts at y1 location
                  .attr("height", function (d) { return Global_this.y(d.y0) - Global_this.y(d.y1); }); //height of that rectangle is y - y0 location
  })

  // ===================== Draw lines ===================== 
  this.focus = this.plot.append("circle")
                  .attr("r", 4.5)
                  .style("fill", "none")
                  .style("stroke", "black")
                  .style('display', 'none');

  // define the Outside line
  var valueline = d3.line()
                    .x(function (d) { return Global_this.x_line(d.time); })
                    .y(function (d) { return Global_this.y_line(d.temp); });

  var temperature_line = this.plot.append("path")
                              .data([data.lines])
                              .attr("class", "line Outside-bar-path")
                              .style("stroke", "#103e60")
                              .attr("d", valueline);

  this.plot.append('g')
          .attr('class', 'Outside-bar-circle-group bar-circle-group')
          .selectAll('.Outside-bar-circle')
          .data(data.lines)
          .enter()
          .append('circle')
          .attr("class", "Outside-bar-circle")
          .attr('r', 1.5)
          .attr("cx", function (dd) { return Global_this.x_line(dd.time) })
          .attr("cy", function (dd) { return Global_this.y_line(dd.temp) })
          .attr("fill", "#103e60")
          .style("stroke", "transparent")
          .style("stroke-width", "20px");

  // define the Setpoint line
  var valueline1 = d3.line()
                    .x(function (d) { return Global_this.x_line(d.time); })
                    .y(function (d) { return Global_this.y_line(d.setpoint); });

  var setpoint_line = this.plot.append("path")
                          .data([data.lines])
                          .attr("class", "line Setpoint-bar-path")
                          .style("stroke", "#5DA5DA")
                          .style("stroke-dasharray", ("3, 3"))
                          .style("stroke-opacity", 0.5)
                          .attr("d", valueline1);
  this.plot.append('g')
          .attr('class', 'Setpoint-bar-circle-group bar-circle-group')
          .selectAll('.Setpoint-bar-circle')
          .data(data.lines)
          .enter()
          .append('circle')
          .attr("class", "Setpoint-bar-circle")
          .attr('r', 1.5)
          .attr("cx", function (dd) { return Global_this.x_line(dd.time) })
          .attr("cy", function (dd) { return Global_this.y_line(dd.setpoint) })
          .attr("fill", "#5DA5DA")
          .style("stroke", "transparent")
          .style("stroke-width", "20px");
}

RuntimeBarChart.prototype.relayBarChartResize = function(){
  Global_this = this;
  this.width = $(this.element).width() - this.margin.left - this.margin.right;
  this.svg.attr("width", this.width + this.margin.left + this.margin.right);

  this.x.range([0, this.width]);
  this.x_line.range([0, this.width]);     //x-axis property for "Outside" temp line

  this.plot.select(".bar-axis--x")
          .call(Global_this.BarDateArray.length > 40 ? d3.axisBottom(Global_this.x).tickValues(Global_this.x.domain().filter(function (d, i) { return !(i % 10) })) : d3.axisBottom(Global_this.x))    //remove some ticks to make it clean when filter by year
          .selectAll("text")
          .style("text-anchor", "end")
          .style("stroke", "black")
          .attr("dx", "-.8em")
          .attr("dy", ".15em")
          .attr("transform", "rotate(-45)")

  //right y-axis
  this.right_yaxis = this.plot.select(".line-axis--y")
                        .attr("transform", "translate(" + this.width + ", 0)")
                        .call(d3.axisRight(this.y_line));

  if (this.Bar_group != null) {
    //resize bars
      if (!this.Bar_group.empty()) {
        this.Bar_group.each(function (data1, i) {
          //edit rectangle
          d3.select(this).selectAll(".bar" + i)
                        .attr("x", function (d) { return Global_this.x(d.Day); })
                        .attr("width", Global_this.x.bandwidth())
                        .attr("y", function (d) { return Global_this.y(d.y1); }) //rect starts at y1 location
                        .attr("height", function (d) { return Global_this.y(d.y0) - Global_this.y(d.y1); }); //height of that rectangle is y - y0 location
        })
      }
  }
  
  // redraw the Outside line
  var valueline = d3.line()
                    .x(function (d) { return Global_this.x_line(d.time); })
                    .y(function (d) { return Global_this.y_line(d.temp); });
  var temperature_line = this.plot.select(".Outside-bar-path")
                            .attr("d", valueline);
  this.plot.selectAll('.Outside-bar-circle')
          .attr("cx", function (dd) { return Global_this.x_line(dd.time) })
          .attr("cy", function (dd) { return Global_this.y_line(dd.temp) });
  
  // redraw the Setpoint line
  var valueline1 = d3.line()
                      .x(function (d) { return Global_this.x_line(d.time); })
                      .y(function (d) { return Global_this.y_line(d.setpoint); });
  var setpoint_line = this.plot.select(".Setpoint-bar-path")
                          .attr("d", valueline1);
  this.plot.selectAll('.Setpoint-bar-circle')
          .attr("cx", function (dd) { return Global_this.x_line(dd.time) })
          .attr("cy", function (dd) { return Global_this.y_line(dd.setpoint) });
}

RuntimeBarChart.prototype.clearChart = function(){
  this.plot.selectAll("path.line").remove();  //remove old lines
  this.plot.selectAll("rect").remove();  //remove all the bars
  this.plot.selectAll(".bar-circle-group").remove();  //remove circle on temperature lines
  this.boxWrapper.selectAll(".legend").remove();  // remove legend li from ul
  this.plot.selectAll(".bar-axis--x").remove(); //remove x-axis
  this.plot.selectAll(".bar-axis--y").remove(); //remove y-axis bar
  this.plot.selectAll(".line-axis--y").remove(); //remove y-axis line
}
/***************************************************************
*  Show tooltip for Bars.
*  Show tooltip for lines.
*  Legend click actions for bar chart and line chart
***************************************************************/
function BarchartActions(chart){
  var t = d3.transition().duration(500).ease(d3.easeLinear); //transition

  // Show tooltip when hover over rectangles
  chart.Bar_group.each(function(data, i){
    chart.plot.selectAll(".bar" + i)
          .on("mouseover", function (d) {
            var xPosition = parseFloat(d3.select(this).attr("x")) + chart.x.bandwidth() / 2;
            var yPosition = parseFloat(d3.select(this).attr("y"));
            // move tooltip to little left when hovering over rightmost element
            if (xPosition > chart.width - 180) {
              xPosition = chart.width - 180;
            }
            chart.tooltip.style("display", "block").style("left", xPosition + "px").style("top", yPosition -45 + "px");
            chart.tooltip_title.html(d.Day+"-"+d.name);
            chart.tooltip.select('.name').html(d.duration < 60 ? "Seconds" : "Hours")
            chart.tooltip.select('div[lineV="0"]').html(d.duration < 60 ? d.duration : convertTime(d.duration));
          })
          .on("mouseout", function (d) { chart.tooltip.style("display", "none") })
  })

  // Legend clicks for line chart inside bar chart
  dev_names = chart.dev_namesArr_tweaked;
  chart.boxWrapper.selectAll('.box-row-line')
                  .on("mouseover", function(){
                      //gray out the lines that is not equal to legend device id
                      for (i = 0; i < dev_names.length; i++) {
                        if (chart.boxWrapper.select('[id = id' + dev_names[i] + ']').attr("isActive") == "active") { 
                          if (dev_names[i] != this.id.substring(2)) {
                            chart.plot
                                .selectAll(".bar" + dev_names[i])
                                .style("opacity", "0.4");
                            chart.plot
                                .selectAll("."+dev_names[i] +"-bar-circle")
                                .style("opacity", "0.4");
                          }else{
                            chart.plot
                                .selectAll("."+dev_names[i] +"-bar-circle")
                                .style("opacity", "1");                            
                          }
                      }
                    }
                    
                  })
                  .on("mouseout", function(){
                      for (i = 0; i < dev_names.length; i++) {
                        if (chart.boxWrapper.select('[id = id' + dev_names[i] + ']').attr("isActive") == "active") {                          
                          chart.plot
                                .selectAll(".bar" + dev_names[i])
                                .style("opacity", "1");
                          chart.plot
                                .selectAll("."+dev_names[i] +"-bar-circle")
                                .style("opacity", "1");
                        }
                      }
                  })
                  .on("click", function () {
                    if (d3.select(this).attr("isActive") == "active") {   //hide the line
                      d3.select(this)
                        .attr("isActive", "activeNow")
                        .style("opacity", 0.5);
                      selected_device = this.id.substring(2);
                      chart.plot.select("."+selected_device +"-bar-circle-group").remove();
                      chart.plot.selectAll("."+selected_device +"-bar-path").style("opacity", "0");

                    }else { //deactivate legend. show the line
                      if (d3.select(this).attr("isActive") == "activeNow") {
                        d3.select(this)
                          .attr("isActive", "active")
                          .style("opacity",1);
                        selected_device = this.id.substring(2);
                        chart.plot.append('g')
                                  .attr('class', selected_device+'-bar-circle-group')
                                  .selectAll('.'+selected_device+'-bar-circle')
                                  .data(chart.data.lines)
                                  .enter()
                                  .append('circle')
                                  .attr("class", selected_device+"-bar-circle")
                                  .attr('r', 1.5)
                                  .attr("cx", function (dd) { return Global_this.x_line(dd.time) })
                                  .attr("cy", selected_device == "Outside" ? function (dd) { return Global_this.y_line(dd.temp) } : function (dd) { return Global_this.y_line(dd.setpoint) })
                                  .attr("fill", selected_device == "Outside" ? "#103e60" : '#5DA5DA')
                                  .style("stroke", "transparent")
                                  .style("stroke-width", "20px")
                                  .on('mouseover', mouseover(selected_device))
                                  .on('mouseout', function (d) { chart.tooltip.style("display", "none"); chart.focus.style('display', 'none') });;
                        chart.plot
                              .selectAll("."+selected_device +"-bar-path")
                              .style("opacity", "1");
                      }
                    } //end activeNow check
                  });

  // Legend clicks for bar chart
  chart.boxWrapper.selectAll('.box-row')
                  .on("mouseover", function(){
                    //gray out the bars that is not equal to legend device id
                    for (i = 0; i < dev_names.length; i++) {
                      if (dev_names[i] != this.id.split("id").pop()) {
                        d3.select("#BarChartRelay svg").selectAll(".bar" + dev_names[i]).style("opacity", "0.4");
                      }else{
                        d3.select("#BarChartRelay svg").selectAll(".bar" + dev_names[i]).style("opacity", "1");
                      }
                    }
                  })
                  .on("mouseout", function(){
                    for (i = 0; i < dev_names.length; i++) {
                        d3.select("#BarChartRelay svg").selectAll(".bar" + dev_names[i]).style("opacity", "1");
                    }
                  })
                  .on("click", function () {
                    if (d3.select(this).attr("isActive") == "active") {   //click function only on active item
                      d3.select(this)
                        .attr("isActive", "activeNow")
                        .style("stroke", "black")
                        .style("stroke-width", 2);
                      selected_device = this.id.split("id").pop();
                      plotSingle(selected_device, dev_names);
                      //gray out the others legends
                      for (i = 0; i < dev_names.length; i++) {
                        if (d3.select('[id = id' + dev_names[i] + ']').attr("isActive") == "active") {
                          d3.select('[id = id' + dev_names[i] + ']')
                            .style("opacity", 0.5)
                            .attr("isActive", "inactive");
                        }
                      }
                    }else { //deactivate
                      if (d3.select(this).attr("isActive") == "activeNow") {//active square selected; turn it OFF
                        d3.select(this).style("stroke", "none");

                        //restore remaining boxes to normal opacity
                        for (i = 0; i < dev_names.length; i++) {
                          d3.select('[id = id' + dev_names[i] + ']')
                            .attr("isActive", "active")
                            .style("opacity", 1);
                        }
                        //restore plot to original
                        restorePlot(dev_names);
                      }
                    } //end activeNow check
                  });
  
        function plotSingle(selected_device, devices) {
            //erase all but selected bars by setting opacity to 0
            for (i = 0; i < devices.length; i++) {
              if (devices[i] != selected_device) {
                d3.select("#BarChartRelay svg").selectAll(".bar" + devices[i])
                  .transition(t)
                  .style("display", "none");
              }
            }
            visible_rect = [];  //Only the device that is selected from legend is visible_rect
            orig_val = [];      //Original rectangle width, height, duration and all other property
            max_duration = [];  //for readjusting y-axis
            //Lower the bars to start on x-axis.
            //Bar_group is a bar that contains an array of small rectangles stacked on top of each other for 1 day
              chart.Bar_group.each(function (k, i) {
                k.map(function (d) {
                  curr_device = removeSpecialChar(d.name);
                  if (curr_device == selected_device) {
                    d.height = d3.select(".bar" + i + ".bar" + selected_device).attr("height");
                    orig_val.push(d);
                    max_duration.push(d.duration); //convert time from HH:MM:SS to seconds
                    //store selected rect in array
                    visible_rect.push(".bar" + i + ".bar" + selected_device);
                  }
                })
              });
            //change y-axis
            var max = d3.max(max_duration);
            chart.y.domain([0, max]);
            d3.select(".bar-axis--y")
              .transition(t)
              .call(d3.axisLeft(chart.y).tickFormat(function (d) {
                return max < 60 ? convertTime(d) : convertTime(d).slice(0, -3);   //if time is less than a minute, include seconds to y-axis
              }));

            //reposition selected bars, moving starting point of each rect to the x-axis
            visible_rect.map(function (r, i) {
              d3.select(r)
                .transition(t)
                .ease(d3.easeBounce)
                .delay(250)
                .attr("y", chart.y(orig_val[i].duration))
                .attr("height", chart.y(0) - chart.y(orig_val[i].duration));
            })
        }
          
        function restorePlot(devices) {
            //reset y-axis to original
            chart.y.domain([0, chart.y_max_bar]);
            d3.select(".bar-axis--y")
              .transition(t)
              .call(d3.axisLeft(chart.y)
                .tickFormat(function (d) {
                  return chart.y_max_bar < 60 ? convertTime(d) : convertTime(d).slice(0, -3);   //if time is less than a minute, include seconds to y-axis
                }));

            //restore shifted bars to original position
            visible_rect.map(function (d, i) {
              d3.select(d)
                .transition(t)
                .attr("y", chart.y(orig_val[i].y1))
                .attr("height", orig_val[i].height);
            });
            //restore opacity of erased bars
            for (i = 0; i < devices.length; i++) {
              d3.select("#BarChartRelay svg").selectAll(".bar" + devices[i])
                .transition(t)
                .style("display", "block");
            }
        }
  //lines over bar chart - show tooltip
  chart.plot.selectAll(".Outside-bar-circle")
            .on('mouseover', mouseover("Outside"))
            .on('mouseout', function (d) { chart.tooltip.style("display", "none"); chart.focus.style('display', 'none') });
  chart.plot.selectAll(".Setpoint-bar-circle")
            .on('mouseover', mouseover("Setpoint"))
            .on('mouseout', function (d) { chart.tooltip.style("display", "none"); chart.focus.style('display', 'none') });
  function mouseover(lineName){
    return function(d){
      var yaxis_val = lineName == "Outside" ? chart.y_line(d.temp) : chart.y_line(d.setpoint);
      chart.focus.style('display', null)
                  .attr("transform", "translate(" + chart.x_line(d.time) + "," + yaxis_val + ")");
      var left_pos = chart.x_line(d.time);
      if (left_pos > chart.width - 180) {
        left_pos = chart.width - 180;
      }
      chart.tooltip.style("display", "block")
      .style("left", left_pos + "px")
      .style("top", yaxis_val - 60 + "px");
      chart.tooltip_title.html(d3.timeFormat("%Y-%m-%d %H:%M")(d.time));
      chart.tooltip.select('.name').html(lineName+" Temperature");
      chart.tooltip.select('div[lineV="0"]').html(d.temp + "°F");
    }
  }
}


function relayBarChart(d3startdate, d3enddate, initialize) {
  var ChartElem = document.getElementById("BarChartRelay");
  if (ChartElem != null) {
    if (initialize) {
      //create chart skeleton
      runtimebar = new RuntimeBarChart({
        element         : "#BarChartRelay", //contains svg, loading div, nodata div, legend container and tooltip
        charttype       : document.querySelector("#BarChartRelay").dataset.charttype,
        legendSelectID  : "#legend-container-RuntimeRelay",
        margin          : { top: 20, right: 30, bottom: 80, left: 50 },
        BarDateArray    : [],
        stackedBar_group: null,
        start_date       : d3startdate,
        end_date         : d3enddate
      });
    }else{
      runtimebar.clearChart();
    }
    
    runtimebar.start_date     = d3.timeParse("%Y-%m-%d")(d3startdate);
    runtimebar.end_date       = d3.timeParse("%Y-%m-%d")(d3enddate);
    runtimebar.createAXIS();
    //=========================== AJAX CALL - get the data =======================
    $('.nodataRelayBar').hide();    //hide "no data" message
    $('.loadingRelayBar').show();   //show loading indicator
    var request = $.ajax({
    url: system_id + '/ajax',
    data: {
      dataFunction: ["Relay"],
      Digital     : InitData.Relay.Digital,
      dev_id_name : InitData.Relay,
      timeVtime   : true,
      startdateToFetch: d3startdate,
      enddateToFetch: d3enddate
    },
    method: "POST",
    complete: function (data, textStatus, jqXHR) {
      if (data.statusText == "timeout" || typeof data.responseJSON.bars == 'undefined') {
        $('.nodataRelayBar').show();
      }
      $('.loadingRelayBar').hide();
    },
    success: function (data, textStatus, jqXHR) {
      if (typeof data.bars != 'undefined') {
        runtimebar.createLegends(data['device_names']);
        runtimebar.updateAXIS(data);
        runtimebar.drawSHAPES(data, runtimebar);
        BarchartActions(runtimebar);
      }
    }
    });
  }
}


// Start loading bar chart with 1 week time period 
var last_week = new Date(currentTime.getTime() - 7 * 24 * 60 * 60 * 1000);
d3startdate = last_week.yyyymmdd();
relayBarChart(d3startdate, currentTime.yyyymmdd(), initialize = true);

//=============================== FILTER - time range select =================================================
function TimeSelect(selected, Chartname) {
  var currentOption = selected.options[selected.selectedIndex];
  if (currentOption.value == "Custom") {
    selected.selectedIndex = 5; //select a disabled option, we could use custom option more than once.
    $(currentOption.dataset.target).modal();
  } else {
    PerChartFilter(currentOption.value, Chartname);
  }
}
//=============================== FILTER - time range select  =================================================
function PerChartFilter(timerangeString, Chartname) {
  //======================= get Start End Date =======================
  var startDate = '';
  var d = new Date();
  var endDate = d.yyyymmdd();
  switch (timerangeString) {
    case "Today":
      startDate = d.yyyymmdd();
      break;
    case "Last Week":
      d = new Date(d.getTime() - 7 * 24 * 60 * 60 * 1000);
      startDate = d.yyyymmdd();
      break;
    case "Last two Weeks":
      d = new Date(d.getTime() - 14 * 24 * 60 * 60 * 1000);
      startDate = d.yyyymmdd();
      break;
    case "Last Month":
      d.setMonth(d.getMonth() - 1);
      startDate = d.yyyymmdd();
      break;
    case "Last Year":
      d.setFullYear(d.getFullYear() - 1);
      startDate = d.yyyymmdd();
      break;
    case "Custom":
      startDate = document.getElementsByName('start-date-modal' + Chartname)[0].value;
      endDate = document.getElementsByName('end-date-modal' + Chartname)[0].value;
      break;
    default:
      break;
  }
  document.getElementsByName('start-date-modal' + Chartname)[0].value = startDate;  //add these values to the custom modal window
  document.getElementsByName('end-date-modal' + Chartname)[0].value = endDate;    //add these values to the custom modal window
  var sd_obj = new Date(startDate);
  var ed_obj = new Date(endDate);
  //======================= ~get Start End Date~ =======================
  if (ed_obj.getTime() - sd_obj.getTime() < 0) {  //for custom time selection modal
    var alertdiv = '<div class="alert alert-warning alert-dismissable fade in"><a class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Warning!</strong> Start time should always be smaller than end time.</div>';
    $('#modal-body-' + Chartname).prepend(alertdiv);
  } else {
    $('#close' + Chartname).click(); //close modal
    if (Chartname == "RuntimeBar"){
      relayBarChart(startDate, endDate, initialize = false);
    }
    else {
        while(request1.length){
        request1.pop().abort();
      }
      
      // setTimeout(function(){
        drawTemperature(startDate, endDate, false);
        drawRelay(startDate, endDate, false);
      // }, 5000) 
      
      
    }
  }
} 

/* ========================================================================= 
   *************************   Water Widget ************************* 
========================================================================= */  
if (InitData.hasOwnProperty('Water')) {
  // Create WaterWidget Class
  var WaterWidget = function(opts){
    this.minValue          = IsUndefined(opts.minValue)? 0 : opts.minValue, // The gauge minimum value.
    this.maxValue          = IsUndefined(opts.maxValue)? 100 : opts.maxValue, // The gauge maximum value.
    this.circleThickness   = IsUndefined(opts.circleThickness) ? 0.05 : opts.circleThickness, // The outer circle thickness as a percentage of it's radius.
    this.circleFillGap     = IsUndefined(opts.circleFillGap) ? 0.05 : opts.circleFillGap, // The size of the gap between the outer circle and wave circle as a percentage of the outer circles radius.
    this.circleColor       = IsUndefined(opts.circleColor) ? "#178BCA" : opts.circleColor, // The color of the outer circle.
    this.waveHeight        = IsUndefined(opts.waveHeight) ? 0.05 : opts.waveHeight, // The wave height as a percentage of the radius of the wave circle.
    this.waveCount         = IsUndefined(opts.waveCount) ? 1 : opts.waveCount, // The number of full waves per width of the wave circle.
    this.waveRiseTime      = IsUndefined(opts.waveRiseTime) ? 3000 : opts.waveRiseTime, // The amount of time in milliseconds for the wave to rise from 0 to it's final height.
    this.waveAnimateTime   = IsUndefined(opts.waveAnimateTime) ? 18000 : opts.waveAnimateTime, // The amount of time in milliseconds for a full wave to enter the wave circle.
    this.waveRise          = IsUndefined(opts.waveRise) ? true : opts.waveRise, // Control if the wave should rise from 0 to it's full height, or start at it's full height.
    this.waveHeightScaling = IsUndefined(opts.waveHeightScaling) ? true : opts.waveHeightScaling, // Controls wave size scaling at low and high fill percentages. When true, wave height reaches it's maximum at 50% fill, and minimum at 0% and 100% fill. This helps to prevent the wave from making the wave circle from appear totally full or empty when near it's minimum or maximum fill.
    this.waveAnimate       = IsUndefined(opts.waveAnimate) ? true : opts.waveAnimate, // Controls if the wave scrolls or is static.
    this.waveOffset        = IsUndefined(opts.waveOffset) ? 0 : opts.waveOffset, // The amount to initially offset the wave. 0 = no offset. 1 = offset of one full wave.
    this.textVertPosition  = IsUndefined(opts.textVertPosition) ? .5 : opts.textVertPosition, // The height at which to display the percentage text withing the wave circle. 0 = bottom, 1 = top.
    this.textSize          = IsUndefined(opts.textSize) ? 0.8 : opts.textSize, // The relative height of the text to display in the wave circle. 1 = 50%
    this.valueCountUp      = IsUndefined(opts.valueCountUp) ? true : opts.valueCountUp, // If true, the displayed value counts up from 0 to it's final value upon loading. If false, the final value is displayed.
    this.displayUnits      = IsUndefined(opts.displayUnits) ? true : opts.displayUnits, // If true, a % symbol is displayed after the value.
    this.units             = IsUndefined(opts.units) ? "%" : opts.units,
    this.alarm_state       = IsUndefined(opts.alarm_state) ? 0 : opts.alarm_state,
    this.wave_alarm_color  = IsUndefined(opts.wave_alarm_color) ? ["#178BCA", "#178BCA", "#f87086"] : opts.wave_alarm_color, // The color of the value text when the wave overlaps it. Also this is the same as wave color
    this.text_color        = IsUndefined(opts.text_color) ? ["#A4DBf8", "#A4DBf8", "#ebccd1"] : opts.text_color  // The color of the value text when the wave does overlap it.

    function IsUndefined(obj){
      if (typeof obj != "undefined") {
        return false;
      }
      return true;
    }
  }
  // Draw the Widget
  WaterWidget.prototype.createCircle = function(elementId, value){
    var config = this;
    if (config.maxValue < value) {
        config.maxValue = value+100;
    }
    var gauge       = d3.select("#" + elementId);
    var radius      = Math.min(parseInt(gauge.style("width")), parseInt(gauge.style("height")))/2;
    var locationX   = parseInt(gauge.style("width"))/2 - radius;
    var locationY   = parseInt(gauge.style("height"))/2 - radius;
    var fillPercent = Math.max(config.minValue, Math.min(config.maxValue, value))/config.maxValue;

    gauge.selectAll("*").remove();

    var waveHeightScale;
    if(config.waveHeightScaling){
        waveHeightScale = d3.scaleLinear()
            .range([0,config.waveHeight,0])
            .domain([0,50,100]);
    } else {
        waveHeightScale = d3.scaleLinear()
            .range([config.waveHeight,config.waveHeight])
            .domain([0,100]);
    }

    var textPixels          = (config.textSize*radius/2);
    var textFinalValue      = parseFloat(value).toFixed(2);
    var textStartValue      = config.valueCountUp?config.minValue: textFinalValue;
    var unitText            = config.displayUnits?config.units:"";
    var circleThickness     = config.circleThickness * radius;
    var circleFillGap       = config.circleFillGap * radius;
    var fillCircleMargin    = circleThickness + circleFillGap;
    var fillCircleRadius    = radius - fillCircleMargin;
    var waveHeight          = fillCircleRadius*waveHeightScale(fillPercent*100);
    var waveLength          = fillCircleRadius*2/config.waveCount;
    var waveClipCount       = 1+config.waveCount;
    var waveClipWidth       = waveLength*waveClipCount;

    // Rounding functions so that the correct number of decimal places is always displayed as the value counts up.
    var textRounder = function(value){ return Math.round(value); };
    if(parseFloat(textFinalValue) != parseFloat(textRounder(textFinalValue))){
        textRounder = function(value){ return parseFloat(value).toFixed(1); };
    }
    if(parseFloat(textFinalValue) != parseFloat(textRounder(textFinalValue))){
        textRounder = function(value){ return parseFloat(value).toFixed(2); };
    }

    // Data for building the clip wave area.
    var data = [];
    for(var i = 0; i <= 40*waveClipCount; i++){
        data.push({x: i/(40*waveClipCount), y: (i/(40))});
    }

    // Scales for drawing the outer circle.
    var gaugeCircleX = d3.scaleLinear().range([0,2*Math.PI]).domain([0,1]);
    var gaugeCircleY = d3.scaleLinear().range([0,radius]).domain([0,radius]);

    // Scales for controlling the size of the clipping path.
    var waveScaleX = d3.scaleLinear().range([0,waveClipWidth]).domain([0,1]);
    var waveScaleY = d3.scaleLinear().range([0,waveHeight]).domain([0,1]);

    // Scales for controlling the position of the clipping path.
    var waveRiseScale = d3.scaleLinear()
        // The clipping area size is the height of the fill circle + the wave height, so we position the clip wave
        // such that the it will overlap the fill circle at all when at 0%, and will totally cover the fill
        // circle at 100%.
        .range([(fillCircleMargin+fillCircleRadius*2+waveHeight),(fillCircleMargin-waveHeight)])
        .domain([0,1]);
    var waveAnimateScale = d3.scaleLinear()
        .range([0, waveClipWidth-fillCircleRadius*2]) // Push the clip area one full wave then snap back.
        .domain([0,1]);

    // Scale for controlling the position of the text within the gauge.
    var textRiseScaleY = d3.scaleLinear()
        .range([fillCircleMargin+fillCircleRadius*2,(fillCircleMargin+textPixels*0.7)])
        .domain([0,1]);

    // Center the gauge within the parent SVG.
    var gaugeGroup = gauge.append("g")
        .attr('transform','translate('+locationX+','+locationY+')');

    // Draw the outer circle.
    var gaugeCircleArc = d3.arc()
        .startAngle(gaugeCircleX(0))
        .endAngle(gaugeCircleX(1))
        .outerRadius(gaugeCircleY(radius))
        .innerRadius(gaugeCircleY(radius-circleThickness));
    var outerCircle = gaugeGroup.append("path")
        .attr("d", gaugeCircleArc)
        .style("fill", config.circleColor)
        .attr('transform','translate('+radius+','+radius+')');

    // Text where the wave does not overlap.
    var text1 = gaugeGroup.append("text")
        .text(textRounder(textStartValue))
        .attr("class", "liquidFillGaugeText")
        .attr("text-anchor", "middle")
        .attr("font-size", textPixels + "px")
        .attr('transform','translate('+radius+','+textRiseScaleY(config.textVertPosition)+')')
        .style("fill", config.wave_alarm_color[0]);

    // The clipping wave area.
    var clipArea = d3.area()
        .x(function(d) { return waveScaleX(d.x); } )
        .y0(function(d) { return waveScaleY(Math.sin(Math.PI*2*config.waveOffset*-1 + Math.PI*2*(1-config.waveCount) + d.y*2*Math.PI));} )
        .y1(function(d) { return (fillCircleRadius*2 + waveHeight); } );
    var waveGroup = gaugeGroup.append("defs")
        .append("clipPath")
        .attr("id", "clipWave" + elementId);
    var wave = waveGroup.append("path")
        .datum(data)
        .attr("d", clipArea)
        .attr("T", 0);

    // The inner circle with the clipping wave attached.
    var fillCircleGroup = gaugeGroup.append("g")
        .attr("clip-path", "url(#clipWave" + elementId + ")");
    var circle = fillCircleGroup.append("circle")
        .attr("cx", radius)
        .attr("cy", radius)
        .attr("r", fillCircleRadius)
        .style("fill", config.wave_alarm_color[0]);


    // Text where the wave does overlap.
    var text2 = fillCircleGroup.append("text")
        .text(textRounder(textStartValue))
        .attr("class", "liquidFillGaugeText")
        .attr("text-anchor", "middle")
        .attr("font-size", textPixels + "px")
        .style("fill", config.text_color[0])
        .attr('transform','translate('+radius+','+textRiseScaleY(config.textVertPosition)+')');

    // Change wave color depending on alarm_state  
    circle.transition().duration(config.waveRiseTime)
            .style("fill", config.wave_alarm_color[config.alarm_state]);


    // Make the value count up.
    if(config.valueCountUp){
        var textTween = function(){
            var that = d3.select(this),
            i = d3.interpolateNumber(that.text().replace(/,/g, ""), textFinalValue);
            return function(t) { that.text(textRounder(i(t)) + unitText); };
        };
            text1.transition()
                .duration(config.waveRiseTime)
                .tween("text", textTween)
                .style("fill", config.wave_alarm_color[config.alarm_state]);
            text2.transition()
                .duration(config.waveRiseTime)
                .tween("text", textTween)
                .style("fill", config.text_color[config.alarm_state]);
    }

    // Make the wave rise. wave and waveGroup are separate so that horizontal and vertical movement can be controlled independently.
    var waveGroupXPosition = fillCircleMargin+fillCircleRadius*2-waveClipWidth;
    if(config.waveRise){
        waveGroup.attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(0)+')')
            .transition()
            .duration(config.waveRiseTime)
            .attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(fillPercent)+')')
            .on("start", function(){ wave.attr('transform','translate(1,0)'); }); // This transform is necessary to get the clip wave positioned correctly when waveRise=true and waveAnimate=false. The wave will not position correctly without this, but it's not clear why this is actually necessary.
    } else {
        waveGroup.attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(fillPercent)+')');
    }

    if(config.waveAnimate) animateWave();

    function animateWave() {
        wave.attr('transform','translate('+waveAnimateScale(wave.attr('T'))+',0)');
        wave.transition()
            .duration(config.waveAnimateTime * (1-wave.attr('T')))
            .ease(d3.easeLinear)
            .attr('transform','translate('+waveAnimateScale(1)+',0)')
            .attr('T', 1)
            .on('end', function(){
                wave.attr('T', 0);
                animateWave(config.waveAnimateTime);
            });
    }
  }
  // Create Class object. This will call the constructor
  var WaterGauge = new WaterWidget({
    circleThickness : 0.1,
    waveAnimateTime : 2000,
    waveHeight      : 0.2,
    waveCount       : 1,
    units           : InitData.Water.Units,
  });
  if(typeof InitData.Water.data != 'undefined'){
    WaterGauge.alarm_state  = parseInt(InitData.Water.data.alarm_state);
    WaterGauge.maxValue     = parseInt(InitData.Water.data.alarm_high);
    value = parseInt(InitData.Water.data.current_value);
    WaterGauge.createCircle("Waterfillgauge", value);
  }else{
    WaterGauge.createCircle("Waterfillgauge", 0);
  }

  // This will be called when the refresh button on water widget is pressed
  function waterAjax(elem){
    $(elem).addClass('fa-spin');
    $( "#water-loadingmessage" ).show().html("Loading data");
    $("#water-info").hide();
    //Call the weather API. 
    var request = $.ajax({
      url: system_id + '/water',
      method: "POST",
      data : {
        dev_id : InitData.Water.dev_id,
        command : InitData.Water.commands
      },
      success: function (data, textStatus, jqXHR) {
        $(elem).removeClass('fa-spin');
        $("#water-loadingmessage").hide();
        $("#water-info").show();
        $("#waterDate").html(data.datetime);
        $("#waterAlarm").html(data.description);
        // Create WaterWidget Class object. This will call the constructor
        var WaterGauge = new WaterWidget({
          circleThickness : 0.1,
          waveAnimateTime : 2000,
          waveHeight      : 0.2,
          waveCount       : 1,
          units           : InitData.Water.Units,
        });
        if(typeof data != 'undefined'){
          WaterGauge.alarm_state  = parseInt(data.alarm_state);
          WaterGauge.maxValue     = parseInt(data.alarm_high);
          value = parseInt(data.current_value);
          WaterGauge.createCircle("Waterfillgauge", value);
        }else{
          WaterGauge.createCircle("Waterfillgauge", 0);
        }
      },error: function (xhr, ajaxOptions, thrownError) {
        $("#water-loadingmessage").html("No Data found");
      }
    });
  }
}
