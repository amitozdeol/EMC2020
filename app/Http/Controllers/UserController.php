<?php

class UserController extends BaseController
{

    public $mobile_carriers = [
    '@txt.att.net'                => 'AT&T',
    '@tmomail.net'                => 'T-Mobile',
    '@vtext.net'                  => 'Verizon',
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
        $mobile_carrier = [];
        foreach ($this->mobile_carriers as $key => $value) {
            $mobile_carrier[$key] = $value;
        }
        $users = User::where('customer_id', Auth::user()->customer_id)->get();

        return View::make('users.list')
        ->with('users', $users)
        ->with('mobile_carrier', $mobile_carrier);
    }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
    public function create()
    {
        $mobile_carrier = [];
        $user = new User();
        $customer = Customer::find(Auth::user()->customer_id);
        $roles_raw = DB::table('role_labels')
        ->where('id', '<=', Auth::user()->role)
        ->get();
        asort($this->mobile_carriers, SORT_STRING);

        foreach ($roles_raw as $role) {
            $roles[$role->id] = $role->label;
        }
        foreach ($this->mobile_carriers as $key => $value) {
            $mobile_carrier[$key] = $value;
        }

        return View::make('users.form')
        ->with('user', $user)
        ->with('customer', $customer)
        ->with('roles', $roles)
        ->with('mobile_carriers', $mobile_carrier);
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store()
    {
        if (Input::get('password') === Input::get('re-password')) {
            $user = new User();
            foreach (Input::except('_token', 're-password') as $key => $value) {
                $user->$key = $value;
            }
            $user->customer_id = Auth::user()->customer_id;
            $user->password = Hash::make(Input::get('password'));

            if ($user->save()) {
                SystemLog::info($user->customer_id, 'New user #'.$user->id.'('.$user->email.') was created by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
                Session::flash('message', "The $user->email account was successfully created");
                Session::flash('alert-class', 'alert-success alert-dismissable');
            } else {
                SystemLog::error($user->customer_id, 'Failed to create new user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
                Session::flash('message', "There was an error creating the $user->email account");
                Session::flash('alert-class', 'alert-danger alert-dismissable');
            }

            return Redirect::to('user#'.$user->id);
        }
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($id)
    {
        $user = User::find($id);
        $customer = Customer::find(Auth::user()->customer_id);
        asort($this->mobile_carriers, SORT_STRING);

        return View::make('users.form')
        ->with('user', $user)
        ->with('customer', $customer)
        ->with('mobile_carriers', $this->mobile_carriers);
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
        foreach (Input::except('_token', '_method') as $key => $value) {
            $user->$key = $value;
        }
        if ($user->save()) {
            SystemLog::info(0, 'User information for user #'.$user->id.'('.$user->email.') was updated by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', "The $user->email account was successfully updated");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update user information for #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', "There was an error updating the $user->email account");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::to('user#'.$user->id);
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            SystemLog::info($user->customer_id, 'User #'.$user->id.'('.$user->email.') was deleted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', "The user $user->email was successfully removed");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error($user->customer_id, 'Failed to delete user #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', "There was an error removing the user $user->email");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::to('user');
    }


  /**
   * Reset the specified user's password.
   *
   * @param  int  $id
   * @return Response
   */
    public function password($id)
    {
        $user = User::find($id);
        if (Input::get('password') === Input::get('re-password')) {
            $user->password = Hash::make(Input::get('password'));
        }
        if ($user->save()) {
            SystemLog::info(0, 'Password was reset for user #'.$user->id.'('.$user->email.') was updated by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash("success", "The password for $user->email was successfully updated");
            Session::flash('message', "The password for $user->email was successfully updated");
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to reset password for #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', "There was an error updating the password for $user->email");
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::to('user');
    }


/**
   * Display the user's account information.
   *
   * @param  int  $id
   * @return Response
   */
    public function account()
    {
        $user = User::find(Auth::user()->id);
        $alert_classes = AlarmCodes::select('alarm_class')->groupBy('alarm_class')->orderBy('alarm_class');

        /* Prevent non-EAW staff from subscribing to most alerts*/
        if (Auth::user()->customer_id !== 0) {
            $alert_classes->where('alarm_class', '!=', 'EAW');
        }

        $alert_classes = $alert_classes->get()->toArray();
        $alert_subscriptions = Alert::where('user_id', Auth::user()->id)->get();
        $buildings = $this->customer_buildings_for_navbar;

        asort($this->mobile_carriers, SORT_STRING);
        foreach ($this->mobile_carriers as $key => $value) {
            $mobile_carrier[$key] = $value;
        }

        $data['user'] = $user;
        $data['buildings'] = $buildings;


        $customers = DB::table('customers')
        ->whereNull('deleted_at')
        ->orderby('name', 'asc')
        ->get();
        $systems =  DB::table('systems')
        ->whereNull('deleted_at')
        ->orderBy('id', 'asc')
        ->get();

        $alarm_codes = AlarmCodes::all();
        $alerts = Alert::where('user_id', Auth::user()->id)->get();

        $sensor_alerts = [];
        $log_alerts = [];
        $control_alerts = [];

        /******************* mark which alerts are currently active for this user **********************************8*/
        foreach ($alerts as $alert) {
            if (!is_null($alert->function)) {
                if ($alert->alarm_code == 29 || $alert->alarm_code == 32) {
                    if ($alert->email == '1') {
                        $sensor_alerts[$alert->system_id]['critical'][$alert->function]['email'] = true;
                    }
                    if ($alert->sms == '1') {
                        $sensor_alerts[$alert->system_id]['critical'][$alert->function]['sms'] = true;
                    }
                } else {
                    if ($alert->email == '1') {
                        $sensor_alerts[$alert->system_id]['warning'][$alert->function]['email'] = true;
                    }
                    if ($alert->sms == '1') {
                        $sensor_alerts[$alert->system_id]['warning'][$alert->function]['sms'] = true;
                    }
                }
            } else {
                if ($alert->notification_type == 'alarm') {
                    if ($alert->email == '1') {
                        $control_alerts[$alert->system_id][$alert->alarm_code]['email'] = true;
                    }
                    if ($alert->sms == '1') {
                        $control_alerts[$alert->system_id][$alert->alarm_code]['sms'] = true;
                    }
                } else {
                    if ($alert->email == '1') {
                        $log_alerts[$alert->system_id][$alert->alarm_code]['email'] = true;
                    }
                    if ($alert->sms == '1') {
                        $log_alerts[$alert->system_id][$alert->alarm_code]['sms'] = true;
                    }
                }
            }
        }

        /*Discern which device types are relevant to this customer*/
        if (Auth::user()->customer_id != 0) {
            $customer_dev_types = DB::table('product_types')
            ->join('devices', 'devices.product_id', '=', 'product_types.product_id')
            ->join('systems', 'systems.id', '=', 'devices.system_id')
            ->join('buildings', 'buildings.id', '=', 'systems.building_id')
            ->join('users', 'users.customer_id', '=', 'buildings.customer_id')
            ->where('users.id', Auth::user()->id)
            ->select('product_types.commands as commands', 'systems.id as sys_id')
            ->get();
        } else {
            /*EAW STAFF USERS*/
            $customer_dev_types = DB::table('product_types')
            ->join('devices', 'devices.product_id', '=', 'product_types.product_id')
            ->join('systems', 'systems.id', '=', 'devices.system_id')

            ->select('product_types.commands as commands', 'systems.id as sys_id')
            ->get();
        }

        $system_dev_types = [];

        foreach ($customer_dev_types as $cdt) {
            $boom = explode(',', $cdt->commands);
            foreach ($boom as $boomlet) {
                $system_dev_types[$cdt->sys_id][] = $boomlet;
            }
        }
        foreach ($system_dev_types as $key => $sdt) {
            $system_dev_types[$key] = array_unique($sdt);
        }

        $device_types = DeviceType::where('IO', 'Input')->get();

        $commands = [];
        foreach ($device_types as $type) {
            foreach ($system_dev_types as $key => $sdt_array) {
                foreach ($sdt_array as $sys_dt) {
                    if ($sys_dt == $type->id) {
                        $commands[$key][str_replace(' ', '', $type->function)] = $type->function;
                    }
                }
            }
        }

        if (Auth::user()->role >= 6) {
            $buildings = Building::all();
            $log_types = LogType::all();


            return View::make('users.account', $data)
            ->with('customers', $customers)
            ->with('buildings', $buildings)
            ->with('systems', $systems)
            ->with('alarm_codes', $alarm_codes)
            ->with('sensor_alerts', $sensor_alerts)
            ->with('control_alerts', $control_alerts)
            ->with('log_alerts', $log_alerts)
            ->with('log_types', $log_types)
            ->with('commands', $commands)
            ->with('mobile_carriers', $mobile_carrier);
        }



        return View::make('users.account', $data)
        ->with('customers', $customers)
        ->with('systems', $systems)
        ->with('alarm_codes', $alarm_codes)
        ->with('sensor_alerts', $sensor_alerts)
        ->with('control_alerts', $control_alerts)
        ->with('log_alerts', $log_alerts)
        ->with('commands', $commands)
        ->with('mobile_carriers', $mobile_carrier);
    }


/**
   * Display the user's account information.
   *
   * @param  int  $id
   * @return Response
   */
    public function accountUpdate()
    {
        $conflicts = User::where('email', Input::get('email')) ->where('id', '!=', Auth::user()->id)->count();
        if ($conflicts) {
            Session::flash('error', 'The email addres you entered is already in use.');
            return Redirect::back();
        }
        /*Update the user's personal information************************************************************************************************/
        $user = User::find(Auth::user()->id);
        $user->email = Input::get('email');
        $user->suffix = Input::get('suffix');
        $user->prefix = Input::get('prefix');
        $user->last_name = Input::get('last_name');
        $user->first_name = Input::get('first_name');
        $user->mobile_number = Input::get('mobile_number');
        $user->middle_initial = Input::get('middle_initial');
        $user->mobile_carrier = Input::get('mobile_carrier');
        if ($user->save()) {
            SystemLog::info(0, 'User information for user #'.$user->id.'('.$user->email.') was updated by user #'.Auth::user()->id.'('.Auth::user()->email.')', 19);
            Session::flash('message', 'Your account information has been updated.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update user information for #'.$user->id.'('.$user->email.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 20);
            Session::flash('message', 'There was a problem updating your account.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        /*define indeceies of parsed inputs*/
        static $IDENIFIER = 0;
        static $SYSTEM_ID = 1;
        static $ALARM_FUNCTION = 2;
        static $COMM_METHOD = 3;
        /*Update the user's notification subscriptions**********************************************************************************************/
        $notifications = Input::except('_token', 'email', 'first_name', 'last_name', 'email', 'mobile_number', 'mobile_carrier', 'prefix', 'middle_initial', 'suffix');

        Alert::where('user_id', Auth::user()->id)->delete();

        foreach ($notifications as $notification => $value) {
            $parsed_notification = explode('-', $notification);

            if ($parsed_notification[$IDENIFIER] == 'alarm' || $parsed_notification[$IDENIFIER] == 'log') {
                $alert = new Alert();
                $alert->notification_type = ( $parsed_notification[$IDENIFIER] == 'log' )?'log':'alarm';
                $alert->user_id = Auth::user()->id;
                $alert->system_id = $parsed_notification[$SYSTEM_ID];
                $alert->building_id = 0;
                $alert->alarm_code = $parsed_notification[$ALARM_FUNCTION];
                $alert->unsubscribe_key = md5(microtime(1));
                $alert->class_subscription = ' ';
                $alert->severity = 0;
                $alert->email = ($parsed_notification[$COMM_METHOD] == 'email')?'1':'0';
                $alert->sms = ($parsed_notification[$COMM_METHOD] == 'sms')?'1':'0';
                $alert->save();
                unset($alert);
            } else if ($parsed_notification[$IDENIFIER] == 'critical') {
                $lowAlert = new Alert();
                $lowAlert->user_id = Auth::user()->id;
                $lowAlert->alarm_code = 29;
                $lowAlert->system_id = $parsed_notification[$SYSTEM_ID];
                $lowAlert->building_id = 0;
                $lowAlert->function = $parsed_notification[$ALARM_FUNCTION];
                $lowAlert->notification_type = 'alarm';//29,32
                $lowAlert->unsubscribe_key = md5(microtime(1));
                $lowAlert->class_subscription = ' ';
                $lowAlert->severity = 0;
                $lowAlert->email = ($parsed_notification[$COMM_METHOD] == 'email')?'1':'0';
                $lowAlert->sms = ($parsed_notification[$COMM_METHOD] == 'sms')?'1':'0';
                $lowAlert->save();
                unset($lowAlert);

                $highAlert = new Alert();
                $highAlert->user_id = Auth::user()->id;
                $highAlert->alarm_code = 32;
                $highAlert->system_id = $parsed_notification[$SYSTEM_ID];
                $highAlert->building_id = 0;
                $highAlert->function = $parsed_notification[$ALARM_FUNCTION];
                $highAlert->notification_type = 'alarm';//29,32
                $highAlert->unsubscribe_key = md5(microtime(1));
                $highAlert->class_subscription = ' ';
                $highAlert->severity = 0;
                $highAlert->email = ($parsed_notification[$COMM_METHOD] == 'email')?'1':'0';
                $highAlert->sms = ($parsed_notification[$COMM_METHOD] == 'sms')?'1':'0';
                $highAlert->save();
                unset($highAlert);
            } else if ($parsed_notification[$IDENIFIER] == 'warning') {
                $lowAlert = new Alert();
                $lowAlert->user_id = Auth::user()->id;
                $lowAlert->alarm_code = 30;
                $lowAlert->system_id = $parsed_notification[$SYSTEM_ID];
                $lowAlert->building_id = 0;
                $lowAlert->function = $parsed_notification[$ALARM_FUNCTION];
                $lowAlert->notification_type = 'alarm';//29,32
                $lowAlert->unsubscribe_key = md5(microtime(1));
                $lowAlert->class_subscription = ' ';
                $lowAlert->severity = 0;
                $lowAlert->email = ($parsed_notification[$COMM_METHOD] == 'email')?'1':'0';
                $lowAlert->sms = ($parsed_notification[$COMM_METHOD] == 'sms')?'1':'0';
                $lowAlert->save();
                unset($lowAlert);

                $highAlert = new Alert();
                $highAlert->user_id = Auth::user()->id;
                $highAlert->alarm_code = 31;
                $highAlert->system_id = $parsed_notification[$SYSTEM_ID];
                $highAlert->building_id = 0;
                $highAlert->function = $parsed_notification[$ALARM_FUNCTION];
                $highAlert->notification_type = 'alarm';//29,32
                $highAlert->unsubscribe_key = md5(microtime(1));
                $highAlert->class_subscription = ' ';
                $highAlert->severity = 0;
                $highAlert->email = ($parsed_notification[$COMM_METHOD] == 'email')?'1':'0';
                $highAlert->sms = ($parsed_notification[$COMM_METHOD] == 'sms')?'1':'0';
                $highAlert->save();
                unset($highAlert);
            }
        }



        return Redirect::route('account.index');
    }


/**
   * Display a password reset form.
   *
   * @param  int  $id
   * @return Response
   */
    public function accountPassword()
    {
        return View::make('users.password');
    }


/**
   * Update the user's password.
   *
   * @param  int  $id
   * @return Response
   */
    public function accountPasswordUpdate()
    {
        if (! AdminUserController::validate_password()) {
            return Redirect::back();
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make(Input::get('password'));
        if ($user->save()) {
            SystemLog::info(0, 'User #'.$user->id.'('.$user->email.') has changed their password', 19);
            Session::flash('message', 'Your password was successfully updated.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to change password for user #'.$user->id.'('.$user->email.')', 20);
            Session::flash('message', 'There was a problem updating your password.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('account.index');
    }
}
