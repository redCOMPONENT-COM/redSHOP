<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Order User Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityOrder_User extends RedshopEntity
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  JTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Order_User_Detail', 'Table');
	}

	/**
	 * Method for load plugin data of this payment
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	public function loadExtraFields()
	{
		if (!$this->hasId() || !is_null($this->get('fields', null)))
		{
			return $this;
		}

		$this->set('email', $this->get('user_email'));

		$privateSection = extraField::SECTION_PRIVATE_BILLING_ADDRESS;
		$companySection = extraField::SECTION_COMPANY_BILLING_ADDRESS;

		if ($this->get('address_type', '') == 'ST')
		{
			$privateSection = extraField::SECTION_PRIVATE_SHIPPING_ADDRESS;
			$companySection = extraField::SECTION_COMPANY_SHIPPING_ADDRESS;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('f.name') . ',' . $db->qn('fd.data_txt'))
			->from($db->qn('#__redshop_fields_data', 'fd'))
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . '=' . $db->qn('fd.fieldid'))
			->where(
				'('
				. $db->qn('fd.section') . ' = ' . $privateSection
				. ' OR '
				. $db->qn('fd.section') . ' = ' . $companySection
				. ')'
			)
			->where($db->qn('fd.itemid') . ' = ' . $this->get('users_info_id'));

		// Set the query and load the result.
		$results = $db->setQuery($query)->loadObjectList();

		if (empty($results))
		{
			$this->set('fields', array());

			return $this;
		}

		$fieldsData = array();

		foreach ($results as $result)
		{
			$fieldsData[$result->name] = $result->data_txt;
		}

		$this->set('fields', $fieldsData);

		return $this;
	}
}
