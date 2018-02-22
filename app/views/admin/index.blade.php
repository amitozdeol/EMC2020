<?php
  $admincss='admin';
  $title="Admin"; 
  
?>

@extends('layouts.wrapper')

@section('content')
<style type="text/css">
  .system-ok {
    background: #9f9;
    color:  #030;
  }
  .searchField {
    width: 100%;
    margin: 10px;
    border: 1px solid grey;
    font-size: 20px;
    outline: grey;
    padding: 5px;
    padding-right: 50px;
    cursor: pointer;
    color: grey;
    box-sizing:border-box;
  }
  .searchInput {
    position: relative;
  }
  .searchInput .glyphicon {
    position: absolute;
    z-index: 2;
    right: 25px;
    font-size: 24px;
    width: 24px;
    color: #4b4b4b;
    text-align: center;
    padding: 6px 0;
    top: 11px;
    cursor: pointer;
  }
  @media screen and (max-width: 1080px){
    .searchField{
      margin: 10px 0;
    }
  }
</style>
<h1>Administration</h1>
<hr style="border-top-color: #000;">
<div>
  <div class="row">
    <div class="searchInput">
      <input type="text" class="searchField" placeholder="Search Buildings" onkeyup="filterBuilding(this)">
      <label for="search" class="glyphicon glyphicon-search" rel="tooltip" title="search"></label>
    </div>
  </div>
</div>
<div class="grid-group ">
  @foreach($customers as $customer)
    @if($customer->deleted_at == null)
      <div class="grid-item customer">
        <div class="col-xs-12">
          <span style="font-family: serif; font-weight: 700;">{{$customer->name}}</span>
        </div>

        <?php $systemcount = 0; ?>

        @foreach($buildings as $building)
          @if($building->customer_id == $customer->id && $building->deleted_at == null)
            <?php $systemcount = 0; ?>
            <div class="col-xs-12 col-sm-6 building">
              <span class="building-title" style="font-family: serif; font-weight: 500;">{{$building->name}}</span>

              @foreach($systems as $system)
                @if($system->building_id == $building->id and $system->system_delete == null)

                  <a href="{{URL::route('building.system', [$system->building_id, $system->id])}}">

                    @if($system->alarm_severity == 0)
                        <div class="col-xs-offset-1 col-xs-10 col-md-offset-0 col-md-12 system system-ok" title="V{{$system->software_version}}">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        {{$system->name}}
                      </div>
                    @endif

                    @if($system->alarm_severity == 1)
                      <div class="col-xs-offset-1 col-xs-10 col-md-offset-0 col-md-12 system" style="background:#<?php echo dechex(0xffff55-(0x040404*$system->alarm_intensity));?>;color:#030;" title="V{{$system->software_version}}">
                        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                        {{$system->name}}
                      </div>
                    @endif

                    @if($system->alarm_severity == 2)
                      <div class="col-xs-offset-1 col-xs-10 col-md-offset-0 col-md-12 system" style="background:#<?php echo dechex(0xff2222-(0x0e0000*$system->alarm_intensity));?>;color:white;" title="V{{$system->software_version}}">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        {{$system->name}}
                      </div>
                    @endif

                  </a>

                  <?php $systemcount++; ?>
                @endif
              @endforeach
              @if(!$systemcount)
                <div class="col-xs-12" style="text-align: center; padding: 0px 20px; ">
                  <h3>
                    <a class="path-link" href="{{URL::to('building/' . $building->id . '/newsystem')}}" style="color: #ffffff;">Add System</a>
                  </h3>
                </div>
              @endif
            </div>
          @endif
        @endforeach
        <hr>
      </div>
      @endif
  @endforeach

</div>

<script language"javascript" type"text/javascript">
  $(document).ready(function(){
    //refresh page every 30 seconds
    $(window).scroll(function () {
      //set scroll position in session storage
      sessionStorage.scrollPos = $(window).scrollTop();
    });
    var init = function () {
      //get scroll position in session storage
      $(window).scrollTop(sessionStorage.scrollPos || 0)
    };
    window.onload = init;
  });
  //stop reloading when modal is open or when StopRefresh class is selected
  var timeout = setInterval(function(){
    if($('.modal').hasClass('in') || $('.StopRefresh').hasClass('open')){
      //do nothing
    }
    else{
      window.location.hash = ""
      location.reload(true);
    }
  },300000);

  function filterBuilding(el) {
    var filter = el.value.toUpperCase();
    var buildings = document.getElementsByClassName('building-title');
    for (i = 0; i < buildings.length; i++) {
        var building = buildings[i];
        if (building.innerHTML.toUpperCase().indexOf(filter) > -1) {
            building.parentElement.style.display = "";
        } else {
            building.parentElement.style.display = "none";
        }
    }
}
</script>
@stop
