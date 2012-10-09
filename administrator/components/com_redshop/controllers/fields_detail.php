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

class RedshopControllerFields_detail extends RedshopCoreController
{
    public $redirectViewName = 'fields';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post               = $this->input->getArray($_POST);
        $post["field_desc"] = $this->input->post->getString('field_desc', '');
        $option             = $this->input->get('option');
        $cid                = $this->input->post->get('cid', array(0), 'array');

        $post['field_name'] = strtolower($post['field_name']);

        $post['field_name'] = str_replace(" ", "_", $post['field_name']);

        list($key) = explode("_", $post['field_name']);

        if ($key != 'rs')
        {
            $post['field_name'] = "rs_" . $post['field_name'];
        }

        $post ['field_id'] = $cid [0];

        $model = $this->getModel('fields_detail');

        $fieldexists = $model->checkFieldname($post['field_name'], $post ['field_id']);

        if ($fieldexists)
        {
            $msg = JText::_('COM_REDSHOP_FIELDS_ALLREADY_EXIST');
            $this->setRedirect('index.php?option=' . $option . '&view=fields_detail&task=edit&cid[]=' . $cid[0], $msg);
            return;
        }
        else if ($row = $model->store($post))
        {
            if ($post["field_type"] == 0 || $post["field_type"] == 1 || $post["field_type"] == 2)
            {
                $aid[] = $row->field_id;
                $model->field_delete($aid, 'field_id');
            }
            else
            {
                $model->field_save($row->field_id, $post);
            }

            $msg = JText::_('COM_REDSHOP_FIELDS_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_FIELDS_DETAIL');
        }
        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=fields_detail&task=edit&cid[]=' . $row->field_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
        }
    }
}
