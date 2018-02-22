<!--staff_navbar-->
@if( isset($thisBldg->id) && Auth::user()->auth_role >= 7 )

  @if(Auth::user()->auth_role >= 8)
    <li>
      <a class="path-link android_nav" href="{!!URL::to('building/' . $thisBldg->id . '/newsystem')!!}">Add System</a>
    </li>
  @endif


  <li>
    <a class="path-link android_nav" href="{!!URL::route('settings.customerbuildings', [$thisBldg->customer_id])!!}">Global Settings</a>
  </li>

  @if( isset($thisSystem->id) )

    <li>
      <a class="path-link android_nav" href="{!!URL::route('system.editSystem', [$thisBldg->id, $thisSystem->id])!!}">Configure System</a>
    </li>

    @if( isset($thisBldg->id) && isset($thisSystem->id) )
    <li>
      <a class="path-link android_nav" href="{!!URL::route('reports.export', [$thisBldg->id, $thisSystem->id])!!}">Data Export</a>
    </li>
    @endif

  @endif


@endif
