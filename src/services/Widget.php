<?php
/**
 * Represents the widgets of the graphical window system
 *
 * @author Rune Krauss
 */
class Widget {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a widget
	 *
	 * @param Application $app Application object
	 * @access public
	 */
	public function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Registers a widget
	 * 
	 * @param string $path Path to the widget
	 * @param string $name Name of the widget
	 * @param string $class Class of the widget
	 * @access public
	 */
	public function register($path, $name, $class) {
		global $dbPraefix, $pdo;
		$stmt = "INSERT INTO " . DB_PRAEFIX . "widget (Path, Name, Class) 
				VALUES (?, ?, ?)";
		$rs = $this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$path,
				$name,
				$class 
		) );
		return $rs;
	}
	
	/**
	 * Gets widget properties by a path parameter
	 *
	 * @param string $path Path to the widget
	 * @return array Properties of the widget
	 * @access public
	 */
	public function getWidgetProperties($path) {
		$stmt = "SELECT Path, Class 
				FROM " . DB_PRAEFIX . "widget 
				WHERE Path = ?";
		$widgetProperties = $this->app ['dbs'] ['mysql_read']->fetchAssoc ( $stmt, array (
				$path 
		) );
		return $widgetProperties;
	}
	
	/**
	 * Gets widget properties
	 *
	 * @return array Properties of the widget
	 * @access public
	 */
	public function getWidgetsProperties() {
		$stmt = "SELECT Path, Class
				FROM " . DB_PRAEFIX . "widget";
		$widgetsAttributes = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		return $widgetsAttributes;
	}
	
	/**
	 * Converts widget properties to an object
	 *
	 * @param $widgetProperties Properties of the widget
	 * @return Widget Widget
	 * @access public
	 */
	public function convertWidgetPropertiesToObject($widgetProperties) {
		include_once (__DIR__ . '/../../res/plugins/' . $widgetProperties ["Path"]);
		return new $widgetProperties ["Class"] ();
	}
	
	/**
	 * Gets all widgets
	 *
	 * @return Widget Widget
	 * @access public
	 */
	public function getWidgets() {
		$widgetsProperties = getWidgetsProperties ();
		if ($widgetsProperties) {
			foreach ( $widgetsProperties as $widgetsProperty ) {
				$widget [] = convertWidgetPropertiesToObject ( $widgetsProperty );
			}
		}
		return $widget;
	}
}
?>