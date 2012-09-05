<?php
/**
 * Hello World table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableBarcode extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $log_id = null;

	/**
	 * @var string
	 */
	var $order_id = null;

	var $user_id = null;
	var $barcode = null;
	var $search_date = null;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableBarcode(& $db) {

		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix.'orderbarcode_log', 'log_id', $db);
	}
}