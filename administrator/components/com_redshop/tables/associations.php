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
/** 
 * @copyright Copyright (C) 2008 redCOMPONENT.com. All rights reserved. 
 * @license can be read in this package of software in the file license.txt or 
 * read on http://redcomponent.com/license.txt  
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * Products table
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 */
class TableAssociations extends JTable {
	/** @var int Primary key */
	var $id = null;
	/** @var string Whether or not a product is published */
	var $published = null;
	/** @var string Whether or not a product is checked out */
	var $checked_out = null;
	/** @var string When a product is checked out */
	var $checked_out_time = null;
	/** @var integer The order of the product */
	var $ordering = 0;
	/** @var integer The ID of the Redshop product */
	var $product_id = 0;
	
	/**
	* @param database A database connector object
	*/
	function __construct( &$db ) {
		parent::__construct('#__redproductfinder_associations', 'id', $db );
	}
}
?>