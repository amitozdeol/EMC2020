<?php

use App\Http\Controllers\Controller;

class AdminBuildingController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {
        $data['customers'] = Customer::orderBy('name')->get();
        $data['buildings'] = Building::all();

        return view('admin.building.index', $data);
    }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
    public function create()
    {
        $data['thisBldg'] = new Building();
        $data['customers'] = Customer::orderBy('name')->get();
        $customer_list = [''=>'Choose a Customer Account'];
        foreach ($data['customers'] as $customer) {
            $customer_list[$customer->id] = $customer->name;
        }
        $data['customer_list'] = $customer_list;

        return view('admin.building.create', $data);
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store()
    {
        $building = new Building();

        foreach (Input::except('_token', '_method') as $key => $value) {
            $building->$key = $value;
        }
        if ($building->save()) {
            SystemLog::info(0, 'New building #'.$building->id.'('.$building->name.') was created by user #'.Auth::user()->id.'('.Auth::user()->email.')', 23);
            Session::flash('message', "$building->name was successfully created");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to create new building '.$building->name.' as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 24);
            Session::flash('message', "There was an error creating $building->name");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('admin.building.index');
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($id)
    {
        $data['thisBldg'] = Building::find($id);
        $data['customers'] = Customer::orderBy('name')->get();
        $customer_list = [''=>'Choose a Customer Account'];
        foreach ($data['customers'] as $customer) {
            $customer_list[$customer->id] = $customer->name;
        }
        $data['customer_list'] = $customer_list;

        return view('admin.building.edit', $data);
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function update($id)
    {
        $building = Building::find($id);

        foreach (Input::except('_token', '_method') as $key => $value) {
            $building->$key = $value;
        }
        if ($building->save()) {
            SystemLog::info(0, 'Updated building #'.$building->id.'('.$building->name.') was created by user #'.Auth::user()->id.'('.Auth::user()->email.')', 23);
            Session::flash('message', "$building->name was successfully updated");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update building #'.$building->id.'('.$building->name.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 24);
            Session::flash('message', "There was an error updating $building->name");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('admin.building.index');
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($id)
    {
        $building = Building::find($id);
        $systems = System::where('building_id', $building->id)->get();

        /* Clean up the building's systems */
        foreach ($systems as $system) {
            /* Retire all devices */
            try {
                Device::where('system_id', $system->id)->update(['retired' => 1]);
                SystemLog::info($system->id, 'Retired all devices for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 25);
            } catch (Exception $e) {
                SystemLog::error($system->id, 'Failed to retire all devices for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 26);
            }

            /* Revoke user access */
            try {
                BuildingManager::where('building_id', $building->id)->delete();
                BuildingGroupMember::where('building_id', $building->id)->delete();
                SystemLog::info($system->id, 'Revoked all user access for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 33);
            } catch (Exception $e) {
                SystemLog::error($system->id, 'Failed to revoked all user access for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 34);
            }

            /* Remove email subscriptions*/
            try {
                Alert::where('building_id', $building->id)->delete();
                SystemLog::info($system->id, 'Removed email subscriptions for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 15);
            } catch (Exception $e) {
                SystemLog::error($system->id, 'Failed to remove email subscriptions for system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 16);
            }

            /* Delete the system */
            if ($system->delete()) {
                SystemLog::info($system->id, 'Deleted system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 25);
            } else {
                SystemLog::error($system->id, 'Failed to delete system #'.$system->id.'('.$system->name.') along with deletion of building #'.$building->id.'('.$building->name.')', 26);
            }
        }

        if ($building->delete()) {
            SystemLog::info(0, 'Building #'.$building->id.'('.$building->name.') was deleted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 23);
            Session::flash('message', "The $building->name building was successfully removed");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update building #'.$building->id.'('.$building->name.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 24);
            Session::flash('message', "There was a problem removing the $building->name building. You may need to try again.");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('admin.building.index');
    }
}
