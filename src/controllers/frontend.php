<?php
// Router for the frontend
$app->get ( ('/{alias}'), function ($alias) use($app) {
	$app ['pages.service']->setProperties ( $alias );

	$header = $app ['pages.service']->getHeader ();

	$globalMenus = $app ['menus.service']->getGlobalMenu ();
	$localMenus = $app ['menus.service']->getLocalMenu ();

	$breadcrumb = $app ['pages.service']->getBreadcrumb();

	$content = $app ['pages.service']->getContent ();

	$localMenuExists = $app['menus.service']->localMenuExists();
	$title = $app ['layouts.service']->getTitle ();
	$layoutsPath = $app ['layouts.service']->getLayoutsPath ();
	$bgcolor = $app ['layouts.service']->getColor ( 'bgcolor' );
	$forecolor = $app ['layouts.service']->getColor ( 'bgcolor' );
	$highlight1 = $app ['layouts.service']->getColor ( 'bgcolor' );
	$highlight2 = $app ['layouts.service']->getColor ( 'bgcolor' );

	return $app ['twig']->render ( 'layouts/frontend/' . $app ['layouts.service']->getLayoutPath () . '/index.html', array (
			'title' => $title,
			'layoutsPath' => $layoutsPath,
			'bgcolor' => $bgcolor,
			'forecolor' => $forecolor,
			'highlight1' => $highlight1,
			'highlight2' => $highlight2,
			'globalMenus' => $globalMenus,
			'localMenus' => $localMenus,
			'localMenuExists' => $localMenuExists,
			'breadcrumb' => $breadcrumb,
			'content' => $content,
	) );
} )->value ( 'alias', '' );