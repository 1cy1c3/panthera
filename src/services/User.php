<?php
/**
 * Represents the user operations
 *
 * @author Rune Krauss
 */
class User {
	/**
	 * Name of the user
	 * 
	 * @var string
	 * @access public
	 */
	public $name;
	
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes an user
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Checks the password of the user
	 * 
	 * @param string $pw Password
	 * @return bool Number of users
	 * @access private
	 */
	private function checkPassword($pw) {
		$name = trim ( $this->name );
		$pw = trim ( $pw );
		$stmt = "SELECT COUNT(*) as Count
				FROM " . DB_PRAEFIX . "user 
				WHERE name = ? AND password = sha1(?)";
		$user = $this->app['db']->fetchAssoc( $stmt, array(
			$this->name, $pw
		) );
		if ($user) {
			return $user ['Count'] == 1;
		}
	}
	
	/**
	 * Checks the login based on the name and password of an user
	 *
	 * @param string $name Name of the user
	 * @param string $pw Password of the user
	 * @return bool Checked password
	 * @access public
	 */
	public function checkLogin($name, $pw) {
		if ($this->checkPassword ( $pw )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Logs off the user
	 * Destroys the specific session
	 *
	 * @access public
	 */
	public function logout() {
		session_destroy ();
	}
	
	/**
	 * Returns all users
	 *
	 * @return array Users
	 * @access public
	 */
	public function getAllUsers() {
		$stmt = "SELECT UserId, Name 
				FROM " . DB_PRAEFIX . "user
				ORDER BY Name";
		$users = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		foreach ($users as $user) {
			$res[] = $user;
		}
		return $res;
	}
}
?>