<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SystemMacUpdate extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'deploy:system-mac-update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send the appropriate config file to mis-matched system and trigger full system mapping deploy';

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
    $SysMac = $this->argument('SysMac');
    $systems = [];

    echo "Received MAC of:";
    echo $SysMac;
    echo "\n";
    

    if($SysID !== NULL) {

      array_push($systems, (integer)$SysID);

    }else{

      throw new Exception("No system was specified to be updated.", 1);
      return;

    }

    foreach($systems as $system) {
      $this->info('Deploying new configuration to system #' . $system);
      try{
        SystemController::DeployNewConfig($system,$SysMac);
      }catch(Exception $e) {
        $this->error('Error deploying new configuration to system #' . $system . "\n" . $e->getMessage());
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
      array('SysID', InputArgument::OPTIONAL, 'System ID to have its config updated.'),
      array('SysMac', InputArgument::OPTIONAL, 'Update Config file based on ID/MAC correlation'),
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
    );
  }

}
