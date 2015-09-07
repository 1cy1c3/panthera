<?php
/**
 * Presents the list of plugins
 *
 * @author Rune Krauss
 */
class Pluginlist {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Plugins
	 *
	 * @var string
	 * @access public
	 */
	public $plugins = "";
	
	/**
	 * Initializes a pluginlist object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Adds plugins
	 * 
	 * @param string $pluginInfo Information about this plugin
	 * @access private
	 */
	private function add($pluginInfo) {
		$this->plugins [] = $pluginInfo;
	}
	
	/**
	 * Loads all plugins
	 *
	 * @access public
	 */
	public function loadAll() {
		$handle = opendir ( "../res/plugins/" );
		while ( $folder = readdir ( $handle ) ) {
			if (is_dir ( "../res/plugins/" . $folder )) {
				if (file_exists ( "../res/plugins/" . $folder . "/info.php" )) {
					include ("../res/plugins/" . $folder . "/info.php");
				}
			}
		}
		closedir ( $handle );
	}
}
?>