<?php
/**
 * Media server
 * Deals with files and folders
 * Holds upload functions
 *
 * @author Rune Krauss
 */
class Media {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a media object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Gets files by directory name
	 * 
	 * @param string $dir Directory name
	 * @return array Result
	 * @access public
	 */
	public function getFiles($dir) {
		$rs = '';
		$dir = str_replace('-', '/', $dir);
		$handle = opendir ( "../web/assets/img/media" . $dir );
		while ( $file = readdir ( $handle ) ) {
			if (is_file ( "../web/assets/img/media" . $dir . "/" . $file )) {
				$rs [] = $file;
			}
		}
		closedir ( $handle );
		return $rs;
	}
	
	/**
	 * Gets folders by directory name
	 *
	 * @param string $dir Directory name
	 * @return array Result
	 * @access public
	 */
	public function getFolders($dir) {
		$rs = '';
		$dir = str_replace('-', '/', $dir);
		$handle = opendir ( "../web/assets/img/media" . $dir );
		while ( $folder = readdir ( $handle ) ) {
			if (is_dir ( "../web/assets/img/media" . $dir . "/" . $folder )) {
				if ($folder != "." && $folder != "..") {
					$rs [] = $folder;
				}
			}
		}
		closedir ( $handle );
		return $rs;
	}
	
	/**
	 * Inserts a folder
	 *
	 * @param string $path Path to the folder
	 * @param string $name Name of the folder
	 * @return array Result
	 * @access public
	 */
	public function insertFolder($path, $name) {
		$path = str_replace('-', '/', $path);
		$rs = mkdir ( "../web/assets/img/media" . $path . "/" . $name, 0744 );
		$args['name'] = '../web/assets/img/media' . $path . '/' . $name; // Event
		if ($rs) {
			$this->app['eventhandlers.service']->raiseEvent('onFolderAfterInsert', '../', $args);
		}
		return $rs;
	}
	
	/**
	 * Uploads a file
	 *
	 * @param string $path Path to the folder
	 * @param string $name Name of the folder
	 * @return array Result
	 * @access public
	 */
	public function upload($path, $name) {
		$path = str_replace('-', '/', $path);
		$temp = $_FILES ["file"] ["tmp_name"];
		$name = $_FILES ["file"] ["name"];
		$rs = move_uploaded_file ( $temp, "../web/assets/img/media" . $path . "/" . $name );
		$args['name'] = '../web/assets/img/media' . $path . '/' . $name; // Event
		if ($rs) {
			$this->app['eventhandlers.service']->raiseEvent('onFileAfterUpload', '../', $args);
		}
		return $rs;
	}
	
	/**
	 * Inserts an image
	 *
	 * @param string $value Value regarding the image
	 * @param string $title Title of the image
	 * @return array Result
	 * @access public
	 */
	public function insertImage($value, $title) {
		$stmt = "INSERT INTO " . DB_PRAEFIX . "image (Value, Title) 
					VALUES (?, ?)";
		$rs = $this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$value,
				$title
		) );
		if ( $rs ) {
			$args['value'] = $value; // Event
			$args['title'] = $title;
			$this->app['eventhandlers.service']->raiseEvent('onImageAfterRegister', '../', $args);
		}
		return $rs;
	}
	
	/**
	 * Gets all images
	 *
	 * @return string Image
	 * @access public
	 */
	public function getImages() {
		$stmt = "SELECT * 
				FROM " . DB_PRAEFIX . "image";
		$image = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		return $image;
	}
}
?>