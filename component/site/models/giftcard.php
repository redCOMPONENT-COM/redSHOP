<?php
/** 
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved. 
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.model' );

class giftcardModelgiftcard extends JModel 
{
	var $_id = null;
	var $_data = null;
	var $_product = null; /// product data
	var $_table_prefix = null;
	var $_template = null;
	var $_limit = null;
	
	function __construct() 
	{
		global $mainframe;
		parent::__construct ();

		$this->_table_prefix = '#__redshop_';
		$Id = JRequest::getInt ( 'gid', 0 );
		
		$this->setId ( ( int ) $Id );
	}
	
	function setId($id) 
	{
		$this->_id = $id;
		$this->_data = null;
	}
	
	function _buildQuery() 
	{
		global $mainframe;

		$and = "";
		if($this->_id)
		{
			$and .= "AND giftcard_id='".$this->_id."' ";
		}
		$query = "SELECT * FROM ".$this->_table_prefix."giftcard "
				."WHERE published = 1 "
				.$and;
		return $query;
	}
		
	function getData() 
	{
		if (empty ( $this->_data )) {
			$query = $this->_buildQuery ();
			$this->_data = $this->_getList ( $query );
		}
		return $this->_data;
	}
	
	function getGiftcardTemplate() 
	{
		global $mainframe,$context;
		
		$redTemplate = new Redtemplate( );
		if (!$this->_id) {
			$carttemplate = $redTemplate->getTemplate ( "giftcard_list" );
		} else {
			$carttemplate = $redTemplate->getTemplate ( "giftcard" );
		}
		return $carttemplate;
	}
}
?>