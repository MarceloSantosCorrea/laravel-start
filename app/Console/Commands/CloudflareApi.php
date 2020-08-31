<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Cloudflare\API\Endpoints;
use Illuminate\Console\Command;

class CloudflareApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cloudflare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manager Api Cloudflare';

    /**
     * @var \Cloudflare\API\Adapter\Adapter
     */
    private $adapter;

    /**
     * @var array
     */
    private $zones = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('Gerenciamento Cloudflare Api');
        $this->_connectApi();
        $resource = $this->choice('Selecione o recurso?', [
            'Users', 'List Zones', 'List Records', 'Add Record', 'Delete Record',
        ]);

        $method = lcfirst(str_replace(' ', '', $resource));
        $this->$method();
    }

    private function _connectApi()
    {
        do {
            $email = $this->ask('Qual é o seu email de acesso?', 'mauricio@ciclano.io');
        } while ($email == null);

        do {
            $apiKey = $this->ask('Qual é a api key de acesso?', 'b4cfb80560a173a2b334c11f6bb58549ab140');
        } while ($email == null);

        $key           = new \Cloudflare\API\Auth\APIKey($email, $apiKey);
        $this->adapter = new \Cloudflare\API\Adapter\Guzzle($key);
    }

    private function users()
    {
        $user = new Endpoints\User($this->adapter);
        dd($user->getUserDetails());
    }

    private function listZones()
    {
        $zones      = new Endpoints\Zones($this->adapter);
        $response   = $zones->listZones();
        $resultInfo = $response->result_info;
        if ($resultInfo->count) {
            $headers = ['ID', 'Name', 'Status'];
            $rows    = [];
            foreach ($response->result as $item) {
                $rows[] = [
                    $item->id, $item->name, $item->status,
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
    }

    private function listRecords()
    {
        $zones    = new Endpoints\Zones($this->adapter);
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

    private function addRecord()
    {
        $zones    = new Endpoints\Zones($this->adapter);
        $response = $zones->listZones();
        $rows     = [];
        foreach ($response->result as $item) {
            $rows[] = "$item->id - $item->name";
        }

        $zone   = $this->choice('Selecione a Zone?', $rows, '7e6a63da99cbac258055961be7e93dd2 - ciclano.io');
        $exp    = explode(' - ', $zone);
        $zoneId = array_shift($exp);

        $dns = new Endpoints\DNS($this->adapter);

        do {
            $type = $this->ask('Qual é o Tipo?', 'A');
        } while ($type == null);

        do {
            $name = $this->ask('Qual é url?');
        } while ($name == null);

        do {
            $content = $this->ask('Qual é o ip?');
        } while ($content == null);

        if ($dns->addRecord($zoneId, $type, $name, $content, 0, false) === true) {
            $this->comment("DNS criado com sucesso");
        }
    }

    private function deleteRecord()
    {
        $zones    = new Endpoints\Zones($this->adapter);
        $response = $zones->listZones();
        $rows     = [];
        foreach ($response->result as $item) {
            $rows[] = "$item->id - $item->name";
        }

        $zone   = $this->choice('Selecione a Zone?', $rows, '7e6a63da99cbac258055961be7e93dd2 - ciclano.io');
        $exp    = explode(' - ', $zone);
        $zoneId = array_shift($exp);

        $dns = new Endpoints\DNS($this->adapter);

        do {
            $type = $this->ask('Qual é o Tipo?', 'A');
        } while ($type == null);

        $name = $this->ask('Qual é url?', 'devffmpeg.ciclano.io');

        $recordId = $dns->getRecordID($zoneId, $type, $name);

        dd($dns->deleteRecord($zoneId, $recordId));
    }
}
