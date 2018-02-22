
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/


/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::post('reset', ['as'=>'reset.generate', 'uses'=>'HomeController@generateToken']);
Route::get('/reset/{token}', ['as'=>'reset.form',     'uses'=>'HomeController@resetForm']);
Route::post('/reset/{token}', ['as'=>'reset.update',   'uses'=>'HomeController@updatePassword']);

Route::get('login', 'HomeController@get_login');
Route::post('login', 'HomeController@login');
Route::get('logout', 'HomeController@logout');
Route::get('forgot-password', 'HomeController@recoverPassword');
Route::get('products', 'HomeController@products');
Route::get('aboutus', 'HomeController@about_us');
Route::get('services', 'HomeController@services');
Route::get('emc2020', 'HomeController@emc2020');
Route::get('emczigbeewireless', 'HomeController@emc_zigbee_wireless');
Route::get('support', 'HomeController@support');
Route::get('aboutus/privacy', 'HomeController@privacy');


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| These routes are available to all users, even if they're not logged in.
|
*/
Route::get('about', function () {
    return view('about', ['buildings'=>Building::all()]);
});
Route::get('contact', function () {
    return view('contact', ['buildings'=>Building::all()]);
});
Route::get('YourIpPlusaBunchOfHTML.php', function () {
    return $_SERVER['REMOTE_ADDR'];
});

/*
|--------------------------------------------------------------------------
| Auth filtered routes
|--------------------------------------------------------------------------
| Group all other routes to run them throu the 'auth' filter. Users must be
| logged in before using these routes
| The 'auth' filter is defined in `/app/filters.php`
|
*/
Route::group(['middleware' => 'auth'], function () {

    Route::get('dashboard', 'BuildingController@dashboard');
    Route::get('help', ['as' => 'help', 'uses' => 'HelpController@index']);
  /*
  |--------------------------------------------------------------------------
  | Building Management filtered routes
  |--------------------------------------------------------------------------
  | These routes must have a building id (the primary key of the `buildings`
  | table) passed to them in the url. The routes will be runt hrough the
  | `building_manager` filter located in `/app/filters.php`. That filter will
  | assess wether or not the user may access that building's information.
  |
  */
    Route::group(['before' => 'building_manager'], function ($route) {

        Route::get('settings/{customer_id}', ['as'=>'settings.customerbuildings','uses'=>'SettingsController@customerbuildings']);
        Route::post('settings/{customer_id}', ['as'=>'settings.updatecustomerbuildings','uses'=>'SettingsController@updatecustomerbuildings']);

        Route::get('building/{id}', ['as'=>'building', 'uses'=>'BuildingController@building'])
        ->where('id', '[0-9]+');
        Route::get('building/{id}/system/{sid}', ['as'=>'building.system', 'uses'=>'BuildingController@page'])
        ->where('id', '[0-9]+')->where('sid', '[0-9]+');
        Route::get('building/{id}/system/{sid}/page/{pid}', ['as'=>'building.dashboard', 'uses'=>'BuildingController@page']);
        Route::post('building/{id}/system/{sid}/page/{pid}', ['as'=>'building.dashboard', 'uses'=>'BuildingController@page']);
        Route::post('building/{bbid}/system/{sid}/page/{id}', ['as'=>'building.dashboard', 'uses'=>'BuildingController@page']);
        Route::post('building/{id}/system/{sid}/ajax', ['as'=>'building.Chartajax',  'uses'=>'BuildingController@Chartajax']);
        Route::post('building/{id}/system/{sid}/weather', ['as'=>'building.WeatherAjax',  'uses'=>'BuildingController@WeatherAjax']);
        Route::post('building/{id}/system/{sid}/water', ['as'=>'building.WaterAjax',  'uses'=>'BuildingController@WaterAjax']);
        Route::post('building/{id}/system/{sid}/systemlog', ['as'=>'building.SystemLogAjax',  'uses'=>'BuildingController@SystemLogAjax']);

        /*
        |------------------------------------------------------------------------
        | Routes to specific building status pages
        |------------------------------------------------------------------------
        */
        Route::get('building/{id}/system/{sid}/detail/{gid}', 'BuildingController@detail');
        Route::get('building/{id}/system/{sid}/devicestatus', ['as'=>'devicestatus', 'uses'=>'BuildingController@devicestatus']);
        // temporary route to status page for debug remove if placed in web mapping table
        Route::get('building/{id}/system/{sid}/zonestatus', ['as'=>'zonestatus', 'uses'=>'BuildingController@zonestatus']);
        Route::post('building/{id}/system/{sid}/zonestatus', 'BuildingController@zonestatus');
        Route::get('building/{id}/system/{sid}/alarmstatus', ['as'=>'alarms', 'uses'=>'BuildingController@alarmstatus']);
        Route::post('building/{id}/system/{sid}/alarmstatus', 'BuildingController@alarmstatus');
        Route::get('building/{id}/system/{sid}/eventstatus', ['as'=>'eventstatus', 'uses'=>'BuildingController@eventstatus']);
        Route::post('building/{id}/system/{sid}/eventstatus', 'BuildingController@eventstatus');

        /*
        |------------------------------------------------------------------------
        | Routes to systems configs for specific buildings
        |------------------------------------------------------------------------
        */
        Route::group(['before' => 'auth_admin'], function ($route) {
            Route::get('building/{id}/newsystem', 'SystemController@newsystem');
            Route::post('building/{id}/newsystem', 'SystemController@addsystem');
            Route::get('building/{id}/editsystem/{sid}', ['as'=>'system.editSystem', 'uses'=>'SystemController@editsystem']);
            Route::post('building/{id}/editsystem/{sid}', 'SystemController@updatesystem');
            Route::get('building/{id}/editsystem/{sid}/webmapping/dashboardmaps', ['as'=>'dashboardmaps.edit', 'uses'=>'BuildingController@editDashboardMaps']);
            Route::post('building/{id}/editsystem/{sid}/webmapping/dashboardmaps', ['as'=>'dashboardmaps.update', 'uses'=>'BuildingController@updateDashboardMaps']);
            Route::resource(
                'building/{id}/editsystem/{sid}/algorithm',
                'AlgorithmController',
                ['names' => [
                'index'   => 'algorithm.index',
                'create'  => 'algorithm.create',
                'store'   => 'algorithm.store',
                'show'    => 'algorithm.show',
                'edit'    => 'algorithm.edit',
                'update'  => 'algorithm.update',
                'destroy' => 'algorithm.destroy',
                ]]
            );
            Route::resource(
                'building/{id}/editsystem/{sid}/webmapping',
                'WebMappingController',
                ['names' => [
                'index'   => 'webmapping.index',
                'store'   => 'webmapping.store',
                'show'    => 'webmapping.show',
                'edit'    => 'webmapping.edit',
                'update'  => 'webmapping.update',
                ],
                'except' => ['destroy','create','show']]
            );
            Route::get('building/{id}/editsystem/{sid}/webmapping/{did}/delete', 'WebMappingController@destroy');
        });
        Route::resource(
            'building/{id}/editsystem/{sid}/zone',
            'ZoneController',
            ['names' => [
            'index'   => 'zone.index',
            'create'  => 'zone.create',
            'store'   => 'zone.store',
            'show'    => 'zone.show',
            'edit'    => 'zone.edit',
            'update'  => 'zone.update',
            'destroy' => 'zone.destroy',
            ]]
        );
        Route::resource(
            'building/{id}/editsystem/{sid}/chart',
            'ChartController',
            ['names' => [
            'edit'    => 'charts.edit',
            'update'  => 'charts.update',
            'destroy' => 'charts.destroy',
            ],
            'except' => ['index','create','show','store']]
        );
        Route::group(['before' => 'auth_setpointmapping'], function ($route) {
            Route::resource(
                'building/{id}/editsystem/{sid}/setpointmapping',
                'SetpointMappingController',
                ['names' => [
                'index'   => 'setpointmapping.index',
                'create'  => 'setpointmapping.create',
                'store'   => 'setpointmapping.store',
                'show'    => 'setpointmapping.show',
                'edit'    => 'setpointmapping.edit',
                'update'  => 'setpointmapping.update',
                'destroy' => 'setpointmapping.destroy',
                ]]
            );
            Route::post('building/{id}/system/{sid}/setpointmapping', ['as' => 'setpointmapping.remap','uses' => 'SetpointMappingController@remap']);
            Route::post('building/{id}/system/{sid}/mapdevicesetpoint', ['as' => 'setpointmapping.mapDevice','uses' => 'SetpointMappingController@mapDevice']);

            /*
            |------------------------------------------------------------------------
            | Route to Reports page
            |------------------------------------------------------------------------
            */
            Route::get('building/{id}/system/{sid}/reports', ['as'=>'reports.index',  'uses'=>'ReportsController@index']);
            Route::post('building/{id}/system/{sid}/reports', ['as'=>'reports.update', 'uses'=>'ReportsController@update']);

            Route::post('building/{id}/system/{sid}/reports/ajax', ['as'=>'reports.ajax',  'uses'=>'ReportsController@ajax']);

            Route::get('building/{id}/system/{sid}/export', ['as'=>'reports.export',   'uses'=>'ReportsController@export']);
            Route::post('building/{id}/system/{sid}/export/filter', ['as'=>'reports.filter', 'uses'=>'ReportsController@filter']);
            Route::post('building/{id}/system/{sid}/export', ['as'=>'reports.download', 'uses'=>'ReportsController@download']);
        });
    });
  /*
  |------------------------------------------------------------------------
  | Customer Account Management
  |------------------------------------------------------------------------
  */
    Route::group(['before'=>'auth_customers'], function () {
        Route::resource('customer', 'CustomerController', ['only' => ['index']]);
    });

  /*
  |------------------------------------------------------------------------
  | User Account Management
  |------------------------------------------------------------------------
  */
    Route::group(['before'=>'auth_user'], function () {
        Route::resource('user', 'UserController', ['except' => ['show']]);
        Route::post('user/password/{id}', 'UserController@password');
    });

    Route::get('account', ['as'=>'account.index',  'uses'=>'UserController@account']);
    Route::post('account', ['as'=>'account.update', 'uses'=>'UserController@accountUpdate']);
    Route::get('account/password', ['as'=>'account.password.index',  'uses'=>'UserController@accountPassword']);
    Route::post('account/password', ['as'=>'account.password.update', 'uses'=>'UserController@accountPasswordUpdate']);

  /*
  |------------------------------------------------------------------------
  | Buildiing Management
  |------------------------------------------------------------------------
  */
    Route::group(['before'=>'auth_access'], function () {
        Route::resource('access', 'AccessController', ['only'=>['index', 'create', 'store', 'destroy']]);
        Route::get('access/group/store', ['as'=>'access.createGroup',         'uses'=>'AccessController@createGroup']);
        Route::post('access/group/store', ['as'=>'access.storeGroup',          'uses'=>'AccessController@storeGroup']);
        Route::post('access/group/store', ['as'=>'access.storeGroup',          'uses'=>'AccessController@storeGroup']);
        Route::post('access/group/update', ['as'=>'access.updateGroup',         'uses'=>'AccessController@updateGroup']);
        Route::get('access/group/delete/{id}', ['as'=>'access.deleteGroup',         'uses'=>'AccessController@deleteGroup']);
        Route::post('access/group/building/add', ['as'=>'access.addGroupBuilding',    'uses'=>'AccessController@addGroupBuilding']);
        Route::delete('acecss/group/building/delete', ['as'=>'access.deleteGroupBuilding', 'uses'=>'AccessController@deleteGroupBuilding']);
        Route::post('access/group/manager/add', ['as'=>'access.addGroupManager',     'uses'=>'AccessController@addGroupManager']);
        Route::delete('acecss/group/manager/delete', ['as'=>'access.deleteGroupManager',  'uses'=>'AccessController@deleteGroupManager']);
    });

  /*
  |------------------------------------------------------------------------
  | Buildiing Management
  |------------------------------------------------------------------------
  */
    Route::group(['before'=>'auth_sysadmin'], function () {
        Route::resource('admin/building', 'AdminBuildingController', ['except' => ['show']]);
    });

  /* Admin Interface for Staff */
    Route::group(['before'=>'auth_staff'], function () {
        Route::resource('admin', 'AdminController', ['only'=>'index']);
        Route::resource('admin/customer', 'AdminCustomerController', ['except'=>['show']]);
        Route::resource('admin/user', 'AdminUserController', ['except'=>['show']]);
        Route::resource('admin/producttype', 'ProductTypeController', ['except'=>['show', 'create', 'edit']]);
        Route::resource('admin/devicetype', 'DeviceTypesController', ['except'=>['show', 'create','edit']]);
        Route::get('admin/user/{id}/edit/password', ['as'=>'admin.user.edit_password',   'uses'=>'AdminUserController@edit_password']);
        Route::post('admin/user/{id}/edit/password', ['as'=>'admin.user.update_password', 'uses'=>'AdminUserController@update_password']);
    });
});


Route::group([], function () {
  /* Touchscreen interface */
    Route::get('EMC/{bid}/system/{sid}', ['as'=>'touchscreen.system',       'uses'=>'BuildingController@page']);
    Route::get('EMC/{bid}/system/{sid}/page/{gid}', ['as'=>'touchscreen.dashboard',    'uses'=>'BuildingController@page']);
    Route::post('EMC/{bid}/system/{sid}/page/{gid}', ['as'=>'touchscreen.dashboard',    'uses'=>'BuildingController@page']);
    Route::post('EMC/{bid}/system/{sid}/systemShutdown', ['uses' => 'BuildingController@systemShutdown']);
});


Route::get('mail/update_subscription/{unsubscribe_key}', ['as'=>'update_subscription', 'uses'=>'EmailController@update_subscription']);
Route::get('mail/unsubscribe/{unsubscribe_key}', ['as'=>'unsubscribe', 'uses'=>'EmailController@unsubscribe']);

//test the mail layouts on web
Route::get('/foo', ['as' => 'foo', function () {
    return App::make('EmailController')->TestalarmNotification(15519);
}]);
//
// Route::get('/bar', array('as' => 'bar', function(){
//   return App::make('EmailController')->TestlogNotification(39080804);
// }));
