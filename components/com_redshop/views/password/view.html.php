<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class passwordViewpassword extends JViewLegacy
{
    public function display($tpl = null)
    {
        $layout = JRequest::getVar('layout');
        $uid    = JRequest::getInt('uid', 0);

        if ($uid != 0)
        {
            $this->setLayout('setpassword');
        }
        else
        {
            if ($layout == 'token')
            {
                $this->setLayout('token');
            }
            else
            {
                $this->setLayout('default');
            }
        }
        parent::display($tpl);
    }
}
