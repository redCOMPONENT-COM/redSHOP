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

defined('_JEXEC') or die( 'Restricted access' );
require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php' );
//require_once( JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'helper.php' );

/**
 * Renders a searchtype Form
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JFormFieldorderbyproduct extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $type = 'orderbyproduct';

	protected function getInput()
	{
		$order_data = array();
		$order_data[0]->value="p.product_name ASC";
		$order_data[0]->text=JText::_('COM_REDSHOP_PRODUCT_NAME' );
		$order_data[1]->value="p.product_price ASC";
		$order_data[1]->text=JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC' );
		$order_data[2]->value="p.product_price DESC";
		$order_data[2]->text=JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC' );
		$order_data[3]->value="p.product_number ASC";
		$order_data[3]->text=JText::_('COM_REDSHOP_PRODUCT_NUMBER' );
		$order_data[4]->value="p.product_id DESC";
		$order_data[4]->text=JText::_('COM_REDSHOP_NEWEST' );
		$order_data[5]->value="pc.ordering ASC";
		$order_data[5]->text=JText::_('COM_REDSHOP_ORDERING' );
		$order_data[6]->value="m.manufacturer_name ASC";
		$order_data[6]->text=JText::_('COM_REDSHOP_MANUFACTURER_NAME' );
		
		$name 		= $this->name;
		$value		= $this->value;
		if(!$value)
		{
			$value = DEFAULT_PRODUCT_ORDERING_METHOD;
		}

		$order_select = JHTML::_ ( 'select.genericlist', $order_data, $name, 'class="inputbox"', 'value', 'text', $value, $name  );

		return $order_select;
	}
}
