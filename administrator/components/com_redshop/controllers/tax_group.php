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

class tax_groupController extends RedshopCoreController
{
    public function cancel()
    {
        $option = JRequest::getVar('option');
        $this->setRedirect('index.php?option=' . $option . '&view=tax_group');
    }
}
