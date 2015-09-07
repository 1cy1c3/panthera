<?php
/**
 * Menu operations of the frontend and backend
 *
 * @author Rune Krauss
 */
class Menu {
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a menu object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}

	/**
	 * Gets all menu entries regarding the frontend
	 * 
	 * @param int $menuId Menu ID
	 * @return array Menu entries
	 * @access public
	 */
	public function getMenuEntries($menuId) {
		$stmt = 'SELECT EntryID, Title, Link 
						FROM ' . DB_PRAEFIX . 'menu 
						WHERE MenuId = ? 
						ORDER BY EntryID ASC';
		$rs = $this->app ['db']->fetchAll ( $stmt, array (
				$menuId 
		) );
		return $rs;
	}

	/**
	 * Gets all menu entries regarding the backend
	 *
	 * @param int $menuId Menu ID
	 * @return array Menus
	 * @access public
	 */
	public function getMenu() {
		$stmt = "SELECT MenuNameId, Name, (
						SELECT COUNT(*) 
						FROM " . DB_PRAEFIX . "menu 
						WHERE MenuId = " . DB_PRAEFIX . "menu_name.MenuNameId 
					) AS 'Count' 
					FROM " . DB_PRAEFIX . "menu_name";
		$menus = $this->app['dbs']['mysql_read']->fetchAll($stmt);
		return $menus;
	}
	
	/**
	 * Sets a menu editable by a menu id
	 *
	 * @param int $menuId Menu ID
	 * @return array Editabled menu
	 * @access public
	 */
	public function setEditable($menuId) {
		$stmt = "SELECT EntryId, Title, Link
						FROM " . DB_PRAEFIX . "menu
						WHERE MenuId = ? " . "
						ORDER BY EntryId ASC";
		return $this->app['dbs']['mysql_read']->fetchAll($stmt, array($menuId));
	}
	
	/**
	 * Inserts an entry regarding a menu
	 *
	 * @param int $menuId Menu ID
	 * @param int $title Menu title
	 * @param int $href Menu link
	 * @return array Result
	 * @access public
	 */
	public function insertEntry($menuId, $title, $href) {
		$stmt = "SELECT MAX(EntryId) AS MaxEntryId
						FROM " . DB_PRAEFIX . "menu
						WHERE MenuId = ?";
		
		$menu = $this->app['dbs']['mysql_read']->fetchAssoc($stmt, array($menuId));
		$menu ['MaxEntryId'] = $menu ['MaxEntryId'] + 1;
		$stmt = "INSERT INTO " . DB_PRAEFIX . "menu (EntryId, MenuId, Title, Link) 
						VALUES (?, ?, ?, ?)";
		$rs = $this->app['dbs']['mysql_write']->executeUpdate($stmt, array($menu ['MaxEntryId'], $menuId, $title, $href));
		if ($rs) {
			$args ['menuID'] = $menuID; // Event
			$args ['title'] = $title;
			$args ['href'] = $href;
			$this->app['eventhandlers.service']->raiseEvent ( 'onEntryAfterInsert', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Updates entries
	 *
	 * @param int $entryId Entry ID
	 * @param int $menuId Menu ID
	 * @param int $title Menu title
	 * @param int $href Menu link
	 * @return array Result
	 * @access public
	 */
	public function updateEntries($entryId, $menuId, $title, $link) {
		$stmt = "UPDATE " . DB_PRAEFIX . "menu 
						SET Title = ?, Link = ? 
						WHERE EntryId = ? AND MenuId = ?";
		$rs = $this->app['dbs']['mysql_write']->executeUpdate($stmt, array($title, $link, $entryId, $menuId));
		if ($rs) {
			$args ['entryID'] = $entryID; // Event
			$args ['menuID'] = $menuID;
			$args ['title'] = $title;
			$args ['href'] = $href;
			$this->app['eventhandlers.service']->raiseEvent ( 'onEntryAfterUpdate', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Deletes an entry
	 *
	 * @param int $entryId Entry ID
	 * @param int $menuId Menu ID
	 * @return array Result
	 * @access public
	 */
	public function deleteEntry($entryId, $menuId) {
		$stmt = "DELETE FROM " . DB_PRAEFIX . "menu
						WHERE EntryId = ? AND MenuId = ?";
		$this->app['dbs']['mysql_write']->executeUpdate($stmt, array($entryId, $menuId));
		$stmt = "UPDATE " . DB_PRAEFIX . "menu 
						SET EntryId = EntryId - 1 
						WHERE EntryId > ? AND MenuId = ?";
		$rs = $this->app['dbs']['mysql_write']->executeUpdate($stmt, array($entryId, $menuId));
		if ($rs) {
			$args ['entryID'] = $entryID; // Event
			$args ['menuID'] = $menuID;
			$this->app['eventhandlers.service']->raiseEvent ( 'onEntryAfterDelete', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Inserts a menu
	 *
	 * @param string $name Name of the menu
	 * @return array ID of the specific menu
	 * @access public
	 */
	public function insert($name) {
		$stmt = "INSERT INTO " . DB_PRAEFIX . "menu_name (Name) 
					VALUES (?)";
		$this->app['dbs']['mysql_write']->executeUpdate($stmt, array($name));
		$stmt = "SELECT MenuNameId 
					FROM " . DB_PRAEFIX . "menu_name 
					WHERE Name = ?";
		$menuName = $this->app['dbs']['mysql_read']->fetchAssoc($stmt, array($name));
		$args ['name'] = $name; // Event
		$this->app['eventhandlers.service']->raiseEvent ( 'onMenuAfterInsert', '../', $args );
		return $menuName ["MenuNameId"];
	}
	
	/**
	 * Gets the menu name by the id
	 *
	 * @param int $menuNameId ID of the menu name
	 * @return array Menu name
	 * @access public
	 */
	public function getMenuName($menuNameId) {
		$stmt = "SELECT Name
					FROM " . DB_PRAEFIX . "menu_name
					WHERE MenuNameId = ?";
		$menu = $this->app['dbs']['mysql_read']->fetchAssoc($stmt, array($menuNameId));
		return $menu['Name'];
	}
	
	/**
	 * Deletes a menu
	 *
	 * @param int $menuNameId ID of the menu name
	 * @return array Result
	 * @access public
	 */
	public function delete($menuNameId) {
		$stmt = "DELETE FROM " . DB_PRAEFIX . "menu_name
				WHERE MenuNameId = ?";
		$rs = $this->app['dbs']['mysql_write']->executeUpdate($stmt, array($menuNameId));
		if ($rs) {
			$args ['menuNameID'] = $menuNameID; // Event
			$this->app['eventhandlers.service']->raiseEvent ( 'onMenuAfterDelete', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Gets an id by a menu name
	 *
	 * @param int $name Menu name
	 * @return array ID of the menu
	 * @access public
	 */
	public function getIdByName($name) {
		$stmt = "SELECT MenuNameId 
				FROM " . DB_PRAEFIX . "menu_name
				WHERE trim(Name) = ? 
				LIMIT 0, 1";
		$rs = $this->app ['db']->fetchAssoc ( $stmt, array (
				$name 
		) );
		return $rs['MenuNameId'];
	}
	
	/**
	 * Gets the global menu
	 *
	 * @return array Menu entries
	 * @access public
	 */
	public function getGlobalMenu() {
		return $this->getMenuEntries ( $this->app['settings.service']->getSetting ( 'globalMenuId' ));
	}
	
	/**
	 * Gets the local menu
	 *
	 * @return array Menu entries
	 * @access public
	 */
	public function getLocalMenu() {
		if ($this->app['pages.service']->menuId > - 1) {
			return $this->getMenuEntries ( $this->app['pages.service']->menuId );
		}
	}

	/**
	 * Checks whether local menu exists
	 *
	 * @return bool Local menu
	 * @access public
	 */
	public function localMenuExists() {
		if ($this->app['pages.service']->menuId > - 1) {
			return true;
		} else {
			return false;
		}
	}
}