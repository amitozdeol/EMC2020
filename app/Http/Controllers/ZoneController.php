<?php

namespace App\Http\Controllers;

use App\Building;
use App\System;
use App\Zone;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class ZoneController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public static function index($bid, $sid)
    {
        $thisBldg = Building::find($bid);
        $thisSys = System::find($sid);

        $zones = Zone::where('system_id', $sid)
        ->get();



         return view('zonelabels.list')
            ->with('thisBldg', $thisBldg)
            ->with('sid', $sid)
            ->with('zones', $zones);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, $sid, $zid)
    {
        $zid = json_decode($zid);
        foreach ($zid as $value) {
                $zone = Zone::find($value->recnum);
                $zone->zonename = Input::get($value->recnum);
                $zone->temp_range = Input::get("Temp".$value->recnum);
                //dd($zone->toArray());
                $zone->save();
        }
        return Redirect::route("system.editSystem", [$id, $sid]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
