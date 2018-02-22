<div class="row">
	  <br>
	
	  <div class="col-sm-10 col-sm-offset-1">
	
	    <div class="col-xs-12 col-sm-4 col-md-3 pull-right">
	      {!! Form::submit('Submit', ['class'=>'btn btn-primary btn-sm col-xs-12']) !!}
	      	<div class="col-xs-12">
			<br><!-- Spacing for mobile -->
			</div>
	    </div>
	    <div class="col-xs-12 col-sm-4 col-md-3 pull-left">
      {!! HTML::link (URL::previous(), 'Cancel', ['class'=>'btn btn-default btn-sm col-xs-12'])!!}
	      <div class="col-xs-12"><br><!-- Spacing for mobile --></div>
	    </div>
	
	  </div>
	
	</div>