<!-- hide for ios app -->
@if(!strlen(strstr($_SERVER['HTTP_USER_AGENT'],"EMC2020/ios")) > 0)
  <!--  ==================================START CONTACT SECTION================================================-->
  <footer id="contact" class="col-xs-12">
      <div class="container">
          <div class="row">
              <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                  <h3>Let's Get In Touch!</h3>
                  <p>Contact us today to find out more about the quality services we offer, to bring you the most from your <span title="BAS">Building Automation System</span>.</p>
                  <div class="gap">
                  <div class="col-sm-6 col-xs-6">
                    <a href="tel:18443622020">
                      <span class="glyphicon glyphicon-earphone"></span>
                      1-844-EMC-2020</a>
                  </div>
                  <div class="col-sm-6 col-xs-6">
                    <a href="mailto:get@bootstrap.com">
                      <span class="glyphicon glyphicon-envelope"></span>
                      sales@eawelectro.com</a>
                  </div>
                  </div>

              </div>
          </div>
      </div>
  </footer>
  <!--  ==================================END CONTACT SECTION================================================-->
@endif
<style>
	/************** FOOTER ************/

	footer {
			background: #222;
			padding: 20px 0;
			position: relative;
			text-align: center;
	}

	footer h3 {
			font-size: 36px;
			margin-bottom: 30px;
			text-transform: capitalize;
			color: #fff;
			letter-spacing: 0.05em;
	}

	footer h3:after {
			content: "";
			display: block;
			width: 80px;
			height: 3px;
			margin: 20px auto 0;
			background: #419CB0;
	}

	footer p {
			font-size: 18px;
			line-height: 1.6;
			color: #adadad;
	}

	.gap {
			display: block;
			margin-top: 50px;
	}

	footer span.glyphicon {
			color: #adadad;
			font-size: 28px;
			display: block;
			margin: 0 auto 30px;
	}

	footer a {
			color: #adadad;
	}

	footer a:hover {
			color: #419CB0;
	}
	/************** FOOTER ************/

</style>
