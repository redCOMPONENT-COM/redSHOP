<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
include_once JPATH_COMPONENT . '/helpers/user.php';

/**
 * Class registrationModelregistration
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class registrationModelregistration extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function store(&$data)
	{
		$userhelper = new rsUserhelper;
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
