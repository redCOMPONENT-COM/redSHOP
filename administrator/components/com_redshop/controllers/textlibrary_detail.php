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

class textlibrary_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'textlibrary';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post               = $this->input->getArray($_POST);
        $text_field         = $this->input->post->getString('text_field', '');
        $post["text_field"] = $text_field;
        $option             = $this->input->getString('option', '');
        $cid                = $this->input->post->get('cid', array(0), 'array');

        $post ['textlibrary_id'] = $cid [0];

        $model = $this->getModel('textlibrary_detail');

        if ($row = $model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_TEXTLIBRARY_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEXTLIBRARY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=textlibrary_detail&task=edit&cid[]=' . $row->textlibrary_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=textlibrary', $msg);
        }
    }
}

