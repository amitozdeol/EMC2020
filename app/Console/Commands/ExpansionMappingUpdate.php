<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExpansionMappingUpdate extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'deploy:exp-mapping';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Update expansion board mapping file on remote systems.';

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
            $this->info('Deploying new expansion board mapping to system #' . $system);
            try {
                SystemController::DeployExpansionMapping($system);
            } catch (Exception $e) {
                $this->error('Error deploying new expansion board mapping to system #' . $system . "\n" . $e->getMessage());
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
        return array(
        array('SysID', InputArgument::OPTIONAL, 'System ID to have its expansion board mapping updated.'),
        );
    }

  /**
   * Get the console command options.
   *
   * @return array
   */
    protected function getOptions()
    {
        return array(
        array('force', 'f', InputOption::VALUE_NONE, 'Send new expansion board mapping files to all systems.', null),
        );
    }
}
