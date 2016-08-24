<?php

namespace SidekiqJobPusher;

use Predis\Client as PredisClient;

class Client
{
    private $redis;

    function __construct(PredisClient $redis, $namespace = null)
    {
        $this->redis = $redis;
        $this->namespace = $namespace;

        $this->keyGenerator = new KeyGenerator;
        $this->messageSerialiser = new MessageSerialiser;
    }

    function perform($workerClass, $arguments = array(), $retry = false, $queue = 'default')
    {
        $message = $this->messageSerialiser->prepare($workerClass, $arguments, $retry, $queue);
        $this->push($message);
    }

    function perform_in($interval, $workerClass, $arguments = array(), $retry = false, $queue = 'default')
    {
        $now = time();
        $ts = floatval($interval < 1000000000 ? ($now + $interval) : $interval);

        $this->perform_at($ts, $workerClass, $arguments, $retry, $queue);
    }

    function perform_at($ts, $workerClass, $arguments = array(), $retry = false, $queue = 'default')
    {
        $message = $this->messageSerialiser->prepare($workerClass, $arguments, $retry, $queue, $ts);
        $this->push($message);
    }

    protected function push($message)
    {
        if(isset($message['at']) && !is_null($message['at']))
        {
            $at = $message['at'];
            unset($message['at']);

            $key = $this->keyGenerator->generate('schedule', $this->namespace);
            $payload = $this->messageSerialiser->serialise($message);
            $this->redis->zadd($key, $at, $payload);
        } else {
            $key = $this->keyGenerator->generate($message['queue'], $this->namespace);
            $payload = $this->messageSerialiser->serialise($message);
            $this->redis->lpush($key, $payload);
        }
    }
}
