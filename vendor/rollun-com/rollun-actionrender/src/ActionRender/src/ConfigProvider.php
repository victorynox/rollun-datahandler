<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.04.17
 * Time: 16:59
 */

namespace rollun\actionrender;

use rollun\actionrender\Factory\MiddlewarePluginManagerFactory;

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
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getDependencies()
    {
        // Provides application-wide services.
        // We recommend using fully-qualified class names whenever possible as
        // service names.
        return [
            // Use 'aliases' to alias a service name to another service. The
            // key is the alias name, the value is the service to which it points.
            'aliases' => [],
            // Use 'invokables' for constructor-less services, or services that do
            // not require arguments to the constructor. Map a service name to the
            // class name.
            'invokables' => [],
            // Use 'factories' for services provided by callbacks/factory classes.
            'factories' => [
                MiddlewarePluginManager::class => MiddlewarePluginManagerFactory::class
            ],
        ];
    }
}
