<?php
use Symfony\Component\Config\Definition\IntegerNode;

/**
 * Graphical window system with widgets
 *
 * @author Rune Krauss
 */
class Dashboard {
	/**
	 * Application object
	 * 
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * ID of the specific dashboard
	 * 
	 * @var int
	 * @access public
	 */
	public $dashboardId = '';
	
	/**
	 * Specific column of the dashboard
	 * @var array
	 * @access private
	 */
	private $cols = '';
	
	/**
	 * Initializes an dashboard object
	 * 
	 * @param Application $app Application object
	 * @access public
	 */
	public function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Loads widgets by a column
	 * Selects the specific path and gets dashboards
	 * Saves widgets in an array
	 * 
	 * @param array $col Column of the dashboard
	 * @return array Array of results
	 * @access private
	 */
	private function loadWidgetsByCol($col) {
		$stmt = "SELECT Path 
				FROM " . DB_PRAEFIX . "dashboard
				WHERE Col = ? AND DashboardID = ?";
		$dashboards = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt, array (
				$col,
				$this->dashboardId 
		) );
		foreach ( $dashboards as $dashboard => $value ) {
			$widgetProperties = $this->app ['widgets.service']->getWidgetProperties ( $value ['Path'] );
			$widget = $this->app ['widgets.service']->convertWidgetPropertiesToObject ( $widgetProperties );
			$widget->load ();
			$res [] = $widget;
			return $res;
		}
	}
	
	/**
	 * Loads widgets and stores them in an array
	 * 
	 * @access public
	 */
	public function loadWidgets() {
		$this->cols [] = $this->loadWidgetsByCol ( 1 );
		$this->cols [] = $this->loadWidgetsByCol ( 2 );
		$this->cols [] = $this->loadWidgetsByCol ( 3 );
	}
	
	/**
	 * Display a column
	 * 
	 * @param int $dashboardId ID of the specific dashboard
	 * @access private
	 */
	private function displayColumn($dashboardId) {
		if ($this->cols [$dashboardId]) {
			foreach ( $this->cols [$dashboardId] as $widget ) {
				$widget = $widget->display ();
				return $widget;
			}
		}
	}
	
	/**
	 * Displays a widget
	 * 
	 * @return array Widget
	 * @access public
	 */
	public function display() {
		$widget [] = $this->displayColumn ( 0 );
		$widget [] = $this->displayColumn ( 1 );
		$widget [] = $this->displayColumn ( 2 );
		return $widget;
	}
}
?>