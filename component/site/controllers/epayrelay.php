<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/extra_field.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';
include_once JPATH_COMPONENT . '/helpers/cart.php';
include_once JPATH_COMPONENT . '/helpers/user.php';

JLoader::import('joomla.application.component.controller');

/**
 * Order Detail Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class EpayrelayController extends JController
{
}
