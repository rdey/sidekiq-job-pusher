<?php

namespace SidekiqJobPusher;

class MessageSerialiser
{
    function serialise($workerClass, $args = array(), $retry = false, $at = null)
    {
        $message = array(
            'class' => $workerClass,
            'args'  => $args,
            'retry' => $retry
        );

        if(!is_null($at) && $at > time()) $message['at'] = $at;
        return json_encode($message);
    }
}
