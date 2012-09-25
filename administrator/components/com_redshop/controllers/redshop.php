<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class redshopController extends JControllerLegacy
{
    function demoContentInsert()
    {
        $model = $this->getModel();

        $model->demoContentInsert();
        $msg = JText::_('COM_REDSHOP_SAMPLE_DATA_INSTALLED');

        $this->setRedirect('index.php?option=com_redshop', $msg);
    }
}
