<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The zipcode table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableZipcode extends RedshopTable
{
	/**
	 * The table name without prefix.
	 *
	 * @var string
	 */
	protected $_tableName = 'redshop_zipcode';

	/**
	 * The table key column
	 *
	 * @var string
	 */
	protected $_tableKey  = 'id';

	public $id = null;

	public $state_code = null;

	public $city_name = null;

	public $zipcode = null;

	public $country_code = null;

	/**
	 * Check zipcode
	 *
	 * @return  boolean
	 */
	public function docheck()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('id')
			->from($db->qn("#__redshop_zipcode"))
			->where($db->qn('zipcode') . ' = ' . $db->q((int) $this->zipcode))
			->where($db->qn('id') . ' != ' . $db->q((int) $this->id))
			->where($db->qn('country_code') . ' = ' . $db->q((string) $this->country_code));

		$xid = intval($db->setQuery($query)->loadResult());

		if ($xid)
		{
			return false;
		}

		return parent::doCheck();
	}
}
