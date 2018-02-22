<!--navbar_returns index-->
@if(Request::is('EMC/*'))
  <?php $touchscreen='touchscreenJS';?>
@else
  <?$chartJS='chartimport';?>
@endif
<style>
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
  .page-nav {
    padding: 8px;
    color: #cccece;
    background-color: #2B3C51;
    color: white;
    text-align: center;
    font-size: 18px;
    box-shadow: black 0.1em 0.1em 0.15em;
    font-weight: 100;
    margin-bottom: 20px;
  }
  body{ 
    margin-top: 110px;
  }
</style>


<?php
  if( preg_match('/touchscreen/', Route::currentRouteName()) && isset($thisBldg->id) && isset($thisSystem->id) ) {
    /* Touchscreen UI */
    if(isset($parent) && $parent->parent_id !== 0) {
      /* Return to top level */
      $back_url = URL::route('touchscreen.system', [$thisBldg->id, $thisSystem->id, $parent->parent_id]);
      $back_message = "Back to " . $thisBldg->name . " - " . $thisSystem->name . " - Dashboard";
    }else{
      /* Return to parent id */
      $back_url = URL::route('touchscreen.system', [$thisBldg->id, $thisSystem->id]);
      $back_message = "Back to " . $thisBldg->name . " - " . $thisSystem->name . " Overview";
    }
  }else{
    /* Regular Web UI */
    if(isset($parent) && $parent->parent_id !== 0) {
      /* Return to top level */
      $back_url = URL::route('building.dashboard', [$thisBldg->id, $thisSystem->id, $parent->parent_id]);
      $back_message = "Back to " . $thisBldg->name . " - " . $thisSystem->name . " - " . $parent->label;
    }else{
      /* Return to parent id */
      $back_url = URL::route('building.system', [$thisBldg->id, $thisSystem->id]);
      $back_message = "Back to " . $thisBldg->name . " - " . $thisSystem->name . " Dashboard";
    }
  }

  if(Route::currentRouteName() === 'building.system') {
    $back_url = URL::route('building', $thisBldg->id);
    $back_message = "Back to " . $thisBldg->name . " Overview";
  }
?>

@if(Request::is('EMC/*'))
  @if( Route::currentRouteName() !== 'touchscreen.system' )
    <!-- back button for touchscreen -->
    <div class="col-xs-12">
      <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-4 col-md-offset-4 page-nav" style="cursor: pointer; margin-top: 20px; margin-bottom: 5px;" onclick="window.location='{{$back_url}}';">
        {{$back_message}}
      </div>
    </div>
  @endif
@else
<!--navbar_returns content-->
  <nav class="navbar navbar-default navbar-returns affix" style="box-shadow: black 0.1em 0.1em 0.2em; z-index:2; border-radius: 0px;" data-spy="affix" data-offset-top="0">
    <div class="container col-xs-12" style="align-items: center; height: 50px; display: grid;">
      <div class="page-title col-xs-12">
          {{$thisBldg->name}} - {{$thisSystem->name}}
      </div>
  </nav>
  <br>
@endif

<script>
  if(navigator.userAgent.match("EMC2020/ios") || navigator.userAgent.match("EMC2020/1.0/Android")){
    document.getElementsByTagName("BODY")[0].style.marginTop = "50px";
    document.getElementsByClassName("navbar-returns")[0].style.top = "0px";
  }else{
    @if(Request::is('EMC/*'))
      //document.getElementsByTagName("BODY")[0].style.marginTop = "6px";
      //document.getElementsByClassName("navbar-returns")[0].style.top = "0px";
    @endif
  }

</script>
