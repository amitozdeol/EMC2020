<?php

namespace App\Console\Commands;

use App\Http\Controllers\SystemController;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;



class LocalParamUpdate extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'deploy:local-param-update';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Send the appropriate local parameter file to system';

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
        $systems = [];

        if ($SysID !== null) {
            array_push($systems, (integer)$SysID);
        } else {
            throw new Exception("No system was specified to be updated.", 1);
            return;
        }

        foreach ($systems as $system) {
            $this->info('Deploying new local parameters file to system #' . $system);
            try {
                SystemController::DeployLocalParam($system);
            } catch (Exception $e) {
                $this->error('Error deploying new local parameters file to system #' . $system . "\n" . $e->getMessage());
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
        ['SysID', InputArgument::OPTIONAL, 'System ID to have its local parameters file updated.'],
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
