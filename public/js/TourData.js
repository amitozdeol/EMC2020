/*************************
This file contains all the data necessary for help tour
Append links to floating help button in each page, like this
$('.pmd-floating-action').prepend('\
          <a href="javascript:void(0);" class="class_name_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
            Global Setpoints\
          </a>\
          <a href="javascript:void(0);" class="class_name_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
            Zonal Setpoints\
          </a>\'
        );
To add action to each link, go to gettourdata and compare 'class_name_tour' with classname variable.
Create new function for that page that contains all the tour data
**************************/
function gettourdata(classname){
  var MyDATA =[];
  classname === "login_email_tour" ? MyDATA = logintour(classname) : '';
  classname === "forget_password_tour" ? MyDATA = forgetpassword(classname) : '';
  classname === "dashboard_tiles_tour" ? MyDATA = dashboardTilesTour(classname) : '';
  classname === "furnace_sensor_tour" ? MyDATA = furnaceSensorTour(classname) : '';
  classname === "furnace_control_tour" ? MyDATA = furnaceControlTour(classname) : '';
  classname === "user_dropdown_tour" ?  MyDATA = userDropdownTour(classname) : '';
  classname === "multi_season_change_tour" ?  MyDATA = multiSeasonChangeTour(classname) : '';
  classname === "single_season_change_tour" ?  MyDATA = singleSeasonChangeTour(classname) : '';
  classname === "global_setpoint_change_tour" ?  MyDATA = globalSetpointChangeTour(classname) : '';
  classname === "zonal_setpoint_change_tour" ?  MyDATA = zonalSetpointChangeTour(classname) : '';
  classname === "single_setpoint_change_tour" ?  MyDATA = singleSetpointChangeTour(classname) : '';
  classname === "export_data_tour" ?  MyDATA = exportDataTour(classname) : '';
  classname === "alarms_tour" ?  MyDATA = alarmsTour(classname) : '';
  classname === "total_events_tour" ?  MyDATA = totalEventsTour(classname) : '';
  classname === "list_events_tour" ?  MyDATA = listEventsTour(classname) : '';
  classname === "control_zones_tour" ?  MyDATA = controlZonesTour(classname) : '';
  classname === "sensor_zones_tour" ?  MyDATA = sensorZonesTour(classname) : '';
  classname === "reports_tour" ?  MyDATA = reportsTour(classname) : '';
  classname === "users_tour" ?  MyDATA = usersTour(classname) : '';
  classname === "update_users_tour" ?  MyDATA = updateUsersTour(classname) : '';
  classname === "user_account_tour" ?  MyDATA = userAccountTour(classname) : '';
  classname === "SynchronousChart-toolbar-tour" ?  MyDATA = SynchronousChartToolbarTour(classname) : '';
  classname === "Synchronous-chart-tour" ?  MyDATA = SynchronousChartTour(classname) : '';
  classname === "d3-barchart-tour" ?  MyDATA = d3BarChartTour(classname) : '';
  // classname === "" ?  MyDATA = () : '';

  //add the common property values to MyDATA
  for (var i = 0; i < MyDATA.length; i++) {
    MyDATA[i]["delay"] = 250;
    MyDATA[i]["backdrop"] = true;   // Add a transparent background 
    MyDATA[i]["placement"] = (MyDATA[i]["placement"] != undefined)?MyDATA[i]["placement"]:"bottom";
    MyDATA[i]["onShown"] = function (tour) {
      $('.tour-step-backdrop').closest(".nav").addClass("tour-step-backdrop-parent").css("z-index", "1101");
      $('.tour-step-backdrop').closest(".navbar").addClass("tour-step-backdrop-parent").css("z-index", "1101");
    };
    MyDATA[i]["onHidden"] = function (tour) {
      $('.tour-step-backdrop-parent').removeClass("tour-step-backdrop-parent").css("z-index", "");
    };
    MyDATA[i]["template"] = "<div class='popover tour' style=\"margin:10pt;\"><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-default btn-sm' data-role='prev'>« Prev</button><button class='btn btn-primary btn-sm' data-role='next'>Next »</button><button class='btn btn-default btn-sm pull-right' style='background-color: #e74c3c; color:white; font-weight: 600; margin:5pt;' data-role='end'>End Tour</button></div></div>";
  }
  return MyDATA;
}

// function exampleTour(){
//   var tourdata = [{
//     element: document.getElementsByClassName("example_tour_class")[0],
//     title: "step1",
//     content: "content",
//   },{
//     element: document.getElementsByClassName("example_tour_class")[1],
//     title: "step2",
//     content: "content",
//   }];
//   return tourdata;
// }

function userAccountTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Reset Password",
    content: "You may click here to update your password.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Email",
    content: "Please provide an accurate email address.<br>This is the address to which <b><i>email&nbsp;alerts</i></b> will be sent.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Mobile Number",
    content: "A mobile phone number is required for users wishing to receive <b><i>text&nbsp;message&nbsp;alerts</i></b>.<br>The field expects a 10-digit phone number (US numbers only).",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Mobile Carrier",
    content: "If using <b><i>text&nbsp;message&nbsp;alerts</i></b>, please select the mobile phone service provider from the drop down list.<br>If your carrier is not listed, and you would like to receive alerts by text message, please contact your system administrator.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Prefix",
    content: "You may optionally include an appropriate prefix, from the drop down menu.",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "First Name",
    content: "Please provide an accurate first name here.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Middle Initial",
    content: "You may optionally include the user's middle initial.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Last Name",
    content: "Please provide an accurate last name here.",
  },{
    element: document.getElementsByClassName(classname)[8],
    title: "Suffix",
    content: "You may optionally include an appropriate suffix, form the drop down menu.",
  },{
    element: document.getElementsByClassName(classname)[9],
    title: "Building Name",
    content: "Change between your buildings by tapping or clicking on the building's name.<br><b><u>Notification subscriptions are on a system-by-system basis!</u></b><br>This means subscriptions for one building will not effect your other buildings' notifications subscriptions; these are independent, from system to system.",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName(classname)[9];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    }
  },{
    element: document.getElementsByClassName(classname)[10],
    title: "System Name",
    content: "The current system is listed here. Click or tap this dropdown to view any addiitonal systems associated with this building.",
    onNext: function(tour){ 
      var sys_options = document.getElementsByClassName(classname)[10].options;
      var i;
      var lowest_val = 10000;
      for(i = 0; i < sys_options.length; i++){
        console.log(lowest_val)
        console.log(sys_options[i].value);
        lowest_val = (Number(sys_options[i].value) < lowest_val)? Number(sys_options[i].value): lowest_val;
      }
      console.log(lowest_val);
      $(document.getElementsByClassName(classname)[10]).val(lowest_val).change();
    },
  },{
    element: document.getElementsByClassName(classname)[11],
    title: "Sensor Notifications",
    content: "Here, you may subscribe to <i>sensor notifications</i>.<br>These notification types relate to the <i>alarm&nbsp;levels</i> of your input devices.<br>For more information on setting your input devices' <i>alarm levels</i>, visit your <i>setpoints</i> page.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[12],
    title: "Control Notifications",
    content: "Here, you may subscribe to <i>control notifications</i>.<br>These notification types relate to the output states of your algorithms and devices.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[13],
    title: "Submit",
    content: "To save your changes, click or tap here.",
  },{
    element: document.getElementsByClassName(classname)[14],
    title: "Cancel",
    content: "To <i>undo your recent changes<i/> and reload the page, click or tap here.",
  }];
  return tourdata;
}

function updateUsersTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Update Password",
    content: "You may click here to update the password for this user.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Customer Account",
    content: "Your users are automatically assigned to this customer account.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Email Address",
    content: "Please provide an accurate email address.<br>This is the address to which <b><i>email&nbsp;alerts</i></b> will be sent.<br>For more information on subscribing to <i>email alerts</i>, please visit the <i>Your Account</i> page, available from the account dropdown menu at the top of this page.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Mobile Number",
    content: "A mobile phone number is required for users wishing to receive <b><i>text&nbsp;message&nbsp;alerts</i></b>.<br>The field expects a 10-digit phone number (US numbers only).<br>For more information on subscribing to <i>text message alerts</i>, please visit the <i>Your Account</i> page, available from the account dropdown menu at the top of this page.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Mobile Carrier",
    content: "If using <b><i>text&nbsp;message&nbsp;alerts</i></b>, please select the mobile phone service provider from the drop down list.<br>If your carrier is not listed, and you would like to receive alerts by text message, please contact your system administrator.",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Prefix",
    content: "You may optionally include an appropriate prefix, from the drop down menu.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "First Name",
    content: "Please provide an accurate first name here.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Middle Initial",
    content: "You may optionally include the user's middle initial.",
  },{
    element: document.getElementsByClassName(classname)[8],
    title: "Last Name",
    content: "Please provide an accurate last name here.",
  },{
    element: document.getElementsByClassName(classname)[9],
    title: "Suffix",
    content: "You may optionally include an appropriate suffix, form the drop down menu.",
  },{
    element: document.getElementsByClassName(classname)[10],
    title: "Save",
    content: "To save your changes, click or tap here.",
  },{
    element: document.getElementsByClassName("delete_users_tour")[0],
    title: "Delete User",
    content: "To <span style=\"color: red;\">completely remove</span> this user and related information, click or tap here.",
  }];
  return tourdata;
}

function usersTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[1],
    title: "Users List",
    content: "Here you may view the list of users under your customer account, including the basic information about each user, such as names, email addresses, phone numbers and mobile carrier.<br>Note: accurate mobile carrier information is required for text message alerts.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Edit User",
    content: "To change the information listed for a user or to change a user's password, tap or click the edit button, here.",
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Create Additional Users",
    content: "To create additional users for your customer account, tap or click here.",
  }];
  return tourdata;
}

function reportsTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Graph",
    content: "Here you can see your devices' reports plotted, over time.<br>The side of the graph displays the names of the devices which fall under the selected <i>Data Type</i> of the displayed graph.<br><span style=\"color: #707070;\">- Greyed out</span> device names indicate either no reports for that device during the time period or that the device has been deselected from the current graph.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Zones",
    content: "You may filter the devices shown on the graph by selecting a zone from the list.<br>This will remove devices from the graph that are outside of the slected zone.<br>To add those devices back, you may click on the <span style=\"color: #707070;\">- greyed out</span> device name on the side of the graph.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Start Date",
    content: "Here, you may select the desired start date of the graph.<br>Be aware, the graph allows for a maximum of <b>7</b> days to shown on a single graph.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "End Date",
    content: "Here you may select the desired end date of the graph; if this date is more than <b>7</b> days after the start date, the graph will automatically be limited to the seven days following the start date.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Data Type",
    content: "Typically, your system records multiple data types. Click or tap on the dropdown to view all available data types for your system.",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Retired Devices",
    content: "For systems where you've recently replaced a device, selecting this option may include data from previously retired/decommissioned devices in the generated graph.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Submit",
    content: "Click or tap <i>Submit</i> to reload the graph with your chosen graph options.",
  }];
  return tourdata;
}

function sensorZonesTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Sensor Zones",
    content: "The <i>Sensor Zones</i> tab is where you can check the latest repoting status of your system's sensors.",
    placement: "top",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName(classname)[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Select Zone(s)",
    content: "Within this tab, the sensors are sorted by zone. By default, all zones are loaded. In order to limit the number of sensors shown, you may select a single zone from this dropdown menu.",
    onNext: function(tour){ if(!$("#tabsin-0").hasClass('in')){ $(document.getElementsByClassName(classname)[1]).val('0').change(); } },
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Sensor Panel",
    content: "Each sensor is displayed within its own panel, containing the sensor's name, location and status.<br>The <i>Status</i> of the output indicates whether the output is currently available for use or whether the output has been \"inhibited\".<br>As well, the icon to the right of the device name may indicate any current issues with the sensor's normal operation.<br>While the icon is green, the sensor is operating normally.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Latest Readings",
    content: "The latest sensor reports are displayed here.<br>Whether a report has \"exceeded\" its setpoint (and is therefor voting yes to any possibly associated outputs) is also indicated.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Report Time",
    content: "The <i>Last Report</i> gives the last date and time that a report was received from the sensor.",
  }];
  return tourdata;
}

function controlZonesTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Control Zones",
    content: "The <i>Control Zones</i> tab is were you can check on the current state of your system's outputs and execute <i>bypass</i> commands.",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName(classname)[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Select Zone(s)",
    content: "Within this tab, the outputs are sorted by zone. By default, all zones are loaded. In order to limit the number of outputs shown, you may select a single zone from this dropdown menu.",
    onNext: function(tour){ if(!$("#tabsout-0").hasClass('in')){ $(document.getElementsByClassName(classname)[1]).val('0').change(); } },
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Control Panel",
    content: "Each output is displayed within its own panel, containing the output's name, MAC Address (where available), and location.<br> As well, the icon to the right of the output name may indicate any current issues with the output's normal operation.<br>While the icon is green, the output is operating normally.",
    placement: "top",
    onShow: function(tour){ if($(document.getElementsByClassName(classname)[5]).hasClass("in")){ $(document.getElementsByClassName(classname)[3]).click(); } },
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Current Status",
    content: "Here, you can see the output's <i>Status</i>, <i>State</i> and <i>Last State Change</i><br>The <i>Status</i> of the output indicates whether the output is currently available for use or whether the output has been inhibited.<br>The <i>State</i> of the output indicates whether the output  was last reported as ON or OFF.<br>The <i>Last State Change</i> corresponds to when the above <i>State</i> was reported.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Set Bypass",
    content: "For bypass/override options, tap or click here.",
    onPrev: function(tour){ if($(document.getElementsByClassName(classname)[5]).hasClass("in")){ $(document.getElementsByClassName(classname)[3]).click(); } },
    onNext: function(tour){ if(!$(document.getElementsByClassName(classname)[5]).hasClass("in")){ $(document.getElementsByClassName(classname)[3]).click(); } },
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Bypass Settings",
    content: "Here you can choose to bypass the output's normal operation by choosing alternate settings for the output.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Bypass State",
    content: "Select the desired state of this output.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Bypass Time",
    content: "Select the amount of time for the output to bypass normal operation.<br>During this time, the output will attempt to remain in the state specified in the previous step.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[8],
    title: "Execute Bypass",
    content: "With the previous selections made, tap or click the <i>Bypass</i> button to transmit the bypass command to the output.",
  },{
    element: document.getElementsByClassName(classname)[9],
    title: "Toggle",
    content: "Alternatively, you may simply select to <i>Toggle</i> the output.<br>This option is typically used for testing purposes, where the output will have its state changed for approximately three seconds before being returned to normal operation.",
  }];
  return tourdata;
}

function listEventsTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("list_events_active_tour")[0],
    title: "Active Events",
    content: "To view <i>Active Control Events</i> for this system, click or tap here.",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName("list_events_active_tour")[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName("list_events_active_tour")[1],
    title: "Output Select",
    content: "From the dropdown, you may filter which active events to view by selecting one the system's individual outputs.",
  },{
    element: document.getElementsByClassName("list_events_active_tour")[2],
    title: "Next/Previous",
    content: "If presented with a significant number of active events, you may use the <i>Next</i> and <i>Previous</i> buttons to view any additional events that have not been displayed initially.",
  },{
    element: document.getElementsByClassName("list_events_active_tour")[3],
    title: "Event Information",
    content: "Each panel contains information relevant to an active event.<br>For alarmed events, hover over red or yellow icon at the top-right of the panel to see more information.",
    placement: "top",
  },{
    element: document.getElementsByClassName("list_events_history_tour")[0],
    title: "Events History",
    content: "To view the <i>Control Events History</i> for this system, click or tap here.",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName("list_events_history_tour")[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName("list_events_history_tour")[1],
    title: "Output Select",
    content: "From the dropdown, you may filter which past events to view by selecting one the system's individual outputs.",
  },{
    element: document.getElementsByClassName("list_events_history_tour")[2],
    title: "Next/Previous",
    content: "If presented with a significant number of past events, you may use the <i>Next</i> and <i>Previous</i> buttons to view any additional events that have not been displayed initially.",
  },{
    element: document.getElementsByClassName("list_events_history_tour")[3],
    title: "Event Information",
    content: "Each panel contains information relevant to a past event.<br>For alarmed events, hover over <span style=\"color:#DD2222;background-color:#123E5D;\">&nbsp;Critical&nbsp;</span> or <span style=\"color:#DDDD22;background-color:#123E5D;\">&nbsp;Warning&nbsp;</span> at the top-right of the panel to see more information.<br>Events marked <span style=\"color:#22DD22;background-color:#123E5D;\">&nbsp;Normal&nbsp;</span> represent events triggered by normal algorithm operations.",
    placement: "top",
  },{
  }];
  return tourdata;
}

function totalEventsTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Custom Totals",
    content: "The <i>Custom Totals</i> section provides you with an easy way to view <i>ON</i> times for your output devices over various time frames.",
    onNext: function(tour){
      if(document.getElementsByClassName(classname)[0].classList.contains('collapsed')){
        $(document.getElementsByClassName(classname)[0]).collapse("");
      }
    },
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Select Device",
    content: "To begin, select the relevant output device.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Weekly Total",
    content: "You may select <i>Weekly</i> total to get totals for the device over the past week.",
    placement: "top",
    onNext: function(tour){$(document.getElementsByClassName(classname)[2]).click();},
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Monthly Total",
    content: "You may select <i>Monthly</i> totals to get totals for the device over the past month.",
    placement: "top",
    onNext: function(tour){$(document.getElementsByClassName(classname)[3]).click();},
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Custom Total",
    content: "Or, if you would like to set an alternate time frame, click or tap the <i>Custom</i> button to reveal date options.",
    placement: "top",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[3]).click();},
    onNext: function(tour){
      $(document.getElementsByClassName(classname)[4].previousElementSibling.firstElementChild).removeClass("active");
      $(document.getElementsByClassName(classname)[4].previousElementSibling.previousElementSibling.firstElementChild).removeClass("active");
      $(document.getElementsByClassName(classname)[4].parentElement.nextElementSibling).collapse('toggle');
    },
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Start Date",
    content: "With <i>Custom</i> totals, use the <i>Start Date</i> to indicate the earliest date from which to begin totalling the output device's time <i>ON</i>.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "End Date",
    content: "Then, use the <i>Stop Time</i> to indicate the latest date from which to total the output device's time <i>ON</i>.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Submit",
    content: "When you've made your device and time frame selections, tap or click <i>Submit</i>.<br>The page will refresh and you'll be presented with totals, based on your selections.",
  }];
  return tourdata;
}

function alarmsTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("active_alarms_tour")[0],
    title: "Active Alarms",
    content: "To view ongoing alarms, click on the <i>Active Alarms</i> tab.",
    onNext: function(tour){
      var thisElement = document.getElementsByClassName("active_alarms_tour")[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Priority and All Alarms",
    content: "Select to view only <i>Priority</i> active alarms or <i>All</i> active alarms.<br>By default, <i>All</i> active alarms are displayed.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Next/Previous",
    content: "In the event of a large number of active alarms, you can access additional active alarms by selecting <i>Next</i>.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Alarm Information",
    content: "Individual alarms are identified by the name of the device and the alarm's associated description.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Timing",
    content: "The <i>Start Time</i> of an alarm indicates when the particular instance of this alarm began, following any possible previous alarms clearing (whether automatic or manual).<br>The <i>Duration</i> is calculated based on the previously mentioned <i>Start Time</i>.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Recommendations",
    content: "For certain alarms, you may be presented with a suggested action which may assist in remedying the alarm's cause.",
    placement: "top",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Clear Alarm",
    content: "Alternatively, you may select to manually <i>Clear Alarm</i>. This action will mark the alarm condition as manually resolved.<br>Be aware, if the conditions resulting in the cleared alarm persist, the alarm will likely be regenerated when the system is again made aware of the alarming state/condition.",
    placement: "top",
  },{
    element: document.getElementsByClassName("past_alarms_tour")[0],
    title: "Alarm History",
    content: "To view previously cleared alarms, select <i>Alarms History</i>",
    onPrev: function(tour){
      var thisElement = document.getElementsByClassName("active_alarms_tour")[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
    onNext: function(tour){
      var thisElement = document.getElementsByClassName("past_alarms_tour")[0];
      if($(thisElement).attr("data-parent") == "#myTab-accordion"){ if(thisElement.classList.contains('collapsed')){ $(thisElement).click(); } }
      else{ $(thisElement).click(); }//prevent clicking on open accordions
    },
  },{
    element: document.getElementsByClassName("past_alarms_tour")[1],
    title: "Past Alarms",
    content: "Here you can view previously active alarms, along with the relevant data associated with those alarms.",
    placement: "top",
  }];
  return tourdata;
}

function exportDataTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Devices",
    content: "From the dropdown menu, select either an individual device or select <i>All Devices - By Data Type</i><br>The <i>All Devices - By Data Type</i> option will provide export data for multiple devices, based on the selected data type.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Data Type",
    content: "Here, you may select which type of data you wish to have exported for the chosen device or devices.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Start Date",
    content: "This will be the earliest date from which device data will be exported.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "End Date",
    content: "This will be the latest date from which device data will be exported.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Export Data",
    content: "Once the appropriate selections have been made, tap or click the button to prompt the export file download.",
  }];
  return tourdata;
}

function singleSetpointChangeTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("setpoint_tab_tour")[0],
    title: "Device Function Type",
    content: "Setpoints are based on the function type of a device.<br>These function types correspond to the type of data a particular device or group of devices report.",
    onNext: function(tour){$(document.getElementsByClassName("setpoint_tab_tour")[0]).show();},
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Current Zone",
    content: "Devices are grouped by zone; see the devices for a given zone by selecting the zone from the dropdown menu.",
    // onNext: function(tour){
    //   $(document.getElementsByClassName(classname)[0]).change(function(){
    //     $(document.getElementsByClassName(classname)[0].firstElementChild).attr("selected","selected");  
    //   });
    //   $(document.getElementsByClassName(classname)[0]).change();
    // },
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Primary Settings",
    content: "The primary settings for a device are displayed here.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Settings",
    content: "To change these settings, click or tap the icon.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[2]).click();},
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Setpoint",
    content: "A device's setpoint determines the value below which the device will give a positive vote to any outputs the device has been assigned to.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[8]).click();},
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Priority Alarm",
    content: "Those device's whose alarms are important should be marked as <i>Priority Alarms</i>.",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Alarm Low",
    content: "The <i>Alarm Low</i> value sets the value below which device reports will generate a critically low value alarm.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Alarm High",
    content: "The <i>Alarm High</i> value sets the value above which device reports will generate a critically high value alarm.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Save",
    content: "To apply your changes to the device's settings, click or tap Save.",
  },{
    element: document.getElementsByClassName(classname)[8],
    title: "Cancel",
    content: "To prevent saving your changes, click or tap Cancel.",
    onHide: function(tour){$(document.getElementsByClassName(classname)[8]).click();},
  }];
  return tourdata;
}

function zonalSetpointChangeTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("setpoint_tab_tour")[0],
    title: "Device Function Type",
    content: "Setpoints are based on the function type of a device.<br>These function types correspond to the type of data a particular device or group of devices report.",
    onNext: function(tour){$(document.getElementsByClassName("setpoint_tab_tour")[0]).show();},
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Zonal Setpoints",
    content: "Click or tap the icon to make changes to the setpoints of all of this zone's devices.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Devices' Setpoints",
    content: "To change the setpoint of all devices within this zone, type the desired setpoint for this zone's devices into the field.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[3]).click();},
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Save",
    content: "To confirm and apply the setpoint change to this zone's devices, click or tap Save.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Cancel",
    content: "To prevent saving the zonal setpoint value, click or tap Cancel.",
    onHide: function(tour){$(document.getElementsByClassName(classname)[3]).click();},
  }];
  return tourdata;
}

function globalSetpointChangeTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("setpoint_tab_tour")[0],
    title: "Device Function Type",
    content: "Setpoints are based on the function type of a device.<br>These function types correspond to the type of data a particular device or group of devices report.",
    onNext: function(tour){$(document.getElementsByClassName("setpoint_tab_tour")[0]).show();},
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Global Setpoints",
    content: "Click or tap the icon to make changes to the setpoints of all of the devices of the chosen function type, for this system.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Devices' Setpoints",
    content: "To change the setpoint of all devices with this function type, type the desired setpoint into the field.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[3]).click()}
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Save",
    content: "To confirm and apply the setpoint change for all devices of this type, click or tap Save.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Cancel",
    content: "To prevent saving the global setpoint value, click or tap Cancel.",
    onHide: function(tour){$(document.getElementsByClassName(classname)[3]).click()}
  }];
  return tourdata;
}

function singleSeasonChangeTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Select A System",
    content: "Click or tap on the system you want to change.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Select A Season",
    content: "From the drop down, select a season.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Save",
    content: "To apply your changes, click or tap Save.",
    onNext: function(tour){
      var elmt = document.getElementsByClassName(classname)[2];
      var seasonVal = $(elmt.previousElementSibling.lastElementChild.firstElementChild).find(":selected").text();
      if(seasonVal == "Winter"){
        $(elmt).attr("data-toggle","modal");
        $(document.getElementsByClassName(classname)[2]).click();
      }
    },
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Warning",
    content: "If attempting to change to winter mode, you will be presented with a warning.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[5]).click();},
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Make Update",
    content: "If you are sure of the status of your equipment, select Submit",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Cancel",
    content: "Cancelling will leave the system in its current season mode.",
    onHide: function(tour){$(document.getElementsByClassName(classname)[5]).click();},
  }];
  return tourdata;
}

function multiSeasonChangeTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "All Your Buildings",
    content: "Seasonal changes made here will effect all of your buildings.",
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Select a Season",
    content: "Choose the season that all of your buildings will be changed to.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Save",
    content: "To apply your changes, click or tap Save.",
    onNext: function(tour){
      var elmt = document.getElementsByClassName(classname)[2];
      var seasonVal = $(elmt.previousElementSibling.lastElementChild.firstElementChild).find(":selected").text();
      if(seasonVal == "Winter"){
        $(elmt).attr("data-toggle","modal");
        $(document.getElementsByClassName(classname)[2]).click();
      }
    },
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Warning",
    content: "If attempting to change to winter mode, you will be presented with a warning.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[5]).click();},
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Make Update",
    content: "If you are sure of the status of your equipment, select Submit",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Cancel",
    content: "Cancelling will leave the systems in their current season mode.",
    onHide: function(tour){$(document.getElementsByClassName(classname)[5]).click();},
  }];
  return tourdata;
}

function userDropdownTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Administration",
    content: "content",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Users",
    content: "content",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Access",
    content: "content",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Your Account",
    content: "content",
  }];
  return tourdata;
}

function furnaceControlTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName("furnace_sensor_tour")[0],/*starts in the same place as the sensor tour*/
    title: "Layout Name",
    content: "The name of the current layout is displayed here.",
  },{
    element: document.getElementsByClassName(classname)[0],
    title: "Control Icon",
    content: "See more information about this output by clicking on the icon.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "Control Name",
    content: "The name of the output, as well as its type are listed here.",
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "Current State",
    content: "This is the state of the output, as of the last report received.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Last Report Time",
    content: "This is the last time the current state was reported.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Algorithm Info",
    content: "Click or tap here to see more information about the current state of the output.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[4]).click();},
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Key/Bypass State",
    content: "Whether the output has been bypassed or overridden is displayed here.<br>While in its <i>Active Season</i>, \"Key Switch: Auto\" indicates that the output is being controlled by the assigned inputs.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[4]).click();}, //close the algorithm info container
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Active Season(s)",
    content: "The output's <i>Active Season</i> is listed here.<br>Devices will default to OFF if their <i>Active Season</i> does not match the system's current season mode.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Votes",
    content: "Displays the current number of votes received from inputs below their setpoints (left) alongside the minimum number of votes required for a state of ON (right), under normal/automatic operating conditions.",
  },{
    element: document.getElementsByClassName(classname)[8],
    title: "Inputs",
    content: "The inputs to the above-named output are listed here.<br>These inputs are grouped by their current voting relevance.",
    placement: "top",
    onNext: function(tour){$(document.getElementsByClassName(classname)[4]).click();}, //close the algorithm info container
  },{
    element: document.getElementsByClassName(classname)[9],
    title: "Bypass/Override",
    content: "Options for performing a software bypass/override of the output.",
    onPrev: function(tour){$(document.getElementsByClassName(classname)[4]).click();},
  },{
    element: document.getElementsByClassName(classname)[10],
    title: "Desired State",
    content: "When performing a bypass/override, click or tap either the ON or OFF button to select the desired state.",
  },{
    element: document.getElementsByClassName(classname)[11],
    title: "Bypass Period",
    content: "Select a time period for the bypass/override to last,<br> or select <i>reset</i> to cancel a previous bypass/overrride.",
  },{
    element: document.getElementsByClassName(classname)[12],
    title: "Bypass Submit",
    content: "With your selections complete, click Submit to send the command to your system.",
  },{
    element: document.getElementsByClassName(classname)[13],
    title: "Toggle",
    content: "Selecting toggle will place override the output for approximately three seconds. This option may be useful for testing the responsiveness of the output.",
  }];
  return tourdata;
}

function furnaceSensorTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0], 
    title: "Layout Name",
    content: "content",
  },{
    element: document.getElementsByClassName(classname)[1], 
    title: "Sensor Icon",
    content: "To continue this tour,<br><b>Click the icon, then click Next</b>.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[1]).click();},
  },{
    element: document.getElementsByClassName(classname)[2], 
    title: "Sensor Identifier",
    content: "Name and function type",
  },{
    element: document.getElementsByClassName(classname)[3], 
    title: "Last Report Time",
    content: "content",
  },{
    element: document.getElementsByClassName(classname)[4], 
    title: "Settings",
    content: "content",
  },{
    element: document.getElementsByClassName(classname)[5], 
    title: "Other Information",
    content: "content",
  }];
  return tourdata;
}

function dashboardTilesTour(classname){
  var tourdata = [{
    element: document.getElementsByClassName(classname)[0],
    title: "Building Tile",
    content: "The building tile lets you know if here are active alarms associated with a given building, and also provides access to your system's many features.<br><b>Click on the building tile above</b>,<br>then click next to advance the tour.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[0]).click();},
  },{
    element: document.getElementsByClassName(classname)[1],
    title: "System Name",
    content: "The name associated with the system in this building.",
    onNext: function(tour){$(document.getElementsByClassName(classname)[1]).click();},
  },{
    element: document.getElementsByClassName(classname)[2],
    title: "System Status",
    content: "The system status page is an excellent tool for quickly viewing current sensor readings, as well as understanding the present status of your algorithm controlled output devices.",
  },{
    element: document.getElementsByClassName(classname)[3],
    title: "Alarms",
    content: "The Alarms page provides a single place to view all of the currently active and past alarms associated with your system. From here, you may manually clear an alarm or investigate possible solutions for an alarm state.",
  },{
    element: document.getElementsByClassName(classname)[4],
    title: "Setpoints",
    content: "The setpoints you choose will dictate the point at which your device will change its vote going to any algorithm it may be associated with.<br>You may also assign Alarm High and Alarm Low levels on a sensor by sensor basis.",
  },{
    element: document.getElementsByClassName(classname)[5],
    title: "Data Export",
    content: "The Data Export page allows you to generate and download tables (in .csv format) for specific device's particular data types over a given date range.",
  },{
    element: document.getElementsByClassName(classname)[6],
    title: "Control Events",
    content: "Users can look at device run times, as well as related algorithm \"ON\" state durations. Such information can help users better configure their system for optimal effiency and satisfaction.",
  },{
    element: document.getElementsByClassName(classname)[7],
    title: "Zone Status",
    content: "This page provides you, the user, with a list of your devices. Your devices are broken out into output devices and input devices.",
  }];
  return tourdata;
}

function logintour(classname){
  var logintourdata = [{
    element: document.getElementsByClassName(classname)[0], 
    title: "Email Address",
    content: "Type the email address associated with your account here.",
  },{
    element: document.getElementsByClassName(classname)[1], 
    title: "Password",
    content: "Type the password associated with your account here.",
  },{
    element: document.getElementsByClassName(classname)[2], 
    title: "Sign In",
    content: "Once you've input your email and password, select Sign In.",
  }];
  return logintourdata;
}

function forgetpassword(classname){
  var forgettourdata = [{
    element: document.getElementsByClassName(classname)[0], //first element
    title: "Lorem ipsum dolor sit amet",
    content: "Lorem ipsum dolor sit amet, pellentesque sed lacus etiam, sodales nisl odio, in et suspendisse habitant donec. Pulvinar nascetur ante posuere sollicitudin diam, dapibus varius consectetuer morbi magna sapien. ",
  },{
    element: document.getElementsByClassName(classname)[1], //second element
    title: "Vitae vitae pellentesque,",
    content: "Vitae vitae pellentesque, convallis enim a libero ac, diam natoque phasellus nibh in iaculis suscipit. ",
  }];
  return forgettourdata;
}

function SynchronousChartToolbarTour(classname){
  var charttoolbartourdata = [{
    element: document.getElementsByClassName(classname)[0], //time select
    title: "Time Selection",
    content: "Click here to select the time range to which you want your data to be shown from.",
  },{
    element: document.getElementsByClassName(classname)[1], //zoom out
    title: "Zoom Chat",
    content: "Drag across the chart to zoom into the chart. This button will be enabled when zoom in.",
  },{
    element: document.getElementsByClassName(classname)[2], //select a zone
    title: "Zone selection",
    content: "If you have zone specified, it'll show up here and you can filter the chart based on the zones.",
  },{
    element: document.getElementsByClassName(classname)[3], //expand contrast
    title: "Expand",
    content: "Expand and contract the chart to full screen of your device.",
  }];
  return charttoolbartourdata;
}

function SynchronousChartTour(classname){
  var charttourdata = [{
    element: document.getElementsByClassName(classname)[0], //zoom out
    title: "Drag",
    content: "Drag across the chart to zoom into the chart. All the charts will synchronously zoom into the particular timezone whichever one you selected. Double click anywhere to zoom out.",
  },{
    element: document.getElementsByClassName(classname)[1], //time select
    title: "Chart legends",
    content: "You can filter the chart by hiding/showing any devices you want.",
  },{
    element: document.getElementsByClassName(classname)[2], //zoom out
    title: "Drag",
    content: "Drag across the chart to zoom into the chart. All the charts will synchronously zoom into the particular timezone whichever one you selected. Double click anywhere to zoom out.",
  }];
  return charttourdata;
}

function d3BarChartTour(classname){
  var charttourdata = [{
    element: document.getElementsByClassName(classname)[0], //zoom out
    title: "Time vs Time graph",
    content: "This chart contains the everyday total runtime of all the boilers(represented as bars). The lines in the chart represent the outside temperature and the setpoint for those days.",
  },{
    element: document.getElementsByClassName(classname)[1], //time select
    title: "Boiler Y axis",
    content: "This axis contains time in HH:MM:SS format. It shows how many hours the boiler has ran in 1 day",
  },{
    element: document.getElementsByClassName(classname)[2], //time select
    title: "Temperature Y axis",
    content: "This axis contains temperature in degree fahrenheit format. It shows what's the current temperature for outside as well as setpoint value.",
  }];
  return charttourdata;
}