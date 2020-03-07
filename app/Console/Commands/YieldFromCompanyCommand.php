<?php

namespace App\Console\Commands;

use App\Domains\Parsers\Actions\ParseStatusInvest;
use App\Domains\Stocks\Models\Stock;
use Illuminate\Console\Command;

class YieldFromCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:yield {stock}';

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
    public function handle(ParseStatusInvest $statusInvest)
    {
        $stock = Stock::firstWhere('code', strtoupper($this->argument('stock')));

        if (!$stock) {
            $this->info("Not found {$stock}");
            return;
        }

        $statusInvest->execute($stock);
    }
}
