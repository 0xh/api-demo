<?php

namespace App\Repositories;

use App\Models\Device;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Mylibs\pointLocation;
use App\Events\CheckFence;
use App\Models\Notification;
use App\Models\ShareDevice;
use App\Models\User;
class DeviceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'imei',
        'name',
        'user_id',
        'battery',
        'phone',
        'mode',
        'company_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Device::class;
    }

    public function all($columns = ['*']){
        $devices = Device::orderBy('id', 'DESC')->with('user','company')->get();
        return $devices;
    }

    public function storeDeviceInfo($deviceId, $input){

        $device = Device::where('imei',$deviceId)->first();

        if (empty($device)) {
            return array(
                'success' => false,
                'message' => 'Device not found'
            );
        }

        // // Update device
        // $device->battery = $input['battery'];
        // $device->save();

        // // Update roll
        // $roll = $device->rolls()->create([
        //     'amount' => $input['roll'],
        //     'pet_id' => 1
        // ]);

        // // Update jump
        // $jump = $device->jumps()->create([
        //     'amount' => $input['jump'],
        //     'pet_id' => 1
        // ]);

        // // Update lat/lon
        // list($lat, $long) = explode('/', $input['lat_lon']);
        // $location = $device->locations()->create([
        //     'lat' => $lat,
        //     'long' => $long,
        //     'user_id' => $device->user->id
        // ]);

        // Create Job to check GEO fences

        if(!empty($device->pets)){ // check the device has pet or not? if has so continue, else break
            $locationDevice = $device->with(['locations'=>function($query){
                $query->select('id','device_id','long','lat')->latest()->first();
            }])->where('imei',$deviceId)->first();
            if(!empty($locationDevice->locations)){ /// check device has locations or not? if has so coutinue else break
                $location = $locationDevice->locations->first();
                $locationPet = array(
                    'long'=> $location->long,
                    'lat'=> $location->lat
                );
            }
            $pet = $device->pets->first();
            $pet_fence = $pet->pet_fence->first();
            if(!empty($pet_fence)){

                $fence = $pet_fence->fence;
                if(!empty($fence)){
                    $data = $fence->fence_data->toArray();
                }
            }
            if($locationPet && $data){
                $pointLocation = new pointLocation();
                if(!$pointLocation->pointInPolygon($locationPet, $data)){
                    $array = [
                        'content'=> $pet->name.' is out GEO fence: ' .$fence->name,
                        'status'=>false,
                        'receiver'=> $pet->user_id ,
                        'type' => 'danger'
                    ];
                    $notification = Notification::create($array);

                    event(new CheckFence($notification));
                }
            }
    
        }

        // Update ip,shake

        return array(
            'success' => true,
            'message' => 'Store successfully'
        );
    }
  
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->orderBy('id','DESC')->with('company')->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function create(array $attributes)   
    {
        // Have to skip presenter to get a model not some data
        $model = $this->model->newInstance($attributes);
        $model->battery = 100;
        $model->save();
        return $this->parserResult($model);

    }
    public function getDevicesOfUser($UserID){
        $devices = Device::orderBy('id','DESC')
                    ->where('user_id',$UserID)
                    ->get();
        foreach ($devices as $key => $device) {
            if($device->share){
                $device->share->user;
            }
            if($device->product){
                $devices[$key]['url'] = $device->product->getAvatar();
            }else{
                $devices[$key]['url'] = url('/img/default/default_image.png');
            }
        }

        return $devices;
    }
    public function assign(array $attributes){
        $devices = Device::whereIn('id',$attributes)->update(['mode' => 1]);
        return array(
            'success' => true,
            'message' => 'Assign successfully'
        );
    }
    public function unassignDevice($id){
        $devices = Device::find($id)->update(['mode' => 0]);
        return array(
            'success' => true,
            'message' => 'Unassign successfully'
        );
    }

    public function demoproduct($id){
        $de = Device::find($id);
        $pr = $de->product->images;
        dd($pr->toArray());
    }
    public function shareDevice($attributes){
        $del = ShareDevice::where('device_id',$attributes['device_id'])->delete();
        $shareDevice = ShareDevice::create($attributes);
        return $shareDevice;
    }
    public function getDevicesWithShareDevices($userID){
        $devices = Device::orderBy('id','DESC')
                    ->where('user_id',$userID)
                    ->get();
        foreach ($devices as $key => $device) {
            if($device->share){
                $device->share->user;
            }

            if($device->product){
                $devices[$key]['url'] = $device->product->getAvatar();
            }else{
                $devices[$key]['url'] = url('/img/default/default_image.png');
            }
        }
        $arrayDevice = $devices->toArray();
        $shareDevices = ShareDevice::where('user_id',$userID)->get();
        
        foreach ($shareDevices as $key => $shareDevice) {
            $device =  $shareDevice->device;
            $device['user'] = $device->user;
            $product = $device->product;
            $device['url']  = $product->getAvatar();
            array_push($arrayDevice,$device);
        }
        return $arrayDevice;

    }
}
