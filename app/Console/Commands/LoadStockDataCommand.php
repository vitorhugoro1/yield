<?php

namespace App\Console\Commands;

use App\Domains\Enterprises\Models\Enterprise;
use App\Domains\Parsers\Actions\GetStocksFromBovespa;
use App\Domains\Parsers\Actions\ParseCodeData;
use Illuminate\Console\Command;

class LoadStockDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:load {--ignore-bovespa} {--company-name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GetStocksFromBovespa $stocksFromBovespa, ParseCodeData $parseCodeData)
    {
        $this->info("Begin load Stock data");

        if (!$this->option('ignore-bovespa')) {
            $stocksFromBovespa->execute();
        }

        if (!$this->option('company-name')) {
            $companies = Enterprise::with('stocks')->get();

            $companies->each(fn($company) => $parseCodeData->execute($company));

            return;
        }

        $company = Enterprise::where('name', 'LIKE', strtoupper($this->option('company-name') . "%"))
            ->with('stocks')->first();

        if (!$company) {
            $this->info("Company not founded!");
            return;
        }

        $this->info("Found: {$company->name}");

        $parseCodeData->execute($company);

        $this->info("{$company->name} Stocks loaded");

        $this->info("End load Stock data");
    }
}
