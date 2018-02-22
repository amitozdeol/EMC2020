<?php
/*
*	SettingsController
*
*	Used for: setting season_mode across mutliple systems
*/
class SettingsController extends BaseController {
	/*
	* @fcn 		customerbuildings()
	*
	* @desc 	Gathers data for the "global settings" page (customerbuildings.blade.php)
	*
	* @param 	customer_id: the id associated with systems to be evaluated
	*
	* @return 	return the customerbuildings view, with the appropriate systems' data
	*/
	public function customerbuildings($customer_id){

		$systems = System::select('systems.*','buildings.name AS building_name')
			->where('customers.id',$customer_id)
			->whereNull('systems.deleted_at')
			->whereNull('buildings.deleted_at')
			->join('buildings','buildings.id','=','systems.building_id')
			->join('customers','customers.id','=','buildings.customer_id')
			->orderBy('building_name','asc')
			->get();

		$sysarray = array();
		$confirmarray = array();
		foreach ($systems as $key => $value) {
			$sysarray[$key]['system_id'] = $value->id;
			$sysarray[$key]['building_name'] = $value->building_name;
			$sysarray[$key]['system_name'] = $value->name;
			$sysarray[$key]['season_mode_code'] = $value->season_mode;
			$sysarray[$key]['season_mode'] = $value->season_mode?"SUMMER":"WINTER";

			$confirmarray[$value->id] = RemoteTask::ConfirmSent($value->id,'var.2020_command.config.txt');
		}


		$data = array('customer_id' => $customer_id );
		return View::make('settings.customerbuildings',$data)
			->with('sysarray',$sysarray)
			->with('confirmarray',$confirmarray);
	
	}
	/*
	* @fcn 		updatecustomerbuildings()
	*
	* @desc 	Uses the input from the customerbuildings.blade view to make updates to multiple system's configurations
	*
	* @param 	customer_id: the id associated with systems to be evaluated
	*
	* @return 	return the customerbuildings view, with the appropriate systems' data
	*/
	public function updatecustomerbuildings($customer_id){
		$sysarray = array();
		$confirmarray = array();
		
		$input = Input::except('_token');

		if(isset($input['Seasons'])){
			/*Set each systems season individually*/
			foreach ($input as $key => $value) {
				if(strpos($key,'Season_') !== false){
					$updatesystem = System::find(str_replace("Season_", "", $key));
					if($updatesystem->season_mode != $value){
						$updatesystem->season_mode = $value;
						$updatesystem->save();
						SystemController::DeployConfig($updatesystem->id,false);
						SystemLog::info($updatesystem->id, "User [".Auth::user()->email."] changed systems season mode to [".$value."]", '25');
					}
				}
			}
			Session::flash('success', 'Systems\' Seasons Saved');
		}

		/*Gather all systems relevant to the customer_id*/
		$systems = System::select('systems.*','buildings.name AS building_name')
			->where('customers.id',$customer_id)
			->whereNull('systems.deleted_at')
			->whereNull('buildings.deleted_at')
			->join('buildings','buildings.id','=','systems.building_id')
			->join('customers','customers.id','=','buildings.customer_id')
			->orderBy('building_name','asc')
			->get();

		
		
		if(isset($input['AllSeasons'])){
			/*Set all systemsto the same season*/
			$sysidarray = array();
			foreach ($systems as $key => $value) {
				$sysidarray[] = $value->id;
			}

			$updatesystem = System::whereIn('id',$sysidarray)->get();
			foreach ($updatesystem as $systems) {
				if($systems->season_mode != $input['All_Season']){
					$systems->season_mode = $input['All_Season'];
					$systems->save();
					SystemController::DeployConfig($systems->id,false);
					SystemLog::info($systems->id, "User [".Auth::user()->email."] changed systems season mode to [".$input['All_Season']."]", '25');
				}
			}
			$seasonstring = $input['All_Season']?"Summer":"Winter";
			Session::flash('success', 'All Systems\' Season Mode Changed to '.$seasonstring.'');

			$systems = System::select('systems.*','buildings.name AS building_name')
				->whereIn('systems.id',$sysidarray)
				->join('buildings','buildings.id','=','systems.building_id')
				->orderBy('building_name','asc')
				->get();

		}

		foreach ($systems as $key => $value) {
			$sysarray[$key]['system_id'] = $value->id;
			$sysarray[$key]['building_name'] = $value->building_name;
			$sysarray[$key]['system_name'] = $value->name;
			$sysarray[$key]['season_mode_code'] = $value->season_mode;
			$sysarray[$key]['season_mode'] = $value->season_mode?"SUMMER":"WINTER";

			$confirmarray[$value->id] = RemoteTask::ConfirmSent($value->id,'var.2020_command.config.txt');
		}



		$data = array('customer_id' => $customer_id );
		return View::make('settings.customerbuildings',$data)
			->with('sysarray',$sysarray)
			->with('confirmarray',$confirmarray);
	}
}