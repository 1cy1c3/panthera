<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the database
$backend->match ( '/database/{dbpage}', function ($dbpage) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$navi = 3;
	// $tables = $app ['mysql.service']->getTables($dbpage * $navi, $navi);
	
	foreach ( $app ['mysql.service']->getTables ( $dbpage * $navi, $navi ) as $table ) {
		$tbls [] = $table;
		$tuples [] = $app ['mysql.service']->countTuples ( $table );
	}
	
	$pageCount = ceil ( $app ['mysql.service']->countData / $navi );
	if ($dbpage > $pageCount) {
		return $app->redirect ( $app ['url_generator']->generate ( 'database' ) );
	}
	
	for($link = 0; $link < $pageCount; $link ++) {
		$links [] = $link;
	}
	
	return $app ['twig']->render ( 'tmpl/backend/db.html', array (
			'tuples' => $tuples,
			'links' => $links,
			'tbls' => $tbls 
	) );
} )->bind ( 'database' )->value ( 'dbpage', 0 );

$backend->match ( '/table-show/{table}', function ($table) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$columns = $app ['mysql.service']->getColumns ( $table );
	
	$tbls = $app ['mysql.service']->getTableContent ( $table );
	
	return $app ['twig']->render ( 'tmpl/backend/table-show.html', array (
			'table' => $table,
			'columns' => $columns,
			'tbls' => $tbls 
	) );
} )->bind ( 'table-show' )->value ( 'table', '' );

$backend->match ( '/tuple-edit/{table}/{limit}', function ($table, $limit) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$data = $app ['mysql.service']->getData ( $table, $limit, 1 );
	
	foreach ( $data as $datum ) {
		$test [] = $datum;
	}
	if ($test [0] == '') {
		return $app->redirect ( $app ['url_generator']->generate ( 'database' ) );
	}
	
	if (isset ( $_POST ["updateTuple"] )) {
		$olddata = $data;
		foreach ( $data as $key => $value ) {
			if (isset ( $_POST [$key] )) {
				$data [$key] = $_POST [$key];
			}
		}
		$app ['mysql.service']->update ( $olddata, $data, $table );
	}
	
	$columnss = $app ['mysql.service']->getColumns ( $table );
	foreach ( $columnss as $column ) {
		$columns [] = $column;
		$length [] = $app ['mysql.service']->getMaxLength ( $column ["Type"] );
	}
	
	return $app ['twig']->render ( 'tmpl/backend/tuple-edit.html', array (
			'table' => $table,
			'limit' => $limit,
			'columns' => $columns,
			'data' => $data,
			'length' => $length 
	) );
} )->bind ( 'tuple-edit' )->value ( 'table', '' )->value ( 'limit', '' );