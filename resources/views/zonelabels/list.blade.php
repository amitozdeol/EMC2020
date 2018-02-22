<div class="col-xs-12">
	@if($zones->isEmpty())

	    <div class="device-title">
	        <div class="row" id="no-zones" style="text-align:center">
	            <h4 class="col-xs-11" style="font-weight: bold; margin-left: 1%" >
	                  This system has no zones.
	                  <br>
	                  <br>
	                  Increase the number of system zones in the System Configuration tab
	            </h4>
	        </div>
	    </div>
	@else
		<div class="col-xs-12 seamless_block_emc device-block row-padding" style="color:black;  width: 100%; padding-bottom: 20px;">
			{!!Form::open(['route'=>['zone.update', $thisBldg->id, $sid, $zones], "method" => "put"])!!}
				<div style="overflow: hidden;">
					@foreach($zones as $zone)
					    <div class="col-xs-12 col-sm-6 row-padding" style="padding-top: 15px;">
								<label for="start-date" class="pull-left" style="margin-top: 5px;">Zone #{!!$zone->zone!!}:&nbsp;</label>
				    		<div style="display: inline-block;">
				    			{!!Form::text($zone->recnum, $zone->zonename, ["class" => "form-control form-small form-group"])!!}
				    		</div>
								<!-- select a range for temperature -->
								<div style="display: inline-block; position: relative;">
									<select name="Temp{!!$zone->recnum!!}" class="form-control" required>
										<option disabled selected value>Temperature Range</option>
									  <option value="low" {!!$zone->temp_range == "low" ? "selected":""!!}>Low(<50)</option>
									  <option value="medium" {!!$zone->temp_range == "medium" ? "selected":""!!}>Medium(50-100)</option>
									  <option value="high" {!!$zone->temp_range == "high" ? "selected":""!!}>High(>100)</option>
									</select>
								</div>
			        </div>
					@endforeach
				</div>
				<div class="pull-right col-sm-4" >
					{!!Form::submit('Save',["class" => "btn btn-primary btn-block btn-sm col-xs-12"])!!}
				</div>
			{!!Form::close()!!}
		</div>
	@endif
</div>
