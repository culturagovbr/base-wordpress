Easy Docs  
===  

Ativando este plugin, um novo tipo de conteúdo será criado: Documentos (slug documents), que é semelhante a uma postagem comum, porém conta com um gerenciador de anexos, tornando possível anexar um ou mais arquivos de forma simples e intuitiva.

Categorias  
---------------  

É possível definir uma hierarquia para os documentos, categorizando-os e ou agrupando-os em diferentes tipos (Ex: Editais, Portarias, Licitações, etc). 

Shortcodes
---------------

O Easy Docs, conta ainda com suporte à shortcodes, permitindo-se criar uma lista de documentos de maneira simples em suas páginas. Para utilizar o shortcode basta inserir o seguinte código:

```sh
[easy-docs]
```

### Parâmetros disponíveis: 

| Nome | Descrição |
| ------ | ------ |
| category | Permite definir uma categoria de documentos para listar. Padrão: Todos |
| items | Quantidade de documentos para listar. Padrão: 5 |
| all-items-label | Permite edtitar o link para todos os documentos. Padrão: Todos os documentos |

Exemplo de uso do shortcode, utilizando todos os parâmetros disponíveis:

```sh
[easy-docs category="editais" items="7" all-items-label="Lista com todos os editais"]
```