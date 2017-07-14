<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelRating extends RedshopModel
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
		$id .= ':' . $this->getState('comment_filter');

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
	protected function populateState($ordering = 'rating_id', $direction = 'desc')
	{
		$comment_filter = $this->getUserStateFromRequest($this->context . '.comment_filter', 'comment_filter', '');
		$this->setState('comment_filter', $comment_filter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$comment_filter = $this->getState('comment_filter');

		$where = '';

		if ($comment_filter)
		{
			$where = " WHERE u.username LIKE '%" . $comment_filter . "%' ";
			$where .= " OR r.comment LIKE '%" . $comment_filter . "%' ";
			$where .= " OR p.product_name LIKE '%" . $comment_filter . "%' ";
		}

		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT p.product_name,u.username,r.* '
			. ' FROM #__redshop_product_rating r LEFT JOIN #__redshop_product p ON p.product_id = r.product_id  LEFT JOIN #__users u ON u.id = r.userid ' . $where . $orderby;

		return $query;
	}
}
