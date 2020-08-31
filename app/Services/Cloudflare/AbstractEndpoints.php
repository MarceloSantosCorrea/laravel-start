<?php

namespace App\Services\Cloudflare;

use Cloudflare\API\Adapter\Adapter;

abstract class AbstractEndpoints
{
    protected $endpoint;

    public function __construct(Adapter $adapter)
    {
        $this->endpoint = new $this->endpoint($adapter);
    }
}