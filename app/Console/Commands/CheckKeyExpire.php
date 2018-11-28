<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class CheckKeyExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:key-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check key in redis expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = Redis::connection();

        $devices = $redis->lrange('devices', 0, -1);

        foreach ($devices as $device) {
            $device_item = json_decode($device);

            $device_class = \App\Models\Device::find($device_item->id);

            $key = $redis->get('key_device_' . $device_item->imei);

            if($device_class && !$key){
                event(new \App\Events\DeviceKeyExpired($device_class));
            }
        }


    }
}
