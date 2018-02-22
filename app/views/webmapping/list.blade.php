<?php $title="Web Mapping"; ?>

@extends('layouts.wrapper')

  @section('content')

    <!-- <a class="row col-xs-offset-10 btn btn-primary" style="text-align: right"  href="{{URL::route('building.system', [$bid, $sid])}}">Return to Dashboard</a> -->
    <h1>Web Mapping Setup</h1><br>
    {{Form::open(['route'=>['webmapping.update', $bid, $sid, -1], "method" => "put"])}}
    <div class="sortable-list inactive-parent-selected-list-group">
      <?$item_exist = 0;?>
      @foreach($dashboardItems as $item)
        <?$item_exist++;?>
        @if($item->parent_id == 0)
          <div class="sortable-list-group inactive-child-selected-list-group">
            <h4 class="row-detail sortable-list-item collapsed list-item" id="{{str_replace(' ','',$item->label)}}{{$item->id}}" style="padding-left:10px"  data-toggle="collapse" href="#{{str_replace(' ','',$item->label)}}{{$item->id}}Children">{{$item->label}}</h4>
            <div class="container-fluid collapse" id="{{str_replace(' ','',$item->label)}}{{$item->id}}Children" style="margin:10px">
               @if( $item->chart_type == NULL || $item->chart_type != strtoupper($item->chart_type))
              <div style="text-align:right">
                @if( (!isset($onlyChildren[$item->id]) || $dashboardItems[$onlyChildren[$item->id]]->chart_type !== strtoupper($dashboardItems[$onlyChildren[$item->id]]->chart_type)) && $dashboardItems[$item->id]->chart_type == NULL )
                  <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#{{str_replace(' ','',$item->label)}}{{$item->id}}AddModal">Add</a>
                @endif
                @if($item->chart_type != strtoupper($item->chart_type))
                  <a class="btn btn-primary btn-xs" href="{{URL::route('charts.edit', [$bid, $sid, $item->id])}}">Edit Chart</a>
                  </div>
                  <div style="text-align:center">{{ HTML::image('images/chart_snap.png', 'Chart', array('class' => 'web-snap-shot')) }}</div>
                @else
                  <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#{{str_replace(' ','',$item->label)}}{{$item->id}}EditModal">Edit</a>
                  </div>
                @endif
              @endif
              <div id="{{str_replace(' ','',$item->label)}}{{$item->id}}Children" class="sortable-list"></div>
              {{Form::text(str_replace(' ','',$item->label).'Order',$item->order."-".$item->parent_id."-".$item->id,["id" => str_replace(' ','',$item->label).$item->id."Order", "hidden" => "true"])}}
            </div>
          </div>
        @endif
      @endforeach
    </div>
      @if($item_exist == 0)
        <div class="alert alert-info" role="alert" style="text-align:center">You have not mapped anything yet.</div>
        <div class="col-xs-12" style="margin: 15px; text-align:right">
          <div class="col-xs-offset-3 col-xs-2">
            <a class="btn btn-primary btn-lg js-confirm" data-confirm="Are you sure you want to Cancel? This will lose all changes and return you to the edit system dashboard." style="width:100%" href="{{URL::route('system.editSystem', [$bid, $sid])}}">Cancel</a>
          </div>
          <div class="col-xs-offset-2 col-xs-2">
            <button class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-target="#newGroupModal">Add Dashboard Item</button>

          </div>
        </div>
      @else
        <div class="col-xs-12" style="margin: 15px; text-align:right">
          <button class="btn btn-primary btn-md" type="button" data-toggle="modal" data-target="#newGroupModal"><span aria-hidden="true">&plus;</span></button>
        </div>
        <div class="col-xs-12" style="margin: 15px; text-align:right">
          <div class="col-xs-offset-3 col-xs-2">
            <a class="btn btn-primary btn-lg js-confirm" data-confirm="Are you sure you want to Cancel? This will lose all changes and return you to the edit system dashboard." style="width:100%" href="{{URL::route('system.editSystem', [$bid, $sid])}}">Cancel</a>

          </div>
          <div class="col-xs-offset-2 col-xs-2">
            {{Form::submit('Save Layout', ["class"=>"btn btn-lg btn-primary", "style" => "width:100%"])}}
          </div>
        </div>
      @endif
    {{Form::close()}}
      <!-- Parent 0 Add Modal -->
      <div id="modals">
      <div class="modal fade" id="newGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="color:black; text-align:center">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">New System Dashboard Item</h4>
          </div>
          <div class="modal-body row">
          {{Form::open(['route' => ['webmapping.store', $bid, $sid], 'method' => 'post'])}}
          {{Form::hidden('parent_id', "0")}}
          @if ( isset($availableDashboardItems[0]) )
            <?$disabled = 'disabled';
            $altdisabled = '';
            ?>
          @else
            <?$disabled = '';
            $altdisabled = 'disabled';
            ?>
          @endif
          <div class="col-xs-6">
            <div class="col-xs-2"></div>
            <div class="col-xs-8">
            {{Form::radio('item_type','predefined', $disabled == 'disabled' ? false : true, ["class" => "pull-left item-type", $disabled])}}
            <h4>Predefined</h4>
            <p>Page Type:</p>
            {{Form::select('id', $availableDashboardItems, min(array_keys($availableDashboardItems)) ,["class" => "form-control predefined-type select-id", "style" => "color:black", $disabled, "id" => "id"])}}<br>
            <p>Name:</p>
            {{Form::text('label', !isset($dashModels[min(array_keys($availableDashboardItems))]) ? "Nothing" : $dashModels[min(array_keys($availableDashboardItems))]->label,["class" => "form-control predefined-type", "style" => "color:black", $disabled, "id" => "label"])}}
            <br>
          </div>
          <div class="col-xs-2"></div>
          </div>
          <div class="col-xs-6 modal-or-divider">
            <div class="col-xs-2"></div>
              <div class="col-xs-8">
              {{Form::radio('item_type','generic',$disabled == 'disabled' ? true : false, ["class" => "pull-left item-type", $disabled])}}
              <h4>Generic</h4>
              <p>Dashbord Item Type:</p>
              {{Form::select('dash_item_type',array_merge(["link" => "Dashboard Group"] ,$chart_types), 'link' ,["class" => "form-control generic-type", "style" => "color:black", $altdisabled])}}<br>
              <p>Name:</p>
              {{Form::text('label', null ,["class" => "form-control generic-type", "style" => "color:black", "placeholder" => "Enter a label",$altdisabled])}}
              <br>
            </div>
            <div class="col-xs-2"></div>
          </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          {{Form::submit('Save', ["class"=>"btn btn-primary"])}}
          {{Form::close()}}
          </div>
        </div>
        </div>
      </div>
      <!-- Parent 0 Add Modal -->


      @foreach($dashboardItems as $item)
        @if( $item->chart_type == NULL || $item->chart_type != strtoupper($item->chart_type))
      <!-- Sub level Add Modal -->
          @if(!isset($onlyChildren[$item->id]) || ($dashboardItems[$onlyChildren[$item->id]]->chart_type != strtoupper($dashboardItems[$onlyChildren[$item->id]]->chart_type || $dashboardItems[$onlyChildren[$item->id]]->chart_type == NULL)))
          <div class="modal fade" id="{{str_replace(' ','',$item->label)}}{{$item->id}}AddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="color:black; text-align:center">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            {{Form::open(['route' => ['webmapping.store', $bid, $sid], 'method' => 'post'])}}
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">New {{$item->label}} Dashboard Item</h4>
              </div>
              <div class="modal-body row">

              {{Form::hidden('parent_id', $item->id)}}
              @if ( isset($availableDashboardItems[0]) )
                <?$disabled = 'disabled';
                $altdisabled = '';
                ?>
              @else
                <?$disabled = '';
                $altdisabled = 'disabled';
                ?>
              @endif
              <div class="col-xs-6">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
                  {{Form::radio('item_type','predefined', $disabled == 'disabled' ? false : true, ["class" => "pull-left item-type", $disabled])}}
                  <h4>Predefined</h4>
                  <p>Page Type:</p>
                  {{Form::select('id', $availableDashboardItems, min(array_keys($availableDashboardItems)) ,["class" => "form-control predefined-type select-id", "style" => "color:black", $disabled, "id" => "id"])}}<br>
                  <p>Name:</p>
                  {{Form::text('label', !isset($dashModels[min(array_keys($availableDashboardItems))]) ? "Nothing" : $dashModels[min(array_keys($availableDashboardItems))]->label,["class" => "form-control predefined-type", "style" => "color:black", $disabled, "id" => "label"])}}
                  <br>
                </div>
                <div class="col-xs-2"></div>
              </div>
              <div class="col-xs-6 modal-or-divider" style="border-left: 1px solid #e5e5e5;">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
                {{Form::radio('item_type','generic',$disabled == 'disabled' ? true : false, ["class" => "pull-left item-type", $disabled])}}
                <h4>Generic</h4>
                <p>Dashbord Item Type:</p>
                {{Form::select('dash_item_type',array_merge(["link" => "Dashboard Group"] ,$chart_types), 'link' ,["class" => "form-control generic-type", "style" => "color:black", $altdisabled])}}<br>
                <p>Name:</p>
                {{Form::text('label', null ,["class" => "form-control generic-type", "style" => "color:black", "placeholder" => "Enter a label", $altdisabled])}}
                <br>
              </div>
                <div class="col-xs-2"></div>
              </div>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              {{Form::submit('Save', ["class"=>"btn btn-primary"])}}
              </div>
            {{Form::close()}}
            </div>
            </div>
          </div>
          @endif
          <!-- Sub level Edit Modal -->
          <div class="modal fade" id="{{str_replace(' ','',$item->label)}}{{$item->id}}EditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="color:black; text-align:center">
            <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              {{Form::open(['route' => ['webmapping.update', $bid, $sid, $item->id], 'method' => 'put'])}}
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Edit Dashboard Item Label</h4>
              </div>
              <div class="modal-body row">
              {{Form::hidden('id', $item->id)}}
              <div class="col-xs-2"></div>
                <div class="col-xs-8">
                  <p>Dashboard Item Type:</p>
                  @if ( $item->chart_type == NULL )
                    <p style="font-weight:bold">Link</p>
                  @else
                    @if (strtoupper($dashboardItems[$item->id]->chart_type) == $dashboardItems[$item->id]->chart_type)
                      <p>{{ $dashboardItems[$item->id]->chart_type }}</p>
                    @else
                      <p>{{ $dashboardItems[$item->id]->chart_type." Chart" }}</p>
                    @endif
                  @endif
                  <p>Name:</p>
                  {{Form::text('label', $item->label,["class" => "form-control", "style" => "color:black"])}}
                  <br>
                </div>
              <div class="col-xs-2"></div>
              </div>
              <div class="modal-footer" style="text-align:center">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              {{Form::submit('Save', ["class"=>"btn btn-primary"])}}
              {{ HTML::linkAction('WebMappingController@destroy', 'Delete', [$bid, $sid,$item->id], ['class' => 'btn btn-danger js-confirm', 'data-confirm' => 'All dashboard items connected to this dashboard item will be deleted! This cannot be undone. Would you like to continue deleting this dashboard item?']) }}
              </div>
              {{Form::close()}}

            </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
    <script>
      var dashboardParents = {{json_encode($dashboardParents)}};
      var dashboardChildren = {{json_encode($dashboardChildren)}};
      var onlyChildren = {{json_encode($onlyChildren)}};
      var dashboardItems = {{json_encode($dashboardItems)}};
      var dashModels = {{json_encode($dashModels)}};
      var availableDashboardItems = {{json_encode($availableDashboardItems)}};
      var chart_types = {{json_encode($chart_types)}};
      var bid = {{json_encode($bid)}};
      var sid = {{json_encode($sid)}};

    </script>

  @stop

@stop
