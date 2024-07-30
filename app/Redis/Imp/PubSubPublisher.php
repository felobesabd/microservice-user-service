<?php


namespace App\Redis\Imp;


use App\Redis\IPubSubPublisher;
use Illuminate\Support\Facades\Redis;
use Predis\Client;

class PubSubPublisher implements IPubSubPublisher
{
    public function publisher($topic, $data)
    {
        $redisPrefix = env('REDIS_PREFIX');

        $publisher = new Client([
            "host" => env('REDIS_HOST'),
            "password" => env('REDIS_PASSWORD'),
            "port" => env("REDIS_PORT"),
        ]);

        $publisher->publish(
            $redisPrefix.$topic,
            json_encode($data)
        );
    }

    public function subscribe($topics, $functions)
    {
        $redis = Redis::connection('subscriber');

        $publisher = new Client([
            "host" => env('REDIS_HOST'),
            "password" => env('REDIS_PASSWORD'),
            "port" => env("REDIS_PORT"),
        ]);

        $redis->subscribe($topics, function ($message) use ($publisher, $functions) {
            $message = json_decode($message);
            var_dump('message', $message);
            if ($message->type == 'user_added_note') {
                $functions['userAddedNotes']($message, $publisher);
            }
        });
    }
}
