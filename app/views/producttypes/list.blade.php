<?php
  $admincss='admin';
  $title="Product Type";
?>

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
<div class="jumbotron">
  <h2>Products Type</h2>
</div>

  <div class="row">
    <br>
    <br>
    <br>
    <button class="btn btn-sm btn-primary pull-right" type="button" data-toggle="modal" data-target="#newProductModal">Add New Product Type</button>
  </div>

  <!--New Product Modal to add product type to product_type table -->
  <div class="modal fade" id="newProductModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {{Form::open(['route'=>['admin.producttype.store'], 'method'=>'post', "id" => "form-add"])}}
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="productModalLabel">Add Product Type</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-xs-12" style="margin-bottom:10px">
                  <div class="col-xs-4 col-sm-2"><label for="product_id">ID</label>
                    {{Form::text('product_id',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "product_id-add", "title" => "Enter distinct ID"])}}
                  </div>
                  <div class="col-xs-4 col-sm-4"><label for="name">Name</label>
                    {{Form::text('name',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "name-add", "title" => "Enter this product's name"])}}
                  </div>
                  <div class="col-xs-4 col-sm-6"><label for="function">Function</label>
                    {{Form::text('function',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "function-add", "title" => "Enter this product's function"])}}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="manufacturer">Manufacturer</label>
                    {{Form::text('manufacturer',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "manufacturer-add", "title" => "Enter this product's manufacturer name"])}}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="partnumber">Part Number</label>
                    {{Form::text('partnumber',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "partnumber-add", "title" => "Enter part number of this product"])}}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="commands">Commands</label>
                    {{Form::select('commands',$device_types,1,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "command-select-add", "style" =>"display: none", "disabled", "title" => "Choose the command type associated with this product"])}}
                    {{Form::text('commands',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "command-text-add", "title" => "Enter command types associated with this product seperated by commas"])}}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="hardwarebus">Hardware Bus</label>
                    {{Form::select('hardwarebus',$hardwarebus,'ZigBee',["class" => "form-control hardwarebus-select-add hardwarebus", "style"=> "height:34px; font-size:14px;", "id" => "hardwarebus-add", "title" => "Choose this product's hardware bus type"])}}
                  </div>
                  <div class="col-xs-6 col-sm-4"><label for="direct">Direct</label>
                    {{Form::text('direct',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "direct-add", "title" => ""])}}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="reporttime">Report Time</label>
                    {{Form::text('reporttime',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "reporttime-add", "title" => "Enter this product's report time"])}}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="powerlevel">Power Level</label>
                    {{Form::text('powerlevel',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "powerlevel-add", "title" => "Enter this product's power level"])}}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="mode">Mode</label>
                    {{Form::select('mode',["Input" => "Input","Output" => "Output"],"Input",["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "mode-add", "title" => "Specify this product's mode"])}}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="product_type">Product Type</label>
                    {{Form::text('product_type',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "product_type-add", "title" => "Enter the type of this product"])}}
                  </div>
                  <div class="col-xs-6 col-sm-6"><label for="auxcontroller">Auxillary Controller</label>
                    {{Form::text('auxcontroller',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "auxcontroller-add", "title" => "Enter the, if any, auxillary controller of this product"])}}
                  </div>
                  <div class="col-xs-6 col-sm-2"><label for="price">Price</label>
                    {{Form::text('price',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "price-add", "title" => "Enter the price of this product"])}}
                  </div>
                </div>
                <div class="col-xs-12">
                  <div class="col-xs-12" style="margin-bottom:10px"><label for="comments">Comments</label>
                    {{Form::text('comments',null,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "comments-add", "title" => "Include additional comments"])}}
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer" style="text-align:center">
          <div class="col-xs-12" style="text-align: left; color: red">
            <p id="alert-add"></p>
          </div>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              {{Form::submit('Save',["class" => "btn btn-primary btn-sm"])}}
          </div>
          {{Form::close()}}
        </div>
      </div>
  </div>

  <div>
    @foreach($modes as $mode)
      <h2 class="row">{{$mode->mode}}</h2>
      <div class="scrollleftright" style="overflow: auto;">
        <div class="direction_left">
          <div class="left">
          	<svg version="1.1" class="arrow first" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
            	<polygon fill="rgba(0, 0, 0, 0.69)" points="59.476,30.991 8.39,30.991 23.921,15.46 22.493,14.032 4.524,32 22.493,49.969 23.921,48.543
            		8.389,33.009 59.476,33.009 	"/>
            </svg>
        	</div>
        </div>
        <div class="direction_right">
          <div class="right">
		        <svg version="1.1" class="arrow second" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
            	<polygon fill="rgba(0, 0, 0, 0.69)" points="59.476,30.991 8.39,30.991 23.921,15.46 22.493,14.032 4.524,32 22.493,49.969 23.921,48.543
            		8.389,33.009 59.476,33.009 	"/>
            </svg>
        	</div>
        </div>
        <!-- Table for displaying current values found in product_types table -->
        <table class="responsive-table table table-hover">
      @foreach($product_types as $type)
          @if($type->mode == $mode->mode)
            <thead>
              <th scope="row" colspan="15" style="background-color: #000; color: white; text-align: center;">{{$type->product_type}}</th>
              <tr class="theadrow"style="font-size: 13px;">
                  <th scope="col" style="vertical-align:top">Name</th>
                  <th scope="col" style="vertical-align:top">ID</th>
                  <th scope="col" style="vertical-align:top">Function</th>
                  <th scope="col" style="vertical-align:top">Manufacturer</th>
                  <th scope="col" style="vertical-align:top">Part #</th>
                  <th scope="col" style="vertical-align:top">Commands</th>
                  <th scope="col" style="vertical-align:top">Hardware Bus</th>
                  <th scope="col" style="vertical-align:top">Direct</th>
                  <th scope="col" style="vertical-align:top">Report Time</th>
                  <th scope="col" style="vertical-align:top">Power Level</th>
                  <th scope="col" style="vertical-align:top">Auxilary Controller</th>
                  <th scope="col" style="vertical-align:top">Price</th>
                  <th scope="col" style="vertical-align:top">Comments</th>
                  <th scope="col" style="vertical-align:top"></th>
              </tr>
          </thead>
          <tbody>
          @foreach($products as $product)
            @if($product->product_type == $type->product_type)
                  <tr>
                      <th scope="row">{{$product->name}}</td>
                    <td data-title="ID">{{$product->product_id}}</td>
                    <td data-title="Function">{{$product->function}}</td>
                    <td data-title="Manufacturer">{{$product->manufacturer}}</td>
                    <td data-title="Part #">{{$product->partnumber}}</td>
                    <td data-title="Commands">{{$product->commands}}</td>
                    <td data-title="Hardware Bus">{{$product->hardwarebus}}</td>
                    <td data-title="Direct">{{$product->direct}}</td>
                    <td data-title="Report Time">{{$product->reporttime}}</td>
                    <td data-title="Power Level">{{$product->powerlevel}}</td>
                    <td data-title="Auxilary Controller">{{$product->auxcontroller}}</td>
                    <td data-title="Price">{{$product->price}}</td>
                    <td data-title="Comments">{{$product->comments}}</td>
                    <td><button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#{{str_replace(' ','',$product->product_id)}}Modal">Edit</button></td>
                  </tr>

              <!-- Edit product type modal for altering current entry in product_types table-->
              <div class="modal fade" id="{{str_replace(' ','',$product->product_id)}}Modal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      {{Form::open(['route'=>['admin.producttype.update', $product->recnum], 'method'=>'put', "id" => "form-".$product->product_id])}}
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="productModalLabel">Edit Product Type</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-xs-12" style="margin-bottom:10px">
                              <div class="col-xs-6 col-sm-2"><label for="product_id">ID</label>
                                {{Form::text('product_id',$product->product_id,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "product_id-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-4"><label for="name">Name</label>
                                {{Form::text('name',$product->name,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "name-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-6"><label for="function">Function</label>
                                {{Form::text('function',$product->function,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "function-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-4"><label for="manufacturer">Manufacturer</label>
                                {{Form::text('manufacturer',$product->manufacturer,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "manufacturer-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-4"><label for="partnumber">Part Number</label>
                                {{Form::text('partnumber',$product->partnumber,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "partnumber-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-4 edit-product"><label for="commands">Commands</label>
                                @if($product->hardwarebus != 'Wired')
                                  {{Form::select('commands',$device_types,$product->commands,["class" => "form-control commands", "style"=> "height:34px; font-size:14px;", "id" => "command-select-edit-".$product->product_id, "style" =>"display: none", "disabled", "title" => "Chosoe the command type associated with this product"])}}
                                  {{Form::text('commands',$product->commands,["class" => "form-control commands", "style"=> "height:34px; font-size:14px;", "id" => "command-text-edit-".$product->product_id, "title" => ""])}}
                                @else
                                  {{Form::select('commands',$device_types,$product->commands,["class" => "form-control commands", "style"=> "height:34px; font-size:14px;", "id" => "command-select-edit-".$product->product_id, "title" => "Chosoe the command type associated with this product"])}}
                                  {{Form::text('commands',$product->commands,["class" => "form-control commands", "style"=> "height:34px; font-size:14px;", "id" => "command-text-edit-".$product->product_id, "style" =>"display: none", "disabled", "title" => ""])}}
                                @endif
                              </div>
                              <div class="col-xs-6 col-sm-4"><label for="hardwarebus">Hardware Bus</label>
                                {{Form::select('hardwarebus',$hardwarebus,$product->hardwarebus,["class" => "form-control hardwarebus-select-edit hardwarebus", "style"=> "height:34px; font-size:14px;", "id" => "hardwarebus-select-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-4"><label for="direct">Direct</label>
                                {{Form::text('direct',$product->direct,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "direct-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-2"><label for="reporttime">Report Time</label>
                                {{Form::text('reporttime',$product->reporttime,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "reporttime-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-2"><label for="powerlevel">Power Level</label>
                                {{Form::text('powerlevel',$product->powerlevel,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "powerlevel-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-2"><label for="mode">Mode</label>
                                {{Form::select('mode',["Input" => "Input","Output" => "Output"],$product->mode,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "mode-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-2"><label for="product_type">Product Type</label>
                                {{Form::text('product_type',$product->product_type,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "product_type-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-6"><label for="auxcontroller">Auxillary Controller</label>
                                {{Form::text('auxcontroller',$product->auxcontroller,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "auxcontroller-edit-".$product->product_id, "title" => ""])}}
                              </div>
                              <div class="col-xs-6 col-sm-2"><label for="price">Price</label>
                                {{Form::text('price',$product->price,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "price-edit-".$product->product_id, "title" => ""])}}
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <div class="col-xs-12" style="margin-bottom:10px"><label for="comments">Comments</label>
                                {{Form::text('comments',$product->comments,["class" => "form-control", "style"=> "height:34px; font-size:14px;", "id" => "comments-edit-".$product->product_id, "title" => ""])}}
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="modal-footer" style="text-align:center">
                        <div class="col-xs-12" style="text-align:left; color:red">
                          <p id="alert-edit-{{$product->product_id}}"></p>
                        </div>
                        <button type="button" class="btn btn-default btn-sm pull-right" data-dismiss="modal">Close</button>
                          {{Form::submit('Save',["class" => "btn btn-primary btn-sm pull-right"])}}
                          {{Form::close()}}
                          {{Form::open(['route'=>['admin.producttype.destroy', $product->recnum], 'method'=>'DELETE'])}}
                                {{Form::submit('Delete', ['class'=>'btn btn-danger btn-sm js-confirm pull-left', 'data-confirm'=>"Are you sure you want to delete this product?\n\nThis cannot be undone."])}}
                          {{Form::close()}}
                      </div>
                    </div>
                  </div>
              </div>
              @endif
          @endforeach
        </tbody>
        @endif
      @endforeach
      </table>
    </div>
    @endforeach
</div>

<script>
  var hardwarebus = {{json_encode($hardwarebus)}};
  var device_types = {{json_encode($device_types)}};
  var product_ids = {{json_encode($product_ids)}};
  var product_numbers = {{json_encode($product_numbers)}};

  var container = document.getElementsByClassName('scrollleftright');
  var table = document.getElementsByClassName('responsive-table');
  //hide/show the scroll button when page loads
  if (container[0].offsetWidth >= table[0].offsetWidth) {
    $('.direction_left').hide();
    $('.direction_right').hide();
  }else{
    $('.direction_left').show();
    $('.direction_right').show();
  }
  function sideScroll(element,direction,speed,distance,step){
      scrollAmount = 0;
      var slideTimer = setInterval(function(){
          if(direction == 'left'){
              element[0].scrollLeft -= step;
              element[1].scrollLeft -= step;
          } else {
              element[0].scrollLeft += step;
              element[1].scrollLeft += step;
          }
          scrollAmount += step;
          if(scrollAmount >= distance){
              window.clearInterval(slideTimer);
          }
      }, speed);
  }
  $('.direction_left').click(function() {
    sideScroll(container,'left',25,100,10);
  });
  $('.direction_right').click(function() {
    sideScroll(container,'right',25,100,10);
  });
  //hide/show the scroll button when page resize
  window.onresize = function() {
    if (container[0].offsetWidth >= table[0].offsetWidth) {
      $('.direction_left').hide();
      $('.direction_right').hide();
    }else{
      $('.direction_left').show();
      $('.direction_right').show();
    }
  }
</script>

@stop
