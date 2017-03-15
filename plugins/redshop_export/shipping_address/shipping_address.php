<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Shipping_Address
 *
 * @since  1.0
 */
class PlgRedshop_ExportShipping_Address extends AbstractExportPlugin
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 *
	 * @TODO: Need to load XML File instead
	 */
	public function onAjaxShipping_Address_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShipping_Address_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->writeData($this->getHeader(), 'w+');

		return (int) $this->getTotal();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShipping_Address_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShipping_Address_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  1.0.0
	 */
	protected function getQuery()
	{
		return $this->db->getQuery(true)
			->select('IFNULL(' . $this->db->qn('u.email') . ',' . $this->db->qn('ui.user_email') . ') AS ' . $this->db->qn('email'))
			->select(
				$this->db->qn(
					array(
						'u.username', 'ui.company_name', 'ui.firstname', 'ui.lastname', 'ui.address', 'ui.city', 'ui.state_code',
						'ui.zipcode', 'ui.country_code', 'ui.phone'
					)
				)
			)
			->from($this->db->qn('#__redshop_users_info', 'ui'))
			->leftJoin(
				$this->db->qn('#__users', 'u') . ' ON ' . $this->db->qn('u.id') . ' = ' . $this->db->qn('ui.user_id')
			)
			->where($this->db->qn('ui.address_type') . ' = ' . $this->db->quote('ST'));
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array|bool
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'email', 'username', 'company_name', 'firstname', 'lastname', 'address', 'city', 'state_code', 'zipcode', 'country_code', 'phone'
		);
	}
}
