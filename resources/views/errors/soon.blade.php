@extends('layouts.error')

@section('message')

    <h1>Coming Soon</h1>
    <h3>This page includes new features that we&rsquo;re still working on.</h3>

    <br>

    <p>
      We&rsquo;re always working on new features and functionality for the EMC system,
      and unfortunately this one isn&rsquo;t quite ready yet.
    </p>
    <p>
      <a href="{!! URL::previous() !!}">previous page</a>.
    </p>

@stop