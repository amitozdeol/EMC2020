<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AlarmNotification extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'email:alarm-notification';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Send notification emails for an alarm.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
    public function __construct()
    {
        parent::__construct();
    }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
    public function fire()
    {

        //to test email alerts for a single alarm_id
        //EmailController::TestalarmNotification(15519);
        $report = new BuildingController();
        $report->page(29, 537, 447);
        // echo "Get up to 500 alarm table entries where notification has not been sent\n";
        // $alarms = Alarms::where('notifications_sent', 0)
        //   ->where('system_id','>',0)
        //   ->where('device_id','!=',0)
        //   ->limit(500)
        //   ->get();
        // echo "Increment through received entries\n";
        // foreach ($alarms as $alarm) {
        //   EmailController::alarmNotification($alarm->id);
        // }
        //
        // echo "Get up to 500 system_log table entries where notification has not been sent\n";
        // $logs = SystemLog::where('notifications_sent', 0)
        //   ->limit(500)
        //   ->get();
        // echo "Increment through received entries\n";
        // foreach ($logs as $log) {
        //   EmailController::logNotification($log->recnum);
        // }
    }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
    protected function getArguments()
    {
        return [
        ];
    }

  /**
   * Get the console command options.
   *
   * @return array
   */
    protected function getOptions()
    {
        return [
        ];
    }
}
