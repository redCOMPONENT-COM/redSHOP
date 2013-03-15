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

require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php';
class checkoutViewcheckout extends JView
{
	function display($tpl = null)
	{
		$user =& JFactory::getUser();


		parent::display("checkoutfinal");
	}
}
