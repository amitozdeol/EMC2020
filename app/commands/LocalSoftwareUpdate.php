<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LocalSoftwareUpdate extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'update-system-software';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send a file, containing EMC API code to trigger update of local system\'s software .';

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

    if($SysID !== NULL) {
      echo "Reqsting Update of System ";
      echo $SysID;
      echo "\n";
      array_push($systems, (integer)$SysID);

    }else{

      throw new Exception("No system was specified to be updated.", 1);
      return;

    }

    foreach($systems as $system) {
      $this->info('Deploying new update request to system #' . $system);
      try{
        SystemController::ReqSysSoftUpdate($SysID);
      }catch(Exception $e) {
        $this->error('Error attempting to request update of software on system ' . $system . "\n" . $e->getMessage());
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
      array('SysID', InputArgument::OPTIONAL, 'System ID on which to attempt software update.'),
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
