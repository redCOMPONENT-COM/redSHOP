<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelNewslettersubscr extends RedshopModel
{
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'subscription_id', $direction = '')
	{
		$filter = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$this->setState('filter', $filter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$filter = $this->getState('filter');
		$where  = '';

		if ($filter)
		{
			$where = " AND (ns.name like '%" . $filter . "%' OR ns.email like '%" . $filter . "%') ";
		}

		$orderby = $this->_buildContentOrderBy();
		$query   = 'SELECT  distinct(ns.subscription_id),ns.*,n.name as n_name FROM #__redshop_newsletter_subscription as ns '
			. ',#__redshop_newsletter as n '
			. 'WHERE ns.newsletter_id=n.newsletter_id '
			. $where
			. $orderby;

		return $query;
	}

	public function getnewslettername($nid)
	{
		$query = 'SELECT name FROM #__redshop_newsletter WHERE newsletter_id=' . $nid;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function getnewsletters()
	{
		$query = 'SELECT newsletter_id as value,name as text FROM #__redshop_newsletter WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	/**
	 * Method import data.
	 *
	 * @param   integer  $nid    newsletter id
	 * @param   array    $data   data
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function importdata($nid, $data)
	{
		if (!isset($data['email_id']) || $data['email_id'] === null)
		{
			return false;
		}

		/** @var Tablenewslettersubscr_detail $table */
		$table = RedshopTable::getInstance('newslettersubscr_detail', 'Table');

		$key = $table->getKeyName();

		if (array_key_exists($key, $data) && $data[$key])
		{
			if (!$table->load($data[$key]))
			{
				return false;
			}
		}

		$table->subscription_id = $data['subscription_id'];
		$table->newsletter_id   = $nid;
		$table->email           = $data['email_id'];
		$table->name            = $data['subscriber_full_name'];

		try
		{
			if (!$table->check() || !$table->store())
			{
				return false;
			}
		}
		catch (\Exception $e)
		{
			return false;
		}

		return true;
	}
}
