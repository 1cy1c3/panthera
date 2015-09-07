<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the pages
$backend->match ( '/pages', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$pages = $app ['pages.service']->getPages ();
	return $app ['twig']->render ( 'tmpl/backend/pages.html', array (
			'pages' => $pages 
	) );
} )->bind ( 'pages' );

$backend->match ( '/page-new', function (Request $request) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$form = $app ['form.factory']->createBuilder ( 'form' )->add ( 'Alias', 'text', array (
			'label' => 'Page alias',
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Regex ( array (
							'pattern' => '/([a-zA-Z])/i' 
					) ),
					/*new Assert\Type ( array (
					 'type' => 'string',
							'message' => '{{ type }}',
					),*/ new Assert\Length ( array (
							'min' => 1 
					) ) 
			) 
	) )->getForm ();
	
	if ('POST' == $request->getMethod ()) {
		$form->bind ( $request );
		
		if ($form->isValid ()) {
			$data = $form->getData ();
			$alias = $app ['pages.service']->insert ( trim ( $data ['Alias'] ) );
			return $app->redirect ( 'page-edit/' . $data ['Alias'] );
		}
	}
	
	return $app ['twig']->render ( 'tmpl/backend/page-new.html', array (
			'newPage' => $form->createView () 
	) );
} )->bind ( 'page-new' );

$backend->match ( '/page-edit/{alias}', function (Request $request, $alias) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if ($alias == '0')
		return $app->redirect ( 'pages' );
		
	$app ['pages.service']->setProperties ( $alias );
	
	$content = $app ['pages.service']->readContent ( '../' );
	$menus = $app ['menus.service']->getMenu ();
	
	if (isset ( $_POST ["menu"] )) {
		$menuEntries2 = $app ['menus.service']->getMenuEntries ( $_POST ['menu'] );
	} else if ($app ['pages.service']->menuId > 0) {
		$menuEntries2 = $app ['menus.service']->getMenuEntries ( $app ['pages.service']->menuId );
	}
	
	$postMenu = '';
	
	$form = $app ['form.factory']->createBuilder ( 'form' );
	$form->add ( 'pageTitle', 'text', array (
			'label' => 'Title',
			'data' => $app ['pages.service']->title,
			'attr' => array (
					'class' => 'pageTitle' 
			),
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Length ( array (
							'min' => 1 
					) ) 
			) 
	) );
	$form->add ( 'pageAlias', 'text', array (
			'label' => 'Alias',
			'data' => $app ['pages.service']->alias,
			'attr' => array (
					'class' => 'pageAlias' 
			),
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Length ( array (
							'min' => 1 
					) ) 
			) 
	) );
	$form->add ( 'pageContent', 'textarea', array (
			'label' => 'Content',
			'data' => $content 
	) );
	$form = $form->getForm ();
	
	if (isset ( $_POST ['updatePage'] ) or isset ( $_POST ['menu'] )) {
		if ('POST' == $request->getMethod ()) {
			$postMenu = $_POST ['menu'];
			$form->bind ( $request );
			$data = $form->getData ();
			$data ['pageContent'] = stripslashes ( $data ['pageContent'] );
			$app ['pages.service']->deleteContent ( '../' );
			$app ['pages.service']->update ( trim ( $data ['pageAlias'] ), trim ( $data ['pageTitle'] ), trim ( $_POST ['menu'] ) );
			$app ['pages.service']->writeContent ( '../', $data ['pageContent'] );
		}
	}
	
	$images = $app ['media.service']->getImages ();
	
	return $app ['twig']->render ( 'tmpl/backend/page-edit.html', array (
			'editPage' => $form->createView (),
			'menus' => $menus,
			'menuEntries2' => $menuEntries2,
			'menuId' => $app ['pages.service']->menuId,
			'postMenu' => $postMenu,
			'images' => $images 
	) );
} )->bind ( 'page-edit' )->value ( 'alias', 0 );

$backend->match ( '/page-delete/{alias}/{delete}', function ($alias, $delete) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if ($alias == '0')
		return $app->redirect ( 'pages' );
	
	$app ['pages.service']->setProperties ( $alias );
	
	if ($delete == 1) {
		$app ['pages.service']->deleteContent ( '../' );
		$app ['pages.service']->delete ( $alias );
	}
	return $app ['twig']->render ( 'tmpl/backend/page-delete.html', array (
			'delete' => $delete,
			'alias' => $alias 
	) );
} )->bind ( 'page-delete' )->value ( 'alias', 0 )->value ( 'delete', 0 );
