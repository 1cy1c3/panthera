<?php
// Register an autoloader for the controllers
$handle = opendir(__DIR__ . '/controllers');
while ( $file = readdir($handle) ) {
	if (is_file(__DIR__ . '/controllers/' . $file)) {
		require_once 'controllers/' . $file;
	}
}

$app->mount ( '/backend', $backend );