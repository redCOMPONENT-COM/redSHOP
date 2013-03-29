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

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php';

class epayrelayViewepayrelay extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		parent::display($tpl);
	}
}
