#!/bin/bash
#
# Script de atualização do wordpress
# - atualização de plugins
# - atualização de temas
# - atualização do core
# - envio de alterações
#


###
# define funções
###

function set_configs {
    #
    # configurações padrão
    #
    
    LOG_FILE=/tmp/atualizacao-wp.log
    WP_ROOT=/var/www/base-wordpress/src
    BRANCH=atualizacao
    
    ATUALIZAR_SUBMODULOS=0
    ATUALIZAR_PLUGINS=1
    ATUALIZAR_THEMES=1
    COMMIT=0
    PUSH_COMMIT=0
    ATUALIZAR_CORE=1
}


function check_configs {
    if [ ! -f "$LOG_FILE" ]; then
	touch $LOG_FILE || die "Não foi possível criar o arquivo de log! \nCorrija alterando a variável LOG_FILE"
    fi

    if [ ! -d "$WP_ROOT" ]; then
	die "Diretório $WP_ROOT não existe! \nCorrija alterando a variável WP_ROOT"
    fi

    is_wp=$(wp core version 2>&1 > /dev/null)
    if [[ $is_wp == *"Error"* ]]; then
	die "O diretório $WP_ROOT não é uma instalação de wordpress válida! \nCorrija alterando a variável WP_ROOT"
    else
	is_wp=''
    fi	    
    
}

# report
function report_updates {
    echo "----------------------------------"
    echo "Temas com atualizações pendendes?"
    wp theme list --update=available
    echo "----------------------------------"
    echo "Plugins com atualizações pendendes?"
    wp plugin list --update=available
    echo "----------------------------------"
    echo "Core com atualizações pendendes?"
    wp core check-update   
}

# cancela updates
function reset_updates {
    git reset HEAD
    git checkout .
}

#
# verifica e atualiza submódulos
#
function atualizar_submodulos {
    git submodule update --recursive --remote || die "Falha ao atualizar submódulos"
    if [ "$COMMIT" = "1" ]; then
	git add $WP_ROOT/wp-content/ || die "Submódulos - falha ao adicionar arquivos ao commit"
	git commit -m "Atualizando submódulos" 
    fi
}


#
# atualização de temas
#
function atualizar_themes {
  for NOME_THEME in $(wp theme list --update=available --field=name); do
    VERSAO_THEME=$(wp theme list --update=available --name=$NOME_THEME --field=version)
    echo "Atualizando theme $NOME_THEME..."
    echo ""
    
    wp theme update $NOME_THEME... || die "Falha ao atualizar thema $NOME_THEME..."
    echo "Atualizando theme $NOME_THEME da versão $VERSAO_THEME para versão mais atual" >> $LOG_FILE

    if [ "$COMMIT" = "1" ]; then
      git add $WP_ROOT/wp-content/themes/$NOME_THEME || die "Temas - falha ao adicionar arquivos do tema $NOME_THEME ao commit"
      git commit -m "Atualizando theme $NOME_THEME" 
    fi
  done
}


#
# atualização de plugins
#
function atualizar_plugins {
  for NOME_PLUGIN in $(wp plugin list --update=available --field=name); do
    VERSAO_PLUGIN=$(wp plugin list --update=available --name=$NOME_PLUGIN --field=version)
    echo "Atualizando plugin $NOME_PLUGIN..."
    echo ""
    
    wp plugin update $NOME_PLUGIN || die "Falha ao atualizar thema $NOME_THEME..."
    echo "Atualizando plugin $NOME_PLUGIN da versão $VERSAO_PLUGIN para versão mais atual" >> $LOG_FILE

    if [ "$COMMIT" = "1" ]; then
	git add $WP_ROOT/wp-content/plugins/$NOME_PLUGIN || die "Plugins - Falha ao adicionar arquivos do plugin $NOME_PLUGIN ao commit"
      git commit -m "Atualizando plugin $NOME_PLUGIN" 
    fi
  done
}

#
# atualização do core
#

function atualizar_core {
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
		    wp core update || die "Falha ao atualizar core do Wordpress"
		    
		    if [ "$COMMIT" == "1" ]; then
			git add $WP_ROOT || die "Core - Falha ao adicionar arquivos do core ao commit"
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
}

function update {
    if [ "$ATUALIZAR_SUBMODULOS" = "1" ]; then
	atualizar_submodulos
    fi
    
    if [ "$ATUALIZAR_PLUGINS" = "1" ]; then
	atualizar_plugins
    fi
    
    if [ "$ATUALIZAR_THEMES" = "1" ]; then
	atualizar_themes
    fi
    
    if [ "$ATUALIZAR_PLUGINS" = "1" ]; then
	atualizar_plugins
    fi
    
    if [ "$ATUALIZAR_CORE" = "1" ]; then
	atualizar_core
    fi
    
    
    # se commit ativado
    if [ "$COMMIT" = "1" ]; then
	echo "Fazendo push dos commits..."
	git push origin $BRANCH || die "Falha ao fazer push"
    fi
}

function main {
    set_configs
    check_configs
    
    if [ "$1" = "reset" ]; then
	reset_updates
    elif [ "$1" = "update" ]; then
	update
    elif [ "$1" = "report" ]; then
	report_updates
    elif [ "$1" = "" ]; then
	echo "Escolha a opção: update, reset, report"
    fi
}

function die {
    local message=$1
    [ -z "$message" ] && message="Died"
    echo -e "$message at ${BASH_SOURCE[1]}:${FUNCNAME[1]} line ${BASH_LINENO[0]}." >&2
    exit 1
}

main $1
