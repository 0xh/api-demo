<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\MyLibs\AES;
use Illuminate\Support\Facades\Log;

class RedisDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:devices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get info devices from Redis server and save to Database';

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
        // $devices = $redis->lrange('devices', 0, -1);
        // $imputKey = "GDE3LAVRCZVRQWPL";
        // $blockSize = 128;
        // $aes = new AES(null, $imputKey, $blockSize);
        // foreach ($devices as $device) {
        //     $aes->setData($device);
        //     $dec=$aes->decrypt();
        //     list($timestamp, $imei, $lat_lon, $roll, $shake, $jump, $ip, $battery) = explode(',', $dec);
        //     Log::info($dec);
        //     Log::info('timestamp: '.$timestamp);
        //     Log::info('imei: '.$imei);
        //     Log::info('lat_lon: '.$lat_lon);
        //     Log::info('roll: '.$roll);
        //     Log::info('shake: '.$shake);
        //     Log::info('jump: '.$jump);
        //     Log::info('ip: '.$ip);
        //     Log::info('battery: '.$battery);
        //     // Save to data;
        // }

        $device = $redis->lrange('devices', 0, 0);
        $key = "GDE3LAVRCZVRQWPL";
        $blockSize = 128;
        $aes = new AES($device[0], $key, $blockSize);
        $dec=$aes->decrypt();
        list($timestamp, $imei, $lat_lon, $roll, $shake, $jump, $ip, $battery) = explode(',', $dec);
        Log::info($dec);
        Log::info('timestamp: '.$timestamp);
        Log::info('imei: '.$imei);
        Log::info('lat_lon: '.$lat_lon);
        Log::info('roll: '.$roll);
        Log::info('shake: '.$shake);
        Log::info('jump: '.$jump);
        Log::info('ip: '.$ip);
        Log::info('battery: '.$battery);

    }
}
