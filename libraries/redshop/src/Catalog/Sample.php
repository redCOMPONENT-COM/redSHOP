<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Catalog;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Catalog sample
 *
 * @since  __DEPLOY_VERSION__
 */
class Sample
{
	/**
	 * Method for get catalog sample list
	 *
	 * @return array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getCatalogSampleList()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_catalog_sample', 'c'))
			->where($db->qn('c.published') . ' = 1');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for get catalog sample color list
	 *
	 * @param   integer $sampleId Sample Id
	 *
	 * @return  array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getCatalogSampleColorList($sampleId = 0)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_catalog_colour', 'c'));

		if ($sampleId)
		{
			$query->where($db->qn('c.sample_id') . ' = ' . (int) $sampleId);
		}

		return $db->setQuery($query)->loadObjectList();
	}
}