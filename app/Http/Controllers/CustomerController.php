<?php

class CustomerController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {
        $customers = Customer::all();

        return view('customers.list')->with('customers', $customers);
    }
}
