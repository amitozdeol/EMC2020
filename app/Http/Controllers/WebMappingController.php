<?php

class WebMappingController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @param  int  $bid Building ID.
   * @param  int  $sid System ID.
   * @return Response
   */
    public static function index($bid, $sid)
    {
        $thisBldg = Building::find($bid);
        $thisSystem = System::find($sid);

        $dashItems = DashboardItem::where('system_id', $sid)
        ->orderby('parent_id', 'ASC')
        ->orderby('order', 'ASC')
        ->get();

        $dashItemModels = DashboardItem::where('system_id', 0)
        ->get();

        if (count($dashItems) == 0) {
            $lastItem = DashboardItem::orderby('id', 'DESC')
            ->select('id')
            ->first()
            ->toArray();

            foreach ($dashItemModels as $model) {
                  $defaultItem = new DashboardItem();
                foreach ($model->toArray() as $key => $value) {
                    if (in_array($key, ['label','order','parent_id','chart_type'])) {
                        $defaultItem->$key = $value;
                    }
                }
                $defaultItem->system_id = $sid;
                $defaultItem->save();
                unset($defaultItem);
            }

            unset($dashItems);

            $dashItems = DashboardItem::where('system_id', $sid)
            ->orderby('parent_id', 'ASC')
            ->orderby('order', 'ASC')
            ->get();

            $model_array = [];
            foreach ($dashItemModels as $model) {
                $model_array[$model->id] = $model->label;
            }

            $item_array = [];
            foreach ($dashItems as $item) {
                if ($item->chart_type != strtoupper($item->chart_type) or $item->chart_type == null) {
                    $item_array[$item->label] = $item->id;
                }
            }

            foreach ($dashItems as $item) {
                if ($item->parent_id != 0) {
                    $item->parent_id = $item_array[ $model_array[ $item->parent_id ] ];
                    $item->save();
                }
            }
        }

        $chartTypes = Chart::all();

        $chart_types = [];
        foreach ($chartTypes as $chart) {
            $chart_types[$chart->chart_type] = $chart->chart_type.' Chart';
        }

        $usedDashboardItems = [];
        $availableDashboardItems = [];
        $dashboardItems = [];
        $dashboardParents = [];
        $dashModels = [];
        $dashboardChildren = [];
        $onlyChildren = [];

        foreach ($dashItems as $parent) {
            $dashboardItems[$parent->id] = $parent;
            $usedDashboardItems[$parent->id] = $parent->chart_type;
            foreach ($dashItems as $child) {
                if ($child->parent_id == $parent->id) {
                    if (!array_key_exists($parent->id, $dashboardParents)) {
                        $dashboardParents[$parent->id] = "$child->id";
                    } else {
                        $dashboardParents[$parent->id] .= ' '.$child->id;
                    }
                }
            }
        }

        foreach ($dashboardParents as $parent => $kids) {
            $children = explode(' ', $kids);
            if (count($children) == 1) {
                $onlyChildren[$parent] = $children[0];
            }
        }

        foreach ($dashItems as $child) {
            if ($child->parent_id != 0) {
                $dashboardChildren[$child->id] = $dashboardItems[$child->parent_id];
            }
        }

        foreach ($dashItemModels as $model) {
            if (strtoupper($model->chart_type) == $model->chart_type and $model->chart_type != null and !in_array($model->chart_type, $usedDashboardItems)) {
                $dashModels[$model->id] = $model;
                $availableDashboardItems[$model->id] = $model->chart_type;
            }
        }

        if (empty($availableDashboardItems)) {
            $availableDashboardItems[0] = 'No Remaining Options';
        }

        return view('webmapping.list')
        ->with('thisBldg', $thisBldg)
        ->with('thisSystem', $thisSystem)
        ->with('bid', $bid)
        ->with('sid', $sid)
        ->with('dashboardItems', $dashboardItems)
        ->with('dashItemModels', $dashItemModels)
        ->with('dashModels', $dashModels)
        ->with('availableDashboardItems', $availableDashboardItems)
        ->with('chart_types', $chart_types)
        ->with('dashboardChildren', $dashboardChildren)
        ->with('onlyChildren', $onlyChildren)
        ->with('dashboardParents', $dashboardParents);
    }



  /**
   * Store a newly created resource in storage.
   *
   * @param  int  $bid Building ID.
   * @param  int  $sid System ID.
   * @return Response
   */
    public function store($bid, $sid)
    {
      
        $input = Input::except('_token');

        $predefined = DashboardItem::where('system_id', 0)
        ->get();
        $current = DashboardItem::where('system_id', $sid)
        ->where('parent_id', $input['parent_id'])
        ->get();


        $definedDashItems = [];
        foreach ($predefined as $item) {
            $definedDashItems[$item->id] = $item;
        }


        if (isset($input['item_type']) && $input['item_type'] === "predefined") { // Create predefined dashboard item

            $dashParent = new DashboardItem();
            $dashParent->label = $input['label'];
            $dashParent->order = count($current)+1;
            $dashParent->parent_id = $input['parent_id'];
            $dashParent->system_id = $sid;
            $dashParent->chart_type = null;
            $dashParent->save();

            $dashChild = new DashboardItem();
            $dashChild->label = $input['label'];
            $dashChild->order = 1;
            $dashChild->parent_id = $dashParent->id;
            $dashChild->system_id = $sid;
            $dashChild->chart_type = $definedDashItems[$input['id']]->chart_type;
            $dashChild->save();
        }
        if (!isset($input['item_type']) || $input['item_type'] === "generic") { // Create generic dashboard item
      
            $dashItem = new DashboardItem();
            $dashItem->label = $input['label'];
            $dashItem->order = count($current)+1;
            $dashItem->parent_id = $input['parent_id'];
            $dashItem->system_id = $sid;
            if ($input['dash_item_type'] == 'link') {
                $dashItem->chart_type = null;
            } else {
                $dashItem->chart_type = $input['dash_item_type'];
            }
            $dashItem->save();
        }

        return redirect(route('webmapping.index', [$bid, $sid]));
    }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $bid Building ID.
   * @param  int  $sid System ID.
   * @param  int  $id Dashboard Item ID.
   * @return Response
   */
    public function edit($bid, $sid, $id)
    {
        //Maybe use for editing chart parameters.
    }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $bid Building ID.
   * @param  int  $sid System ID.
   * @param  int  $id Dashboard Item ID.
   * @return Response
   */
    public function update($bid, $sid, $id)
    {
        //
        $data = Input::all();
    
        if ($id == -1) {
            foreach ($data as $index => $value) {
                if (strpos($index, 'Order')) {
                    //Update each dashboard Item.
                    $item_data = explode('-', $value);
                    $dashboard_item = DashboardItem::find($item_data[2]);
                    $dashboard_item->order = $item_data[0];
                    $dashboard_item->save();
                }
            }
        } else {
            $item = DashboardItem::Find($id);
            $item->label = $data['label'];
            $item->save();
        }

        return Redirect::route('webmapping.index', [$bid, $sid]);
    }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $bid Building ID.
   * @param  int  $sid System ID.
   * @param  int  $id Dashboard Item ID.
   * @return Response
   */
    public function destroy($bid, $sid, $id)
    {

        $dashboardItems = DashboardItem::where('system_id', $sid)
        ->orderby('id', 'ASC')
        ->get();

        $i = 0;
        $deleteGroup = [];
        $deleteGroup[$i++] = $id;
        foreach ($dashboardItems as $dashboardItem) {
            if (in_array($dashboardItem->parent_id, $deleteGroup)) {
                $deleteGroup[$i++] = $dashboardItem->id;
            }
        }

        foreach ($deleteGroup as $key => $value) {
            $item = DashboardItem::Find($value);
            $item->delete();
        }
    
        return Redirect::route('webmapping.index', [$bid, $sid]);
    }
}
