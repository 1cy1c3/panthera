<?php
/**
 * Represents the base class of the widgets
 *
 * @author Rune Krauss
 */
abstract class WidgetBase {
	/**
	 * Color of the widget
	 * 
	 * @var string
	 * @access public
	 */
	public $color = "white";
	
	/**
	 * Headline of the widget
	 *
	 * @var string
	 * @access public
	 */
	public $headline = "";
	
	/**
	 * Content of the widget
	 *
	 * @var string
	 * @access public
	 */
	public $content = "";
	
	/**
	 * Displays the widget
	 * 
	 * @return array Displayed widget
	 * @access public
	 */
	public function display() {
		$display = array($this->color, $this->headline, $this->content);
		return $display;
	}
	
	/**
	 * Loads the widget
	 */
	public abstract function load();
}