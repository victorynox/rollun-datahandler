<?php


namespace rollun\Downloader\Client\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use rollun\Downloader\Client\SerializableFtpClient;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class SerializableFtpClientAbstractFactory implements AbstractFactoryInterface
{
    const KEY = SerializableFtpClientAbstractFactory::class;

    const KEY_HOST = "host";

    const KEY_LOGIN = "login";

    const KEY_PASSWORD = "password";

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        try {
            $config = $container->get("config");
        } catch (NotFoundExceptionInterface $e) {
            return false;
        } catch (ContainerExceptionInterface $e) {
            return false;
        }
        return (
        isset($config[static::KEY][$requestedName])
        );
    }

    /**
     * Create an object
     * [
     *      "ftpClient" => [
     *          SerializableFtpClientAbstractFactory::KEY_HOST => "192.168.1.1",
     *          SerializableFtpClientAbstractFactory::KEY_LOGIN => "login",
     *          SerializableFtpClientAbstractFactory::KEY_PASSWORD => "pass",
     *     ]
     * ]
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SerializableFtpClient
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \FtpClient\FtpException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $factoryConfig = $config[static::KEY][$requestedName];
        $host = $factoryConfig[static::KEY_HOST];
        $login = $factoryConfig[static::KEY_LOGIN];
        $password = $factoryConfig[static::KEY_PASSWORD];
        return new SerializableFtpClient($host, $login, $password);
    }
}