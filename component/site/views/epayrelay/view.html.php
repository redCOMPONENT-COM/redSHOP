<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');


require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';

class epayrelayViewepayrelay extends JView
{
	public function display($tpl = null)
	{
		global $mainframe;

		parent::display($tpl);
	}
}
