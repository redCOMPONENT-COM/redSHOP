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

class shipping_ratecontroller extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $post = $this->input->getArray($_POST);
        $this->setRedirect('index.php?option=' . $post['option'] . '&view=shipping_detail&task=edit&cid[]=' . $post['id']);
    }
}
