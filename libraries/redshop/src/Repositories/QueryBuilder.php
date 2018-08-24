<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  QueryBuilder
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Repositories;

/**
 * @package     Redshop\Repositories
 *
 * @since       2.1.0
 */
class QueryBuilder
{
	/**
	 * @var   string
	 * @since 2.1.0
	 */
	protected $table;

	/**
	 * @var   string
	 * @since 2.1.0
	 */
	protected $primaryKey;

	/**
	 * @var   \JDatabaseDriver
	 * @since 2.1.0
	 */
	protected $db;

	/**
	 * @var   \JDatabaseQuery
	 * @since 2.1.0
	 */
	protected $query;

	/**
	 * QueryBuilder constructor.
	 */
	public function __construct()
	{
		$this->db    = \JFactory::getDbo();
		$this->query = $this->db->getQuery(true);
	}

	/**
	 * @return  void
	 * @since   2.1.0
	 */
	public function reset()
	{
		$this->query->clear();
	}

	/**
	 * @param   array $conditions Conditions
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function find($conditions)
	{
		if (!empty($conditions))
		{
			return $this;
		}

		foreach ($conditions as $condition)
		{
			$this->query->where(
				$this->db->quoteName($condition[0]) . $condition[1] . $this->db->quoteName($condition[2])
			);
		}

		return $this;
	}

	/**
	 * @param   integer $offset Offset
	 * @param   integer $limit  Limit
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public function getAll($offset = null, $limit = null)
	{
		$this->query
			->select($this->db->quoteName($this->primaryKey))
			->from($this->db->quoteName($this->table));

		return $this->db->setQuery($this->query, $offset, $limit)->loadColumn();
	}
}
