<?php

class EmailController extends BaseController
{


  /**
   * Send email notifications about a new alarm
   * @param  integer $alarm_id Primary key for the new alarm
   * @return
   */
    public static function alarmNotification($alarm_id)
    {
        $alarm = Alarms::where('notifications_sent', 0)
        ->join('alarm_codes', 'alarms.alarm_code_id', '=', 'alarm_codes.id')
        ->select('alarms.*', 'alarm_codes.*', 'alarms.description AS alarm_description', 'alarm_codes.description AS code_description')
        ->find($alarm_id);
        $alarm_code = AlarmCodes::find($alarm->alarm_code_id);

        $device_types = DeviceType::all();

        $functions = array();

        foreach ($device_types as $device_type) {
            $functions[$device_type->command] = str_replace(' ', '', $device_type->function);/*remove spaces from function field*/
        }

        $system = System::find($alarm->system_id);
        if (isset($system)) {
            $building = Building::find($system->building_id);
            $device = Device::where('system_id', $alarm->system_id)->where('id', $alarm->device_id)->first();

            if (isset($device)) {
                $zone = Zone::where('system_id', $alarm->system_id)->where('zone', $device->zone)->first();

                $alerts = Alert::where('system_id', $system->id)
                ->where('alarm_code', $alarm->alarm_code_id)
                ->where('notification_type', 'alarm');

                if ($alarm->alarm_code_id > 28 && $alarm->alarm_code_id < 33) {
                    $alerts = $alerts->where('function', $functions[$alarm->command]);
                }

                $alerts = $alerts->join('users', 'users.id', '=', 'alerts.user_id')
                ->select('users.*', 'alerts.*', 'users.id AS user_id', 'users.email AS email', 'alerts.email AS email_alert')
                ->where('deleted_at', null)
                ->get();

                foreach ($alerts as $alert) {
                    if (!AccessController::checkRole($alert->user_id, $building->id)) {
                        SystemLog::error($alarm->system_id, "User $alert->user_id does not have access to building $building->id", 14);
                    } else {
                        $data = [
                        'alarm'      => $alarm->toArray(),
                        'alarm_code' => $alarm_code->toArray(),
                        'alert'      => $alert->toArray(),
                        'building'   => $building->toArray(),
                        'system'     => $system->toArray(),
                        'device'     => $device->toArray(),
                        'zone'       => $zone->zonename,
                        'first_name' => $alert->first_name,
                        ];

                        if ($alert->email_alert == '1') {
                            try {
                                /*Queue messages for users' email accounts*/
                                Mail::queue(['html'=>'mail.html.alarm', 'plain'=>'mail.plain.alarm'], $data, function ($message) use ($alert, $alarm, $device) {
                                    $message
                                      ->to($alert->email, $alert->first_name . ' ' . $alert->last_name)
                                      ->from('noreply@eawelectro.com', 'EMC 20/20')
                                      ->subject('EMC 20/20 - ' . $alarm->description . ' - ' . $device->name);
                                });
                            } catch (Exception $e) {
                                SystemLog::error($alarm->system_id, "Failed to send email alert to [".$alert->email."]", 16);
                                SystemLog::error($alarm->system_id, $e, 16);
                            }
                        }

                        if ($alert->sms == '1' && isset($alert->mobile_number) && isset($alert->mobile_carrier)) {
                            try {
                                /*Queue messages for users' mobile phone number*/
                                Mail::queue('mail.plain.alarm', $data, function ($message) use ($alert, $alarm, $device) {
                                    $message
                                      ->to($alert->mobile_number .''. $alert->mobile_carrier, $alert->first_name . ' ' . $alert->last_name)
                                      ->from('noreply@eawelectro.com', 'EMC 20/20')
                                      ->subject('');
                                });
                            } catch (Exception $e) {
                                SystemLog::error($alarm->system_id, "Failed to send text alert", 16);
                                SystemLog::error($alarm->system_id, $e, 16);
                            }
                        }
                    }
                }
            }
        }

        $alarm = Alarms::find($alarm_id);
        $alarm->notifications_sent = 1;
        $alarm->save();

        return ;
    }

  
  /**
   * Send email notifications about a new alarm
   * @param  integer $alarm_id Primary key for the new alarm
   * @return
   */
    public static function logNotification($log_id)
    {
        $log = SystemLog::where('notifications_sent', 0)
        ->join('log_type', 'system_log.log_type', '=', 'log_type.id')
        ->select('system_log.*', 'log_type.*')
        ->find($log_id);
        if (isset($log)) {
            $log_type = LogType::find($log->log_type);
        } else {
            return;
        }

        if ($log->system_id > 0) {
            $system = System::find($log->system_id);
            if (isset($system)) {
                $building = Building::find($system->building_id);
            } else {
                return;
            }
        }
        if (isset($system) && isset($building)) {
             $alerts = Alert::where('system_id', $log->system_id)
            ->where('alarm_code', $log->log_type)
            ->where('notification_type', 'log')
            ->join('users', 'users.id', '=', 'alerts.user_id')
            ->select('users.*', 'alerts.*', 'users.id AS user_id', 'alerts.email as email_alert', 'users.email AS user_email')
            ->get();


            foreach ($alerts as $alert) {
                if ($log->system_id > 0 && !AccessController::checkRole($alert->user_id, $building->id)) {
                    SystemLog::error($log->system_id, "User $alert->user_id does not have access to building $building->id", 14);
                } else {
                    if (isset($system)) {
                        $data = [
                        'log'      => $log->toArray(),
                        'log_type' => $log_type->toArray(),
                        'alert'      => $alert->toArray(),
                        'building'   => $building->toArray(),
                        'system'     => $system->toArray(),
                        ];
                    } else {
                        $data = [
                        'log'      => $log->toArray(),
                        'log_type' => $log_type->toArray(),
                        'alert'      => $alert->toArray(),
                        ];
                    }
                    if ($alert->email_alert == '1') {
                        try {
                            Mail::queue(['html'=>'mail.html.log', 'plain'=>'mail.plain.log'], $data, function ($message) use ($alert, $log) {
                                $message
                                  ->to($alert->user_email, $alert->first_name . ' ' . $alert->last_name)
                                  ->from('noreply@eawelectro.com', 'EMC 20/20')
                                  ->subject('EMC 20/20 - ' . $log->report . ' - ' . $log->application_name);
                            });
                        } catch (Exception $e) {
                            SystemLog::error($alarm->system_id, "Failed to send email log", 16);
                            SystemLog::error($alarm->system_id, $e, 16);
                        }
                    }

                    if ($alert->sms == '1' && isset($alert->mobile_number) && isset($alert->mobile_carrier)) {
                        try {
                            /*Queue messages for users' mobile phone number*/
                            Mail::queue('mail.plain.log', $data, function ($message) use ($alert, $log) {
                                $message
                                  ->to($alert->mobile_number .''. $alert->mobile_carrier, $alert->first_name . ' ' . $alert->last_name)
                                  ->from('noreply@eawelectro.com', 'EMC 20/20')
                                  ->subject('');
                            });
                        } catch (Exception $e) {
                            SystemLog::error($alarm->system_id, "Failed to send text log", 16);
                            SystemLog::error($alarm->system_id, $e, 16);
                        }
                    }
                }
            }
        }

        $log = SystemLog::find($log_id);
        $log->notifications_sent = 1;
        $log->save();

        return;
    }

  /**
   * Unsubscribe from an email type based on it's unsubscribe key
   * @param  string $unsubscribe_key The alert record's unsubscribe key
   * @return
   */
    public function unsubscribe($unsubscribe_key)
    {
        $alert = Alert::where('unsubscribe_key', $unsubscribe_key)->first();

        if ($alert->delete()) {
            Session::flash('success', 'You have been unsubscribed from this email type. You can update your email settings from your profile.');
        } else {
            Session::flash('error', 'There was a problem unsubscribing you from these emails. You can update your email settings from your profile.');
        }

        return Redirect::route('account.index');
    }

  /**
   * Unsubscribe from an email type based on it's unsubscribe key
   * @param  string $unsubscribe_key The alert record's unsubscribe key
   * @return a confirm page where user can check which alert he/she is unsubscibing from.
   */
    public function update_subscription($unsubscribe_key)
    {
        $alert = Alert::where('unsubscribe_key', $unsubscribe_key)->first();
        /* Bad key */
        if (! count((array)$alert)) {
            Session::flash('error', 'This link has already expired. You can update your email settings from your profile.');
            return Redirect::route('account.index');
        }

        $alarm_code = AlarmCodes::find($alert->alarm_code);
        if (isset($alarm_code)) {
            $data = [
            'alert'      => $alert->toArray(),
            'alarm_code' => $alarm_code->toArray(),
            'unsubscribe_key' => $unsubscribe_key,
            ];
        }
        return View::make('mail.unsubscribe', $data);
    }

    public static function TestalarmNotification($alarm_id)
    {
        $alarm = Alarms::where('notifications_sent', 0)
        ->join('alarm_codes', 'alarms.alarm_code_id', '=', 'alarm_codes.id')
        ->select('alarms.*', 'alarm_codes.*', 'alarms.description AS alarm_description', 'alarm_codes.description AS code_description')
        ->find($alarm_id);
        $alarm_code = AlarmCodes::find($alarm->alarm_code_id);

        $device_types = DeviceType::all();

        $functions = array();

        foreach ($device_types as $device_type) {
            $functions[$device_type->command] = str_replace(' ', '', $device_type->function);/*remove spaces from function field*/
        }

        $system = System::find($alarm->system_id);
        if (isset($system)) {
            $building = Building::find($system->building_id);
            $device = Device::where('system_id', $alarm->system_id)->where('id', $alarm->device_id)->first();
            if (isset($device)) {
                $zone = Zone::where('system_id', $alarm->system_id)->where('zone', $device->zone)->first();

                $alerts = Alert::where('system_id', $system->id)
                ->where('alarm_code', $alarm->alarm_code_id)
                ->where('notification_type', 'alarm');

                $alerts = $alerts->join('users', 'users.id', '=', 'alerts.user_id')
                ->select('users.*', 'alerts.*', 'users.id AS user_id')
                ->where('deleted_at', null)
                ->get();

                foreach ($alerts as $alert) {
                    if (!AccessController::checkRole($alert->user_id, $building->id)) {
                        SystemLog::error($alarm->system_id, "User $alert->user_id does not have access to building $building->id", 14);
                    } else {
                        $data = [
                        'alarm'      => $alarm->toArray(),
                        'alarm_code' => $alarm_code->toArray(),
                        'alert'      => $alert->toArray(),
                        'building'   => $building->toArray(),
                        'system'     => $system->toArray(),
                        'device'     => $device->toArray(),
                        'zone'       => $zone->zonename,
                        'first_name' => $alert->first_name,
                        ];
                        // Mail::queue(['html'=>'mail.html.alarm', 'plain'=>'mail.plain.alarm'], $data, function($message) use ($alert, $alarm, $device)
                        // {
                        //     $message
                        //       ->to('amitozdeol@gmail.com', $alert->first_name . ' ' . $alert->last_name)
                        //       ->from('noreply@eawelectro.com', 'EMC 20/20')
                        //       ->subject('EMC 20/20 - ' . $alarm->description . ' - ' . $device->name);
                        // });
                    }
                }
            }
        }

        $alarm = Alarms::find($alarm_id);
        //$alarm->notifications_sent = 1;
        $alarm->save();

        return View::make('mail.html.alarm', $data);
    }
}
