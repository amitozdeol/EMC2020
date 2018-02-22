<?php $title="Dashboard"; ?>

<!--System Page for EMC touchscreen-->
@extends('layouts.wrapper')

@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/bootstrap-dashboard.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>

<style type="text/css">
  .system-blocks{
    padding: 12pt;
    display: block;
    box-shadow: rgb(00,5,10) 0.5em 0.5em 0.5em;
  }
  .system-blocks:hover,
  .system-blocks:focus{
    box-shadow: rgb(00,5,10) .2em .2em .2em;
  }
  .item_title{
    font-size: 16px;
  }

  @media (max-width: 992px){
    .item_title{
      font-size: 12px;
    }
    .item_col{
      padding: 0;
    }
  }
</style>
 <?php
	if( preg_match('/touchscreen/', Route::currentRouteName()) ) {
		/* Touchscreen UI */
		$routePrefix = 'touchscreen';
	}else{
		$routePrefix = 'building';
	}
?>
@foreach($items as $item)
  @if($item->chart_type == NULL)
    <!-- SYSTEMS PAGE ITEMS -->
    <?php
      $this_icon = "fa-building-o";
      switch ($item->label) {
        case 'Alarms':
          $this_icon = "fa-bell";
          break;
        case 'Control History':
        case 'Control Events':
          $this_icon = "fa-history";
          break;
        case 'Zone Control':
        case 'Zone Status':
        case 'EMC Zone Status':
          $this_icon = "fa-gear";
          break;
        case 'Operations':
        case 'Reports':
        case 'Monitors':
          $this_icon = "fa-area-chart";
          break;
        case 'System Status':
          $this_icon = "fa-building-o";
          break;
        default:
          $this_icon = "fa-building-o";
          break;
      }
    ?>
    @if($item->label != 'Operations' )<!--replace operations with reports-->
      <div class="item_col col-xs-6 col-md-4">
        <a class="building-overview-item system-blocks" href="{!!URL::route($routePrefix.'.dashboard', [$thisBldg->id, $thisSystem->id, $item->id])!!}" >
            <i class="fa {!!$this_icon!!} fa-3x" style="color: white;"></i>
          <hr style="margin-top:10px; margin-bottom:5px;">
          <p class="item_title">{!! $item->label !!}</p>
        </a>
      </div>
    @else
      <div class="item_col col-xs-6 col-md-4">
        <a class="building-overview-item system-blocks" href="{!!URL::route('reports.index', [$thisBldg->id, $thisSystem->id])!!}?routeprefix=touchscreen" >
            <i class="fa {!!$this_icon!!} fa-3x" style="color: white;"></i>
          <hr style="margin-top:10px; margin-bottom:5px;">
          <p class="item_title">Reports</p>
        </a>
      </div>
    @endif
    @endif
@endforeach

  <script type="text/javascript">
    @if(isset($times))
      var times = {!! json_encode($times, JSON_NUMERIC_CHECK) !!};
    @endif
    @if(isset($chart_data))
      var chart_data = {!! json_encode($chart_data, JSON_NUMERIC_CHECK) !!};
    @endif
  </script>
@stop
