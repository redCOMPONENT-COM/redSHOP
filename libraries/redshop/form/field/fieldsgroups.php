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
 * @since  1.6
 */
class RedshopFormFieldFieldsgroups extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Fieldsgroups';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 */
	public function getOptions()
	{
		$groups = \Redshop\Helper\FieldsGroups::getGroups();

		$options[] = JHtml::_('select.option', 0, 'COM_REDSHOP_FIELDS_GROUP_NOGROUP');

		if (count($groups) > 0)
		{
			foreach ($groups as $group)
			{
				$options[] = array('value'=>$group->id,'text'=>$group->name);
			}
		}

		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
