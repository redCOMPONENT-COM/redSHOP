<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'cart.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'user.php');

jimport('joomla.application.component.controller');
/**
 * Order Detail Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class epayrelayController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);

	}


}    ?>
