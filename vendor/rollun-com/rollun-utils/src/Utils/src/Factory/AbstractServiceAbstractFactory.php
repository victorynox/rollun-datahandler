<?php

namespace rollun\utils\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ReflectionClass;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class AbstractServiceAbstractFactory
 * @package rollun\utils\Factory
 */
class AbstractServiceAbstractFactory extends AbstractAbstractFactory
{
    const KEY = AbstractServiceAbstractFactory::class;

    /**
     * service dependencies
     */
    const KEY_DEPENDENCIES = "dependencies";

    const TYPE_SERVICE = "service";

    const TYPE_SERVICES_LIST = "services_list";

    const TYPE_SIMPLE = "simple";

    const KEY_VALUE = "value";

    const KEY_TYPE = "type";

    /**
     * Create an object
     *
     * [
     *      "myService" => [
     *          "class" => MyClass::class,
     *          "dependencies" => [
     *              "isCreate" => true, //bool - simple by default,
     *              "age" => 123 // numeric - simple by default,
     *              "generator" => "myGenerator"// string - service by default,
     *              "name" => [ // need set up, because string is service by default, but expected string value
     *                  "type" => "simple",
     *                  "value" => "my name",
     *              ],
     *              "data" => [ // need set up, because expected array value
     *                  "type" => "simple",
     *                  "value" => [
     *                       "my name1",
     *                       "my name2",
     *                       "my name3",
     *                  ],
     *              ], "data" => [ // need set up, because expected array value
     *                  "type" => "services_list",
     *                  "value" => [
     *                       "my_service1",
     *                       "my_service2",
     *                       "my_service3",
     *                  ],
     *              ],
     *          ],
     *      ]
     *
     * ]
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     * @throws \ReflectionException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        $class = isset($serviceConfig[static::KEY_CLASS]) ? $serviceConfig[static::KEY_CLASS] : $requestedName;
        if (!class_exists($class)) {
            throw new ServiceNotCreatedException("Class $class not existed.");
        }
        $dependencies = isset($serviceConfig[static::KEY_DEPENDENCIES]) ? $serviceConfig[static::KEY_DEPENDENCIES] : $serviceConfig;

        $serviceDependencies = [];
        foreach ($dependencies as $parameterName => $dependency) {
            $serviceDependencies[$parameterName] = $this->resolveDependency($container, $dependency);
        }
        $classReflection = new ReflectionClass($class);
        $constructParameters = $classReflection->getConstructor()->getParameters();
        $paramArgs = [];
        foreach ($constructParameters as $constructParameter) {
            if (isset($serviceDependencies[$constructParameter->getName()])) {
                $value = $serviceDependencies[$constructParameter->getName()];
            } elseif($constructParameter->isDefaultValueAvailable()) {
                $value = $constructParameter->getDefaultValue();
            } else {
                throw new ServiceNotCreatedException("Not set service $requestedName dependency {$constructParameter->getName()}.");
            }
            $paramArgs[] = $value;
        }
        return $classReflection->newInstance(...$paramArgs);
    }

    /**
     * Can the factory create an instance for the service?
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        return (
            isset($config[static::KEY][$requestedName])
            && (
                class_exists($requestedName)
                || (
                    isset($config[static::KEY][$requestedName][static::KEY_CLASS])
                    && class_exists($config[static::KEY][$requestedName][static::KEY_CLASS])
                )
            )
        );

    }

    /**
     * @param $dependency
     * @param ContainerInterface $container
     * @return mixed
     */
    protected function resolveDependency(ContainerInterface $container, $dependency)
    {
        switch (true) {
            case is_array($dependency):
                switch ($dependency[static::KEY_TYPE]) {
                    case static::TYPE_SERVICES_LIST:
                        return array_map(function($dependency) use ($container){
                            return $container->get($dependency);
                        },$dependency[static::KEY_VALUE]);
                    case static::TYPE_SERVICE:
                        return $container->get($dependency[static::KEY_VALUE]);
                    case static::TYPE_SIMPLE:
                        return $dependency[static::KEY_VALUE];
                    default:
                        throw new ServiceNotCreatedException("Dependency type dosn't set.");
                }
                break;
            case is_string($dependency):
                return $container->get($dependency);
                break;
            case is_null($dependency):
            case is_integer($dependency):
            case is_float($dependency):
            case is_bool($dependency):
            case is_object($dependency):
                return $dependency;
                break;
            default:
                throw new ServiceNotCreatedException("Dependency has invalid type.");
        }
    }
}