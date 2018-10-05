# Lazy Load Middleware
LazyLoadMiddleware представляет из себя, некий прокси-middleware который
позволяет определять нужный middleware уже непосредственно в рантайме.

Для этого используется обьект `\rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface`,
для того что бы определить имя middleware из запроста.
> [Более подробно об MiddlewareDeterminator](./MiddlewareDetermitaor.md)

И `\rollun\actionrender\MiddlewarePluginManager` что бы получть нужным нам middleware.

## Factory
Для создания LazyLoad Middleware существует фабрика `\rollun\actionrender\Factory\LazyLoadMiddlewareAbstractFactory`
Что бы ее подключить вы можете воспользоваться предоставленый инсталлером - `\rollun\actionrender\Installers\LazyLoadMiddlewareInstaller`.
Фабрика предоставляет следующие ключи для настройки сервиса:
* `LazyLoadMiddlewareAbstractFactory::KEY` - ключь для указания начала конфига фабрки.
* `LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_DETERMINATOR` - имя сервиса middleware determinator.
* `LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_PLUGIN_MANAGER` - имя сервиса middleware плагин менеджера.

Пример конфига
```php
LazyLoadMiddlewareAbstractFactory::KEY => [
    "testActionRender" => [
        LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_DETERMINATOR => "testMiddlewareDeterminator",
        LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_PLUGIN_MANAGER => \rollun\actionrender\MiddlewarePluginManager,
    ]
]
```
