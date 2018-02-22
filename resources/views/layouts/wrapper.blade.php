<!DOCTYPE HTML>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>{!!isset($title) ? $title : 'EMC20/20'!!}</title>
		<meta name="description" content="The latest in building automation technology. Sense and control your building remotely." />
		<meta name="ROBOTS" content="INDEX, FOLLOW" />
		<meta name="GOOGLEBOT" content="INDEX, FOLLOW" />
		<meta name="google-site-verification" content="Jf4spJV5UiNXEtR3U_Lx00BV0EUqbZDxXGW3oHcTZqk" />
		@if(isset($removeviewport))
			@if($removeviewport==1)
			@else
				<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			@endif
		@else
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		@endif
		<link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
		<?
			//Cache control
			//Add last modified date of a file to the URL, as get parameter.
			$import_css = ['/css/bootstrap.min.css', '/css/building/mytabs.css', '/css/main.css', '/css/bootstrap-tour.min.css'];    //add file name
			foreach ($import_css as $value) {
				$filename = public_path().$value;
				if (file_exists($filename)) {
						$appendDate = substr($value."?v=".filemtime($filename), 1);
						echo HTML::style($appendDate);
				}
			}
		?>
		<!-- Speed up fontawesome loading by 200ms by loading from cloudfare global cache. -->
		{!!HTML::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css')!!}
		@if(isset($admincss))
			@if($admincss=="admin")
				<?
					//Cache control
					//Add last modified date of a file to the URL, as get parameter.
					$import_css = ['/css/admin.css'];    //add file name
					foreach ($import_css as $value) {
						$filename = public_path().$value;
						if (file_exists($filename)) {
								$appendDate = substr($value."?v=".filemtime($filename), 1);
								echo HTML::style($appendDate);
						}
					}
				?>
			@endif
		@endif
		<?
			//Cache control - Scripts
			//Add last modified date of a file to the URL, as get parameter.
			$import_scripts = ['/js/vendor/jquery-3.2.1.min.js', '/js/vendor/jquery-ui-1.10.4.custom.min.js', '/js/vendor/bootstrap.min.js', '/js/main.js', '/js/bootstrap-tour.min.js'];
			foreach ($import_scripts as $value) {
				$filename = public_path().$value;
				if (file_exists($filename)) {
						$appendDate = substr($value."?v=".filemtime($filename), 1);
						echo HTML::script($appendDate);
				}

			}
		?>
	</head>

	<body>
		<!-- Touchscreen -->
		@if(!isset($routePrefix))
			<? $routePrefix = 'building'; ?>
		@endif

		@include('layouts.navbar')
		@if(!empty($thisSystem->id))
			<div id='wrapper'>	<!-- ID is important for sidebar.  -->
				@include('layouts.alert')
				@yield('content')
			</div>
		@else
			<div id="wrapper" style="margin-left: auto; margin-right: auto; width: 90%; padding-top: 5px;">
				@include('layouts.alert')
				@yield('content')
			</div>
		@endif

		<!-- If user is logged in and is not touchscreen, then show... -->
		@if(Auth::user())
			@if(Request::is('EMC/*') || $routePrefix == "touchscreen")
				<!--  Show left side buttons for touchscreen scrolling -->
				<script>
					// Create scroll buttons below the sidebar
					var sidebar = document.getElementById('sidebar-wrapper');
					sidebar.className = 'touchscreen auto-refresh nav-away-spinner';
					sidebar.removeAttribute("style");

					$(function() {
						$("html").tapScroll({
							movePercent: 33,
							upContent: '<span class="glyphicon glyphicon-chevron-up"></span>',
							downContent: '<span class="glyphicon glyphicon-chevron-down"></span>',
							top: true,
							bottom: true,
							refresh: true,
							topContent: '<span class="glyphicon glyphicon-chevron-up"></span>',
							bottomContent: '<span class="glyphicon glyphicon-chevron-down"></span>',
							refreshContent: '<span class="glyphicon glyphicon-refresh"></span>'
						})
					});
				</script>
			@else
				@include('layouts.help-modal') <!--  Show Help modal -->
				<?
					//Cache control - Scripts
					//Add last modified date of a file to the URL, as get parameter.
					$import_scripts = ['/js/TourData.js']; // Sincludes all the help doc for help tour
					foreach ($import_scripts as $value) {
						$filename = public_path().$value;
						if (file_exists($filename)) {
								$appendDate = substr($value."?v=".filemtime($filename), 1);
								echo HTML::script($appendDate);
						}

					}
				?>
				<!-- Floating Action Button-->
				<div class="menu pmd-floating-action"  role="navigation" style="z-index: 100;">
					<a id = "floatingbutton" class="helpbutton" title="Help" href="javascript:void(0);">
						<i class="fa fa-question" style="font-size: 25px; margin: 27%;" data-role='end'></i>
					</a>
				</div>

				<script>
					// Add space at the bottom of body for help button
					document.getElementsByTagName("BODY")[0].style.marginBottom = "100px";
					$(document).ready(function(){
						// Help button
						$(".pmd-floating-action-btn").on("click", function(e){
								var classname_grouped = this.className.split(' ')[0];
								var myDATA = gettourdata(classname_grouped);
								var tour = new Tour({
									storage : false
								});
								tour.addSteps(myDATA);
								// Initialize the tour
								tour.init();
								// Start the tour
								tour.start();
						});

						var count_clicks = 0;
						$('#floatingbutton').on('click', function(e){
							if (count_clicks == 0) { //show
								$('.pmd-floating-action .pmd-floating-action-btn').show().css({"opacity": "1", "-ms-transform": "none", "transform": "none", "position": "relative", "bottom": "auto", "visibility": "visible"});
								count_clicks = 1;
							}else{ //hide
								$('.pmd-floating-action .pmd-floating-action-btn').css({"opacity": "0", "-ms-transform": "none", "transform": "none", "position": "relative", "bottom": "auto", "visibility": "hidden"}).hide();
								count_clicks = 0;
							}
						});
						//hide the list of help buttons
						$(document).on('click', function(e){
							var target = e.target;
							if (!$(target).is('#floatingbutton') && !$(target).parents().is('#floatingbutton')) {
								$('.pmd-floating-action .pmd-floating-action-btn').css({"opacity": "0", "-ms-transform": "none", "transform": "none", "position": "relative", "bottom": "auto", "visibility": "hidden"}).hide();
							count_clicks = 0;
							}
						});
					});
				</script>

				<style>
					/* Propeller css for Floating Action Button*/
					.pmd-floating-action { bottom: 10%; position: fixed;  margin:1em;  right: 0;}
					.pmd-floating-action-btn { display:block; position: relative; transition: all .2s ease-in-out;}
					.pmd-floating-action-btn:last-child:before { font-size: 14px; bottom: 25%;}
					.pmd-floating-action-btn:active, .pmd-floating-action-btn:focus, .pmd-floating-action-btn:hover {box-shadow: 0px 5px 11px -2px rgba(0, 0, 0, 0.18), 0px 4px 12px -7px rgba(0, 0, 0, 0.15);}
					.pmd-floating-action-btn:not(:last-child){ opacity: 0; -ms-transform: translateY(20px) scale(0.3); transform: translateY(20px) scale(0.3); margin-bottom:15px; margin-left:8px; position:absolute; bottom:0;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(1) { transition-delay: 50ms;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(2) { transition-delay: 100ms;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(3) { transition-delay: 150ms;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(4) { transition-delay: 200ms;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(5) { transition-delay: 250ms;}
					.pmd-floating-action-btn:not(:last-child):nth-last-child(6) { transition-delay: 300ms;}
					.menu--floating--open .pmd-floating-action-btn { opacity: 1; -ms-transform: none; transform: none; position:relative; bottom:auto;}
					.pmd-floating-action-btn.btn:hover{ overflow:visible;}
				</style>
			@endif
		@else
			<!-- Don't show footer on IOS devices -->
			@if(strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) <= 0 )
				{!!View::make('home.footer')!!}
			@endif
		@endif
	</body>
</html>
