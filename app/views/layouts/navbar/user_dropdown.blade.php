<!--user.dropdown-->
<li class="dropdown StopRefresh">

  <a href="#" role="button" class="user_dropdown_tour dropdown-toggle" data-toggle="dropdown">
    @if(Auth::user()->customer_id == 0)
      <span class="label label-default">STAFF</span>
    @endif
      <span class="path-link">
    @if(Auth::user()->first_name !== '' && Auth::user()->last_name !== '')
      {{Auth::user()->first_name}} {{Auth::user()->last_name}}
    @else
      {{Auth::user()->email}}
    @endif
    </span>
    <span class="caret"></span>
  </a>

  <ul class="dropdown-menu user-dropdown-lg" role="menu">

  @if((int)Auth::user()->role === 5)
      <li class="dropdown-header"><big><b>Management</b></big></li>
    @if((int)Auth::user()->role === 5)
      <li class="user_dropdown_tour path-link" role="presentation">
        {{HTML::link(URL::route('user.index'),     'Users',     ['role'=>'menuitem', 'tabindex'=>'-1','class'=>'android_nav_dropdown'])}}
      </li>
    @endif
    @if((int)Auth::user()->role === 8)
      <li class="path-link" role="presentation">
        {{HTML::link(URL::route('building.index'), 'Buildings', ['role'=>'menuitem', 'tabindex'=>'-1','class'=>'android_nav_dropdown'])}}
      </li>
    @endif
    @if((int)Auth::user()->role === 5 || (int)Auth::user()->role >= 7)
      <li class="user_dropdown_tour path-link" role="presentation">
        {{HTML::link(URL::route('access.index'),   'Access',    ['role'=>'menuitem', 'tabindex'=>'-1','class'=>'android_nav_dropdown'])}}
      </li>
    @endif
      <li role="presentation" class="divider"></li>
  @endif

  @if((int)Auth::user()->role >= 6)
    <li class="dropdown-header">
      <big><b>
        Administration
      </b></big>
    </li>
    <li class="path-link" role="presentation">{{HTML::link(URL::route('admin.customer.index'),    'Customers',     ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}</li>
    <li class="path-link" role="presentation">{{HTML::link(URL::route('admin.user.index'),        'Users',         ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}</li>
  @endif
  @if((int)Auth::user()->role >= 8)
    <li class="path-link" role="presentation">{{HTML::link(URL::route('admin.building.index'),    'Buildings',     ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}</li>
    <li class="path-link" role="presentation">{{HTML::link(URL::route('admin.producttype.index'), 'Product Types', ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}</li>
    <li class="path-link" role="presentation">{{HTML::link(URL::route('admin.devicetype.index'),  'Device Types',  ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}</li>
  @endif
  @if((int)Auth::user()->role >= 6)
    <li role="presentation" class="divider"></li>
  @endif

    <li class="user_dropdown_tour path-link" role="presentation">
      {{HTML::link(URL::route('account.index'), 'Your Account', ['role'=>'menuitem', 'tabindex'=>'-1', 'class'=>'android_nav_dropdown'])}}
    </li>
    <li class="path-link" role="presentation">
      {{HTML::link(URL::to('logout'), 'Logout', ['class'=>'android_nav_dropdown'])}}
    </li>

  </ul>

</li>
