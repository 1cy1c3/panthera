<?php
/**
 * CSS to structure elements
 *
 * @author Rune Krauss
 */
class Css {
	/**
	 * Application object
	 * 
	 * @var Application
	 */
	private $app = '';
	
	/**
	 * Initializes an CSS object
	 * 
	 * @param Application $app Application object
	 */
	function __construct(Application $app) {
		$this->app = $app;
	}
	
	/**
	 * Prints the stylesheet
	 * 
	 * @param string $name Name of the stylesheet
	 * @access public
	 */
	public static function printStylesheet($name) {
		$path = self::getPath ( $name );
		if ($path) {
			echo $path . ".css";
		}
	}
	
	/**
	 * Gets path of the stylesheet
	 * 
	 * @param string $name Name of the stylesheet
	 * @access private
	 */
	private static function getPath($name) {
		global $pdo, $dbPraefix;
		$stmt = "SELECT Path 
					FROM " . $dbPraefix . "css 
					WHERE Name = :Name";
		try {
			$rs = $pdo->prepare ( $stmt );
			$rs->bindParam ( ":Name", $name, PDO::PARAM_STR );
			$rs->execute ();
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
		}
		if ($row = $rs->fetch ( PDO::FETCH_NUM )) {
			$css = $row [0];
		}
		return $css;
	}
}
?>