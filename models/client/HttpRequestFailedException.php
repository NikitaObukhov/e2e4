<?php

namespace app\models\client;

class HttpRequestFailedException extends \Exception
{
    public function __construct($uri, $status, Exception $previous)
    {
        assert(200 !== $status);
        $message = sprintf('Request to %s failed: %d', $uri, $status);
        parent::__construct($message, $status, $previous);
    }
}