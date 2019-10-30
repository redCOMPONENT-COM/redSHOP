<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
			// Old coupon files
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/coupon/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/coupon_detail.php',
			// Old shipping box files
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/shipping_box_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/shipping_box_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/shipping_box/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/shipping_box_detail.php',
			// Old address field files
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/addressfields_listing.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/addressfields_listing.php',
			JPATH_LIBRARIES . '/redshop/src/Economic/Economic.php'
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
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/coupon_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/shipping_box_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/css',
			JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images'
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

		$coupons = $db->setQuery($query)->loadObjectList();

		if (empty($coupons))
		{
			return;
		}

		$nullDate = $db->getNullDate();

		foreach ($coupons as $coupon)
		{
			/** @var RedshopTableCoupon $table */
			$table = RedshopTable::getAdminInstance('Coupon');

			if (!$table->load($coupon->id))
			{
				continue;
			}

			$needUpdate = false;

			if ($table->start_date == $nullDate && !empty($coupon->start_date_old))
			{
				$table->start_date = JFactory::getDate($coupon->start_date_old)->toSql();
				$needUpdate        = true;
			}

			if ($table->end_date == $nullDate && !empty($coupon->end_date_old))
			{
				$table->end_date = JFactory::getDate($coupon->end_date_old)->toSql();
				$needUpdate      = true;
			}

			if (!$needUpdate)
			{
				continue;
			}

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
