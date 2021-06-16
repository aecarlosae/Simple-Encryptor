# How to install via composer

```
composer require aecarlosae/simple-encryptor
```
# How to use

```
try {
    $passphrase = "@#~^*¿}[!9/3_@#~^*¿}[!";
    $encryptedData = Aecarlosae\SimpleEncryptor\Encryptor::getInstance($passphrase)->encrypt('Hello World');
    
    echo Aecarlosae\SimpleEncryptor\Encryptor::getInstance($passphrase)->decrypt($encryptedData);
} catch (Exception $e) {
    echo $e->getMessage();
}
```
