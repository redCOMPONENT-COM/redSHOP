<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Field Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Field
 * @since       2.0.6
 */
class RedshopModelField extends RedshopModelForm
{
	/**
	 * Method for get all exist field names except specific field ID.
	 *
	 * @param   integer  $fieldId  Field ID.
	 *
	 * @return  array              List of exist field name.
	 *
	 * @since   2.0.6
	 */
	public function getExistFieldNames($fieldId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('f.name'))
			->from($db->qn('#__redshop_fields', 'f'));

		if ($fieldId)
		{
			$query->where($db->qn('id') . ' <> ' . $fieldId);
		}

		return $db->setQuery($query)->loadColumn();
	}
}