<?php

class HomeController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  |  Route::get('/', 'HomeController@showWelcome');
  |
  */


  public function index()
  {
    if(Auth::guest()) {
      return View::make('home.index')->render();
    }else{
      if(Auth::user()->customer_id == 0) {
        return Redirect::route('admin.index');
      }else{
        return Redirect::to('dashboard');
      }
    }
  }


  public function generateToken()
  {

    $email = Input::get('recover-email');
    $user = User::where('email', $email)->first();

    /* Check that the user exists */
    if(! $user) {
      SystemLog::warning(0, 'Password reset requested for non-existant email address '.$email, 27);
      Session::flash('error', '<strong>Whoah</strong> That address does not exist in this system.');
    }else{
      $user->password_reset_token = md5( microtime(1) );
      $user->password_reset_expires = date('Y-m-d H:i:s', strtotime('+1 day'));
      if( $user->save() ) {
        SystemLog::info(0, 'Password reset requested for user #'.$user->id.'('.$email.')', 27);
      }

      /* MAIL!!!!!!!!!! */
      Mail::queue(['html'=>'mail.html.recover-password', 'plain'=>'mail.plain.recover-password'], ['user'=>$user->toArray()], function($message) use ($user)
      {
        $message
          ->to($user->email, $user->first_name . ' ' . $user->last_name)
          ->from('noreply@eawelectro.com', 'EMC 20/20')
          ->subject('EMC 20/20 - Password Reset');
      });

      SystemLog::info(0, 'Password reset email sent for user #'.$user->id.'('.$email.')', 15);
      Session::flash('success', "<strong>Ok.</strong> I'm gonna send you an email.");
    }
    return Redirect::to('/');
  }


  public function resetForm($token)
  {
    /* Make sure we can find the user whose password is being reset */
    $user = User::where('password_reset_token', $token)->where('password_reset_expires', '>', date('Y-m-d H:i:s'))->first();
    if(! $user) {
      Session::flash('error', 'It appears that link has already expired');
      return Redirect::to('/');
    }
    return View::make('home.reset-request');
  }


  public function updatePassword($token)
  {
    /* Make sure we can find the user whose password is being reset */
    if(! $user = User::where('password_reset_token', $token)->where('password_reset_expires', '>', date('Y-m-d H:i:s'))->first() ) {
      Session::flash('error', '<strong>Not So Fast!</strong> It looks like that link has already expired');
      return Redirect::to('/');
    }

    /* Validate the new password */
    $password    = Input::get('password');
    $re_password = Input::get('re-password');
    if(! AdminUserController::validate_password($password, $re_password) ) {
      return Redirect::to('/');
    }

    /* If nothing has gone wrong then lets finally make the update */
    $user->password = Hash::make($password);
    $user->password_reset_token = null;
    $user->password_reset_expires = null;
    if( $user->save() ) {
      Session::flash('success', '<strong>Awesome!</strong> Your password has been reset.');
    }

    return Redirect::to('/');
  }


  public function login()
  {
    $credentials = [
      'Email'    => Input::get('email'),
      'password' => Input::get('password')
    ];
    $remember = Input::get('remember_me')?true:false;

    if( Auth::attempt($credentials, $remember) ) {
      SystemLog::info(0, 'Successful login for user #'.Auth::user()->id.'('.Auth::user()->email.')', 28);
      $redirect = Session::get('redirect');   //redirect to the url the user requested
      Session::forget('redirect');            //if nothing requested, it will go to default URL
      return Redirect::to($redirect);
    }else{
      SystemLog::warning(0, 'Failed login for email '.Input::get('email'), 28);
      Session::flash('error', 'Incorrect username and/or password.');
      return Redirect::back()->withInput();
    }
  }


  public function logout()
  {
    Auth::logout();
    return Redirect::to('/login');
  }


  public static function recoverPassword()
  {
    return View::make('home.forgot-password')->render();
  }

  public static function products()
  {
    return View::make('home.products.index')->render();
  }

  public static function emc2020()
  {
    return View::make('home.products.emc2020')->render();
  }

  public static function emc_zigbee_wireless()
  {
    return View::make('home.products.emc_zigbee_wireless')->render();
  }

  public static function about_us()
  {
    return View::make('home.about-us.index')->render();
  }

  public static function services()
  {
    return View::make('home.services.index')->render();
  }

  public static function support()
  {
    return View::make('home.support.index')->render();
  }

  public static function privacy()
  {
    return View::make('home.privacypolicy.index')->render();
  }

  public static function get_login()
  {
    if (Auth::check()) {
      return Redirect::to('/');
    }else {
      return View::make('home.login');
    }
  }


  /***************Non-Routed Methods******************/
  public static function login_form()
  {
    return View::make('home.login-form');
  }

  public static function single_center_image_right()
  {
    return View::make('home.info.single-center-image-right');
  }

  public static function built_for_you()
  {
    return View::make('home.info.built-for-you');
  }

  public static function easy_integration()
  {
    return View::make('home.info.easy-integration');
  }

  public static function lead_ad()
  {
    return View::make('home.info.summer-2016');
  }

  public static function second_ad()
  {
    return View::make('home.info.services-ad');
  }

  public static function third_ad()
  {
    return View::make('home.info.zigbee-ad');
  }

  public static function emc_biography()
  {
    return View::make('home.info.emc_biography');
  }

  public static function emc_quick_stats()
  {
    return View::make('home.info.emc_quick_stats');
  }

  public static function system_services()
  {
    return View::make('home.info.system-services');
  }
}
