# How to install via composer

```
composer require aecarlosae/simple-encryptor

```
# How to use

```
try {
    $phrase = Aecarlosae\SimpleEncryptor\Encryptor::getInstance()->encrypt('Hello World');
    echo Aecarlosae\SimpleEncryptor\Encryptor::getInstance()->decrypt($phrase);
} catch (Exception $e) {
    echo $e->getMessage();
}

```
