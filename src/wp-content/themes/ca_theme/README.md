# Empurrando Juntos Wordpress Theme

Este projeto é o tema wordpress para uso com a plataforma Empurrando Juntos

## Instalação de plugins necessários para o tema

- [Advanced Custom Fields](https://br.wordpress.org/plugins/advanced-custom-fields/)
- [WP-PageNavi](https://br.wordpress.org/plugins/wp-pagenavi/)

## Configuração

### Links permanentes

Ativar opção `Nome do post`

### Advanced Custom Fields

#### Para o Painel Home

1. Criar uma categoria de posts chamada `Painel home`
2. Criar um grupo de campo chamado `Painel Home`
3. Criar uma aba chamada `Frase`
4. Criar o campo de rótulo `Frase`, nome `frase_painel` e tipo `Editor Wysiwyg`, basic sem botão de mídia e sem condições para exibição
5. Criar outra aba chamada `Descrições`
6. Dentro dela, criar para cada um dos três ítens do Painel Home, os conjuntos de campos abaixo
7. Campo de rótulo `Título 1`, nome `titulo_1_painel`, tipo `Texto`, sem formatação e o restante das configurações padrão
8. Campo de rótulo `Link 1 painel`, nome `link_1_painel`, tipo `Texto`, sem formatação e o restante das configurações padrão
9. Campo de rótulo `Chamada 1`, nome `chamada_1_painel`, tipo `Texto`, sem formatação e o restante das configurações padrão
10. Campo de rótulo `Ícone 1`, nome `icone_1_painel`, tipo `Texto`, sem formatação e o restante das configurações padrão
11. Campo de rótulo `Texto 1`, nome `texto_1_painel`, tipo `Editor Wysiwyg`, full e com botão de mídia e sem condições para exibição
12. Repetir o mesmo processo dos ítens (6 a 10) para os outros dois ítens trocando os números para 2 e 3
13. Configurar a condição de mostrar esse grupo de campos se a Categoria do post for igual a `Painel home`
14. Nas configurações de ocultar na tela, selecionar (ocultar) tudo menos `Permalink` e `Categorias`

#### Para as Conversas

1. Criar um grupo de campo chamado `Conversa`
2. Criar uma aba chamada `Àrea Conversa`
3. Criar um campo de rótulo `Campo opine`, de nome `campo_opine`, tipo `Editor Wysiwyg`, full e com botão de mídia e sem condições para exibição
4. Criar um campo de rótulo `Campo diálogo`, de nome `campo_dialogo`, tipo `Editor Wysiwyg`, full e com botão de mídia e sem condições para exibição
5. Criar um campo de rótulo `Campo resposta`, de nome `campo_resposta`, tipo `Editor Wysiwyg`, full e com botão de mídia e sem condições para exibição
6. Criar uma aba chamada `Área Slideshow`. Para cada slide (o tema atualmente suporta até 7), será necessário criar 4 campos: Frase, Fonte, Link, Imagem
7. Criar um campo de rótulo `Frase slide 1`, de nome `frase_slide_1`, tipo `Texto`, sem formatação e o restante das configurações padrão
8. Criar um campo de rótulo `Fonte da informação slide 1`, de nome `fonte_da_informacao_slide_1`, tipo `Botão de Rádio` com opção sim e não, valor padrão `nao`
9. Criar um campo de rótulo `Link Slide 1`, de nome `link_slide_1`, tipo `Texto`, sem formatação e o restante das configurações padrão
10. Criar um campo de rótulo `Imagem Slide 1`, de nome `imagem_slide_1`, tipo `Imagem`, com parametro de retorno `URL da imagem`, Pré-vizualização `Completo` e Biblioteca `Todos`
11. Repetir o mesmo processo dos ítens (7 a 10) para as outras imagens do slideshow trocando os números para 2, 3, 4 e assim por diante
12. Configurar a condição de mostrar esse grupo de campos se a Categoria do post for igual a `Conversas`
13. Nas configurações de ocultar na tela, selecionar (ocultar) tudo menos `Permalink`, `Imagem Destacada` e `Categorias`


### WP-PageNavi

Esse plugin, basta ativar e usar as configurações default

## Utilização do tema

1. Criar um post na categoria `Painel home`, ideal utilizar o mesmo nome no post para facilitar
2. Preencher os campos no post de acordo com o que se deseja exibir no Painel home
3. Criar um post na categoria `Conversas` e preencher os campos com as três fases da conversa e os parametros e imagens do Slideshow
4. O post de Conversa que estiver marcado na categoria `Acontecendo agora`, será exibido com destaque na página inicial do tema
5. A `Imagem Destacada` do post de Conversa será usada nos thumbnails das Conversas
6. O tema está configurado para indicar 4 páginas wordpress: `Perguntas frequentes`, `Problemas ou sugestões`, `Sobre nós` ou `Termos de uso`. Se essas páginas não forem utilizadas na sua instalação, ou tiverem outro nome, é importante alterar no código do tema
