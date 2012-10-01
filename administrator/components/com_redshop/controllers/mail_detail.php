<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class mail_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'mail';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post              = $this->input->getArray($_POST);
        $post["mail_body"] = $this->input->post->getString('mail_body', '');
        $option            = $this->input->get('option');
        $cid               = $this->input->post->get('cid', array(0), 'array');

        $post ['mail_id'] = $cid [0];

        if ($post['mail_section'] != 'order_status')
        {
            $post['mail_order_status'] = 0;
        }

        $model = $this->getModel('mail_detail');
        $row   = $model->store($post);

        if ($row)
        {

            $msg = JText::_('COM_REDSHOP_MAIL_DETAIL_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_MAIL_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=mail_detail&task=edit&cid[]=' . $row->mail_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=mail', $msg);
        }
    }

    public function mail_section()
    {
        $model = $this->getModel('mail_detail');

        $order_status     = $model->mail_section();
        $order_statusHtml = $model->order_statusHtml($order_status);

        $json = array();

        $json['order_status']     = $order_status;
        $json['order_statusHtml'] = $order_statusHtml;

        $encoded = json_encode($json);
        die($encoded);
    }
}
