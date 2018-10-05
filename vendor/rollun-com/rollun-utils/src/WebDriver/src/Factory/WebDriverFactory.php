<?php

namespace rollun\util\WebDriver\Factory;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Interop\Container\ContainerInterface;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use MongoDB\Driver\Exception\ConnectionException;
use Zend\ServiceManager\Factory\FactoryInterface;

class WebDriverFactory implements FactoryInterface
{
    const KEY = 'WebDriver';

    const KEY_HOST = 'host';

    const KEY_BROWSER = 'browser';

    /**
     * WebDriverFactory::KEY => [
     *      [
     *          'host' => 'http://192.168.122.22:4444/wd/hub/',
     *          'browser' => \Facebook\WebDriver\Remote\WebDriverBrowserType::CHROME,
     *      ],
     *      [
     *          'host' => 'http://192.168.122.22:5444/wd/hub/',
     *          'browser' => \Facebook\WebDriver\Remote\WebDriverBrowserType::CHROME,
     *      ],
     * ...
     * ],
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RemoteWebDriver
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfigs = $container->get('config')[self::KEY];
        foreach ($serviceConfigs as $serviceConfig) {
            try {
                /** @var DesiredCapabilities $capability */
                $capability = call_user_func(['\Facebook\WebDriver\Remote\DesiredCapabilities', $serviceConfig[self::KEY_BROWSER]]);
                $capability->setCapability("enableVNC", true);
                $webDriver = RemoteWebDriver::create(
                    $serviceConfig[self::KEY_HOST],
                    $capability,
                    600 * 1000,
                    600 * 1000
                );
                $webDriver->get("https://google.com/");
                return $webDriver;
            } catch (\Throwable $throwable) {
                //TODO: add handle
            }
        }
        throw new ConnectionException("Not found working webDriver");
    }
}