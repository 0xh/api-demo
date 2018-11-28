<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateDeviceAPIRequest;
use App\Http\Requests\API\UpdateDeviceAPIRequest;
use App\Models\Device;
use App\Repositories\DeviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Events\DeviceGenerateKey;
use Illuminate\Support\Facades\Redis;
use App\Models\ShareDevice;

/**
 * Class DeviceController
 * @package App\Http\Controllers\Api\V1
 */

class DeviceAPIController extends AppBaseController
{
    /** @var  DeviceRepository */
    private $deviceRepository;

    public function __construct(DeviceRepository $deviceRepo)
    {
        $this->deviceRepository = $deviceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/devices",
     *      summary="Get a listing of the Devices.",
     *      tags={"Device"},
     *      description="Get all Devices",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Device")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->deviceRepository->pushCriteria(new RequestCriteria($request));
        $this->deviceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $devices = $this->deviceRepository->all();

        return $this->sendResponse($devices->toArray(), 'Devices retrieved successfully');
    }

    /**
     * @param CreateDeviceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/devices",
     *      summary="Store a newly created Device in storage",
     *      tags={"Device"},
     *      description="Store Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Device that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Device")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Device"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */

   

    public function store(CreateDeviceAPIRequest $request)
    {
        $input = $request->all();

        $devices = $this->deviceRepository->create($input);

        return $this->sendResponse($devices->toArray(), 'Device saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/devices/{id}",
     *      summary="Display the specified Device",
     *      tags={"Device"},
     *      description="Get Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Device"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($user_id)
    {
        /** @var Device $device */
        // $device = $this->deviceRepository->findWithoutFail($id);
        $device = $this->deviceRepository->findByField('user_id',$user_id);


        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        return $this->sendResponse($device->toArray(), 'Device retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateDeviceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/devices/{id}",
     *      summary="Update the specified Device in storage",
     *      tags={"Device"},
     *      description="Update Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Device that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Device")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Device"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($uuid, Request $request)
    {
        $input = $request->all();

        /** @var Device $device */
        $device = $this->deviceRepository->findByField('UUID',$uuid)->first();
        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        $device = $this->deviceRepository->update($input, $device->id);

        return $this->sendResponse($device->toArray(), 'Device updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/devices/{id}",
     *      summary="Remove the specified Device from storage",
     *      tags={"Device"},
     *      description="Delete Device",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Device",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($uuid)
    {
        /** @var Device $device */
        $device = $this->deviceRepository->findByField('UUID',$uuid)->first();

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        $device->delete();

        return $this->sendResponse($uuid, 'Device deleted successfully');
    }

    public function isDeviceExist($imei){
        $redis = Redis::connection();

        $redis_devices = $redis->lrange('devices', 0, -1);

        foreach ($redis_devices as $key => $device) {
            $device_item = json_decode($device);
            if($device_item->imei == $imei){
                return true;
            }
        }
        return false;
    }

    public function generateKey($imei){
        $timeExpire = 10; //seconds

        $device = $this->deviceRepository->findByField('imei', $imei)->first();

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        $key = \Random::generateString(16);

        $redis = Redis::connection();

        if(!$this->isDeviceExist($imei)){
            $redis->lpush('devices', $device);
        }

        $redis->set('key_device_' . $imei, $key);

        $redis->expire('key_device_' . $imei, $timeExpire);

        // event(new DeviceGenerateKey($device));

        return $this->sendResponse(['key' => $key], 'Key retrieved successfully');
    }

    public function saveDeviceInfo($deviceId, Request $request){

        $input = $request->all();

        $result = $this->deviceRepository->storeDeviceInfo($deviceId, $input);

        if($result['success']){
            return $this->sendResponse($deviceId, 'Store device info successfully');
        }else{
            return $this->sendError($result['message']);
        }
    }
     public function getDevicesOfUser(Request $request,$UserID){

        $this->deviceRepository->pushCriteria(new RequestCriteria($request));
        $this->deviceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $devices = $this->deviceRepository->getDevicesOfUser($UserID);

        return $this->sendResponse($devices, 'Devices retrieved successfully');
    }
    public function assignDevice(Request $request){
        $input = $request->all();

        $devices = $this->deviceRepository->assign($input);

        return $this->sendResponse($devices, 'Devices assign successfully');
    }
    public function unassignDevice(Request $request,$id){

        $devices = $this->deviceRepository->findWithoutFail($id);

        if (empty($devices)) {
            return $this->sendError('Device not found');
        }

        $devices = $this->deviceRepository->unassignDevice($id);

        return $this->sendResponse($devices, 'Device updated successfully');
    }

    public function demoproduct($id){
        $device = $this->deviceRepository->demoproduct($id);

        if (empty($device)) {
            return $this->sendError('Device not found');
        }

        return $this->sendResponse($device->toArray(), 'Device retrieved successfully');
    }
    public function shareDevice(Request $request){
        $input = $request->all();

        $share = $this->deviceRepository->shareDevice($input);

        return $this->sendResponse($share->toArray(), 'Device saved successfully');
    }
    public function getDevicesWithShareDevices($userID){
        $devices = $this->deviceRepository->getDevicesWithShareDevices($userID);

        return $this->sendResponse($devices, 'Devices retrieved successfully');
    }
    public function unShareDevice(Request $request){
        $del = ShareDevice::where('device_id',$request->input('device_id'))->first();
        $del->delete();
        return $this->sendResponse($del->toArray(), 'Device delete successfully');
    }
}
