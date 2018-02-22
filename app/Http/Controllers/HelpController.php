<?php

class HelpController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public static function index()
    {
         /*Displays example on page for help section. Set false or remove entirely before releasing to production.*/
        $example = false;

        return View::make('help.list')
        ->with('example', $example);
    }
}
