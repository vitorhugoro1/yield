<?php

namespace App\Domains\Parsers\Actions;

use App\Domains\Enterprises\Models\Enterprise;
use App\Domains\Parsers\Support\Browser;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser as DuskBrowser;
use Symfony\Component\DomCrawler\Crawler;

class GetStocksFromBovespa
{
    /** @var \App\Domains\Parsers\Support\Browser */
    protected $browser;

    protected $url = "http://www.b3.com.br/en_us/products-and-services/trading/equities/listed-companies.htm";

    /** @var string */
    protected $data;

    protected $baseUrl;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function execute()
    {
        $this->getOnlineData();
    }

    private function getOnlineData(): void
    {
        $this->browser->browse(function (DuskBrowser $browser) {
            $browser->visit($this->url)
                ->waitFor('iframe#bvmf_iframe')
                ->withinFrame('iframe#bvmf_iframe', function (DuskBrowser $browser) {
                    $browser->pause(200);

                    $browser->click('#ctl00_contentPlaceHolderConteudo_BuscaNomeEmpresa1_btnTodas');

                    retry(3, function () use ($browser) {
                        $browser->waitFor('#ctl00_contentPlaceHolderConteudo_BuscaNomeEmpresa1_grdEmpresa_ctl01', 20);

                        $this->data = $browser
                            ->element('#ctl00_contentPlaceHolderConteudo_BuscaNomeEmpresa1_grdEmpresa_ctl01')
                            ->getAttribute('innerHTML');

                        return;
                    });
                });

            $this->baseUrl = $browser->element('iframe#bvmf_iframe')->getAttribute('src');

            $companies = $this->parse();

            $companies
                ->map(fn($company) => ['name' => $company['company_name'], 'website' => $company['link']])
                ->filter(fn($company) => Enterprise::find($company['name']) === null)
                ->each(fn($company) => Enterprise::create(Arr::only($company, ['name', 'website'])));

            $browser->quit();
        });
    }

    private function parse(): Collection
    {
        $crawler = new Crawler($this->data);

        $table = $this->tableToArray($crawler);

        return collect($table)->unique();
    }

    private function tableToArray(Crawler $crawler): array
    {
        $header = $crawler->filter('thead tr th')
            ->each(fn(Crawler $e) => $e->text());

        $keys = collect($header)->map(fn($key) => Str::slug($key, '_'));
        $keys->add('link');

        $content = $crawler->filter('tbody tr')
            ->each(fn(Crawler $tr) => $keys->combine($this->getCompanyWithLink($tr))->all());

        return $content;
    }

    private function getCompanyWithLink(Crawler $tr): array
    {
        $link = $tr->filter('td a')->first()->attr('href');
        $baseLink = explode('/', Str::replaceFirst('http://', '', $this->baseUrl));
        array_pop($baseLink);
        $link = 'http://' . implode('/', $baseLink) . '/' . $link;

        $fields = $tr->filter('td')->each(fn(Crawler $td) => $td->text());

        return array_merge($fields, [$link]);
    }
}
