<?php
// adiciona campo da tabela de inscricoes

$sql = "ALTER TABLE `wpmc_inscricoes` ADD `data_inscricao` DATE NOT NULL AFTER `ID`;";