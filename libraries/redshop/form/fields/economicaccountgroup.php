<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('joomla.form.formfield');

JFormHelper::loadFieldClass('list');

/**
 * Economic account group select list for redSHOP
 *
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @since       1.0
 */
class JFormFieldEconomicAccountGroup extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var   string
	 */
	protected $type = 'EconomicAccountGroup';

	/**
	 * Get the select options
	 *
	 * @return  array  Options to populate the select field
	 */
	public function getOptions()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('accountgroup_id', 'value'))
			->select($db->quoteName('accountgroup_name', 'text'))
			->from($db->quoteName('#__redshop_economic_accountgroup'))
			->where($db->quoteName('published') . ' = 1');
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Get other options inserted in the XML file
		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
