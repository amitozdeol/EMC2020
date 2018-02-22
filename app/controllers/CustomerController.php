<?php

class CustomerController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $customers = Customer::all();

    return View::make('customers.list')->with('customers', $customers);
  }



}
