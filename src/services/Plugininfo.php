<?php
use Silex\Application;

/**
 * Presents information about plugins
 *
 * @author Rune Krauss
 */
class Plugininfo {
	/**
	 * Path to the plugin
	 * 
	 * @var string
	 * @access public
	 */
	public $path = "";
	
	/**
	 * Name of the plugin
	 *
	 * @var string
	 * @access public
	 */
	public $name = "";
	
	/**
	 * Version of the plugin
	 *
	 * @var string
	 * @access public
	 */
	public $version = "";
	
	/**
	 * Description of the plugin
	 *
	 * @var string
	 * @access public
	 */
	public $description = "";
	
	/**
	 * Author of the plugin
	 *
	 * @var string
	 * @access public
	 */
	public $author = "";
	
	/**
	 * Link to the plugin website
	 *
	 * @var string
	 * @access public
	 */
	public $link = "";
	
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a plugininfo object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app = 0) {
		$this->app = $app;
	}
	
	/**
	 * Checks whether a plugin is activated
	 * 
	 * @param Application $app
	 * @return bool Activated plugin
	 * @access public
	 */
	public function isActivated($app) {
		$stmt = "SELECT COUNT(*) AS Count
				FROM " . DB_PRAEFIX . "activated_plugin 
				WHERE Path = ?";
		$plugin = $app ['dbs'] ['mysql_read']->fetchAssoc ( $stmt, array (
				$this->path,
		) );
		if ($plugin['Count']) {
			return $plugin ['Count'] > 0;
		} else {
			return 0;
		}
	}
	
	/**
	 * Activates a plugin
	 *
	 * @param Application $app
	 * @access public
	 */
	public function activate($app) {
		$stmt = "INSERT INTO " . DB_PRAEFIX . "activated_plugin (Path) 
				VALUES (?)";
		$app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$this->path,
		) );
		
		@include ("../res/plugins/" . $this->path . "/activate.php");
	}
	
	/**
	 * Deactivates a plugin
	 *
	 * @param Application $app
	 * @access public
	 */
	public function deactivate($app) {
		$stmt = "DELETE FROM " . DB_PRAEFIX . "activated_plugin 
				WHERE Path = ?";
		$app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$this->path,
		) );
		@include ("../res/plugins/" . $this->path . "/activate.php");
	}
}
?>