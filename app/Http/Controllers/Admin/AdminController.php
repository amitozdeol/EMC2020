<?php

use App\Http\Controllers\Controller;

class AdminController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {

        date_default_timezone_set("America/New_York");// temporaryyy

        $date=date_create();
        $yesterday = time() - (24 * 60 * 60); //Subtract 24 hrs, in secs
        //date_timestamp_set($date,$now);
        //$yesterday = date_format($date,"Y-m-d H:i:s");

        $customers = Customer::where('id', '!=', 0)->orderBy('name', 'ASC')->get();

        $systems = DB::table('systems')
        ->join('buildings', 'systems.building_id', '=', 'buildings.id')
        ->select('systems.id', 'systems.name', 'systems.building_id', 'buildings.customer_id', 'systems.deleted_at as system_delete', 'systems.software_version')
        ->get();
    
        $alarms  = DB::table('alarms')
        ->where('alarms.active', 1)
        ->where('alarms.system_id', '>', 0)
        ->get();

        $buildings = DB::table('buildings')->get();
        $buildings_array = [];

        $systems_array = [];
        foreach ($systems as $system) {
            $systems_array[$system->id] = $system;
            $systems_array[$system->id]->alarm_severity = 0;
            $systems_array[$system->id]->alarm_time = $yesterday;
            $systems_array[$system->id]->alarm_intensity = 6;
            if (!$system->name) {
                $systems_array[$system->id]->name = 'System ' . $system->id;
            }
        }

        foreach ($buildings as $building) {
            $buildings_array[$building->id] = $building;
            if (!$building->name) {
                if (!$building->address1) {
                    $buildings_array[$building->id]->name = 'Building ' . $building->id;
                } else {
                    $buildings_array[$building->id]->name = $building->address1;
                }
            }
        }

        foreach ($alarms as $alarm) {
            if (isset($systems_array[$alarm->system_id])) {
                if ($systems_array[$alarm->system_id]->alarm_severity < $alarm->alarm_state) {
                    $systems_array[$alarm->system_id]->alarm_severity = $alarm->alarm_state;
                }
                if ($systems_array[$alarm->system_id]->alarm_time < strtotime($alarm->created_at)) {
                    $systems_array[$alarm->system_id]->alarm_time = strtotime($alarm->created_at);
                }
            }
        }
        foreach ($systems_array as $key => $sa) {
            $systems_array[$key]->alarm_intensity = ((6-ceil(($systems_array[$key]->alarm_time - $yesterday)/(3600*4))));
        }


        $data['customers'] = $customers;
        $data['systems'] = $systems_array;
        $data['buildings'] = $buildings_array;

        return View::make('admin.index', $data);
    }
}
