<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'extra_field.php' );
include_once (JPATH_COMPONENT.DS.'helpers'.DS.'user.php');
class registrationModelregistration extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	function store(&$data)
	{
		$userhelper = new rsUserhelper();
		$captcha =  $userhelper->checkCaptcha($data);
		if(!$captcha)
		{
			return false;
		}
		$joomlauser =  $userhelper->createJoomlaUser($data,1);
		if(!$joomlauser)
		{
			return false;
		}
		$data['billisship'] = 1;
		$reduser =  $userhelper->storeRedshopUser($data,$joomlauser->id);
		return $reduser;
	}
}?>