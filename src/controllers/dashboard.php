<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the dashboard
$backend->get ( '', function () use($app) {
	if (null === $user = $app ['session']->get ( 'user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	return $app->redirect ( $app ['url_generator']->generate ( 'dashboard' ) );
} );

$backend->match ( '/dashboard', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	// $app['monolog']->addInfo('test');
	
	$dashboardId = $app ['dashboards.service']->dashboardId = 1;
	$app ['dashboards.service']->loadWidgets ();
	$widgets = $app ['dashboards.service']->display ();
	return $app ['twig']->render ( 'tmpl/backend/dashboard.html', array (
			'widgets' => $widgets,
			'dashboardId' => $dashboardId 
	) );
} )->bind ( 'dashboard' );