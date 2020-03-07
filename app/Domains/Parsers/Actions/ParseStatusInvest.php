<?php

namespace App\Domains\Parsers\Actions;

use App\Domains\Parsers\DataTransferObject\YieldData;
use App\Domains\Stocks\Models\Stock;
use Exception;
use Goutte\Client;

class ParseStatusInvest
{
    protected $url = "https://statusinvest.com.br/acoes/";

    /** @var \Goutte\Client */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function execute(Stock $stock)
    {
        $response = $this->client->request('GET', $this->url . strtolower($stock->code));

        $result = $response->filter('#results');

        if (!$result) {
            throw new Exception("Not found {$stock->code} on Status Invest");
        }

        $data = json_decode($result->attr('value'), true);

        $data = collect($data)
            ->map(fn($yield) => YieldData::fromParser($yield, 1))
            ->map(fn(YieldData $yield) => $yield->all());

        $stock->stockYield()->createMany($data->all());
    }
}
