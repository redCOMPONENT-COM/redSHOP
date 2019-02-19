<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JFormHelper::loadFieldClass('list');
/**
 * Economic account group select list for redSHOP
 *
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @since       __DEPLOY_VERSION__
 */
class JFormFieldRState2Code extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RState2Code';
	/**
	 * Get the select options
	 *
	 * @return  array  Options to populate the select field
	 */
	public function getOptions()
	{
		$app          = JFactory::getApplication();
		$country3Code = $app->input->get('country_code');
		$db           = JFactory::getDbo();
		$query        = $db->getQuery(true);
		$query->select($db->qn('s.state_2_code', 'value'))
			->select($db->qn('s.state_name', 'text'))
			->from($db->qn('#__redshop_state', 's'))
			->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('s.country_id'))
			->where($db->qn('c.country_3_code') . ' = ' . $db->q($country3Code));

		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage());
		}

		// Get other options inserted in the XML file
		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}