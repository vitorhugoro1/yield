<?php

namespace App\Domains\Parsers\Actions;

use App\Domains\Enterprises\Models\Enterprise;
use App\Domains\Parsers\Support\Browser;
use Laravel\Dusk\Browser as DuskBrowser;
use Symfony\Component\DomCrawler\Crawler;

class ParseCodeData
{
    /** @var \App\Domains\Parsers\Support\Browser */
    protected $browser;

    /** @var string */
    protected $data;

    protected $baseUrl;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function execute(Enterprise $company)
    {
        $this->browser->browse(function (DuskBrowser $browser) use ($company) {
            $browser->visit($company->website)
                ->waitFor('#ctl00_contentPlaceHolderConteudo_iframeCarregadorPaginaExterna')
                ->withinFrame(
                    '#ctl00_contentPlaceHolderConteudo_iframeCarregadorPaginaExterna',
                    function (DuskBrowser $browser) {
                        $browser->pause(200);

                        $element = $browser->element('#panel1a');

                        if ($element) {
                            $this->data = $element->getAttribute('innerHTML');
                            return;
                        }

                        $element = $browser->element('#accordionDados');

                        $this->data = $element->getAttribute('innerHTML');
                    }
                );
        });

        $codes = $this->parseCodeData();

        $codes = collect($codes)
            ->unique()
            ->filter(fn($code) => $company->stocks->where('code', $code)->isEmpty())
            ->map(fn($code) => ['stock' => $code, 'code' => $code]);

        if ($codes->isNotEmpty()) {
            $company->stocks()->createMany($codes->all());
        }

        return $company;
    }

    private function parseCodeData(): array
    {
        $crawler = new Crawler($this->data);
        $codes = $crawler->filter('.LinkCodNeg')->each(fn(Crawler $a) => $a->text());

        return $codes;
    }

}
