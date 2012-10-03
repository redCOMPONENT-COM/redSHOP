<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'extra_field.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'user.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class registrationModelregistration extends RedshopCoreModel
{
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
