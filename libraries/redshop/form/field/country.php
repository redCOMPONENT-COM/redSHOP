<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Countries field.
 *
 * @since  1.0
 */
class RedshopFormFieldCountry extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Country';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getOptions()
	{
		$key = isset($this->element['idfield']) ? (string) $this->element['idfield'] : 'id';

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn($key, 'value'))
			->select($db->qn('country_name', 'text'))
			->from($db->qn('#__redshop_country'));
		$options = $db->setQuery($query)->loadObjectList();

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
