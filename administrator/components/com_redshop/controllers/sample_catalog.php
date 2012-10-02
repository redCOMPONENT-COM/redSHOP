<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class sample_catalogController extends RedshopCoreControllerDefault
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->input->set('view', 'sample_catalog');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
    }
}
