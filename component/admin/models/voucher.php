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
 * Model Voucher
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.7
 */
class RedshopModelVoucher extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.1.0
	 */
	public function save($data)
	{
		if ($data['start_date'] > $data['end_date'])
		{
			$this->setError(JText::_('COM_REDSHOP_END_DATE_MUST_MORE_THAN_START_DATE'));

			return false;
		}

		if (!empty($data['start_date']))
		{
			$data['start_date'] = DateTime::createFromFormat(Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d'), $data['start_date']);
			$data['start_date'] = JFactory::getDate($data['start_date']->format('Y-m-d') . ' 00:00:00');
			$data['start_date'] = $data['start_date']->toSql();
		}

		if (!empty($data['end_date']))
		{
			$data['end_date'] = DateTime::createFromFormat(Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d'), $data['end_date']);
			$data['end_date'] = JFactory::getDate($data['end_date']->format('Y-m-d') . ' 23:59:59');
			$data['end_date'] = $data['end_date']->toSql();
		}

		return parent::save($data);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 *
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.voucher.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.voucher', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  JObject|boolean  Object on success, false on failure.
	 *
	 * @since   2.1.0
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem();

		if (false === $item)
		{
			return false;
		}

		$item->voucher_products = RedshopEntityVoucher::getInstance($item->id)->getProducts()->ids();
		$item->start_date       = $item->start_date != JFactory::getDbo()->getNullDate() ? JFactory::getDate($item->start_date)->toUnix() : null;
		$item->end_date         = $item->end_date != JFactory::getDbo()->getNullDate() ? JFactory::getDate($item->end_date)->toUnix() : null;

		return $item;
	}
}
