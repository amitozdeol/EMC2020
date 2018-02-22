@extends('layouts.wrapper_public')

@section('content')

<!--  ==================================START HEADER SECTION================================================-->
<header style="background-image:url('./images/home_page/brownstones.jpg'); padding-top: 50px;">
    <div class="header-content">
        <div class="header-content-inner">
            <h1>EMC<sup>20/20</sup></h1>
            <p>Bringing you the latest in building automation.</p>
        </div>
    </div>
    <a class="ca3-scroll-down-link ca3-scroll-down-arrow" data-ca3_iconfont="ETmodules" data-ca3_icon="" href="#products"></a>
</header>
<!--  ==================================END HEADER SECTION================================================-->
<div style="overflow-x: hidden;"> <!-- for chrome in android devices -->

  <!--  ==================================START PRODUCT SECTION================================================-->
  <section class="products-section bg-color" id="products">
    <div class="row">
      <h3 class="underline">Product</h3>
      <div class="col-xs-12" style="overflow: hidden;">
        <div class="col-md-6 col-sm-6 col-xs-12 module">
            <p>Our products are designed, from the ground up, to get you the most from today's latest generation of smart devices.</p>
            <p>No one knows your building as well as you. Let us customize your system to meet your needs and give you the view you deserve.</p>
            <br><a href="#" class="btn btn-default-new">Learn more</a>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 module">
            {!!HTML::image('images/home_page/product.jpeg', '', array('class' => 'img-responsive img-thumbnail'))!!}
        </div>
      </div>

      <hr style="width: 90%; border-top: 1px solid #3A8C9E;">

      <div class="col-xs-12" style="overflow: hidden; text-align: left">
        <div class="col-md-4 col-sm-12 " style="margin-top: 50px;">
            <h2 class="left_underline title">Choose EMC<sup>20/20</sup></h2>
            <p class="p1"><b>We bring you the best in building automation and control when it comes to:</b></p>
        </div>
        <div class="col-md-8 col-sm-12">
          <div class="row">
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>COMPATIBLE</strong></h4>
                <p class="p1">The EMC<sup>20/20</sup> has been design to integrate into your building's existing control and sensing networks, saving you time and money.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>EXPANDABLE</strong></h4>
                <p class="p1">By maintaining a flexible development model, the EMC<sup>20/20</sup> is able to accomodate your building's needs. The EMC<sup>20/20</sup> grows with you, as technology advances.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>ADAPTABLE</strong></h4>
                <p class="p1">Thanks to the EMC<sup>20/20</sup>'s open software architecture, systems seemlessly receive the latest updates and improvements, keeping you at the leading edge of building automation systems technology.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="iconBox">
                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                <h4 style="margin-bottom: 10px;"><strong>ACCESSIBLE</strong></h4>
                <p class="p1">The EMC<sup>20/20</sup>'s large, built-in touch screen allows you to monitor your system on site, while maintaining consistency with the mobile interface you use every day.</p>
              </div>
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
            <li data-target="#myCarousel" data-slide-to="0" class="col-md-3 active nav1">
              <a href="#skills" class="products_item active">
                <i class="fa fa-tasks icon"></i>
                <h6>Software</h6>
                <p style="color: #23344b;">What we're good at</p>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="1" class="col-md-3 nav1">
              <a href="#mission" class="products_item">
                <i class="fa fa-lightbulb-o icon"></i>
                <h6>Expansion Units</h6>
                <p style="color: #23344b;">Seamless integration</p>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="2" class="col-md-3 nav1">
              <a href="#values" class="products_item">
                <i class="fa fa-sliders icon"></i>
                <h6>BACnet MSTP</h6>
                <p style="color: #23344b;">Working for you</p>
              </a>
            </li>
            <li data-target="#myCarousel" data-slide-to="3" class="col-md-3 nav1">
              <a href="#about" class="products_item">
                <i class="fa fa-trophy icon"></i>
                <h6>INOVONICS</h6>
                <p style="color: #23344b;">Save cost on installation.</p>
              </a>
            </li>
          </ul>
          <!-- Wrapper for slides -->
          <div class="carousel-inner" style="min-height: 500px;">

            <div class="item active set_slider_padding ">
              <div id="skills" class="row" style="background: white;">
                <div class="col-sm-6 tab_slider_img aos-init aos-animate" data-aos="fade-in">
                  {!! HTML::image('images/home_page/zone_new_mobile.png', 'image title', array('class' => 'tab_slider_img_mobile responsive')) !!}
                  {!! HTML::image('images/home_page/zone_new.png', 'image title', array('class' => 'tab_slider_img_non-mobile responsive')) !!}
                </div>
                <div class="col-sm-6 aos-init aos-animate" data-aos="fade-in">
                  <div class="sectionTitle">
                    <p>&nbsp;</p>
                    <h2 class="title">User Interface</h2>
                  </div>
                  <div class="col-xs-12" style="text-align: left;">
                    <p class="check_desc">We provide an intuitive, mobile-friendly user interface, with access to your building’s most relevant metrics, all from wherever you are.</p>
                    <strong style="color: #23344b;">On the web:</strong>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Accessible from All Major Web Browsers (Firefox, Chrome, Safari, Edge)</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">At-a-Glance System Status Dashboards</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Graphical Building Layout Integration</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Long Term Graphical Visualizations</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Download Reports In Spreadsheet Layout</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Remote Controls Overrides</span></li>
                    </ul>
                  </div>
                  <p>&nbsp;</p>
                </div>
              </div>
            </div>
            <!-- End Item -->

            <div class="item set_slider_padding">
              <div id="mission" class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  {!! HTML::image('images/home_page/expansion_board.jpeg', 'image title', array('class' => 'responsive')) !!}
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <p>&nbsp;</p>
                    <h2 class="title">EMC Expansion Units</h2>
                  </div>
                  <div class="col-xs-12">
                    <p class="check_desc">We offer a competitively designed alternative sensing and control device, built into your EMC<sup>20/20</sup> building automation system.</p>
                    <p class="check_desc">Our unit consists of 10" HD touch screen display, At-a-glance system status dashboard. On top of that we provide features like <b>Graphical Building Layout Integration</b> and <b>Term Graphical Visualizations</b></p>
                  </div>
                  <div class="col-xs-12" style="text-align:left;">
                    <p class="check_desc">The heart of expansion unit is made out of: </p>
                    <ul class="itemList mBase40">
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Internal Interface</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Up to Eight Control Relays</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Multiple Wired Temperature Probe Inputs</span></li>
                      <li><i class="fa fa-check-square-o"></i><span class="check_desc">Multiple Binary Signal Inputs</span></li>
                    </ul>
                  </div>
                  <p>&nbsp;</p>
                </div>
              </div>
            </div>
            <!-- End Item -->

            <div class="item set_slider_padding">
              <div id="values" class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  {!! HTML::image('images/home_page/touch-screen.jpg', 'image title', array('class' => 'responsive')) !!}
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <p>&nbsp;</p>
                    <h2 class="title">BACnet MSTP</h2>
                  </div>
                  <div class="col-xs-12">
                    <p class="core_para">Using BACnet compatible devices, you control valves, switches, pumps, fans, burners, and much more, all from a single, easy to use interface.</p>
                    <p class="core_para">BACnet provide interfaces such as <strong>RS-485 Network Bus</strong> which help us in providing you with controls such as: Lightning, Boiler, Valve, Pumps and Fans.
                    Along with all that you'll be able to control  all your water usage, electricity usage and many other environmental variables.</p>
                    <a href="#" class="button primary">View more</a>
                    <p>&nbsp;</p>
                  </div>
                </div>
              </div>

            </div>
            <!-- End Item -->

            <div class="item set_slider_padding">
              <div id="mission" class="row jshide" style="display: block; background: white;">
                <div class="col-sm-6 tab_slider_img">
                  <img src="http://commonpixel.com/themes/edgar/images/examples/photo_04.jpg" class="responsive" alt="Image title">
                </div>
                <div class="col-sm-6">
                  <div class="sectionTitle">
                    <p>&nbsp;</p>
                    <h2 class="title">Cost Effective</h2>
                  </div>
                  <div class="col-xs-12">
                    <p class="check_desc">Wireless means faster installation, getting you up and running sooner. The temperature and humidity conditions around your buildings influence the indoor climate.
                      Our wireless sensors provide you with realtime data access wherever you are so that you'll always have optimal climate and save money. All over sensors are specially designed to analyze the weather condition and act accordingly.
                      <br>
                      The building manager can adjust all/any rooms in the building with EMC<sup>20/20</sup> simply via interacting with specially designed dashboard, without having to call a contractor.</p>
                  </div>
                </div>
                <p>&nbsp;</p>
              </div>
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

          <div class="row spacer service-section-inner1">
              <div class="col-md-3 col-sm-6 col-xs-12 module">
                  <div class="service-items">
                      <div class="icon">
                        <i class="fa fa-cloud" aria-hidden="true"></i>
                      </div>
                      <h4>Information services</h4>
                      <p>Always keeps you up-to-date with our real-time information and insight using our online cloud service.</p>
                  </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12 module">
                  <div class="service-items">
                      <div class="icon">
                          <i class="fa fa-street-view" aria-hidden="true"></i>
                      </div>
                      <h4>Field Services</h4>
                      <p>Improve performance, safety and also reduce the cost of maintenance or your assets with the help of our experienced provider of complete field services and a partner you can trust.</p>
                  </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="service-items">
                      <div class="icon">
                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                      </div>
                      <h4>Energy Management</h4>
                      <p>If you can measure it, you can manage it. Our software collects data from the sensors 24/7 and help you better manage your energy usage</p>
                  </div>
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="service-items">
                      <div class="icon">
                        <i class="fa fa-bolt" aria-hidden="true"></i>
                      </div>
                      <h4>Lighting Control</h4>
                      <p>Roughly 40 percent of all energy use in commercial buildings is due to lighting. Our advance sensors system dim the lightning to meet the light level requires for space.
                      </p>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-sm-6 mobile_advert">
              {!!HTML::image('images/applogo.png', '', array('class' => 'img-responsive img-thumbnail' ,'style' => 'background: white; max-height: 100px;'))!!}
              <h2>Take EMC<sup>20/20</sup> anywhere you go</h2>
              <label style="padding: 10px 0;">Control your building automation system from anywhere with our new EMC<sup>20/20</sup> app on your iOS and Android devices.</label>

              <div class="row">
                <!-- android logo -->
                <div class="col-xs-6">
                  <a href='https://play.google.com/store/apps/details?id=com.eaw&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png' style="max-width: 200px;"/></a>
                </div>
                <!-- ios logo -->
                <div class="col-xs-6">
                  <a href="#">
                    {!!HTML::image('images/home_page/iOS_badge.svg', '', array('class' => 'img-responsive img-thumbnail' ,'style' => 'background: white; height: 70px; padding-top: 10px;'))!!}
                  </a>
                </div>
              </div>
            </div>
            <div class="col-sm-6" >
              {!!HTML::image('images/home_page/iphone6s.png', '', array('class' => 'img-responsive img-thumbnail iphoneimg' ,'style' => 'background: white;'))!!}
            </div>
          </div>
      </div>
  </section>
  <!--  ==================================END SERVICES SECTION================================================-->

  <!--  ==================================START ABOUT_US SECTION================================================-->
  <section class="aboutus-section" id="aboutus">
      <div class="col-xs-12 aboutus-section-inner" style="background: black; opacity: 0.7; z-index: 10; ">
        <h3>About Us</h2>
        <div style="text-align: left;">
          <label style="color: #3A8C9E;" >What We do</label>
          <div class="col-xs-12 " style="padding-bottom: 10px;">
            <div class="col-sm-6 aboutus-section-text-inner">
              <p class="">At EAW, we're dedicated to providing you with the highest quality building automation devices for remote central heating control.
              <br>Our New York manufacturing facility allows us to ensure our customers receive only the highest quality devices,
              while minimizing maintenance down-times.</p>
              <hr>
            </div>
            <div class="col-sm-6 aboutus-section-text-inner">
              <p>Thanks to our many years of experience in electronics design, manufacturing and testing,
                we are able to offer robust products which have been designed with maximal cost-savings in mind,
                while providing the kind of service and quality you expect.</p>
                <hr>
              <p>Manufacturing our own products means we can more quickly adapt to the ever changing building automation systems market.<br>
              As ongoing innovations continue to spark new applications,
              we are positioned to rapidly integrate these cuttting edge improvements for the benefit of our system's building owners, managers and occupants.</p>
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
