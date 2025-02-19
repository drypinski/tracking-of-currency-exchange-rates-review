<?php

namespace App\Factory;

use Redis;

final readonly class RedisFactory
{
    public static function create(string $host, string $password): Redis
    {
        $redis = new Redis(['host' => $host]);
        $redis->auth($password);

        return $redis;
    }
}
