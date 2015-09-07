<?php
/**
 * Page operations regarding WYSIWYG in the backend
 * Creates, reads, updates and deletes content in terms of pages
 *
 * @author Rune Krauss
 */
class Page {
	/**
	 * ID of the page
	 * 
	 * @var int
	 * @access public
	 */
	public $pageId = - 1;
	
	/**
	 * Alias of the page
	 *
	 * @var string
	 * @access public
	 */
	public $alias = "";
	
	/**
	 * Title of the page
	 *
	 * @var string
	 * @access public
	 */
	public $title = "";
	
	/**
	 * ID of the owner
	 *
	 * @var int
	 * @access public
	 */
	public $ownerId = - 1;
	
	/**
	 * Activated Owner
	 *
	 * @var bool
	 * @access public
	 */
	public $owner = false;
	
	/**
	 * ID of the menu
	 *
	 * @var int
	 * @access public
	 */
	public $menuId = - 1;
	
	/**
	 * Application object
	 *
	 * @var Application
	 * @access private
	 */
	private $app = '';
	
	/**
	 * Initializes a page object
	 *
	 * @param Application $app Application object
	 */
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * Sets properties like title and alias based on the alias
	 * 
	 * @param string $alias Alias
	 * @access public
	 */
	public function setProperties($alias) {
		$stmt = "SELECT PageID, Title, Owner, MenuID
				FROM " . DB_PRAEFIX . "page
						WHERE Alias = ?";
		$page = $this->app ['dbs'] ['mysql_read']->fetchAssoc($stmt, array($alias));
		if ($page) {
			$this->pageId = $page ["PageID"];
			$this->title = $page ["Title"];
			$this->ownerId = $page ["Owner"];
			$this->menuId = $page ["MenuID"];
			$this->alias = $alias;
		}
	}
	
	/**
	 * Gets the content of a page regarding the frontend
	 *
	 * @return string Content
	 * @access public
	 */
	public function getContent() {
		$this->app['eventhandlers.service']->raiseEvent ( 'onContentBeforeInclude', '.', $this );
		return 'tmpl/frontend/' . (filterFilename ( $this->alias ));
		$this->app['eventhandlers.service']->raiseEvent ( 'onContentAfterInclude', '.', $this );
	}
	
	/**
	 * Gets the header of a file regarding the frontend
	 *
	 * @return string Metatags
	 * @access public
	 */
	public function getHeader() {
		$stmt = "SELECT MetaName, Content
				FROM " . DB_PRAEFIX . "global_meta
						UNION SELECT MetaName, Content
						FROM " . DB_PRAEFIX . "local_meta
								WHERE PageId = ?";
		$metas = $this->app ['dbs'] ['mysql_read']->fetchAll($stmt, array($this->pageId));
		return $metas;
		$this->app['eventhandlers.service']->raiseEvent ( 'onHeaderAfterInclude', '.', null );
	}
	
	/**
	 * Sets the owner regarding the frontend
	 *
	 * @return string Owner
	 * @access public
	 */
	public function setOwner() {
		if (! $this->owner) {
			$stmt = "SELECT Alias
					FROM " . DB_PRAEFIX . "page
							WHERE PageId = ?";
			$row = $this->app ['dbs'] ['mysql_read']->fetchAssoc($stmt, array($this->ownerId));
			if ($row) {
				$this->owner = new Page ($this->app);
				$this->owner->setProperties ( $row ["Alias"] );
			}
		}
		return $this->owner;
	}
	
	/**
	 * Gets the breadcrumb regarding the frontend
	 *
	 * @return array Breadcrumb
	 * @access public
	 */
	public function getBreadcrumb() {
		if (! $this->owner)
			$this->setOwner ();
		if ($this->owner) {
			$breadcrumb = $this->owner->getBreadcrumb ();
		}
		$breadcrumb [] = array (
				$this->alias,
				$this->title 
		);
		return $breadcrumb;
	}
	
	/**
	 * Gets the pages regarding the backend
	 *
	 * @return array Pages
	 * @access public
	 */
	public function getPages() {
		$stmt = "SELECT *
				FROM " . DB_PRAEFIX . "page
						ORDER BY title LIMIT 0, 20";
		
		$pages = $this->app ['dbs'] ['mysql_read']->fetchAll ( $stmt );
		return $pages;
	}
	
	/**
	 * Reads the content by the directory prefix
	 *
	 * @param $dirPraefix Directory prefix
	 * @return string Content
	 * @access public
	 */
	public function readContent($dirPraefix) {
		$filename = $dirPraefix . 'res/views/tmpl/frontend/' . $this->alias . '.html';
		if (! file_exists ( $filename )) {
			$content = '';
		} else {
			$handle = fopen ( $filename, "rb" );
			$content = fread ( $handle, filesize ( $filename ) );
			fclose ( $handle );
		}
		return $content;
	}
	
	/**
	 * Updates the page
	 *
	 * @param $alias Alias
	 * @param $title Title
	 * @param $menuId ID of the menu
	 * @return array Result
	 * @access public
	 */
	function update($alias, $title, $menuId) {
		$this->alias = $alias;
		$this->title = $title;
		$this->menuId = $menuId;
		$stmt = "UPDATE " . DB_PRAEFIX . "page 
				SET Alias = ?, Title = ?, MenuId = ? 
				WHERE PageId = ?";
		$rs = $this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array($alias, $title, $menuId, $this->pageId) );
		if ($rs) {
			$args ['alias'] = $this->alias; // Event
			$args ['title'] = $this->title;
			$args ['menuID'] = $this->menuID;
			$args ['pageID'] = $this->pageID;
			$this->app['eventhandlers.service']->raiseEvent ( 'onPageAfterUpdate', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Deletes the page
	 *
	 * @param $dirPraefix Directory prefix
	 * @access public
	 */
	public function deleteContent($dirPraefix) {
		$filename = $dirPraefix . 'res/views/tmpl/frontend/' . $this->alias . '.html';
		if (file_exists ( $filename )) {
			unlink ( $filename );
			$args ['alias'] = $this->alias; // Event
			$args ['filename'] = $filename;
			$this->app['eventhandlers.service']->raiseEvent ( 'onContentAfterDelete', '../', $args );
		}
	}
	
	/**
	 * Writes the content
	 *
	 * @param $dirPraefix Directory prefix
	 * @param $content Content of the page
	 * @return string Content
	 * @access public
	 */
	public function writeContent($dirPraefix, $content) {
		
		$content = html_entity_decode ( $content, ENT_QUOTES, 'UTF-8' );
		$filename = $dirPraefix . 'res/views/tmpl/frontend/' . $this->alias . '.html';
		$handle = fopen ( $filename, "a" );
		$content = fwrite ( $handle, $content );
		fclose ( $handle );
		if ($content) {
			$args ['alias'] = $this->alias; // Event
			$args ['filename'] = $filename;
			$args ['content'] = $content;
			$this->app['eventhandlers.service']->raiseEvent ( 'onContentAfterWrite', '../', $args );
		}
		return $content;
	}
	
	/**
	 * Insert the page by specify an alias
	 *
	 * @param $alias Alias
	 * @return array Result
	 * @access public
	 */
	public function insert($alias) {
		$stmt = "INSERT INTO " . DB_PRAEFIX . "page (Alias)
				VALUES (?)";
		$rs = $this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$alias 
		) );
		if ($rs) {
			$args ['alias'] = $this->alias; // Event
			$args ['pageID'] = $this->pageID;
			$this->app['eventhandlers.service']->raiseEvent ( 'onPageAfterInsert', '../', $args );
		}
		return $rs;
	}
	
	/**
	 * Deletes the page by specify an alias
	 *
	 * @param $alias Alias
	 * @return array Result
	 * @access public
	 */
	public function delete($alias) {
		$this->alias = $alias;
		$stmt = "DELETE FROM " . DB_PRAEFIX . "page 
				WHERE Alias = ?";
		$rs = $this->app ['dbs'] ['mysql_write']->executeUpdate ( $stmt, array (
				$alias 
		) );
		if ($rs) {
			$args ['alias'] = $this->alias; // Event
			$this->app['eventhandlers.service']->raiseEvent ( 'onPageAfterDelete', '../', $args );
		}
		return $rs;
	}
}