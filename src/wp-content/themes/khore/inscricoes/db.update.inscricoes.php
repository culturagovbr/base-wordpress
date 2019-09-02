<?php
// insercao da tabela de inscricoes

$sql = "CREATE TABLE wpmc_inscricoes (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `telefone` varchar(55)  NOT NULL,
  `coletivo_entidade` varchar(100) DEFAULT '' NOT NULL,
  `cidade` varchar(100) DEFAULT '' NOT NULL,
  `UF` varchar(2) DEFAULT '' NOT NULL,
  `pais` varchar(100) DEFAULT '' NOT NULL,
  `necessidade_especial` varchar(255) DEFAULT '' NOT NULL,
  `receber_informacoes` varchar(3) DEFAULT 'não' NOT NULL,

  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

