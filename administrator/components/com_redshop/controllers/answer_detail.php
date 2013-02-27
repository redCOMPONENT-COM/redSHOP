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

class RedshopControllerAnswer_detail extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');

        // Set the redirect view name.
        $parent_id              = $this->input->get('parent_id');
        $this->redirectViewName = 'answer&parent_id=' . $parent_id;
    }

    public function save($send = 0)
    {
        $post      = $this->input->getArray($_POST);
        $question  = $this->input->post->getString('question', '');
        $option    = $this->input->getString('option', '');
        $cid       = $this->input->post->get('cid', array(0), 'array');
        $parent_id = $this->input->get('parent_id');

        $post["question"]    = $question;
        $post['question_id'] = $cid [0];

        $model = $this->getModel('answer_detail');

        if ($post['question_id'] == 0)
        {
            $post['question_date'] = time();
        }

        $row = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_ANSWER_DETAIL_SAVED');
        }

        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_ANSWER_DETAIL');
        }

        if ($send == 1)
        {
            $model->sendMailForAskQuestion($row->question_id);
        }

        $this->setRedirect('index.php?option=' . $option . '&view=answer&parent_id=' . $parent_id, $msg);
    }
}
