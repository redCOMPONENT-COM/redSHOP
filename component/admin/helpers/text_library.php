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
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
JHTML::_('behavior.tooltip');
class text_library 
{
	var $_data = null;	
	var $_table_prefix = null;
	var $_db = null;
	
	function __construct()
	{		
		global $mainframe, $context;		
	  	$this->_table_prefix = '#__redshop_';
	  	$this->_db = JFactory::getDbo();
	}
 
	function getTextLibraryData()
	{		
		$query = "SELECT * FROM ".$this->_table_prefix."textlibrary "
				."WHERE published=1 ";
		$this->_db->setQuery($query);
		$textdata=$this->_db->loadObjectlist();
		return $textdata;
	}

	function getTextLibraryTagArray()
	{		
		$result = array();
		$textdata = $this->getTextLibraryData();
		for($i=0;$i<count($textdata);$i++)
		{
			$result[] = $textdata[$i]->text_name;
		}
		return $result;
	}
	
	function replace_texts($data)
	{		
		$textdata = $this->getTextLibraryData();
		for($i=0;$i<count($textdata);$i++)
		{
			$textname="{".$textdata[$i]->text_name."}";
			$textreplace=$textdata[$i]->text_field;
			$data=str_replace($textname,$textreplace,$data);
		}
		return $data;
	}
}
?>