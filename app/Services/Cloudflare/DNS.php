<?php

namespace App\Services\Cloudflare;

use Carbon\Carbon;
use Cloudflare\API\Endpoints\DNS as DNSApi;

class DNS extends AbstractEndpoints
{
    protected $endpoint = DNSApi::class;

    private function allDns()
    {
        $response = $zones->listZones();
        $rows     = [];
        foreach ($response->result as $item) {
            $rows[] = "$item->id - $item->name";
        }

        $zone   = $this->choice('Selecione a Zone?', $rows, '7e6a63da99cbac258055961be7e93dd2 - ciclano.io');
        $exp    = explode(' - ', $zone);
        $zoneId = array_shift($exp);

        $dns = new Endpoints\DNS($this->adapter);

        $type    = $this->ask('Qual é o Tipo?') ?? '';
        $name    = $this->ask('Qual é url?') ?? '';
        $content = $this->ask('Qual é o content?') ?? '';

        $page = 1;
        do {

            $response   = $dns->listRecords($zoneId, $type, $name, $content, $page);
            $resultInfo = $response->result_info;

            if ($resultInfo->count) {
                $headers = ['ID', 'Zone Name', 'Name', 'Type', 'Content', 'Created At', 'Updated At'];
                $rows    = [];
                foreach ($response->result as $item) {
                    $created = new Carbon($item->created_on);
                    $updated = new Carbon($item->modified_on);
                    $rows[]  = [
                        $item->id, $item->zone_name, $item->name, $item->type, $item->content,
                        $created->format('d/m/Y H:i:s'),
                        $updated->format('d/m/Y H:i:s'),
                    ];
                }

                $this->table($headers, $rows);
            }

            $this->table(['Page', 'Per Page', 'Total Pages', 'Count', 'Total Count'], [
                [
                    $resultInfo->page,
                    $resultInfo->per_page,
                    $resultInfo->total_pages,
                    $resultInfo->count,
                    $resultInfo->total_count,
                ],
            ]);

            if ($loop = $this->confirm('Ir para próxima página?', true)) {
                $page++;
            }
        } while ($loop);
    }
}