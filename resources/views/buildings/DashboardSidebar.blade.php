<style>
	/********************************* Sidebar **********************************/
	#wrapper {
		/* margin-top: -17px; */
		padding-left: 0;
		-webkit-transition: all .2s ease;
		-moz-transition: all .2s ease;
		-o-transition: all .2s ease;
		transition ll .2s ease;
	}

	#sidebar-wrapper {
		z-index: 10;
		position: fixed;
		left: 225px;
		width: 0;
		top: 0;
		height: 100%;
		margin-left: -225px;
		overflow-y: auto;
		overflow-x: hidden;
		background: #004a74;
		border-right: 1px solid #e7e7e7;
		-webkit-transition: all .2s ease;
		-moz-transition: all .2s ease;
		-o-transition: all .2s ease;
		transition: all .2s ease;
	}
	@media (min-width: 992px) {
		#wrapper {
			padding-left: 225px !important;
		}
		#wrapper #sidebar-wrapper {
			width: 225px !important;
		}
	}

	/* .navbar-returns.affix{
		position: absolute;
	} */
	.temp-sidebar-click {
		display: none;
	}

	#sidebar .user-profile-icon {
		position: absolute;
		top: 0;
		width: 225px;
		height: 50px;
		text-indent: 0;
		line-height: 45px;
		background: #333;
		color: white;
		text-align: center;
	}

	#sidebar .user-profile-icon .sidebar-icon {
		width: 45px;
		height: 50px;
		font-size: 14px;
		padding: 0 2px;
		display: list-item;
		margin-right: 10px;
		color: #fff;
		float: left;
		background: black;
		line-height: 55px;
	}
	/* Centering the icons in sidebar */
	#sidebar .user-profile-icon .sidebar-icon:before {
		content: '';
		display: inline-block;
		height: 100%;
		vertical-align: middle;
		width: 0px;
	}

	#sidebar-wrapper .sidebar-nav {
		position: relative;
		top: 50px;
		width: 225px;
		font-size: 14px;
		margin: 0;
		padding: 0;
		list-style: none;
	}

	#sidebar-wrapper .sidebar-nav li {
		text-indent: 0;
		line-height: 45px;
	}

	#sidebar-wrapper .sidebar-nav li a{
		display: block;
		text-decoration: none;
		color: #fff;
		cursor: pointer;
		position: relative;
	}
	#sidebar-wrapper .sidebar-nav li a:hover, #sidebar-wrapper .sidebar-nav li .sidebar-highlight{
		font-size: 16px;
		text-shadow: black 0.8px 0.8px 1em;
		background-color: #364a5d !important;
		border: 1px solid #2a374b;
		transition: all ease 0.3s;
		border-left: 4px solid #07a9e1;
	}
	#sidebar-wrapper .sidebar-nav li a .sidebar-icon{
		width: 45px;
		height: 45px;
		font-size: 14px;
		padding: 0 2px;
		display: inline-block;
		text-indent: 7px;
		margin-right: 10px;
		color: #fff;
	}
	/* Centering the icons in sidebar */
	#sidebar-wrapper .sidebar-nav li a .sidebar-icon:before {
		content: '';
		display: inline-block;
		height: 100%;
		vertical-align: middle;
		width: 0px;
	}
	#weather{
		display: block;
	}
	@media (max-width: 992px) {
		#wrapper #sidebar-wrapper, .temp-sidebar-click {
			width: 45px;
		}
		.temp-sidebar-click {
			z-index: 1001;
			position: fixed;
			left: 225px;
			height: 100%;
			display: block;
			cursor: pointer;
			margin-left: -225px;
			overflow-y: auto;
		}
		#wrapper #sidebar-wrapper #sidebar #sidemenu li ul {
			position: fixed;
			left: 45px;
			margin-top: -45px;
			z-index: 1000;
			width: 200px;
			height: 0;
		}
		#weather{
			display: none;
		}
		#wrapper{
			padding-left: 47px;
		}
	}

	.sidebar-nav li:first-child a { background: #92bce0 !important; }
	.sidebar-nav li:nth-child(2) a { background: #6aa3d5 !important; }
	.sidebar-nav li:nth-child(3) a { background: #428bca !important; }
	.sidebar-nav li:nth-child(4) a { background: #3071a9 !important; }
	.sidebar-nav li:nth-child(5) a { background: #245682 !important; }
	.sidebar-nav li:nth-child(6) a { background: #193c5a !important; }
	.sidebar-nav li:nth-child(7) a { background: #10273b !important; }

	/********************************* Sidebar **********************************/
</style>
<?php
	if (!isset($routePrefix)) {
		if( preg_match('/touchscreen/', Route::currentRouteName()) ) {
			/* Touchscreen UI */
			$routePrefix = 'touchscreen';
		}else{
			$routePrefix = 'building';
		}
	}
?>
<!-- for android and ios when the top navbar is hidden -->
<div id="sidebar-wrapper" style="@if($routePrefix != 'touchscreen') top:112px; @endif">
	<div class="temp-sidebar-click" onclick="expandSidebar(this)"></div>
	<aside id="sidebar">
			<div class="user-profile-icon">
				<span id = "backbutton-sidebar-icon" class="sidebar-icon" onclick="compressSidebar(this)"><i class="fa fa-user-circle-o fa-2x"></i></span>
				<span class="sidebar-title">My Dashboard</span>
			</div>
			<hr style="position: absolute; top: 48px; height: 2px; width: 100%; background: #ffd900; z-index: 1000; margin: 0; border: 0;">
			<ul id="sidemenu" class="sidebar-nav">
				@if($routePrefix != 'touchscreen')
					<li>
						<a href="{!!URL::route($routePrefix.'.system', [$thisBldg->id, $thisSystem->id])!!}" class="sidebar-item wrap" >
							<span class="sidebar-icon"><i class="fa fa-home"></i></span>
							<span class="sidebar-title">Dashboard</span>
						</a>
					</li>
				@endif
				@foreach($items as $item)
					@if($item->chart_type == NULL)
						<!-- SYSTEMS PAGE ITEMS -->
						<?php
							$this_icon = "fa-building-o";
							switch ($item->label) {
								case 'Alarms':
									$this_icon = "fa-bell";
									break;
								case 'Control History':
								case 'Control Events':
									$this_icon = "fa-history";
									break;
								case 'Zone Control':
								case 'Zone Status':
								case 'EMC Zone Status':
									$this_icon = "fa-gear";
									break;
								case 'Operations':
								case 'Reports':
								case 'Monitors':
									$this_icon = "fa-area-chart";
									break;
								case 'System Status':
									$this_icon = "fa-building-o";
									break;
								default:
									$this_icon = "fa-building-o";
									break;
							}
						?>
						@if($item->label == 'Operations' )<!--replace operations with reports-->
							<li>
								<a href="{!!URL::route('reports.index', [$thisBldg->id, $thisSystem->id])!!}?routeprefix={!!$routePrefix!!}" class="sidebar-item wrap" >
									<span class="sidebar-icon"><i class="fa {!!$this_icon!!}"></i></span>
									<span class="sidebar-title">Reports</span>
								</a>
							</li>
						@else
							<li>
								<a href="{!!URL::route($routePrefix.'.dashboard', [$thisBldg->id, $thisSystem->id, $item->id])!!}" class="sidebar-item wrap">
									<span class="sidebar-icon"><i class="fa {!!$this_icon!!}"></i></span>
									<span class="sidebar-title">{!! $item->label !!}</span>
								</a>
							</li>
						@endif
					@endif
				@endforeach
				@if(Auth::user()->auth_role >= 4 && $routePrefix != 'touchscreen')
					<li>
						<a href="{!!URL::route('setpointmapping.index', [$thisBldg->id, $thisSystem->id])!!}" class="sidebar-item wrap">
							<span class="sidebar-icon"><i class="fa fa-tasks"></i></span>
							<span class="sidebar-title">Setpoints</span>
						</a>
					</li>
				@endif
			</ul>
	</aside>
</div>
<script type="text/javascript">
	var sidebar_wrapper = document.getElementById("sidebar-wrapper");
	var sidebar_items = document.querySelectorAll('.sidebar-item');
	var AndroidOrIos = checkUserAgent("EMC2020/ios") || checkUserAgent("EMC2020/1.0/Android");
	var top_margin = sidebar_wrapper.style.top;
	top_margin = parseInt(top_margin.substr(0, top_margin.indexOf('px')));

	// highlight the active sidebar item
	sidebar_items.forEach(function(elem){
		if (elem.pathname === window.location.pathname) {
			addClass(elem, "sidebar-highlight");
		}else{
			removeClass(elem, "sidebar-highlight");
		}
	});
	// Check if page is loading in android or IOS app
	if(AndroidOrIos){
		top_margin = 52;
		sidebar_wrapper.style.top = top_margin+"px";
	}else{
		// If page is not loading in EMC touchscreen
		if('{!!$routePrefix !!}'!= "touchscreen"){
			//change sidebar vertical position when scrolling
			$(window).scroll(function() {
				var top = top_margin - $(window).scrollTop();
				if ($(window).scrollTop() <= top_margin) {
					$("#sidebar-wrapper").css({"top":top+"px"});
				}else{
					$("#sidebar-wrapper").css({"top":"60px"});	//60px is height of top navbar
				}
			}).scroll();
		}
	}

	function expandSidebar(obj){
		$("#wrapper").css({"padding-left":225});
		$('#sidebar-wrapper').width(225);
		$(obj).hide();
		//show weather
		$("#weather").show();
		//replace user icon to back icon
		$(".user-profile-icon > .sidebar-icon > i").removeClass('fa-user-circle-o').addClass('fa-arrow-circle-o-left').css("cursor", "pointer");
	}

	function compressSidebar(thisobj){
		if($(thisobj).find('.fa-arrow-circle-o-left').length !== 0){   //backbutton exist
			var backbutton = $(thisobj).children('i').eq(0);
			$("#sidebar-wrapper").width(45);
			$("#wrapper").css({'padding-left': 47});
			$(".temp-sidebar-click").show();
			//hide weather
			$("#weather").hide();
			//replace back icon to user icon
			backbutton.removeClass('fa-arrow-circle-o-left').addClass('fa-user-circle-o').css("cursor", "auto");
		}
	}

</script>
