<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardAdminApiController extends AppBaseController
{
    public function statistical(Request $request){
    	$subscriptions 	= Subscription::count();
    	$purchases 		= Order::count();
    	$clinics 		= Clinic::count();
    	$visiters 		= 0;
    	$statistical 	= [
    		'visiters' 	=> $visiters,
    		'clinics' 	=> $clinics,
    		'purchases' => $purchases,
    		'subscriptions' => $subscriptions
    	];
    	return $this->sendResponse($statistical, 'Dashboard retrieved successfully');
    }

    public function getDataPurchases(Request $request){
    	if($request->input('day')){

            $input = $request->all();
            $data =  $this->getDataOneDay($input,2);

        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $data =  $this->getDataRandDay($input,2);

        }else{
            return $this->sendError('Item not found');
        }

        // return $data;
        return $this->sendResponse($data, 'Data retrieved successfully');

    }
    public function getDataVisitors(Request $request){
    	if($request->input('day')){

            $input = $request->all();
            $data =  $this->getDataOneDay($input,1);

        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $data =  $this->getDataRandDay($input,1);

        }else{
            return $this->sendError('Item not found');
        }
        
        return $this->sendResponse($data, 'Data retrieved successfully');

    }
    public function getDataSubcriptions(Request $request){
    	if($request->input('day')){

            $input = $request->all();
            $data =  $this->getDataOneDay($input,3);

        }elseif($request->input('startDay') && $request->input('endDay')){

            $input = $request->all();
            $data =  $this->getDataRandDay($input,3);

        }else{
            return $this->sendError('Item not found');
        }
        
        return $this->sendResponse($data, 'Data retrieved successfully');

    }
    private function getDataOneDay($attributes,$state){
    	/* 
    		State to discriminate get data: 
			if 	State = 1 -> get data Visiters
				State = 2 -> get data Purchases
				State = 3 -> get data Subscriptions
    	*/
		$day 		= Carbon::parse($attributes['day'])->format('Y-m-d');
		if($state 	== 1){
			$data 	= 0;
		}else if($state == 2){
			$data 	= Order::whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               				->selectRaw('HOUR(created_at) as hour,count(id) as times')
               				->get();
		}else{
			$data 	= Subscription::whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               				->selectRaw('HOUR(created_at) as hour,count(id) as times')
               				->get();
		}
		return $data;
    }
    private function getDataRandDay($attributes,$state){
    	// dd($state);
    	/* 
    		State to discriminate get data: 
			if 	State = 1 -> get data Visiters
				State = 2 -> get data Purchases
				State = 3 -> get data Subscriptions
    	*/
		$startDay 	= Carbon::parse($attributes['startDay'])->format('Y-m-d');

        $endDay 	= Carbon::parse($attributes['endDay'])->format('Y-m-d');

		if($state 	== 1){
			$data 	= 0;
		}else if($state == 2){
			$data 	= Order::whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                			->groupBy(DB::raw('MONTH(created_at)'))
                			->groupBy(DB::raw('DAY(created_at)'))
               				->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, count(id) as times')
               				->get();
		}else{
			$data 	= Subscription::whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                			->groupBy(DB::raw('MONTH(created_at)'))
                			->groupBy(DB::raw('DAY(created_at)'))
               				->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, count(id) as times')
               				->get();
		}
		return $data;
    }
}
