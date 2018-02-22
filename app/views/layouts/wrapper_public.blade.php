<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{isset($title) ? $title : 'EMC20/20'}}</title>
    <meta name="description" content="The latest in building automation technology. Sense and control your building remotely." />
    <meta name="ROBOTS" content="INDEX, FOLLOW" />
    <meta name="GOOGLEBOT" content="INDEX, FOLLOW" />
    <meta name="google-site-verification" content="Jf4spJV5UiNXEtR3U_Lx00BV0EUqbZDxXGW3oHcTZqk" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" href="{{{ asset('images/apple-touch-icon.png') }}}">
    <?
      //Cache control -
      //Add last modified date of a file to the URL, as get parameter.
      $import_css = ['/css/bootstrap.css', '/font-awesome-4.7.0/css/font-awesome.min.css', '/css/publicpage.css'];    //add file name
      foreach ($import_css as $value) {
        $filename = public_path().$value;
        if (file_exists($filename)) {
            $appendDate = substr($value."?v=".filemtime($filename), 1);
            echo HTML::style($appendDate);
        }
      }
    ?>
    <?
      //Cache control - Scripts
      //Add last modified date of a file to the URL, as get parameter.
      $import_scripts = ['/js/vendor/jquery-3.2.1.js', '/js/vendor/bootstrap.js'];
      foreach ($import_scripts as $value) {
        $filename = public_path().$value;
        if (file_exists($filename)) {
            $appendDate = substr($value."?v=".filemtime($filename), 1);
            echo HTML::script($appendDate);
        }

      }
    ?>

</head>
<body>
  <!-- hide for ios app -->
  @if(!strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) > 0)
    <nav class="navbar navbar-default navbar-fixed-top" data-spy="affix" data-offset-top="205">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{URL::to('/')}}">{{HTML::image('images/home_page/logo-white-light-back.png', 'EMC20/20', array('class' => 'nav-bar-logo'))}}</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li class="pub_nav_item"><a href="#product-info" id="nav_product">Product</a></li>
                    <li class="pub_nav_item"><a href="#services" id="nav_services">Service</a></li>
                    <li class="pub_nav_item"><a href="#aboutus" id="nav_aboutus">About Us</a></li>
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Support <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li class="pub_nav_item"><a href="#contact">Contact</a></li>
                        <li class="pub_nav_item"><a href="{{URL::to('/support')}}">Help Center</a></li>
                      </ul>
                    </li>
                    <li><a href="{{URL::to('login')}}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
  @endif

  @yield('content')

  <!-- hide for ios app -->
  @if(!strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) > 0)
    <!--  ==================================START CONTACT SECTION================================================-->
    {{View::make('home.footer')}}
    <!--  ==================================END CONTACT SECTION================================================-->
  @endif

  <script>
    var navitems = ["#products", "#services", "#aboutus", "#contact", "#product-info", "#slides"];
      $(document).ready(function () {
        $('.pub_nav_item').on('click',function(e){
          $('#myNavbar:visible').collapse('hide');
        });
        $(document).on("scroll", onScroll);
        //smoothscroll
        $('.navbar-nav li a[href^="#"], .ca3-scroll-down-arrow, .btn').on('click', function (e) {
            e.preventDefault();
            if(contains(navitems, $(this).attr("href"))){
              $('html, body').animate({
                  scrollTop: $( $.attr(this, 'href') ).offset().top
              }, 1000);
            }
        });

      });


      function onScroll(event) {
        var scrollPos = $(document).scrollTop();
        $('.navbar-nav li a').each(function () {
            var currLink = $(this);
            if(contains(navitems, currLink.attr("href"))){
              var refElement = $(currLink.attr("href"));

              if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                  $('.navbar-nav li a').removeClass("active");
                  currLink.addClass("active");
              }
              else {
                  currLink.removeClass("active");
              }
            }
        });
      }

      function contains(arr, obj) {
        var proxy = new Set(arr);
        return proxy.has(obj);
      }
  </script>
</body>
</html>
