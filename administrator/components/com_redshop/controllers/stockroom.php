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

class stockroomController extends RedshopCoreController
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function listing()
    {
        $this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0');
    }
}
