<?php
function filterFilename($filename) {
	$filename = strtolower ( $filename );
	$filename = preg_replace ( "/[^a-z0-9\-\/]/i", "", $filename );
	if ($filename [0] == "/") {
		$filename = substr ( $filename, 1 );
	}
	$filename .= ".html";
	return $filename;
}
function security($validate = null) {
	if ($validate == null) {
		foreach ( $_REQUEST as $key => $val ) {
			if (is_string ( $val )) {
				$_REQUEST [$key] = htmlspecialchars ( $val, ENT_COMPAT, 'UTF-8' );
			} else if (is_array ( $val )) {
				$_REQUEST [$key] = security ( $val );
			}
		}
		foreach ( $_GET as $key => $val ) {
			if (is_string ( $val )) {
				$_GET [$key] = htmlspecialchars ( $val, ENT_COMPAT, 'UTF-8' );
			} else if (is_array ( $val )) {
				$_GET [$key] = security ( $val );
			}
		}
		foreach ( $_POST as $key => $val ) {
			if (is_string ( $val )) {
				$_POST [$key] = htmlspecialchars ( $val, ENT_COMPAT, 'UTF-8' );
			} else if (is_array ( $val )) {
				$_POST [$key] = security ( $val );
			}
		}
		foreach ( $_FILES as $key => $val ) {
			if (is_string ( $val )) {
				$_POST [$key] = htmlspecialchars ( $val, ENT_COMPAT, 'UTF-8' );
			} else if (is_array ( $val )) {
				$_POST [$key] = security ( $val );
			}
		}
	} else {
		foreach ( $validate as $key => $val ) {
			if (is_string ( $val )) {
				$validate [$key] = htmlspecialchars ( $val, ENT_COMPAT, 'UTF-8' );
			} else if (is_array ( $val )) {
				$validate [$key] = security ( $val );
			}
			return $validate;
		}
	}
}
security ();