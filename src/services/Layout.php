<?php
/**
 * Displays a layout which is imported by an user
 *
 * @author Rune Krauss
 */
class Layout {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a layout object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Gets the layout ID
	 * 
	 * @return array Layout ID
	 * @access private
	 */
	private function getLayoutId() {
		return $this->app ['settings.service']->getSetting ( 'layoutId' );
	}
	
	/**
	 * Gets the layout name
	 * 
	 * @return array Name of the layout
	 * @access private
	 */
	private function getLayoutName() {
		$layoutId = $this->getLayoutId ();
		$stmt = "SELECT Name FROM " . DB_PRAEFIX . "layout WHERE LayoutId = ?";
		$layout = $this->app ['dbs']['mysql_read']->fetchAssoc ( $stmt, array (
				$layoutId 
		) );
		if ($layout) {
			return $layout ["Name"];
		} else {
			return "default";
		}
		
		$themeId = $this->app ['dbs']['mysql_read']->fetchAssoc ( $stmt, array (
				$name 
		) );
	}
	
	/**
	 * Gets layout path
	 * 
	 * @return array Name of the layout
	 * @access public
	 */
	public function getLayoutPath() {
		return $this->getLayoutName ();
	}
	
	/**
	 * Gets layouts path
	 *
	 * @return array Path of the layouts
	 * @access public
	 */
	public function getLayoutsPath() {
		return $this->app ['settings.service']->getSetting ( 'layoutsPath' );
	}
	
	/**
	 * Gets a color by property
	 *
	 * @param $property Property regarding CSS
	 * @return Hexadecimal code
	 * @access public
	 */
	public function getColor($property) {
		return "#" . $this->app ['settings.service']->getSetting ( "layout" . $property );
	}
	
	/**
	 * Gets the title
	 *
	 * @return array Title
	 * @access public
	 */
	public function getTitle() {
		return $this->app ['settings.service']->getSetting ( 'title' );
	}
}
?>