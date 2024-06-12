<?php

namespace App;

use RuntimeException;

class NoHealthyServersFound extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('No healthy servers available');
    }
}