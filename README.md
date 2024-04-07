# StringTranslator
Translates the received text using various services 

## Supported services
* [MyMemory](https://mymemory.translated.net)
* Offline transliteration
* Offline bijective transliteration

## Install
```
composer require abeliani/string-translator
```

## Usage
Online drivers need to pass psr7 client and request objects

```
$driver = new MyMemoryDriver('token', $psr7Client, $psr7Request);
$translator = new TextTranslator($driver);

// set text and it languge code
$translator->setSource('some text', 'en')

// Here will be the translated text
print $translator->translate('ge');
```
Translate the text to many languages
```
print $translator->translate('fr');
...
print $translator->translate('tr');
```

Chain of driver calls. We can pass the driver into the chain, which will be called if the previous one does not complete the translation.
```
$driver = new OneDriver($apiKey, $psr7Client, $psr7Request, new TwoDriver($apiKey, $psr7Client, $psr7Request));
$translator = new TextTranslator($driver);
```


Package [homepage](https://treecode.ru/article/4/text-translator-transilterator-library)