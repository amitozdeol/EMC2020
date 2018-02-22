<?php

class AccessController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $managers = DB::table('building_managers')
      ->join('users', 'users.id', '=', 'building_managers.user_id')
      ->join('buildings', 'buildings.id', '=', 'building_managers.building_id')
      ->join('role_labels', 'role_labels.id', '=', 'building_managers.role')
      ->where('buildings.customer_id', Auth::user()->customer_id)
      ->select('building_managers.id', 'first_name', 'last_name', 'email', 'name', 'building_managers.role', 'label')
      ->get();
    $groups = BuildingGroup::where('customer_id', Auth::user()->customer_id)
      ->get();
    $group_managers = DB::table('building_groups')
      ->join('building_group_managers', 'building_group_managers.building_group_id', '=', 'building_groups.id')
      ->join('users', 'users.id', '=', 'building_group_managers.user_id')
      ->join('role_labels', 'role_labels.id', '=', 'building_group_managers.role')
      ->select('building_group_managers.id', 'building_group_managers.building_group_id', 'users.first_name', 'users.last_name', 'users.email', 'building_group_managers.role', 'label')
      ->get();
    $group_buildings = DB::table('building_group_members')
      ->join('building_groups', 'building_groups.id', '=', 'building_group_members.building_group_id')
      ->join('buildings', 'buildings.id', '=', 'building_group_members.building_id')
      ->where('buildings.customer_id', Auth::user()->customer_id)
      ->select('building_group_members.id', 'building_group_members.building_group_id', 'buildings.name', 'buildings.address1')
      ->get();
    $buildings = Building::where('customer_id', Auth::user()->customer_id)
      ->get();
    $users = User::where('customer_id', Auth::user()->customer_id)
      ->get();
    $labels = DB::table('role_labels')
      ->where('id', '<', 5)
      ->get();

    $data = [
      'managers'           => $managers,
      'groups'             => $groups,
      'group_buildings'    => $group_buildings,
      'group_managers'     => $group_managers,
      'buildings' => [],
      'users'     => [],
      'labels'    => []
    ];

    array_unshift($data['buildings'], "Choose a Building");
    array_unshift($data['users'], "Choose a User");

    foreach($buildings as $building) {
      $data['buildings'][$building->id] = $building->name;
    }

    foreach($users as $user) {
      $data['users'][$user->id] = $user->email;
    }

    foreach($labels as $label) {
      $data['labels'][$label->id] = $label->label;
    }

    return View::make('buildings.access.list', $data);
  }


  public function create()
  {
    $customer_buildings = Building::where('customer_id', Auth::user()->customer_id)
      ->get();
    $customer_users = User::where('customer_id', Auth::user()->customer_id)
      ->get();

    $data = [
      'customer_buildings'=>[],
      'customer_users'=> []
    ];

    foreach($customer_buildings as $customer_building) {
      $data['customer_buildings'][$customer_building->id] = $customer_building->name;
    }
    array_unshift($data['customer_buildings'], "Choose a Building");
    foreach($customer_users as $customer_user) {
      $data['customer_users'][$customer_user->id] = $customer_user->email;
    }
    array_unshift($data['customer_users'], "Choose a User");

    return View::make('buildings.access.modal.new-manager', $data);
  }


  public function store()
  {
    $manager = BuildingManager::where('building_id', Input::get('building_id'))
      ->where('user_id', Input::get('user_id'))
      ->where('role', Input::get('role'))
      ->first();
    if(count($manager)) {
        Session::flash('danger', 'That access rule already exists.');
    }else{
      $manager = new BuildingManager();
      foreach(Input::except('_token') as $key => $val) {
        $manager->$key = $val;
      }
      if($manager->save()) {
        Session::flash('success', 'The new access rule was successfully created.');
        return Redirect::route('access.index');
      }
    }
  }


  public function destroy()
  {
    $manager = BuildingManager::find(Input::get('building_manager_id'));
    if( $manager->delete() ) {
      Session::flash('success', 'The access rule was successfully removed.');
    }else{
      Session::flash('success', 'The access rule was successfully removed.');
    }
    return Redirect::route('access.index');
  }


  public function storeGroup()
  {
    $group = new BuildingGroup();
    $group->customer_id = Auth::user()->customer_id;
    $group->name = Input::get('name');

    if($group->save()) {
      Session::flash('success', 'The new group was successfully created.');
    }else{
      Session::flash('error', 'There was an error creating the new group.');
    }
    return Redirect::route('access.index');
  }


  public function updateGroup()
  {
    $group = BuildingGroup::find(Input::get('id'));
    $group->name = Input::get('name');

    if($group->save()) {
      Session::flash('success', 'yes');
    }else{
      Session::flash('error', 'no');
    }

    return Redirect::route('access.index');
  }


  public function deleteGroup($id)
  {
    $group = BuildingGroup::find($id);

    if($group->customer_id === Auth::user()->customer_id) {
      $group_buildings = BuildingGroupMember::where('building_group_id', $group->id)->get();
      foreach($group_buildings as $group_building) {$group_building->delete();}

      $group_managers = BuildingGroupManager::where('building_group_id', $group->id)->get();
      foreach($group_managers as $group_manager) {$group_manager->delete();}
      if($group->delete()) {
        Session::flash('success', 'The group was successfully removed.');
      }
    }else{
      Session::flash('error', 'You can only delete groups that belong to you.');
    }

    return Redirect::route('access.index');

  }


  public function addGroupBuilding()
  {
    $building_group_id = Input::get('id');
    $building_id = Input::get('building_id');
    $existing = BuildingGroupMember::where('building_id', $building_id)
      ->where('building_group_id', $building_group_id)
      ->get();

    if(count($existing)) {
      Session::flash('error', 'The building you selected is already part of this group.');
    }else{
      $member = new BuildingGroupMember();
      $member->building_group_id = $building_group_id;
      $member->building_id = $building_id;
      if($member->save()) {
        Session::flash('success', 'The building was successfully added.');
      }else{
        Session::flash('error', 'There was an error adding the building to this group.');
      }
    }

    return Redirect::route('access.index');
  }

  public function deleteGroupBuilding()
  {
    $building = BuildingGroupMember::find(Input::get('id'));

    if($building->delete()) {
      Session::flash('success', 'The building was successfully removed from this group.');
    }else{
      Session::flash('error', 'There was an error removing the building from this group.');
    }
    return Redirect::route('access.index');
  }


  public function addGroupManager()
  {
    $group_id = Input::get('id');
    $user_id = Input::get('user_id');
    $role = Input::get('role');
    $existing = BuildingGroupManager::where('building_group_id', $group_id)->where('user_id', $user_id)
      ->where('role', $role)
      ->count();


    if($existing) {
      Session::flash('error', 'The access rule you defined is already part of this group.');
    }else{
      $manager = new BuildingGroupManager();
      $manager->building_group_id = Input::get('id');
      $manager->user_id = $user_id;
      $manager->role = $role;
      if($manager->save()) {
        Session::flash('success', 'The manager was successfully added.');
      }else{
        Session::flash('error', 'There was an error adding the manager to this group.');
      }
    }

    return Redirect::route('access.index');
  }

  public function deleteGroupManager()
  {
    $manager = BuildingGroupManager::find(Input::get('id'));

    if($manager->delete()) {
      Session::flash('success', 'The manager was successfully removed.');
    }else{
      Session::flash('error', 'There was an error removing the manager.');
    }
    return Redirect::route('access.index');
  }


  /**
   * Look up the highest role level a user has for a building
   * @param  integer $user_id     The user being checked
   * @param  integer $building_id The building being checked
   * @return integer              The highest role this user has in this building
   */
  public static function checkRole($user_id, $building_id)
  {
    $role = 0;

    /* Get building managers for this building */
    $building_managers = BuildingManager::where('user_id', $user_id)
      ->where('building_id', $building_id)
      ->get();
    foreach($building_managers as $building_manager) {
      if($building_manager->role > $role) {
        $role = $building_manager->role;
      }
    }

    /* Get group managers for this building's group */
    $building_groups = DB::table('building_groups')
      ->join('building_group_managers', 'building_group_managers.building_group_id', '=', 'building_groups.id')
      ->join('building_group_members',  'building_group_members.building_group_id',  '=', 'building_groups.id')
      ->where('building_group_managers.user_id', $user_id)
      ->where('building_group_members.building_id', $building_id)
      ->get();
    foreach($building_groups as $building_group) {
      if($building_group->role > $role) {
        $role = $building_group->role;
      }
    }

    $user = User::find($user_id);
    if($user->role >= 6 && $user->customer_id == 0) {
      $role = $user->role;
    }

    return $role;

  }

  

}
