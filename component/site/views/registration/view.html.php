<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

class registrationViewregistration extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');

		if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
		{
			$app->Redirect('index.php?option=' . $option . '&view=account&Itemid=' . $Itemid);
		}

		$params = $app->getParams('com_redshop');
		JHTML::Script('joomla.javascript.js', 'includes/js/', false);
		JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.metadata.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('registration.js', 'components/com_redshop/assets/js/', false);
		JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');

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
