<!--forgot-password-->
@extends('layouts.wrapper')

@section('content')

<div class="col-xs-12 shortest-height" style="background-color: #419CB0;"></div>

{{ Form::open(array('url' => 'reset')) }}
	<form>
	<div class="block_emc" style="min-height: 400px; background-color: #419CB0;">
       <div class="col-sm-12 col-md-6 col-md-offset-3 form-group block-emc">
    	    <b>Please enter the email address associated with your account in the form below. </b>
    	    <br>
    	    We'll send you an email with instructions for resetting your password.
    	    <br>
    	    <br>
    	    {{ Form::label('recover-email', 'Email', ['class' => 'forget_password_tour']) }}
    	    {{ Form::email('recover-email', Input::old('email'), ['class' => 'form-control forget_password_tour', 'autocomplete' => 'on', 'required']) }}
    	    <br>
    	    <div class="row">
				<button type="submit" class="col-xs-4 col-xs-offset-7 col-sm-2 col-sm-offset-9 col-md-3 col-md-offset-8 btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
	</form>
{{ Form::close() }}


<script>
  // add list of help menu buttons for each page
  $(document).ready(function(){
    $('.pmd-floating-action').prepend('\
    	<a href="javascript:void(0);" class="forget_password_tour pmd-floating-action-btn btn btn-sm btn-default">\
		  Forget Email\
		</a>'
    );
  });
</script>
@stop
