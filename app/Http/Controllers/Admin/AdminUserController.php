<?php

class AdminUserController extends BaseController
{

    public $mobile_carriers = [
    '@txt.att.net'                => 'AT&T',
    '@tmomail.net'                => 'T-Mobile',
    '@vtext.com'                  => 'Verizon',
    '@pm.sprint.com'              => 'Sprint',
    '@vmobl.com'                  => 'Virgin Mobile',
    '@mmst5.tracfone.com'         => 'Tracfone',
    '@mymetropcs.com'             => 'MetroPCS',
    '@myboostmobile.com'          => 'Boost Mobile',
    '@mms.cricketwireless.com'    => 'Cricket Wireless',
    '@ptel.com'                   => 'Ptel/Powertel',
    '@text.republicwireless.com'  => 'Republic Wireless',
    '@msg.fi.google.com'          => 'Google Fi',
    '@tms.suncom.com'             => 'Suncom',
    '@message.ting.com'           => 'Ting',
    '@email.uscc.net'             => 'U.S. Cellular',
    '@cingularme.com'             => 'Consumer Cellular',
    '@cspire.com'                 => 'C-Spire',
    '@vtext.com'                  => 'Page Plus',
    '@message.alltel.com'         => 'All Tell',
    '@csouth1.com'                => 'Cellular South',
    '@cwemail.com'                => 'Centennial Wireless',
    '@gocbw.com'                  => 'Cincinnati Bell',
    '@vmobl.com'                  => 'Virgin Mobile USA ',
    '@voicestream.net'            => 'VoiceStream',
    '@sms.edgewireless.com'       => 'Edge Wireless',
    '@comcastpcs.textmsg.com'     => 'Comcast',
    '@mobile.celloneusa.com'      => 'Cellular One',
    '@alltelmessage.com'          => 'Alltel',
    '@airtelmail.com'             => 'Delhi Aritel',
    '@emtelworld.net'             => 'Emtel',
    '@fido.ca'                    => 'Fido'
    ];

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {

        $data['users'] = User::all();
        $data['customers'] = Customer::where('id', '>', 0)->get();

        return View::make('admin.user.index', $data);
    }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
    public function create()
    {

        $customers = Customer::orderby('name')->get();
        $roles = DB::table('role_labels')
        ->where('id', '<=', Auth::user()->role)
        ->get();

        $data['customers'] = [null=>'Choose a Customer Account', 0=>'EAW (Internal Staff)'];
        $data['roles'] = [null=>'Choose an Auth Level'];

        foreach ($customers as $customer) {
            $data['customers'][$customer->id] = $customer->name;
        }

        foreach ($roles as $role) {
            $data['roles'][$role->id] = $role->label;
        }

        foreach ($this->mobile_carriers as $key => $value) {
            $data['mobile_carriers'][$key] = $value;
        }
        asort($data['mobile_carriers'], SORT_STRING);

        return View::make('admin.user.create', $data);
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store()
    {

        $user = new User();
        $inputs = Input::except('_token', 're-password');

        if (! $this->validate_email($inputs['email'])) {
            return Redirect::back()->withInput();
        }

        if (! $this->validate_role()) {
            return Redirect::back()->withInput();
        }

        if (! $this->validate_password()) {
            return Redirect::back()->withInput();
        }

        foreach ($inputs as $field => $value) {
            $user->$field = $value;
        }
        $user->password = Hash::make(Input::get('password'));

        if ($user->save()) {
            SystemLog::info(0, 'New user #'.$user->id.'('.$user->email.') was created by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', 'A user account was created for <strong>' . $user->email . '</strong>');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to create new user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', "There was an error creating the $user->email account");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }
        return Redirect::to(URL::route('admin.user.index') . '#' . $user->id);
    }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function show($id)
    {
        //
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($id)
    {

        $customers = Customer::orderby('name')->get();
        $roles = DB::table('role_labels')
        ->where('id', '<=', Auth::user()->role)
        ->get();

        $data['user'] = User::find($id);
        $data['customers'] = [null=>'Choose a Customer Account', 0=>'EAW (Internal Staff)'];
        $data['roles'] = [null=>'Choose an Auth Level'];

        foreach ($customers as $customer) {
            $data['customers'][$customer->id] = $customer->name;
        }

        foreach ($roles as $role) {
            $data['roles'][$role->id] = $role->label;
        }

        foreach ($this->mobile_carriers as $key => $value) {
            $data['mobile_carriers'][$key] = $value;
        }
        asort($data['mobile_carriers'], SORT_STRING);

        return View::make('admin.user.edit', $data);
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function update($id)
    {

        $user = User::find($id);
        $inputs = Input::except('_token', '_method');

        if (! $this->validate_email($inputs['email'], $user)) {
            return Redirect::back()->withInput();
        }

        if (! $this->validate_role()) {
            return Redirect::back()->withInput();
        }

        foreach ($inputs as $field => $value) {
            $user->$field = $value;
        }
        $user->password = Hash::make(Input::get('password'));
        if ($user->save()) {
            SystemLog::info(0, 'User information for user #'.$user->id.'('.$user->email.') was updated by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', 'User information was updated for <strong>' . $user->email . '</strong>');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update user information for #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', 'There was an error creating the' .$user->email. 'account');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }
        return Redirect::to(URL::route('admin.user.index'));
    }


  /**
   * Update the specified user password in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit_password($id)
    {

        $data['user'] = User::find($id);

        return View::make('admin.user.password', $data);
    }


    public function update_password($id)
    {

        if (! $this->validate_password()) {
            return Redirect::back();
        }

        $user = User::find($id);
        $user->password = Hash::make(Input::get('password'));
        if ($user->save()) {
            SystemLog::info(0, 'Reset password for user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', 'The password was updated for <strong>' . $user->email . '</strong>');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to reset password for user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', 'There was an error updating the password for <strong>' . $user->email . '</strong>');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::to(URL::route('admin.user.index') . '#' . $user->id);
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($id)
    {

        /* Remove all access rules for the user being deleted */
        $managers = BuildingManager::where('user_id', $id)->get();

        foreach ($managers as $manager) {
            $manager->delete();
        }

        /* Remove all access rules for the user being deleted */
        $group_managers = BuildingGroupManager::where('user_id', $id)->get();

        foreach ($group_managers as $group_manager) {
            $group_manager->delete();
        }

        $user = User::find($id);

        if ($user->delete()) {
            SystemLog::info(0, 'User #'.$user->id.'('.$user->email.') was deleted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', 'The user account for <strong>' . $user->email . '</strong> was removed.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to delete user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', 'There was an error removing the user' .$user->email);
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('admin.user.index');
    }


    public static function validate_email($email, User $current_user = null)
    {
        $check = User::where('email', '=', Input::get('email'));
        if ($current_user != null) {
            $check = $check->where('id', '!=', $current_user->id);
        }
        $count = ($check->count() > 0);

        if ($count) {
            Session::flash('message', 'A user with that email already exists.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        } else {
            return true;
        }
    }


    public static function validate_password($password1 = null, $password2 = null)
    {

        try {
            if ($password1 === null) {
                $password1 = Input::get('password');
            }
            if ($password2 === null) {
                $password2 = Input::get('re-password');
            }
        } catch (Exception $e) {
            return false;
        }

        if ($password1 !== $password2) {
            Session::flash('message', 'The password fields must match.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        }

        if ($password1 === '') {
            Session::flash('message', 'You must enter a password.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        }

        //TODO: establish global password requirements

        return true;
    }


    private function validate_role($role = null, $customer_id = null)
    {

        try {
            if ($role === null) {
                $role        = intval(Input::get('role'));
            }
            if ($customer_id === null) {
                $customer_id = intval(Input::get('customer_id'));
            }
        } catch (Exception $e) {
            return false;
        }

        /* Make sure there is a value for role and customer_id */
        if ($role === '' || $customer_id === '') {
            Session::flash('message', 'Both Authorization Level and Customer must be set.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        }

        /* Only EAW staff may be in a Support, Admin, or Sysadmin role */
        if ($customer_id > 0 && $role >= 6) {
            Session::flash('message', 'Only EAW Staff can be in a Support, Administrator, or Systems Administrator role.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        }

        /* Only EAW staff may be in a Support, Admin, or Sysadmin role */
        if ($role === 0 && $role < 6) {
            Session::flash('message', 'EAW Staff should be in Support, Administrator, or Systems Administrator roles.<br>Lower authorization levels are meant for customer accounts.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
            return false;
        }

        return true;
    }
}
