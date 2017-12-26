<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.1.0
 */
class RedshopUpdate210 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/coupon/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/addressfields_listing.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/addressfields_listing.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/addressfields_listing',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/coupon_detail'
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function migrateCoupons()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_coupons'))
			->order($db->qn('id'));

		$coupons = $db->setQuery($query)->loadColumn();

		if (empty($coupons))
		{
			return;
		}

		foreach ($coupons as $coupon)
		{
			$table = RedshopTable::getAdminInstance('Coupon');

			if (!$table->load($coupon->id))
			{
				continue;
			}

			$table->start_date = empty($coupon->start_date_old) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon->start_date_old)->format('Y-m-d H:i:s');

			$table->end_date = empty($coupon->end_date_old) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon->end_date_old)->format('Y-m-d H:i:s');

			if (!$table->store())
			{
				JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
			}
		}

		$query = 'CALL redSHOP_Column_Remove(' . $db->quote('#__redshop_coupons') . ',' . $db->quote('start_date_old') . ');';
		$db->setQuery($query)->execute();
		$query = 'CALL redSHOP_Column_Remove(' . $db->quote('#__redshop_coupons') . ',' . $db->quote('end_date_old') . ');';
		$db->setQuery($query)->execute();
	}
}
