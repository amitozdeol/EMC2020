<?php $title="Access"; ?>

@extends('layouts.wrapper')

@section('content')
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_css = ['/css/responsive_table.css'];    //add file name
  foreach ($import_css as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::style($appendDate);
    }
  }
?>

<style>
  .btn-sm{
    font-size: 14px;
  }
  .titlebutton{
    border: 1px solid;
  }
  .groupaccess_title{
    background: #123e5d;
    margin: 0px;
    color: white;
    padding-left: 10px;
    padding-right: 10px;
  }
  .groupaccess_groups{
    padding: 10px;
    border: 1px solid;
    background: white;
  }
  @media (max-width: 1080px) {
    .btn-sm{
      font-size: 12px;
      padding: 6px;
    }
    .js-confirm{
      padding: 5px 10px;
      margin-top: -5px;
    }
    h2{
      font-size: 22px;
    }
    .groupaccess_groups{
      margin: 5px;
      box-shadow: black 0.1em 0.1em 0.15em;
    }
  }
</style>
<br>
<div class="row well" style="box-shadow: black 0.1em 0.1em 0.15em;">
  <div class="pull-left">
    <h2>Building Access</h2>
  </div>
  <div class="pull-right">
    <button class="btn btn-default btn-sm titlebutton text-right" data-toggle="modal" data-target="#new-manager-modal">Add a Manager</button>
  </div>
  <div class="col-xs-12">
    <br>
  @if(count($managers))
    <div>
      <table class="responsive-table table table-hover">
        <thead>
          <tr class="theadrow">
            <th scope="col"><strong>Building</strong></th>
            <th scope="col"><strong>User</strong></th>
            <th scope="col"><strong>Access Level</strong></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($managers as $manager)
            <tr>
              <th scope="row">{{$manager->name}}</th>
              <td data-title="User">
                @if($manager->first_name !== '' && $manager->last_name !== '')
                  {{$manager->first_name}} {{$manager->last_name}}
                @else
                  {{$manager->email}}
                @endif
              </td>
              <td data-title="Access Level">
                {{Form::open(['action'=>'access.destroy', 'method'=>'DELETE'])}}
                {{Form::hidden('building_manager_id',$manager->id)}}
                {{$manager->label}}
              </td>
              <td data-title="Remove">
                <button type="submit" class="btn btn-danger btn-sm pull-right js-confirm" data-confirm="Are you sure you want to delete this access rule?">
                  <i class="glyphicon glyphicon-remove"></i>
                  <span class="hidden-xs">Remove</span>
                </button>
              </td>
                {{Form::close()}}
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <h3 class="text-center">There are no building managers yet</h3>
  @endif
  </div>
</div>


<br>
<div class="row well" style="box-shadow: black 0.1em 0.1em 0.15em;">
  <div class="row" style="margin-left: 0px; margin-right: 0px;">
    <div class="pull-left">
      <h2>Group Access</h2>
    </div>
    <div class="pull-right">
      <button class="btn btn-default btn-sm titlebutton text-right" data-toggle='modal' data-target='#new-group-modal'>Create a Group</button>
    </div>
  </div>

  @if(!count($groups))
    <h3 class="text-center">There are no groups yet</h3>
  @endif

  @foreach($groups as $group)
  <br>
    <div class="row groupaccess_title">
      <h3 class="col-xs-4 col-sm-6 col-sm-offset-2" style="margin-top: 5px; text-align: center;">
        {{$group->name}}

      </h3>
      <div class="pull-right" style="margin-top: 5px;">
        <div class="btn-group">
          <button class="btn btn-default btn-sm"  style="margin:1px;" data-toggle="modal" data-target="#edit-group-{{$group->id}}">Edit</button>
          <button type="button" class="btn btn-sm btn-default" style="margin:1px;" data-toggle="modal" data-target="#add-building-{{$group->id}}">
            <span class="visible-xs-inline hidden-sm-inline"><i class="glyphicon glyphicon-plus"></i></span>
            <span class="hidden-xs">Add</span>
            Building
          </button>
          <button type="button" class="btn btn-sm btn-default" style="margin:1px;" data-toggle="modal" data-target="#add-manager-{{$group->id}}">
            <span class="visible-xs-inline hidden-sm-inline"><i class="glyphicon glyphicon-plus"></i></span>
            <span class="hidden-xs">Add</span>
            Manager
          </button>
        </div>
      </div>
    </div>

    <div class="row" style="margin: 0px; border: 2px solid #123e5d; background: #ababab;">
      <div class="col-md-6 groupaccess_groups">
        <div>
          <table class="responsive-table table table-hover">
            <thead>
              <tr class="theadrow">
                <th scope="col">Building</th>
                <th scope="col">Address</th>
                <th scope="col"></th>
              </tr>
            </thead>
            @foreach($group_buildings AS $group_building)
              @if($group->id === $group_building->building_group_id)
              <tr>
                <th scope="row">{{$group_building->name}}</th>
                <td data-title="Address">
                  <span>{{$group_building->address1}}</span>
                  {{Form::open(['action'=>'access.deleteGroupBuilding', 'method'=>'DELETE', 'class'=>'pull-right'])}}
                  {{Form::hidden('id', $group_building->id)}}
                </td>
                <td data-title="Remove">
                  <button type="submit" class="btn btn-danger btn-sm pull-right js-confirm" data-confirm="Are you sure you want to delete this access rule?">
                    <i class="glyphicon glyphicon-remove"></i>
                    <span class="hidden-xs">Remove</span>
                  </button>
                  {{Form::close()}}
                </td>
              </tr>
              @endif
            @endforeach
          </table>
        </div>
      </div>

      <div class="col-md-6 groupaccess_groups">
        <div>
          <table class="responsive-table table table-hover">
            <thead>
              <tr class="theadrow">
                <th scope="col">User</th>
                <th scope="col">Access Level</th>
                <th scope="col"></th>
              </tr>
            </thead>
            @foreach($group_managers as $group_manager)
              @if($group_manager->building_group_id === $group->id)
              <tr>
                <th scope="row">{{$group_manager->email}}</th>
                <td data-title="Access Level">
                  <span>{{$group_manager->label}}</span>
                  {{Form::open(['action'=>'access.deleteGroupManager', 'method'=>'DELETE', 'class'=>'pull-right'])}}
                  {{Form::hidden('id', $group_manager->id)}}
                </td>
                <td data-title="Remove">
                  <button type="submit" class="btn btn-danger btn-sm pull-right js-confirm" data-confirm="Are you sure you want to delete this access rule?">
                    <i class="glyphicon glyphicon-remove"></i>
                    <span class="hidden-xs">Remove</span>
                  </button>
                  {{Form::close()}}
                </td>
              </tr>
              @endif
            @endforeach
          </table>
        </div>
      </div>
    </div>
  @endforeach
</div>

<!-- Here's an absurd amount of modals -->

<div class="modal fade" id="new-manager-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{Form::open(['action'=>'access.store', 'class'=>'form-spaced'])}}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">
            Add a New manager
          </h4>
        </div>
        <div class="modal-body">
            {{Form::label('building_id','Building')}}
            {{Form::select('building_id',$buildings, null, ['class'=>'form-control'])}}

            {{Form::label('user_id','User')}}
            {{Form::select('user_id',$users, null, ['class'=>'form-control'])}}

            {{Form::label('role','Access Level')}}
            {{Form::select('role', $labels, null, ['class'=>'form-control'])}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input  type="submit" class="btn btn-primary btn-sm" value="Save">
        </div>
      </div>
      {{Form::close()}}
    </div>
  </div>
</div>

<div class="modal fade" id="new-group-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{Form::open(['action'=>'access.storeGroup', 'class'=>'form-spaced'])}}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">
            Create a New Group
          </h4>
        </div>
        <div class="modal-body">
          {{Form::label('name', 'Group Name')}}
          {{Form::text('name', null, ['class'=>'form-control'])}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input  type="submit" class="btn btn-primary btn-sm" value="Save">
        </div>
      </div>
      {{Form::close()}}
    </div>
  </div>
</div>

@foreach($groups as $group)
<div class="modal fade" id="edit-group-{{$group->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{Form::open(['action'=>'access.updateGroup', 'class'=>'form-spaced'])}}
      {{Form::hidden('id', $group->id)}}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">
            Edit Group
          </h4>
        </div>
        <div class="modal-body">
          {{Form::label('name', 'Group Name')}}
          {{Form::text('name', $group->name, ['class'=>'form-control'])}}
        </div>
        <div class="modal-footer">
          {{HTML::link('access/group/delete/'.$group->id, 'Delete Group', ['class'=>'btn btn-danger btn-sm pull-left js-confirm', 'data-confirm'=>'Are you sure you want to delete this group and all of the associated building/manager access rules?'])}}
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input  type="submit" class="btn btn-primary btn-sm" value="Save">
        </div>
      </div>
      {{Form::close()}}
    </div>
  </div>
</div>

<div class="modal fade" id="add-building-{{$group->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{Form::open(['action'=>'access.addGroupBuilding', 'class'=>'form-spaced'])}}
      {{Form::hidden('id', $group->id)}}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">
            Add a building to {{$group->name}}.
          </h4>
        </div>
        <div class="modal-body">
          {{Form::label('building_id', 'Building')}}
          {{Form::select('building_id', $buildings, NULL, ['class'=>'form-control'])}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input  type="submit" class="btn btn-primary btn-sm" value="Save">
        </div>
      </div>
      {{Form::close()}}
    </div>
  </div>
</div>

<div class="modal fade" id="add-manager-{{$group->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{Form::open(['action'=>'access.addGroupManager', 'class'=>'form-spaced'])}}
      {{Form::hidden('id', $group->id)}}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">
            Add a Manager to {{$group->name}}.
          </h4>
        </div>
        <div class="modal-body">
          {{Form::label('user_id', 'User')}}
          {{Form::select('user_id', $users, NULL, ['class'=>'form-control'])}}

          {{Form::label('role', 'Access Level')}}
          {{Form::select('role', $labels, NULL, ['class'=>'form-control'])}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
          <input  type="submit" class="btn btn-primary btn-sm" value="Save">
        </div>
      </div>
      {{Form::close()}}
    </div>
  </div>
</div>
@endforeach

@stop
