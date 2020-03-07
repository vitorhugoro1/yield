<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoadStockDataCommandTest extends TestCase
{
    /** @test */
    public function canLoadStocksFromParser()
    {
        $artisan = $this->artisan('stocks:load')
            ->expectsOutput('Begin load Stock data');

        sleep(10);

        $artisan->expectsOutput('End load Stock data');
    }
}
