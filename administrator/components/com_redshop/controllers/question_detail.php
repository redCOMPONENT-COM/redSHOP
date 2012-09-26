<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class question_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'question_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save($send = 0)
    {
        $post             = $this->input->getArray($_POST);
        $question         = $this->input->post->getString('question', '');
        $post["question"] = $question;
        $option           = $this->input->getString('option', '');
        $cid              = $this->input->post->get('cid', array(0), 'array');

        $post['question_id'] = $cid [0];
        $model               = $this->getModel('question_detail');

        if ($post['question_id'] == 0)
        {
            $post['question_date'] = time();
            $post['parent_id']     = 0;
        }

        $row = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUESTION_DETAIL');
        }

        if ($send == 1)
        {
            $model->sendMailForAskQuestion($row->question_id);
        }

        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    public function send()
    {
        $this->save(1);
    }

    public function remove()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('question_detail');
        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    public function removeanswer()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('aid', array(0), 'array');
        $qid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('question_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=question_detail&task=edit&cid[]=' . $qid[0], $msg);
    }

    public function sendanswer()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('aid', array(0), 'array');
        $qid    = $this->input->post->get('cid', array(0), 'array');

        for ($i = 0; $i < count($cid); $i++)
        {
            $redshopMail = new redshopMail();
            $redshopMail->sendAskQuestionMail($cid[$i]);
        }

        $msg = JText::_('COM_REDSHOP_ANSWER_MAIL_SENT');
        $this->setRedirect('index.php?option=' . $option . '&view=question_detail&task=edit&cid[]=' . $qid[0], $msg);
    }

    public function cancel()
    {
        $option = $this->input->getString('option', '');

        $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('question_detail');

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('question_detail');

        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    /**
     * logic for orderup
     *
     * @access public
     * @return void
     */
    public function orderup()
    {
        $option = $this->input->get('option');

        $model = $this->getModel('question_detail');
        $model->orderup();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    /**
     * logic for orderdown
     *
     * @access public
     * @return void
     */
    public function orderdown()
    {
        $option = $this->input->get('option');

        $model = $this->getModel('question_detail');
        $model->orderdown();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }

    /**
     * logic for save an order
     *
     * @access public
     * @return void
     */
    public function saveorder()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(), 'array');
        $order  = $this->input->post->get('order', array(), 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('question_detail');
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }
}
