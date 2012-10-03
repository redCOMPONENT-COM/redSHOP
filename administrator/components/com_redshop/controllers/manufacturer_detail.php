<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class manufacturer_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'manufacturer';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                      = $this->input->getArray($_POST);
        $post["manufacturer_desc"] = $this->input->post->getString('manufacturer_desc', '');
        $option                    = $this->input->get('option');
        $cid                       = $this->input->post->get('cid', array(0), 'array');

        $post ['manufacturer_id'] = $cid [0];

        $model = $this->getModel('manufacturer_detail');

        if ($row = $model->store($post))
        {

            $field = new extra_field();
            $field->extra_field_save($post, "10", $row->manufacturer_id); /// field_section 6 :Userinformations

            $msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_MANUFACTURER_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=manufacturer_detail&task=edit&cid[]=' . $row->manufacturer_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
        }
    }
}
