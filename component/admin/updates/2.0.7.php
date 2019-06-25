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
 * @since       2.0.7
 */
class RedshopUpdate207 extends RedshopInstallUpdate
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
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/voucher_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/voucher/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/voucher_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/voucher_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/mail_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/mail/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/mail_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/mail_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/template_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/views/template/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/models/template_detail.php',
			JPATH_ADMINISTRATOR . '/component/com_redshop/controllers/template_detail.php',
			JPATH_LIBRARIES . '/redshop/helper/route.php'
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
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/voucher_detail',
			JPATH_SITE . '/components/com_redshop/layouts/tags',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/mail_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/template_detail'
		);
	}

	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	public function migrateVoucher()
	{
		$db = JFactory::getDbo();

		// Check table exist.
		$result = $db->setQuery("SHOW TABLES LIKE " . $db->quote('#__redshop_product_voucher'))->loadResult();

		if (empty($result))
		{
			return;
		}

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
		$table->setOption('skip.updateProducts', true);

		foreach ($vouchers as $voucher)
		{
			$data = (array) $voucher;

			$data['id']         = $data['voucher_id'];
			$data['code']       = $data['voucher_code'];
			$data['type']       = $data['voucher_type'];
			$data['free_ship']  = $data['free_shipping'];
			$data['start_date'] = !$data['start_date'] ? '0000-00-00 00:00:00' : JFactory::getDate($data['start_date'])->format('Y-m-d H:i:s');
			$data['end_date']   = !$data['end_date'] ? '0000-00-00 00:00:00' : JFactory::getDate($data['end_date'])->format('Y-m-d H:i:s');

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
	 * @since   2.0.7
	 */
	protected function dropOldTable()
	{
		$db = JFactory::getDbo();

		$db->setQuery('DROP TABLE IF EXISTS ' . $db->qn('#__redshop_product_voucher'))->execute();
	}
}
