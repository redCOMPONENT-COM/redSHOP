<?php
/**
 * @version		$Id: bannerclient.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Banners
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access');

/**
 * Renders a category element
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JFormFieldBannerclient extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Bannerclient';

	function getInput()
	{
		$db = &JFactory::getDBO();
		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT id, name' .
				' FROM #__banner_clients' .
				' ORDER BY name';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', JText::_('---------- Select All ----------'), 'id', 'name'));
		return JHTML::_('select.genericlist',  $options, ''.$this->name.'[]', 'class="inputbox" multiple="multiple" size="5" style="width:95%;"', 'id', 'name', $this->value, $this->fieldname);
	}
}