<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InovonicsMappingUpdate extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
    protected $name = 'deploy:ino-mapping';

  /**
   * The console command description.
   *
   * @var string
   */
    protected $description = 'Update Inovonics device mapping file on remote systems.';

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
                SystemController::DeployInovonicsMapping($system);
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
        ['SysID', InputArgument::OPTIONAL, 'System ID to have its inovonics mapping updated.'],
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
        ['force', 'f', InputOption::VALUE_NONE, 'Send new inovnoics mapping files to all systems.', null],
        ];
    }
}
