<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'extra_field.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'user.php');
class registrationModelregistration extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function store(&$data)
	{
		$userhelper = new rsUserhelper();
		$captcha    = $userhelper->checkCaptcha($data);
		if (!$captcha)
		{
			return false;
		}
		$joomlauser = $userhelper->createJoomlaUser($data, 1);
		if (!$joomlauser)
		{
			return false;
		}
		$data['billisship'] = 1;
		$reduser            = $userhelper->storeRedshopUser($data, $joomlauser->id);

		return $reduser;
	}
}
