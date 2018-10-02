
# rollun-datahandler

Библиотека `rollun-datahandler` расширяет стандартные библиотеки 
[`zendframework\zend-filter`](https://github.com/zendframework/zend-filter),
[`zendframework\zend-validator`](https://github.com/zendframework/zend-validator) и 
[`symfony\expression-language`](https://github.com/symfony/expression-language), 
а так же добавляет процессоры.

## Фильтры

Фильтры расширяют стандартную библиотеку [`zendframework\zend-filter`](https://github.com/zendframework/zend-filter).

**Список фильтров:**

* DuplicateSymbol - заменяет повторяющиеся символи (последовательность символов) на заданой символ (по умолчанию заменяет на то что дублируется)
* Evaluation - вычисляет выражение (Expression Language) над входящей строкой и возвращет его
* RemoveDigits - удаляет все цифры в строке, оставляя после себя один пробел ('a1c' => 'a c')
* RqlReplace - фильтр, который заменяет все вхождения rql маски на заданную строку
* SortSymbols - сортирует символы в строке (не имеет значение какие символы)
* SortWords - сортирует слова в строке


## Процессоры

Процессоры выполняют некие действие к входящему массиву и возвращают его. Суть процессора в том что бы 
он что то сделал с входящими данными. Он может вернуть массив так ничего с ним и не сделав, при этом 
обработать данные и уже с этими данными что то делать: записать логи, отправить что то на почту и тд.

**Список процессоров:**

* Concat - объединяет значения массива и записывает результат в столбец этого же массива
* Evaluation - Вычисляет выражение над элементами массива 
(элементы массива стают переменными в выражении, где ключ = название переменной, а значение = значение переменной)
и записывает результат в столбец этого же массива
* FilterApplier - применяет фильтры к заданным столбцам массива


## Валидаторы

Валидаторы расширяют стандартную библиотеку [`zendframework\zend-validator`](https://github.com/zendframework/zend-validator).

Список валидаторов:

* ArrayAdapter - валидирует заданные столбцы массива


## Язык выражений (Expression Language)

Рассширение до библиотека [`symfony\expression-language`](https://github.com/symfony/expression-language)

**Expression functions**

Callback - предоставляет возможность создание ExpressionFunction с колбэка

**Expression functions providers**

Plugin - предоставляет возможность создание ExpressionFunctionProvider с AbstractPluginManager,
указав список сервисов и вызываемый метод
