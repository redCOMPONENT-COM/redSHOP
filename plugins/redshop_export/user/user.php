<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Export;
use Redshop\Ajax\Response;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export User
 *
 * @since  1.0
 */
class PlgRedshop_ExportUser extends Export\AbstractBase
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
	public function onAjaxUser_Config()
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
	public function onAjaxUser_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$headers = $this->getHeader();

		if (!empty($headers))
		{
			// Init temporary folder
			Redshop\Filesystem\Folder\Helper::create($this->getTemporaryFolder());
			$this->writeData($headers, 'w+');
		}

		$response = new Response;
		$data = new stdClass;

		// Total rows for exporting
		$data->rows = (int) $this->getTotal();

		// Limit rows percent request
		$data->limit = $this->limit;
		$data->total = ceil($data->rows / $data->limit);

		return $response->setData($data)->success()->respond();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxUser_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;

		return $this->exporting($input->getInt('from', 0) * $this->limit, $this->limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxUser_Complete()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->convertFile();
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
			->select(
				array(
					$this->db->qn('ui.users_info_id'),
					$this->db->qn('sg.shopper_group_name'),
					'IFNULL(u.id,ui.user_id) as id',
					'IFNULL(u.email,ui.user_email) as email',
					$this->db->qn('u.username'),
					$this->db->qn('u.name'),
					'"" as password'
				)
			)
			->select('GROUP_CONCAT(' . $this->db->qn('ug.group_id') . ' SEPARATOR ' . $this->db->quote(',') . ') AS ' . $this->db->qn('usertype'))
			->select(
				array(
					$this->db->qn('u.block'),
					$this->db->qn('u.sendEmail'),
					$this->db->qn('ui.company_name'),
					$this->db->qn('ui.firstname'),
					$this->db->qn('ui.lastname'),
					$this->db->qn('ui.vat_number'),
					$this->db->qn('ui.tax_exempt'),
					$this->db->qn('ui.shopper_group_id'),
					$this->db->qn('ui.country_code'),
					$this->db->qn('ui.address'),
					$this->db->qn('ui.city'),
					$this->db->qn('ui.state_code'),
					$this->db->qn('ui.zipcode'),
					$this->db->qn('ui.tax_exempt_approved'),
					$this->db->qn('ui.approved'),
					$this->db->qn('ui.is_company'),
					$this->db->qn('ui.phone')
				)
			)
			->from($this->db->qn('#__redshop_users_info', 'ui'))
			->where($this->db->qn('ui.address_type') . ' = ' . $this->db->quote('BT'))
			->leftjoin(
				$this->db->qn('#__users', 'u') . ' ON ' . $this->db->qn('u.id') . ' = ' . $this->db->qn('ui.user_id')
			)
			->innerJoin(
				$this->db->qn('#__user_usergroup_map', 'ug') . ' ON ' . $this->db->qn('ug.user_id') . ' = ' . $this->db->qn('ui.user_id')
			)
			->leftjoin(
				$this->db->qn('#__redshop_shopper_group', 'sg')
				. ' ON ' . $this->db->qn('sg.shopper_group_id') . ' = ' . $this->db->qn('ui.shopper_group_id')
			)
			->group($this->db->qn('ui.user_id'));
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
			'users_info_id', 'shopper_group_name', 'id', 'email', 'username', 'name', 'password', 'usertype', 'block', 'sendEmail', 'company_name',
			'firstname', 'lastname', 'vat_number', 'tax_exempt', 'shopper_group_id', 'country_code', 'address', 'city', 'state_code', 'zipcode',
			'tax_exempt_approved', 'approved', 'is_company', 'phone'
		);
	}
}
