<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Manufacturer field.
 *
 * @since  1.0
 */
class RedshopFormFieldManufacturer extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Manufacturer';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('manufacturer_id', 'value'))
			->select($db->qn('manufacturer_name', 'text'))
			->from($db->qn('#__redshop_manufacturer'));
		$options = $db->setQuery($query)->loadObjectList();

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
