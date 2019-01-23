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
 * Model Mass Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.3
 */
class RedshopModelMass_Discount extends RedshopModelForm
{
	use Redshop\Model\Traits\HasDateTimeRange;

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.1.1
	 */
	public function save($data)
	{
		$this->handleDateTimeRange($data['start_date'], $data['end_date']);

		if ($data['start_date'] > $data['end_date'])
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_START_DATE_MUST_BE_SOONER_OR_EQUAL_TO_END_DATE'));

			return false;
		}

		return parent::save($data);
	}
}
