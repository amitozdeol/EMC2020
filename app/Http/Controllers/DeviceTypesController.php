<?php

namespace App\Http\Controllers;

use App\DeviceType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DeviceTypesController extends Controller
{

  /**
  * Display a listing of the device types.
  *
  * @return Redirect::route("admin.devicetype.index"): The list view of all the current device types in the device_types table
  */
    public function index()
    {
        $device_types = DeviceType::orderby('IO', 'ASC')
        ->orderby('mode')
        ->orderby('function')
        ->get();

        $type_ios = DeviceType::groupby('IO')
        ->orderby('IO', 'ASC')
        ->get();

        $type_modes = DeviceType::groupby('mode', 'IO')
        ->orderby('mode', 'ASC')
        ->get();

        return view('devicetypes.list')
        ->with('device_types', $device_types)
        ->with('type_ios', $type_ios)
        ->with('type_modes', $type_modes);
    }


  /**
  * Store a newly created device type in storage.
  *
  * @return Redirect::route("admin.devicetype.index"): The list view of all the current device types in the device_types table
  */
    public function store()
    {
        $max_device_type_id      = DeviceType::select('id')->orderBy('id', 'DESC')->limit(1)->first();
        $max_device_type_command = DeviceType::select('command')->orderBy('command', 'DESC')->first();

        $device_type = new DeviceType();

        $device_type->id = $max_device_type_id->id + 1;
        $device_type->command = $max_device_type_command->command + 1;

        foreach (Input::except('_token', '_method') as $key => $value) {
            $device_type->$key = $value;
        }

        $device_type->save();

        return Redirect::route("admin.devicetype.index");
    }


  /**
  * Update the specified device type in storage.
  *
  * @param  int  $id: The recnum of the device type being updated.
  * @return Redirect::route("admin.devicetype.index"): The list view of all the current device types in the device_types table
  */
    public function update($id)
    {
        $device_type = DeviceType::find($id);
    
        foreach (Input::except('_token', '_method') as $key => $value) {
            $device_type->$key = $value;
        }

        $device_type->save();

        return Redirect::route("admin.devicetype.index");
    }


  /**
  * Remove the specified device type from storage.
  *
  * @param  int  $id: The recnum of the device type being removed.
  * @return Redirect::route("admin.devicetype.index"): The list view of all the current device types in the device_types table
  */
    public function destroy($id)
    {
        $device_type = DeviceType::find($id);
        $device_type->delete();

        return Redirect::route("admin.devicetype.index");
    }
}
