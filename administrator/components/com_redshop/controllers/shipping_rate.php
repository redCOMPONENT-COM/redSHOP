<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class shipping_ratecontroller extends JControllerLegacy
{
    function cancel()
    {
        $post = JRequest::get('post');
        $this->setRedirect('index.php?option=' . $post['option'] . '&view=shipping_detail&task=edit&cid[]=' . $post['id']);
    }
}
