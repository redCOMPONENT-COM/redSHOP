<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Product Mini Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class product_miniController extends JControllerLegacy
{
    /**
     * cancel function
     *
     * @access public
     * @return void
     */
    function cancel()
    {
        $this->setRedirect('index.php');
    }
}
