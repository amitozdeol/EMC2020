<?
	//Cache control - Scripts
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
{!!$categories!!}
@if(isset($categories))
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
      @endif
    @endforeach
@endif

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





<script>
  var body = document.getElementsByTagName('body')[0];
  body.className = 'touchscreen auto-refresh nav-away-spinner';
  body.removeAttribute("style");

  $(function() {
    var url_str = '{!!Request::url()!!}';
    var url_prefix = url_str.split("page");
    url_prefix = url_prefix[0];
    var url_suffix = (url_str.indexOf('page') > 0)?'systemShutdown':'/systemShutdown';
    $("html").tapScroll({
      movePercent: 33,
      upContent: '<span class="glyphicon glyphicon-chevron-up"></span>',
      downContent: '<span class="glyphicon glyphicon-chevron-down"></span>',
      top: true,
      bottom: true,
      refresh: true,
      shutdown: true,
      topContent: '<span class="glyphicon glyphicon-chevron-up"></span>',
      bottomContent: '<span class="glyphicon glyphicon-chevron-down"></span>',
      refreshContent: '<span class="glyphicon glyphicon-refresh"></span>',
      shutdownContent: '<span class="glyphicon glyphicon-off"></span>',
      shutdown_url: url_prefix + url_suffix
    });
  });
</script>
