<?php

namespace SidekiqJobPusher;

class MessageSerialiser
{
    function serialise($workerClass, $args = array(), $retry = false)
    {
        return json_encode(array(
            'class' => $workerClass,
            'jid' => bin2hex(openssl_random_pseudo_bytes(12)),
            'created_at' => microtime(true),
            'enqueued_at' => microtime(true),
            'args'  => $args,
            'retry' => $retry
        ));
    }
}
