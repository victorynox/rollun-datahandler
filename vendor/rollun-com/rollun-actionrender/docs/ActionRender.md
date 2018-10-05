# ActionRender

ActionRender - middleware pipe состоящий из 3 middleware.
Цепочка поделенная на три логические части Action, Render, Returner.

## Последовательость работы ActionRender

В самом простом случае у нас существует два **Middleware**

1) **Action** - Выполняет определенное действие. Результат должен пометсить в атребут запроста `\rollun\actionrender\Renderer\AbstractRenderer::RESPONSE_DATA`

2) **Render** - Создает ответ и кладет его в атрибут запроса с именем `\Psr\Http\Message\ResponseInterface`.

Теоретически **Render** может вернуть ответ, но мы рекомендуем использовать для этого **Returner**.
Он достанет **Response** из атрибута запроста и вернет его пользователю.

3) **Returner** - возвращает результат.
Моежт использоваться в качестве аспекта.

## Factory
Для создания ActionRender Middleware существует фабрика `\rollun\actionrender\Factory\ActionRenderAbstractFactory`
Что бы ее подключить вы можете воспользоваться предоставленый инсталлером - `\rollun\actionrender\Installers\ActionRenderInstaller`.
Фабрика предоставляет следующие ключи для настройки сервиса:
* `ActionRenderAbstractFactory::KEY` - ключь для указания начала конфига фабрки.
* `ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE` - имя сервиса middleware действия.
* `ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE` - имя сервиса middleware отрисовки ответа
* `ActionRenderAbstractFactory::KEY_RETURNER_MIDDLEWARE_SERVICE` - имя сервиса Returner middleware (не обязательное).

Пример конфига
```php
ActionRenderAbstractFactory::KEY => [
    "testActionRender" => [
        ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => "testActionMiddleware",
        ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => "testRenderMiddleware",
        //ActionRenderAbstractFactory::KEY_RETURNER_MIDDLEWARE_SERVICE => "testReturnerMiddleware",
    ]
]
```


## Замечания
* Каждый из **Middleware** может быть **Middleware**, **PipeLine** либо **LazyLoadMiddleware**.