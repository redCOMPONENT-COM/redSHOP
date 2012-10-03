<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class RedshopControllerTax_group extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $option = $this->input->get('option');
        $this->setRedirect('index.php?option=' . $option . '&view=tax_group');
    }
}
