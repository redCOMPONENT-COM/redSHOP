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

class JFormFieldextrafieldpayment extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public	$type = 'extrafieldpayment';
	

	protected function getInput()
	{

		$db = &JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		
		$query = "SELECT field_name,field_title FROM #__redshop_fields "
				."WHERE published=1 "
				."AND field_show_in_front=1 "
				."AND field_section='18'  ORDER BY ordering";
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('COM_REDSHOP_SELECT_EXTRA_FIELD').' -', 'field_name', 'field_title'));
		return JHTML::_('select.genericlist',  $options, ''.$this->name.'[]',  'multiple="multiple" size="5"','field_name', 'field_title', $this->value, $this->id);

	}
}
