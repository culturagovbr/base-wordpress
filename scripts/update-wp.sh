#!/bin/bash
#
# Script de atualização do wordpress
# - atualização de plugins
# - atualização de temas
# - atualização do core
# - envio de alterações
#

#
# configurações padrão
#

LOG_FILE=/var/www/vhosts/dev.redelivre.cultura.gov.br/logs/atualizacao-wp.log
WP_ROOT=/var/www/vhosts/dev.redelivre.cultura.gov.br/git/redelivre/src
BRANCH=atualizacao

ATUALIZAR_SUBMODULOS=0
ATUALIZAR_PLUGINS=0
ATUALIZAR_THEMES=0
COMMIT=0
ATUALIZAR_CORE=0


#
# início #
#

cd $WP_ROOT

echo "=================================="
echo "Iniciando atualização do wordpress"
echo "=================================="

#
# verifica e atualiza submódulos
#

if [ $ATUALIZAR_SUBMODULOS = "1" ]; then
    git submodule update --recursive --remote
    if [ $COMMIT = "1" ]; then
	git add $WP_ROOT/wp-content/
	git commit -m "Atualizando submódulos" 
    fi
fi

if [ $COMMIT = "1" ]; then
    git add $WP_ROOT/wp-content/
    git commit -m "Atualizando plugin $NOME_PLUGIN" 
fi

# atualização de plugins
if [ $ATUALIZAR_PLUGINS = "1" ]; then
  for NOME_PLUGIN in $(wp plugin list --update=available --field=name); do
    VERSAO_PLUGIN=$(wp plugin list --update=available --name=$NOME_PLUGIN --field=version)
    echo "Atualizando plugin $NOME_PLUGIN..."
    echo ""
    
    wp plugin update $NOME_PLUGIN
    echo "Atualizando plugin $NOME_PLUGIN da versão $VERSAO_PLUGIN para versão mais atual" >> $LOG_FILE

    if [ $COMMIT = "1" ]; then
      git add $WP_ROOT/wp-content/plugins/$NOME_PLUGIN
      git commit -m "Atualizando plugin $NOME_PLUGIN" 
    fi
  done
fi


#
# atualização de temas
#

if [ $ATUALIZAR_THEMES = "1" ]; then
  for NOME_THEME in $(wp theme list --update=available --field=name); do
    VERSAO_THEME=$(wp theme list --update=available --name=$NOME_THEME --field=version)
    echo "Atualizando theme $NOME_THEME..."
    echo ""
    
    wp theme update $NOME_THEME...
    echo "Atualizando theme $NOME_THEME da versão $VERSAO_THEME para versão mais atual" >> $LOG_FILE

    if [ $COMMIT = "1" ]; then
      git add $WP_ROOT/wp-content/themes/$NOME_THEME
      git commit -m "Atualizando theme $NOME_THEME" 
    fi
  done
fi

#
# atualização de plugins
#

if [ $ATUALIZAR_PLUGINS = "1" ]; then
  for NOME_PLUGIN in $(wp plugin list --update=available --field=name); do
    VERSAO_PLUGIN=$(wp plugin list --update=available --name=$NOME_PLUGIN --field=version)
    echo "Atualizando plugin $NOME_PLUGIN..."
    echo ""
    
    wp plugin update $NOME_PLUGIN
    echo "Atualizando plugin $NOME_PLUGIN da versão $VERSAO_PLUGIN para versão mais atual" >> $LOG_FILE

    if [ $COMMIT = "1" ]; then
      git add $WP_ROOT/wp-content/plugins/$NOME_PLUGIN
      git commit -m "Atualizando plugin $NOME_PLUGIN" 
    fi
  done
fi

#
# atualização do core
#

WP_CORE_CURRENT_VERSION=$(wp core version)
WP_CORE_NEW_VERSION=$(wp core check-update --field=version)
WP_TYPE_UPDATE=$(wp core check-update --field=update_type)

if [ $WP_CORE_CURRENT_VERSION != $WP_CORE_NEW_VERSION ]; then
  echo "CORE DESATUALIZADO. Atualizar da versão $WP_CORE_CURRENT_VERSION para $WP_CORE_NEW_VERSION ?"
  if [ $ATUALIZAR_CORE = 0 ]; then
      read -p "Atualizar o core do Wordpress? (s/n)" choice
      case "$choice" in
	  s|S ) # sim
	      echo "Atualizando core...";
	      echo "Atualizando core da versão $WP_CORE_CURRENT_VERSION para $WP_CORE_NEW_VERSION" >> $LOG_FILE
	      wp core update
	      
	      if [ $COMMIT = "1" ]; then
		  git add $WP_ROOT
		  git commit -m "Atualizando core do wordpress para versão $WP_CORE_VERSION" 
	      fi
	      ;;   
	  n|N ) # nao
	      echo "no"
	      ;; 
	  * ) # outros
	      echo "Escolha inválida!"
	      ;;
      esac
  fi
fi



# se commit ativado
if [ $COMMIT = "1" ]; then
  echo "Enviando commit..."
  #git push origin $BRANCH
fi
