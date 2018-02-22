@extends('layouts.wrapper_public')

@section('content')

<!--  ==================================START HEADER SECTION================================================-->
<header style="background-image:url('./images/home_page/building_bg.jpg'); padding-top: 50px;">
    <div class="header-content">
        <div class="header-content-inner">
            <h1>Introducing EMC<sup>20/20</sup></h1>
            <p>
              The Clear Choice in Building Automation
              <br>
            </p>
        </div>
    </div>
    <a class="ca3-scroll-down-link ca3-scroll-down-arrow" data-ca3_iconfont="ETmodules" data-ca3_icon="" href="#products"></a>
</header>
<!--  ==================================END HEADER SECTION================================================-->
<div style="overflow-x: hidden;"> <!-- for chrome in android devices -->

  <!--  ==================================START PRODUCT SECTION================================================-->
  <section class="products-section bg-color" id="products">
    <div class="row">
      <div class="col-xs-12" style="overflow: hidden;">
        <div class="col-md-6 col-sm-6 col-xs-12 module">
            <h1>Vision</h1>
            <br>
            <h4>-so you can-</h4>
            <br>
            <h2>Perceive Challenges</h2>
            <br>
            <h2>Prevent Oversights</h2>
            <br>
            <h2>Prepare for the Future</h2>
            <br>
            <a class="btn btn-default-new" href="#product-info" title="Learn More">
              EMC<sup>20/20</sup>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 module">
            {{HTML::image('images/home_page/product.jpeg', '', array('class' => 'img-responsive img-thumbnail'))}}
        </div>
      </div>

      <hr style="width: 90%; border-top: 1px solid #3A8C9E;">

      <div id="product-info" class="col-xs-12" style="overflow: hidden; text-align: left">
        <div class="col-sm-12 col-xs-offset-0 col-sm-offset-3 col-sm-6" style="margin-top: 50px;">
            <h2 class="left_underline title"><b><i>We Bring You the Best in Building Automation</i></b></h2>
        </div>
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>COMPATIBLE</strong></h4>
                <p class="p1">The EMC<sup>20/20</sup> has been design to integrate into your building's existing control and sensing networks, saving you time and money.</p>
              </div>
              <hr>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>EXPANDABLE</strong></h4>
                <p class="p1">By maintaining a flexible development model, the EMC<sup>20/20</sup> is able to accomodate your building's needs. The EMC<sup>20/20</sup> grows with you, as technology advances.</p>
              </div>
              <hr>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>ADAPTABLE</strong></h4>
                <p class="p1">Thanks to the EMC<sup>20/20</sup>'s open software architecture, systems seemlessly receive the latest updates and improvements, keeping you at the leading edge of <span title="BAS">Building Automation Systems</span> technology.</p>
              </div>
              <hr>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>ACCESSIBLE</strong></h4>
                <p class="p1">The EMC<sup>20/20</sup>'s large, built-in touch screen allows you to monitor your system on site, while maintaining consistency with the mobile interface you use every day.</p>
              </div>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--  ==================================START   TABBED SLIDER SECTION================================================-->
    <section id="tabbed_slider">
      <div class="container">
        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="200000">

          <ul class="iconTabs nav row  aos-init aos-animate">
            <li data-target="#myCarousel" data-slide-to="0" class="col-xs-12 col-sm-6 col-md-3 nav1 active">
              <a href="#slides" class="btn btn-default-new" style="overflow: hidden">
                <i class="fa fa-random icon"></i>
                <div class="nav1-text">
                  <h6>RESPONSIVE</h6>
                  <p>Working for You</p>
                </div>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="1" class="col-xs-12 col-sm-6 col-md-3 nav1">
              <a href="#slides" class="btn btn-default-new" style="overflow: hidden">
                <i class="fa fa-code-fork icon"></i>
                <div class="nav1-text">
                  <h6>INTUITIVE</h6>
                  <p>An Effortless Interface</p>
                </div>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="2" class="col-xs-12 col-sm-6 col-md-3 nav1">
              <a href="#slides" class="btn btn-default-new" style="overflow: hidden">
                <i class="fa fa-sitemap icon"></i>
                <div class="nav1-text">
                  <h6>EXPANSIVE</h6>
                  <p>Seamless Integration</p>
                </div>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="3" class="col-xs-12 col-sm-6 col-md-3 nav1">
              <a href="#slides" class="btn btn-default-new" style="overflow: hidden">
                <i class="fa fa-wifi icon"></i>
                <div class="nav1-text">
                  <h6>TRANSFORMATIVE</h6>
                  <p>Moving You Forward</p>
                </div>
              </a>
            </li>
          </ul>
          <!-- Wrapper for slides -->
          <div id="slides" class="carousel-inner" style="min-height: 400px;">

            <div class="item active set_slider_padding">
              <div class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  {{ HTML::image('images/home_page/resp.jpeg', 'image title', array('class' => 'responsive')) }}
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <h2 class="title">Your Building's Solution</h2>
                    <hr>
                  </div>
                  <div class="col-xs-12" style="text-align:left;">
                    <p class="check_desc">Your building is unique. With the EMC<sup>20/20</sup>, you take control of your new or existing systems and devices.</p>
                    <strong style="color: #23344b;">Interoperable:</strong>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Industry Protocol Compatibility</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Wireless and Wired Sensor Communication</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Utilities Data Collection</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Existing Hardware Integration</span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <a class="left carousel-control" href="#myCarousel" data-slide-to="3"></a>
              <a class="right carousel-control" href="#myCarousel" data-slide-to="1"></a>
            </div>
            <!-- End Item -->
            
            <div class="item set_slider_padding ">
              <div class="row" style="background: white;">
                <div class="col-sm-6 tab_slider_img aos-init aos-animate" data-aos="fade-in">
                  {{ HTML::image('images/home_page/interface.jpeg', 'image title', array('class' => 'responsive')) }}
                </div>
                <div class="col-sm-6 aos-init aos-animate" data-aos="fade-in">
                  <div class="sectionTitle">
                    <h2 class="title">Your Buildings<br>From Anywhere</h2>
                  </div>
                  <div class="col-xs-12" style="text-align: left;">
                    <p class="check_desc">We provide an intuitive, mobile-friendly user interface, with access to your buildings' most relevant metrics, all from wherever you are.</p>
                    <strong style="color: #23344b;">On <i>Your</i> Device:</strong>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">At-a-Glance Status Dashboards</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Long Term Graphical Visualizations</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Downloadable Spreadsheet Reports</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Remote Override Controls</span></li>
                    </ul>
                  </div>
                  <p>&nbsp;</p>
                </div>
              </div>
              <a class="left carousel-control" href="#myCarousel" data-slide-to="0"></a>
              <a class="right carousel-control" href="#myCarousel" data-slide-to="2"></a>
            </div>
            <!-- End Item -->

            <div class="item set_slider_padding">
              <div class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  {{ HTML::image('images/home_page/expansive.jpeg', 'image title', array('class' => 'responsive')) }}
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <h2 class="title">Modern Flexibility</h2>
                    <hr>
                  </div>
                  <div class="col-xs-12" style="text-align:left;">
                    <p class="check_desc">Leverage the EMC<sup>20/20</sup>'s many built in control and sensing devices to shed light on all your building's systems and metrics.</p>
                    <strong style="color: #23344b;">The Latest Hardware:</strong>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Expandable Configuration</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Integrated Interface</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Inherent Flexiblity</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Built for You</span></li>
                    </ul>
                  </div>
                  <p>&nbsp;</p>
                </div>
              </div>
              <a class="left carousel-control" href="#myCarousel" data-slide-to="1"></a>
              <a class="right carousel-control" href="#myCarousel" data-slide-to="3"></a>
            </div>
            <!-- End Item -->

            <div class="item set_slider_padding">
              <div class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  {{ HTML::image('images/home_page/business-office.jpg', 'image title', array('class' => 'responsive')) }}
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <h2 class="title">Revolutionary Technology</h2>
                    <hr>
                  </div>
                  <div class="col-xs-12"  style="text-align:left;">
                    <p class="check_desc">A state-of-the-art Building Automation System (BAS), designed for <i>your</i> buildings. <i>This</i> is what you've been waiting for.</p>
                    <strong style="color: #23344b;">Our Technology Provides:</strong>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Round-the-Clock Software-Based Monitoring</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Global and Granular Settings</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Concise and Objective Reporting</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Real-Time Remote Control</span></li>
                    </ul>
                  </div>
                </div>
                <p>&nbsp;</p>
              </div>
              <a class="left carousel-control" href="#myCarousel" data-slide-to="2"></a>
              <a class="right carousel-control" href="#myCarousel" data-slide-to="0"></a>
            </div>
            <!-- End Item -->
            
          </div>
          <!-- End Carousel Inner -->
        </div>
      </div>
    </section>
    <!--======================================================================================================= -->
    <!--  =================================END   TABED SLIDER SECTION================================================-->
  </section>
  <!--  ==================================END PRODUCTS SECTION================================================-->

  <!--  ==================================START SERVICES SECTION================================================-->
  <section class="service-section" id="services">
      <div class="container">
          <h3>service</h3>
          <div class="row spacer service-section-inner1" style="padding:20pt;">
              <div class="col-sm-4 col-xs-12 module">
                  <div class="service-items">
                      <div class="icon">
                          <i class="fa fa-building-o" aria-hidden="true"></i>
                      </div>
                      <h4>On Site</h4>
                      <p>
                        We work with our trusted distributors to give you a simple and effortless installation process.
                      </p>
                  </div>
              </div>

              <div class="col-sm-4 col-xs-12">
                  <div class="service-items">
                      <div class="icon">
                        <i class="fa fa-commenting-o" aria-hidden="true"></i>
                      </div>
                      <h4>Notifications</h4>
                      <p>
                        Customize your alerts and alarms to give you the notices you need to act.
                      </p>
                  </div>
              </div>

              <div class="col-sm-4 col-xs-12">
                  <div class="service-items">
                      <div class="icon">
                        <i class="fa fa-mobile" aria-hidden="true"></i>
                      </div>
                      <h4>With You</h4>
                      <p>
                        Our software collects data from your building's sensors, 24/7, giving you the knowledge you need.
                      </p>
                  </div>
              </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-sm-8 mobile_advert">
              {{HTML::image('images/logo-color.png', '', array('class' => 'img-responsive img-thumbnail' ,'style' => 'background: white; max-height: 100px;'))}}
              <h2>Anywhere You Go</h2>
              <label style="padding: 10px 0;">
                Control your <span title="Building Automation System">BAS</span> with the EMC<sup>20/20</sup> app on your iOS and Android devices.
              </label>

              <div class="row">
                <!-- android logo -->
                <div class="col-xs-12 col-sm-6">
                  <a href='https://play.google.com/store/apps/details?id=com.eaw&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png' style="max-width: 200px;"/></a>
                </div>
                <!-- ios logo -->
                <div class="col-xs-12 col-sm-6">
                  <a href="https://itunes.apple.com/us/app/emc-20-20/id1248858068?ls=1&mt=8">
                    {{HTML::image('images/home_page/iOS_badge.svg', '', array('class' => 'img-responsive img-thumbnail' ,'style' => 'background: white; height: 70px; padding-top: 10px;'))}}
                  </a>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4" >
              {{HTML::image('images/home_page/iphone6s.png', '', array('class' => 'img-responsive img-thumbnail iphoneimg' ,'style' => 'background: white;'))}}
            </div>
          </div>
      </div>
  </section>
  <!--  ==================================END SERVICES SECTION================================================-->

  <!--  ==================================START ABOUT_US SECTION================================================-->
  <section class="aboutus-section" id="aboutus">
      <div class="col-xs-12 aboutus-section-inner" style="background: black; opacity: 0.8; z-index: 10; padding: 20pt">
        <h3>About Us</h2>
        <div style="text-align: left;">
          <div class="col-xs-12 " style="padding-bottom: 10px;">
            <div class="col-sm-6 aboutus-section-text-inner">
              <p>
                We strive to give you the tools you need to monitor and control your building, both for your building's tenants <i>and</i> your bottom-line. That's why we make our systems flexible, to fit your building's demands and to give you the ability to adjust your <span title="Building Automation System">BAS</span> to your current and future needs.
              </p>
              <hr>
              <p>
                We are dedicated to providing you with the highest quality <span title="BAS">Building Automation Systems</span>.
                <br>
                Our New York design and manufacturing center allows us to ensure our customers receive only the highest quality devices.
              </p>
              <hr>
            </div>
            <div class="col-sm-6 aboutus-section-text-inner">
              <p>
                Thanks to our many years of experience in technology design, fabrication and testing, we are able to offer robust products which have been designed with maximal cost-savings in mind, while providing the kind of service and quality you expect.
              </p>
              <hr>
              <p>
                With the ever increasing speed of technological change, we believe it is essential to continuously integrate the latest developments in building automation and sensing technology into our systems. 
                <br>
                Thanks to our lean and agile development model, we are positioned to rapidly integrate such cuttting edge improvements for the benefit of our systems' building owners, managers and occupants.</p>
              <hr>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!--  ==================================END ABOUT_US SECTION================================================-->
</div>

<script>
  $(document).ready(function () {
      $(document).on("scroll", onScroll);

      $('.products_item').on( "click", function(e){
        CarouselTabColor(this);
      });

      var $carousel = $('#myCarousel');
      $carousel.bind('slide.bs.carousel', function (e) {
        var active = $(e.target).find('.carousel-inner > .item.active');
        var next = $(e.relatedTarget);
        var to = next.index();
        $('.products_item').each(function( index ) {
          if (index == to) {
            CarouselTabColor(this);
          }
        });
      });
  });

    function CarouselTabColor(current){
      var currentItem = current;
      $(currentItem).addClass("active");
      $('.products_item').each(function( index ) {
        if (currentItem != this) {
          $(this).removeClass("active");
        }
      });
    }
</script>
@stop
