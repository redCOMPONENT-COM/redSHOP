<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.form.helper');
JLoader::import('redshop.library');
JFormHelper::loadFieldClass('list');

/**
 * Form fields for redSHOP Promote Free Shipping Module
 *
 * @since  1.7.0
 */
class JFormFieldRedshopShippings extends JFormFieldList
{
	/**
	 * @access private
	 */
	var $_name = 'redshopshippings';

	/**
	 * [getInput description]
	 * 
	 * @return [html]
	 */
	protected function getInput()
	{
		$name      = $this->name;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(['shipping_rate_id', 'shipping_rate_name']))
			->from($db->qn('#__redshop_shipping_rate'));
		$items = $db->setQuery($query)->loadObjectList();

		$options = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$option = JHTML::_('select.option', $item->shipping_rate_id, $item->shipping_rate_name);
				$options[] = $option;
			}
		}

		$options = array_merge(parent::getOptions(), $options);
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['multiple'] ? ' multiple' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}
}
