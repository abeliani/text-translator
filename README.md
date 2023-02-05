# StringTranslator
Performs translation of the received string through the remote services 

## Supported services
* [Yandex](https://yandex.com/dev/translate)
* [MyMemory](https://mymemory.translated.net)

## Install
```
composer require abeliani/string-translator
```

## Usage
You can use static method of StringTranslator class
```
...
use StringTranslator\StringTranslator;
use StringTranslator\Drivers\MyMemoryDriver;
...

// We will be use MyMemory service for our example
$driver = new MyMemoryDriver();
$translatedText = StringTranslator::translate($textToTranslate, 'en', 'gb', $driver)

// Here is the translated text
print $translatedText;
```
Translate the text to many languages
```
...
$translator = StringTranslator::prepareTranslator($someText, 'en', $driver);
$translatedText1 = $translator->translate('gb');
$translatedText2 = $translator->translate('tr');
```

You can call drivers in a chain. if the free request limit is reached in one driver (or got any error), the next request will be direct to the next driver.
```
use StringTranslator\Drivers\YandexDriver;
use StringTranslator\Drivers\MyMemoryDriver;
...

$driver = new YandexDriver($apiKey, new MyMemoryDriver());
$translatedText = StringTranslator::translate($textToTranslate, 'en', 'gb', $driver)
```


Package [homepage](https://treecode.ru)