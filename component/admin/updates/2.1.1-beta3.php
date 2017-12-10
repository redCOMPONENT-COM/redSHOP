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
 * @since       __DEPLOY_VERSION__
 */
class RedshopUpdate211Beta3 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/coupon/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/coupon_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/coupon_detail.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/coupon_detail'
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  Exception
	 */
	public function migrateCoupons()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_coupons'))
			->order($db->qn('id'));

		$coupons = $db->setQuery($query)->loadColumn();

		if (empty($coupons))
		{
			return;
		}

		$table = RedshopTable::getAdminInstance('Coupon');

		foreach ($coupons as $couponId)
		{
			if (!$table->load($couponId))
			{
				continue;
			}

			$table->start_date = !empty($coupon['start_date_old']) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon['start_date_old'])->format('Y-m-d H:i:s');

			$table->end_date = !empty($coupon['end_date_old']) ?
				'0000-00-00 00:00:00' : JFactory::getDate($coupon['end_date_old'])->format('Y-m-d H:i:s');

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
