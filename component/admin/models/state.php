<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelState extends RedshopModel
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
		$id .= ':' . $this->getState('country_id_filter');
		$id .= ':' . $this->getState('country_main_filter');

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
	protected function populateState($ordering = 'state_id', $direction = '')
	{
		$country_id_filter = $this->getUserStateFromRequest($this->context . '.country_id_filter', 'country_id_filter', 0);
		$country_main_filter = $this->getUserStateFromRequest($this->context . '.country_main_filter', 'country_main_filter', '');

		$this->setState('country_id_filter', $country_id_filter);
		$this->setState('country_main_filter', $country_main_filter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$country_id_filter = $this->getState('country_id_filter');
		$country_main_filter = $this->getState('country_main_filter');
		$andcondition = '1=1';
		$country_main_filter = addslashes($country_main_filter);

		if ($country_id_filter > 0 && $country_main_filter == '')
		{
			$andcondition = 'c.id = ' . $country_id_filter;
		}

		elseif ($country_id_filter > 0 && $country_main_filter != '')
		{
			$andcondition = "c.id = " . $country_id_filter . " and (s.state_name like '" . $country_main_filter . "%' || s.state_3_code = '"
				. $country_main_filter . "' || s.state_2_code = '" . $country_main_filter . "')";
		}
		elseif ($country_id_filter == 0 && $country_main_filter != '')
		{
			$andcondition = "s.state_name like '" . $country_main_filter . "%' || s.state_3_code = '" . $country_main_filter
				. "' || s.state_2_code='" . $country_main_filter . "'";
		}
		$query = 'SELECT distinct(s.state_id),s . * , c.country_name FROM `#__redshop_state` AS s '
			. 'LEFT JOIN #__redshop_country AS c ON s.country_id = c.id WHERE ' . $andcondition . $orderby;

		return $query;
	}

	public function getCountryName($country_id)
	{
		$query = "SELECT  c.country_name from #__redshop_country AS c where c.id=" . $country_id;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
}
