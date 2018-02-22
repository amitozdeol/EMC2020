<!-- Layout for Pages that need Highcharts -->
<?
  //Cache control
  //Add last modified date of a file to the URL, as get parameter.
  $import_scripts = ['/js/vendor/Highcharts/highcharts.js', '/js/vendor/Highcharts/highcharts-more.js', '/js/vendor/Highcharts/modules/data.js', '/js/vendor/Highcharts/modules/drilldown.js'];
  foreach ($import_scripts as $value) {
    $filename = public_path().$value;
    if (file_exists($filename)) {
        $appendDate = substr($value."?v=".filemtime($filename), 1);
        echo HTML::script($appendDate);
    }

  }
?>

	@if(Request::is('building/*/system/*/detail/1'))
		@foreach ($categories as $item)
			<!-- Have @ifs to populate the correct div with the correct chart data -->
			@if (isset($tempChart) & $item->subgroup_name == "Temperatures")
		    	<script type="text/javascript">$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}').highcharts( {!! $tempChart !!} )</script>
		    	@if (isset($zoneTempCharts)) <!-- If there are separate zones to show -->
		    		<?php $i = 1; ?>
		    		@foreach($zoneTempCharts as $chart => $chartData) <!-- Encode each separate zone chart and place it into the corresponding div prepared for it in detail.blade.php -->
		    			<?php $zoneTempChart = json_encode($chartData, JSON_NUMERIC_CHECK); ?>
		    			<script type="text/javascript">$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}-{!!$i!!}').highcharts( {!! $zoneTempChart !!} )</script>
		    			<?php $i++; ?>
		    		@endforeach
		    	@endif
		    @elseif (isset($humChart) & $item->subgroup_name == "Humidity")
		    	<script type="text/javascript">$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}').highcharts( {!! $humChart !!} )</script>
		      	@if (isset($zoneHumCharts)) <!-- If there are separate zones to show -->
		    		<?php $i = 1; ?>
		    		@foreach($zoneHumCharts as $chart => $chartData) <!-- Encode each separate zone chart and place it into the corresponding div prepared for it in detail.blade.php -->
		    			<?php $zoneHumChart = json_encode($chartData, JSON_NUMERIC_CHECK); ?>
		    			<script type="text/javascript">$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}-{!!$i!!}').highcharts( {!! $zoneHumChart !!} )</script>
		    			<?php $i++; ?>
		    		@endforeach
		    	@endif
		    @elseif (!isset($tempChart) & $item->subgroup_name == "Temperatures")
		    	<script type="text/javascript">
		    		$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}').html( "No data to show." );
		    	</script>
		    @elseif (!isset($humChart) & $item->subgroup_name == "Humidity")
		    	<script type="text/javascript">
		    		$('#{!!$item->subgroup_number!!}-{!!$item->itemnumber!!}').html( "No data to show." );
		    	</script>
		    @endif
	    @endforeach
	@endif

	@if(Request::is('reports/*/*'))
		@if (isset($tempChart))
	    	<script type="text/javascript">$('#chart').highcharts( {!! $tempChart !!} )</script>
		    @if (isset($zoneTempCharts)) <!-- If there are separate zones to show -->
		    	<?php $i = 1; ?>
		    	@foreach($zoneTempCharts as $chart => $chartData) <!-- Encode each separate zone chart and place it into the corresponding div prepared for it in detail.blade.php -->
		    		<?php $zoneTempChart = json_encode($chartData, JSON_NUMERIC_CHECK); ?>
		    		<script type="text/javascript">$('#chart-{!!$i+1!!}').highcharts( {!! $zoneTempChart !!} )</script>
		    		<?php $i++; ?>
		    	@endforeach
		    @endif
		@endif
		@if (isset($humChart))
	    	<script type="text/javascript">$('#charthum').highcharts( {!! $humChart !!} )</script>
		    @if (isset($zoneHumCharts)) <!-- If there are separate zones to show -->
		    	<?php $i = 1; ?>
		    	@foreach($zoneHumCharts as $chart => $chartData) <!-- Encode each separate zone chart and place it into the corresponding div prepared for it in detail.blade.php -->
		    		<?php $zoneHumChart = json_encode($chartData, JSON_NUMERIC_CHECK); ?>
		    		<script type="text/javascript">$('#charthum-{!!$i+1!!}').highcharts( {!! $zoneHumChart !!} )</script>
		    		<?php $i++; ?>
		    	@endforeach
		    @endif
		@endif
	@endif
