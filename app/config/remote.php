<?php

return array(

  /*
  |--------------------------------------------------------------------------
  | Default Remote Connection Name
  |--------------------------------------------------------------------------
  |
  | Here you may specify the default connection that will be used for SSH
  | operations. This name should correspond to a connection name below
  | in the server list. Each connection will be manually accessible.
  |
  */

  'default' => 'production',

  /*
  |--------------------------------------------------------------------------
  | Remote Server Connections
  |--------------------------------------------------------------------------
  |
  | These are the servers that will be accessible via the SSH task runner
  | facilities of Laravel. This feature radically simplifies executing
  | tasks on your servers, such as deploying out these applications.
  |
  */

  'connections' => array(

    'production' => array(
      'host'      => '',
      'username'  => '',
      'password'  => '',
      'key'       => '',
      'keyphrase' => '',
      'root'      => '/var/www',
    ),

  ),

  /*
  |--------------------------------------------------------------------------
  | Remote Server Groups
  |--------------------------------------------------------------------------
  |
  | Here you may list connections under a single group name, which allows
  | you to easily access all of the servers at once using a short name
  | that is extremely easy to remember, such as "web" or "database".
  |
  */

  'groups' => array(

    'web' => array('production')

  ),

  /*
  |--------------------------------------------------------------------------
  | Web Domain
  |--------------------------------------------------------------------------
  |
  | This is the domain/subdomain where the web application will be hosted.
  |
  */
  'web_domain' => $_ENV['WEB_DOMAIN'],

  /*
  |--------------------------------------------------------------------------
  | IP Lookup Address
  |--------------------------------------------------------------------------
  |
  | This is an address that will return, in plain text, the IP address that
  | requested the page. If a heat computer loses communication with it's
  | server it will use this address to check if its public IP address has
  | changed. If the address returned from this link doesn't match the one in
  | its config file then the heat computer will send an update message to
  | the server notifying it of the change.
  |
  */
  'ip_request_address' => 'https://' . $_ENV['WEB_DOMAIN'] . '/YourIpPlusaBunchOfHTML.php',

  /*
  |--------------------------------------------------------------------------
  | Server Receiving Directory
  |--------------------------------------------------------------------------
  |
  | This is the directory on a server that heat computers will try to place
  | their data files in when they sync those files up.
  |
  */
  'server_receiving_dir' => '/var/2020_data_serv/',

  /*
  |--------------------------------------------------------------------------
  | Server User Account
  |--------------------------------------------------------------------------
  |
  | The user account on a server that a heat computer will use when trying
  | to sync files up.
  |
  */
  'server_username' => $_ENV['SERVER_USERNAME'],

  /*
  |--------------------------------------------------------------------------
  | Server Domain
  |--------------------------------------------------------------------------
  |
  | This is the domain name of the server that heat computers will upload
  | their data files to
  |
  */
  'server_domain_name' => $_ENV['PARSER_DOMAIN'],

);
