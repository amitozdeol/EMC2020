<?php

/**/
class RemoteTask {

  /**
   * Look up a system and use it's IP for remote task execution
   * @param  int    $system_id The id of the remote system
   * @return null
   */
  public static function connect($system_id)
  {
    $system = System::find( $system_id );

    Config::set('remote.connections.remote.host', $system->ethernet_ip.':'.$system->ethernet_port);
    Config::set('remote.connections.remote.username', 'h8j9l8knlk');
    Config::set('remote.connections.remote.password', 'h8j9l8knlk');

    return;
  }


  /**
   * Connect to a remote machine and execute generic bash commands
   * @param  int    $system_id The id of the remote system
   * @param  array  $commands  Commands to be executed
   * @return null
   */
  public static function execute( $system_id, $commands )
  {
    self::connect($system_id);

    SSH::into('remote')->run($commands, function($line){
      /* Consider wither logging the responses stored in $line.PHP_EOL or
       *removing this callback
       */
      var_dump($line);
    });

    return;
  }


  /**
   * Move a local fine onto a remote machine
   * @param  int    $system_id  The id of the remote system
   * @param  string $localFile  The file to be sent
   * @param  string $remotePath The path to the files destination
   * @return bool
   */
  public static function sendFile($system_id, $localFile, $remotePath)
  {
    self::connect($system_id);

    return SSH::into('remote')->put($localFile, $remotePath);
  }


  /**
   * Write text to a file on a remote machine
   * @param  int    $system_id  The id of the remote system
   * @param  string $remotePath Path to the remote file
   * @param  string $string     The text to be written
   * @return bool
   */
  public static function sendText($system_id, $remotePath, $string)
  {
    self::connect($system_id);

    return SSH::into('remote')->putString($remotePath, $string);
  }


  /**
   * Generate files that will be moved onto a remote system
   * @param  int    $system_id
   * @param  string $destinationPath     Path to the directory on a remote system where the file should be sent
   * @param  string $destinationFileName Name of the file when it gets to the remote system
   * @param  string $fileContents        Contents of the file
   * @param boolean $forceSend  Allow an override of the downlink check
   * @return
   */
  public static function deployFile($system_id, $destinationPath, $destinationFileName, $fileContents, $forceSend = false)
  {
    $reduced_permissions = false;
    // Where the hell is this thing going anyway?
    $system_id = intval($system_id);
    $system = System::find( (int)$system_id );

    // Format the destination directory for use in the file name
    $destinationPath = preg_replace('/^\//', '', $destinationPath);
    $destinationPath = preg_replace('/\//', '.', $destinationPath);

    $boom = explode('.', $destinationFileName);
    if(count($boom) > 1){
      /*  Check whether the deploy-file is of the type *.emc  */
      if(0 == strcmp("emc", $boom[1])){
        $reduced_permissions = true;
      }
    }

    // Asseble the full file name
    $destinationFileName = $destinationPath . '.' . $destinationFileName;

    // Check that a local directory exist for temporary storage
    $localPath  = storage_path('downlink/storage_' . $system_id . '/');
    if(!File::exists($localPath)) {
      File::makeDirectory($localPath,0777);
    }

    // Build the path to a temporary file that will be sent down
    $localPath .= $destinationFileName;

    // Make a temporary file that can be moved down to the system
    $localFile = fopen($localPath, 'w');
    fwrite($localFile, $fileContents);

    /*Reduce file permissions, where indicated*/
    if($reduced_permissions == true){
      if(!chmod($localPath,0777)){
        SystemLog::info($system_id, 'Failed to reduce file permissions for '.$localPath, 14);
      }
    }
    /**
     * Send the file down to the remote system.
     * Everything goes to `/var/2020_command` and `sys_main` will parse its
     * destination out of its file name nad then move it.
     *
     * If the downlink is enabled for this system then the file is left until a
     * system calls the server to retreive it
     */
    // if(! $system->downlink || $forceSend === true) {
    //   try{
    //     self::sendFile($system_id, $localPath, '/var/2020_downlink/'.$destinationFileName);
    //     File::delete($localPath);
    //   }catch(Exception $exception){
    //     SystemLog::error($system_id, substr($exception,0,254), 13);
    //   }
    // }

    return;
  }


  /*
  * @fcn      ConfirmSent
  *
  * @desc     Ensure the pending file is not waiting to be sent from the systems dowlink directory
  *
  * @param    system_id: The relevent system
  *           file_type: Dictates which type of file is to be confirmed
  *
  * @return   TRUE/FALSE
  */
  public static function ConfirmSent($system_id,$file_tye){
    $localPath  = storage_path('downlink/storage_' . $system_id . '/');
    $localPath .= $file_tye;
    if(File::exists($localPath)){
      return FALSE;
    }else{
      return TRUE;
    }

  }
}
