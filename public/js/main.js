if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}
// addClass using only Javascript
function addClass(element,className) {
  var currentClassName = element.getAttribute("class");
  if (typeof currentClassName!== "undefined" && currentClassName) {
    element.setAttribute("class",currentClassName + " "+ className);
  }
  else {
    element.setAttribute("class",className); 
  }
}
// removeClass using only Javascript
function removeClass(element,className) {
  var currentClassName = element.getAttribute("class");
  if (typeof currentClassName!== "undefined" && currentClassName) {

    var class2RemoveIndex = currentClassName.indexOf(className);
    if (class2RemoveIndex != -1) {
      var class2Remove = currentClassName.substr(class2RemoveIndex, className.length);
      var updatedClassName = currentClassName.replace(class2Remove,"").trim();
      element.setAttribute("class",updatedClassName);
    }
  }
  else {
    element.removeAttribute("class");   
  } 
}

//Check Useragent of browsers. This is useful to differentiate between android, Ios and desktop browsers
function checkUserAgent(val){
  return navigator.userAgent.indexOf(val) >=0;
}

$(document).ready(function () {
  if(typeof(chart_data) !== 'undefined' && typeof chart_data.error == 'undefined' && Object.keys(chart_data.charts).length) {

    $.each(chart_data.charts, function(){
      var plotOptions = eval("(" + this.plotOptions + ")");
      if(typeof(this.yAxisTitle) !== 'undefined') {
        plotOptions.yAxis = {"title": {"text": this.yAxisTitle}};
        // plotOptions.push(type:'datetime');
        if(plotOptions.chart.type == 'columnrange'){
          plotOptions.yAxis.type = 'datetime';
          plotOptions.yAxis.dateTimeLabelFormats = {
            hour: '%l %p',
            minute: '%l:%M %p',
            second: '%l:%M:%S %p'
          }
        }
      }
      var zoneSeries = [];
      var selector = this.selector;

      if(typeof(this.start) !== 'undefined') {
        if(this.start !== this.stop) {
          plotOptions.subtitle = {'text': this.start + ' → ' + this.stop};
        }else{
          plotOptions.subtitle = {'text': this.start};
        }
      }
      if(plotOptions.chart.type == 'column') {
         plotOptions.subtitle = {'text': 'Current Values', 'align': 'center'};
      }

      /* Allow horizontal zooming */
      // plotOptions.chart.zoomType = 'x';

      /* Make the chart as big as it's container */
      plotOptions.chart.width = $(this.selector).outerWidth();

      /*  */
      if(plotOptions.chart.type == 'spline'){
      plotOptions.xAxis = {
        categories:(typeof this.times != 'undefined')?this.times:[],
        labels:{
          staggerLines: 1,
          step: Math.floor(this.times.length/Math.floor($(document).width()/300)) + 1,
        }
      };
    } else if(plotOptions.chart.type == 'columnrange'){
      plotOptions.tooltip = {
        formatter: function () {
          return '<b>' + Highcharts.dateFormat('%I:%M:%S %p', this.point.low) + '</b> - <b>' + Highcharts.dateFormat('%I:%M:%S %p', this.point.high) + '</b>';
        }
      }
    }
      plotOptions.series = [];

      /*
       * Loop through zones adding their devices to the main chart and building a
       * zone-specific chart for each one.
       */
      $.each(this.zones, function(){

        zoneSeries = [];
        var zoneSelector = selector + '-zone-' + this.id;


        if(this.devices) {

          if(plotOptions.chart.type == 'spline') {
            $.each(this.devices, function(){
              /* Add devices in this zone to the main chart */
              var seriesCount = $(plotOptions.series).length;
              plotOptions.series.push({});
              plotOptions.series[seriesCount].name = this.name;
              plotOptions.series[seriesCount].data = this.data;
              plotOptions.series[seriesCount].visible = this.visible;

              /* Collect data from this zone for the zone chart */
              var zoneSeriesCount = $(zoneSeries).length;
              zoneSeries.push({});
              zoneSeries[zoneSeriesCount].name = this.name;
              zoneSeries[zoneSeriesCount].data = this.data;
              zoneSeries[zoneSeriesCount].visible = this.visible;

            });
          } else if(plotOptions.chart.type == 'column') {
            $.each(this.devices, function(){
              /* Add devices in this zone to the main chart */
              var seriesCount = $(plotOptions.series).length;
              // cosajd
              plotOptions.series.push({});
              plotOptions.series[seriesCount].name = this.name;
              plotOptions.series[seriesCount].data = this.data;
              plotOptions.series[seriesCount].visible = this.visible;

              /* Collect data from this zone for the zone chart */
              var zoneSeriesCount = $(zoneSeries).length;
              zoneSeries.push({});
              zoneSeries[zoneSeriesCount].name = this.name;
              zoneSeries[zoneSeriesCount].data = this.data;
              zoneSeries[zoneSeriesCount].visible = this.visible;

            });
          } else if(plotOptions.chart.type == 'columnrange') {
            $.each(this.devices, function() {
              $.each(this.data, function() {
                if(typeof(this.low) !== 'undefined') {
                  parseLow = this.low.split(',');
                  this.low = Date.UTC(parseLow[0], parseLow[1], parseLow[2], parseLow[3], parseLow[4], parseLow[5]);
                }
                if(typeof(this.high) !== 'undefined') {
                  parseHigh = this.high.split(',');
                  this.high = Date.UTC(parseHigh[0], parseHigh[1], parseHigh[2], parseHigh[3], parseHigh[4], parseHigh[5]);
                }
              });
              /* Add devices in this zone to the main chart */
              var seriesCount = $(plotOptions.series).length;
              plotOptions.series.push({});
              plotOptions.series[seriesCount].name = this.name;
              plotOptions.series[seriesCount].data = this.data;
              plotOptions.series[seriesCount].visible = this.visible;

              /* Collect data from this zone for the zone chart */
              var zoneSeriesCount = $(zoneSeries).length;
              zoneSeries.push({});
              zoneSeries[zoneSeriesCount].name = this.name;
              zoneSeries[zoneSeriesCount].data = this.data;
              zoneSeries[zoneSeriesCount].visible = this.visible;

            });
          }
        }

        /* Render the zone chart */
        zoneOptions            = JSON.parse(JSON.stringify(plotOptions));
        zoneOptions.title.text = zoneOptions.title.text + ' - ' + this.name;
        zoneOptions.series     = zoneSeries;
        zoneContainer          = $(zoneSelector);
        if(zoneContainer.length) {
          zoneContainer.highcharts(zoneOptions);
        }

      });




      container = $(selector);
      if(container.length) {
        container.highcharts(plotOptions);
      }


    });
  }


  $(function () {
    $('[data-toggle="popover"]').popover({html: true});
  })



  // Submit a form when a select option is chosen
  $('.selectSubmit').change(function(){
      this.form.submit();
  });


    $("#accordion").accordion({
      active: false,
      collapsible: true,
        header: "h4",
        autoFill:true,
        heightStyle: "content"
    });

    $( ".tabs" ).tabs();
    $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      showSpeed: 1
    });

    $('.js-confirm').click(function(){
        var msg = 'Are you sure about this??';
        if( $(this).attr('data-confirm') !== undefined ){
            msg = $(this).attr('data-confirm');
        }
        return window.confirm(msg);
    });

  if( $('.js-supress-enter').length ) {
    $(window).keydown(function(event) {
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  }


  // Dynamic select to set proper device types when adding a device
  $('select[name="device_types_id"]').on('change', function() {
      var val= $(this).find(":selected").text();
      var target = $(this).closest("div").next().find(":first");
      var modetarget = $(this).closest("div").next().find("select[name*='device_mode']");
      var functiontarget = $(target).closest("div").next().find("select[name*='device_function']");
      $.ajax ({
                  type: 'POST',
                  success: function (data) {
                  if (val == "Wireless Temperature Sensor") {
                      $(modetarget).val("wireless");
                      $(functiontarget).val("Temperature");
                  } else if (val == "Wireless Voltage Sensor") {
                      $(modetarget).val("wireless");
                      $(functiontarget).val("Voltage");
                  } else if (val == "Wireless Light Sensor") {
                      $(modetarget).val("wireless");
                      $(functiontarget).val("Light");
                  } else if (val == "Wireless Occupancy Sensor") {
                      $(modetarget).val("wireless");
                      $(functiontarget).val("Occupancy");
                  } else if (val == "Wired Analog Sensor") {
                      $(modetarget).val("wired");
                      $(functiontarget).val("Analog");
                  } else if (val == "Wired Digital Sensor") {
                      $(modetarget).val("wired");
                      $(functiontarget).val("Digital");
                  } else if (val == "Wired Current Sensor") {
                      $(modetarget).val("wired");
                      $(functiontarget).val("Current");
                  } else if (val == "Current Loop Output") {
                      $(modetarget).val("wired");
                      $(functiontarget).val("Current");
                  } else if (val == "Wired Relay Output") {
                      $(modetarget).val("wired");
                      $(functiontarget).val("Relay");
                  }
              }
      });
  });

  $('.touchscreen img').on('dragstart', function(event) {
    event.preventDefault();
  });


  /*
   * jQuery plugin to create up/down scroll buttons for use on touchscreens
   */
  (function($) {

    $.fn.tapScroll = function( options ) {

      // Establish default settings
      var settings = $.extend({
        movePercent  : 66,
        moveTime     : 600,
        upContent    : "&and;",
        downContent  : "&or;",
        top          : false,
        bottom       : false,
        refresh      : false,
        topContent   : null,
        bottomContent: null,
        refreshContent: null
      }, options);

      return this.each( function() {
        $(this).append("<div id='tapScroll-container'><div id='tapScroll-btngroup' class='btn-group-vertical'></div></div>");

        /* Append elements for navigation triggers */
        // TOP
        if(settings.top) {
          $("#tapScroll-btngroup").append("<button id='tapScroll-top' type='button' class='btn btn-primary tapScroll-buttons' data-toggle='tooltip' data-placement='right' title='Top'>"+settings.topContent+"</button>")
        }
        // UP
        $("#tapScroll-btngroup").append("<button id='tapScroll-up' type='button' class='btn btn-primary tapScroll-buttons' data-placement='right' title='Up'>"+settings.upContent+"</button>");
        // REFRESH
        if(settings.refresh){
          $("#tapScroll-btngroup").append("<button id='tapScroll-refresh' type='button' class='btn btn-primary tapScroll-buttons' data-placement='right' title='Refresh'>"+settings.refreshContent+"</button>");
        }
        // DOWN
        $("#tapScroll-btngroup").append("<button id='tapScroll-down' type='button' class='btn btn-primary tapScroll-buttons' data-placement='right' title='Down'>"+settings.downContent+"</button>");
        // BOTTOM
        if(settings.bottom) {
          $("#tapScroll-btngroup").append("<button id='tapScroll-bottom' type='button' class='btn btn-primary tapScroll-buttons' data-placement='right' title='Bottom'>"+settings.bottomContent+"</button>")
        }


        /* Attach click events to trigger scrolling */
        $("#tapScroll-top").click(function(){
          $.fn.tapScroll.move(-$.fn.tapScroll.checkHeight(), settings.moveTime);
          // $('#tapScroll-btngroup button').tooltip({trigger: 'manual'}).tooltip("show");
        });

        $("#tapScroll-up").click(function(){
          var height = Math.floor( -window.innerHeight * (settings.movePercent/100) );
          $.fn.tapScroll.move(height, settings.moveTime);
        });

        $("#tapScroll-down").click(function(){
          var height = Math.ceil( window.innerHeight * (settings.movePercent/100) );
          $.fn.tapScroll.move(height, settings.moveTime);
        });

        $("#tapScroll-bottom").click(function(){
          $.fn.tapScroll.move($.fn.tapScroll.checkHeight(), settings.moveTime);
        });

        $("#tapScroll-refresh").click(function(){
          location.reload();
        });

      });

    };


    $.fn.tapScroll.move = function(distance, time) {
      $("html, body").animate({
        scrollTop: "+=" + distance
       }, time);
    };
    $.fn.tapScroll.checkHeight = function(){
      return Math.max(
        document.body.scrollHeight,
        document.body.offsetHeight,
        document.documentElement.clientHeight,
        document.documentElement.scrollHeight,
        document.documentElement.offsetHeight
      )
    };

  }(jQuery));
  setTimeout(function(){
    $('.tapScroll-buttons').tooltip({trigger: 'manual'}).tooltip('show');
  },200);


  if( $('.js-5-min-refresh').length ) {
    var auto_refresh = setTimeout(
      function(){
        window.location.href=window.location.href;
      },
      300000
    );
  }

  $(function () {
    $('[data-toggle="tooltip"]:not(.tapScroll-buttons)').tooltip({
      trigger:'hover',
      html:true
    })
  })


  if(document.getElementsByClassName('nav-away-spinner').length) {
    window.onbeforeunload = function() {
      window.onbeforeunload = undefined;

      var innerOverlay = '<div class="page-loader"><div class="throbber"></div></div>'

      var overlay = document.createElement('div');
      overlay.classList.add('overlay');

      overlay.innerHTML = innerOverlay;

      document.body.appendChild(overlay);
    }
  }


  /*
    function fillAlgTempName()

    When a user wishes to save a template, the name of the current algorithm will be the default value of the template name text field.

  */
  function fillAlgTempName() {
    algorithmName = document.getElementById("algorithm_name");
    name = $(algorithmName).val();
    templateName = document.getElementById("name");
    templateName.setAttribute("value",name);

  }

  /*
    function renderDashboardItems(parentID, childrenList)

    A recursive function that displays all known child dashboard items for a particular parent dashboard item.

    parentID: The id of the parent dashboard item.
    childrenList: A string of all the child dashboard items for the chosen parent dashboard item.
  */
  function renderDashboardItems(parentID, childrenList) {
    var children = childrenList.split(' ');
    parent = document.getElementById(dashboardItems[parentID].label.replace(/ /g,'') + "" + dashboardItems[parentID].id);// Parent dashboard item row-item.
    parentContainer = document.getElementById(parent.id+"Children");// Parent dashboard item child container.

  /* Create each child dashboard item of the parent dashboard item. */
    $.each(children,function(index,value) {
      listItem = document.createElement("div");
      listItem.setAttribute("class","sortable-list-group inactive-child-selected-list-group");
      if( parentID != 0) {
        container = document.getElementById(dashboardChildren[value].label.replace(/ /g,'')+ "" + dashboardChildren[value].id +"Children");
      }
      container.appendChild(listItem);
      createDashboardItem(listItem, parent, value);
    });
    return null;
  }

  /*
    function createDashboardItem(value, parent)

    A function that creates a child dashboard items for a particular parent dashboard item.

    value: The dashboardItem ID of the new dashboard item.
    parent: The object of the parent dashboard item.
  */
  function createDashboardItem(listItem,parent, value) {

    child = document.createElement("h4");
    child.id = dashboardItems[value].label.replace(/ /g,'') + "" + dashboardItems[value].id;
    var definedItem;

    if( child.id == parent.id )  {
      child.id = child.id + "Jr";
    }

    if(dashboardItems[value].chart_type !== null && dashboardItems[value].chart_type == dashboardItems[value].chart_type.toUpperCase() ) {
      child.setAttribute("class"      , "sortable-list-item row-detail list-item");
    } else {
      child.setAttribute("class"      , "sortable-list-item row-detail collapsed list-item");
      child.setAttribute("data-toggle", "collapse");
      child.setAttribute("href"       ,"#" + child.id + "Children");
    }

    child.setAttribute("style"      , "padding-left: 10px;");
    child.innerHTML = dashboardItems[value].label;
    listItem.appendChild(child);

    if( typeof(dashboardParents[value]) !== 'undefined' || dashboardItems[value].chart_type == null) { //Drop-down accordian dashboard item
      childContainer = document.createElement("div");
      childContainer.id = child.id + "Children";
      if(dashboardItems[value].chart_type !== null && dashboardItems[value].chart_type == dashboardItems[value].chart_type.toUpperCase() ) {
        childContainer.setAttribute("class","col-xs-12 inactive-parent-selected-list-group");
      } else {
        childContainer.setAttribute("class","container-fluid collapse sortable-list inactive-parent-selected-list-group");
      }
      listItem.appendChild(childContainer);

      childButtonGroup = document.createElement("div");
      childButtonGroup.setAttribute("style","text-align:right");
      childContainer.appendChild(childButtonGroup);

      if( dashboardItems[value].chart_type == null || dashboardItems[value].chart_type !==  dashboardItems[value].chart_type.toUpperCase()) {
        if((typeof(onlyChildren[value]) !== 'undefined' && (dashboardItems[onlyChildren[value]].chart_type !== dashboardItems[onlyChildren[value]].chart_type.toUpperCase() || dashboardItems[onlyChildren[value]].chart_type == null)) || typeof(onlyChildren[value]) === 'undefined') {
          addModals(childButtonGroup,value);
        }
        editModals(childButtonGroup, value);
      } else if( dashboardItems[value].chart_type == 'FURNACE') {
        editMapButton = document.createElement("a");
        editMapButton.setAttribute("class","btn btn-primary btn-xs");
        editMapButton.setAttribute("href" ,document.location.href+"/dashboardmaps");
        editMapButton.innerHTML = "Edit Dashboard Maps";
        childButtonGroup.appendChild(editMapButton);
      }
      if( typeof(dashboardParents[value]) !== 'undefined') {
        renderDashboardItems( value, dashboardParents[value] );
      }
    } else { //Link dashboard Item
      childContainer = document.createElement("div");
      childContainer.id = child.id + "Children";
      if(dashboardItems[value].chart_type !== null && dashboardItems[value].chart_type == dashboardItems[value].chart_type.toUpperCase() ) {
        childContainer.setAttribute("class", "");
      }else{
        childContainer.setAttribute("class", "container-fluid collapse");
      }
      childContainer.setAttribute("style", "margin:10px");
      listItem.appendChild(childContainer);

        childButtonGroup = document.createElement("div");
        childButtonGroup.setAttribute("style","text-align:right");
        childContainer.insertBefore(childButtonGroup, childContainer.firstChild);
        pictureBox = document.createElement("div");
        pictureBox.setAttribute("style","text-align:center");
        childContainer.appendChild(pictureBox);
        picture = document.createElement("img");
        picture.setAttribute("class", "web-snap-shot");

      if( dashboardItems[value].chart_type == null || dashboardItems[value].chart_type !==  dashboardItems[value].chart_type.toUpperCase()) {
       if(typeof(onlyChildren[value]) !== 'undefined' && (dashboardItems[onlyChildren[value]].chart_type !== dashboardItems[onlyChildren[value]].chart_type.toUpperCase() || dashboardItems[onlyChildren[value]].chart_type == null)) {
          addModals(childButtonGroup,value);
        } else{
          picture.setAttribute("src","/images/chart_snap.png");
          pictureBox.appendChild(picture);
        }
        editModals(childButtonGroup, value);
      } else if( dashboardItems[value].chart_type == 'FURNACE') {
        editMapButton = document.createElement("a");
        editMapButton.setAttribute("class","btn btn-primary btn-xs");
        editMapButton.setAttribute("href" ,document.location.href+"/dashboardmaps");
        editMapButton.innerHTML = "Edit Dashboard Maps";
        childButtonGroup.appendChild(editMapButton);
        picture.setAttribute("src","/images/system_maps_snap.png");
         pictureBox.appendChild(picture);
      } else if( dashboardItems[value].chart_type == 'EVENT') {
        picture.setAttribute("src","/images/control_events_snap.png");
         pictureBox.appendChild(picture);
      }
      else if( dashboardItems[value].chart_type == 'ZONE') {
        picture.setAttribute("src","/images/zone_status_snap.png");
         pictureBox.appendChild(picture);
      }
      else if( dashboardItems[value].chart_type == 'ALARM') {
        picture.setAttribute("src","/images/alarms_snap.png");
         pictureBox.appendChild(picture);
      }
      else if( dashboardItems[value].chart_type == 'DEVICE') {
        picture.setAttribute("src","/images/emc_hardware_system_status_snap.png");
         pictureBox.appendChild(picture);
      }

    }

    formOrder = document.createElement("input");
    formOrder.setAttribute('name'  ,dashboardItems[value].label.replace(/ /g,'') + "" + dashboardItems[value].id + 'Order');
    formOrder.setAttribute('id'    ,dashboardItems[value].label.replace(/ /g,'') + "" + dashboardItems[value].id + 'Order');
    formOrder.setAttribute('hidden','true');
    formOrder.setAttribute('value' ,dashboardItems[value].order+'-'+dashboardItems[value].parent_id+'-'+dashboardItems[value].id);
    listItem.appendChild(formOrder);

  }

  /*
    function editModals(node,id)

    A function that creates an edit button and modal for a dashboard item.

    node: The dashboardItem that the button and modal are associated with.
    id: The dashboardItem ID that the button and modal are associated with.
  */
  function editModals(node,id) {

    button = document.createElement("a");
    button.setAttribute("class"      , "btn btn-primary btn-xs");
    button.innerHTML = "Edit";

    if(dashboardItems[id].chart_type != null && dashboardItems[id].chart_type !== dashboardItems[id].chart_type.toUpperCase()){
      button.setAttribute('href',document.location.href+'/../chart/'+ id + '/edit');
      button.innerHTML = "Edit Chart"
      node.appendChild(button);
    }
    else{
      button.setAttribute("style"      , "margin:2px");
      button.setAttribute("data-toggle", "modal");
      button.setAttribute("data-target", "#"+id+"EditModal");
      node.appendChild(button);

      modalGroup = document.getElementById("modals");

      modal = document.createElement("div");
      modal.setAttribute("class"          , "modal fade");
      modal.setAttribute("id"             , ""+id+"EditModal");
      modal.setAttribute("tabindex"       , "-1");
      modal.setAttribute("role"           , "dialog");
      modal.setAttribute("aria-labelledby", "myModalLabel");
      modal.setAttribute("style"          , "color:black; text-align:center");
      modalGroup.appendChild(modal);

        modalDialog = document.createElement("div");
        modalDialog.setAttribute("class", "modal-dialog modal-md");
        modalDialog.setAttribute("role" , "document");
        modal.appendChild(modalDialog);

          modalContent = document.createElement("div");
          modalContent.setAttribute("class", "modal-content");
          modalDialog.appendChild(modalContent);

            modalForm = document.createElement("form");
            modalForm.setAttribute("method"        ,"POST");
            modalForm.setAttribute("action"        ,document.location.href+"/"+id);
            modalForm.setAttribute("accept-charset","UTF-8");
            modalContent.appendChild(modalForm);

              methodForm = document.createElement("input");
              methodForm.setAttribute("name" ,"_method");
              methodForm.setAttribute("type" ,"hidden");
              methodForm.setAttribute("value","PUT");
              modalForm.appendChild(methodForm);

              modalHeader = document.createElement("div");
              modalHeader.setAttribute("class", "modal-header");
              modalForm.appendChild(modalHeader);

                closeButton = document.createElement("button");
                closeButton.setAttribute("type"        , "button");
                closeButton.setAttribute("class"       , "close");
                closeButton.setAttribute("data-dismiss", "modal");
                closeButton.setAttribute("aria-label"  , "Close");
                modalHeader.appendChild(closeButton);

                  X = document.createElement("span");
                  X.setAttribute("aria-hidden","true");
                  X.innerHTML = "&times;";
                  closeButton.appendChild(X);

                modalTitle = document.createElement("h4");
                modalTitle.setAttribute("class","modal-title");
                modalTitle.innerHTML = "Edit Dashboard Item Label";
                modalHeader.appendChild(modalTitle);

              modalBody = document.createElement("div");
              modalBody.setAttribute("class","modal-body row");
              modalForm.appendChild(modalBody);

                IDForm = document.createElement("input");
                IDForm.setAttribute("name" ,"id");
                IDForm.setAttribute("type" ,"hidden");
                IDForm.setAttribute("value",id);
                modalBody.appendChild(IDForm);

                lBuff = document.createElement("div");
                lBuff.setAttribute("class","col-xs-2");
                modalBody.appendChild(lBuff);

                cBuff = document.createElement("div");
                cBuff.setAttribute("class","col-xs-8");
                modalBody.appendChild(cBuff);

                  dashTypeTitle = document.createElement("p");
                  dashTypeTitle.innerHTML = "Dashboard Item Type:";
                  cBuff.appendChild(dashTypeTitle);

                  dashType = document.createElement("p");
                  dashType.setAttribute("style","font-weight:bold");

                  if(dashboardItems[id].chart_type == null) {
                    dashType.innerHTML = "Link";
                  } else {
                    if( dashboardItems[id].chart_type.toUpperCase() == dashboardItems[id].chart_type ) {
                    dashType.innerHTML = dashboardItems[id].chart_type;
                    } else {
                      dashType.innerHTML = dashboardItems[id].chart_type + " Chart";
                    }
                  }
                  cBuff.appendChild(dashType);

                  labelTitle = document.createElement("p");
                  labelTitle.innerHTML = "Label:";
                  cBuff.appendChild(labelTitle);

                  label = document.createElement("input");
                  label.setAttribute("class","form-control");
                  label.setAttribute("style","color:black");
                  label.setAttribute("name" ,"label");
                  label.setAttribute("type" ,"text");
                  label.setAttribute("value",dashboardItems[id].label);
                  cBuff.appendChild(label);

                rBuff = document.createElement("div");
                rBuff.setAttribute("class","col-xs-2");
                modalBody.appendChild(rBuff);

              modalFooter = document.createElement("div");
              modalFooter.setAttribute("class","modal-footer");
              modalFooter.setAttribute("style","text-align:center")
              modalForm.appendChild(modalFooter);

                cancelButton = document.createElement("button");
                cancelButton.setAttribute("class"       ,"btn btn-default");
                cancelButton.setAttribute("type"        ,"button");
                cancelButton.setAttribute("data-dismiss","modal");
                cancelButton.innerHTML = "Cancel";
                modalFooter.appendChild(cancelButton);

                saveButton = document.createElement("input");
                saveButton.setAttribute("class","btn btn-primary");
                saveButton.setAttribute("type" ,"submit");
                saveButton.setAttribute("value","Save");
                modalFooter.appendChild(saveButton);

                deleteButton = document.createElement("a");
                deleteButton.setAttribute("href"        , document.location.href + "/" + id +"/delete");
                deleteButton.setAttribute("class"       ,"btn btn-danger js-confirm");
                deleteButton.setAttribute("data-confirm", "All dashboard items connected to this dashboard item will be deleted! This cannot be undone. Would you like to continue deleting this dashboard item?");
                deleteButton.innerHTML = "Delete";
                modalFooter.appendChild(deleteButton);
    }
  }

  /*
    function addModals(node,id)

    A function that creates an add button and modal for a dashboard item.

    node: The dashboardItem that the button and modal are associated with.
    id: The dashboardItem ID that the button and modal are associated with.
  */
  function addModals(node, id) {



    button = document.createElement("a");
    button.setAttribute("class"      , "btn btn-primary btn-xs");
    button.setAttribute("style"      , "margin:2px");
    button.setAttribute("data-toggle", "modal");
    button.setAttribute("data-target", "#"+id+"AddModal");
    button.innerHTML = "Add";
    node.appendChild(button);

    modalGroup = document.getElementById("modals");

    modal = document.createElement("div");
    modal.setAttribute("class"          , "modal fade");
    modal.setAttribute("id"             , ""+id+"AddModal");
    modal.setAttribute("tabindex"       , "-1");
    modal.setAttribute("role"           , "dialog");
    modal.setAttribute("aria-labelledby", "myModalLabel");
    modal.setAttribute("style"          , "color:black; text-align:center");
    modalGroup.appendChild(modal);

      modalDialog = document.createElement("div");
      modalDialog.setAttribute("class", "modal-dialog modal-lg");
      modalDialog.setAttribute("role" , "document");
      modal.appendChild(modalDialog);

        modalContent = document.createElement("div");
        modalContent.setAttribute("class", "modal-content");
        modalDialog.appendChild(modalContent);

          modalForm = document.createElement("form");
          modalForm.setAttribute("method","POST");
          modalForm.setAttribute("action",document.location.href);
          modalForm.setAttribute("accept-charset","UTF-8");
          modalContent.appendChild(modalForm);

            modalHeader = document.createElement("div");
            modalHeader.setAttribute("class", "modal-header");
            modalForm.appendChild(modalHeader);

              closeButton = document.createElement("button");
              closeButton.setAttribute("type"        , "button");
              closeButton.setAttribute("class"       , "close");
              closeButton.setAttribute("data-dismiss", "modal");
              closeButton.setAttribute("aria-label"  , "Close");
              modalHeader.appendChild(closeButton);

                X = document.createElement("span");
                X.setAttribute("aria-hidden","true");
                X.innerHTML = "&times;";
                closeButton.appendChild(X);

              modalTitle = document.createElement("h4");
              modalTitle.setAttribute("class","modal-title");
              modalTitle.innerHTML = "New "+dashboardItems[id].label+" Dashboard Item";
              modalHeader.appendChild(modalTitle);

            modalBody = document.createElement("div");
            modalBody.setAttribute("class","modal-body row");
            modalForm.appendChild(modalBody);

              parentIDForm = document.createElement("input");
              parentIDForm.setAttribute("name" ,"parent_id");
              parentIDForm.setAttribute("type" ,"hidden");
              parentIDForm.setAttribute("value",id);
              modalBody.appendChild(parentIDForm);

              modalLeft = document.createElement("div");
              modalLeft.setAttribute("class","col-xs-6");
              modalBody.appendChild(modalLeft);

                llBuff = document.createElement("div");
                llBuff.setAttribute("class","col-xs-2");
                modalLeft.appendChild(llBuff);

                lmBuff = document.createElement("div");
                lmBuff.setAttribute("class","col-xs-8");
                modalLeft.appendChild(lmBuff);

                  definedRadio = document.createElement("input");
                  definedRadio.setAttribute("class","pull-left item-type");
                  definedRadio.setAttribute("name" ,"item_type");
                  definedRadio.setAttribute("type" ,"radio");
                  definedRadio.setAttribute("value","predefined");
                  lmBuff.appendChild(definedRadio);

                  leftHeader = document.createElement("h4");
                  leftHeader.innerHTML = "Predefined";
                  lmBuff.appendChild(leftHeader);

                  leftSelectTitle = document.createElement("p");
                  leftSelectTitle.innerHTML = "Page Type:";
                  lmBuff.appendChild(leftSelectTitle);

                  leftSelect = document.createElement("select");
                  leftSelect.setAttribute("class","form-control predefined-type select-id");
                  leftSelect.setAttribute("style","color: black;");
                  leftSelect.setAttribute("name" ,"id");
                  lmBuff.appendChild(leftSelect);

                  breakLeft2 = document.createElement("br");
                  lmBuff.appendChild(breakLeft2);

                  leftNameTitle = document.createElement("p");
                  leftNameTitle.innerHTML = "Name:";
                  lmBuff.appendChild(leftNameTitle);

                  leftName = document.createElement("input");
                  leftName.setAttribute("class","form-control predefined-type");
                  leftName.setAttribute("style","color:black");
                  leftName.setAttribute("name" ,"label");
                  leftName.setAttribute("type" ,"text");
                  lmBuff.appendChild(leftName);

                  count = 1;
                  $.each(availableDashboardItems, function(index,value) {

                    option = document.createElement("option");
                    option.setAttribute("value",index);
                    option.innerHTML = value;
                    if(count == 1) {
                      option.setAttribute("selected","selected");
                      count++;
                      if(typeof(availableDashboardItems[0]) === 'undefined'){
                        nameLabel = dashModels[index].label;
                        definedRadio.setAttribute("checked","checked");
                      } else {
                        leftName.setAttribute("disabled"    ,"disabled");
                        leftSelect.setAttribute("disabled"  ,"disabled");
                        definedRadio.setAttribute("disabled","disabled");
                        nameLabel = 'Nothing';
                      }
                      leftName.setAttribute("value",nameLabel);

                    }
                    leftSelect.appendChild(option);
                    delete option;
                  });


                  breakLeft = document.createElement("br");
                  lmBuff.appendChild(breakLeft);

                lrBuff = document.createElement("div");
                lrBuff.setAttribute("class","col-xs-2");
                modalLeft.appendChild(lrBuff);




              modalRight = document.createElement("div");
              modalRight.setAttribute("class","col-xs-6 modal-or-divider");
              modalRight.setAttribute("style","border-left: 1px solid #e5e5e5;");
              modalBody.appendChild(modalRight);

                rlBuff = document.createElement("div");
                rlBuff.setAttribute("class","col-xs-2");
                modalRight.appendChild(rlBuff);

                rmBuff = document.createElement("div");
                rmBuff.setAttribute("class","col-xs-8");
                modalRight.appendChild(rmBuff);

                  genericRadio = document.createElement("input");
                  genericRadio.setAttribute("class","pull-left item-type");
                  genericRadio.setAttribute("name" ,"item_type");
                  genericRadio.setAttribute("type" ,"radio");
                  genericRadio.setAttribute("value","generic");
                  rmBuff.appendChild(genericRadio);

                  rightHeader = document.createElement("h4");
                  rightHeader.innerHTML = "Generic";
                  rmBuff.appendChild(rightHeader);

                  rightSelectTitle = document.createElement("p");
                  rightSelectTitle.innerHTML = "Dashboard Item Type:";
                  rmBuff.appendChild(rightSelectTitle);

                  rightSelect = document.createElement("select");
                  rightSelect.setAttribute("class","form-control generic-type");
                  rightSelect.setAttribute("style","color: black;");
                  rightSelect.setAttribute("name" ,"dash_item_type");
                  rmBuff.appendChild(rightSelect);

                  option =document.createElement("option");
                  option.setAttribute("value"   ,"link");
                  option.setAttribute("selected","selected");
                  option.innerHTML = 'Dashboard Group';
                  rightSelect.appendChild(option);
                  delete option;

                  $.each(chart_types,function(index,value) {
                  option =document.createElement("option");
                  option.setAttribute("value",index);
                  option.innerHTML = value;
                  rightSelect.appendChild(option);
                  delete option;
                  });

                  breakRight2 = document.createElement("br");
                  rmBuff.appendChild(breakRight2);

                  rightNameTitle = document.createElement("p");
                  rightNameTitle.innerHTML = "Name:";
                  rmBuff.appendChild(rightNameTitle);

                  rightName = document.createElement("input");
                  rightName.setAttribute("class"      ,"form-control generic-type");
                  rightName.setAttribute("style"      ,"color:black");
                  rightName.setAttribute("name"       ,"label");
                  rightName.setAttribute("type"       ,"text");
                  rightName.setAttribute("placeholder","Enter a label");

                  rmBuff.appendChild(rightName);

                  breakRight = document.createElement("br");
                  rmBuff.appendChild(breakRight);

                  if(nameLabel == 'Nothing') {
                    genericRadio.setAttribute("disabled","disabled");
                    genericRadio.setAttribute("checked" ,"checked");
                  }
                  else {
                    rightName.setAttribute("disabled"  ,"disabled");
                    rightSelect.setAttribute("disabled","disabled");
                  }

                rrBuff = document.createElement("div");
                rrBuff.setAttribute("class","col-xs-2");
                modalRight.appendChild(rrBuff);

            modalFooter = document.createElement("div");
            modalFooter.setAttribute("class","modal-footer");
            modalForm.appendChild(modalFooter);

              cancelButton = document.createElement("button");
              cancelButton.setAttribute("class"       ,"btn btn-default");
              cancelButton.setAttribute("type"        ,"button");
              cancelButton.setAttribute("data-dismiss","modal");
              cancelButton.innerHTML = "Cancel";
              modalFooter.appendChild(cancelButton);

              saveButton = document.createElement("input");
              saveButton.setAttribute("class","btn btn-primary");
              saveButton.setAttribute("type" ,"submit");
              saveButton.setAttribute("value","Save");
              modalFooter.appendChild(saveButton);

  }


  /* Render Dashboard Items after page loads.*/
  if(typeof(dashboardParents) !== 'undefined') {

    $.each(dashboardParents,function(index,value) {
      if(dashboardItems[index].parent_id == 0) {
      renderDashboardItems(index,value);
    }
    });

    $('.select-id').change(function() {
      var label = dashModels[$(this).val()]['label']
      nameform = this.nextSibling;
      while(nameform.name != 'label'){ // search for the name text form.
        nameform = nameform.nextSibling;
      }
      nameform.setAttribute("value",dashModels[this.value].label);


    });

    $('.item-type').on('click',function() {
      if($(this).val() == 'generic') {
        $('.predefined-type').each( function() {
          $(this).prop('disabled',true);
        });
        $('.generic-type').each( function() {
          $(this).prop('disabled',false);
        });
      } else if($(this).val() == 'predefined') {
        $('.predefined-type').each( function() {
          $(this).prop('disabled',false);
        });
        $('.generic-type').each( function() {
          $(this).prop('disabled',true);
        });
      }
    });

  }


  $(".sortable-list").sortable( {
    revert: true,
    start: function(event,ui) {
      $(this).removeClass("inactive-parent-selected-list-group");
      $(this).addClass("active-parent-selected-list-group");

      groupHead = document.getElementById(ui.item.context.firstElementChild.id);

      if( $(groupHead).hasClass("collapsed") ) {
        $(groupHead).removeClass("list-item");
        $(groupHead).addClass("selected-list-item");
      } else {
        var group = groupHead.parentNode;
        $(group).removeClass("inactive-child-selected-list-group");
        $(group).addClass("active-child-selected-list-group");
      }
    },

    stop: function(event,ui) {

      $(this).removeClass("active-parent-selected-list-group");
      $(this).addClass("inactive-parent-selected-list-group");

      groupHead = document.getElementById(ui.item.context.firstElementChild.id);

      if( $(groupHead).hasClass("collapsed") ) {
        $(groupHead).removeClass("selected-list-item");
        $(groupHead).addClass("list-item");
      } else {
        var group = groupHead.parentNode;
        $(group).removeClass("active-child-selected-list-group");
      $(group).addClass("inactive-child-selected-list-group");
      }
      children = $(this).children();
      $.each(children,function(index,item){
        var values = $("#"+item.firstElementChild.id+"Order").attr('value').split('-');
        $("#"+item.firstElementChild.id+"Order").attr('value',(index+1)+'-'+values[1]+'-'+values[2]);
      });
    }
  }).disableSelection();

  setInterval( function(){

      $(".seconds").each( function(index, item) {

        var difference = $(item).attr('value');
        var seconds = fixIntegers((difference) % 60);

        if(difference >= 1) {
          $(item).text(seconds + "s");
          $(item).attr('value',$(item).attr('value')-1);
        } else {
          $(item).text('');
        }

      }),

      $(".minutes").each( function(index, item) {

        var difference = $(item).attr('value');
        var minutes = fixIntegers( Math.floor(difference/60) % 60);

        if(difference >= 60) {
            $(item).text(minutes + "m");
            $(item).attr('value',$(item).attr('value')-1);
        } else {
          $(item).text('');
        }
      }),

      $(".hours").each( function(index, item) {

        var difference = Math.floor($(item).attr('value'));
        var hours = fixIntegers( Math.floor(difference/3600) % 24);

        if(difference >= 3600) {
          $(item).text(hours + "h");
          $(item).attr('value',$(item).attr('value')-1);
        } else {
          $(item).text('');
        }
      }),

      $(".days").each( function(index, item) {

        var difference = $(item).attr('value');
        var days = Math.floor(difference/86400);

        if(difference >= 86400) {
          $(item).text(days + "d");
          $(item).attr('value',$(item).attr('value')-1);
        } else {
          $(item).text('');
        }
      }),

      $(".timeout").each( function(index, item) {
        if($(item).attr('value') > 0) {
          $(item).attr('value',$(item).attr('value')-1);
        } else if($(item).attr('value') == 0) {
          $(item).text('Bypass control over');
        }
      })

  }, 1000);

  function fixIntegers(integer)
  {
      if (integer < 0)
          integer = 0;
      if (integer < 10)
          return "0" + integer;
      return "" + integer;
  }

  if(typeof(chart_sensors) !== 'undefined') {

    $("#device_type").change(function() { // Called whenever the device type is changed on the Edit Chart page.
      var type_select = document.getElementById("device_type");
      var chart_select = document.getElementById("chart_type");
      if(typeof(chartListStart) === 'undefined') {
        var chartListStart = null;
      }
        chart_select.options.length = 0;
        for(i = 0; i < chart_lists[type_select.value].length; i++) {
          createOption(chart_select, chart_lists[type_select.value][i],chart_lists[type_select.value][i], chartListStart);
          $("#chart_type").change();
        }

        if( $("#device_type").val() == 0) {
          $('.chart-sensor').each(function(index,value) {
              $(this).removeClass('hidden-type');
              if( !$(this).hasClass('hidden-zone') ) {
                $(this.parentNode).show();
              }
          });
          $('.chart-control').each(function(index,value) {
              $(this).removeClass('hidden-type');
              if( !$(this).hasClass('hidden-zone') ) {
                $(this.parentNode).show();
              }
          });
        } else {
          $('.chart-sensor').each(function(index,value) {
            if( $("#device_type").val() == chart_sensors[index].function ) {
              $(this).removeClass('hidden-type');
              if( !$(this).hasClass('hidden-zone') ) {
                $(this.parentNode).show();
              }
            } else {
              $(this).addClass('hidden-type');
              $(this.parentNode).hide();
            }
          });
          $('.chart-control').each(function(index,value) {
            if( $("#device_type").val() == chart_controls[index].function ) {
              $(this).removeClass('hidden-type');
              if( !$(this).hasClass('hidden-zone') ) {
                $(this.parentNode).show();
              }
            } else {
              $(this).addClass('hidden-type');
              $(this.parentNode).hide();
            }
          });
        }
        var deviceFound = 0;
        var sensorFound  = 0;
        var controlFound = 0;
        $('.check').each(function(index,value){
          if( $(this.parentNode.parentNode).hasClass('hidden-zone') || $(this.parentNode.parentNode).hasClass('hidden-type') ){
            this.checked = false;
          }
        })
        $('.chart-sensor').each(function(index,value) {
          if( !$(this).hasClass('hidden-zone') && !$(this).hasClass('hidden-type') ) {
            deviceFound = 1;
            sensorFound = 1;
          }
        })
        $('.chart-control').each(function(index,value) {
          if( !$(this).hasClass('hidden-zone') && !$(this).hasClass('hidden-type') ) {
            deviceFound  = 1;
            controlFound = 1;
          }
        })
        if(deviceFound == 1) {
          if( !$("#no-devices").hasClass('hidden') ){
            $("#no-devices").addClass('hidden');
          }
        } else {
          if( $("#no-devices").hasClass('hidden') ){
            $("#no-devices").removeClass('hidden');
          }
        }
        if(sensorFound == 1) {
          if( $("#sensor-devices").hasClass('hidden') ){
            $("#sensor-devices").removeClass('hidden');
          }
        } else {
          if( !$("#sensor-devices").hasClass('hidden') ){
            $("#sensor-devices").addClass('hidden');
          }
        }
        if(controlFound == 1) {
          if( $("#control-devices").hasClass('hidden') ){
            $("#control-devices").removeClass('hidden');
          }
        } else {
          if( !$("#control-devices").hasClass('hidden') ){
            $("#control-devices").addClass('hidden');
          }
        }
        isSavable();
    });



    $("#zone").change(function() { // Called whenever the zone is changed on the Edit Chart page.
      $('#devices').fadeToggle(200,function(){
        if( $("#zone").val() == 0) {
          $('.chart-sensor').each(function(index,value) {
              $(this).removeClass('hidden-zone');
              if( !$(this).hasClass('hidden-type') ) {
                $(this.parentNode).show();
              }
          });
          $('.chart-control').each(function(index,value) {
              $(this).removeClass('hidden-zone');
              if( !$(this).hasClass('hidden-type') ) {
                $(this.parentNode).show();
              }
          });
        } else {
          $('.chart-sensor').each(function(index,value) {
            if( $("#zone").val() == chart_sensors[index].zone ) {
              $(this).removeClass('hidden-zone');
              if( !$(this).hasClass('hidden-type') ) {
                $(this.parentNode).show();
              }
            } else {
              $(this).addClass('hidden-zone');
              $(this.parentNode).hide();
            }
          });
          $('.chart-control').each(function(index,value) {
            if( $("#zone").val() == chart_controls[index].zone ) {
              $(this).removeClass('hidden-zone');
              if( !$(this).hasClass('hidden-type') ) {
                $(this.parentNode).show();
              }
            } else {
              $(this).addClass('hidden-zone');
              $(this.parentNode).hide();
            }
          });
        }

        var deviceFound = 0;
        var sensorFound  = 0;
        var controlFound = 0;
        $('.check').each(function(index,value){
          if( $(this.parentNode.parentNode).hasClass('hidden-zone') || $(this.parentNode.parentNode).hasClass('hidden-type') ){
            this.checked = false;
          }
        })
        $('.chart-sensor').each(function(index,value) {
          if( !$(this).hasClass('hidden-zone') && !$(this).hasClass('hidden-type') ) {
            deviceFound = 1;
            sensorFound = 1;
          }
        })
        $('.chart-control').each(function(index,value) {
          if( !$(this).hasClass('hidden-zone') && !$(this).hasClass('hidden-type') ) {
            deviceFound  = 1;
            controlFound = 1;
          }
        })
        if(deviceFound == 1) { // Hide or show no devices dialog.
          if( !$("#no-devices").hasClass('hidden') ){
            $("#no-devices").addClass('hidden');
          }
        } else {
          if( $("#no-devices").hasClass('hidden') ){
            $("#no-devices").removeClass('hidden');
          }
        }
        if(sensorFound == 1) { // Hide or show sensors title and content.
          if( $("#sensor-devices").hasClass('hidden') ){
            $("#sensor-devices").removeClass('hidden');
          }
        } else {
          if( !$("#sensor-devices").hasClass('hidden') ){
            $("#sensor-devices").addClass('hidden');
          }
        }
        if(controlFound == 1) { // Hide or show controls title and content.
          if( $("#control-devices").hasClass('hidden') ){
            $("#control-devices").removeClass('hidden');
          }
        } else {
          if( !$("#control-devices").hasClass('hidden') ){
            $("#control-devices").addClass('hidden');
          }
        }
      });
      isSavable();
      $('#devices').fadeToggle(200);
    });


    $("#chart_type").change(function() {
      chart_type = $(this).val();
        switch(chart_type) {
          case 'Temperature Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', "Temperature (\xB0"+thisSys.temperature_format+')', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Temperature':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', "Temperature (\xB0"+thisSys.temperature_format+')', 'none','/images/column_snap.png');
            break;

          case 'Voltage Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Voltage (V)', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Voltage':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Voltage (V)', 'none','/images/column_snap.png');
            break;

          case 'Humidity Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', '% Humidity', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Humidity':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', '% Humidity', 'none','/images/column_snap.png');
            break;

          case 'Light Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Light (lm)', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Light':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Light (lm)', 'none','/images/column_snap.png');
            break;

          case 'Occupancy Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Occupancy', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Occupancy':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Occupancy', 'none','/images/column_snap.png');
            break;

          case 'Analog Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Analog', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Analog':
            if( $("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Analog', 'none','/images/column_snap.png');
            break;

          case 'Digital Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Digital', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Digital':
            if( $("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Digital', 'none','/images/column_snap.png');
            break;

          case 'Flow Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Flow', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Flow':
            if( $("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Flow', 'none','/images/column_snap.png');
            break;

          case 'Pressure Differential Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Pressure Differential', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Pressure Differential':
            if( $("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Pressure Differential', 'none','/images/column_snap.png');
            break;

          case 'Pressure Line Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Available Chart
              $("#unavailable").addClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Spline', 'Time', 'Pressure', 'block','/images/line_graph_snap.png');
            break;

          case 'Device Pressure':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Pressure', 'none','/images/column_snap.png');
            break;

          case 'Relay Activity Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'ColumnRange', 'Device', 'Time', 'block','/images/horizontal_bar_snap.png');
            break;

          case 'Relay States':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Relay State', 'none','/images/column_snap.png');
            break;

          case 'Virtual Activity Chart':
            if( !$("#unavailable").hasClass('hidden-chart') ) { // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'ColumnRange', 'Device', 'Time', 'block','/images/horizontal_bar_snap.png');
            break;

          case 'Virtual States':
            if( !$("#unavailable").hasClass('hidden-chart') ){ // Unavailable Chart
              $("#unavailable").removeClass('hidden-chart');
              $("#unavailable").slideToggle(100);
            }
            update_chart_forms(chart_type, 'Column', 'Device', 'Virtual State', 'none','/images/column_snap.png');
            break;
        }
        isSavable();

    });
    $("#device_type").change();
    $("#save").prop('disabled',true);
    $(".check").each(function(index,value) {
      if($(this).is(':checked') && $("#unavailable").hasClass('hidden-chart')) {
        $("#save").prop('disabled',false);
      }
    })

    $(".check").click(function(){
      isSavable();
    })

  /*
    function update_chart_forms(chart_label_1, chart_type_1, x_axis_1, y_axis_1, display_range, image)

    A function that changes hidden form holding chart parameters.

    chart_label_1: The label of the chart list option for the primary chart.
    chart_type_1: The chart type of the primary chart.
    x_axis_1: The label for the x_axis on the primary chart.
    y_axis_1: The label for the y_axis on the primary chart.
    display_range: Determines whether or not the x_range_1_group if visable to the user.
    image: The snap shot shown under the chart_type list.
  */
    function update_chart_forms(chart_label_1, chart_type_1, x_axis_1, y_axis_1, display_range, image) {
      $("#chart_type_1").attr('value',chart_type_1);
      $("#x_axis_1").attr('value',x_axis_1);
      $("#y_axis_1").attr('value',y_axis_1);
      $("#x_range_1_group").attr('style','display:'+display_range);
      $("#function_type_1").attr('value', $("#device_type").val());
      $("#chart_label_1").attr('value', chart_label_1);
      $("#x_range_1").attr('value', $("#x_range_1_form").val());
      $("#chart_snap_shot").attr('src',image);
    }

  /*
    function createOption(node,id)

    A function that creates an option for a drop down select list.

    select_list: The list that the option is being added to.
    text: The text that is shown on the list.
    value: The value of the option.
  */
    function createOption(select_list, text, value, selected) {
        var opt = document.createElement('option');
        opt.value = value;
        opt.text = text;
        if (selected == value && selected != null){
          opt.selected = 'selected';
        }
        select_list.options.add(opt);
    }

  /*
    function isSavable()

    A function that checks to see if paremeters chosen can be saved.

  */
    function isSavable() {
      $("#save").prop('disabled',true);
      $(".check").each(function(index,value) {
        if($(this).is(':checked') && $("#unavailable").hasClass('hidden-chart')) {
          $("#save").prop('disabled',false);
        }
      });
    }
  }

  /* Only use for products type page. */
  if(typeof(hardwarebus) !== 'undefined') {

    /* Run when hardware bus field is changed on products type page.*/
    $(".hardwarebus").change(function(){
      if( $(this).hasClass('hardwarebus-select-edit') ) {// Check if form is for updating an old command type.
    // Reference specific edit modal with product id.
        var product_id = this.id.replace('hardwarebus-select-edit-','');
        if($(this).val() == 'Wired') {// If wired hardware bus show select list and disable text form.
          $("#command-text-edit-"+product_id).hide().attr('disabled','disabled');
          $("#command-select-edit-"+product_id).show().removeAttr('disabled');
        } else {// If not wired hardware bus, show text form and disable select list.
          $("#command-select-edit-"+product_id).hide().attr('disabled','disabled');
          $("#command-text-edit-"+product_id).show().removeAttr('disabled');
        }
      } else if ( $(this).hasClass('hardwarebus-select-add') ) {// Check if form is for submitting a new command type.
        if($(this).val() == 'Wired') {// If wired hardware bus show select list and disable text form.
          $("#command-text-add").hide().attr('disabled','disabled');
          $("#command-select-add").show().removeAttr('disabled');
        } else {// If not wired hardware bus, show text form and disable select list.
          $("#command-select-add").hide().attr('disabled','disabled');
          $("#command-text-add").show().removeAttr('disabled');
        }
      }
    });

  /* Check commands form for valid inputs. Correct or reject otherwise. */
    $('form').submit(function(){
      var id = this.id;
      if(id != ''){
        var finalCommands = [];
        var badCommand = 0;
        var missingField = 0;
        var cancelSubmit = 0;
        if(id == 'form-add') {// Check if it is adding a new product type or, otherwise, editing one.
          if( !$("#command-text-add").prop('disabled') ) {// Check if this form is submitting a text form for commands as opposed to a select list.
            commandForm = document.getElementById("command-text-add");
          } else {
            commandForm = document.getElementById("command-select-add");
          }
          productIdForm     = document.getElementById("product_id-add");
          nameForm          = document.getElementById("name-add");
          functionForm      = document.getElementById("function-add");
          manufacturerForm  = document.getElementById("manufacturer-add");
          partnumberForm    = document.getElementById("partnumber-add");
          directForm        = document.getElementById("direct-add");
          reporttimeForm    = document.getElementById("reporttime-add");
          powerlevelForm    = document.getElementById("powerlevel-add");
          modeForm          = document.getElementById("mode-add");
          productTypeForm   = document.getElementById("product_type-add");
          auxcontrollerForm = document.getElementById("auxcontroller-add");
          priceForm         = document.getElementById("price-add");
          commentsForm      = document.getElementById("comments-add");
          alert             = document.getElementById("alert-add");
          alert.innerHTML = '';
          if(productIdForm.value in product_ids || productIdForm.value == '') {
            if(productIdForm.value in product_ids){
              alert.innerHTML = alert.innerHTML+"-You've entered a product id that has already been used. Please correct this.<br>";
            }
            if (productIdForm.value == '') {
              missingField = 1;
            }
            $(productIdForm).css('border-color','red');
          } else $(productIdForm).css('border-color','#ccc');
        } else {
          product_id = id.replace('form-','');
          if( !$("#command-text-edit-"+product_id).prop('disabled') ) {// Check if this form is submitting a text form for commands as opposed to a select list.
            commandForm = document.getElementById("command-text-edit-"+product_id);
          } else {
            commandForm = document.getElementById("command-select-edit-"+product_id);
          }
          productIdForm     = document.getElementById("product_id-edit-"+product_id);
          nameForm          = document.getElementById("name-edit-"+product_id);
          functionForm      = document.getElementById("function-edit-"+product_id);
          manufacturerForm  = document.getElementById("manufacturer-edit-"+product_id);
          partnumberForm    = document.getElementById("partnumber-edit-"+product_id);
          directForm        = document.getElementById("direct-edit-"+product_id);
          reporttimeForm    = document.getElementById("reporttime-edit-"+product_id);
          powerlevelForm    = document.getElementById("powerlevel-edit-"+product_id);
          modeForm          = document.getElementById("mode-edit-"+product_id);
          productTypeForm   = document.getElementById("product_type-edit-"+product_id);
          auxcontrollerForm = document.getElementById("auxcontroller-edit-"+product_id);
          priceForm         = document.getElementById("price-edit-"+product_id);
          commentsForm      = document.getElementById("comments-edit-"+product_id);
          alert             = document.getElementById("alert-edit-"+product_id);
          alert.innerHTML = '';
          if(productIdForm.value in product_ids || productIdForm.value == '') {
            if(productIdForm.value in product_ids){
              if( !(typeof(product_id) != 'undefined' && product_id == productIdForm.value)){
            $(productIdForm).css('border-color','red');
                alert.innerHTML = alert.innerHTML+"-You've entered a product id that has already been used. Please correct this.<br>";
              }
            }
            if (productIdForm.value == '') {
              missingField = 1;
            $(productIdForm).css('border-color','red');
            }
          } else $(productIdForm).css('border-color','#ccc');
        }
        if( isNaN(commandForm.value.replace(/,/g,'')) ) {// Prevent form submission. Prompt user to fix problems.
          alert.innerHTML = alert.innerHTML+"-You have entered illegal characters into the commands field. Each command should only be seperated by a single comma. Please correct this to continue.<br>";
          $(commandForm).css('border-color', 'red');
          cancelSubmit = 1;
        } else {
          command_array = commandForm.value.split(',');// convert commands list to an array seperated by commas (',').
          command_array.forEach(function(value,index) {// Loop through each command type submitted.
            if(value in device_types) {// Check if command type is valid.
              if($.inArray(value, finalCommands) === -1) { // Check if command type is a duplicate.
                finalCommands.push(value); // Add to array of legal commands for submission.
              }
            } else {// Set flag for illegal command found.
              badCommand = 1;
            }
          });
          $(commandForm).val(finalCommands);// Reset commands text form with all valid, non-duplicate commands found.
          if(finalCommands.length == 0){
            $(commandForm).css('border-color', 'red');
            alert.innerHTML = alert.innerHTML+"-You've enter no valid commands in the commands field. Any illegal commands have been removed. Please correct this issue.<br>";
          }else if (badCommand) {// Prompt user of problem with command types entered, but submit form with corrections made automatically.
            alert.innerHTML = alert.innerHTML+"-Some of the commands that you entered were not found in our records. We\'ve already removed them from the list. If this does not seem correct, please check the Device Types page for any issues.<br>";
            $(commandForm).css('border-color', '#ccc');
          } else $(commandForm).css('border-color', '#ccc');
        }
        if(nameForm.value == '') {// Check for blank name text field.
          missingField = 1;
          $(nameForm).css('border-color','red');
        } else $(nameForm).css('border-color','#ccc');
        if(functionForm.value == '') {// Check for blank function text field.
          missingField = 1;
          $(functionForm).css('border-color','red');
        } else $(functionForm).css('border-color','#ccc');
        if(manufacturerForm.value == '') {// Check for blank manufacturer text field.
          missingField = 1;
          $(manufacturerForm).css('border-color','red');
        } else $(manufacturerForm).css('border-color','#ccc');
        if(priceForm.value == '') {// Check for blank price text field.
          missingField = 1;
          $(priceForm).css('border-color','red');
        } else $(priceForm).css('border-color','#ccc');
        if(productTypeForm.value == '') {// Check for blank price text field.
          missingField = 1;
          $(productTypeForm).css('border-color','red');
        } else $(productTypeForm).css('border-color','#ccc');
        if(missingField) {// Prevent form submission. Promt user to fix problems.
          alert.innerHTML = alert.innerHTML+"-You have left required fields empty. Please correct them.<br>";
          cancelSubmit =1;
        }
        if(partnumberForm.value in product_numbers && partnumberForm.value != '') {
          $(partnumberForm).css('border-color','red');
          alert.innerHTML = alert.innerHTML+"-You've entered a part number that has already been used. Please correct this.<br>";
        } else $(partnumberForm).css('border-color','#ccc');
        if(cancelSubmit) {
          return false;
        }
      }
    });
  }


  /**
   * Filter Data Type options for a given device
   */
  if( $('.js-validate-export').length ) {
    var functionSelect = $('#function');

    // Takes a list of functions and populates the Data Type select with them
    var reloadSelect = function(data)
    {
      functionSelect.prop('disabled', true);

      // First clear out the old function list
      $('#function option[value!=""]').remove();

      // Then re-populate it with the device-specific list
      for (i = 0; i < data.length; i++) {
        functionSelect.append($("<option></option>")
          .attr("value",data[i])
          .text(data[i])
        );
      };

      functionSelect.prop('disabled', false);
    }

    // When a device is selected call the server to see what types of data it
    // reports, them pass that data to reloadSelect()
    $('#device_id').change(function(){
      $.ajax(
        'export/filter',
        {
          method: 'POST',
          data: {'device_id': $(this).val()},
          success: function(data, textStatus, jqXHR){ reloadSelect(data); }
        }
      );
    });
  }

  // if( $('.js-reports-load').length ) {
  //
  //   // x-axis label format
  //   plotOptions.xAxis.labels = {
  //     formatter: function(){ return Highcharts.dateFormat('%l:%M %P<br> %e %b', this.value); },
  //     rotation: -30
  //   };
  //
  //   // Initial chart render with no data
  //   $(container).highcharts(plotOptions);
  //   $('#zone-toggles .btn').click(
  //     function(){
  //       var zone = $(this).attr('data-zone-toggle');
  //       // Put the spinner back so everyone knows what's going on here
  //       $(container).append(loadingDiv);
  //       $('#loading-message').html('Filtering displayed data');
  //       $('#zone-toggles .btn').addClass('disabled');
  //
  //       setTimeout(function(){
  //         filterData(zone);
  //       }, 10);
  //
  //     }
  //   );
  //   var loadingDiv = '<div class="loading"><div class="loading-spinner"></div><span id="loading-message"></span></div>';
  //   $(container).append(loadingDiv);
  //   var report = $(container).highcharts(),
  //       seriesIndex = -1,
  //       checking = true,
  //       i = 1,
  //       dateString,
  //       hardLimit = 7;
  //
  //   var reportCheck = function()
  //   {
  //     // Stop checking data after checking the limit date
  //     if(fetchDate === fetchLimit) {
  //       checking = false;
  //     }
  //
  //     $('#loading-message').html('Getting data for ' + fetchDate);
  //     // $.ajax(
  //     //   'reports/ajax',
  //     //   {
  //     //     data: {
  //     //       dataFunction: $("#function-select option:selected").text(),
  //     //       dateToFetch: fetchDate
  //     //     },
  //     //     method: "POST",
  //     //     complete: function(data, textStatus, jqXHR){
  //     //       if(checking) {
  //     //         // Scheduling another check
  //     //         setTimeout(reportCheck(), 100);
  //     //       }
  //     //     },
  //     //     success: function(data, textStatus, jqXHR){
  //     //       if(typeof(data.nextFetchDate) !== "undefined" && data.nextFetchDate !== null) {
  //     //         // Updating fetchDate value
  //     //         fetchDate = data.nextFetchDate;
  //     //       }
  //     //       if(typeof(data.data) !== 'undefined') {
  //     //         appendData(data.data);
  //     //       }
  //     //       console.log(data.data[Object.keys(data.data)[0]]);
  //     //
  //     //       // Once we're done polling for data the loading spinner can go
  //     //       if(checking === false) {
  //     //         $('.loading').remove();
  //     //         $('#zone-toggles').show('slow');
  //     //       }
  //     //
  //     //     }
  //     //   }
  //     // );
  //
  //
  //
  //
  //     i++;
  //     if(i > hardLimit){
  //       // Hard Stop because the limit of ajax calls has been reached
  //       checking = false;
  //     }
  //
  //
  //
  //   };
  //
  //   // var appendData = function(data)
  //   // {
  //   //   $.each(data, function(deviceId, devicedataPoints){
  //   //     deviceId = parseInt(deviceId, 10);
  //   //     console.log(deviceId);
  //   //     seriesIndex = seriesReference.indexOf( parseInt(deviceId, 10) )
  //   //     console.log(seriesIndex);
  //   //     if(seriesIndex >= 0) {
  //   //       report.series[seriesIndex].show();
  //   //
  //   //       $.each(devicedataPoints, function(key, dataPoint){
  //   //             dateString = dataPoint[0].split(',');
  //   //         var pointValue = parseFloat(dataPoint[1], 10);
  //   //         var d = Date.UTC(dateString[0], dateString[1] - 1, dateString[2], dateString[3], dateString[4], dateString[5]); // Months are 0 based in JS lol
  //   //         report
  //   //           .series[seriesIndex]
  //   //           .addPoint([
  //   //             d,
  //   //             pointValue
  //   //           ], false);
  //   //       });
  //   //
  //   //     }
  //   //
  //   //     // Add plot lines to seperate days in multi-day charts
  //   //     if(i > 2) { // referencing the incrementor avoids a line on the firs day's data
  //   //       report.xAxis[0].addPlotLine({
  //   //         value: new Date( Date.UTC(dateString[0], dateString[1] - 1, dateString[2], 0, 0, 0) ),
  //   //         color: '#ccc',
  //   //         width: 1
  //   //       });
  //   //     }
  //   //     report.redraw();
  //   //
  //   //   });
  //   // };
  //
  //   var filterData = function(zone)
  //   {
  //     for (i = 0; i < seriesReference.length; i++) {
  //       var device = seriesReference[i];
  //
  //       if(zone !== 'all') {
  //
  //         if( typeof(zoneReference[zone]) !== 'undefined' && zoneReference[zone].indexOf(device) > -1) {
  //           try{ report.series[i].show(); }
  //           catch(e){console.log('Failed to show series '+i);}
  //         }else{
  //           try{ report.series[i].hide(); }
  //           catch(e){console.log('Failed to hide series '+i);}
  //         }
  //
  //       }else{
  //         try{ report.series[i].show(); }
  //         catch(e){console.log('Failed to show series '+i);}
  //       }
  //
  //     }
  //
  //     // Now let's get rid of that stupid spinner again
  //     $('#zone-toggles .btn').removeClass('disabled');
  //     $('.loading').hide('fast');
  //     $('.loading').remove();
  //
  //   };
  //
  //   setTimeout( reportCheck(), 1000);
  //
  // }

    $("#help-modal").on('shown.bs.modal',function() {
      var id = $(this).attr('data-scrollto');
      focusSection = document.getElementById(''+id+'');
      focusSection.scrollIntoView(true);
    });
});
