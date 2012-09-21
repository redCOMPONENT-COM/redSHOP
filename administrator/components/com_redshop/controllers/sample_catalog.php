<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

class sample_catalogController extends JController
{
    function __construct($default = array())
    {
        parent::__construct($default);
        JRequest::setVar('view', 'sample_catalog');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 1);
        //parent::display ();
    }
}
