<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('welcome');
});
Route::get('/invitation-register',function(){
	return view('auth.invitationRegister');
});
Route::get('/home', 'HomeController@index');

Auth::routes();
Route::get('/testmail', function(){
	// dd(Mail);
  \Mail::raw('email.checkout.checkout', function($message){
    $message->to('nguyenytran06@gmail.com')->subject('Subject');
  });
});
// Test performence query
use App\Repositories\DeviceRepository;
Route::get('/test', function(DeviceRepository $deviceRepo){
	// dd(\Carbon\Carbon::parse('2016-8')->format('Y/m'));
	// $devices = \App\Models\Device::where('user_id', 2)->with('user', 'company')->get();
	// $devices = $deviceRepo->getDevicesWithShareDevices(2);
	// return view('test')->with('devices', $devices);
	// dd(\Carbon\Carbon::parse('2017-07-27')->toDateString());
	$startDay = \Carbon\Carbon::parse('2016-08-03')->toDateString();
	$endDay = \Carbon\Carbon::parse('2017-08-03')->toDateString();

	// $transactions = \App\Models\PaymentTransaction::select([
	// 					DB::raw('sum(amount) as amount'),
	// 					DB::raw('DATE(created_at) as day')
	// 				])
	// 				->whereDate('created_at', '>=', $startDay)
	// 				->whereDate('created_at', '<=', $endDay)
	// 				->groupBy('day')
	// 				->get();
	
	$transactions = \App\Models\PaymentTransaction::select([
						DB::raw('sum(amount) as amount'),
						DB::raw('MONTH(created_at) month'),
						DB::raw('YEAR(created_at) year'),
					])
					->whereDate('created_at', '>=', $startDay)
					->whereDate('created_at', '<=', $endDay)
					->groupBy('year', 'month')
					->get();
	foreach ($transactions as $key => $value) {
		$transactions[$key]['time'] = \Carbon\Carbon::parse($value->year . '-' . $value->month)->format('Y/m');
	}

    // $transactions = \App\Models\PaymentTransaction::whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
    //         ->groupBy(DB::raw('YEAR(created_at)'))
    //         ->groupBy(DB::raw('MONTH(created_at)'))
    //        ->selectRaw('MONTH(created_at) as month,YEAR(created_at) as year, sum(amount) as amount')
    //        ->get();

               dd($transactions->toArray());
               return view('test')->with('devices', $transactions->toArray());
});


Route::get('fences', function() {
	$fences = \App\Models\Fence::where('user_id', 2)->orderBy('id', 'DESC')->with('fence_data', 'pet_fence.pet.image')->select('id', 'name')->get();
    // foreach ($fences as $key => $fence) {
    //     $fence->fence_data;
    //     $fence->pet_fence;
    //     if($fence->pet_fence){
    //         foreach ($fence->pet_fence as $key => $pet_fence) {
    //             $pet_fence->pet;
    //         }
    //     }
    //     $fence->user;
    // }

    return view('test')->with('fences', $fences->toArray());
});