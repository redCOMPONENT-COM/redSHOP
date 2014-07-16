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

/**
 * sample_catalogController
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class sample_catalogController extends JController
{
	/**
	 * __construct
	 *
	 * @param $default
	 *
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		JRequest::setVar('view', 'sample_catalog');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
	}
}
