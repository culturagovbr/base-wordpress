# Documentação sobre migrações

Existem vários tipos de migrações nos ambientes wordpress relacionados a instalações single e multi-site. O objetivo desse documento é agregar informações e dicas de procedimentos para agilizar a movimentação de sites wordpress entre diferentes ambientes.

Exemplos de migrações:

    - mover single site para instalação multisite
           (o) ------------>  ooo(o)o    

    - mover site em uma instalação multisite para outra instalação multisite
           oo(o)oo ------------>  oo(o)oo
    
    - mover site de uma instalação multisite para instalação single site
           oo(o)oo ------------>  (o)



## Converter instalação multisite de produção para ambiente de desenvolvimento

### Adicione os sites no DNS local (opcional)

    /etc/hosts
    127.0.0.1       dev.base-wp.cultura.gov.br
    127.0.0.1       dev.intranet.cultura.gov.br

### Transformando banco de produção em local

    UPDATE wpminc_options SET option_value = REPLACE(option_value, 'base-wp.cultura.gov.br', 'dev.base-wp.cultura.gov.br') WHERE option_value LIKE '%base-wp.cultura.gov.br%'
    UPDATE wpminc_site SET domain = 'http://dev.base-wp.cultura.gov.br/';


### Atualizando sites para endereço local

Loop na tabela com todos os sites
    UPDATE wpminc_blogs SET domain = REPLACE(domain, 'cultura.gov.br', 'dev.cultura.gov.br') WHERE domain LIKE '%.cultura.gov.br%';


### Atualizando configurações de cada site

    UPDATE wpminc_[id]_options SET option_value = REPLACE(option_value, 'cultura.gov.br', 'dev.cultura.gov.br') WHERE option_value LIKE '%cultura.gov.br%';


### Atualizar domains

    SELECT * FROM wpminc_domain_mapping
    # para cada site
    UPDATE wordpress_base.wpminc_domain_mapping SET domain = 'dev.intranet.cultura.gov.br' WHERE domain = 'intranet.cultura.gov.br'


### Wp cli para o resto

Caso seja necessário atualizar urls, você pode utilizar o wp cli.

    # Atualizando links e endereços em posts
    cd [path_to_wp_install]
    wp search-replace 'base-wp.cultura.gov.br' 'dev.base-wp.cultura.gov.br' wp_posts


