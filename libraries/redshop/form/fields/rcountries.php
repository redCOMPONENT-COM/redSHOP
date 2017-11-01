<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  2.0.0.4
 */
class JFormFieldRcountries extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  2.0.0.4
	 */
	protected $type = 'Rcountries';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 */
	public function getOptions()
	{
		$options = array();

		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('id', 'value'))
			->select($db->qn('country_name', 'text'))
			->from($db->qn('#__redshop_country'))
			->order($db->qn('country_name'));

		$options = $db->setQuery($query)->loadObjectList();

		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
