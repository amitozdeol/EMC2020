  {!! Form::open(array('url' => 'login')) !!}
    <div class="col-xs-12 col-md-10 col-md-offset-1 transparent_blue_white border_blue_white" id="Login_block">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        <div class="col-xs-12 shortest-height"></div>
        <div class="col-xs-12 form-group">
          <p>
            {!! Form::label('email', 'Email Address')!!}
            {!! Form::email('email', Input::old('email'), ['class' => 'form-control login_email_tour', 'autofocus']) !!}
          </p>
        </div>
        <div class="col-xs-12 form-group" style="padding: 15px;">
          <p>
            {!! Form::label('password', 'Password')!!}
            {!! Form::password('password', ['class' => 'form-control login_email_tour']) !!}
          </p>
        </div>
        <div class="col-xs-12" style="padding: 20px 0px;">
          <!-- for android phones -->
          <?php if(strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/1.0")) > 0 || strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) > 0): ?>
            <div class="col-xs-6 checkbox" style="text-align: left; display:none;">
                <label class="btn btn-primary btn-lg" style="padding: 12px 30px; margin-top: 20px;">
                  {!! Form::checkbox('remember_me', 1, true) !!}
                </label>
            </div>
            <div class="login_email_tour">
                {!! Form::submit('Sign In', ['class' => ' btn btn-primary btn-sm', 'style' => 'text-shadow: 2px 3px 6px #222244;']) !!}
            </div>

          <!-- for browsers -->
          <?php else: ?>
            <div class="col-xs-6" style="padding: 0px;">
              <div class="btn btn-primary btn-sm" style="padding: 10px 20px;" onclick="remember_click(this)">
                {!! Form::checkbox('remember_me', 1, null, ['style' => 'margin: 0px;', 'id' => 'remember_me']) !!}
                <label class="remember" for="remember_me">Remember&nbsp;Me</label>
              </div>
            </div>
            <div class="login_email_tour col-xs-6" style="padding: 0px;">
                {!! Form::submit('Sign In', ['class' => 'btn btn-primary btn-sm', 'style' => 'text-shadow: 2px 3px 6px #222244; padding: 10px 20px;']) !!}
            </div>
          <?php endif; ?>

        </div>
        <div class="col-xs-12 path-link" style="padding: 60px 0px 10px 0px;">
          <a style="color: white;" href="{!!URL::to('forgot-password')!!}" >
            Forgot your password?
          </a>
        </div>
      </div>
    </div>
  {!! Form::close() !!}
  <style>
    .remember{
      font-weight: 400;
      margin: 0px;
    }
    input[type=checkbox] {
    display: none;
    }

    input:checked + .remember {
    color: #4CAF50;
    }

    input:checked + .remember:before {
    content: "\2713";
    }

    @media (min-width: 1080px){
      .btn-sm{
        font-size: 16px;
      }
    }
  </style>

<script>
  // add list of help menu buttons for each page
  $(document).ready(function(){
    $('.pmd-floating-action').prepend('\
      <a href="javascript:void(0);" class="login_email_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
        Login\
      </a>'
    );
  });
  function remember_click(button) {
    if (document.getElementById('remember_me').checked) {
        button.style.backgroundColor = "#719ec5";
    }else{
      button.style.backgroundColor = "#428bca";
    }
  }
</script>
