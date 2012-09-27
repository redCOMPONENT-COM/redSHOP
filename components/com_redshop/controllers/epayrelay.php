<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'cart.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'user.php');

/**
 * epayrelayController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class epayrelayController extends RedshopCoreController
{
}
