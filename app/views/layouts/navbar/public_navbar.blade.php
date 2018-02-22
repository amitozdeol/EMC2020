<!--hide this for ios app-->
@if(!strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) > 0)
  <li style="min-width: 125px;" >
    <a class="path-link android_nav " href="{{URL::to('home')}}" >HOME</a>
  </li>

  <li style="min-width: 125px;">
    <a class="path-link android_nav" id="nav_product" href="{{URL::to('/').'#products'}}">PRODUCTS</a>
  </li>

  <li style="min-width: 125px;">
    <a class="path-link android_nav" id="nav_services" href="{{URL::to('/').'#services'}}">SERVICES</a>
  </li>

  <li style="min-width: 125px;">
    <a class="path-link android_nav" id="nav_aboutus" href="{{URL::to('/').'#aboutus'}}">ABOUT US</a>
  </li>

  <li style="min-width: 125px;">
    <a class="path-link android_nav" href="{{URL::to('support')}}">SUPPORT</a>
  </li>
@endif
  <li style="min-width: 125px;">
    <a class="path-link android_nav" href="{{URL::to('login')}}">LOGIN</a>
  </li>
