# Middleware Pipe
MiddlewarePile - предстявляет из себя цепочку middleware (как Unix pipes).
Позволяет по очередно вызывать middleware из цепочки передавая им request,
до тех пор пока один из них не вернет ответ, либо цепочка не закончится.

## Factory
Для создания MiddlewarePile существует фабрика `\rollun\actionrender\Factory\MiddlewarePipeAbstractFactory`
Что бы ее подключить вы можете воспользоваться предоставленый инсталлером - `\rollun\actionrender\Installers\MiddlewarePipeInstaller`.
Фабрика предоставляет следующие ключи для настройки сервиса:
* `MiddlewarePipeAbstractFactory::KEY` - ключь для указания начала конфига фабрки.
* `MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES` - массив имен сервисов middleware которые будет входить в пайп.

Пример конфига
```php
MiddlewarePipeAbstractFactory::KEY => [
    "testActionRender" => [
        MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
            "testMiddleware1",
            "testMiddleware2",
            "testMiddleware3",
        ],
    ]
]
```

