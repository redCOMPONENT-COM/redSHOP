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

/**
 * Renders a Productfinder Form
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JElementplugins extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'plugins';

	function fetchElement($name, $value, &$node, $control_name)
	{

		$db = &JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT shopper_group_id,shopper_group_name ' .
				' FROM #__redshop_shopper_group WHERE published=1';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Shopper Group').' -', 'shopper_group_id', 'shopper_group_name'));
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]','multiple="multiple" size="5"' , 'shopper_group_id', 'shopper_group_name', $value, $control_name.$name  );
	}
}
