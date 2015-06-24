<?php

namespace SidekiqJobPusher;

class KeyGenerator
{
    function generate($queue = 'default', $namespace = null)
    {
        $queue_key = $queue == 'schedule' ? null : 'queue';
        $parts = $this->compact(array($namespace, $queue_key, $queue));
        return implode(':', $parts);
    }

    private function compact($array)
    {
        return array_filter($array, 'strlen');
    }
}
