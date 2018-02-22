<?php

namespace App\Http\Controllers;

use App\Building;
use App\ChartAttribute;
use App\DashboardItem;
use App\DeviceSetpoints;
use App\DeviceType;
use App\MappingOutput;
use App\System;
use Illuminate\Support\Facades\Input;


class ChartController extends Controller
{

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($bid, $sid, $id)
    {
        $thisBldg = Building::find($bid);
        $thisSys = System::find($sid);

        $sensors = DeviceSetpoints::join('devices', 'devices.id', '=', 'device_setpoints.device_id')
        ->where('devices.system_id', $sid)->where('devices.retired', '!=', 1)->where('devices.inhibited', '!=', 1)->where('devices.device_io', 'input')->orderBy('devices.name', 'ASC')
        ->where('device_setpoints.system_id', $sid)->orderBy('device_setpoints.device_id', 'ASC')->orderBy('device_setpoints.command', 'ASC')
        ->join('device_types', 'device_setpoints.command', '=', 'device_types.command')
        ->join('zone_labels', 'devices.zone', '=', 'zone_labels.zone')->where('zone_labels.system_id', $sid)
        ->select('devices.*', 'device_types.*', 'device_setpoints.*', 'zone_labels.*', 'devices.name AS name', 'devices.id AS id', 'devices.id AS device_id', 'device_setpoints.command AS device_command')
        ->get();

        $controls = MappingOutput::join('devices', 'devices.id', '=', 'mapping_output.device_id')
        ->where('devices.retired', 0)->where('devices.inhibited', 0)->where('devices.device_io', 'output')->where('devices.system_id', $sid)->orderBy('devices.name', 'ASC')
        ->where('mapping_output.system_id', $sid)->orderBy('mapping_output.device_id', 'ASC')->orderBy('mapping_output.device_type', 'ASC')
        ->join('device_types', 'mapping_output.device_type', '=', 'device_types.command')
        ->join('zone_labels', 'devices.zone', '=', 'zone_labels.zone')->where('zone_labels.system_id', $sid)
        ->select('devices.*', 'device_types.*', 'mapping_output.*', 'zone_labels.*', 'devices.name AS name', 'devices.id AS id')
        ->get();

        $deviceTypes = DeviceType::where('function', '!=', 'Current')
        ->orderBy('IO', 'ASC')
        ->orderBy('function', 'ASC')
        ->get();

        $dashboardItems = DashboardItem::where('system_id', $sid)
        ->get();

        $chartAttributes = ChartAttribute::where('system_id', $sid)
        ->get();

        $charts = [];
        $chart_attributes = [];

        foreach ($dashboardItems as $item) {
            if ($item->chart_type !== strtoupper($item->chart_type)) {
                $charts[$item->id] = $item;
            }
        }

        foreach ($chartAttributes as $chartAttribute) {
            $chart_attributes[$chartAttribute ->chart_id] = $chartAttribute;
        }


        /* Arrays for drop down select forms */
        $device_types = []; // Array to hold all of the device types with the command number as the key.
        $device_zones = []; // Array to hold all of the zone labels with the zone number as the key.
        $chart_types  = [];

        $x_ranges = [
        1  => "1 Hour",
        2  => "2 Hours",
        3  => "3 Hours",
        6  => "6 Hours",
        12 => "12 Hours",
        24 => "24 Hours"
        ];

        $chart_lists['Temperature'] = [
        "Temperature Line Chart",
        "Device Temperature"
        ];
        $chart_lists['Voltage'] = [
        "Voltage Line Chart",
        "Device Voltage"
        ];
        $chart_lists['Humidity'] = [
        "Humidity Line Chart",
        "Device Humidity"
        ];
        $chart_lists['Light'] = [
        "Light Line Chart",
        "Device Light"
        ];
        $chart_lists['Occupancy'] = [
        "Occupancy Line Chart",
        "Device Occupancy"
        ];
        $chart_lists['Analog'] = [
        "Analog Line Chart",
        "Device Analog"
        ];
        /*There is a Current type for Inputs and Outputs. Not sure if this is a neccesary chart. */
        // $chart_lists['Current'] = [
        //   "Current Line Chart",
        //   "Device Current"
        // ];
        $chart_lists['Digital'] = [
        "Digital Line Chart",
        "Device Digital"
        ];
        $chart_lists['Flow'] = [
        "Flow Line Chart",
        "Device Flow"
        ];
        $chart_lists['Pressure Differential'] = [
        "Pressure Differential Line Chart",
        "Device Pressure Differential"
        ];
        $chart_lists['Pressure'] = [
        "Pressure Line Chart",
        "Device Pressure"
        ];
        $chart_lists['Relay'] = [
        "Relay States",
        "Relay Activity Chart"
        ];
        $chart_lists['Virtual'] = [
        "Virtual States",
        "Virtual Activity Chart"
        ];







        $device_zones[0] = 'All Zones';
        foreach ($sensors as $sensor) {
            $device_zones[$sensor->zone]    = $sensor->zonename;
        }
        foreach ($controls as $control) {
            $device_zones[$control->zone]    = $control->zonename;
        }

        foreach ($deviceTypes as $type) {
            if ($type->IO == 'Input') {
                $device_types[$type->function] = $type->function.' Sensors';
            } else if ($type->IO == 'Output') {
                $device_types[$type->function] = $type->function.' Controllers';
            }
        }

        return view('charts.edit')
        ->with('thisBldg', $thisBldg)
        ->with('thisSys', $thisSys)
        ->with('id', $id)
        ->with('device_types', $device_types)
        ->with('device_zones', $device_zones)
        ->with('chart_types', $chart_types)
        ->with('sensors', $sensors)
        ->with('controls', $controls)
        ->with('charts', $charts)
        ->with('chart_attributes', $chart_attributes)
        ->with('chart_lists', $chart_lists)
        ->with('x_ranges', $x_ranges);
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function update($bid, $sid, $id)
    {
        //
        $input = Input::all();

        $chartAttributes = ChartAttribute::where('system_id', $sid)
        ->where('chart_id', $id)
        ->first();

        $dashboardItem = DashboardItem::where('system_id', $sid)
        ->where('id', $id)
        ->first();

        $chart_type_1 = explode('-', $input['chart_type_1']);
        if (isset($input['chart_type_2'])) {
            $chart_type_2 = explode('-', $input['chart_type_2']);
        }

        foreach ($input as $key => $value) {
            if (strpos($key, '-')) {
                if (!isset($devices)) {
                    $devices = $key;
                } else {
                    $devices .= ','.$key;
                }
            }
        }

        if (empty($chartAttributes)) {
            $chartAttributes = new ChartAttribute();
            $chartAttributes->system_id = $sid;
            $chartAttributes->chart_id = $id;
            $chartAttributes->save();
        }

        $chartAttributes->devices_1 = $devices;
        $chartAttributes->title = $input['title'];
        $chartAttributes->description = $input['description'];
        $chartAttributes->x_axis_1 = $input['x_axis_1'];
        $chartAttributes->x_axis_2 = $input['x_axis_2'];
        if (isset($input['x_range_1'])) {
            $chartAttributes->x_range_1 = $input['x_range_1_form'];
        }
        if (isset($input['x_range_2'])) {
            $chartAttributes->x_range_2 = $input['x_range_2'];
        }
        $chartAttributes->y_axis_1 = $input['y_axis_1'];
        $chartAttributes->y_axis_2 = $input['y_axis_2'];
        $chartAttributes->chart_type_1 = $input['chart_type_1'];
        $chartAttributes->chart_type_2 = $input['chart_type_2'];
        $chartAttributes->function_type_1 = $input['function_type_1'];
        $chartAttributes->function_type_2 = $input['function_type_2'];
        $chartAttributes->chart_label_1 = $input['chart_label_1'];
        $chartAttributes->chart_label_2 = $input['chart_label_2'];
        $chartAttributes->save();

        $dashboardItem->label = $input['title'];
        $dashboardItem->chart_type = 'Custom';
        $dashboardItem->save();

        return redirect(route('webmapping.index', [$bid, $sid]));
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($bid, $sid, $id)
    {
        //
        $chart = ChartAttribute::where('system_id', $sid)
        ->where('chart_id', $id)
        ->delete();

        $item = DashboardItem::where('system_id', $sid)
        ->where('id', $id)
        ->delete();

        return redirect(route('webmapping.index', [$bid, $sid]));
    }
}
