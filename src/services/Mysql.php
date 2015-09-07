<?php
/**
 * Database operations regarding the DBMS MySQL
 *
 * @author Rune Krauss
 */
class Mysql {
	/**
	 * Counts data
	 * @var int
	 * @access public
	 */
	public $countData = 0;
	
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a mysql object
	 *
	 * @param Application $app Application object
	 */
	public function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Gets tables regarding MySQL
	 * 
	 * @param int $start Start ID
	 * @param int $length Length of the data
	 * @return array Tables
	 * @access public
	 */
	public function getTables($start, $length) {
		$rsTables = '';
		$stmt = "SHOW TABLES LIKE '" . DB_PRAEFIX . "%'";
		$tables = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		foreach ( $tables as $table ) {
			foreach ( $table as $tbl ) {
				$tbls [] = $tbl;
			}
		}
		$x = 0;
		while ( $x < count ( $tbls ) ) {
			if ($start > 0) { // Niemals bei Aufruf ohne GET["dbpage"]
				$start --;
			} elseif (count ( $rsTables ) < $length || ! $length) {
				$rsTables [] = $tbls [$x];
			}
			$this->countData ++;
			$x ++;
		}
		return $rsTables;
	}
	
	/**
	 * Counts tuples regarding MySQL
	 *
	 * @param string $table Name of the table
	 * @return array Number of tuples
	 * @access public
	 */
	public function countTuples($table) {
		$stmt = "SELECT COUNT(*) AS 'Count'
				FROM " . $table;
		$tuple = $this->app ['dbs'] ['mysql_read']->fetchAssoc ( $stmt );
		return $tuple ['Count'];
	}
	
	/**
	 * Gets columns by a table
	 *
	 * @param string $table Name of the table
	 * @return array Columns
	 * @access public
	 */
	public function getColumns($table) {
		$stmt = "SHOW COLUMNS FROM " . $table;
		$columns = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		return $columns;
	}
	
	/**
	 * Gets data of a table
	 *
	 * @param string $table Name of the table
	 * @param int $x Position
	 * @param int $y Results
	 * @return array Data
	 * @access public
	 */
	public function getData($table, $x, $y) {
		$stmt = "SELECT * FROM " . $table . " LIMIT " . $x . ", " . $y;
		return $this->app ['dbs'] ['mysql_read']->fetchAssoc ( $stmt );
	}
	
	/**
	 * Gets maximal length by a type
	 *
	 * @param string $type Type regarding MySQL
	 * @return int Length regarding the type
	 * @access public
	 */
	public function getMaxLength($type) {
		if (strpos ( $type, "(" ) > - 1 && strpos ( $type, "(" ) > - 1) {
			return substr ( $type, strpos ( $type, "(" ) + 1, strpos ( $type, ")" ) - strpos ( $type, "(" ) - 1 );
		} else {
			return false;
		}
	}
	
	/**
	 * Gets table content by a table
	 *
	 * @param string $table Name of the table
	 * @return array Data of the table
	 * @access public
	 */
	public function getTableContent($table) {
		$stmt = "SELECT * FROM " . $table;
		$data = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		return $data;
	}
	
	/**
	 * Updates data
	 *
	 * @param string $oldValues Old values
	 * @param string $newValues New values
	 * @param string $table Table
	 * @access public
	 */
	public function update($oldValues, $newValues, $table) {
		$stmt = "UPDATE " . $table . " 
				SET ";
		foreach ( $newValues as $key => $value ) {
			$stmt .= $key . " = '" . $value . "', ";
		}
		$stmt = substr ( $stmt, 0, strlen ( $stmt ) - 2 );
		$stmt .= " WHERE ";
		foreach ( $oldValues as $key => $value ) {
			$stmt .= $key . " = '" . $value . "' AND ";
		}
		$stmt = substr ( $stmt, 0, strlen ( $stmt ) - 5 );
		$this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt );
	}
}