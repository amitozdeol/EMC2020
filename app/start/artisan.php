<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

/*
|--------------------------------------------------------------------------
| Register Config File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of config files to remote heat computers.
|
*/
Artisan::add(new ConfigUpdate);


/*
|--------------------------------------------------------------------------
| Register Device Mapping File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of device mapping files to remote heat computers.
|
*/
Artisan::add(new ZigMappingUpdate);


/*
|--------------------------------------------------------------------------
| Register Expansion Board Mapping File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of expansion board mapping files to remote heat computers.
|
*/
Artisan::add(new ExpansionMappingUpdate);


/*
|--------------------------------------------------------------------------
| Register Device Mapping File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of device mapping files to remote heat computers.
|
*/
Artisan::add(new AlgMappingUpdate);

/*
|--------------------------------------------------------------------------
| Register Device Mapping File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of device mapping files to remote heat computers.
|
*/
Artisan::add(new SetpointMappingUpdate);


/*
|--------------------------------------------------------------------------
| Register Bacnet Mapping File Deployment Commands
|--------------------------------------------------------------------------
|
| Allows command line deployment of bacnet mapping files to remote heat computers.
|
*/
Artisan::add(new BacnetMappingUpdate);


/*
|--------------------------------------------------------------------------
| Register Alarm Notifications Command
|--------------------------------------------------------------------------
|
| Allows command line trigger of sending email notificaions of an alarm.
|
*/
Artisan::add(new AlarmNotification);


/*
|--------------------------------------------------------------------------
| Register Inovonics Mapping File Deployment Command
|--------------------------------------------------------------------------
|
| Allows command line deployment of inovonics mapping files to remote heat computers.
|
*/
Artisan::add(new InovonicsMappingUpdate);

/*
|--------------------------------------------------------------------------
| Register System MAC Update Deployment Command
|--------------------------------------------------------------------------
|
| Allows command line deployment of new config file to downlink folder of incorrectly labeled system
| based on the mac-address and system id received by the command
|
*/
Artisan::add(new SystemMacUpdate);

/*
|--------------------------------------------------------------------------
| Register Local Parameters Update Deployment Command
|--------------------------------------------------------------------------
|
| Allows command line deployment of new local parameters file to downlink folder of system.
|
*/
Artisan::add(new LocalParamUpdate);