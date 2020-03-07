<?php

namespace App\Domains\Parsers\Support;

use Closure;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Container\Container;
use Laravel\Dusk\Browser as DuskBrowser;
use Throwable;

class Browser
{
    /** @var \Laravel\Dusk\Browser */
    private $browser;

    public function browse(Closure $callback)
    {
        if (!$this->browser) {
            $this->browser = $this->newBrowser($this->createWebDriver());
        }

        try {
            return Container::getInstance()->call($callback);
        } catch (Exception $e) {
            throw $e;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function __destruct()
    {
        if ($this->browser) {
            $this->closeBrowser();
        }
    }

    public function closeBrowser()
    {
        if (!$this->browser) {
            throw new Exception("The browser hasn't been initialized yet");
        }

        $this->browser->quit();
        $this->browser = null;
    }

    public function newBrowser(?RemoteWebDriver $driver = null): DuskBrowser
    {
        return new DuskBrowser($driver ?? $this->createWebDriver());
    }

    /**
     * Create the remote web driver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function createWebDriver()
    {
        return retry(5, function () {
            return $this->driver();
        }, 50);
    }

    protected function driver()
    {
        $options = new ChromeOptions();
        $prefs = ['download.default_directory' => storage_path('app')];
        $options->setExperimentalOption('prefs', $prefs);
        $options->addArguments(['--headless']);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = RemoteWebDriver::create(
            'http://127.0.0.1:9515',
            $capabilities
        );

        return $driver;
    }
}
