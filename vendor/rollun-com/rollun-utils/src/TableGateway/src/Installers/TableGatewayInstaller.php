<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.03.17
 * Time: 10:26
 */

namespace rollun\tableGateway\Installers;

use rollun\installer\Install\InstallerAbstract;
use rollun\tableGateway\Factory\TableGatewayAbstractFactory;
use rollun\tableGateway\Factory\TableManagerMysqlFactory;
use rollun\utils\DbInstaller;

class TableGatewayInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        $config = [
            'dependencies' => [
                'factories' => [
                    'TableManagerMysql' => TableManagerMysqlFactory::class
                ],
                'abstract_factories' => [
                    TableGatewayAbstractFactory::class,
                ],
            ]
        ];
        return $config;
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {

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
                $description = "Позволяет представить таблицу в DB в качестве TableGateway.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function isInstall()
    {
        $config = $this->container->get('config');
        //return false;
        $result = isset($config['dependencies']['abstract_factories']) &&
            isset($config['dependencies']['factories']) &&
            in_array(TableGatewayAbstractFactory::class, $config['dependencies']['abstract_factories']) &&
            isset($config['dependencies']['factories']['TableManagerMysql']) &&
            $config['dependencies']['factories']['TableManagerMysql'] === TableManagerMysqlFactory::class;
        return $result;
    }

    public function getDependencyInstallers()
    {
        return [
            DbInstaller::class,
        ];
    }
}
