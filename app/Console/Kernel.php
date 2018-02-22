<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\AlgMappingUpdate',
        'App\Console\Commands\InovonicsMappingUpdate',
        'App\Console\Commands\SystemMacUpdate',
        'App\Console\Commands\RemoteTask',
        'App\Console\Commands\ExpansionMappingUpdate',
        'App\Console\Commands\furnaceErrorCatch',
        'App\Console\Commands\Inspire',
        'App\Console\Commands\ZigMappingUpdate',
        'App\Console\Commands\AlarmNotification',
        'App\Console\Commands\LocalParamUpdate',
        'App\Console\Commands\ConfigUpdate',
        'App\Console\Commands\SetpointMappingUpdate',
        'App\Console\Commands\ConvFunc',
        'App\Console\Commands\BacnetMappingUpdate',
        'App\Console\Commands\RequestSystemReset',
        'App\Console\Commands\LocalSoftwareUpdate',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
