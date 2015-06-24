<?php

namespace SidekiqJobPusher;

class MessageSerialiser
{
    function prepare($workerClass, $args = array(), $retry = false, $queue = 'default', $at = null)
    {
        $message = array(
            'class' => $workerClass,
            'args'  => $args,
            'retry' => $retry,
            'queue' => $queue
        );

        if(!is_null($at) && $at > time()) $message['at'] = $at;
        return $message;
    }

    function serialise(array $message) {
        return json_encode($message);
    }
}
