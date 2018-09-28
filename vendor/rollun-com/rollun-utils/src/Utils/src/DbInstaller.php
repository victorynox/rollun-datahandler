<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.03.17
 * Time: 16:49
 */

namespace rollun\utils;

use Psr\Container\ContainerExceptionInterface;
use rollun\installer\Install\InstallerAbstract;
use Zend\Db\Adapter\AdapterAbstractServiceFactory;
use Zend\Db\Adapter\AdapterInterface;

class DbInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        $config = [
            'dependencies' => [
                'aliases' => [
                    'db' => AdapterInterface::class,
                ]
            ]
        ];

        if ($this->consoleIO->askConfirmation("Do you want to start the process of generating a DB config file?", false)) {
            $adapterName = $this->consoleIO->ask("Set adapter name (" . AdapterInterface::class . " by default):");
            if (is_null($adapterName)) {
                $adapterName = AdapterInterface::class;
            }
            $adapterConfig = $this->createAdapter($adapterName);
            $adapters = array_merge($this->getAdapters(false), $adapterConfig);
            if($adapterName == AdapterInterface::class) {
                $config["db"] = $adapterConfig[$adapterName];
            } else {
                $config["db"]["adapters"] = $adapters;
            }
        } else {
            $this->consoleIO->write("You must create config for db adapter, with adapter name 'db'.");
        }
        return $config;
    }

    /**
     * @return boolean
     */
    protected function hasAdapters()
    {
        return !empty($this->getAdapters());
    }

    /**
     * @param bool $isAll
     * @return array
     */
    protected function getAdapters($isAll = true)
    {
        try {
            $config = $this->container->get("config");
            $adapters = isset($config["db"]["adapters"]) ? $config["db"]["adapters"] : [];
            if (!$isAll) {
                $adapters = array_filter($adapters, function ($adapter) {
                    return (isset($adapter[static::class]) && $adapter[static::class]);
                });
            }
            return $adapters;
        } catch (ContainerExceptionInterface $exception) {
            return [];
        }
    }

    /**
     * @param $name
     * @return array
     */
    protected function createAdapter($name)
    {
        $drivers = ['IbmDb2', 'Mysqli', 'Oci8', 'Pgsql', 'Sqlsrv', 'Pdo_Mysql', 'Pdo_Sqlite', 'Pdo_Pgsql'];
        $index = $this->consoleIO->select("", $drivers, 5);

        do {
            $dbName = $this->consoleIO->ask("Set database name:");
            if (is_null($dbName)) {
                $this->consoleIO->write("You not set, database name");
            }
        } while ($dbName == null);
        do {
            $dbUser = $this->consoleIO->ask("Set database user name:");
            if (is_null($dbUser)) {
                $this->consoleIO->write("You not set, database user name");
            }
        } while ($dbUser == null);
        $dbPass = $this->consoleIO->askAndHideAnswer("Set database password:");

        $dbHost = $this->consoleIO->ask("Set database host(localhost by default):");
        $adapterHost = [
            $name => [
                'driver' => $drivers[$index],
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass
            ]
        ];
        if(isset($dbHost)) {
            $adapterHost[$name]["host"] = $dbHost;
        }
        return $adapterHost;
    }

    public function isInstall()
    {
        return $this->hasAdapters();
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Позволяет представить таблицу в DB в качестве хранилища.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }
}
