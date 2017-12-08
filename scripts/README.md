# Scripts auxiliares para migrações de ambiente e afins

## Testes

### Instalando PHP Unit
    $ wget https://phar.phpunit.de/phpunit.phar
    $ chmod +x phpunit.phar
    $ sudo mv phpunit.phar /usr/local/bin/phpunit
    $ phpunit --version
    PHPUnit 6.5.0 by Sebastian Bergmann and contributors.

### Rodando testes
    phpunit --bootstrap MovimentarAmbiente.class.php test/ScriptsTest.php