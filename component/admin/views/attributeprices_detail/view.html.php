<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class attributeprices_detailVIEWattributeprices_detail extends JView
{
	public function display($tpl = null)
	{
		$uri = JFactory::getURI();

		$lists = array();
		$detail = $this->get('data');

		$model = $this->getModel('attributeprices_detail');
		$property = $model->getPropertyName();
		$shoppergroup = $model->getShopperGroup();

		$lists['shopper_group_name'] = JHTML::_('select.genericlist', $shoppergroup, 'shopper_group_id',
			'class="inputbox" size="1"', 'value', 'text', $detail->shopper_group_id
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->property = $property;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
