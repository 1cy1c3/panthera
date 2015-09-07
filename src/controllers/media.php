<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
// Router for the media server
$backend->match ( '/media/{dir}', function (Request $request, $dir) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	$path = '';
	
	foreach ( explode ( "-", $dir ) as $dirLink ) {
		$path .= "-" . $dirLink;
		$hrefs [] = $path;
		if ($path == "-") {
			$links [] = '/';
			$path = '';
		} else {
			$links [] = $dirLink;
		}
	}
	
	$app ['links'] = $links;
	
	$form = $app ['form.factory']->createBuilder ( 'form' )->add ( 'folderName', 'text', array (
			'constraints' => array (
					new Assert\NotBlank (),
					new Assert\Length ( array (
							'min' => 1 
					) ),
					new Assert\Regex ( array (
							'pattern' => '/([a-zA-Z])/i' 
					) ) 
			) 
	) )->getForm ();
	
	if ('POST' == $request->getMethod ()) {
		$form->bind ( $request );
		if ($form->isValid ()) {
			
			$data = $form->getData ();
			$app ['media.service']->insertFolder ( $dir, $data ['folderName'] );
		}
	}
	
	$subfolders = $app ['media.service']->getFolders ( '-' . $dir );
	$files = $app ['media.service']->getFiles ( '-' . $dir );
	
	$dir2 = '';
	
	if ($dir !== '') {
		$dir2 = '/';
		$dir2 .= $dir;
	}
	
	$fileDir = str_replace ( '-', '/', $dir );
	
	return $app ['twig']->render ( 'tmpl/backend/media.html', array (
			'hrefs' => $hrefs,
			'links' => $links,
			'dir' => $dir,
			'dir2' => $dir2,
			'insertFolder' => $form->createView (),
			'subfolders' => $subfolders,
			'files' => $files,
			'fileDir' => $fileDir 
	) );
} )->bind ( 'media' )->value ( 'dir', '' );

$backend->match ( '/media-upload/{dir}', function (Request $request, $dir) use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if (! $_FILES ["file"])
		return $app->redirect ( 'media' );
	
	if ($app ['media.service']->upload ( $dir, $_FILES ["file"] )) {
		$name = $_FILES ['file'] ['name'];
		$dir = str_replace ( '-', '/', $dir );
		$pathInfo = pathinfo ( "../web/assets/img/media" . $dir . "/" . $name );
		strtolower ( $pathInfo ["extension"] == "png" ) or strtolower ( $pathInfo ["extension"] == "jpg" ) or strtolower ( $pathInfo ["extension"] == "jpeg" ) or strtolower ( $pathInfo ["extension"] == "gif" ) or strtolower ( $pathInfo ["extension"] == "bmp" );
	}
	
	return $app ['twig']->render ( 'tmpl/backend/media-upload.html', array (
			'dir' => $dir,
			'name' => $name 
	) );
} )->bind ( 'media-upload' )->value ( 'dir', '' );

$backend->match ( '/media-image', function () use($app) {
	if (! $app ['session']->get ( 'is_user' )) {
		return $app->redirect ( $app ['url_generator']->generate ( 'login' ) );
	}
	
	if (! $_POST ["value"] or ! $_POST ["title"])
		return $app->redirect ( 'media' );
	
	$register = $app ['media.service']->insertImage ( $_POST ["value"], $_POST ["title"] );
	return $app ['twig']->render ( 'tmpl/backend/media-image.html', array (
			'register' => $register,
			'title' => $_POST ["title"] 
	) );
} )->bind ( 'media-image' );