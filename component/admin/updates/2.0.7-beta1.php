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
class RedshopUpdate207Beta1 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/voucher_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/voucher/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/voucher_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/voucher_detail.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/voucher_detail',
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function migrateVoucher()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_product_voucher'))
			->order($db->qn('voucher_id'));

		$vouchers = $db->setQuery($query)->loadObjectList();

		if (empty($vouchers))
		{
			$this->dropOldTable();

			return;
		}

		$table = RedshopTable::getAdminInstance('Voucher');
		$table->setOption('skip.checkPrimary', true);

		foreach ($vouchers as $voucher)
		{
			$data = (array) $voucher;

			$data['id'] = $data['voucher_id'];
			$data['code'] = $data['voucher_code'];
			$data['type'] = $data['voucher_type'];
			$data['free_ship'] = $data['free_shipping'];
			$data['start_date'] = !$data['start_date'] ? '0000-00-00 00:00:00' : JFactory::getDate($data['start_date'])->format('Y-m-d H:i:s');
			$data['end_date'] = !$data['end_date'] ? '0000-00-00 00:00:00' : JFactory::getDate($data['end_date'])->format('Y-m-d H:i:s');

			unset($data['voucher_id']);
			unset($data['voucher_code']);
			unset($data['voucher_type']);
			unset($data['free_shipping']);

			if (!$table->save($data))
			{
				JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
			}
		}

		$this->dropOldTable();
	}

	/**
	 * Method for drop old `#__redshop_product_voucher` table.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function dropOldTable()
	{
		$db = JFactory::getDbo();

		$db->setQuery('DROP TABLE IF EXISTS ' . $db->qn('#__redshop_product_voucher'))->execute();
	}
}
