<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{

    use DispatchesCommands, ValidatesRequests;

    public $customer_buildings_for_navbar;
    public $us_state_abbrevs_names;

  /**
   * Set up global variables for controllers and share them across all templates
   *
   */
    public function __construct()
    {
        if (Auth::user()) {
            $authorized_buildings = DB::table('buildings')
            ->distinct()
            ->join('building_managers', 'building_managers.building_id', '=', 'buildings.id')
            ->where('building_managers.user_id', Auth::user()->id)
            ->select('buildings.id', 'buildings.name')
            ->get();

            $authorized_groups = DB::table('buildings')
            ->distinct()
            ->join('building_group_members', 'building_group_members.building_id', '=', 'buildings.id')
            ->join('building_group_managers', 'building_group_managers.building_group_id', '=', 'building_group_members.building_group_id')
            ->where('building_group_managers.user_id', Auth::user()->id)
            ->select('buildings.id', 'buildings.name')
            ->get();
            $customer_buildings_for_navbar = (object)array_unique(array_merge((array)$authorized_buildings, (array)$authorized_groups), SORT_REGULAR);

            $this->customer_buildings_for_navbar = $customer_buildings_for_navbar;
            View::share('customer_buildings_for_navbar', $customer_buildings_for_navbar);

            $us_state_abbrevs_names = [''=>'Choose a State','AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming',];
            $this->us_state_abbrevs_names = $us_state_abbrevs_names;
            View::share('us_state_abbrevs_names', $us_state_abbrevs_names);

            $route_group = (preg_match('/touchscreen/', Route::currentRouteName()))?'touchscreen':'building';
            View::share('route_group', $route_group);

            $help_id = substr(Route::currentRouteName(), 0, strpos(Route::currentRouteName(), '.'));
            View::share('help_id', $help_id);
        }
    }

  /**
   * Setup the layout used by the controller.
   *
   * @return void
   */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
}
