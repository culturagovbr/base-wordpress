<?php
	return array(
		'host'      => 'localhost',
		'port'      => '5432',
		'dbname'    =>'some-database',
		'user'      =>'some-postgres-user',
		'password'  => '0123456789',
		'query'     => 'SELECT DISTINCT ID, 
                	title, 
                	description,
				FROM books;',
		'query-single'=> 'SELECT ID, 
                	title, 
                	description,
				FROM books
				WHERE ID = 1;'
	);