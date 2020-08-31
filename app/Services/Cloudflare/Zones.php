<?php

namespace App\Services\Cloudflare;

use Cloudflare\API\Endpoints\Zones as ZonesApi;

class Zones extends AbstractEndpoints
{
    protected $endpoint = ZonesApi::class;


}