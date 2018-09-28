<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.04.17
 * Time: 16:59
 */

namespace rollun\example\actionrender;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\ZendView\HelperPluginManagerFactory;
use Zend\Expressive\ZendView\ZendViewRendererFactory;
use Zend\View\HelperPluginManager;


/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'templates' => $this->getTemplates(),
            "dependencies" => $this->getDependencies(),
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'layout' => 'ar-layout::default',
            'paths' => [
                'ar-app'    => [__DIR__ . '/../templates/app'],
                'ar-error'  => [__DIR__ . '/../templates/error'],
                'ar-layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }

    /**
     * Returns the dependencies configuration
     */
    public function getDependencies()
    {
        return [
            'factories' => [
                TemplateRendererInterface::class => ZendViewRendererFactory::class,
                HelperPluginManager::class => HelperPluginManagerFactory::class,
            ],
        ];
    }
}
