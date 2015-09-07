<?php
// Load and register important libraries for this application
require_once __DIR__ . '/../lib/vendor/autoload.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application ();

$app->register ( new Silex\Provider\FormServiceProvider () );

use Silex\Provider\TwigServiceProvider;
$app->register ( new Silex\Provider\TwigServiceProvider (), array (
		'twig.path' => __DIR__ . '/../res/views',
		'twig.options' => array (
				'debug' => true,
				'cache' => true 
		) 
) );

$app->register ( new Silex\Provider\TranslationServiceProvider (), array (
		'locale_fallback' => 'en' 
) );
$app->register ( new Silex\Provider\UrlGeneratorServiceProvider () );

use Silex\Provider\SessionServiceProvider;
$app->register ( new Silex\Provider\SessionServiceProvider () );

$app->register ( new Silex\Provider\ValidatorServiceProvider () );
// $app->register ( new Silex\Provider\SecurityServiceProvider () );
$app->register ( new Silex\Provider\MonologServiceProvider (), array (
		'monolog.logfile' => __DIR__ . '/../res/log/sys.log',
		'monolog.name' => 'test' 
) );
$app->register ( new Silex\Provider\DoctrineServiceProvider () );
$app->register ( new Silex\Provider\ServiceControllerServiceProvider () );

// Autoload operation
if (version_compare ( PHP_VERSION, "5.1.2" ) >= 0) {
	function autoload($class) {
		@include_once __DIR__ . '/../src/services/' . $class . '.php';
	}
	spl_autoload_register ( 'autoload' );
} else {
	function __autoload($class) {
		@include_once __DIR__ . '/../src/services/' . $class . '.php';
	}
}

// Load the configuration
require_once __DIR__ . '/../res/conf/prod.php';
require_once __DIR__ . '/../res/conf/filter.php';

// Set the log level
$app ['monolog.level'] = LOG_LEVEL;

require_once __DIR__ . '/../src/services.php';

// Use a controller factory to create a backend and frontend
$backend = $app ['controllers_factory'];

// Load the template regarding the backend
$backend->before ( function () use($app) {
	if ($app ['session']->has ( 'msg' )) {
		$msg = $app ['session']->get ( 'msg' );
		$app ['twig']->addGlobal ( 'msg', $msg );
	}
	$menuEntries = $app ['menus.service']->getMenuEntries ( $app ['menus.service']->getIdByName ( '{Admin}' ) );
	$app ['twig']->addGlobal ( 'layout', $app ['twig']->loadTemplate ( 'layouts/backend/admin/layout.html' ) );
	$app ['twig']->addGlobal ( 'menuEntries', $menuEntries );
} );

// Error handling
$app->error ( function (\Exception $e) use($app) {
	if ($e instanceof NotFoundHttpException) {
		return $app ['twig']->render ( 'tmpl/error.html', array (
				'code' => 404 
		) );
	}
	
	$code = ($e instanceof HttpException) ? $e->getStatusCode () : 500;
	return $app ['twig']->render ( 'tmpl/error.html', array (
			'code' => $code 
	) );
} );

require_once __DIR__ . '/../src/controllers.php';

// Start the application
$app->run ();