Hey {!!$user['first_name']!!}

We got a request to reset the password for your account.

You can set a new one at {!!URL::route('reset.form', $user['password_reset_token'])!!}

If you didn't mean to change your password you can just ignore this message.
