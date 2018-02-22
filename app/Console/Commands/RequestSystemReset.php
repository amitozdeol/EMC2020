<?php

namespace App\Console\Commands;

use App\Http\Controllers\SystemController;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RequestSystemReset extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'reset-system';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Send a file, containing EMC API code to trigger a watchdog initiated reset of the local system.';

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
        $SysID = $this->argument('SysID');

        if ($SysID !== null) {
            echo "Reqsting Reset of System ";
            echo $SysID;
            echo "\n";
            array_push($systems, (integer)$SysID);
        } else {
            throw new Exception("No system was specified to be reset.", 1);
            return;
        }

        foreach ($systems as $system) {
            $this->info('Deploying reset request to system #' . $system);
            try {
                SystemController::ReqSysReset($SysID);
            } catch (Exception $e) {
                $this->error('Error attempting to request reset of system ' . $system . "\n" . $e->getMessage());
            }
        }
    }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
    protected function getArguments()
    {
        return [
        ['SysID', InputArgument::OPTIONAL, 'System ID on which to attempt reset.'],
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
