<!-- Don't show navbar in EMC touchscreen. $routeprefix is touchscreen when open reports page in touchscreen -->
@if(!Request::is('EMC/*') && $routePrefix != 'touchscreen')
  <style>
    .nav-bar-logo {
      padding-right: 40px;
      max-height: 60px;
      max-width: 250x;
      margin-bottom: 5px;
      margin-top: 5px;
    }
    .navbar-mobile li{
      /*padding-top: 5px;
      padding-bottom: 5px;*/
      text-align: center;
      /*min-height: 100px;*/
    }
    .navbar-mobile ul {
      padding-left: 0px;
    }
    @media (max-width: 1216px) {
      .navbar-mobile li {
        font-size: 15px;
      }
      .nav-bar-logo{
        padding-right: 0px;
        margin-bottom: 2px;
        margin-top: 2px;
        margin-left: 2vw;
      }
    }
    @media (max-width: 1165px) {
      .navbar-nav .open .dropdown-menu {
        position: static;
        float: none;
        width: auto;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
      }
      .navbar-header {
          float: none;
      }

      .navbar-toggle {
          display: block;
      }
      .navbar-collapse {
          border-top: 1px solid transparent;
          box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
      }
      .navbar .navbar-collapse.in {
        max-height:400px!important;
        overflow-y:scroll!important;
        overflow-x:hidden!important;
      }
      .navbar-fixed-top {
          top: 0;
          border-width: 0 0 1px;
      }
      .navbar-collapse.collapse {
          display: none!important;
          overflow-y: auto!important;
      }
      .navbar-nav {
          float: none!important;
          margin-top: 7.5px;
      }
      .navbar-nav>li {
          float: none;
      }
      .navbar-nav>li>a {
          padding-top: 10px;
          padding-bottom: 10px;
      }
      .collapse.in{
          display:block !important;
      }

      .nav-bar-logo {
        max-height: 55px;
        max-width: 250px;
        padding-right: 0px;
      }
      .navbar-toggle {
        position: relative;
        float: right;
        padding: 7px 7px;
        margin-top: 5px;
        margin-bottom: 0px;
        /*background-color: transparent;*/
        background-image: none;
        border: 6px solid #000000;
      }
      .navbar-toggle .icon-bar {
        display: block;
        width: 30px;
        height: 4px;
        border-radius: 3px;
      }
      .navbar-toggle .icon-bar + .icon-bar {
        margin-top: 4px;
      }
      .navbar-mobile li {
        font-size: 28px;
        text-align: center;
        padding: 10px;
        border: 2px groove #DDDDDD;
        /*min-height: 100px;*/
      }
    }
    @media (max-width: 600px) {
      .navbar-mobile li {
        font-size: 20px;
        text-align: center;
        padding: 5px;
        border: 2px groove #DDDDDD;
        min-height: 0px;
      }
      .page-title{
        padding-top: 0px;
        font-size: 1.1em;
      }
    }
    /*==============dropdown arrow change==============*/
    .caretup {
      transform: rotate(180deg);
    }
    /*==============dropdown arrow change==============*/

    /*====================Navbar hamburger menu button animation==============*/
    .navbar-toggle .icon-bar:nth-of-type(2) {
        top: 1px;
    }

    .navbar-toggle .icon-bar:nth-of-type(3) {
        top: 2px;
    }

    .navbar-toggle .icon-bar {
        position: relative;
        transition: all 500ms ease-in-out;
    }

    .navbar-toggle.active .icon-bar:nth-of-type(1) {
        top: 8px;
        transform: rotate(45deg);
    }

    .navbar-toggle.active .icon-bar:nth-of-type(2) {
        background-color: transparent;
    }

    .navbar-toggle.active .icon-bar:nth-of-type(3) {
        top: -8px;
        transform: rotate(-45deg);
    }
    /*====================Navbar hamburger menu button animation==============*/

    /* ================== navbar title ================== */
    .navbar-returns.affix {
      position: fixed;
        top: 60px;
        width: 100%;
    }
    @media screen and (max-width: 400px){
      .navbar-btn{
        font-size: 0.9em !important;
      }
      .page-title{
        padding-top: 0px;
        font-size: 1em;
      }
    }
  </style>

    <!--navbar - index-->
    <style>
      body{ 
        margin-top: 63px;
      }
    </style>
    <nav class="navbar navbar-default navbar-fixed-top" id = "android_nav_hide" style="box-shadow: black 0 0.1em 0.15em" >
      <div class="container-fluid" >
        <div class="navbar-header" >
          <button id = "hamburger" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="{!!URL::to('/')!!}" >
            {!!HTML::image('images/logo-bw-light-back.png', 'EMC20/20', array('class' => 'nav-bar-logo'))!!}
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-mobile">
            <!-- class="col-sm-12 col-md-2 col-offset-md-6"-->
            @if(Auth::check())
            <li class="path-link" ><a class = "android_nav" href="{!!URL::to('/')!!}">Home</a></li>
              @if(Auth::user()->customer_id == 0)
                @include('layouts.navbar.staff_navbar')
              @else
                @include('layouts.navbar.customer_navbar')
              @endif
              <li class="path-link"><a class="StopRefresh help-btn" href="" data-toggle="modal" data-target="#help-modal" data-scrollto="{!!$help_id!!}-help">Help</a></li>
              @include('layouts.navbar.user_dropdown')
            @else
              @include('layouts.navbar.public_navbar')
            @endif
          </ul>
        </div>
      </div>
    </nav>

    <!--title navbar-->
    @if(Auth::check() && !empty($thisSystem->id))
      <style>
        body{ 
          margin-top: 120px;
        }
      </style>
      <nav id="navbar_buildingtitle" class="navbar navbar-default navbar-returns affix" style="box-shadow: black 0.1em 0.1em 0.2em; z-index:200; border-radius: 0px;" data-spy="affix" data-offset-top="0">
          <div class="container col-xs-12" style="align-items: center; height: 50px; line-height: 50px;">
            <div class="page-title col-xs-12">
                {!!$thisBldg->name!!} - {!!$thisSystem->name!!}
            </div>
      </nav>
    @endif

  <script type="text/javascript">
    var AndroidOrIos = checkUserAgent("EMC2020/ios") || checkUserAgent("EMC2020/1.0/Android");
    var navbar = document.getElementById("android_nav_hide");
    var navbar_title = document.getElementById("navbar_buildingtitle");
	  if(AndroidOrIos){
      navbar.style.display = "none";  //Hide top navbar 
      // Dashboard page with all the systems
      @if (empty($thisSystem->id))
        document.getElementsByTagName("BODY")[0].style.marginTop = "0px";  //
      @else
        document.getElementsByTagName("BODY")[0].style.marginTop = "60px";  //
        navbar_title.style.top = "0px";
      @endif
      
    }else{
      navbar_title.style.position = "absolute";
      //dropdown change icon and navbar hamburger menu change to x
      $(".dropdown").on("show.bs.dropdown hide.bs.dropdown", function(){
        $(this).find(".caret").toggleClass("caretup");
      });
      $(".navbar-toggle").on("click", function () {
          $(this).toggleClass("active");
      });
    }

    //send data to android device
    window.addEventListener('load', loadURLData, false);
    var sendDataToIOS;
    function loadURLData() {
      var array = [];
      var array_dropdown = [];
      var array_dropdown_building = [];
      var helpID = "";
      var x = document.getElementsByClassName("android_nav");       //Navbar items
      var y = document.getElementsByClassName("android_nav_dropdown");  // navbar items under user name
      var z = document.getElementsByClassName("android_nav_building");  // navbar items under building dropdown
      //check if there's any user logged in and store the username- send it to android/ios app
      //username
      array[0] = '@if(Auth::check()) @if(Auth::user()->first_name !== "" && Auth::user()->last_name !== ""){!!Auth::user()->first_name!!} {!!Auth::user()->last_name!!}@else{!!Auth::user()->email!!}@endif @endif'.trim();
      for(var j=1, i=0; i<x.length; i++, j++)
      {
        array[j] = x[i].href;
        j++;
        array[j] = x[i].innerHTML;
      }
      for (var i = 0, j=0; i < y.length; i++, j++) {
        array_dropdown[j] = y[i].href;
        j++;
        array_dropdown[j] = y[i].innerHTML;
      }
      for (var i = 0, j=0; i < z.length; i++, j++) {
        array_dropdown_building[j] = z[i].href;
        j++;
        array_dropdown_building[j] = z[i].innerText;
      }
      //work as a help anchor in android app
      helpID = '@if(Auth::check()){!!$help_id!!}@endif';
      sendDataToIOS = {
        "ios_nav": array,
        "ios_dropdown": array_dropdown,
        "ios_dropdown_building": array_dropdown_building,
        "helpID": helpID,
        "Authuser": "",
        "help-user-title": [],
        "help-user-content": [],
        "help-admin-title": [],
        "help-admin-content": []
      };
      try{
        //check if loaded on android phone
        Android.sendData(array, array_dropdown, array_dropdown_building, helpID);
      }catch(err){
        //check if user is logged in and there's help section in navbar
        if (typeof helpLoad === "function")
        {
          console.log(err.message);
        }else{
          try{
            webkit.messageHandlers.iosApp.postMessage(sendDataToIOS);
          }catch(err){
            console.log(err.message);
          }
        }
      }
    }
  </script>
@endif