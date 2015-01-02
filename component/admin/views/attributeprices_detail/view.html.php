<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminShopper');

class RedshopViewAttributeprices_detail extends RedshopView
{
	public function display($tpl = null)
	{
		$this->lists       = array();
		$this->detail      = $this->get('data');
		$model             = $this->getModel('attributeprices_detail');
		$this->property    = $model->getPropertyName();
		$this->request_url = JFactory::getURI()->toString();

		$shoppergroup   = new shoppergroup;
		$this->lists['shopper_group_name'] = $shoppergroup->list_all(
										"shopper_group_id",
										0,
										array($this->detail->shopper_group_id),
										1,
										true,
										false
									);

		parent::display($tpl);
	}
}
