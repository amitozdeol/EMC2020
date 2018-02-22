<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\Http\Controllers\Controller;
use App\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;



class AdminCustomerController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {

        $data['customers'] = Customer::where('id', '>', 0)->get();

        return view('admin.customer.index', $data);
    }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
    public function create()
    {

        return view('admin.customer.create');
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function store()
    {

        $customer = new Customer();

        foreach (Input::except('_token') as $field => $value) {
            $customer->$field = $value;
        }

        if ($customer->save()) {
            SystemLog::info(0, 'New customer #'.$customer->id.'('.$customer->name.') was created by user #'.Auth::user()->id.'('.Auth::user()->email.')', 21);
            Session::flash('message', 'The new customer account for <strong>'.$customer->name.'</strong> was created successfully.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to create new customer #'.$customer->id.'('.$customer->name.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 22);
            Session::flash('message', 'There was an error creating the new customer account for <strong>'.$customer->name.'</strong>.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return redirect(URL::route('admin.customer.index'));
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function edit($id)
    {

        $data['customer'] = Customer::find($id);

        return view('admin.customer.edit', $data);
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function update($id)
    {

        $customer = Customer::find($id);

        foreach (Input::except('_token', '_method') as $field => $value) {
            $customer->$field = $value;
        }

        if ($customer->save()) {
            SystemLog::info(0, 'Customer #'.$customer->id.'('.$customer->name.') was updated by user #'.Auth::user()->id.'('.Auth::user()->email.')', 21);
            Session::flash('message', 'The customer account for <strong>'.$customer->name.'</strong> was update successfully.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to update the customer #'.$customer->id.'('.$customer->name.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 22);
            Session::flash('message', 'There was an error updating the customer account for <strong>'.$customer->name.'</strong>.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return redirect(URL::route('admin.customer.index'));
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function destroy($id)
    {

        $customer = Customer::find($id);

        if ($customer->delete()) {
            SystemLog::info(0, 'Customer #'.$customer->id.'('.$customer->name.') was deleted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 21);
            Session::flash('message', 'The customer account for <strong>'.$customer->name.'</strong> was successfully removed.');
            Session::flash('alert-class', 'alert-success alert-dismissable');
        } else {
            SystemLog::error(0, 'Failed to delete customer #'.$customer->id.'('.$customer->name.') as attempted by user #'.Auth::user()->id.'('.Auth::user()->email.')', 22);
            Session::flash('message', 'There was an error removing the customer account for <strong>'.$customer->name.'</strong>.');
            Session::flash('alert-class', 'alert-danger alert-dismissable');
        }

        return Redirect::route('admin.customer.index');
    }
}
