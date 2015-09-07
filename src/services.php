<?php
// Share all services regarding dependency injection
$app ['menus.service'] = $app->share ( function () use($app) {
	return new Menu ( $app );
} );
$app ['pages.service'] = $app->share ( function () use($app) {
	return new Page ( $app );
} );
$app ['users.service'] = $app->share ( function () use($app) {
	return new User ( $app );
} );
$app ['settings.service'] = $app->share ( function () use($app) {
	return new Setting ( $app );
} );
$app ['layouts.service'] = $app->share ( function () use($app) {
	return new Layout ( $app );
} );
$app ['dashboards.service'] = $app->share ( function () use($app) {
	return new Dashboard ( $app );
} );
$app ['widgets.service'] = $app->share ( function () use($app) {
	return new Widget ( $app );
} );
$app ['media.service'] = $app->share ( function () use($app) {
	return new Media ( $app );
} );
$app ['plugininfo.service'] = $app->share ( function () use($app) {
	return new Plugininfo ( $app );
} );
$app ['pluginlist.service'] = $app->share ( function () use($app) {
	return new Pluginlist ( $app );
} );
$app ['mysql.service'] = $app->share ( function () use($app) {
	return new Mysql ( $app );
} );
$app ['eventhandlers.service'] = $app->share ( function () use($app) {
	return new Eventhandler ( $app );
} );