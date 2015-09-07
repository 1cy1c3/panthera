<?php
/**
 * Presents the preferences of this content management system
 *
 * @author Rune Krauss
 */
class Setting {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a setting object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Gets preferences by a property parameter
	 * 
	 * @param string $property Property
	 * @return array Value in relation to the property
	 */
	public function getSetting($property) {
		$stmt = "SELECT Value FROM " . DB_PRAEFIX . "setting WHERE Property = ?";
		$setting = $this->app ['dbs']['mysql_read']->fetchAssoc ( $stmt, array (
				$property 
		) );
		return $setting ["Value"];
	}
	
	/**
	 * Gets all preferences
	 *
	 * @return array Preferences
	 */
	public function getData() {
		$stmt = "SELECT * 
				FROM " . DB_PRAEFIX . "setting 
				WHERE Activated = 1";
		$settings = $this->app ['dbs']['mysql_read']->fetchAll ($stmt);
		foreach ( $settings as $setting => $value )
			$rs [] = $value;
		return $rs;
	}
	
	/**
	 * Updates the preferences
	 *
	 * @param $property Property
	 * @param $value Value
	 * @return array Result
	 */
	public function update($property, $value) {
		$stmt = "UPDATE " . DB_PRAEFIX . "setting
				SET Value = ? 
				WHERE Property = ?";
		$rs = $this->app ['dbs']['mysql_write']->executeUpdate ( $stmt, array (
				$value,
				$property
		) );
		return $rs;
	}
}
?>