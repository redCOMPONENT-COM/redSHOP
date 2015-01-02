<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRegistration extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$Itemid = JRequest::getInt('Itemid');

		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');

		if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
		{
			$app->redirect('index.php?option=com_redshop&view=account&Itemid=' . $Itemid);
		}

		$params = $app->getParams('com_redshop');
		JHtml::_('redshopjquery.framework');
		JHtml::script('com_redshop/jquery.validate.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/jquery.metadata.js', false, true);
		JHtml::script('com_redshop/registration.js', false, true);
		JHtml::stylesheet('com_redshop/validation.css', array(), true);

		$field                        = new extraField;

		// Field_section 7 : Customer Registration
		$lists['extra_field_user']    = $field->list_all_field(7);

		// Field_section 8 : Company Address
		$lists['extra_field_company'] = $field->list_all_field(8);

		$this->lists = $lists;
		$this->params = $params;
		parent::display($tpl);
	}
}
