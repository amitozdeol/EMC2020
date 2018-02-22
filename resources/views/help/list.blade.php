<style type="text/css">
  h3.help-h3 {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 5px double black;
  }
  .help-subhead{
    padding-top: 10px;
    border-top: 4px solid #888888;
  }
  .help-content-detail{
    padding-top: 5px;
    border-top: 1px dashed #333333;
  }
  .close{
      font-size: 25pt;
    }
  @media screen and (max-width: 1080px){
    h3.help-h3 {
      font-size: 30pt;
      text-align: center;
    }
    a.help-a {
      font-size: 20pt;
    }
    li.help-li {
      padding: 12px;
    }
    h2.modal-title{
      font-size: 32pt;
      border-bottom: 5px double black;
    }
    .help-subhead{
      font-size: 25pt;
      text-align: center;
    }
    .help-content-detail{
      font-size: 23pt;
      text-align: center;
    }
    .help-content{
      font-size: 20pt;
    }
  }
</style>

<script type="text/javascript">

  function collapse_sections()  {
    $('#help-sections').collapse('hide');
  }

</script>

<div class="modal-header help-headcer" help-id="{!!$help_id!!}">
  <button type="button" class="StartRefresh close" data-dismiss="modal" aria-label="Close" style="font-size: 25pt;"><span aria-hidden="true">&times;</span></button>
  <h2 class="modal-title" id="myModalLabel" style="text-align:center">User Guide</h2>
  <div class="help-subhead" data-toggle="collapse" data-parent="#modal-header" href="#help-sections" style="color: #0000FF;cursor: pointer;background-color: #f4f6f8;text-align: center;padding: 5px;padding-top: 10px;border-top: 4px solid #888888;">
    -&nbsp;SECTIONS&nbsp;-
  </div>
  <div id="help-sections" class="container-collapse collapse" style="padding: none;">
    <div id="helper-div" style="min-height: auto">

        <ul class="nav navbar-nav">
          @if($example)
          <li class="help-li"><a class="help-a" href="#section_title_1-help">Section Title 1</a></li>
          <li class="help-li"><a class="help-a" href="#section_title_2-help">Section Title 2</a></li>
          <li class="help-li"><a class="help-a" href="#section_title_3-help">Section Title 3</a></li>
          @endif
          <li class="help-li"><a class="help-a android_help_title" href="#general-help" onclick="collapse_sections()">General</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#globalsettings-help" onclick="collapse_sections()">Global Settings</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#setpointmapping-help" onclick="collapse_sections()">Set Points</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#systemstatus-help" onclick="collapse_sections()">System Status</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#zonestatus-help" onclick="collapse_sections()">Zones Status</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#reports-help" onclick="collapse_sections()">Reports</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#dataexport-help" onclick="collapse_sections()">Data Export</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#alarms-help" onclick="collapse_sections()">Alarms</a></li>
          <li class="help-li"><a class="help-a android_help_title" href="#events-help" onclick="collapse_sections()">Events</a></li>
          <!-- <li class="help-li"><a class="help-a android_help_title" href="#youraccount-help" onclick="collapse_sections()">Your Account</a></li> -->
          @if(Auth::user()->role >= 7)
            <!--For Authorized Users-->
            <li class="help-li"><a class="help-a android_help_title_admin" href="#algorithm-help" onclick="collapse_sections()">Algorithms</a></li>
            <li class="help-li"><a class="help-a android_help_title_admin" href="#webmapping-help" onclick="collapse_sections()">Web Mapping</a></li>
            <!-- <li class="help-li"><a class="help-a android_help_title_admin" href="#systemconfiguration-help" onclick="collapse_sections()">System Configuration</a></li>
            <li class="help-li"><a class="help-a android_help_title_admin" href="#administration-help" onclick="collapse_sections()">Administration</a></li>
            <li class="help-li"><a class="help-a android_help_title_admin" href="#customers-help" onclick="collapse_sections()">Customers</a></li>
            <li class="help-li"><a class="help-a android_help_title_admin" href="#building-help" onclick="collapse_sections()">Buildings</a></li>
            <li class="help-li"><a class="help-a android_help_title_admin" href="#users-help" onclick="collapse_sections()">Users</a></li> -->
          @endif
        </ul>

    </div>
  </div>
</div>

<div class="modal-body help-body">
  <!--Start of modal content -->

  @if($example)
  <!--Example Template Start: You can copy and paste this template to start a new help section. To Show this section set $example to true in the HelpController@index or false ot hide it.-->

  <h3 class="help-h3" id="section_title_1-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;">Section Title 1</h3>
  <div class="help-content">
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 1</h4>
    <div class="help-subcontent">
      <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Subsection Detail</p>
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 2</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 3</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
  </div>
  <!--Example Template End -->

  <h3 class="help-h3" id="section_title_2-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;">Section Title 2</h3>
  <div class="help-content">
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 1</h4>
    <div class="help-subcontent">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget finibus nisi, vitae fringilla purus. Morbi id ligula eget sapien porta porttitor ac et orci. Ut mattis neque ornare diam convallis interdum. Pellentesque porttitor luctus laoreet. In semper viverra eros, nec pharetra diam scelerisque a. Quisque consectetur dolor eget iaculis eleifend. Aliquam eget arcu eget dui vestibulum dignissim id nec elit.</p>
      <p>Sed cursus faucibus lacus, ac vestibulum libero ultrices eget. Pellentesque finibus nunc id felis imperdiet, in malesuada neque ullamcorper. In suscipit sit amet magna nec rutrum. Proin venenatis est nec ex dapibus malesuada. Donec eleifend consequat enim in sollicitudin. Donec ac vestibulum eros. Proin semper, urna quis aliquet aliquam, turpis eros commodo mi, in facilisis sem nisi ut sem.</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 2</h4>
    <div class="help-subcontent">
      <p>Mauris laoreet sagittis mattis. Vestibulum ante tellus, interdum sit amet risus vel, eleifend convallis ipsum. Fusce tincidunt neque magna, a blandit tellus sollicitudin fringilla. Cras dictum lectus tellus, a interdum nulla porta et. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse in orci quis ante ultrices laoreet ut at justo. Ut urna tellus, vestibulum nec sem in, hendrerit lobortis neque. Duis blandit odio bibendum dapibus congue. Praesent cursus elit et suscipit ultrices. Etiam at posuere odio, in efficitur mi.</p>
      <p>Suspendisse purus nisi, tincidunt at pharetra eu, sodales in nisi. Sed pretium lacinia metus, vitae maximus augue suscipit a. Morbi placerat efficitur nibh laoreet condimentum. Donec eget faucibus justo. Donec a dui quis mi pulvinar scelerisque et in ex. Donec ut metus elit. Sed sit amet massa in lectus mollis fermentum.</p>
      <p>In fermentum, ex quis porttitor molestie, tellus arcu pulvinar urna, sed pharetra justo mauris ac est. In ante diam, fringilla sed consequat vitae, ultrices vitae elit. Aenean pulvinar est nec quam consectetur, nec ultrices purus porttitor. Integer sodales nisi nec nunc vehicula elementum. Etiam efficitur ornare sapien, sit amet gravida lectus. Duis tortor odio, efficitur sed euismod eget, imperdiet quis lorem. In vel lacus magna. Nullam non nisl velit. Nullam quis sem mollis, commodo ex eget, aliquam quam. Cras gravida tempus mi at tempus.</p>
    </div>
  </div>

  <h3 class="help-h3" id="section_title_3-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;">Section Title 3</h3>
  <div class="help-content">
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 1</h4>
    <div class="help-subcontent">
      <p>Nunc consectetur neque a pulvinar porttitor. Duis interdum risus ornare elit laoreet posuere. Ut euismod vulputate ipsum. Duis felis erat, pulvinar vitae ornare dapibus, mollis id massa. Integer sollicitudin blandit augue sit amet ultricies. Vestibulum consequat, quam eget interdum scelerisque, nisi massa porttitor libero, lacinia interdum massa nisl ut mi. Suspendisse vel commodo sapien, eu bibendum massa. Curabitur egestas sollicitudin sem semper hendrerit. In vitae pretium ante.</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 2</h4>
    <div class="help-subcontent">
      <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque interdum, leo id imperdiet vehicula, elit.</p>
      <p>Donec ut risus eu enim dictum hendrerit euismod vitae libero. Maecenas odio ex, imperdiet id risus ac, dictum tempus lacus. Nullam lorem sapien, vehicula a urna et, pulvinar ultrices arcu. Nam tristique sit amet nulla eget lacinia. Vestibulum dapibus arcu in orci vestibulum, vitae semper urna maximus. Mauris eget dapibus purus. Maecenas in ante eget nulla dictum lobortis. Quisque nec tortor vel metus finibus sagittis non sit amet neque.</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 3</h4>
    <div class="help-subcontent">
      <p>Nam gravida feugiat mollis. Suspendisse sit amet neque et neque feugiat luctus non in metus. Proin ultrices, lorem nec feugiat mattis, nunc sem pretium nisl, vel tincidunt orci lacus quis dolor. Aenean eleifend velit ante, vel finibus neque vestibulum in. Praesent accumsan luctus metus vitae vestibulum. Donec eros quam, luctus venenatis volutpat at, interdum sed velit. Sed tempor, ligula quis imperdiet auctor, nunc dui molestie ligula, sit amet mollis orci ligula eget lacus. Aliquam viverra commodo mattis. Duis sollicitudin porta molestie. Donec posuere, velit eget dapibus posuere, orci mi iaculis velit, a gravida justo nisi eget nisi. Aenean a elementum sem. Vivamus est mi, iaculis ac luctus sollicitudin, finibus eget massa. Praesent scelerisque ac metus non feugiat.</p>
    </div>
  </div>
  <!--Start __________ Help Section-->
  <h3 class="help-h3" id="section_title_1-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;">Section Title 1</h3>
  <div class="help-content">
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 1</h4>
    <div class="help-subcontent">
      <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Subsection Detail</p>
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 2</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Subsection Head 3</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
  </div>
  <!--End __________ Help Section-->
  <!-- END EXAMPLE HELP SECTION ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~` -->
  @endif



  <!--Start General Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="general-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">General</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Your System</h4>
      <div class="help-subcontent">
        <p>The EMC<sup>20/20</sup> is designed to give you a comprehensive view of your building so that you can exercise the most efficient control possible of your building's many essential systems. This website has been designed to make accessing that information, and those controls, as seemless and intuitive as possible.</p>
        <p>By exploring the many facets of your system's web interface, we hope you'll gain even more insight into what makes your building tick and hum. This help section is available to assist you in unlocking all of the tools available to you as a user of the EMC<sup>20/20</sup> web interface; because, we know that, when it comes to your building's particular needs, one size <i>does not</i> fit all.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">More than One Way</p>
        <p>Maybe you like graphs? Then you may want to check out your <a href="#reports-help">reports</a> page. Or maybe you prefer spread sheets? How about we help you <a href="dataexport-help">export</a> the data you're looking for. Or perhaps a real-time visual representation of your building's current <a href="systemstatus-help">status</a> is more your speed? We want to get you the information you need, the way you want it, so that you can get back to what you do best: keeping your building at its best.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">About the EMC<sup>20/20</sup> Web Interface</h4>
      <div class="help-subcontent">
        <p>You can access your system's web interface from any internet enabled device. To access the interface, simply open your device's web browser and enter <a href="">http://emc.eawelectro.com</a> into your browser's address bar. Then, simply login using the email address associated with your EMC<sup>20/20</sup> account and your password. If you believe you have an account associated with your email address but you are unable to login, you may click on "forgot password?" and follow the instructions to recover your account access.</p>
        <p>In order to accomodate the wide range of devices that may be used to access your system's web interface, each page has been designed to behave responsively to the various screens sizes and resolutions from which you may access it. This means that you may find that the same page appears differently when viewed on a larger screen (such as your laptop) than when viewed on a smaller screen (such as your phone). While the layout of the page may change to accomodate these differing sizes and resolutions, we strive to ensure that <i>each page will retain the same information, regardless of screen size</i>.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Help Where You Need It</h4>
      <div class="help-subcontent">
        <p>You can access this help feature anywhere on the EMC<sup>20/20</sup> web interface by simply clicking on "Help" at the top of the page, in the navigation bar (or navigation dropdown menu, on smaller/lower-resolution screens). Selecting help from a particular page will automatically bring you to the help section specific to the page you're on, but you can still access the full help section from that same page. By clicking on the "SECTIONS" button above, you'll be given links, through which you can access the other major help sections.</p>
        <br><br>
        <p style="text-align: center;"><big></big><big>Thank You for Choosing EMC<sup>20/20</sup></big></p>
        <br>
      </div>
    </div>
  </div>
  <!--End General Help Section-->

  <!--Start Global Settings Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="globalsettings-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Global Settings</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Season Mode Control</h4>
      <div class="help-subcontent">
        <p>Here, you have the ability to see the <i>Season Mode</i> ("Summer" or "Winter") of each of your systems. You can easly change the current mode of your systems by clicking the <i>Mode</i> you would like the system to be in. Once you've confirmed each system's <i>Season Mode</i> is correct, click "Save". Your remote systems will be updated, automatically.</p>
        <p>If you made changes to one or more of your system's <i>Season Mode</i>, but have not yet pressed "Save", and you would like to undo the change you've made, simply click "Cancel". The page will reload, showing you the current <i>Season Mode</i> of each of your systems.</p>
      </div>
    </div>
  </div>
  <!--End Global Settings Help Section-->

  <!--Start Setpoints Help Section -->
  <div class="android_help_content">
  <h3 class="help-h3" id="setpointmapping-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Setpoints</h3>
    <div class="help-content">
      <p>By assigning <i>setpoints</i> and <i>alarm limits</i> to your input devices, you can make the most of your systems algorithms while keeping you a step ahead of any possible future issues. The <i>setpoints</i> you choose will dictate the point at which your device will change its vote going to any algorithm it may be associated with.</p>
      <p>You have the option of assigning these <i>setpoints</i> either to all of a given type of sensor (global assignment), or to all the sensors of a given type within a particular zone (zonal assignment), or you may even assign an unique setpoint to every one of your devices. You decide what works best for your system.</p>
      <p>You may also assign <i>Alarm High</i> and <i>Alarm Low</i> levels on a sensor by sensor basis. These <i>Alarm Levels</i> are responsible for generating email notifications, as well as generating the visual cues found on the <a href="systemstatus-help">System Status</a> and <a href="zonestatus-help">Zone Status</a> pages. You always have the option of adjusting these <i>alarm levels</i> to keep you better in step with your system's workings.</p>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Updating a Setpoint and Alarm Level</h4>
      <div class="help-subcontent">
        <p>Setpoints are grouped by sensor type and then subgrouped by zones. To find a sensor's settings, select the sensor type and then find the zone where the sensor is located.</p>
        <p>Once you have found the desired sensor, you will see the current setpoint and alarm limits for the sensor. To change these value click the <i>Settings</i> button.</p>
        <p>In the pop up section, you can now change the Setpoint, Lower Alarm Limit, and Upper Alarm Limit. When these values have been set, click <i>Save</i>. This will store the new values and return you to the set points page.</p>
      </div>

      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Adding and Updating Setback Values</h4>
      <div class="help-subcontent">
        <p>Setbacks allow you to temporarily change your device's setpoint, for a given period of time, on a regular basis. For example: you may create a setback to change a device's setpoint every Monday between 5:00AM and 5:30AM. During that time, the system will recognize the device's setpoint as the value specified by your setback. Outside of that particular time, the system will default to the device's primary setpoint.</p>
        <p>To add a new setback or change an existing setback for a sensor, click the <i>Settings</i> button for the desired sensor.</p>
        <p>In the pop up section, if there are no setpoints for this device, you will find an <i>Add Setback</i> button. Click this to have a setback for this sensor. If there are setbacks already, there will be a blue <b>+</b> button that will also add a setback.</p>
        <p>You may create up to sixteen setpoints, per device. To remove an undesired setpoint, click the red <b>-</b> button to the right of the setback.</p>
        <p>When you are satisfied with the setback configuration for a given device, click <i>Save</i>.</p>
      </div>

      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Global and Zonal Setpoints and Setbacks</h4>
      <div class="help-subcontent">
        <p>In addition to being assigned individually, setpoints and setbacks can be assigned on a global or zonal basis. Be aware, <b>changing global and zonal setpoints will <u>remove</u> and <u>replace</u> individual sensor setpoints and setbacks, even previously assigned global or zonal setpoints and setbacks.</b></p>
        <p>To set a Global setpoint or setbacks, first select the sensor type that you would like to change. With the sensor type group expanded, click on the <i>Edit Global ... Setpoints</i> button. Here you can set a setpoint for all sensors of that type, as well as add setbacks for all sensors of that type.</p>
        <p>To set a zonal setpoint or setbacks, do the same process except that you will click the associated <i>Edit ... Setpoints</i> button found at the top of a zone group, for a given sensor type.</p>
      </div>
    </div>
  </div>
  <!--End Setpoints Help Section -->

  <!--Start System Status Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="systemstatus-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">System Status</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Your System's Current Status</h4>
      <div class="help-subcontent">
        <p>The system status page is an excellent tool for quickly viewing current sensor readings, as well as understanding the present status of you algorithm controlled output devices. Typical output devices include relays/switches, valves, fans and burners.</p>
        <p>This page has the added benefit of giving you, the user, a more accurate spacial representation of your systems many devices. Such a view can speed up problem diagnoses by giving you, the user, an integrated, high-level perspective of your building. Below you'll find more information on the meaning of some of the graphical representations used to give you the most concise information possible, as it relates to your building.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Input Devices</h4>
      <div class="help-subcontent">
        <p>The EMC<sup>20/20</sup> makes an important distinction between input devices (aka: sensors) and output devices. Whereas the state or status of an output device is dictated by you and your system's algorithms, input devices simply report the values they measure. Where <b>your input</b> comes in is the input's other values, its setpoint, setback(s) and alarm levels. You can learn more about these settings by going to the <a href="#setpointmapping-help">Setpoints</a> section of this help document.</p>
        <p>{!!HTML::image('images/system-status-input.png','Responsive image', array('style' => 'display: block; max-width: 100%; height: auto;'))!!}</p>
        <p>Your input devices will be displayed on the System Status page as an icon; this icon is meant to represent the type of data your input device/sensor is collecting. The color of the icon is meant to represent whether the device is nearing or in a high/low reading alarmed state (i.e. the current reading is alarmingly high/low). Green icons indicate devices within normal alarm limits. Clicking on these icons will display the device's name and current value; simultaneously, the device's current value, last report time, and settings (current setpoint and alarm levels), along with other relevant information, will be displayed in the information panel (to the right on medium to large screens and below on smaller screens). This information panel will display the most recently selected device. You can jump to the <a href="#setpointmapping-help">Setpoints</a> page by clicking on the device's displayed settings within this information panel. If you would prefer to see a device's previous readings, visit the <a href="#reports-help" title="more help info">Reports</a> page.</p>
        <p>For large systems, multiple tabs may be used to create separate views. You can access these other views by clicking areas listed above the current view.</p>
        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Output Devices</h4>
        <p>Output devices are those devices whose state/status may be set, dynamically, either by the user directly (using an <i>bypass</i> command) or by mapping the device as an algorithm output. From the System Status page, users may check on the current state/status of an output device, view the device's basic algorithm information, and/or select to temporarily bypass the device's state/status.</p>
        <p>{!!HTML::image('images/system-status-output.png', 'Responsive image', array('style' => 'display: block; max-width: 100%; height: auto;'))!!}</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Controlling Your Devices</h4>
      <div class="help-subcontent">
        <p>The EMC<sup>20/20</sup> gives you the ability to control your output devices remotely. Typically, your output devices will be controlled through intelligent algorithms, working off of the data from your system's input devices.</p>
        <p>But, there may be times when you wish to take manual control of your output device's state, turning the device either <i>ON</i> or <i>OFF</i>. The Systsem Status page allows you to <i>bypass</i> the current state of your output device.</p>
        <p>If you would like to set your output device <i>ON</i>, begin by selecting the "ON" button in your output device's information panel. Once the "ON" button is selected, chose the amount of time you would like your device to stay <i>ON</i> from the "Bypass Time" drop down menu. Once you've selected the amount of time you'd like your device to stay <i>ON</i>, click the "Bypass" button. Your command will be sent to you system for execution. Allow some time for the system to confirm that it has received and executed your command. Once the task is complete, your device's state should change to reflect your requested bypass.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Algorithm Control</h4>
      <div class="help-subcontent">
        <p>While you have the ability to manually control your output devices, using your system's control algorithms can provide you with a more efficient and effective method of controlling your output devices.</p>
        <p>Your System Status page will indicate which algorithms are in control of your system, as well as what the status of your algorithms are, currently. By selecting an algorithm or output device, you'll be presented with the algorithm's or device's current status. This <i>ON</i>/<i>OFF</i> status can indicate either the current voting status of an algorithm or the physical output state of your output device. In the information panel, you can click on "Algorithm Info" to find out the reason why your algorithm or device is <i>ON</i> or <i>OFF</i>. Here you will see information related to the voting behind the algorithm, along with the current status of the algorithm's input devices.</p>
        <p>"Votes:" refers to the number of input devices and/or input algorithms which are calling for an "ON" state versus the number of votes required to change the current state. For example:</p>
        <p style="text-align: center;"><b>"Votes: 2/5"</b></p>
        <p>In the above example, the output device/algorithm has two inputs voting for the device to turn "ON" <b>but</b> will not turn on until five input devices vote for the "ON" state. See below for another example</p>
        <p style="text-align: center;"><b>"Votes: 4/2"</b></p>
        <p>In the above exmple, two votes are required to change the output device/algorithm's state to "ON". According to the example, the device has four votes in favor of an "ON" state. Therefor, we would expect the state of the output device/algorithm be "ON"; however, there are cases where the <i>votes may be ignored.</i></p>
        <p>Beyond the basic input device vote totals, the state of your device or algorithm may be dicated by several other factors. Below, we provide a brief explanation of some of the more common types of "Algorithm Info."</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Key Switch: OFF</p>
        <p>The key switch, located on the outside of the system's physical enclosure, has been read as being in the "OFF" position, thus bypassing the normal operation of the output device. While the key switch is active, the state of the output device may not be changed by any other factor. This factor does not effect virtual devices (i.e. those algorithms not immediately mapped to a physical device); virtual devices may represent intermediate algorithms, used to inform other algorithms which have been mapped to a physical output device.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Key Switch: ON</p>
        <p>The key switch, located on the outside of the system's physical enclosure, has been read as being in the "ON" position, thus bypassing the normal operation of the output device. While the key switch is active, the state of the output device may not be changed by any other factor. This factor does not effect virtual devices (i.e. those algorithms not immediately mapped to a physical device); virtual devices may represent intermediate algorithms, used to inform other algorithms which have been mapped to a physical output device.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Override: ON</p>
        <p>The output device or algorithm has received a command to bypass its normal operation and to stay "ON" for a preselected period of time. The output device or algorithm may be returned to normal operation by selecting, in the information panel, <i>OFF</i> and <i>Reset</i>. Then click <i>Bypass</i>.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Override: OFF</p>
        <p>The output device or algorithm has received a command to bypass its normal operation and to stay "OFF" for a preselected period of time. The output device or algorithm may be returned to normal operation by selecting, in the information panel, <i>ON</i> and <i>Reset</i>. Then click <i>Bypass</i>.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Toggle: ON</p>
        <p>The control algorithm has entered a <i>defaulted</i> state, and is currently toggled "ON".</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Toggle: OFF</p>
        <p>The control algorithm has entered a <i>defaulted</i> state, and is currently toggled "OFF".</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Defaulted: ON</p>
        <p>The algorithm or output device has lost the minimum number of input device to operate properly and will stay "ON" while it awaits data from the miniumum number of required inputs. You can find which devices are missing under the "Inactive Inputs" area, under "Algorithm Info" in the information panel.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Defaulted: OFF</p>
        <p>The algorithm or output device has lost the minimum number of input device to operate properly and will stay "OFF" while it awaits data from the miniumum number of required inputs. You can find which devices are missing under the "Inactive Inputs" area, under "Algorithm Info" in the information panel.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Defaulted: Toggle</p>
        <p>The algorithm or output device has lost the minimum number of input device to operate properly and will continue to toggle (switch) between "ON" and "OFF" while it awaits data from the miniumum number of required inputs. You can find which devices are missing under the "Inactive Inputs" area, under "Algorithm Info" in the information panel.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm: On-Delay</p>
        <p>The algorithm or output device has reached the minimum number of required votes to turn "ON", but has been setup to delay its start, to prevent premature changes to output devices, based on erratic or unstable input device readings. Once the delay is complete, the algorithm will reevaluate the state of its input and proceed to an "ON" or "OFF" state, accordingly.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm: Off-Delay</p>
        <p>The algorithm or output device has dropped below the minimum number of votes required, but remains on for some predefined period of time, to prevent overly rapid cycling of output devices.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm: On-Duration</p>
        <p>The algorithm or output device has met its minimum number of retired votes, and will remain on for a minimum period of time, as set by the "On-Duration," either to provide a more efficient use of the output device or simply to prevent overly rapid cycling of the output device.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">The Toggle Button</h4>
      <div class="help-subcontent">
        <p>Within the informatin panel for output devices and algorithms, there is a "Toggle: ON" or "Toggle: OFF" button, at the very bottom. This button can be useful in troubleshooting seemingly unresponsive output devices, by commanding the device to temporarily change its state for approximately three seconds before returning to the previously prescribed state.</p>
      </div>
    </div>
  </div>
  <!--End System Status Help Section-->

  <!--Start Zone Status Help Section-->
  <div class="android_help_content">
    <h3 class="help-h3" id="zonestatus-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Zone Status</h3>
      <div class="help-content">
        <div class="help-subcontent">
          <p>This page provides you, the user, with a listed view of your devices. Your devices are broken out into output devices (under "Control Zones") and input devices (under "Sensors Zones"). Within these two categories your devices are further sorted by the zone to which they have been assigned. Devices may be assigned to a zone based on their physical location, their participation in a particular algorithm, or some other reason your system administrator may have chosen, in order to help better sort your system's devices.</p>
        </div>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Tabs</p>
        <div class="help-subcontent">
          <p>Each tab corresponds to one of your system's zones. If a particular zone is not listed either under the "Control Zones" or "Sensors Zones", it is most likely that there is not an output device or input device, respectively, belonging to the missing zone; therefor, only zones containing at least one <i>active</i> or <i>inhibited</i> device will appear beneath the "Control Zones" or "Sensors Zones" headings.</p>
          <p>Selecting a zone tab will remove from view any devices that are not assigned to the selected zone. To view devices from a different zone, simply select the zone where you think the device has been assigned; or, else, select "All Zones", and all available output devices (under "Control Zones") or input devices (under "Sensors Zones") will be displayed. Be sure to scroll down if you do not immediately see the device you are looking for. If you have searched all zones and cannot find the device belonging to your system, contact your system administrator for further assistance.</p>
        </div>
        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Control Zones</h4>
        <div class="help-subcontent">
          <p>This section holds information regarding your physical output devices. Here you can check their current status and state, as well as bypass their state for a preselected period of time.</p>
          <p>Each device is displayed individually, listed sequentially, and grouped by zone.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Identifiers</p>
          <div class="help-subcontent">
            <p>Each devices is listed by its <i>name</i>, <i>[MAC address]</i>, and <i>physical location</i>. A device's name and physical location are assigned during a device's commissioning process by your system's administrator. A device's MAC address is a number which is unique to that physical device. A device's name and physical location may be changed; but the MAC address may not.</p>
          </div>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Conditions</p>
          <div class="help-subcontent">
            <p>A device's <b>Status</b> refers to whether a device is considered by the system to be <i>Active</i> or <i>Inhibited</i>. <i>Active</i> devices are monitored and reported normally. <i>Inhibited</i> device's reports and alarms are suppressed. This status may be initiated by your system's administrator and may be reversed in the event that a device's reports and alarms are available and relevant.</p>
            <p>A round <b>Alarm Indicator</b> may change color to indicate whether the device currently has any alarms associated with it. To view all current system alarms, visit the <a href="alarms-help">Alarms</a> page (accessible from your system's main menu).</p>
            <p>A device's <b>State</b> refers to its current expected output. This may be reported as either "ON" or "OFF".</p>
            <p>A device's <b>Last State Change</b> refers to the date and time associated with the devices last report of its current state.</p>
          </div>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Bypass</p>
          <div class="help-subcontent">
            <p>Generally, the state of an output device will be set by the algorithm mapped to the device; however, you may wish to temporarily "bypass" this state.</p>
            <p>First select one of the <b>Bypass Mode</b> options available, either "ON" or "OFF". This indicates what you wish the state of the output device to be.</p>
            <p>Next, select <b>Bypass Time</b> from the drop-down menu. This selection will let the system know how long you would like to keep the output device's state in the previously chosen <i>Mode</i>.</p>
            <p>Once the <i>Mode</i> and <i>Time</i> are selected, press the <b>Bypass</b> button, and wait while the page is refreshed. Your change may not appear immediately. Depending on local network traffic and speeds to and from your system, several seconds may pass before confirmation of your change is made. If it appears your bypass has not taken effect, please wait several seconds and refresh the page.</p>
            <p>If you would like to extend or shorten the length of your bypass, you may submit another bypass request by following the same steps as before.</p>
            <p>If you would like to end your bypass early and return the device to automatic, algorithm control, select a <i>Bypass Mode</i> of "ON" <i>or</i> "OFF" and select <b>Reset</b> as the <i>Bypass Time</i>; then press <i>Bypass</i>.</p>
          </div>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Missing Algorithm</p>
          <div class="hyourelp-subcontent">
            <p>Some output devices may also be labeled as "Missing Algorithm"; in this case, the device may not be bypassed by the user. This may occur while there is maintenance being done on a device's algorithm, or for devices that have been taken out of service.</p>
          </div>
        </div>
        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Sensors Zones</h4>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Device Information</p>
        <div class="help-subcontent">
          <p>For each input device, several pieces of identifying information are displayed, including the device's <i>name</i>, <i>[MAC address]</i>, <i>physical location</i>, and <i>status</i>. A device's name and physical location are assigned during a device's commissioning process by your system's administrator. A device's MAC address is a number which is unique to that physical device. A device's name and physical location may be changed; but the MAC address may not.</p>
          <p>A device's <b>Status</b> refers to whether a device is considered by the system to be <i>Active</i> or <i>Inhibited</i>. <i>Active</i> devices are monitored and reported normally. <i>Inhibited</i> device's reports and alarms are suppressed. This status may be initiated by your system's administrator and may be reversed in the event that a device's reports and alarms are available and relevant.</p>
          <p>A round <b>Alarm Indicator</b> may change color to indicate whether the device currently has any alarms associated with it. To see more information, regarding red or yellow alarms, simply hover over the <i>indicator</i>. To view all current system alarms, visit the <a href="alarms-help">Alarms</a> page (accessible from your system's main menu or by clicking on an active <i>alarm indicator</i>).</p>
        </div>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Readings</p>
        <div class="help-subcontent">
          <p>A particular device may be responsible for a single report type or it may be responsible for several report types. This area will show the latest reading of each of a device's report types; while the "Last Report" date and time shall refer to whichever report was last received.</p>
          <p>Each report type shall provide the name of the type of report, as well as the latest reading of that report type and whether or not the <i>setpoint</i> value has been crossed. When a reading goes beyond the associated setpoint, a message of "Exceeded" will be displayed; while, if a reading has not crossed the setpoint, a message of "Not Exceeded" will be displayed.</p>
          <p><i>For example</i>, if a typical winter temperature reading is above its setpoint, it may read "Not Exceeded"; but, once the device drops below its setpoint, a message of "Exceeded" will be displayed, until the temperature reading returns to a value above its setpoint, and which point the message will revert to "Not Exceeded".</p>
          <p>You may click on any of the input readings to be redirected to the <a href="reports-help">reports</a> page, where you can see past readings.</p>
        </div>
      </div>
  </div>
    <!--End Zone Status Help Section-->

  <!--Start Reports Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="reports-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Reports</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General</h4>
      <div class="help-subcontent">
        <p>The <i>Reports</i> page provides a convenient way for you to view your device's data, over time. By hovering over or clicking on the graph, you may view individual readings, as well as see the name of the device associated with a particular data point.</p>
        <p>The page also supports other methods of filtering results. See below for further details on filtering your reports graph.</p>
        <p></p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Filters</h4>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">By Data Type</p>
        <p>The <b>Data Type</b> drop down menu provides you with the option of graphing any data type associated with any of your system's devices. Be aware, not every data type associated with your system may have reported on a given date; it is possible to request a graph for a particular data type and have no data returned. The devices associated with a particular data type will be named on the right side of the graph, when the data type specific graph is generated. Devices which are <i>greyed out</i> do not have any reports associated with the selected data type for the select time frame. </p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">By Zone</p>
        <p>Below the graph, the names of the zones associated with your system are listed. By selecting one of these zones, you may filter out any devices not in the selected zone.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">By Device</p>
        <p>By clicking on the name of a device, listed on the right side of the graph, you may enable/disable the display of the particular device's readings. You may even enable the display of a device's readings after it has been filtered by zone selection (i.e. you may graph all sensors from one zone, then enable one of the previously filtered devices to see; this would be analogous to temporarily placing the device in the zone, for graphing purposes).</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">By Date</p>
        <p>By selecting both a <b>Start Date</b> and a <b>Stop Date</b>, you may view data as far back as you like. Each graph is limited to only displaying seven consecutive dates at a time. If your selected date range exceeds seven consecutive days, your graph will be cut short, at the seventh day. </p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Show Retired Devices</p>
        <p>If you are looking for data related to a previously active but currently retired device, be sure to check the box beside the "Show Retired Devices" option, prior to selecting "Submit".</p>
      </div>
    </div>
  </div>
  <!--End Reports Help Section-->

  <!--Start Data Export Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="dataexport-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Data Export</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General</h4>
      <div class="help-subcontent">
        <p>The <i>Data Export</i> page allows you to generate and download tables (in <b>.csv</b> format) for specific device's particular data types over a given date range.</p>
      </div>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Devices</p>
        <p>Select, from this drop down menu, the device whose data you would like to download. Devices in this menu are sorted by zone. For systems with a significant number of sensors, please scroll down if you do not immediately see the desired device displayed in the drop down.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Data Type</p>
        <p>If a particular device has been selected from the <i>Devices</i> drop down menu, then the <i>data type</i> options listed in the associated drop down menu will be limited to those <i>data types</i> associated with the selected device.</p>
        <p>If no device has yet been selected, all of the <i>data types</i> associated with your system will be available; but, be advised, your <i>data type</i> selection may not persist once you've selected a device, if the selected device is not associated with the previously selected <i>data type</i>.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Dates</p>
        <p>Each <i>Start Date</i> and <i>End Date</i> must be formatted as <b>YYYY-MM-DD</b>, that is the year, followed by the month, followed by the day of the month. You may receive and error message if this format is not followed.</p>
      </div>
      <div class="help-subcontent">
        <p>Once you have made all of your selections, press the <b>Export Data</b> button at the bottom of the screen. Once selected, the page will attempt to download the <i>.csv</i> file to your device (laptop, desktop, or phone). <i>Depending on your browser configuration</i>, you may see one of several prompted options. You may be asked if you want to allow the download to proceed. Confirm whether or not you would like to continue the download. You may also be prompted to choose a download location on your local device. Please note the selected location, in order to later located the downloaded file.</p>
      </div>
    </div>
  </div>
  <!--End Data Export Help Section-->

  <!--Start Alarms Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="alarms-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Alarms</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General</h4>
      <div class="help-subcontent">
        <p>The <i>Alarms</i> page provides a single place to view all of the currently active and past alarms associated with your system. From here, you may manually clear an alarm or investigate possible solutions for an alarm state.</p>
        <p>The system employs both a <i>warning</i> alarm level, as well as a <i>critical</i> alarm level. <i>Warning</i> alarms appear as yellow indicators, while <i>critical</i> alarms appear in red. The use of two levels is meant to assist the user in better prioritizing their active alarms.</p>
        <p>Device reports/readings which surpass the users preset <i>alarm levels</i> will result in the generation of <i>critical</i> alarms. Users may also see <i>warning</i> alarms associated with a device's reports/readings. These less severe alarms are generated based on a ratio of the reading value, as it approaches the <i>alarm level</i>, with the device's <i>setpoint</i> acting as the other value assocated with this ratio. This <i>warning</i> will be, typically, generated as a reading approaches 80% of the critical alarm level value. Be aware, both the device's <i>alarm level</i> and the device's <i>setpoint</i> contribute to the point at which a <i>warning</i> alarm will be generated.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Active Alarms</h4>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Identifiers</p>
        <p>Each alarm features the name of the alarmed device and the data type on which the alarm is based.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Severity Indicator</p>
        <p>As mentioned above, alarms may be of two different levels: <i>critical</i> or <i>warning</i>. The round <i>alarm indicator</i> will display either red or yellow.</p>
        <p style="text-align: center">{!!HTML::image('images/yellowbutton.png')!!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!!HTML::image('images/redbutton.png')!!}</p>
        <p>Depending on the type of alarm, users may be presented with possible actions related to the alarm type shown. By hovering over the red or yellow <i>alarm indicator</i>, users may be presented with the option of navigating to the <i>setpoint</i> page, or users may be presented with the option of navigating directly to the <i>zone status</i> page. Simply clicking on the <i>alarm indicator</i> will redirect users to the another page where they may be able to take actions to resolve the alarm, or else where they might find more information regaring thte status of the alarmed device. If redirected, users may return the the alarms page using their browsers back button.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Alarm Message</p>
        <p>A text description of the alarm is provided. This description ought to give a simple explanation of the reason for the alarm state.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Start Time</p>
        <p>The <i>Start Time</i> is a date/time string representing the time at which the system initiated the alarm. This start time may be based on several factors. This time shall be the time on which any calculations of duration are based.</p>
        <p>The format of the <i>start time</i> corresponds to:</p><p style="text-align: center"><b>YYYY-MM-DD HH:MM:SS</b></p>
        <p>In other words, the format of the date/time is in the order of:</p><p style="text-align: center"><b>Year-Month-Day Hours:Minutes:Seconds</b></p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Duration</p>
        <p>The <i>Duration</i> value is the difference between the current time and the <i>start time</i> of the alarm.</p>
        <p>The format of the <i>duration</i> is:</p><p style="text-align: center;"><b>HH:MM:SS</b></p>
        <p>or, in other words:</p><p style="text-align: center;"><b>Hours:Minutes:Seconds</b></p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Clear Alarm</p>
        <p>Users have the option of manually clearing <i>active</i> alarms by selecting the <i>Clear Alarm</i> button. This option is useful in the event there are alarm conditions that no longer apply, such as a change in a device's <i>setpoint</i> and/or <i>alarm level</i>.</p>
        <p>Clearing an active alarm will not prevent the alarm from being regenerated if the reason for the alarm persists. In such a case, clearing the alarm will simply prompt the generation of a new alarm based on the same reason as the old alarm. Users may see an additional email alert, in such cases.</p>
        <p>If the user wishes to permanently prevent futher alarm generation for a particular device, they may request the device to be "inhibited" by their system administrator. Inhibited devices will not be allowed to report data and will be ignored by the system when generating alarms.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Alarm History</h4>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Identifiers</p>
        <p>Each alarm features the name of the alarmed device and the data type on which the alarm is based.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Alarm Message</p>
        <p>A text description of the alarm is provided. This description ought to give a simple explanation of the reason for the alarm state.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Start Time</p>
        <p>The <i>Start Time</i> is a date/time string representing the time at which the system initiated the alarm. This start time may be based on several factors. This time shall be the time on which any calculations of duration are based.</p>
        <p>The format of the <i>start time</i> corresponds to:</p><p style="text-align: center"><b>YYYY-MM-DD HH:MM:SS</b></p>
        <p>In other words, the format of the date/time is in the order of:</p><p style="text-align: center"><b>Year-Month-Day Hours:Minutes:Seconds</b></p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">End Time</p>
        <p>This is the time at which the alarm was cleared.</p>
        <p>The formatting of this time matches that of the <i>start time</i>.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Duration</p>
        <p>The <i>Duration</i> value is the difference between the current time and the <i>start time</i> of the alarm.</p>
        <p>The format of the <i>duration</i> is:</p><p style="text-align: center;"><b>HH:MM:SS</b></p>
        <p>or, in other words:</p><p style="text-align: center;"><b>Hours:Minutes:Seconds</b></p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Resolution</p>
        <p>There are several methods by which an alarm may be resolved.</p>
        <p>An alarm may be resolved by the <b>server</b> or the <b>system</b>. These resolution types indicate that the original condition which lead to the alarm has passed, and the condition of the device has either escalated to a higher alarm level, has de-escalated to a lower alarm level, or is no longer within the alarmed range.</p>
        <p>Alarms that have been cleared by a user, using the "Clear Alarm" button, will have a <i>resolution</i>  of <b>manual</b>.</p>
      </div>
    </div>
  </div>
  <!--End Alarms Help Section-->

  <!--Start Events Help Section-->
  <div class="android_help_content">
  <h3 class="help-h3" id="events-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Events &amp; Control History</h3>
    <div class="help-content">
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General</h4>
      <div class="help-subcontent">
        <p>This page is meant to provide users with information related to their system's control devices and algorithms.</p>
        <p>Users can look at device run times, as well as related algorithm "ON" state durations. Such information can help users better configure their system for optimal effiency and satisfaction.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Today and Yesterday</h4>
      <div class="help-subcontent">
        <p>Each of the output devices and algorithms associated with your system should be listed with their total associated <i>run time</i> (time in the "ON" state).</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Today's Totals</p>
        <p>The totals for this period are based on the <i>run times</i> for each device/algorithm since 12:00 AM (midnight) up to the present time. These times are calculated on page-load.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Yesterday's Totals</p>
        <p>The totals for this period are calculated, on page-load, for each device/algorithm, for the previous day, from 12:00 AM (midnight) to 12:00 AM (midnight).</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Custom Totals</h4>
      <div class="help-subcontent">
        <p>Users may also select to have the total run times for their devices or algorithms calculated individually across a custom time-frame. Users must simply select the device for which they would like these totals generated and select the time frame for these totals.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Weekly</p>
        <p>The weekly time frame will provide the user with the past seven days of <i>run time</i> totals for the selected device or algorithm.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Monthly</p>
        <p>The monthly time frame will provide the user with the past thirty days of <i>run time</i> totals for the selected device or algorithm.</p>
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Custom</p>
        <p>Users may select a custom time frame over which to total a selected device or algorithm's <i>run time</i> by clicking on the "CUSTOM" button, and selecting the preferred <i>start</i> and <i>end</i> dates.</p>
        <p>With a device/algorithm selected and the time frame specified, click "Submit". The page will reload with the requested <i>run time</i> displayed under the "Custom Total" heading.</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Active Events</h4>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Events History</h4>
      <div class="help-subcontent">
        <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
    </div>
  </div>
  <!--End Events Help Section-->

  <!--Start Your Account Help Section
  <h3 class="help-h3" id="youraccount-help">Your Account</h3>
  <div class="help-content">
    <h4 class="help-subhead">Subsection Head 1</h4>
    <div class="help-subcontent">
      <p class="help-content-detail">Subsection Detail</p>
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead">Subsection Head 2</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
    <h4 class="help-subhead">Subsection Head 3</h4>
    <div class="help-subcontent">
      <p>Enter information here...</p>
    </div>
  </div>
  End Your Account Help Section-->

  @if(Auth::user()->role >= 7)
  <!--Section for users with authorization 7 or higher-->
    <!--Start Algorithm Help Section -->
    <div class="android_help_content_admin">
    <h3 class="help-h3" id="algorithm-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Algorithms</h3>
      <div class="help-content">
        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General Algorithm Info</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Name</p>
          <p>This field helps identify the algorithm in the future. Names such as "Valve #3 Algorithm" or "Lobby Lights Algorithm" may provide additional clarity to users.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Output Device</p>
          <p>The output device is the device that will be controlled with the algorithm.</p>
          <p>By not selecting an output device, a virtual device may be created. Virtual devices may later be used as inputs for other algorithms. By acting as intermediate input/output devices, virtual devices may provide additional flexibility to output control.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Template</p>
          <p>The template describes what type of control devices are being used by the algorithm.</p>
          <p>Templates may provide relevant preconfigured algorithm parameters.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Sensor Types</p>
          <p>The sensors types show what different types of sensors are available to be used by the algorithm.</p>
          <p>If a type button is highlighted, then there are sensors in the system of that type to be used by the algorithm.</p>
          <p>Selecting one or more of the types will display the sensors of the selected type or types below.</p>
          <p>If a template was chosen, all related sensors will be displayed to be selected.</p>
          <p>If no template was chosen, select one of the highlighted sensor types to display the associated sensors.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Sensors</p>
          <p>The available sensors of the types selected above are shown with three options below each sensor. A given sensor may only satisfy one of the following roles for a given algorithm. The same device may be differently assigned as an input to another algorithm.</p>
          <p>The <i>Primary</i> option determines that the specific sensor will be included in the algorithm.</p>
          <p>The <i>Secondary</i> option determines that the specific sensor may be used as a replacement input, in the event a <i>primary</i> input has ceased reporting for a prolonged period of time.</p>
          <p>The <i>Not Used</i> option determines that the specific sensor may not effect the state of the algorithm.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Algorithm Parameters</p>
          <p>The algorithm parameters define how the algorithm will function with the sensors selected.</p>
          <p>These parameters are described specifically in the Parameters section.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Parameter Descriptions</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Logic Mode</p>
          <p>This parameter defines what form of logic will be used to relate the different sensors.</p>
          <p>The different logic modes include <i>AND</i>, <i>NAND</i>, <i>OR</i>, <i>NOR</i>, and <i>XOR</i>.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Votes</p>
          <p>This parameter is only relevant when using logic mode <i>AND</i>.</p>
          <p>The number of votes chosen determines the minimum number of sensors that must provide a high/on input state to the algorithm to produce a high/on output state. An input's state shall be determined by the device's setpoint and last reported value.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Polarity Mode</p>
          <p>The polarity of the algorthm determines whether or not the state produced by the algorithm will be inverted or not. This parameter may be used to accomodate devices which /use a low/on and high/off output signals.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Active Season</p>
          <p>The <i>Active Season</i> specifies whether or not the algorithm will observe the <i>Season Mode</i> (As set in the <a href="globalsettings-help">Global Settings</a>). If the algorithm has its <i>Active Season</i> specified as either <i>Summer</i> or <i>Winter</i>, then the algorithm will cease operation when the chosen season is no longer in effect. The algorithm shall resume operation once the system's <i>Season Mode</i> matches the algorithm's chosen season.</p>
          <p>Algorithms whose <i>Active Season</i> is set to <i>Year-Round</i> continue to operate, regardless of the system's current <i>Season Mode</i>.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Response</p>
          <p>The response field determines whether or not the output state change of the algorithm will be reported or not.</p>
          <p>This field has a direct application to lighting control.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">On Delay</p>
          <p>The <i>On Delay</i> determines how long an algorithm will wait to change a state from low/off to high/on, given sufficient votes for a change. If the input which prompted the device to turn on is no longer voting to change the state of the algorithm by the time the this delay is finished, the device will maintain a low/off state. This feature may be useful to prevent devices from responding too promptly to inputs that are prone to freqently or rapidly change their vote. May be cleared by user generated manual override.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Off Delay</p>
          <p>The off delay determines how long an algorithm will wait to change a state from high/on to low/off after the low/off state has been determined. May be cleared by user generated manual override.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">On Duration</p>
          <p>The on duration determines how long an output will remain in a high/on state before automatically changing back to a low/off state. Once an output has begun an on duration, said output will ignore changes to the state of its inputs, until the <i>on duration</i> time has expired. May be cleared by user generated manual override.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Default State</p>
          <p>In the event that input data to an algorithm becomes unavailable, either from a device not reporting or from a defaulted input algorithm, the output from the algorithm will default to the selected value of this field. The state of the algorithm will continue to be dictated by this selection so long as the conditions of the default remain or while no manual override commands are in effect. In other words, users may still issue manual overrides to an algorithm controlled output while the algorithm is in a default state. The default state will be reinstated, once the override command has timed out.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;"><b>Toggle Percent On</b></p>
          <p>If the algorithm's <i>default state</i> is of type <i>Toggle</i>, this field becomes available. The value of this field will dictate the percentage of the Toggle Period time which the algorithm will chose to be high/on, in the event of a defaultedd state.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;"><b>Toggle Period</b></p>
          <p>If the algorithm's <i>default state</i> is of type <i>Toggle</i>, this field becomes available. The value of this field will dictate the length of a single on/off cycle (with the high/on time dictated by the <i>Toggle Percent On</i> parameter). This cycle will repeat as long as the algorithm is in a <i>default</i> state.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Complex Algorithm Setup</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Initial Setup</p>
          <p>To create a complex logic algorithm, some basic algorithms must be created first.</p>
          <p>To create a basic algorithm please refer to the Basic section.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Virtual Outputs</p>
          <p>Virtual outputs are used to create complex algorithms by producing states based on different inputs without applying that output to an output device.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Other Devices</p>
          <p>To view other algorithms as inputs, select the <i>Other Devices</i> sensor type.</p>
          <p>Selecting these algorithms as inputs for the new algorithm will produce a complex algorithm.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Mixing Sensors and Algorithms</p>
          <p>When selecting inputs for the algorithm, both algorithms and sensors can be used together in the same algorithm.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Lighting Control Algorithm Setup</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Initial Setup</p>
          <p>To create a lighting control algorithm, three basic algorithms must be created first.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Basic Algorithm 1: Occupancy Algorithm</p>
          <p>The first basic algorithm is the occupancy algorithm.</p>
          <p>This algorithm must be set up with the specific occupancy sensor or sensors associated with the lights being controlled.</p>
          <p>The parameters for this algorithm must be the following:</p>
          <table class="table table-bordered table-responsive">
            <tr class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">
              <td>Logic Mode</td>
              <td>Votes</td>
              <td>Polarity Mode</td>
              <td>Season Mode</td>
              <td>Response</td>
              <td>On Delay</td>
              <td>Off Delay</td>
              <td>On Duration</td>
            </tr>
            <tr>
              <td>OR</td>
              <td>0</td>
              <td>Direct</td>
              <td>Off</td>
              <td>On</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </table>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Basic Algorithm 2: Light Sensor Algorithm</p>
          <p>The second basic algorithm is the light sensor algorithm</p>
          <p>This algorithm must be set up with the specific light sensor or sensors associated with the lights being controlled.</p>
          <p>The parameters for this algorithm must be the following:</p>
          <table class="table table-bordered table-responsive">
            <tr class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">
              <td>Logic Mode</td>
              <td>Votes</td>
              <td>Polarity Mode</td>
              <td>Season Mode</td>
              <td>Response</td>
              <td>On Delay</td>
              <td>Off Delay</td>
              <td>On Duration</td>
            </tr>
            <tr>
              <td>AND</td>
              <td># of Sensors*</td>
              <td>Direct</td>
              <td>Off</td>
              <td>On</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </table>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Basic Algorithm 3: Previous State Algorithm</p>
          <p>The third basic algorithm is the occupancy algorithm</p>
          <p>This algorithm must be set up with no inputs initially.</p>
          <p>The parameters for this algorithm must be the following:</p>
          <table class="table table-bordered table-responsive">
            <tr class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">
              <td>Logic Mode</td>
              <td>Votes</td>
              <td>Polarity Mode</td>
              <td>Season Mode</td>
              <td>Response</td>
              <td>On Delay</td>
              <td>Off Delay</td>
              <td>On Duration</td>
            </tr>
            <tr>
              <td>OR</td>
              <td>0</td>
              <td>Direct</td>
              <td>Off</td>
              <td>Off</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </table>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Lighting Control Algorithm</p>
          <p>This algorithm is to be setup using the previous three algorithms as the only inputs.</p>
          <p>The parameters for this algorithm must be the following:</p>
          <table class="table table-bordered table-responsive">
            <tr class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">
              <td>Logic Mode</td>
              <td>Votes</td>
              <td>Polarity Mode</td>
              <td>Season Mode</td>
              <td>Response</td>
              <td>On Delay</td>
              <td>Off Delay</td>
              <td>On Duration</td>
            </tr>
            <tr>
              <td>XOR</td>
              <td>0</td>
              <td>Direct</td>
              <td>Off</td>
              <td>On</td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </table>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Previous State</p>
          <p>To finish this setup, edit the Previous State algorithm, and select the Light Control algorithm as the only input.</p>
          <p>Leave all algorithm parameters as they were.</p>

          <p class="help-side-detail">* Number of votes used for the light sensor algorithm is up to the user.</p>
        </div>
      </div>
    </div>
    <!--End Algorithm Help Section -->

    <!--Start Web Mapping Help Section -->
    <div class="android_help_content_admin">
    <h3 class="help-h3" id="webmapping-help" style="margin-top: 20px;padding-top: 10px;border-top: 5px double black;font-size:30px;">Web Mapping</h3>
      <div class="help-content">
        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">General Web Mapping Information</h4>
        <div class="help-subcontent">
          <p>The main system dashboard is shown when the page loads.</p>
          <p>Dashboard item links will show sub items when clicked on.</p>
          <p>Charts will be show an image of a chart when clicked on.</p>
          <p>All others dashboard items will display a preview of the page when clicked on.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Change Dashboard Layout</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Changing Dashboard Item Layout Order</p>
          <p>Dashboard items can be reordered by clicking and holding a dashboard item and then dragging it to the desired position.</p>
          <p>Items cannot be moved between levels or pages. To Change this refer to the <i>Add Dashboard Items</i> section.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Add and Remove Dashboard Items</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Adding a Dashboard Item</p>
          <p>To add a dashboard item to the main system dashboard, click the + button located at the bottom right of the page.</p>
          <p>If there are option remaining in the predefined section, you can select from this list and set a name for the dashboard item.</p>
          <p>If there are no more predefined pages, or you want to create generic dashboard item, choose from the <i>Dashboard Item Type</i> list and enter a name.</p>
          <p>Finally click save and the item will be added at the bottom of the main system dashboard.</p>
          <p>To add a dashboard item within a link or dashboard item group, click on the item group and click add. Then follow the same process.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Edit a Dashboard Item</p>
          <p>To change the name of a dashboard item, click on the item and select the <i>Edit</i> button under the item to the far right.</p>
          <p>From here you are able to rename the dashboard item.</p>
          <p>To store these changes, click the <i>Save</i> button.</p>
          <p>To edit a dashboard chart, please refer to the <i>Custom Charts</i> section.</p>
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Removing a Dashboard Item</p>
          <p>To remove most dashboard items, click on the item's <i>Edit</i> button.</p>
          <p>In the edit pop up there is a <i>delete</i> button. Click it to remove this dashboard item and any items the are connected to it.</p>
        </div>

        <h4 class="help-subhead" style="padding-top: 10px;border-top: 4px solid #888888;">Custom Charts</h4>
        <div class="help-subcontent">
          <p class="help-content-detail" style="padding-top: 5px;border-top: 1px dashed #333333;">Customizing a Chart</p>
          <p>To customize a chart that has been added to the dashboard, click the chart item and click the <i>Edit Chart</i> button. This will bring you to a different page.</p>
          <p>On the <i>Edit Chart</i> page, each parameter of the chart can be set at the top. Devices can be selected at the bottom of the page and will be filtered based on the <i>Device Type</i> and <i>Zone</i> selected above.</p>
          <p>When all parameters are set and devices are selected, click save at the bottom of the page.</p>
          <p>If you decide not to customize this chart, then click cancel.</p>
          <p>To delete a chart, click on the <i>Delete</i> button on the bottom of the page.</p>
        </div>
      </div>
    </div>
    <!--End Web Mapping Help Section -->

    <!--Start System Configuration Help Section
    <h3 class="help-h3" id="systemconfiguration-help">System Configuration</h3>
    <div class="help-content">
      <h4 class="help-subhead">Subsection Head 1</h4>
      <div class="help-subcontent">
        <p class="help-content-detail">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 2</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 3</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
    </div>
    End System Configuration Help Section-->

    <!--Start Administration Help Section
    <h3 class="help-h3" id="administration-help">Administration</h3>
    <div class="help-content">
      <h4 class="help-subhead">Subsection Head 1</h4>
      <div class="help-subcontent">
        <p class="help-content-detail">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 2</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 3</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
    </div>
    End Administration Help Section-->

    <!--Start Customers Help Section
    <h3 class="help-h3" id="customers-help">Customers</h3>
    <div class="help-content">
      <h4 class="help-subhead">Subsection Head 1</h4>
      <div class="help-subcontent">
        <p class="help-content-detail">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 2</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 3</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
    </div>
    End Customers Help Section-->

    <!--Start Buildings Help Section
    <h3 class="help-h3" id="building-help">Buildings</h3>
    <div class="help-content">
      <h4 class="help-subhead">Subsection Head 1</h4>
      <div class="help-subcontent">
        <p class="help-content-detail">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 2</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 3</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
    </div>
    End Buildings Help Section-->

    <!--Start Users Help Section
    <h3 class="help-h3" id="users-help">Users</h3>
    <div class="help-content">
      <h4 class="help-subhead">Subsection Head 1</h4>
      <div class="help-subcontent">
        <p class="help-content-detail">Subsection Detail</p>
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 2</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
      <h4 class="help-subhead">Subsection Head 3</h4>
      <div class="help-subcontent">
        <p>Enter information here...</p>
      </div>
    </div>
    End Users Help Section-->

  <!--End 7+ auth level help section-->
  @endif

<!--End of modal content -->
</div>
<script>
  //send data to android device
   window.addEventListener('load', helpLoad, false);
   function helpLoad(){
      var array_title = [];
      var array_content = [];
      var array_admin = [];
      var array_admin_title = [];
      var x = document.getElementsByClassName("android_help_title");
      var y = document.getElementsByClassName("android_help_content");
      var z = document.getElementsByClassName("android_help_content_admin");
      var admintitle = document.getElementsByClassName("android_help_title_admin");
      var Authuser = "";
      Authuser = "{!!Auth::user()->role!!}";
      //check if there's any user logged in and store the username- send it to android app
      for(var i=0; i<x.length; i++){
        array_title[i] = x[i].innerHTML;
      }
      for (var i = 0; i < y.length; i++) {
        array_content[i] = y[i].outerHTML;
        //console.log(array_content[i].outerHTML);
      }
      for (var i = 0; i < z.length; i++) {
        array_admin[i] = z[i].outerHTML;
      }
      for (var i = 0; i < admintitle.length; i++) {
        array_admin_title[i] = admintitle[i].innerHTML;
      }
      sendDataToIOS["Authuser"] = Authuser;
      sendDataToIOS["help-user-title"] = array_title;
      sendDataToIOS["help-user-content"] = array_content;
      sendDataToIOS["help-admin-title"] = array_admin_title;
      sendDataToIOS["help-admin-content"] = array_admin;

      console.log(Authuser);

      try{
        Android.sendHelpData(Authuser, array_title, array_content, array_admin_title, array_admin);
      }catch(err){
        try{
          webkit.messageHandlers.iosApp.postMessage(sendDataToIOS);
        }catch(err){
          console.log(err.message);

        }
      }
  }

</script>
