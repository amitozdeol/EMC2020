<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
  //
});


App::after(function ($request, $response) {
  //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
  /**
   * Send guests (i.e. logged out users) to the hopage for the login form.
   */
    if (Auth::guest()) {
        Session::put('redirect', URL::full());
        return redirect('/login');
    }
});

Route::filter('building_manager', function ($route) {

    if ($system = System::find($route->getParameter('sid'))) {
        $building_id = $system->building_id;
    } else {
        $building_id = $route->getParameter('id');
    }
    Auth::user()->auth_role = 0;

  /* Get building managers for this building */
    $building_managers = BuildingManager::where('user_id', Auth::user()->id)
    ->where('building_id', $building_id)
    ->get();
    foreach ($building_managers as $building_manager) {
        if ($building_manager->role > Auth::user()->auth_role) {
            Auth::user()->auth_role = $building_manager->role;
            Auth::user()->auth_source = 'manager';
        }
    }

  /* Get group managers for this building's group */
    $building_groups = DB::table('building_groups')
    ->join('building_group_managers', 'building_group_managers.building_group_id', '=', 'building_groups.id')
    ->join('building_group_members', 'building_group_members.building_group_id', '=', 'building_groups.id')
    ->where('building_group_managers.user_id', Auth::user()->id)
    ->where('building_group_members.building_id', $building_id)
    ->get();
    foreach ($building_groups as $building_group) {
        if ($building_group->role > Auth::user()->auth_role) {
            Auth::user()->auth_role = $building_group->role;
            Auth::user()->auth_source = 'group';
        }
    }

    if (Auth::user()->role == 5  && Auth::user()->role > Auth::user()->auth_role) {
        Auth::user()->auth_role = Auth::user()->role;
        Auth::user()->auth_source = 'owner';
    }

    if (Auth::user()->role >= 6 && Auth::user()->customer_id == 0) {
        Auth::user()->auth_role = Auth::user()->role;
        Auth::user()->auth_source = 'admin';
    }

    if (!Auth::user()->auth_role) {
        echo "That's not one of your buildings!!!!!";
        die();
    }
});


Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) {
        return redirect('/');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

Route::filter('auth_customers', function () {
    if ((int)Auth::user()->auth_role < 6) {
        Session::flash("error", "You don't have permission to access that page");
        return Redirect::back();
    }
});

Route::filter('auth_user', function () {
    if ((int)Auth::user()->role !== 5) {
      /* This has to look at role instead of auth_role since no building is selected */
        Session::flash("error", "You don't have permission to access that page");
        return Redirect::back();
    }
});

Route::filter('auth_staff', function () {
    if ((int)Auth::user()->customer_id !== 0) {
        Session::flash("error", "You don't have permission to access that page");
        return redirect('/');
    }
});

Route::filter('auth_sysadmin', function () {
  /* This has to look at role instead of auth_role since a building isn't always selected */
    if ((int)Auth::user()->role !== 8) {
        Session::flash("error", "You don't have permission to access that page");
        return Redirect::back();
    }
});

Route::filter('auth_access', function () {
  /* This has to look at role instead of auth_role since no building is selected */
    if (!in_array((int)Auth::user()->role, [5])) {
        Session::flash("error", "You don't have permission to access that page");
        return Redirect::back();
    }
});

Route::filter('auth_setpointmapping', function () {
    if (!in_array((int)Auth::user()->auth_role, [4, 5, 7, 8])) {
        Session::flash("error", "You don't have permission to access that page");
        return Redirect::back();
    }
});
