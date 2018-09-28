# Middleware Determinator

MiddlewareDeterminator - сервис реализущий
интерфейс `\rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface`
позвоялеющий получить имя middleware на основании
запроса (`\Psr\Http\Message\ServerRequestInterface`).

Для удобства пользования, библиотекой предоставлен ряд абстракций и реализаций сервисов даннойго типа.
А так же фабрик для их создания.

## AbstractParam
Базовый класс `\rollun\actionrender\MiddlewareDeterminator\AbstractParam`
предоставляет возможность получать имя сервиса middleware по параметру
из запроса, либо использовать имя по умолчанию.
Для этого вам нужно наследовать класс и реализовать метод `AbstractParam::getValue(ServerRequestInterface)`.

### Factory
Для создания сервисов подобного типа создана базовая фабрика `\rollun\actionrender\MiddlewareDeterminator\Factory\AbstractParamAbstractFactory`

Фабрика предоставляет следующие ключи для настройки сервиса:
* `AbstractParamAbstractFactory::KEY` - ключь для указания начала конфига фабрки.
* `AbstractParamAbstractFactory::KEY_CLASS` - имя класса реализации **AbstractParam**
* `AbstractParamAbstractFactory::KEY_NAME` - имя параметра запроса
* `AbstractParamAbstractFactory::KEY_DEFAULT_VALUE` - имя сервиса middleware по умолчанию.

Пример конфига
```php
AbstractParamAbstractFactory::KEY => [
    "testActionRender" => [
        AbstractParamAbstractFactory::KEY_CLASS => "ParamClass"
        AbstractParamAbstractFactory::KEY_NAME => "testAttribute"
        AbstractParamAbstractFactory::KEY_DEFAULT_VALUE => "testMiddleware",
    ]
]
```

#### AttributeParam

`\rollun\actionrender\MiddlewareDeterminator\AttributeParam` - реализация **AbstractParam** позволяющая создавать сервис на основании
атрибута запроса или значении по умолчанию.

Для подключения фабрики `\rollun\actionrender\MiddlewareDeterminator\Factory\AttributeParamAbstractFactory`
можете использовать installer `rollun\actionrender\MiddlewareDeterminator\Installers\AttributeParamInstaller`

## AbstractSwitch
Базовый класс `\rollun\actionrender\MiddlewareDeterminator\AbstractSwitch`
предоставляет возможность получать имя сервиса middleware используя matching параметра запроса и ожидаемого значения.
Для этого вам нужно наследовать класс и реализовать метод `AbstractSwitch::getSwitchValue(ServerRequestInterface)`.

### Factory
Для создания сервисов подобного типа создана базовая фабрика `\rollun\actionrender\MiddlewareDeterminator\Factory\AbstractSwitchAbstractFactory`

Фабрика предоставляет следующие ключи для настройки сервиса:
* `AbstractSwitchAbstractFactory::KEY` - ключь для указания начала конфига фабрки.
* `AbstractSwitchAbstractFactory::KEY_CLASS` - имя класса реализации **AbstractSwitch**
* `AbstractSwitchAbstractFactory::KEY_NAME` - имя параметра запроса
* `AbstractSwitchAbstractFactory::KEY_MIDDLEWARE_MATCHING` - массив созержащий
в качетсве ключа регулярное выражение условия, а в качетсве значения - имя сервиса middleware.

Пример конфига
```php
AbstractSwitchAbstractFactory::KEY => [
    "testActionRender" => [
        AbstractSwitchAbstractFactory::KEY_CLASS => "SwitchClass"
        AbstractSwitchAbstractFactory::KEY_NAME => "TestParams"
        AbstractSwitchAbstractFactory::KEY_MIDDLEWARE_MATCHING => [
                '/application\/json/' => "TestJsonMiddleware",
                '/text\/html/' => 'TestHtmlMiddleware'
        ]
    ]
]
```


#### AttributeSwitch
`\rollun\actionrender\MiddlewareDeterminator\AttributeSwitch` -
реализация **AbstractSwitch** позволяющая получать сервис на основании
значения из атрибута запроса.

Для подключения фабрики `\rollun\actionrender\MiddlewareDeterminator\Factory\AttributeSwitchAbstractFactory`
можете использовать installer `rollun\actionrender\MiddlewareDeterminator\Installers\AttributeSwitchInstaller`

#### HeaderSwitch
`\rollun\actionrender\MiddlewareDeterminator\HeaderSwitch` -
реализация **AbstractSwitch** позволяющая получать сервис на основании
значения из заголовка запроса.

Для подключения фабрики `\rollun\actionrender\MiddlewareDeterminator\Factory\HeaderSwitchAbstractFactory`
можете использовать installer `rollun\actionrender\MiddlewareDeterminator\Installers\HeaderSwitchInstaller`






