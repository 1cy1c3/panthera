<?php
/**
 * Event listener for receiving events
 *
 * @author Rune Krauss
 */
class Eventhandler {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes an eventhandler object
	 *
	 * @param Application $app Application object
	 * @access public
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Gets the handler by an event
	 * 
	 * @param string $event Event
	 * @access public
	 */
	public function getHandler($event) {
		$stmt = "SELECT File
				FROM " . DB_PRAEFIX . "event
				WHERE Event = ?";
		$row = $this->app['dbs']['mysql_read']->fetchAssoc($stmt, array($event));
		$file [] = $row ['File'];
		return $file;
	}
	
	/**
	 * Inserts a specific handler
	 * 
	 * @param string $event Event
	 * @param string $file File
	 * @access public
	 */
	public function insertHandler($event, $file) {
		global $dbPraefix, $pdo;
		$stmt = "INSERT INTO " . DB_PRAEFIX . "event (Event, File)
				VALUES (?, ?)";
		$rs = $this->app['dbs']['mysql_write']->executeUpdate($stmt, array($event, $file));
		if ($rs) {
			return $rs;
		}
	}
	
	/**
	 * Raises an event
	 * @param string $event Event
	 * @param string $path Path
	 * @param array $args Arguments
	 * @access public
	 */
	public function raiseEvent($event, $path, $args) {
		//print_r ( $args );
		$files = $this->getHandler($event);
		if ($files) {
			foreach ($files as $file) {
				//@include_once ($path . $file);
			}
		}
	}
}
?>