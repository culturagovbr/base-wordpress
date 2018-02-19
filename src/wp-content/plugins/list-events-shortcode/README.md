# list-events-shortcode
Shortcode for WordPress to list events retrieved using the Mapas Culturais API

# Como usar

Para listar os eventos dos Museus dentro de uma página ou post utilize o shortcode "list_events" com os atributos:


### Atributo URL (*obrigatório*):
```
[list_events url=<domínio da API do mapas culturais>]
```
O atribuito **URL** recebe como valor o endereço ou domínio onde se encontra a API do mapas culturais.

#### Exemplo:
```
[list_events url=http://museus.cultura.gov.br ]
```

### Atributo date_range (*opcional [default=30]*):
```
[list_events url=<domínio da API do mapas culturais> date_range=<período de dias>]
```
O atributo **date_range** recebe um período de dias para exibir os resultados na pagina inicial.


Caso não seja informado o valor padrão é 30 dias

#### Exemplo:
```
[list_events url=http://museus.cultura.gov.br date_range=20]
```

### Atributo title (*opcional [default=""]*):
```
[list_events url=<domínio da API do mapas culturais> title="<título da página>"]
```
O atributo **title** recebe um texto com o título da página.


Caso não seja informado não será exibido o título na página.

#### Exemplo:
```
[list_events url=http://museus.cultura.gov.br title="Evento dos Museus"]
