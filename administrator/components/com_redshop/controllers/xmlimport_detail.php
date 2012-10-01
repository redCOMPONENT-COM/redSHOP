<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class xmlimport_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'xmlimport';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($import = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $post['xmlimport_id'] = $cid [0];
        $model                = $this->getModel('xmlimport_detail');

        if ($post['xmlimport_id'] == 0)
        {
            $post['xmlimport_date'] = time();
        }
        $row = $model->store($post, $import);
        if ($row)
        {
            if ($import == 1)
            {
                $msg = JText::_('COM_REDSHOP_XMLIMPORT_FILE_SUCCESSFULLY_SYNCHRONIZED');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_XMLIMPORT_DETAIL_SAVED');
            }
        }
        else
        {
            if ($import == 1)
            {
                $msg = JText::_('COM_REDSHOP_ERROR_XMLIMPORT_FILE_SYNCHRONIZED');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_ERROR_SAVING_XMLIMPORT_DETAIL');
            }
        }
        $this->setRedirect('index.php?option=' . $option . '&view=xmlimport', $msg);
    }

    public function auto_syncpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE'));
        }
        $model = $this->getModel('xmlimport_detail');
        if (!$model->auto_syncpublish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_ENABLE_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=xmlimport', $msg);
    }

    public function auto_syncunpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_AUTO_SYNCHRONIZE'));
        }
        $model = $this->getModel('xmlimport_detail');
        if (!$model->auto_syncpublish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_AUTO_SYNCHRONIZE_DISABLE_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=xmlimport', $msg);
    }
}
