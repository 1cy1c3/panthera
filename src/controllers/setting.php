<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the preferences
$backend->match ( '/settings', function (Request $request) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	$settings = $app ['settings.service']->getData ();
	$form = $app ['form.factory']->createBuilder ( 'form' );
	foreach ( $settings as $setting ) {
		$form->add ( $setting ['Property'], 'text', array (
				'label' => $setting ['Description'],
				'data' => $setting ['Value'],
				'constraints' => array (
						new Assert\NotBlank (),
						new Assert\Length ( array (
								'min' => 1 
						) ) 
				) 
		) );
	}
	$form = $form->getForm ();
	
	if ('POST' == $request->getMethod ()) {
		$form->bind ( $request );
		
		if ($form->isValid ()) {
			$data = $form->getData ();
			foreach ( $data as $key => $value ) {
				$app ['settings.service']->update ( $key, $value );
			}
			// return $app->redirect(settings);
		}
	}
	return $app ['twig']->render ( 'tmpl/backend/settings.html', array (
			'settings' => $settings,
			'updateSetting' => $form->createView () 
	) );
} )->bind ( 'settings' );