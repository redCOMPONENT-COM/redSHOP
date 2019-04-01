<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Supplier
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelSupplier extends RedshopModelForm
{
	/**
	 * The unique columns.
	 *
	 * @var  array
	 */
	protected $copyUniqueColumns = array('name');

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.supplier.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.supplier', $data);

		return $data;
	}

	/**
	 * Get supplier by email
	 *
	 * @param   string  $email  Supplier email
	 * @return  mixed  The return value or null if the query failed.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getSupplierByEmail($email)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_supplier'))
			->where($db->qn('email') .  ' = ' . $db->q($email));

		return $db->setQuery($query)->loadResult();
	}
}
