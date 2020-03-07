<?php

namespace App\Domains\Parsers\Actions;

use App\Domains\Stocks\Models\Stock;
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

        dd($response);
    }
}
