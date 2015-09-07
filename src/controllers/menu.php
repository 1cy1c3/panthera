<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the menus
$backend->match ( '/menus', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$menus = $app ['menus.service']->getMenu ();
	return $app ['twig']->render ( 'tmpl/backend/menus.html', array (
			'menus' => $menus 
	) );
} )->bind ( 'menus' );

$backend->match ( '/menu-new', function (Request $request) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$form = $app ['form.factory']->createBuilder ( 'form' )->add ( 'Name', 'text', array (
			'label' => 'Menu name',
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
			$menuNameId = $app ['menus.service']->insert ( $data ['Name'] );
			return $app->redirect ( 'menu-edit/' . $menuNameId );
		}
	}
	
	return $app ['twig']->render ( 'tmpl/backend/menu-new.html', array (
			'newMenu' => $form->createView () 
	) );
} )->bind ( 'menu-new' );

$backend->match ( '/menu-edit/{menuNameId}', function (Request $request, $menuNameId) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if ($menuNameId == '0')
		return $app->redirect ( 'menus' );
	
	else {
		foreach ( $_POST as $param => $value ) { // Create 1D-Array
			if (strlen ( $param ) >= 8) {
				if (substr ( $param, - 7, 7 ) == "-delete") {
					$app ['menus.service']->deleteEntry ( substr ( $param, 0, - 7 ), $menuNameId );
				}
			}
		}
	}
	
	$form2 = $app ['form.factory']->createBuilder ( 'form' );
	$form2->add ( 'newTitle', 'text', array (
			'label' => false,
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Length ( array (
							'min' => 1 
					) ) 
			) 
	) );
	$form2->add ( 'newUrl', 'text', array (
			'label' => false,
			'data' => 'http://',
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Length ( array (
							'min' => 1 
					) ) 
			) 
	) );
	$form2 = $form2->getForm ();
	
	if (isset ( $_POST ['insertMenuEntry'] )) {
		if ('POST' == $request->getMethod ()) {
			$form2->bind ( $request );
			$data = $form2->getData ();
			$app ['menus.service']->insertEntry ( $menuNameId, trim ( $data ["newTitle"] ), trim ( $data ["newUrl"] ) );
			// return $app->redirect ( 'menu-edit/' . $menuNameId );
		}
	}
	
	$menus = $app ['menus.service']->setEditable ( $menuNameId );
	
	$form = $app ['form.factory']->createBuilder ( 'form' );
	foreach ( $menus as $menu ) {
		$form->add ( 'title' . $menu ['EntryId'], 'text', array (
				'label' => false,
				'data' => $menu ['Title'],
				'attr' => array (
						'class' => 'formTitle' 
				),
				'constraints' => array (
						new Assert\NotBlank (),
						new Assert\Length ( array (
								'min' => 1 
						) ) 
				) 
		) );
		$form->add ( 'link' . $menu ['EntryId'], 'text', array (
				'label' => false,
				'data' => $menu ['Link'],
				'attr' => array (
						'class' => 'formLink' 
				),
				'constraints' => array (
						new Assert\NotBlank (),
						new Assert\Length ( array (
								'min' => 1 
						) ) 
				) 
		) );
	}
	$form = $form->getForm ();
	
	if (isset ( $_POST ['updateMenuEntries'] )) {
		if ('POST' == $request->getMethod ()) {
			$form->bind ( $request );
			$data = $form->getData ();
			foreach ( $data as $param => $value ) { // Create 1D-Array
				if (strlen ( $param ) >= 5) {
					if (substr ( $param, - 5, 4 ) == "link") { // Create 2D-Array
						$entry [substr ( $param, - 1 )] ["link"] = $value;
					} else if (strlen ( $param ) >= 6 && substr ( $param, - 6, 5 ) == "title") {
						$entry [substr ( $param, - 1 )] ["title"] = $value;
					}
				}
			}
			foreach ( $entry as $entryId => $param ) {
				$app ['menus.service']->updateEntries ( $entryId, $menuNameId, $param ["title"], $param ["link"] );
			}
			// $menuNameId = $app ['menus.service']->insert ( $data ['Name'] );
			// return $app->redirect ( 'menu-edit/' . $menuNameId );
		}
	}
	return $app ['twig']->render ( 'tmpl/backend/menu-edit.html', array (
			'editMenu' => $form->createView (),
			'insertEntry' => $form2->createView () 
	) );
} )->bind ( 'menu-edit' )->value ( 'menuNameId', 0 );

$backend->match ( '/menu-delete/{menuNameId}/{delete}', function (Request $request, $menuNameId, $delete) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if ($menuNameId == '0')
		return $app->redirect ( 'menus' );
	
	$menuName = '';
	
	if ($delete == 1) {
		$menuName = $app ['menus.service']->getMenuName ( $menuNameId );
		$app ['menus.service']->delete ( $menuNameId );
	}
	
	return $app ['twig']->render ( 'tmpl/backend/menu-delete.html', array (
			'menuNameId' => $menuNameId,
			'delete' => $delete,
			'menuName' => $menuName 
	) );
} )->bind ( 'menu-delete' )->value ( 'menuNameId', 0 )->value ( 'delete', 0 );