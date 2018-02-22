<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>EMC 20/20</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<style>
  /* -------------------------------------
      GLOBAL
  ------------------------------------- */
  * {
    margin:0;
    padding:0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    font-size: 100%;
    line-height: 1.6;
  }

  img {
    max-width: 100%;
  }

  body {
    -webkit-font-smoothing:antialiased;
    -webkit-text-size-adjust:none;
    width: 100%!important;
    height: 100%;
  }


  /* -------------------------------------
      ELEMENTS
  ------------------------------------- */
  a {
    color: #348eda;
  }
  hr {
    border: none;
    border-top: 1px solid #ddd;
  }

  .btn-primary, .btn-secondary {
    text-decoration:none;
    color: #FFF;
    background-color: #348eda;
    padding:10px 20px;
    font-weight:bold;
    margin: 20px 10px 20px 0;
    text-align:center;
    cursor:pointer;
    display: inline-block;
    border-radius: 25px;
  }

  .btn-secondary{
    background: #aaa;
  }

  .last {
    margin-bottom: 0;
  }

  .first{
    margin-top: 0;
  }
  @if(isset($alarm_code['severity']))
    .page-nav {
      padding: 8px;
      color: #cccece;
      @if($alarm_code['severity'] > 1)
        background-color: #6b1616;
      @else
        background-color: #2B3C51;
      @endif
      color: white;
      text-align: center;
      font-size: 32px;
      box-shadow: black .1em .1em .15em;
      font-weight: 100;
      min-height: 75px;
      margin-bottom: 20px;
    }


    .page-nav:hover, .page-nav:focus, .page-nav.focus {
      color: white;
      @if($alarm_code['severity'] > 1)
        background-color: #993838;
      @else
        background-color: #556273;
      @endif
      text-decoration: none;
      box-shadow: black 0.3em 0.3em 0.4em;
    }
  @endif
  /* -------------------------------------
      BODY
  ------------------------------------- */
  table.body-wrap {
    max-width: 500px;
    margin-right: auto;
    margin-left: auto;
    align: center;
    padding: 20px;
  }

  /* -------------------------------------
      FOOTER
  ------------------------------------- */
  table.footer-wrap {
    width: 100%;
    clear:both!important;
  }

  .footer-wrap .container p {
    font-size:12px;
    color:#666;

  }

  table.footer-wrap a{
    color: #999;
  }


  /* -------------------------------------
      TYPOGRAPHY
  ------------------------------------- */

  h1,h2,h3,h4{
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
    margin: 10px 0;
    line-height: 1.2;
    font-weight:200;
  }

  h1 {
    font-size: 36px;
  }
  h2 {
    font-size: 28px;
  }
  h3 {
    font-size: 22px;
  }
  h4 {
    font-size: 16px;
  }

  p, ul {
    margin-bottom: 10px;
    font-weight: normal;
    font-size:14px;
  }

  ul li {
    margin-left:5px;
    list-style-position: inside;
  }

  /* ---------------------------------------------------
      RESPONSIVENESS
      Nuke it from orbit. It's the only way to be sure.
  ------------------------------------------------------ */

  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
  .container {
    display:block!important;
    max-width:600px!important;
    margin:0 auto!important; /* makes it centered */
    clear:both!important;
  }
  .content-wrapper {
    border-radius: 6px;
  }

  /* This should also be a block element, so that it will fill 100% of the .container */
  .content {
    padding: 8px;
    max-width:600px;
    margin:0 auto;
    display:block;
  }

  .logo-bar img {
    max-width: 150px;
  }

  /* Let's make sure tables in the content area are 100% wide */
  .content table {
    width: 100%;
  }

  /* ---------------------------------------------------
      CARD LAYOUT
      If you delete this, half-life 3 will never launch
  ------------------------------------------------------ */

.card {
  -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
  -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
  box-shadow:         3px 3px 5px 6px #ccc;  /* Opera 10.5, IE 9, Firefox 4+, Chrome 6+, iOS 5 */

}
/* ---------------------------------------------------
    Special classes to support outlook 2007.
------------------------------------------------------ */
.overlay {
  border: 1px solid #f0f0f0;
  color: #000000;
}
.shadow {
  border-color: #CCCCCC;
  border-radius: 6px;
}
.shadow .overlay {
  bottom: 6px;
  position: relative;
  right: 6px;
}
</style>
</head>

<body bgcolor="#ffffff">

<!-- body -->
<table class="body-wrap" align="center">
  <tr class="shadow container" bgcolor="#ffffff">
    <td class="card container content-wrapper overlay " >
      <!-- logo -->
        <table class=" content logo-bar">
          <tr >
            <td  style="display:block">
              <a href="http://emc.eawelectro.com">
                {{ HTML::image("images/logo-bw-light-back.png","Logo", array('title'=>"Logo",'height'=>"80",'width'=>"150")) }}
              </a>
              </a>
            </td>
          </tr>
        </table>
      <!-- /logo -->

      <!-- content -->

        <table class="content" bgcolor="#DFECF4">
          <tr>
            <td align="center">
              @yield('content')
            </td>
          </tr>
        </table>

      <!-- /content -->
    </td>
  </tr>
</table>
<!-- /body -->

<!-- footer -->
<table class="footer-wrap">
  <tr>
    <td></td>
    <td class="container">

      <!-- content -->
      <div class="content">
        <table>
          <tr>
            <td align="center">
              @if(isset($alert['notification_type']))
                @if($alert['notification_type'] == 'alarm')
                  <p>Click {{ HTML::linkRoute('account.index', 'here') }} to update your email preferences.</p>
                  <p>
                    <a href={{URL::route('update_subscription', $alert['unsubscribe_key'])}} >
                      Unsubscribe from <u>{{$alarm_code['description']}}@if(isset($alert['function'])) - {{ $alert['function']}}@endif </u> related mails.
                    </a>
                  </p>
                @endif
              @endif
            </td>
          </tr>
        </table>
      </div>
      <!-- /content -->

    </td>
    <td></td>
  </tr>
</table>
<!-- /footer -->

</body>
</html>
