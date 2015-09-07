<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the login
$backend->get ( '/login', function () use($app) {
	return $app ['twig']->render ( 'tmpl/backend/login.html' );
} )->bind ( 'login' );

$backend->post ( '/login', function () use($app) {
	$username = $app ['request']->request->get ( 'username' );
	$password = $app ['request']->request->get ( 'password' );
	$app ['users.service']->name = $username;
	$login = $app ['users.service']->checkLogin ( $username, $password );
	if ($login) {
		$app ['session']->set ( 'is_user', true );
		$app ['session']->set ( 'user', $username );
		return $app->redirect ( 'dashboard' );
	} else {
		$msg = "Login failed.";
	}
	return $app ['twig']->render ( 'tmpl/backend/login.html', array(
		"msg" => $msg
	));
} );

$backend->match ( '/logout', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	$app ['session']->clear ();
	$app ['session']->set ( 'msg', 'logged out.' );
	return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
} )->bind ( 'logout' );