<?php


namespace App\Redis;


interface IPubSubPublisher
{
    public function publisher($topic, $data);
    public function subscribe($topics, $functions);
}
