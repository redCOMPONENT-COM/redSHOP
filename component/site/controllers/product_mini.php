<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Product Mini Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerProduct_mini extends RedshopController
{
    /**
     * cancel function
     *
     * @access public
     * @return void
     */
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    /**
     * @param   bool   $cachable
     * @param   array  $urlparams
     *
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = array())
    {
        parent::display($cachable, $urlparams);
    }
}
