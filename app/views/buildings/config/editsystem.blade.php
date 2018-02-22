<?php $title="Edit System"; ?>

@extends('layouts.wrapper')
@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/tgl.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>

<?php
	$help_id='systemconfiguration';
	$admincss="admin";

	if ($thisSystem->name == NULL) {
		$sysname="SYSTEM ".$thisSystem->id;
 	} else {
		$sysname= strtoupper($thisSystem->name);
	}
	if ($thisSystem->wireless_sensors > 0) {
		$WLSFlag=True;
	} else {
		$WLSFlag=False;
	}
	if ($thisSystem->bacnet_devices > 0) {
		$BACNETFlag=True;
	} else {
		$BACNETFlag=False;
	}
	$orphan_dev_bk="rgba(86, 86, 127,0.4)";
	$retired_dev_bk="rgba(20, 27, 38,0.7)";
	$default_dev_bk="rgba(41,81,109,0.3)"
?>


@if (isset($confirm))
	@if ($confirm == "updateSystem")
		<div style="padding: 10px"><div class="notify">System Configuration Updated.</div></div>
	@elseif ($confirm == "updateDevice")
		<div style="padding: 10px"><div class="notify">Devices Updated.</div></div>
	@elseif ($confirm == "updateMap")
		<div style="padding: 10px"><div class="notify">Mapping Updated.</div></div>
	@elseif ($confirm == "updateInput")
		<div style="padding: 10px"><div class="notify">Inputs Updated.</div></div>
	@else
		<div style="padding: 10px"><div class="notify">New Device # {{$confirm->id }} added.</div></div>
	@endif
@endif


<?php
	$netWarning = "WARNING! Changes to network settings may result in loss of communication with systems. Continue making changes to this network setting?";
	$tourcount_page = 0;
	$SV="ND";   // initialize SV
	$collabelcolor="#EED5EF";

	function load_dev_params($hw_version,$location,$hardware_options){
		$param_array = [
			"product_mode" => ucfirst($hardware_options[$hw_version][$location][5]),
			"product_type" => ($hardware_options[$hw_version][$location][2] == "Analog" || $hardware_options[$hw_version][$location][2] == "Digital")?"Sensor":"Unknown",
			"board_num" => ($hardware_options[$hw_version][$location][1] == 0)?'Main':$hardware_options[$hw_version][$location][1],
			"dev_num" => $hardware_options[$hw_version][$location][3],
			"data_type" => $hardware_options[$hw_version][$location][2]
		];
		return $param_array;
	}

	/*Used to assist in determining the possible Product Type assignments for a given device*/
	function HardwareDeviceID($location, $mode, $system_hw_version,$hardware_options){
		$rtn_array = array();
		$rtn_array = [
			"product_mode" => "Unknown",
			"product_type" => "Unknown",
			"board_num" => "Unknown",
			"dev_num" => "Unknown",
			"data_type" => "N/A"
		];
		switch($mode){
			case'wired':
				switch ($system_hw_version) {
					case '01.03.03.00':
						if(isset($hardware_options['01.03.03.00'][$location])){
							$rtn_array = load_dev_params('01.03.03.00',$location,$hardware_options);
						}/*fall thru*/
					case '01.03.00.00':
						if(isset($hardware_options['01.03.00.00'][$location])){
							$rtn_array = load_dev_params('01.03.00.00',$location,$hardware_options);
						}/*fall thru*/
					case '01.00.00.00':
						//if(isset($hardware_options['01.00.00.00'][$location])){
						//	$rtn_array = load_dev_params('01.00.00.00',$location,$hardware_options);
						//}
						break;
					case '11.14.14.14':
						if(isset($hardware_options['11.14.14.14'][$location])){
							$rtn_array = load_dev_params('11.14.14.14',$location,$hardware_options);
						}/*fall thru*/
					case '11.14.14.00':
						if(isset($hardware_options['11.14.14.00'][$location])){
							$rtn_array = load_dev_params('11.14.14.00',$location,$hardware_options);
						}/*fall thru*/
					case '11.14.00.00':
						if(isset($hardware_options['11.14.00.00'][$location])){
							$rtn_array = load_dev_params('11.14.00.00',$location,$hardware_options);
						}/*fall thru*/
					case '11.00.00.00':
						if(isset($hardware_options['11.00.00.00'][$location])){
							$rtn_array = load_dev_params('11.00.00.00',$location,$hardware_options);
						}
						break;
					case '11.13.14.00':
						if(isset($hardware_options['11.13.14.00'][$location])){
							$rtn_array = load_dev_params('11.13.14.00',$location,$hardware_options);
						}/*fall thru*/
					case '11.13.00.00':
						if(isset($hardware_options['11.13.00.00'][$location])){
							$rtn_array = load_dev_params('11.13.00.00',$location,$hardware_options);
						}/*fall thru*/
						if(isset($hardware_options['11.00.00.00'][$location])){
							$rtn_array = load_dev_params('11.00.00.00',$location,$hardware_options);
						}
						break;

					default:
						break;
				}
				break;
			case 'wireless':
			case 'echostream':
			case 'bacnetmstp':
			default:
				$rtn_array = [
					"product_mode" => "error",
					"product_type" => "error",
					"board_num" => "error",
					"dev_num" => "error",
					"data_type" => "N/A"
				];
				break;
		}
		return $rtn_array;

	}

   	function Expanpos($ID)  {
	    $BNum="4";
	    $DN="ND";
	    if ($ID<43)  {$BNum="  3";}
	    if ($ID<29)  {$BNum="  2";}
	    if ($ID<15)  {$BNum="  1";}
	    switch ($ID)  {  // relays out
	       case 10: case 24: case 38: case 52:  $DN=" O1"; break;
	       case 11: case 25: case 39: case 53:  $DN=" O2"; break;
	       case 12: case 26: case 40: case 54:  $DN=" O3"; break;
	       case 13: case 27: case 41: case 55:  $DN=" O4"; break;
	      // current loop out
	       case 14: case 28: case 42: case 56:  $DN="CLO"; break;
	     // digital inputs
	       case 5: case 19: case 33: case 47:   $DN="DI1"; break;
	       case 6: case 20: case 34: case 48:   $DN="DI2"; break;
	       case 7: case 21: case 35: case 49:   $DN="DI3"; break;
	       case 8: case 22: case 36: case 50 :   $DN="DI4"; break;
	       // current loop in
	       case 9: case 23: case 37: case 51 :   $DN="CLI"; break;

	    // analog inputs
	       case 1: case 15: case 29: case 43 :   $DN="AN1"; break;
	       case 2: case 16: case 30: case 44 :   $DN="AN2"; break;
	       case 3: case 17: case 31: case 45 :   $DN="AN3"; break;
	       case 4: case 18: case 32: case 46 :   $DN="AN4"; break;
    	}
		//print_r("Board- ".$BNum."<BR> Pos- ".$DN);
		$array['BN']="$BNum";
		$array['DN']="$DN";
  		return $array;
	}
	function StatusLogicEncode ($S,$I,$R)  {
	    // Retired overides all, then inhibit overrides status active, if none the undefined
	    // Valid States 0 - uncommisioned, 4 - active 5,7 Retired, 6 -  inhibited
	    $SV=$R + ($I<<1) + ($S<<2);

	    return $SV;
	}
	if(Auth::user()->auth_role >= 8){
		$disable_user = "";
	}else{
		$disable_user = 'disabled';
	}
?>
<div class="col-xs-12" class="js-supress-enter">
	<ul id="firstTabs"class="nav e-tabs">
		<li class="col-xs-12 col-sm-2 highlight-tab active" onclick="highlightTab(this)"><a data-toggle="tab" href="#system-configuration">SYSTEM CONFIGURATION</a></li>
		<li class="col-xs-12 col-sm-2" onclick="highlightTab(this)"><a data-toggle="tab" href="#system-devices">SYSTEM DEVICES</a></li>
		<li class="col-xs-12 col-sm-2" onclick="highlightTab(this)"><a data-toggle="tab" href="#device-mapping">DEVICE&nbsp;MAPPING ALGORITHMS
				@if(count($used_retired_devices))<!--what's used_retired_devices SCOPE?-->
					<span style="color: red;"><i class="glyphicon glyphicon-warning-sign retired-warning" title="Algorithm inputs contain retired devices."></i></span>
				@endif
			</a>
		</li>
		<li class="col-xs-12 col-sm-2" onclick="highlightTab(this)"><a data-toggle="tab" href="#zone-labels">ZONE SETUP</a></li>
		<li class="col-xs-12 col-sm-2" onclick="highlightTab(this)"><a data-toggle="tab" href="#system-commands">COMMANDS</a></li>
		@if(Auth::user()->auth_role >= 8)
			<li class="col-xs-12 col-sm-2" onclick="highlightTab(this)"><a href="{{URL::route('webmapping.index', [$thisBldg->id, $thisSystem->id])}}">WEB MAPPING</a></li>
		@endif

	</ul>
	<div id="myTabContent" class="tab-content">
		<!-- SYSTEM CONFIGURATION -->
		<div id="system-configuration" class="col-xs-12 container-fluid collapse active" style="margin-bottom: 3pt;">
			{{ Form::model($thisSystem, array("role" => "form")) }}
			<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
			<!-- SYS NAME / SYS MODE / ZONE COUNT -->
			<div class="col-xs-12 seamless_block_emc device-block stitch">
				<div class="form-group">
					<div class="col-xs-12 col-sm-4 row-padding" title="EXAMPLE: 'Residential System' or 'Industrial System'">
						System Name {{ Form::text('name', $thisSystem->name, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
					</div>
					<div class="col-xs-12 col-sm-4 row-padding">
						System Mode
						{{ Form::select('system_mode', array('0' => 'Reset', '1' => 'Operation', '2' => 'Commission'), $thisSystem->system_mode, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
					</div>
					<div class="col-xs-12 col-sm-4 row-padding">
						Number of System Zones
						{{ Form::text('system_zones', $thisSystem->system_zones, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
					</div>
				</div>
			</div>
			<!-- NET FORMAT / NUM WIRELESS SENSORS / NUM BACNET SENSORS -->
			<div class="col-xs-12 seamless_block_emc device-block stitch">
				<div class="form-group">
			        <div class="col-xs-12 col-sm-4 row-padding">
			        	Network Format
			        	{{ Form::select('coordinator_format', array('1' => 'BACnet', '2' => 'EMC Wireless', '4' => 'Inovonics', '3' => 'BACnet & EMC WireLess', '5' => 'BACnet & Inovonics', '6' => 'EMC Wireless & Inovonics'), $thisSystem->coordinator_format, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
		        	</div>
					<div class="col-xs-12 col-sm-4 row-padding">
						Number of Wireless Sensors
						{{ Form::text('wireless_sensors', $thisSystem->wireless_sensors, array("class" => "form-control", "style" => "color:black")) }}
					</div>
					<div class="col-xs-12 col-sm-4 row-padding">
						Number of Bacnet Devices
						{{ Form::text('bacnet_devices', $thisSystem->bacnet_devices, array("class" => "form-control", "style" => "color:black")) }}
					</div>
				</div>
			</div>
			<!-- SEASON MODE / TEMP FORMAT /COORD MAC -->
			<div class="col-xs-12 seamless_block_emc device-block stitch" style="padding-bottom: 10pt">
				<div class="form-group">
					<div class="col-xs-12 col-sm-4 row-padding">
						Season Mode
						{{ Form::select('season_mode', array('0' => 'Winter', '1' => 'Summer'), $thisSystem->season_mode, array("class" => "form-control", "style" => "color:black")) }}
					</div>
					<div class="col-xs-12 col-sm-4 row-padding">
						Temperature Format
						{{ Form::select('temperature_format', array('C' => 'Centigrade', 'F' => 'Fahrenheit'), $thisSystem->temperature_format, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
					</div>
		        	<div class="col-xs-12 col-sm-4 row-padding hidden" >
		        		Coordinator MAC
		        		{{ Form::text('coordinator_mac', $thisSystem->coordinator_mac, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
	        		</div>
				</div>
			</div>
			<!-- ETH MAC / ETH PORT / WIRELESS PORT / WIRELESS IP -->
			<div class="col-xs-12 seamless_block_emc device-block stitch" style="border-top: solid 2px white;">
				<div class="form-group">
					<div class="col-xs-12 col-sm-4 row-padding" title="EXAMPLE: 00:00:00:00:00:00">
						Ethernet MAC
						{{ Form::text('net_mac', $thisSystem->net_mac, array("class" => "form-control", "style" => "color:black", $disable_user)) }}
					</div>
					<div class="col-xs-6 col-sm-2 row-padding static-network-settings">
						Ethernet Port
						{{ Form::text('ethernet_port', $thisSystem->ethernet_port, array("class" => "form-control", "style" => "color:black", $disable_user, "onChange" => "confirmNetworkChange(this)")) }}
					</div>
	                <div class="col-xs-6 col-sm-2 row-padding static-network-settings">
	                	Wireless Port
	                	{{ Form::text('wireless_port', $thisSystem->wireless_port, array("class" => "form-control", "style" => "color:black", 'disabled')) }}
	            	</div>
		         	<div class="col-xs-12 col-sm-4 row-padding">
			         	Wireless IP
			         	{{ Form::text('wireless_ip', $thisSystem->wireless_ip, array("class" => "form-control", "style" => "color:black", 'disabled')) }}
		         	</div>
				</div>
			</div>
			<!-- ETH IP / DOWNLINK / STATIC IP -->
			<div class="col-xs-12 seamless_block_emc device-block stitch" style="padding-bottom: 10pt;">
				<div class="form-group">
	            	<div class="col-xs-12 col-sm-4 row-padding">
		            	Ethernet IP
		            	{{ Form::text('ethernet_ip', $thisSystem->ethernet_ip, array("class" => "form-control", "style" => "color:black", 'disabled', "onChange" => "confirmNetworkChange(this)")) }}
	            	</div>
					<!-- DOWNLINK -->
					<div class="col-xs-12 col-xs-offset-0 col-sm-offset-1 col-sm-2 device-block" style="padding: 4pt 2pt; text-align: center;">
						<small>
							Local IP Configuration:
						</small>
						<div  style="padding: 2pt 2pt 2pt 20pt;">
							<input class="tgl tgl-skewed js-confirm"
								type="checkbox"
								name="downlink"
								id="downlink" @if($thisSystem->downlink) checked @endif onChange="ipConfigUpdate()" data-confirm="{{$netWarning}}">
							<label class="tgl-btn" data-tg-off="STATIC" data-tg-on="DYNAMIC" for="downlink"></label>
						</div>
					</div>
					<div class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-1 row-padding static-network-settings">
			         	Local Static IP (Static Network Setting)
			         	{{ Form::text('static_ip', $thisNetwork->static_ip, array("class" => "form-control", "style" => "color:black", $disable_user, "onChange" => "confirmNetworkChange(this)")) }}
		         	</div>
				</div>
			</div>
			<!-- NETMASK / GATEWAY / DNS NAMESERVER -->
			<div class="col-xs-12 seamless_block_emc device-block stitch static-network-settings"  style="padding-bottom: 10pt;" title="Changes to network configuration settings may not take effect until after the system has been rebooted.">
				<div class="form-group">
		         	<div class="col-xs-12 col-sm-4 row-padding">
			         	Netmask (Static Network Setting)
			         	{{ Form::text('netmask', $thisNetwork->netmask, array("class" => "form-control", "style" => "color:black", $disable_user, "onChange" => "confirmNetworkChange(this)")) }}
		         	</div>
		         	<div class="col-xs-12 col-sm-4 row-padding">
			         	Gateway (Static Network Setting)
			         	{{ Form::text('gateway', $thisNetwork->gateway, array("class" => "form-control", "style" => "color:black", $disable_user, "onChange" => "confirmNetworkChange(this)")) }}
		         	</div>
		         	<div class="col-xs-12 col-sm-4 row-padding">
			         	DNS Name Server (Static Network Setting)
			         	{{ Form::text('dns_nameserver', $thisNetwork->dns_nameserver, array("class" => "form-control", "style" => "color:black", $disable_user, "onChange" => "confirmNetworkChange(this)")) }}
		         	</div>
				</div>
			</div>
			<!-- SYSTEM - HARDWARE SELECT -->
			<div class="col-xs-12 seamless_block_emc device-block stitch" style="padding-top: 10pt; border-top: solid 2px white;">
				<div class="form-group">
					<?php
						$boom = explode(".", $thisSystem->hardware_version);
					?>
					<div class="col-xs-12 col-sm-4 row-padding">
						Main Board Hardware Version
						{{ Form::select('main_board_version', array('01' => 'M4613-001 - Main Board', '11' => 'M4613-011- Expanded Main Board'), $boom[0], array("class" => "form-control", "style" => "color:black", "id" => "hardware_version_form", "onChange" => "hardwareVersionUpdate('mainboard')")) }}
					</div>
					<div class="col-xs-12 col-xs-offset-0 col-sm-4 row-padding">
						Number of Expansion Boards
						{{ Form::select('extender_boards', array('0','1','2','3'),$thisSystem->extender_boards, array("class" => "form-control", "style" => "color:black", $disable_user, "id" => "expansion_board_count", "onChange" => "expBoardCountUpdate()")) }}
					</div>
					<div class="col-xs-12 col-xs-offset-0 col-sm-2 col-sm-offset-1  device-block" style="padding: 4pt 2pt; text-align: center;">
						<span title="Use devices from matching hardware configurations to re-enable retired/inhibited devices when turning 'ON' hardware" >
							<small>
								Reactivation:
							</small>
							<div style="padding: 2pt 2pt 2pt 20pt;">
								<input class="tgl tgl-skewed"
									type="checkbox"
									name="reactivate"
									id="reactivate" checked>
								<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="reactivate"></label>
							</div>
						</span>
					</div>
				</div>
			</div>
			<!-- EXPANSION BOARD - HARDWARE SELECT -->
			<div id="exp_board_div" class="col-xs-12 seamless_block_emc device-block stitch" style="border-bottom: solid 2px white; padding-bottom: 15px;">
				<div class="form-group">
					<div class="col-xs-12 col-sm-4" title="Bottom Board">
						Expansion Board 1
						{{ Form::select('exp_board_1_version', array(), isset($boom[1])?$boom[1]:"00" , array("class" => "form-control", "style" => "color:black", $disable_user, "id" => "exp_board_1_version_form", "onChange" => "hardwareVersionUpdate('expboard')")) }}
					</div>
					<div class="col-xs-12 col-sm-4">
						Expansion Board 2
						{{ Form::select('exp_board_2_version', array(), isset($boom[2])?$boom[2]:"00" , array("class" => "form-control", "style" => "color:black", $disable_user, "id" => "exp_board_2_version_form", "onChange" => "hardwareVersionUpdate('expboard')")) }}
					</div>
					<div class="col-xs-12 col-sm-4">
						Expansion Board 3
						{{ Form::select('exp_board_3_version', array(), isset($boom[3])?$boom[3]:"00" , array("class" => "form-control", "style" => "color:black", $disable_user, "id" => "exp_board_3_version_form", "onChange" => "hardwareVersionUpdate('expboard')")) }}
					</div>
				</div>
			</div>
			<div class="col-xs-12 seamless_block_emc device-block stitch">&nbsp;</div>
			<!-- EXPANSION I/O SELECTION -->
			<div id="adjustment-warning" class="col-xs-12 seamless_block_emc device-block stitch" style="text-align: center; color: red">
				Invalid Hardware Configuration Selected.
				<br>
				Once you've selected a valid hardware configuration, your I/O options will be displayed here.
			</div>
			@foreach($hardware_options as $hardware_option => $hwo)
				<div id="hw-select-section-{{$hardware_option}}" class="form-group col-xs-12 seamless_block_emc device-block stitch">
					<div class="col-xs-12" style="border:solid 1px rgba(255,255,255,0.5)">
						<div class="col-xs-12" style="color: rgba(0,0,0,0.1);"><small><small>{{$hardware_option}}</small></small></div><!-- hw version watermark -->
						@foreach($hwo as $location => $wired_dev)
							<div class="col-xs-6 col-sm-2 device-block" style="padding: 4pt 2pt; text-align: center;">
								<small>
									{{$wired_dev[0].' '.$wired_dev[1].', '.$wired_dev[2].' '.$wired_dev[3].':'}}
								</small>
								<div style="padding: 2pt 2pt 6pt 20pt;">
									<?php $str_hw_version = str_replace('.', '-', $hardware_option); ?>
									<input class="tgl tgl-skewed"
										type="checkbox"
										name="expansion_io-{{$str_hw_version}}-{{$location}}"
										id="expansion_io-{{$str_hw_version}}-{{$location}}"
										@if(isset($active_components[$hardware_option][$location])) {{$active_components[$hardware_option][$location]==1?"checked":""}} @endif>
									<label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="expansion_io-{{$str_hw_version}}-{{$location}}"></label>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endforeach
			<div class="col-xs-12 seamless_block_emc device-block stitch">&nbsp;</div>
			<div class="col-xs-12 seamless_block_emc device-block stitch">&nbsp;</div>
			<!-- SAVE -->
			<div class="col-xs-12 seamless_block_emc device-block stitch">
				<div class="form-group">
		  			<div class="col-xs-12" style="text-align: center; padding-bottom: 10px;">
		  				<button class="btn btn-lg btn-primary" type="submit" name="Config">
		  					Save
		  				</button>
					</div>
		            <div class="col-xs-12" style="text-align: center">
		            	<b>When initially configuring a new system or modifying an existing, Please click on Save, before proceeding to device definitions</b>
		        	</div>
				</div>
			</div>
			<div class="col-xs-12 seamless_block_emc device-block stitch">
				&nbsp;
			</div>
			{{ Form::close() }}
		</div>
		<!-- end SYSTEM CONFIGURATION -->
		<!-- SYSTEM DEVICES -->
		<?php
			// Show Utility Devices option only when this building is in one of these areas
			$boroughs = ["Manhattan", "Bronx", "Brooklyn", "Queens", "Staten Island"];
		?>
		<div id="system-devices" class="col-xs-12 container-fluid collapse">
			<ul id="secondTabs" class="nav e-tabs">
				<li class="col-xs-12 col-sm-3 highlight-tab active" onclick="changeDeviceList(this)"><a data-toggle="collapse" href="#system-devices-relays">Wired Relay Outputs</a></li>
				<li class="col-xs-12 col-sm-3" onclick="changeDeviceList(this)"><a data-toggle="collapse" href="#system-devices-wired">Wired Sensor Inputs</a></li>
				<li class="col-xs-12 col-sm-3" onclick="changeDeviceList(this)"><a data-toggle="collapse" href="#system-devices-wireless">Wireless Sensors</a></li>
				<li class="col-xs-12 col-sm-3" onclick="changeDeviceList(this)"><a data-toggle="collapse" href="#system-devices-bacnetmstp">BacNet MSTP Devices</a></li>
				@if(strcasecmp($thisBldg->state, 'NY')==0)
					@foreach($boroughs as $borough)
						@if(strcasecmp($thisBldg->city, $borough) == 0)
							<li class="col-xs-12 col-sm-3" onclick="changeDeviceList(this)"><a data-toggle="collapse" href="#system-devices-utility">Utility Devices</a></li>
						@endif
					@endforeach
				@endif
			</ul>
			<div id="myTabContent" class="tab-content">
				
				<!-- WIRED RELAY OUTPUTS -->
				<div id="system-devices-relays" class="col-xs-12 container-fluid collapse device-section system-devices in">
					{{ Form::open(array("role" => "form")) }}
					<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
					@if (isset($relay_devices))
						<div class="col-xs-12 device-title">
							<div class="col-xs-9 row-padding">
								Wired Relay Outputs
							</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">
									Save
								</button>
							</div>
						</div>
						<?php 	$tourcount_page = 0; ?>
						@foreach ($relay_devices as $device)
							<?php
							$SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
							$dev_bg = ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)? " device_retired_bg" :(($SV == 6 or $SV == 2)? " device_inhibited_bg":"");
							$dev_bg = ($SV == 6 or $SV == 2)? " device_inhibited_bg":"";
							$retired_dev = ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)? TRUE:FALSE;
							$product_params = HardwareDeviceID($device->location, $device->device_mode, $thisSystem->hardware_version,$hardware_options);
							?>
							<div class="col-xs-12 seamless_block_emc device-block{{$dev_bg}}">
								<div class="form-group">
									<div class="col-xs-12 col-md-3 row-padding device-subtitle">
										@if(!$retired_dev)
											Board - {{$product_params['board_num']}}<br>
											{{$product_params['data_type']}} - {{$product_params['dev_num']}}<br>
											Device # {{$device->id}}
										@else
											<br>
											Device # {{$device->id}}
											<br>
											<br>
										@endif

										<input type="hidden" name="Device:{{$device->recnum}}[device_type]" value="wired_relay">
										<input type="hidden" name="Device:{{$device->recnum}}[location]" value="{{$device->location}}">
										<input type="hidden" name="Device:{{$device->recnum}}[device_mode]" value="wired">
										<input type="hidden" name="Device:{{$device->recnum}}[mac_address]" value="-">
	                                    <input type="hidden" name="Device:{{$device->recnum}}[short_address]" value="{{$device->short_address}}">
	                                    <input type="hidden" name="Device:{{$device->recnum}}[reporttime]" value="NA">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">
										Product Type
										<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[product_id]" {{$disable_user}}>
											<option value="direct" >
												Direct Connection
											</option>
											@foreach ($products as $product)
												@if (($product->product_type == "Relay") and ($product->mode=="Output")and ($product->direct!="BacNet"))
													<option value="<?=$product->product_id?>" @if ($device->product_id == $product->product_id) {{ "selected" }} @endif><?=$product->name?></option>
								  	       		@endif
											@endforeach
										</select>
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Device Function
										<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[device_function]" {{$disable_user}}>
											<option value="Choose">
												Choose a Function
											</option>
											@foreach ($devicetypes as $function)
											<option value="<?=$function?>" @if ($device->device_function == $function) {{ "selected" }} @endif><?=$function?></option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-12 col-md-3 row-padding">
										Name
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[name]" type="text" value="{{$device->name}}">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">
										Physical Location
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[physical_location]" type="text" value="{{$device->physical_location}}">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">
										Zone
										{{Form::select("Device:".$device->recnum."[zone]",$zone_labels,$device->zone,["class" => "form-control", "style" => "color:black"])}}
									</div>
									<div class="col-xs-12 col-md-3 row-padding">
										Functional Description
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[functional_description]" type="text" value="{{$device->functional_description}}" {{$disable_user}}>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12 col-sm-2 row-padding device-block" style="text-align: center;" title="You may adjust this relay device status from the SYSTEM CONFIGURATION tab">
									<?php $tourcount_relays = 0;//allow on first entry ?>
									<small @if($tourcount_page < 1) class = "tour" id="{{$tourcount_page}}" data-tour-title="Status" data-tour-content="This device can be activated "@endif>
										<?php $tourcount_page+=1; ?>
										Status
									</small>
									<?php $tourcount_relays = 1; ?>
										<p>
											<big><big>
												<input type="hidden" name="Device:{{$device->recnum}}[status]" value="{{$SV}}">
												@if($SV == 4)
														Active
												@elseif($SV == 6 or $SV == 2)
														Inhibited
												@elseif($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)
														Retired
												@else
													Unable to Determine Status
												@endif
											</big></big>
										</p>
									</div>
									<div class="col-xs-12 col-sm-10 row-padding">Comments/Notes
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[comments]" type="text" value="{{$device->comments}}">
									</div>
								</div>
							</div>
							<br>
						@endforeach
						<div class="col-xs-12 seamless_block_emc device-title">
			  				<div class="col-xs-9 row-padding">
			  					Wired Relay Outputs
		  					</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">
									Save
								</button>
				  			</div>
						</div>
					@else
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">
								No Wired Relays
							</div>
						</div>
					@endif
					{{ Form::close() }}
				</div> 
				<!-- end WIRED RELAY OUTPUTS -->
				<!-- WIRED SENSOR INPUTS -->
				<div id="system-devices-wired" class="col-xs-12 container-fluid collapse device-section system-devices">
					{{ Form::open(array("role" => "form")) }}
					<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
					@if (isset($wired_input_devices))<!--if the system has wired inputs-->
						<div class="col-xs-12 seamless_block_emc device-title">
				  			<div class="col-xs-9 row-padding">Wired Sensors</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
					  		</div>
						</div>
						<br>
						@foreach ($wired_input_devices as $device)
							<?php
							$SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired);
							$dev_bg = ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)? " device_retired_bg" :(($SV == 6 or $SV == 2)? " device_inhibited_bg":"");
							$retired_dev = ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)? TRUE:FALSE;
							$product_params = HardwareDeviceID($device->location, $device->device_mode, $thisSystem->hardware_version,$hardware_options);
							?>
							<div class="col-xs-12 seamless_block_emc device-block{{$dev_bg}}">
								<div class="form-group">
									<div class="col-xs-12 col-md-3 row-padding device-subtitle">
										@if(!$retired_dev)
											Board - {{$product_params['board_num']}}<br>
											{{$product_params['data_type']}} - {{$product_params['dev_num']}}<br>
											Device # {{$device->id}}
										@else
											<br>
											Device # {{$device->id}}
											<br>
											<br>
										@endif

										<input type="hidden" name="Device:{{$device->recnum}}[device_type]" value="wired_relay">
										<input type="hidden" name="Device:{{$device->recnum}}[location]" value="{{$device->location}}">
										<input type="hidden" name="Device:{{$device->recnum}}[device_mode]" value="wired">
										<input type="hidden" name="Device:{{$device->recnum}}[mac_address]" value="-">
										<input type="hidden" name="Device:{{$device->recnum}}[short_address]" value="{{$device->short_address}}">
										<input type="hidden" name="Device:{{$device->recnum}}[reporttime]" value="NA">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Product Type
										<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[product_id]" {{$disable_user}}>
											@foreach ($products as $product)
												@if (($product->product_type == "Sensor") and ($product->mode=="Input")and ($product->hardwarebus="Wired") and (substr($product->product_id,0,2)=="AN" or substr($product->product_id,0,1)=="D" or substr($product->product_id,0,1)=="C"))
													<option value="<?=$product->product_id?>" @if ($device->product_id == $product->product_id) {{ "selected" }} @endif><?=$product->name?></option>
								  	       		@endif
											@endforeach

										</select>
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Device Function
										<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[device_function]" {{$disable_user}}>
											<option value="Choose">Choose a Function</option>
											@foreach ($devicetypes as $function)
												<option value="<?=$function?>" @if ($device->device_function == $function) {{ "selected" }} @endif><?=$function?></option>
											@endforeach
										</select>
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Name
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[name]" type="text" value="{{$device->name}}">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Physical Location
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[physical_location]" type="text" value="{{$device->physical_location}}">
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Zone
										{{Form::select("Device:".$device->recnum."[zone]",$zone_labels,$device->zone,["class" => "form-control", "style" => "color:black"])}}
									</div>
									<div class="col-xs-12 col-md-3 row-padding">Functional Description
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[functional_description]" type="text" value="{{$device->functional_description}}" {{$disable_user}}>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12 col-sm-2 row-padding device-block" style="text-align: center;" title="You may adjust this wired device status from the SYSTEM CONFIGURATION tab">
										<small>
											Status
										</small>
										<p>
											<big><big>
												<input type="hidden" name="Device:{{$device->recnum}}[status]" value="{{$SV}}">
												@if($SV == 4)
														Active
												@elseif($SV == 6 or $SV == 2)
														Inhibited
												@elseif($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1)
														Retired
												@else
													Unable to Determine Status
												@endif

											</big></big>
										</p>
									</div>
									<div class="col-xs-12 col-sm-10 row-padding">Comments/Notes
										<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[comments]" type="text" value="{{$device->comments}}">
									</div>
								</div>
							</div>
							<br>
						@endforeach
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">Wired Sensors</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">
									Save
								</button>
							</div>
						</div>
					@else<!--if the system does not have wired inputs-->
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">No Wired Sensor Inputs</div>
						</div>
					@endif
					{{ Form::close() }}
				</div> <!-- enc WIRED SENSOR INPUTS -->
				<!-- WIRELESS SENSOR INPUTS -->
		        <!--  Will appear in two groups - mapped or commissioned and unmapped or needing commissioning
		          commissioned sensors can only have the physical location changes, notes and status changed
		          status options to retire, inhibit or activate
		          Non commissioned sensors will require the entry of a physical location and will be assigned a device number upon
		          selection of a commissioning button  active, inhibited and retired all = 0 and no device id = 0
		          to replace a commissioned sensor - one with a device number and in active, retired or inhibited equal 1
		          will have additional input field to indicate a replacement where the device ID of the unit it is replacing is
		          indicated in a drop down of retired devices.  -->
				<div id="system-devices-wireless" class="col-xs-12 container-fluid collapse device-section system-devices">
					{{ Form::open(array("role" => "form")) }}
					<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
					@if($WLSFlag)<!--if the system has wireless sensors-->
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">
								Wireless Sensors
							</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
							</div>
						</div>
						<br>
						<?php for($i=0;$i<2;$i++)  {
							if ($i==0) {
								$Status=0;
								// look for orphaned devices if none skip to recognized only
								$Orphanflag=0;
								foreach ($wirelessdevices as $device) {
									if ($device->id == 0 and $device->retired != 1) {
										$Orphanflag=1;
									}
								}
								if ($Orphanflag==0){
									$i=1;
									$Status=1;
								}
							} else {
								$Status=1;
							}
							{    //loop bracket
							// loops twice non commissioned first, then commissioned
						?>
						@if ($Status==0)
							<div class="col-xs-12 seamless_block_emc">
								<div class="col-xs-12 row-padding" style="font-weight: bold; color: #FF5544">
									Orphaned Devices
								</div>
							</div>
						@else
							<div class="col-xs-12 seamless_block_emc device-subtitle">
								<div class="col-xs-12 row-padding" style="font-weight: bold; color: #55FF44">
									Recognized Devices
								</div>
							</div>
						@endif
						<?php $deviceCount = 0; ?>
						@foreach ($wirelessdevices as $device)
							<?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired) ?>
							<!-- Loop non commissioned devices first status=0 then status=1-->
							<?php   $deviceCount++; ?>
							@if ((($device->device_mode == "echostream") or ($device->device_mode == "wireless")) and $device->status == $Status)
								<?php
									if ($Status==0) {
										$dev_bg = ' device_orphan_bg';
									} else if ($device->retired==1){
										$dev_bg = ' device_retired_bg';
									} else if ($device->inhibited==1){
										$dev_bg = ' device_inhibited_bg';
									} else {
										$dev_bg = '';
									}
									$ProdOKFlag=0;
									$PN="";/*product name*/
									$PF="";/*product function*/
									$PM="";/*product mode*/
									$PRTime="5";/*default product report time*/
									foreach ($products as $product) {
										if ($product->product_id==$device->product_id) {
											$PN=$product->name;
											$PF=$product->function;
											$PM=$product->mode;
											// define default paramaters or for defined devices get from device table
											if ($Status==0 and $device->reporttime=="") {
												$PRTime=$product->reporttime;
											} else {
												$PRTime=$device->reporttime;
											}
											$ProdOKFlag=1;
										}
									}
									if($PM=="") {
										$PM="Undefined";
									}

								?>
								<div class="col-xs-12 seamless_block_emc device-block{{$dev_bg}}">
									<div class="form-group">
										<div class="col-xs-12 device-subtitle">
											<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 row-padding"><small>Device:</small> #{{$device->id}}<BR>
												{{"<small>Mac Address: </small>".$device->mac_address."<BR>"}}
												{{"<small>Short Address: </small>".$device->short_address}}
												<input type="hidden" name="Device:{{$device->recnum}}[device_type]" value="sensor">
												<input type="hidden" name="Device:{{$device->recnum}}[location]" value="{{$device->location}}">
												<input type="hidden" name="Device:{{$device->recnum}}[device_mode]" value="{{$device->device_mode}}">
												<input type="hidden" name="Device:{{$device->recnum}}[mac_address]" value="{{$device->mac_address}}">
												<input type="hidden" name="Device:{{$device->recnum}}[short_address]" value="{{$device->short_address}}">
												<input type="hidden" name="Device:{{$device->recnum}}[product_id]" value="{{$device->product_id}}">
												<input type="hidden" name="Device:{{$device->recnum}}[device_function]" value="{{$device->device_function}}">
											</div>
											<div class="col-xs-6 col-sm-3col-md-4 col-lg-3 row-padding"><small>Product Type:</small><BR>
												{{$PN}}
												@if ($ProdOKFlag==0)
													{{"<font color='#FF5544'><b><br> Product Type not defined in Product_Type Table
													<BR>Update table before proceeding</b></font>"}}
												@endif
											</div>
											<div class="col-xs-6 col-sm-3col-md-4 col-lg-3 row-padding"><small>Device Function:</small><BR>
												{{$PF}}
											</div>
											@if ($Status==0 and $ProdOKFlag==1)
												<div class="col-xs-12">
													<div class="col-xs-12 col-sm-6 row-padding">New or Replacement for Device #
														<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]">
															<option value="New" <? if ($SV == "ND") { echo "selected"; }?> >New</option>
															@foreach ($wirelessdevices as $option)
																@if ($option->retired==1)
																	@if($option->product_id==$device->product_id)
																		<option value="{{$option->id}}" @if (substr($device->status,4,strlen($device->status)-4))==$option->id) {{ "selected" }} @endif>Retired # - {{$option->id}} - {{$option->name}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
													<div class="col-xs-12 col-sm-6 row-padding"; style="font-weight: bold; color: #bce8f1">
														<input type="checkbox"  name="Device:{{$device->recnum}}[comm]" value="1">
															Check to Commission
													</div>
												</div>
											@endif
										</div>
										@if ($Status==1 and $ProdOKFlag==1)
											<div class="col-xs-12">
												<div class="col-xs-12 col-sm-6 row-padding">Name
													<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[name]" type="text" value="{{$device->name}}">
												</div>
												<div class="col-xs-12 col-sm-6 row-padding">Physical Location
													<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[physical_location]" type="text" value="{{$device->physical_location}}">
												</div>
												<div class="col-xs-12 col-sm-6 row-padding">Zone
													{{Form::select("Device:".$device->recnum."[zone]",$zone_labels,$device->zone,["class" => "form-control", "style" => "color:black"])}}
												</div>
												<div class="col-xs-12 col-sm-6 row-padding">Functional Description
														<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[functional_description]" type="text" value="{{$device->functional_description}}" {{$disable_user}}>
												</div>
											</div>
										@endif
									</div>

									<div class="form-group">
										@if ($Status==1)
											<div class="col-xs-6 row-padding">Status
												<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]" {{$disable_user}}>
													@if($SV==0)
														<option value="0" @if ($SV == 0) {{ "selected" }} @endif>Uncommissioned</option>
													@endif
													<option value="4" @if ($SV == 4) {{ "selected" }} @endif>
															Active
													</option>
													<option value="6" @if ($SV == 6 or $SV == 2) {{ "selected" }} @endif>
															Inhibited
													</option>
													<option value="7" @if ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1) {{ "selected" }} @endif>
															Retired
													</option>
												</select>
											</div>
											<div class="col-xs-6 row-padding">Report Time(mins)
												<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[reporttime]" type="text" value="{{$PRTime}}" {{$disable_user}}>
											</div>
											<div class="col-xs-12 row-padding">Comments/Notes
												<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[comments]" type="text" value="{{$device->comments}}">
											</div>
											<div class="col-xs-12 border_blue_white" style="text-align:center; padding-bottom:10px;">
												<span style="font-size:18pt;"> Calibration Factors</span>
												<div>
												<?php
													$dev_types=explode(",",$product_commands[$PN]);
													$num_commands=count($dev_types);
													if($num_commands == 1){ 		$xs_col_width="col-xs-12"; $lg_col_width="col-lg-12";
													}else if($num_commands == 2){ 	$xs_col_width="col-xs-6"; $lg_col_width="col-lg-3";
													}else if($num_commands == 3){ 	$xs_col_width="col-xs-4"; $lg_col_width="col-lg-4";
													}else{ 							$xs_col_width="col-xs-6"; $lg_col_width="col-lg-3";
													}
													foreach ($dev_types as $command) {
														if($device_type_names[$command] != 'Temperature'){
															$display_units = $device_type_units[$command];
														}else{
															$display_units = '&deg;'.$thisSystem->temperature_format;
														}
													?>
													<div class="{{$xs_col_width}} {{$lg_col_width}} row-padding" title="May not exceed three decimal places">
														{{$device_type_names[$command]}} ( {{$display_units}} )
														@if (isset($devicesoffset[$device->id][$command]))
														<?php
															$offset_value = $devicesoffset[$device->id][$command];

															/*We store the offset in the system's respective format (i.e. Fahrenheit offsets are stored as such, and the same with Celcius offsets). The Offset will be sent to the individual system in the stored format, at which point any necessary conversion will be taken into account (in accordance with the system's temperature format). This is dues to lack of ability to convert use 'converted offsets' from the db to offset incoming values.
															Store the "original_offset" for later comparison with the submitted offset. We compare in the updatesystem() function.
															*/
														?>
														<input type="hidden" name="Device:{{$device->recnum}}[original_offset-{{$device->id}}-{{$command}}]" value="{{round($offset_value,3,PHP_ROUND_HALF_DOWN)}}">
														<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[offset-{{$device->id}}-{{$command}}]" type="text" value="{{round($offset_value,3,PHP_ROUND_HALF_DOWN)}}" {{$disable_user}}>
														@else
														<input type="hidden" name="Device:{{$device->recnum}}[original_offset-{{$device->id}}-{{$command}}]" value="0">
														<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[offset-{{$device->id}}-{{$command}}]" type="text" value="0" {{$disable_user}}>
														@endif
													</div>
													<?php
													}
												?>
												</div>
											</div>
										@endif
									</div>
								</div>
								<br>
							@endif
						@endforeach<!--wirelessdevices as device-->
						@if ($deviceCount==0)
							<div class="col-xs-12 seamless_block_emc device-title">
								<div class="col-xs-9 row-padding">No Devices Detected - Activate Devices to Begin the Commissioning Process</div>
							</div>
						@endif
						<?php } }  ?>
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">Wireless Sensors</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
							</div>
						</div>
					@else<!--if the system does not have wireless sensors-->
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">No WireLess Sensors</div>
						</div>
					@endif
					{{ Form::close() }}
				</div>   
				<!-- emd WIRELESS SENSOR INPUTS -->
				<!-- BACNET MSTP -->
				<div id="system-devices-bacnetmstp" class="col-xs-12 container-fluid collapse device-section system-devices">
					{{ Form::open(array("role" => "form")) }}
					<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
					@if ($BACNETFlag)<!--if bacnet devices detected-->
						<div class="col-xs-12 seamless_block_emc device-title">
				  			<div class="col-xs-9 row-padding">BACNet MSTP Devices</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
							</div>
						</div>
						<?php for($i=0;$i<2;$i++)  {
							if ($i==0) {
								$Status=0;
								// look for orphaned devices if none skip to recognized only
								$Orphanflag=0;
								foreach ($bacnetdevices as $device) {
									if ($device->id ==0 ) {
										$Orphanflag=1;
									}
								}
								if ($Orphanflag==0){
									$i=1;
									$Status=1;
								}
							} else {
								$Status=1;
							}
							{    //loop bracket
							// loops twice non commissioned first, then commissioned
						?>
						@if ($Status==0)
							<div class="col-xs-12 seamless_block_emc" style="background-color: #56567F">
								<div class="col-xs-9 row-padding" style="font-weight: bold; color: #FF5544">Orphaned Devices</div>
								<div class="col-xs-3 row-padding" style="text-align: center"></div>
							</div>
						@else
							<div class="col-xs-12 seamless_block_emc">
								<div class="col-xs-9 row-padding" style="font-weight: bold; color: #55FF44">Recognized Devices</div>
								<div class="col-xs-3 row-padding" style="text-align: center"></div>
							</div>
						@endif
						<br>
						<?php $deviceCount=0; ?>
						@foreach ($bacnetdevices as $device)
							<?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired) ?>
							<!-- Loop non commissioned devices first status=0 then status=1-->
							<?php   $deviceCount++; ?>
							@if ($device->device_mode=="bacnetmstp" and $device->status == $Status)
								<?php
									if ($Status==0) {
										$dev_bg = ' device_orphan_bg';
									} else if ($device->retired==1){
										$dev_bg = ' device_retired_bg';
									} else if ($device->inhibited==1){
										$dev_bg = ' device_inhibited_bg';
									} else {
										$dev_bg = '';
									}
									$ProdOKFlag=0;
									$PN="";
									$PF="";
									$PM="";
									foreach ($products as $product) {
										if ($product->product_id==$device->product_id) {
											$PN=$product->name;
											$PF=$product->function;
											$PM=$product->mode;
											// define default paramaters or for defined devices get from device table
											if ($Status==0 and $device->reporttime=="") {
												// use default unless update has already been defined in device table
												$PRTime=$product->reporttime;
											} else {
												$PRTime=$device->reporttime;
											}
											$ProdOKFlag=1;
										}
									}
									if($PM=="") {
										$PM="Undefined";
									}
								?>
								<div class="col-xs-12 seamless_block_emc device-block{{$dev_bg}}">
									<div class="form-group">
										<div class="col-xs-12 row-padding  device-subtitle"><small>Device: </small>#{{$device->id}}<BR>
											{{"<small>Mac Address: </small>".$device->mac_address}}<BR>
											{{"<small>Mode: </small>".$PM}}
											@if ($Status==0 and $ProdOKFlag==1)
												<div style="font-weight: bold; color: #bce8f1">Check to Commission
													<input type="checkbox"  name="Device:{{$device->recnum}}[comm]" value="1">
												</div>
											@endif
											<input type="hidden" name="Device:{{$device->recnum}}[device_type]" value="sensor">
											<input type="hidden" name="Device:{{$device->recnum}}[location]" value="{{$device->location}}">
											<input type="hidden" name="Device:{{$device->recnum}}[device_mode]" value="bacnetmstp">
											<input type="hidden" name="Device:{{$device->recnum}}[mac_address]" value="{{$device->mac_address}}">
											<input type="hidden" name="Device:{{$device->recnum}}[short_address]" value="{{$device->short_address}}">
											<input type="hidden" name="Device:{{$device->recnum}}[product_id]" value="{{$device->product_id}}">
											<input type="hidden" name="Device:{{$device->recnum}}[device_function]" value="{{$device->device_function}}">
											<!-- <input type="hidden" name="Device:{{$device->recnum}}[reporttime]" value="NA"> -->
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Product Type<BR>
											{{$PN}}
											@if ($ProdOKFlag==0) 
												{{"<font color='#FF5544'><b><br> Product Type not defined
												<BR>Update table before proceeding</b></font>"}}
											@endif
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Device Function<BR>
											{{$PF}}
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Name
											<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[name]" type="text" value="{{$device->name}}">
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Physical Location
											<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[physical_location]" type="text" value="{{$device->physical_location}}">
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Zone
											{{Form::select("Device:".$device->recnum."[zone]",$zone_labels,$device->zone,["class" => "form-control", "style" => "color:black"])}}
										</div>
										<div class="col-xs-12 col-sm-6 row-padding">Functional Description
											<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[functional_description]" type="text" value="{{$device->functional_description}}" {{$disable_user}}>
										</div>
									</div>
									<div class="form-group">
										@if ($Status==1)
											<div class="col-xs-6 row-padding">Status
												<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]" {{$disable_user}}>
													@if($SV==0)
														<option value="0" @if ($SV == 0) {{ "selected" }} @endif>Uncommissioned</option>
													@endif
													<option value="4" @if ($SV == 4) {{ "selected" }} @endif>
															Active
													</option>
													<option value="6" @if ($SV == 6 or $SV == 2) {{ "selected" }} @endif>
															Inhibited
													</option>
													<option value="7" @if ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1) {{ "selected" }} @endif>
															Retired
													</option>
												</select>
											</div>
										@else
											<div class="col-xs-6 row-padding">New or Replacement for Device #
												<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]">
													<option value="New" <? if ($SV == "ND") { echo "selected"; }?> >New</option>
													@foreach ($bacnetdevices as $option)
														@if ($option->retired==1)
															<option value="{{'Rpl-'.$device->id}}" @if (substr($device->status,4,strlen($device->status)-4))==$option->id) {{ "selected" }} @endif>Retired Device # - {{$option->id}}</option>
														@endif
													@endforeach
							 					</select>
											</div>
										@endif
										<div class="col-xs-6 row-padding">Report Time(mins)
											<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[reporttime]" type="text" value="{{$PRTime}}" {{$disable_user}}>
										</div>
										<div class="col-xs-12 row-padding">Comments/Notes
											<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[comments]" type="text" value="{{$device->comments}}">
										</div>
									</div>
								</div>
								<br>
							@endif<!--END - - $device->device_mode=="bacnetmstp" and $device->status == $Status-->
						@endforeach
						@if ($deviceCount==0)
							<div class="col-xs-12 seamless_block_emc device-title">
								<div class="col-xs-9 row-padding">No Devices Detected - Activate Devices to Begin the Commissioning Process</div>
							</div>
						@endif
						<?php } }  ?>
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">BACNet MSTP Devices</div>
							<div class="col-xs-3 row-padding" style="text-align: center">
								<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
							</div>
						</div>
					@else<!--if bacnet no devices detected-->
						<div class="col-xs-12 seamless_block_emc device-title">
							<div class="col-xs-9 row-padding">No BACNET Devices</div>
						</div>
					@endif
					{{ Form::close() }}
				</div>  
				<!-- end BACNET MSTP -->
				<!-- UTILITY DEVICE -->
				@if(strcasecmp($thisBldg->state, 'NY')==0)
					@foreach($boroughs as $borough)
						@if(strcasecmp($thisBldg->city, $borough) == 0)
							<div id="system-devices-utility" class="col-xs-12 container-fluid collapse device-section system-devices">
								{{ Form::open(array("role" => "form")) }}
									<input class="hidden" type="hidden" name="current_user" value="{{Auth::user()->email}}">
									<div class="col-xs-12 seamless_block_emc device-title">
										<div class="col-xs-9 row-padding">Utility Devices</div>
										<div class="col-xs-3 row-padding" style="text-align: center">
											<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
										</div>
									</div>
									<?php 
									for($i=0;$i<2;$i++)  {
										if ($i==0) {
											$Status=0;
											// look for orphaned devices if none skip to recognized only
											$Orphanflag=0;
											foreach ($utilitydevices as $device) {
												if ($device->id ==0 ) {
													$Orphanflag=1;
												}
											}
											if ($Orphanflag==0){
												$i=1;
												$Status=1;
											}
										} else {
											$Status=1;
										}
									}
									// 	{    //loop bracket
										// loops twice non commissioned first, then commissioned
									?>
									<!-- Device list is not empty -->
									@if (count($utilitydevices) != 0)
										@if ($Status==0) 
											<div class="col-xs-12 seamless_block_emc" style="background-color: #56567F">
												<div class="col-xs-9 row-padding" style="font-weight: bold; color: #FF5544">Orphaned Devices</div>
												<div class="col-xs-3 row-padding" style="text-align: center"></div>
											</div>
										@else
											<div class="col-xs-12 seamless_block_emc">
												<div class="col-xs-9 row-padding" style="font-weight: bold; color: #55FF44">Recognized Devices</div>
												<div class="col-xs-3 row-padding" style="text-align: center"></div>
											</div>
										@endif
									@endif
									<br> 
									<!-- Device list is not empty -->
									@if (count($utilitydevices) != 0)
										@foreach ($utilitydevices as $device)
										<?php $SV=StatusLogicEncode($device->status,$device->inhibited,$device->retired); ?>
											<!-- Loop non commissioned devices first status=0 then status=1-->
											@if ($device->device_mode=="api" and $device->status == $Status)
												<?php  
													if ($Status==0) {
														$dev_bg = ' device_orphan_bg';
													} else if ($device->retired==1){
														$dev_bg = ' device_retired_bg';
													} else if ($device->inhibited==1){
														$dev_bg = ' device_inhibited_bg';
													} else {
														$dev_bg = '';
													}
													$ProdOKFlag=0;
													$PN="";
													$PF="";
													$PM="";
													foreach ($products as $product) {
														if ($product->product_id==$device->product_id) {
															$PN=$product->name;
															$PF=$product->function;
															$PM=$product->mode;
															// define default paramaters or for defined devices get from device table
															if ($Status==0 and $device->reporttime=="") {
																// use default unless update has already been defined in device table
																$PRTime=$product->reporttime;
															} else {
																$PRTime=$device->reporttime;
															}
															$ProdOKFlag=1;
														}
													}
													if($PM=="") {
														$PM="Undefined";
													}
												?>
												<div class="col-xs-12 seamless_block_emc device-block{{$dev_bg}}">
													<div class="form-group">
														<div class="col-xs-12 row-padding  device-subtitle"><small>Device: </small>#{{$device->id}}<BR>
															{{"<small>Mac Address: </small>".$device->mac_address}}<BR>
															{{"<small>Mode: </small>".$PM}}
															@if ($Status==0 and $ProdOKFlag==1)
																<div style="font-weight: bold; color: #bce8f1">Check to Commission
																	<input type="checkbox"  name="Device:{{$device->recnum}}[comm]" value="1">
																</div>
															@endif
															<input type="hidden" name="Device:{{$device->recnum}}[device_type]" value="sensor">
															<input type="hidden" name="Device:{{$device->recnum}}[location]" value="{{$device->location}}">
															<input type="hidden" name="Device:{{$device->recnum}}[device_mode]" value="api">
															<input type="hidden" name="Device:{{$device->recnum}}[mac_address]" value="{{$device->mac_address}}">
															<input type="hidden" name="Device:{{$device->recnum}}[short_address]" value="{{$device->short_address}}">
															<input type="hidden" name="Device:{{$device->recnum}}[product_id]" value="{{$device->product_id}}">
															<input type="hidden" name="Device:{{$device->recnum}}[device_function]" value="{{$device->device_function}}">
															<!-- <input type="hidden" name="Device:{{$device->recnum}}[reporttime]" value="NA"> -->
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Product Type<BR>
															{{$PN}}
															@if ($ProdOKFlag==0) 
																{{"<font color='#FF5544'><b><br> Product Type not defined.
																<BR>Update table before proceeding</b></font>"}}
															@endif
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Device Function<BR>
															{{$PF}}
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Name
															<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[name]" type="text" value="{{$device->name}}">
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Physical Location
															<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[physical_location]" type="text" value="{{$device->physical_location}}">
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Zone
															{{Form::select("Device:".$device->recnum."[zone]",$zone_labels,$device->zone,["class" => "form-control", "style" => "color:black"])}}
														</div>
														<div class="col-xs-12 col-sm-6 row-padding">Functional Description
															<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[functional_description]" type="text" value="{{$device->functional_description}}" {{$disable_user}}>
														</div>
													</div>
													<div class="form-group">
														@if ($Status==1)
															<div class="col-xs-6 row-padding">Status
																<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]" {{$disable_user}}>
																	@if($SV==0)
																		<option value="0" @if ($SV == 0) {{ "selected" }} @endif>Uncommissioned</option>
																	@endif
																	<option value="4" @if ($SV == 4) {{ "selected" }} @endif>
																			Active
																	</option>
																	<option value="6" @if ($SV == 6 or $SV == 2) {{ "selected" }} @endif>
																			Inhibited
																	</option>
																	<option value="7" @if ($SV == 7 or $SV == 5 or $SV == 3 or $SV == 1) {{ "selected" }} @endif>
																			Retired
																	</option>
																</select>
															</div>
														@else
															<div class="col-xs-6 row-padding">New or Replacement for Device #
																<select class="form-control" style="color: black" name="Device:{{$device->recnum}}[status]">
																	<option value="New" <? if ($SV == "ND") { echo "selected"; }?> >New</option>
																	@foreach ($utilitydevices as $option)
																		@if ($option->retired==1)
																			<option value="{{'Rpl-'.$device->id}}" @if (substr($device->status,4,strlen($device->status)-4))==$option->id) {{ "selected" }} @endif>Retired Device # - {{$option->id}}</option>
																		@endif
																	@endforeach
																</select>
															</div>
														@endif
														<div class="col-xs-6 row-padding">Report Time(mins)
															<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[reporttime]" type="text" value="{{$PRTime}}" {{$disable_user}}>
														</div>
														<div class="col-xs-12 row-padding">Comments/Notes
															<input class="form-control" style="color: black" name="Device:{{$device->recnum}}[comments]" type="text" value="{{$device->comments}}">
														</div>
													</div>
												</div>
												<br>
											@endif<!--END - - $device->device_mode=="bacnetmstp" and $device->status == $Status-->
										@endforeach
									<!-- No device found -->
									@else
										<div class="col-xs-12 alert alert-danger">
											<div class="col-xs-9 row-padding">No Devices Detected - Activate Devices to Begin the Commissioning Process</div>
										</div>	
										<div class="col-xs-12 well">
											<h2>Login with your DEP username and token to access the API</h2>		
											<div class="col-xs-12 form-group">
												<label for="utility_username" class="col-sm-2 control-label">Username</label>
												<div class="col-sm-10">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
														<input type="text" class="form-control" name="utility_username" id="utility-username"  placeholder="Enter your Username"/>
													</div>
												</div>
											</div>
					
											<div class="col-xs-12 form-group">
												<label for="utility_token" class="col-sm-2 control-label">Token</label>
												<div class="col-sm-10">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
														<input type="text" class="form-control" name="utility_token" id="utility-token"  placeholder="Enter your token"/>
													</div>
												</div>
											</div>
											<div class="col-sm-3 col-sm-offset-5 col-xs-6 col-xs-offset-6 form-group">
												<button type="submit" class="btn btn-primary btn-sm btn-block login-button" name="utilityDevice_login">Login</button>
											</div>
										</div>					
									@endif
									<div class="col-xs-12 seamless_block_emc device-title">
										<div class="col-xs-9 row-padding">Utility Devices</div>
										<div class="col-xs-3 row-padding" style="text-align: center">
											<button class="btn btn-lg btn-primary" type="submit" name="Devices">Save</button>
										</div>
									</div>
								{{ Form::close() }}
							</div>  
						@endif
					@endforeach
				@endif
				<!-- END UTILITY DEVICE -->
			</div>
		</div>
		<!-- ALGORITHM MAPPING LIST -->
		<div id="device-mapping" class="container-fluid collapse col-xs-12">
			{{ AlgorithmController::index($thisBldg->id, $thisSystem->id) }}
		</div>
		<!-- end ALGORITHM MAPPING LIST -->
		<!-- ZONE LABELS -->
		<div id="zone-labels" class="container-fluid collapse col-xs-12 ">
			{{ ZoneController::index($thisBldg->id, $thisSystem->id) }}
		</div>
		<!-- end ZONE LABELS -->
		<!-- SYSTEM COMMANDS -->
		<div id="system-commands" class="container-fluid collapse col-xs-12 ">
			<div class="col-xs-12">
				<div class="col-xs-12 seamless_block_emc device-block row-padding" style="color:white;  width: 100%; margin-bottom: 20px;">
					<div class="device-title" style="text-align: center; margin-bottom: 15pt;">
						COMMANDS
					</div>
					<!-- SYSTEM RESET -->
					<div class="col-xs-12 device-block" style="padding: 15pt;">
						{{ Form::open(array("role" => "form")) }}
							<button class="micro-row-detail device-block seamless_block_emc col-xs-12 col-sm-2 js-confirm" type="submit" name="ResetSystem" data-confirm='Request Local System Reset?' style="margin-top: 40pt;">
								RESET SYSTEM
							</button>
						{{ Form::close() }}
						<div class="col-xs-12 col-sm-6">
							<p>
								This command will generate a file to be transmitted to the local system. The file will be interpreted on the local system and should result in the local system watchdog being triggered, thus triggering a reset of the local system.
							</p>
							<p>
								The local system must be online for this change to take effect.
							</p>
							<p>
								If the system is offline, the command will continue to be pending on the web server. If the reset command is still pending, you may cancel it by selecting the "CANCEL PENDING RESET" command, where available.
							</p>
						</div>
						<div class="col-xs-12 col-sm-2" style="margin-top: 20pt;" >
							<p class="device-block" style="text-align: center;">
								Last System Reset:
								<br>
								@if(isset($reset_log))
									{{$reset_log->datetime}}
								@else
									No Resets Recorded in Last Three Weeks
								@endif
							</p>
						</div>
						<?php $command_sent = RemoteTask::ConfirmSent($thisSystem->id,'var.2020_command.xx_forcewatchdog.emc') ?>
						@if(!$command_sent)
							{{ Form::open(array("role" => "form")) }}
								<button class="micro-row-detail device-block seamless_block_emc col-xs-12 col-sm-2 js-confirm" type="submit" name="UndoResetSystem" data-confirm='Remove Pending Local System Reset Request?' style="margin-top: 20pt;" title="The reset request is still waiting to be transmitted to the local system.">
									CANCEL PENDING RESET
								</button>
							{{ Form::close() }}
						@endif
					</div>
					<!-- end SYSTEM RESET -->
					<!-- SOFTWARE UPDATE -->
					<div class="col-xs-12 device-block" style="padding: 15pt;">
						{{ Form::open(array("role" => "form")) }}
							<button class="micro-row-detail device-block seamless_block_emc col-xs-12 col-sm-2 js-confirm" type="submit" name="SoftwareUpdate" style="margin-top: 40pt;" data-confirm='Request Local System Software Update?'>
								UPDATE SOFTWARE
							</button>
						{{ Form::close() }}
						<div class="col-xs-12 col-sm-6">
							<p>
								This command will attempt to bring the system up-to-date with the latest software changes for this EMC<sup>20/20</sup> system.
							</p>
							<p>
								A command will be generated and staged to be pulled down by the local system. The local system must be online for this change to take effect.
							</p>
							<p>
								If the system is offline, the command will continue to be pending on the web server. If the update command is still pending, you may cancel it by selecting the "CANCEL PENDING UPDATE" command, where available.
							</p>
						</div>
						<div class="col-xs-12 col-sm-2">
							<p class="device-block" style="text-align: center;">
								Current Software Version:
								<br>
								<big>
									{{$thisSystem->software_version}}
								</big>
							</p>
							<p class="device-block" style="text-align: center;">
								Latest Available Software Version:
								<br>
								<big>
									{{$latest_sw_version}}
								</big>
							</p>
						</div>
						<?php $command_sent = RemoteTask::ConfirmSent($thisSystem->id,'var.2020_command.xx_gitupdate.emc') ?>
						@if(!$command_sent)
							{{ Form::open(array("role" => "form")) }}
								<button class="micro-row-detail device-block seamless_block_emc col-xs-12 col-sm-2 js-confirm" type="submit" name="UndoSoftwareUpdate" data-confirm='Remove Pending Local System Software Update Request?' style="margin-top: 20pt;" title="The reset request is still waiting to be transmitted to the local system.">
									CANCEL PENDING UPDATE
								</button>
							{{ Form::close() }}
						@endif
					</div>
					<!-- end SOFTWARE UPDATE -->
				</div>
			</div>
		</div>
		<!-- end SYSTEM COMMANDS -->
	</div>
</div>
<script type"text/javascript">
	$(window).ready(function(){
		console.log("window ready");
		$(window).keydown(function(event){
		    if(event.keyCode == 13) {
		      event.preventDefault();
		      return false;
		    }
		});
		var allDevTabs,i,maxheight;
		maxheight = 0;
		allDevTabs = document.getElementById("firstTabs").children;
		for(i=0; i < allDevTabs.length; i++){
			console.log(allDevTabs[i].clientHeight);
			if(allDevTabs[i].clientHeight > maxheight) {
				maxheight = allDevTabs[i].clientHeight;
			}
		}
		console.log(maxheight);
		for(i=0; i < allDevTabs.length; i++){
			allDevTabs[i].style.height = maxheight + 'px';
		}
		for(i=0; i < allDevTabs.length; i++){
			console.log(allDevTabs[i].clientHeight);
			if(allDevTabs[i].clientHeight > maxheight) {
				maxheight = allDevTabs[i].clientHeight;
			}
		}

		console.log("Resize buttons");
		resizeToggleButtons();
		console.log("Startup updateExpansionSelectOptions:")
		updateExpansionSelectOptions($(document.getElementById("hardware_version_form")).find(":selected").val());
		console.log("Startup expBoardCountUpdate:");
		expBoardCountUpdate("first");
		console.log("End startup scripts");
		console.log("Check Downlink Status");
		ipConfigUpdate();
	});

	function utilitylogin(systemID){
		var username = document.getElementById('utility-username').value;
		var token = document.getElementById('utility-token').value;
		var request = $.ajax({
			url: systemID + '/ajax',
			data: {
				data_username : username,
				data_token    : token
			},
			method: "POST",
			complete: function (data, textStatus, jqXHR) {
				if (data.statusText == "timeout" || typeof data.responseJSON.bars == 'undefined') {
					$('.nodataRelayBar').show();
				}
				$('.utilityloading').hide();
			},
			success: function (data, textStatus, jqXHR) {
				console.log('------------------------------------');
				console.log(data);
				console.log('------------------------------------');
				if (typeof data.bars != 'undefined') {
					runtimebar.createLegends(data['device_names']);
					runtimebar.updateAXIS(data);
					runtimebar.drawSHAPES(data, runtimebar);
					BarchartActions(runtimebar);
				}
			}
		});
	}

	// **********************************************************************************************************************
	function ipConfigUpdate(){
		var i;
		var staticDivs = document.getElementsByClassName("static-network-settings");
		var downlinkElmnt = document.getElementById("downlink");
		var downlinkSelection = downlinkElmnt.value;

		if(document.getElementById("downlink").checked){
			//hide static options
			for(i=0; i < staticDivs.length; i++){
				staticDivs[i].className += " hidden";
			}
			console.log("hide static settings");
		}else{
			//show static options
			for(i=0; i < staticDivs.length; i++){
				staticDivs[i].className = staticDivs[i].className.replace(/ hidden/g,"");
			}
			console.log("show static settings");
		}
	}
	function confirmNetworkChange(element) {
		var oldValue = element.defaultValue;
		var newValue = element.value;
		if (window.confirm('{{$netWarning}}')) {
			element.defaultValue = newValue;
		} else {
			element.value = element.defaultValue;
		}
	}
	// **********************************************************************************************************************
	function resizeToggleButtons(){
		var i;
		var toggleBtns = document.getElementsByClassName("tgl-btn");
		var toggleDivWidth = (toggleBtns[0].parentNode.clientWidth) - 60;
		console.log("divwidth:"+ toggleDivWidth);
		for(i=0; i < toggleBtns.length; i++){
			toggleBtns[i].style.width = toggleDivWidth + 'px';
		}
	}

	// **********************************************************************************************************************
	// Update select options, based on hw version
	function updateExpansionSelectOptions(mainboard){
		console.log("updateExpansionSelectOptions("+mainboard+")")
		var mb_none_option = $('<option></option>').attr("value","00").text("None");
		var mb_11_option_13 = $('<option></option>').attr("value","13").text("M4613-013");
		var mb_11_option_14 = $('<option></option>').attr("value","14").text("M4613-014");
		var mb_01_option = $('<option></option>').attr("value","03").text("M4613-003");
		$('#exp_board_1_version_form').empty();
		$('#exp_board_2_version_form').empty();
		$('#exp_board_3_version_form').empty();
		console.log("mainboard:");
		if(mainboard == "01"){
			console.log("\t"+mainboard);
			$("#exp_board_1_version_form").append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_1_version_form').append($('<option></option>').attr("value","03").text("M4613-003 - Expansion Board"));
			$('#exp_board_2_version_form').append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_2_version_form').append($('<option></option>').attr("value","03").text("M4613-003 - Expansion Board"));
			$('#exp_board_3_version_form').append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_3_version_form').append($('<option></option>').attr("value","03").text("M4613-003 - Expansion Board"));
		}else if(mainboard == "11"){
			console.log("\t"+mainboard);
			$('#exp_board_1_version_form').append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_1_version_form').append($('<option></option>').attr("value","13").text("M4613-013 - Staging Board"));
			$('#exp_board_1_version_form').append($('<option></option>').attr("value","14").text("M4613-014 - Wired Sensors Board"));
			$('#exp_board_2_version_form').append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_2_version_form').append($('<option></option>').attr("value","13").text("M4613-013 - Staging Board"));
			$('#exp_board_2_version_form').append($('<option></option>').attr("value","14").text("M4613-014 - Wired Sensors Board"));
			$('#exp_board_3_version_form').append($('<option></option>').attr("value","00").text("None"));
			$('#exp_board_3_version_form').append($('<option></option>').attr("value","13").text("M4613-013 - Staging Board"));
			$('#exp_board_3_version_form').append($('<option></option>').attr("value","14").text("M4613-014 - Wired Sensors Board"));

		}else{
			console.log("\ter:"+mainboard);
		}
		$('#exp_board_1_version_form').value = "00";
		$('#exp_board_2_version_form').value = "00";
		$('#exp_board_3_version_form').value = "00";

	}

	// **********************************************************************************************************************
	// Update the expansion board fields based on the number of expansion boards
	function expBoardCountUpdate(run){
		console.log("expBoardCountUpdate("+run+")");
		<?php
			$boom = explode(".", $thisSystem->hardware_version);
		?>
		var exp_board_number,exp_board_section;
		exp_board_number = $(document.getElementById("expansion_board_count")).val();
		hw_section_upper_div = document.getElementById("expansion_board_count").parentNode.parentNode.parentNode;
		exp_board_section = document.getElementById("exp_board_div");
		exp_board_1_version_section = document.getElementById("exp_board_1_version_form");
		exp_board_2_version_section = document.getElementById("exp_board_2_version_form");
		exp_board_3_version_section = document.getElementById("exp_board_3_version_form");

		exp_board_1_version_section.parentNode.className = exp_board_1_version_section.parentNode.className.replace(/ hidden/g,"");
		exp_board_2_version_section.parentNode.className = exp_board_2_version_section.parentNode.className.replace(/ hidden/g,"");
		exp_board_3_version_section.parentNode.className = exp_board_3_version_section.parentNode.className.replace(/ hidden/g,"");

		exp_board_1_version_section.value = (run == "first")?"{{isset($boom[1])?$boom[1]:"00"}}":"00";
		exp_board_2_version_section.value = (run == "first")?"{{isset($boom[2])?$boom[2]:"00"}}":"00";
		exp_board_3_version_section.value = (run == "first")?"{{isset($boom[3])?$boom[3]:"00"}}":"00";
		console.log("--Print Values: #("+exp_board_number+") ..:"+exp_board_1_version_section.value+":"+exp_board_2_version_section.value+":"+exp_board_3_version_section.value);
		switch(exp_board_number){
			case "0":
				exp_board_section.className += " hidden";
				//hw_section_upper_div.style.border = "none";
				exp_board_1_version_section.value = "00";
				exp_board_2_version_section.value = "00";
				exp_board_3_version_section.value = "00";
				break;
			case "1":
				exp_board_2_version_section.parentNode.className += " hidden";
				exp_board_2_version_section.value = "00";
			case "2":
				exp_board_3_version_section.parentNode.className += " hidden";
				exp_board_3_version_section.value = "00";
			case "3":
			default:
				exp_board_section.className = exp_board_section.className.replace(/ hidden/g,"");
				//hw_section_upper_div.style.borderTop = "solid 2px white";
				break;
		}

		hardwareVersionUpdate();

	}

	// **********************************************************************************************************************
	// Update the available expansion I/O's based on the selected hardware versions
	function hardwareVersionUpdate(change){

		var redAlert = "#ffdddd";
		var main_board_version_val,exp_board_1_version_val,exp_board_2_version_val,exp_board_3_version_val,hardware_version_str;
		var hv01030000,hv01030300,hv01030303,hv11000000,hv11130000,hv11131400,hv11140000,hv11141300,hv11141400,hv11141414;
		var invalidConfig = document.getElementById("adjustment-warning");
		console.log("Invalid Config Div: "+invalidConfig.className);
		main_board_version_val = $(document.getElementById("hardware_version_form")).find(":selected").val();
		(change=="mainboard")?updateExpansionSelectOptions(main_board_version_val):console.log("Expnsion Board Version Update");

		exp_board_1_version_val = $(document.getElementById("exp_board_1_version_form")).find(":selected").val();
		exp_board_2_version_val = $(document.getElementById("exp_board_2_version_form")).find(":selected").val();
		exp_board_3_version_val = $(document.getElementById("exp_board_3_version_form")).find(":selected").val();

		exp_board_1_version_section = document.getElementById("exp_board_1_version_form");
		exp_board_2_version_section = document.getElementById("exp_board_2_version_form");
		exp_board_3_version_section = document.getElementById("exp_board_3_version_form");

		if(exp_board_1_version_val==undefined){
			exp_board_1_version_val = "00";
		}
		if(exp_board_2_version_val==undefined){
			exp_board_2_version_val = "00";
		}
		if(exp_board_3_version_val==undefined){
			exp_board_3_version_val = "00";
		}
		hardware_version_str = main_board_version_val+"."+exp_board_1_version_val+"."+exp_board_2_version_val+"."+exp_board_3_version_val;
		console.log(hardware_version_str);

		hv01030000 = document.getElementById("hw-select-section-01.03.00.00");
		hv01030300 = document.getElementById("hw-select-section-01.03.03.00");
		hv01030303 = document.getElementById("hw-select-section-01.03.03.03");
		hv11000000 = document.getElementById("hw-select-section-11.00.00.00");
		hv11130000 = document.getElementById("hw-select-section-11.13.00.00");
		hv11131400 = document.getElementById("hw-select-section-11.13.14.00");
		hv11140000 = document.getElementById("hw-select-section-11.14.00.00");
		hv11141400 = document.getElementById("hw-select-section-11.14.14.00");
		hv11141414 = document.getElementById("hw-select-section-11.14.14.14");

		hv01030000.className = hv01030000.className.replace(/ hidden/g,"");
		hv01030300.className = hv01030300.className.replace(/ hidden/g,"");
		hv01030303.className = hv01030303.className.replace(/ hidden/g,"");
		hv11000000.className = hv11000000.className.replace(/ hidden/g,"");
		hv11130000.className = hv11130000.className.replace(/ hidden/g,"");
		hv11131400.className = hv11131400.className.replace(/ hidden/g,"");
		hv11140000.className = hv11140000.className.replace(/ hidden/g,"");
		hv11141400.className = hv11141400.className.replace(/ hidden/g,"");
		hv11141414.className = hv11141414.className.replace(/ hidden/g,"");

		exp_board_1_version_section.style.backgroundColor = "white";
		exp_board_2_version_section.style.backgroundColor = "white";
		exp_board_3_version_section.style.backgroundColor = "white";
		// Change the available I/O configurations based on selected HW version
		switch(hardware_version_str){
			case "01.00.00.00":
				hv01030000.className += " hidden";
				exp_board_1_version_section.style.backgroundColor = redAlert;
			case "01.03.00.00":
				hv01030300.className += " hidden";
				exp_board_2_version_section.style.backgroundColor = redAlert;
			case "01.03.03.00":
				hv01030303.className += " hidden";
				hv11000000.className += " hidden";
				hv11130000.className += " hidden";
				hv11131400.className += " hidden";
				hv11140000.className += " hidden";
				hv11141400.className += " hidden";
				hv11141414.className += " hidden";
				exp_board_3_version_section.style.backgroundColor = redAlert;
				invalidConfig.className += " hidden";
				break;
			case "11.00.00.00":
				hv11130000.className += " hidden";
				exp_board_1_version_section.style.backgroundColor = redAlert;
			case "11.13.00.00":
				hv11140000.className += " hidden";
				hv11131400.className += " hidden";
				exp_board_2_version_section.style.backgroundColor = redAlert;
			case "11.13.14.00":
				hv11140000.className += " hidden";
				hv11141414.className += " hidden";
				hv01030000.className += " hidden";
				hv01030300.className += " hidden";
				hv01030303.className += " hidden";
				hv11141400.className += " hidden";
				exp_board_3_version_section.style.backgroundColor = redAlert;
				invalidConfig.className += " hidden";
				break;

			case "11.14.00.00":
				if(hardware_version_str == "11.00.00.00"){
					hv11140000.className += " hidden";
				}
				hv11130000.className += " hidden";
				hv11141400.className += " hidden";
				exp_board_2_version_section.style.backgroundColor = redAlert;
			case "11.14.14.00":
				hv11141414.className += " hidden";
				exp_board_3_version_section.style.backgroundColor = redAlert;
			case "11.14.14.14":
				hv11131400.className += " hidden";
				hv11130000.className += " hidden";
				hv01030000.className += " hidden";
				hv01030300.className += " hidden";
				hv01030303.className += " hidden";
				invalidConfig.className += " hidden";
				break;

			default:
				invalidConfig.className = invalidConfig.className.replace(/ hidden/g,"");
				hv01030000.className += " hidden";
				hv01030300.className += " hidden";
				hv01030303.className += " hidden";
				hv11000000.className += " hidden";
				hv11130000.className += " hidden";
				hv11131400.className += " hidden";
				hv11140000.className += " hidden";
				hv11141400.className += " hidden";
				hv11141414.className += " hidden";
				exp_board_1_version_section.style.backgroundColor = redAlert;
				exp_board_2_version_section.style.backgroundColor = redAlert;
				exp_board_3_version_section.style.backgroundColor = redAlert;
				console.log("not a hardware version")
				break;
		}

	}

	// **********************************************************************************************************************
	// show/hide device sections
	function changeDeviceList(elmt){
		var allDevBoxes, allDevTabs, i;
		allDevBoxes = document.getElementsByClassName("system-devices");
		for(i=0; i < allDevBoxes.length; i++){
			allDevBoxes[i].className = allDevBoxes[i].className.replace(" in", "");
		}
		allDevTabs = document.getElementById("secondTabs").children;
		for(i=0; i < allDevTabs.length; i++){
			allDevTabs[i].className = allDevTabs[i].className.replace(" highlight-tab","");
		}
		elmt.className += " highlight-tab";
	}
	// **********************************************************************************************************************
	// Maintain a highlight on the selected tab
	function highlightTab(elmt){
		var allDevTabs, i;
		allDevTabs = document.getElementById("firstTabs").children;
		for(i=0; i < allDevTabs.length; i++){
			allDevTabs[i].className = allDevTabs[i].className.replace(" highlight-tab","");
		}
		elmt.className += " highlight-tab";
	}

</script>

@stop
