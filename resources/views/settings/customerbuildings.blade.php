<?php $title="Settings"; ?>

@extends('layouts.wrapper')


@section('content')

<?php
	$help_id='globalsettings';
?>

<script type="text/javascript">
	function update_button(elmt){
		var seasonVal = $(elmt.previousElementSibling.lastElementChild.firstElementChild).find(":selected").text();
		if(seasonVal == "Summer"){
			$(elmt).attr("data-toggle","none");
			$(elmt).prop("type","submit");
		}else if(seasonVal == "Winter"){
			$(elmt).attr("data-toggle","modal");
		}
	}
</script>

<style type="text/css">
	.system-select{
		cursor: pointer;
	}
	.system-select:hover,
	.system-select:focus,
	.system-select.focus{
		background-color: #39617D;
		box-shadow: black .07em .07em .075em;
		border-style: solid;
		border-color: #224E6D;
	}
</style>
<div class="col-xs-12 page-title" style="margin-bottom: 20px;">
  <h2>Global Settings</h2>
</div>
@if(Auth::user()->customer_id == $customer_id || Auth::user()->role >= 7)
	<div class="col-xs-12">

	<!-- Here the user can change the season settings for all systems -->
	 	<div class="col-xs-12 block_emc" style="text-align: center; text-shadow: 0px 0px 10px #000000; margin-top: 10px;  margin-bottom: 30px;">
	 		<div class="col-xs-12 col-sm-6">
	 			<p>
	 				<big>Season Mode</big><br>
	 				The season indicates to your controls whether they should behave normally, based on the chosen inputs, or if they should remain off and ignore their input readings. Different controls may or may not react to the season you set here.
	 			</p>
	 		</div>
	 		{{ Form::open(array('route'=>['settings.updatecustomerbuildings',$customer_id])) }}
	 		<div class="multi_season_change_tour col-xs-12 col-sm-6" style="margin-top: 20pt; margin-bottom:20pt;">
	 			<div class="col-xs-12" style="margin-bottom: 20px">
	 				Set All Systems To:
	 				<br>
		 			<div class="multi_season_change_tour col-xs-12" style="margin-top: 20px; margin-bottom: 10px;">
	 					{{ Form::select('All_Season', array('0' => 'Winter', '1' => 'Summer'), null, array("class" => "form-control", "style" => "color:black;cursor: pointer;")) }}
 					</div>
	 			</div>
				<button name='AllSeasons' class="multi_season_change_tour btn btn-primary col-xs-6" type="button" data-toggle="modal" data-target="#all-change"  data-backdrop="static" onclick="update_button(this)">
					Save
				</button>
				<button class="btn btn-primary col-xs-6" >
					Cancel
				</button>
			</div>

	 	</div>

	 	<!-- Here we display the list of individual systems for the user to pick from -->
	 	<div class="col-xs-12 col-sm-6 " style="height: 300px; overflow-y: scroll; box-shadow: 0px 10px 20px 0px #123E5D inset; padding-top: 10px; border: 1px solid #123E5D;">
	 		<?php $set_single_tour = true; ?>
		 	@foreach ($sysarray as $sys )
		 		<div id="system-select-{{$sys['system_id']}}" class="@if($set_single_tour) single_season_change_tour @endif col-xs-12 block_emc system-select" data-toggle="collapse" data-parent="#collapse-container" href="#change-some-{{$sys['system_id']}}">
		 			<div class="col-xs-4" style="margin-top: 10px;">
		 				<?php
		                  if($sys['season_mode_code'] == '0'){
		                    $seasonPicDesc ='Snow Flakes';
		                    $seasonPicture = 'images/winter.png';
		                  }else{
		                  	$seasonPicDesc ='Sun Shine';
		                    $seasonPicture = 'images/summer.png';
		                  }
		                  if($confirmarray[$sys['system_id']]){
		                  	$confirmPicDesc = 'Confirmed';
		                  	$confirmPicture = 'images/greenbutton-smallSmall.png';
		                  }else{
		                  	$confirmPicDesc = 'Pending Confirmation';
		                  	$confirmPicture = 'images/redbutton-smallSmall.png';
		                  }
		                ?>
		 				{{HTML::image($seasonPicture,$seasonPicDesc,array('width' => '100px','padding' => '10px'))}}
		 			</div>
		 			<div class="col-xs-8" style="text-shadow: 0px 0px 10px #000000; overflow: hidden">
		 				<big>
							{{$sys['building_name']}}
						</big>
						<br>
						<small>
							{{$sys['system_name']}}
						</small>
		 			</div>
		 			<!-- <div class="col-xs-2 pull-right">
		 				{{HTML::image($confirmPicture,'confirmation indicator',array('title' => $confirmPicDesc, 'width' => '30px', 'overflow' => 'scroll'))}}
		 			</div> -->
		 		</div>
		 		<?php $set_single_tour = false; ?>
	 		@endforeach
		</div>

	 	<!-- Here the user can change the setting for the individual systems -->
	 	<div class="col-xs-12 col-sm-6" style="margin-top: 10px;">
		 	{{ Form::open(array('route'=>['settings.updatecustomerbuildings',$customer_id])) }}
		 	<div id="change-some-0" class="col-xs-12 block_emc container-fluid collapse in" style="text-align: center; text-shadow: 0px 0px 10px #000000; margin-top: 10px;">
			 		<div class="col-xs-12  " >
			 			<div class="col-xs-12">
				 			<div class="col-xs-12" style="margin-top: 80px; margin-bottom: 80px;" >
								<p>
									Select a Building to Change its Season Mode
								</p>
				 			</div>
			 			</div>
			 		</div>

				</div>
			<?php $set_single_tour = true; ?>
			@foreach ($sysarray as $sys )
		 		<div id="change-some-{{$sys['system_id']}}" class="col-xs-12 block_emc container-fluid collapse" style="text-align: center; text-shadow: 0px 0px 10px #000000; margin-top: 10px; overflow: hidden;">
			 		<div class="col-xs-12  " >
			 			<div class="col-xs-12 col-sm-4" style="padding:20px;">
			 				<?php
			                  if($sys['season_mode_code'] == '0'){
			                    $seasonPicDesc ='Snow Flakes';
			                    $seasonPicture = 'images/winter.png';
			                    $pictureTitle = 'Winter Mode';
			                  }else{
			                  	$seasonPicDesc ='Sun Shine';
			                    $seasonPicture = 'images/summer.png';
			                    $pictureTitle = 'Summer Mode';
			                  }
			                  if($confirmarray[$sys['system_id']]){
			                  	$confirmPicDesc = 'Confirmed';
			                  	$confirmPicture = 'images/greenbutton-smallSmall.png';
			                  }else{
			                  	$confirmPicDesc = 'Pending Confirmation';
			                  	$confirmPicture = 'images/redbutton-smallSmall.png';
			                  }
			                ?>
			 				{{HTML::image($seasonPicture,$seasonPicDesc,array('width' => '100px','padding' => '10px', 'title' => $pictureTitle))}}
			 			</div>

			 			<div class="col-xs-12 col-sm-8" style="margin-top: 40px;" >
							<big>
								{{$sys['building_name']}}
							</big>
							<br>
							<small>
								{{$sys['system_name']}}
							</small>
			 			</div>
			 		</div>
		 			<div class="@if($set_single_tour) single_season_change_tour @endif col-xs-12">
			 			<div class="col-xs-12" style="margin-top: 20px; margin-bottom: 10px;">
			 				{{ Form::select('Season_'.$sys['system_id'], array('0' => 'Winter', '1' => 'Summer'), $sys['season_mode_code'], array("class" => "form-control", "style" => "color:black; cursor:pointer;", "id"=> "Season_".$sys['system_id'])) }}
			 			</div>
		 			</div>

					<button name='Seasons' type="button" class="@if($set_single_tour) single_season_change_tour @endif btn btn-primary col-xs-6" data-toggle="modal" data-target="#single-change"  data-backdrop="static" onclick="update_button(this)">
						Save
					</button>
					<button class="btn btn-primary col-xs-6">
						Cancel
					</button>
				</div>
				<?php $set_single_tour = false; ?>
			@endforeach
		</div>
	</div>
	<div class="modal fade modal-setback" id="single-change" role="dialog" aria-labelledby="single-changeLabel" aria-hidden="true" style="color: black;">
		<div class="single_season_change_tour modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #FF2222; color: white;">
					<h3 class="emc-modal-title" id="single-changeLabel" style="text-align:center">
						<big>Warning!</big>
					</h3>
				</div>
				<div class="modal-body row">
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding: 10pt;">
						<big><i>If the steam or hot water pipe(s) are open for repair,<br>Do not change to Winter.</i></big>
					</div>
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding: 10pt;">
						<big><i>If you are unsure of the status of the pipes, <br> please select <b>Cancel</b>.</i></big>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding-top: 20px">
						<button  class="single_season_change_tour btn btn-danger pull-right" type="submit" name='Seasons' style="margin-right: 11px; margin-bottom: 20px;" >
							Submit
						</button>
						<button data-dismiss="modal" class="single_season_change_tour btn btn-primary pull-right" type="button" style="margin-right: 11px; margin-bottom: 20px;" >
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-setback" id="all-change" role="dialog" aria-labelledby="all-changeLabel" aria-hidden="true" style="color: black;">
		<div class="multi_season_change_tour modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #FF2222; color: white;">
					<h3 class="emc-modal-title" id="all-changeLabel" style="text-align:center">
						<big>Warning!</big>
					</h3>
				</div>
				<div class="modal-body row">
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding: 10pt;">
						<big><i>If the steam or hot water pipe(s) in <br><u><b>any of your buildings</b></u> are open for repair,<br>do not change to Winter.</i></big>
					</div>
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding: 10pt;">
						<big><i>If you are unsure of the status of the pipes, <br> please select <b>Cancel</b>.</i></big>
					</div>
				</div>
				<div class="modal-footer">
					<div class="col-xs-10 col-xs-offset-1" style="text-align: center; padding-top: 20px">
						<button  class="multi_season_change_tour btn btn-danger pull-right" type="submit" name='AllSeasons' style="margin-right: 11px; margin-bottom: 20px;" >
							Submit
						</button>
						<button data-dismiss="modal" class="multi_season_change_tour btn btn-primary pull-right" type="button" style="margin-right: 11px; margin-bottom: 20px;">
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{ Form::close() }}
@endif

	<script type="text/javascript">
		@foreach ($sysarray as $sys)
			$("#system-select-{{$sys['system_id']}}").click(function(){
				$("#change-some-0:visible").collapse('hide');
				@foreach ($sysarray as $subsys)
					$("#change-some-{{$subsys['system_id']}}:visible").collapse('hide');
				@endforeach
				$("#change-some-{{$sys['system_id']}}:hidden").collapse('show');
			});
		@endforeach
		$(document).ready(function(){
		    $('.pmd-floating-action').prepend('\
		      <a href="javascript:void(0);" class="multi_season_change_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
		        Change All Systems\
		      </a>\
		      <a href="javascript:void(0);" class="single_season_change_tour pmd-floating-action-btn btn btn-sm btn-default tour-floating-options">\
		        Change Single Systems\
		      </a>'
		    );
		});
	</script>
@stop
