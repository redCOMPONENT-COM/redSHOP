<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class sample_catalogController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		JRequest::setVar('view', 'sample_catalog');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
	}
}
