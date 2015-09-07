<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the statistics
$backend->match ( '/users', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$users = $app ['users.service']->getAllUsers ();
	
	return $app ['twig']->render ( 'tmpl/backend/users.html', array (
			'users' => $users
	) );
} )->bind ( 'users' );