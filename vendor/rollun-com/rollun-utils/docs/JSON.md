#UTILS
##JSON CODER AND SERIALIZER

Coder и Serializer имею похожий функционал, но отличаются способом сериализации объектов.

###JSON CODER
[Coder.php](https://github.com/rollun-com/rollun-utils/blob/master/src/Json/Coder.php/).
В основном используется для сериализации простых типов и массивов.
JSON представление stdClass объекта декодируется в массив.

###JSON SERIALIZER
[Serializer.php](https://github.com/rollun-com/rollun-utils/blob/master/src/Json/Serializer.php/).
В основном используется для сериализации простых объектов, Но может сериализовывать простые типы.
JSON представление stdClass объекта декодируется в массив.

##В чем отличия
JSON CODER при попытке кодировать объет выбрасывает исключение..

Подробнее видно в тестах:
[CoderTest.php](https://github.com/rollun-com/rollun-utils/blob/master/src/Json/Coder.php/),
[SerializerTest.php](https://github.com/rollun-com/rollun-utils/blob/master/src/Json/Coder.php/).

##Сериализация Exception объектов
JSON CODER не может кодировать Exception.
JSON SERIALIZER может кодировать и восстанавливать сложные объекты, если написать плагин для поддержки объектов определенного типа.
Пример - Exception. [Serializer.php](https://github.com/rollun-com/rollun-utils/blob/master/src/Json/Serializer.php/).

````PHP
    public static function jsonSerialize($value)
    {
        $serializer = new JsonSerializer();
        $serializer->defineSerialization('Exception', [get_class(), 'serializeException'], [get_class(), 'unserializeException']);
    ....
    }
````
Так же JSON SERIALIZER поддерживает кастомные Exception, вложенные Exception, а так же вложенные кастомные поля.
Для обработки кастомных Exception, предполагается начилие их класса в autoload.

##Выводы
####JSON CODER следует использовать для сериализации данных. Не следует использовать для работы с объектами.
####JSON SERIALIZER лучше использовать для сохранения объектов.
