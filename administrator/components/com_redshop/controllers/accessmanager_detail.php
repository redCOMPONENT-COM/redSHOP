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

class accessmanager_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        //JRequest::setVar('view', 'answer_detail');
        $this->input->set('view', 'answer_detail');
        //JRequest::setVar('layout', 'default');
        $this->input->set('layout', 'default');
        //JRequest::setVar('hidemainmenu', 1);
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function save($apply)
    {
        //$post = JRequest::get('post');
        $post = $this->input->get('post');
        //$option  = JRequest::getVar('option', '', 'request', 'string');
        $option = $this->input->getString('option', '');
        //$section = JRequest::getVar('section', '', 'request', 'string');
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

    public function apply()
    {
        $this->save(1);
    }

    public function cancel()
    {
        //$option = JRequest::getVar('option');
        $option = $this->input->get('option');
        $msg    = JText::_('COM_REDSHOP_ACCESS_LEVEL_CANCEL');
        $this->setRedirect('index.php?option=' . $option . '&view=accessmanager', $msg);
    }
}
