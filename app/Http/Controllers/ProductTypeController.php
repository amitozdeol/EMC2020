<?php

namespace App\Http\Controllers;

use App\DeviceType;
use App\ProductType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ProductTypeController extends Controller
{

  /**
   * Display a listing of the product types.
   *
   * @return Redirect::route("admin.producttype.index"): The list view of all the current product types in the product_types table
   */
    public function index()
    {
        $products = ProductType::orderby('mode', 'ASC')
        ->orderby('product_type', 'ASC')
        ->orderby('name', 'ASC')
        ->get();

        $modes = ProductType::groupby('mode')
        ->orderby('mode')
        ->get();

        $deviceTypes = DeviceType::all();

        $device_types = [];
        foreach ($deviceTypes as $type) {
            $device_types[$type->command] = $type->command;
        }

        $product_types = ProductType::groupby('product_type')
        ->orderby('product_type')
        ->get();

        $product_ids = [];
        $product_numbers = [];


        foreach ($products as $product) {
            $product_ids[$product->product_id]  = $product->product_id;
        }
        // var_dump($product_ids);
        foreach ($products as $product) {
            $product_numbers[$product->partnumber]  = $product->partnumber;
        }
        // var_dump($product_numbers);

        $hardwarebus = [
        'ZigBee'      => 'ZigBee',
        'Wired'       => 'Wired',
        'BACNetMSTP'  => 'BACNetMSTP',
        'BACNetEther' => 'BACNetEther'
        ];

        return view('producttypes.list')
        ->with('products', $products)
        ->with('modes', $modes)
        ->with('product_types', $product_types)
        ->with('device_types', $device_types)
        ->with('product_ids', $product_ids)
        ->with('product_numbers', $product_numbers)
        ->with('hardwarebus', $hardwarebus);
    }


  /**
   * Store a newly created resource in storage.
   *
   * @return Redirect::route("admin.producttype.index"): The list view of all the current product types in the product_types table
   */
    public function store()
    {
        $product = new ProductType();

        foreach (Input::except('_token', '_method') as $key => $value) {
              $product->$key = $value;
        }

        $product->save();

        return Redirect::route("admin.producttype.index");
    }


  /**
   * Update the specified product type in storage.
   *
   * @param  int  $pid: The recnum of the product type being updated.
   * @return Redirect::route("admin.producttype.index"): The list view of all the current product types in the product_types table
   */
    public function update($pid)
    {
        $product = ProductType::find($pid);
    
        foreach (Input::except('_token', '_method') as $key => $value) {
              $product->$key = $value;
        }

        $product->save();

        return Redirect::route("admin.producttype.index");
    }


  /**
   * Remove the specified product type from storage.
   *
   * @param  int  $pid: The recnum of the product type being removed.
   * @return Redirect::route("admin.producttype.index"): The list view of all the current product types in the product_types table
   */
    public function destroy($pid)
    {
        $product = ProductType::find($pid);
        $product->delete();

        return Redirect::route("admin.producttype.index");
    }
}
