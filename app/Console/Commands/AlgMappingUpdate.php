<?php

namespace App\Console\Commands;

use App\Http\Controllers\AlgorithmController;
use App\System;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;



class AlgMappingUpdate extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'deploy:alg-mapping';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Update Algorithm mapping file on remote systems.';

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
        $force = $this->option('force');
        $systems = [];

        if ($force) {
            $everything = System::all();
            foreach ($everything as $thing) {
                array_push($systems, $thing->id);
            }
        } elseif ($SysID !== null) {
            array_push($systems, (integer)$SysID);
        } else {
            throw new Exception("No system was specified to be updated.", 1);
            return;
        }

        foreach ($systems as $system) {
            $this->info('Deploying new device mapping to system #' . $system);
            try {
                AlgorithmController::DeployMapping($system);
            } catch (Exception $e) {
                $this->error('Error deploying new device mapping to system #' . $system . "\n" . $e->getMessage());
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
        ['SysID', InputArgument::OPTIONAL, 'System ID to have its algorithm mapping updated.'],
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
        ['force', 'f', InputOption::VALUE_NONE, 'Send new algorithm mapping files to all systems.', null],
        ];
    }
}
