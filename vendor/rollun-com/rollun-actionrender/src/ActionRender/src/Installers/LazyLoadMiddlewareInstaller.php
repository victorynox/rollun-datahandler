<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.03.17
 * Time: 12:50
 */

namespace rollun\actionrender\Installers;

use rollun\actionrender\Factory\LazyLoadMiddlewareAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\installer\Install\InstallerAbstract;

class LazyLoadMiddlewareInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        return [
            'dependencies' => [
                'abstract_factories' => [
                    LazyLoadMiddlewareAbstractFactory::class,
                ],
            ],
        ];
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
                $description = "Позволяет создавать связки middleware типа LazyLoadPipe.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function isInstall()
    {
        $config = $this->container->get('config');
        return (
            isset($config['dependencies']['abstract_factories']) &&
            in_array(LazyLoadMiddlewareAbstractFactory::class, $config['dependencies']['abstract_factories'])
        );
    }
}