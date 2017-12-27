# Scripts auxiliares para migrações de ambiente e afins

## Testes

### Instalando PHP Unit

A alternativa mais simples e que funcionou é com php7.1 e utilizando composer:

    # apt install php7.1 php7.1-xml php7.1-mysql php7.1-mbstring
    # apt install composer
	$ cd /path/to/project
    $ composer require --dev phpunit/dbunit

### Rodando testes
    cd test
	../vendor/phpunit/phpunit/phpunit .
