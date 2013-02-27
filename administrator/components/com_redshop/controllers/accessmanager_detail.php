<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class RedshopControllerAccessmanager_detail extends RedshopCoreController
{
    public $redirectViewName = 'accessmanager';

    public function __construct($default = array())
    {
        parent::__construct($default);
        //$this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post    = $this->input->getArray($_POST);
        $option  = $this->input->getString('option', '');
        $section = $this->input->getString('section', '');

        $model = $this->getModel('accessmanager_detail');
        $row   = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_ACCESS_LEVEL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_ACCESS_LEVEL_SAVED');
        }
        if ($apply)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=accessmanager_detail&section=' . $section, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=accessmanager', $msg);
        }
    }
}
