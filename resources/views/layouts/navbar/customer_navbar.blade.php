<!--customer_navbar-->
@if(count($customer_buildings_for_navbar))

  <li class="dropdown StopRefresh ">
    <a href="#" class=" dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Buildings <span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">

    @foreach($customer_buildings_for_navbar as $building)

      <li><a href="{{URL::to('/#'.$building->id)}}" class="android_nav_building" ><span class="path-link">{{$building->name}}</span></a></li>

    @endforeach

    </ul>
  </li>


@endif
<li>
  <a class="path-link android_nav" href="{{URL::route('settings.customerbuildings', [Auth::user()->customer_id])}}">Global Settings</a>
</li>
@if( isset($thisBldg->id) && isset($thisSystem->id) )
  <li>
    <a class="path-link android_nav" href="{{URL::route('alarms', [$thisBldg->id, $thisSystem->id])}}">Alarms</a>
  </li>

  <li>
    <a class="path-link android_nav" href="{{URL::route('reports.export', [$thisBldg->id, $thisSystem->id])}}">Data Export</a>
  </li>


@endif
