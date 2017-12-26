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
class RedshopUpdate208Alpha extends RedshopInstallUpdate
{
	/**
	 * Method for migrate voucher data to new table
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateTwigTemplate()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->update('#__redshop_template')
			->set($db->qn('twig_support') . ' = 1')
			->where($db->qn('section') . ' = ' . $db->q('giftcard_list'));
		$db->setQuery($query)->execute();
	}
}
