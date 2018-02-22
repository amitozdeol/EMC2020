<?php $title="Support"; ?>

@extends('layouts.wrapper_public')

@section('content')
<style>
  body {
    font-family: 'Roboto', sans-serif;
    font-weight:400;
  }
  *:focus {
    outline: none;
  }
  h2.tv-faqs-title-tm {
    color:#333;
    font-weight:400;
    font-size:42px;
    margin-bottom:30px;
  }
  h2.tv-faqs-title-tm i {
    font-size:32px;
    margin-right:10px;
  }
  ul.tv-faqs-filter-tm.affix {
    width:360px;
    top:33px;
  }
  ul.tv-faqs-filter-tm {
    padding:0;
    margin:0;
    list-style:none;
  }
  ul.tv-faqs-filter-tm li {
    margin-bottom:10px;
    display:block;
    position:relative;
    padding-right:15px;
  }
  ul.tv-faqs-filter-tm li a {
    color:#219be2 !important;
  }
  ul.tv-faqs-filter-tm li a i {
    position:absolute;
    right:-12px;
    top:20%;
    color:#219be2 !important;
  }
  ul.tv-faqs-filter-tm li a em {
    position:absolute;
    right:-12px;
    top:20%;
    color:#219be2 !important;
  }
  article.tv-faqs-container-tm {
    border-bottom:1px solid #ccc;
    padding-bottom:20px;
    margin-bottom:30px;
  }
  article.tv-faqs-container-tm h3 {
    font-weight:400;
    padding:0;
    margin:0 0 20px 0;
    color:#219be2;
    font-size:24px;
  }
  article.tv-faqs-container-tm p {
    color:#777;
    font-size:16px;
  }
  .anchor:before {
    content:"";
    display:block;
    height:60px; /* fixed header height*/
    margin:-60px 0 0; /* negative fixed header height */
  }
  @media screen and (max-width: 1280px){
    .padding_top{
      padding-top: 60px;
    }
  }
</style>

<div style="background: #222; padding-top: 50px;">
  <div style="background: white; overflow: hidden;">
    <div class="col-xs-12 shortest-height"></div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="tv-faqs-title-tm"><i class="fa fa-comments" aria-hidden="true"></i> FAQ.</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <ul class="tv-faqs-filter-tm">
            <li>
              <a class="smoothscroll" href="#Faq-1">
                Will my existing BACnet devices work with the EMC<sup>20/20</sup>?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a class="smoothscroll" href="#Faq-2">
                Will my existing Inovonics network work with the EMC<sup>20/20</sup>?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a class="smoothscroll" href="#Faq-3">
                Can each device have its own setpoints and alarm levels?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a class="smoothscroll" href="#Faq-4">
                What personal devices can I use to access and control my system?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a class="smoothscroll" href="#Faq-5">
                Will I be required a 24/7 internet connection to use EMC<sup>20/20</sup> in our building?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a class="smoothscroll" href="#Faq-6">
                Something Else?
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-md-8 padding_top">
          <article class="tv-faqs-container-tm anchor" id="Faq-1">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                Will my existing BACnet devices work with the EMC<sup>20/20</sup>?
              </h3>
            </div>
            <div class="tv-faqs-answer-tm">
              <p>The EMC<sup>20/20</sup> can interface with <i>BACnet&nbsp;MSTP</i> devices, with future updates enabling further BACnet integration.</p>
            </div>
          </article>
          <!-- end faqs item -->
          <article class="tv-faqs-container-tm anchor" id="Faq-2">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                Will my existing Inovonics network work with the EMC<sup>20/20</sup>?
              </h3>
            </div>
            <div class="tv-faqs-answer-tm">
              <p>
                The EMC<sup>20/20</sup> is equipped to communicate with the very latest generation of wireless Inovonics devices, with backward integration coming soon.
              </p>
            </div>
          </article>
          <!-- end faqs item -->
          <article class="tv-faqs-container-tm anchor" id="Faq-3">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                Can each device have its own setpoints and alarm levels?
              </h3>
            </div>
            <div class="tv-faqs-answer-tm">
              <p>
                Yes! With the EMC<sup>20/20</sup>, you’ll have the ability to <b>individually customized</b> each of your input devices to vote when you want, with high and low alarm levels specific to each individual sensor.
              </p>
            </div>
          </article>
          <!-- end faqs item -->
          <article class="tv-faqs-container-tm anchor" id="Faq-4">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                What personal devices can I use to access and control my system?
              </h3>
            </div>
            <div class="tv-faqs-answer-tm">
              <p>
                Access your system from anywhere, using your web-enabled smart-phone, tablet, or laptop. Our system has been optimized to help you check on your system from wherever you are.
              </p>
            </div>
          </article>
          <!-- end faqs item -->
          <article class="tv-faqs-container-tm anchor" id="Faq-5">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                Will I be required an internet connection to use the EMC<sup>20/20</sup> in my building?
              </h3>
            </div>
            <div class="tv-faqs-answer-tm">
              <p>
                It is <i>strongly recommended</i> each system be equipped with a reliable internet connection to ensure optimal reporting and control.
                <br>
                If your building currently lacks a dedicated internet conneciton, you may <a href="http://www.dslreports.com/search">click here</a> to find more about your nearest internet service providers or <i>speak with one of distributors about dedicated connection options</i>.
              </p>
            </div>
          </article>
          <!-- end faqs item -->
          <article class="tv-faqs-container-tm anchor" id="Faq-6">
            <div class="tv-faqs-title-answer-tm">
              <h3>
                Something Else?
              </h3>
              <h3>
                <i>We're <b>happy</b> to answer any other questions you have</i>
              </h3>
              <h3>
                Call or Email Us Today
              </h3>
            </div>
          </article>
        </div>

      </div>
    </div>
    
  </div>

</div>
<script>
  $('.smoothscroll').on('click', function(event){
      event.preventDefault();

      $('html, body').animate({
          scrollTop: $( $.attr(this, 'href') ).offset().top
      }, 500);
  });
  $("#nav_product").attr("href", "{{URL::to('/').'#products'}}");
  $("#nav_services").attr("href", "{{URL::to('/').'#services'}}");
  $("#nav_aboutus").attr("href", "{{URL::to('/').'#aboutus'}}");


</script>

@stop
