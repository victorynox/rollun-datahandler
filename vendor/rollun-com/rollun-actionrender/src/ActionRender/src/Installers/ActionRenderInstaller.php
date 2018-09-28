<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.03.17
 * Time: 11:32
 */

namespace rollun\actionrender\Installers;

use rollun\actionrender\Factory\ActionRenderAbstractFactory;
use rollun\actionrender\Factory\LazyLoadMiddlewareAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\actionrender\MiddlewareDeterminator\Factory\ResponseRendererAbstractFactory;
use rollun\actionrender\ReturnMiddleware;
use rollun\installer\Install\InstallerAbstract;

class ActionRenderInstaller extends InstallerAbstract
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
                    ActionRenderAbstractFactory::class,
                ],
                'invokables' => [
                    ReturnMiddleware::class => ReturnMiddleware::class
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
                $description = "Позволяет создавать связки middleware типа ActionRender";
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
            isset($config['dependencies']['invokables']) &&
            in_array(ActionRenderAbstractFactory::class, $config['dependencies']['abstract_factories']) &&
            in_array(ReturnMiddleware::class, $config['dependencies']['invokables'])
        );
    }
}
