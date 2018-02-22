  @extends('layouts.mail')

  @section('content')

    <div class="content">
    <table>
      <tr>
        <td>

          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="10%"></td>
              <td width="80%">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                  <td>
                  <div class="content">
                    <h1 align="left">Hey {{$user['first_name']}}</h1>
                    <p>We got a request to reset the password for your account. You can set a new one by following the link below.</p>
                  </div>
                  </td>
                  </tr>
                </table>
              </td>
              <td width="10%"></td>
            </tr>

            <tr>
              <td width="10%"></td>
              <td width="80%">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#123E5D " style="padding: 12px 18px 12px 18px; -webkit-border-radius:3px; border-radius:5px" align="center">
                      <a href="{{URL::route('reset.form', $user['password_reset_token'])}}" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; display: inline-block;">
                        Reset Your Password
                      </a>
                    </td>
                  </tr>
                </table>
              </td>
              <td width="10%"></td>
            </tr>

            <tr>
              <td width="10%"></td>
              <td width="80%">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                  <td>
                  <div class="content">
                    <p><br><br></p>
                    <p>If you didn't mean to change your password you can just ignore this message. It's no big deal</p>
                  </div>
                  </td>
                  </tr>
                </table>
              </td>
              <td width="10%"></td>
            </tr>
          </table>

        </td>
      </tr>
    <tr class="spacer">
      <td></td>
      <td></td>
      <td></td>
    </tr>
    </table>
    </div>

  @stop
