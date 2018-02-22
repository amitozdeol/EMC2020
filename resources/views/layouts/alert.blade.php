@if(Session::get('error'))
  <div class="container">
    <div class="alert alert-danger alert-dismissible" role="alert" style="position: relative;">
      {!!Session::get('error')!!}
      <button type="button" class="close" data-dismiss="alert" style="position: absolute; top: 48%; right: 25px; line-height: 0;">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
    </div>
  </div>
@endif
@if(Session::get('success'))
  <div class="container">
    <div class="alert alert-success alert-dismissible" role="alert" style="position: relative;">
      {!!Session::get('success')!!}
      <button type="button" class="close" data-dismiss="alert" style="position: absolute; top: 48%; right: 25px; line-height: 0;">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
    </div>
  </div>
@endif