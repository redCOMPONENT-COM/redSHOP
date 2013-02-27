<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');

class checkoutViewcheckout extends JViewLegacy
{
    function display($tpl = null)
    {
        parent::display("checkoutfinal");
    }
}
