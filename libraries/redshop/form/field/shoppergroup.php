<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Shopper Groups field.
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopFormFieldShoppergroup extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Shoppergroup';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getOptions()
	{
		$exclude = !empty($this->element['exclude']) ? $this->element['exclude'] : null;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_id', 'value'))
			->select($db->qn('shopper_group_name', 'text'))
			->from($db->qn('#__redshop_shopper_group'));

		if ($exclude)
		{
			$id = JFactory::getApplication()->input->getInt('shopper_group_id', 0);

			if ($id)
			{
				$query->where($db->qn('shopper_group_id') . ' <> ' . $id);
			}
		}

		$options = $db->setQuery($query)->loadObjectList();

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
