<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the plugins
$backend->match ( '/plugins/{status}/{path}', function ($status, $path) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$app ['pluginlist.service']->loadAll ();
	
	foreach ( $app ['pluginlist.service']->plugins as $plugin ) {
		if ($status == 'activate' && $path == $plugin->path) {
			$plugin->activate ( $app );
		} elseif ($status == 'deactivate' && $path == $plugin->path) {
			$plugin->deactivate ( $app );
		}
		$isActivated [] = $plugin->isActivated ( $app );
	}
	
	return $app ['twig']->render ( 'tmpl/backend/plugins.html', array (
			'plugins' => $app ['pluginlist.service']->plugins,
			'status' => $status,
			'isActivated' => $isActivated 
	) );
} )->bind ( 'plugins' )->value ( 'status', false )->value ( 'path', false );